<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonationReceiptMail;
use Spatie\Browsershot\Browsershot;
use App\Traits\RecalculatesAllocations;

class StripePaymentController extends Controller
{
    use RecalculatesAllocations;

    public function createCheckoutSession(Request $request)
    {
        try {
            \Log::info('Stripe: Create checkout session called');
            \Log::info('Stripe: Request data', $request->all());

            // Validate request
            $request->validate([
                'donor_name' => 'required|string|max:255',
                'donor_email' => 'required|email|max:255',
                'donor_phone' => 'nullable|string|max:20',
                'amount' => 'required|numeric|min:1',
            ]);

            $amount = $request->amount;
            $donorName = $request->donor_name;
            $donorEmail = $request->donor_email;
            $donorPhone = $request->donor_phone;

            // Check if Stripe keys are set
            if (!config('services.stripe.key') || !config('services.stripe.secret')) {
                \Log::error('Stripe: API keys not configured');
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe is not configured properly.'
                ], 500);
            }

            // Set Stripe API key
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // Use provided donation_id if available, otherwise generate sequential donation ID (DON-0001 format)
            $donationId = $request->input('donation_id') ?? $this->generateDonationId();

            // Create or find a pending donation record
            $user = \App\Models\User::where('user_email', $donorEmail)->first();
            $donation = Donation::where('donation_id', $donationId)->first();
            if (!$donation) {
                $donation = Donation::create([
                    'donation_id' => $donationId,
                    'user_id' => $user ? $user->user_id : null,
                    'donor_name' => $donorName,
                    'donor_email' => $donorEmail,
                    'donor_phone' => $donorPhone,
                    'donation_amount' => $amount,
                    'donation_payment_method' => 'online',
                    'donation_transaction_id' => null,
                    'donation_received_by' => 'Stripe',
                    'donation_status' => 'pending',
                ]);
            }

            \Log::info('Stripe: Donation record created', ['donation_id' => $donationId]);

            // Build success URL with proper parameters
            $successUrl = route('stripe.success') . '?donation_id=' . $donation->donation_id . '&session_id={CHECKOUT_SESSION_ID}';

