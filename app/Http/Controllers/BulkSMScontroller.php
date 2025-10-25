<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkSMScontroller extends Controller
{
    public function bulk(){
        try {
            $apiKey   = env('ARKASEL_API_KEY');
            $senderId = env('ARKASEL_SENDER_ID');

            // Get all active recipients
            $recipients = DB::table('bulk_sms_recipients')
                          ->where('is_active', true)
                          ->get();

            $successCount = 0;
            $failCount = 0;
            $failedNumbers = [];

            foreach ($recipients as $recipient) {
                // Your campaign message
                $message = "Vote DABUO JACOB NGMENLANAA (JAKES) for FGMSA CAPACITY BUILDING OFFICER. Let's build an FGMSA that empowers every medical student and leaves no one behind. Leadership with purpose! 💪🏾\n\nThe time is now. Victory is near!\n\nFGMSA, JACOB IS HERE!\nVisit: https://shorturl.at/yahyu";

                // Build the URL
                $url = 'https://sms.arkesel.com/sms/api';
                $query = [
                    'action'  => 'send-sms',
                    'api_key' => $apiKey,
                    'to'      => $recipient->phone_number,
                    'from'    => $senderId,
                    'sms'     => $message,
                ];

                // Send GET request
                $response = \Illuminate\Support\Facades\Http::get($url, $query);

                \Log::debug('Arkasel SMS Sent', [
                    'to' => $recipient->phone_number,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                if ($response->successful()) {
                    $successCount++;
                    \Log::info("Campaign SMS sent to {$recipient->phone_number}");
                } else {
                    $failCount++;
                    $failedNumbers[] = $recipient->phone_number;
                    \Log::error("Failed to send SMS to {$recipient->phone_number}", [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                }

                // Add delay to avoid rate limiting (0.2 seconds between messages)
                usleep(200000);
            }

            return redirect()->back()->with([
                'success' => "Bulk SMS campaign completed! Success: {$successCount}, Failed: {$failCount}",
                'campaign_data' => [
                    'candidate' => 'DABUO JACOB NGMENLANAA (JAKES)',
                    'position' => 'FGMSA CAPACITY BUILDING OFFICER',
                    'total_sent' => $successCount,
                    'total_failed' => $failCount,
                    'failed_numbers' => $failedNumbers
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error("Exception in bulk SMS campaign: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during bulk SMS campaign.');
        }
    }
}
