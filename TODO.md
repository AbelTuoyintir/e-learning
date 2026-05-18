# TODO - Paystack integration

- [ ] Step 1: Update `CourseController::completePurchase()` to create `enrollments` as `pending` and redirect to Paystack after initializing transaction
- [ ] Step 2: Add Paystack initialize logic (reference generation, amount handling) and adjust `createEnrollment()` or create a new `createPendingEnrollment()` helper
- [ ] Step 3: Create `app/Http/Controllers/Paystac kPaymentController.php` with webhook verification and idempotent marking enrollment as `paid`
- [ ] Step 4: Update `routes/web.php` with initialize + webhook routes
- [ ] Step 5: Update `resources/views/students/course-checkout.blade.php` form action to call initialize route
- [x] Step 6: Update `config/services.php` with Paystack keys from env

- [x] Step 7: Run sanity checks (`php artisan route:list`) and basic syntax checks (`php -l` on touched files)




