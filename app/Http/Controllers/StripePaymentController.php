<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripePaymentController extends Controller
{
    /**
     * Create a Stripe Checkout Session
     */
    public function createCheckoutSession(Request $request)
    {
        try {
            \Log::info('Stripe: Create checkout session called');
            \Log::info('Stripe: Request data', $request->all());

            // Validate request
            $request->validate([
                'donor_name' => 'required|string|max:255',
                'donor_email' => 'required|email|max:255',
                'donor_phone_number' => 'nullable|string|max:20',
                'amount' => 'required|numeric|min:1',
            ]);

            $amount = $request->amount;
            $donorName = $request->donor_name;
            $donorEmail = $request->donor_email;
            $donorPhone = $request->donor_phone_number;

            // Check if Stripe keys are set
            if (!config('services.stripe.key') || !config('services.stripe.secret')) {
                \Log::error('Stripe: API keys not configured');
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe is not configured properly.'
                ], 500);
            }

            // Set Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Generate unique donation ID
            $lastDonation = Donation::orderBy('donation_id', 'desc')->first();
            if (!$lastDonation) {
                $donationId = 'DON-0001';
            } else {
                $number = intval(substr($lastDonation->donation_id, 4));
                $donationId = 'DON-' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
            }

            // Create a pending donation record
            $user = User::where('user_email', $donorEmail)->first();
            
            $donation = Donation::create([
                'donation_id' => $donationId,
                'user_id' => $user ? $user->user_id : null,
                'donor_name' => $donorName,
                'donor_email' => $donorEmail,
                'donor_phone' => $donorPhone,
                'donation_amount' => $amount,
                'donation_payment_method' => 'online',
                'donation_transaction_id' => 'pending_' . $donationId,
                'donation_received_by' => 'Stripe',
                'donation_status' => 'pending',
            ]);

            \Log::info('Stripe: Donation record created', ['donation_id' => $donationId]);

            // Build success URL with proper parameters
            $successUrl = route('stripe.success') . '?donation_id=' . $donation->donation_id . '&session_id={CHECKOUT_SESSION_ID}';

            // ─── CREATE STRIPE CHECKOUT SESSION WITH RECEIPT ────────────────────
            $checkoutSession = Session::create([
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
                'customer_email' => $donorEmail, // ✅ REQUIRED for Stripe to send receipt
                'customer_creation' => 'always',
                'payment_intent_data' => [
                    'receipt_email' => $donorEmail, // ✅ REQUIRED for Stripe to send receipt
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

            // Log the session creation with email confirmation
            \Log::info('Stripe: Checkout session created', [
                'session_id' => $checkoutSession->id,
                'customer_email' => $donorEmail,
                'receipt_email' => $donorEmail,
                'payment_status' => $checkoutSession->payment_status,
            ]);

            // Update donation with session ID
            $donation->donation_transaction_id = $checkoutSession->id;
            $donation->save();

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

    /**
     * Handle successful payment - Let Stripe send the receipt
     */
    public function success(Request $request)
    {
        try {
            $donationId = $request->get('donation_id');
            $sessionId = $request->get('session_id');

            \Log::info('Stripe: Success callback', [
                'donation_id' => $donationId,
                'session_id' => $sessionId,
            ]);

            // Clean the donation_id if it has extra parameters
            if ($donationId && strpos($donationId, '?') !== false) {
                $donationId = explode('?', $donationId)[0];
            }

            if (!$donationId || !$sessionId) {
                \Log::error('Stripe: Missing parameters');
                return redirect()->route('user.donate')->with('error', 'Invalid payment response.');
            }

            // Verify the session with Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);

            \Log::info('Stripe: Session retrieved', [
                'session_id' => $session->id,
                'payment_status' => $session->payment_status,
                'customer_email' => $session->customer_email,
                'receipt_url' => $session->payment_intent->charges->data[0]->receipt_url ?? 'N/A',
            ]);

            // Find the donation
            $donation = Donation::where('donation_id', $donationId)->first();

            if (!$donation) {
                \Log::error('Stripe: Donation not found', ['donation_id' => $donationId]);
                return redirect()->route('user.donate')->with('error', 'Donation record not found.');
            }

            // Check if payment was successful
            if ($session->payment_status === 'paid') {
                // Update donation status
                $donation->donation_status = 'success';
                $donation->donation_transaction_id = $session->payment_intent;
                $donation->save();

                \Log::info('Stripe: Donation successful', [
                    'donation_id' => $donationId,
                    'payment_intent' => $session->payment_intent,
                    'receipt_url' => $session->payment_intent->charges->data[0]->receipt_url ?? 'N/A',
                    'customer_email' => $session->customer_email,
                ]);

                return redirect()->route('stripe.donation.success')->with([
                    'success' => 'Thank you for your donation!',
                    'donation_id' => $donation->donation_id,
                    'amount' => $donation->donation_amount,
                ]);
                
            } else {
                $donation->donation_status = 'failed';
                $donation->save();

                \Log::warning('Stripe: Payment not completed', [
                    'donation_id' => $donationId,
                    'payment_status' => $session->payment_status
                ]);

                return redirect()->route('stripe.donation.failed')->with('error', 'Payment was not completed.');
            }

        } catch (\Exception $e) {
            \Log::error('Stripe: Success handler error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('user.donate')->with('error', 'An error occurred processing your donation.');
        }
    }

    /**
     * Handle payment cancellation
     */
    public function cancel()
    {
        \Log::info('Stripe: Payment cancelled');
        return redirect()->route('user.donate')->with('error', 'Payment was cancelled.');
    }

    /**
     * Show success page
     */
    public function showSuccess()
    {
        return view('user.donation.stripe-success');
    }

    /**
     * Show failed page
     */
    public function showFailed()
    {
        return view('user.donation.stripe-failed');
    }
}