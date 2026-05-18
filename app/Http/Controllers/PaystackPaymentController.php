<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PaystackPaymentController extends Controller
{
    public function webhook(Request $request)
    {
        $secret = config('services.paystack.secret_key');

        if (! $secret) {
            Log::error('Paystack webhook failed: missing secret key in config/services.php');
            return response()->json(['error' => 'Paystack not configured'], 500);
        }

        $payload = $request->getContent();
        $eventSignature = $request->header('x-paystack-signature');

        if (! $eventSignature) {
            Log::warning('Paystack webhook failed: missing x-paystack-signature header');
            return response()->json(['error' => 'Missing signature'], 400);
        }

        $hash = hash_hmac('sha512', $payload, $secret);

        if (! hash_equals($hash, $eventSignature)) {
            Log::warning('Paystack webhook failed: invalid signature', [
                'expected' => $hash,
                'provided' => $eventSignature,
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $data = json_decode($payload, true);
        $event = $data['event'] ?? null;
        $trans = $data['data']['transaction'] ?? null;

        if (! $event || ! $trans) {
            Log::warning('Paystack webhook failed: unexpected payload', ['payload' => $data]);
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $reference = $trans['reference'] ?? null;
        $status = $trans['status'] ?? null;
        $amount = $trans['amount'] ?? null;

        if (! $reference) {
            Log::warning('Paystack webhook failed: missing transaction reference', ['data' => $data]);
            return response()->json(['error' => 'Missing reference'], 400);
        }

        // Idempotent update (only mark paid once)
        try {
            DB::transaction(function () use ($reference, $status, $amount) {
                $enrollment = Enrollment::where('payment_reference', $reference)->lockForUpdate()->first();

                if (! $enrollment) {
                    Log::warning('Paystack webhook: no enrollment found for reference', ['reference' => $reference]);
                    return;
                }

                if ($enrollment->payment_status === 'paid') {
                    // Already processed
                    return;
                }

                // Paystack sends status like 'success' / 'failed'
                if ($status === 'success') {
                    $enrollment->payment_status = 'paid';
                    $enrollment->purchased_at = now();

                    // If price_paid was pending, update with verified amount (amount is in kobo)
                    if ($amount !== null) {
                        $enrollment->price_paid = ((float) $amount) / 100;
                    }

                    $enrollment->save();
                } else {
                    // Keep enrollment non-paid unless success.
                    // We'll mark as failed for clarity.
                    $enrollment->payment_status = 'failed';
                    $enrollment->save();
                }
            });
        } catch (\Throwable $e) {
            Log::error('Paystack webhook failed to process enrollment', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Server error'], 500);
        }

        return response()->json(['status' => 'ok']);
    }
}