            // CREATE STRIPE CHECKOUT SESSION
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => [
                            'name' => 'Donation to Kasih Istimewa',
                            'description' => 'Thank you for your generous donation!',
                        ],
                        'unit_amount' => $amount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => route('stripe.cancel'),
                'customer_email' => $donorEmail,
                'customer_creation' => 'always',
                'payment_intent_data' => [
                    'receipt_email' => $donorEmail,
                    'description' => 'Donation to Kasih Istimewa - ' . $donationId,
                    'metadata' => [
                        'donation_id' => $donation->donation_id,
                        'donor_name' => $donorName,
                    ],
                ],
                'metadata' => [
                    'donation_id' => $donation->donation_id,
                    'donor_name' => $donorName,
                    'donor_email' => $donorEmail,
                ],
            ]);


            \Log::info('Stripe: Checkout session created', [
                'session_id' => $checkoutSession->id,
                'donation_id' => $donationId
            ]);

            return response()->json([
                'success' => true,
                'session_id' => $checkoutSession->id,
                'url' => $checkoutSession->url,
                'donation_id' => $donation->donation_id,
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Stripe error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Stripe: Unexpected error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function success(Request $request)
    {
        try {
            $donationId = $request->get('donation_id');
            $sessionId = $request->get('session_id');

            \Log::info('Stripe: Success callback', [
                'donation_id' => $donationId,
                'session_id' => $sessionId,
            ]);

            if (!$donationId || !$sessionId) {
                \Log::error('Stripe: Missing parameters');
                return redirect()->route('user.donate')->with('error', 'Invalid payment response.');
            }

            // Verify the session with Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            $donation = Donation::where('donation_id', $donationId)->first();

            if (!$donation) {
                \Log::error('Stripe: Donation not found', ['donation_id' => $donationId]);
                return redirect()->route('user.donate')->with('error', 'Donation record not found.');
            }

            // Check if payment was successful
            if ($session->payment_status === 'paid') {
                // Retrieve the PaymentIntent to get charge and receipt info
                $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);

                $paymentIntentId = $paymentIntent->id ?? $session->payment_intent;
                $charge = $paymentIntent->charges->data[0] ?? null;
                $chargeId = $charge->id ?? null;
                $receiptUrl = $charge->receipt_url ?? null;

                // Support legacy JSON records for idempotency, then normalize to one ID.
                $existingTransactionId = $donation->donation_transaction_id;
                $legacyTransactionData = json_decode($existingTransactionId ?? '', true);
                $alreadyProcessed = $donation->donation_status === 'success'
                    && ($existingTransactionId === $paymentIntentId
                        || (is_array($legacyTransactionData)
                            && ($legacyTransactionData['payment_intent'] ?? null) === $paymentIntentId));

                if ($alreadyProcessed) {
                    if ($existingTransactionId !== $paymentIntentId) {
                        $donation->donation_transaction_id = $paymentIntentId;
                        $donation->save();
                    }

                    \Log::info('Stripe: Donation already processed', [
                        'donation_id' => $donationId,
                        'payment_intent' => $paymentIntentId,
                    ]);

                    return redirect()->route('stripe.donation.success')->with([
                        'success' => 'Thank you for your donation!',
                        'donation_id' => $donation->donation_id,
                        'amount' => $donation->donation_amount,
                        'transaction_id' => $paymentIntentId,
                        'payment_gateway' => 'Stripe',
                    ]);
                }

                // Store only Stripe's final PaymentIntent transaction ID.
                $donation->donation_status = 'success';
                $donation->donation_transaction_id = $paymentIntentId;
                $donation->save();

                \Log::info('Stripe: Donation successful', [
                    'donation_id' => $donationId,
                    'payment_intent' => $paymentIntentId,
                    'charge_id' => $chargeId,
                    'receipt_url' => $receiptUrl ?? 'N/A',
                ]);

                // Recalculate allocations
                $this->recalculateAllocationsForDonation($donation);

                return redirect()->route('stripe.donation.success')->with([
                    'success' => 'Thank you for your donation!',
                    'donation_id' => $donation->donation_id,
                    'amount' => $donation->donation_amount,
                    'transaction_id' => $paymentIntentId,
                    'payment_gateway' => 'Stripe',
                ]);
                
            } else {
                // Mark as failed only if not already success
                if ($donation->donation_status !== 'success') {
                    $donation->donation_status = 'failed';
                    $donation->save();
                }

                \Log::warning('Stripe: Payment not completed', [
                    'donation_id' => $donationId,
                    'payment_status' => $session->payment_status
                ]);

                return redirect()->route('stripe.donation.failed')->with('error', 'Payment was not completed.');
            }

        } catch (\Exception $e) {
            \Log::error('Stripe: Success handler error: ' . $e->getMessage());
            return redirect()->route('user.donate')->with('error', 'An error occurred processing your donation.');
        }
    }

    public function cancel()
    {
        \Log::info('Stripe: Payment cancelled');
        return redirect()->route('user.donate')->with('error', 'Payment was cancelled.');
    }

    public function showSuccess()
    {
        return view('user.donation.stripe-success');
    }

    public function showFailed()
    {
        return view('user.donation.stripe-failed');
    }

    public function previewReceipt($id = null)
    {
        if ($id) {
            $donation = Donation::find($id);
        } else {
            $donation = Donation::latest()->first();
        }

        if (!$donation) {
            return "Donation not found.";
        }

        $html = view('pdf.wrapper', ['donation' => $donation])->render();
        
        $pdfData = Browsershot::html($html)
            ->setChromePath('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe')
            ->waitUntil('domcontentloaded')
            ->disableHardwareAcceleration()
            ->setNodeBinary('C:\\Program Files\\nodejs\\node.exe')
            ->setNpmBinary('C:\\Program Files\\nodejs\\npm.cmd')
            ->format('A4')
            ->margins(10, 10, 10, 10)
            ->showBackground()
            ->timeout(120)
            ->noSandbox()
            ->pdf();

        return response($pdfData)->header('Content-Type', 'application/pdf');
    }

    /**
     * Generate next donation id in format DON-0001
     */
    private function generateDonationId()
    {
        $row = Donation::select(DB::raw('MAX(CAST(SUBSTRING(donation_id,5) AS UNSIGNED)) as max'))->first();
        $max = $row->max ?? 0;
        $next = intval($max) + 1;
        return 'DON-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}