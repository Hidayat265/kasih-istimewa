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

class ToyyibpayController extends Controller
{
    use RecalculatesAllocations;

    public function showCheckout()
    {
        return view('checkout');
    }

    public function createBill(Request $request)
    {
        try {
            // 1. Validate inputs
            $request->validate([
                'donor_name' => 'required|string|max:255',
                'donor_email' => 'required|email|max:255',
                'donor_phone' => 'nullable|string|max:20',
                'amount' => 'required|numeric|min:1',
            ]);

            Log::info('ToyyibPay: Create bill called', [
                'donor_name' => $request->donor_name,
                'amount' => $request->amount,
            ]);

            // 2. Use provided donation_id or generate one
            $donationId = $request->input('donation_id') ?? $this->generateDonationId();

            // 3. Save or Update Pending Donation
            $donation = Donation::where('donation_id', $donationId)->first();
            
            if (!$donation) {
                $user = \App\Models\User::where('user_email', $request->donor_email)->first();
                
                $donation = Donation::create([
                    'donation_id' => $donationId,
                    'user_id' => $user ? $user->user_id : null,
                    'donor_name' => $request->donor_name,
                    'donor_email' => $request->donor_email,
                    'donor_phone' => $request->donor_phone,
                    'donation_amount' => $request->amount,
                    'donation_payment_method' => 'online',
                    'donation_received_by' => 'ToyyibPay',
                    'donation_transaction_id' => null,
                    'donation_status' => 'pending',
                ]);
                
                Log::info('ToyyibPay: New donation created', ['donation_id' => $donationId]);
            }

            // 4. Call ToyyibPay API
            $baseUrl = config('services.toyyibpay.uri', 'https://dev.toyyibpay.com');
            $userSecret = config('services.toyyibpay.user_secret');
            $categoryCode = config('services.toyyibpay.category_code');

            // Validate required config values
            if (!$userSecret || !$categoryCode) {
                Log::error('ToyyibPay: Missing configuration for ToyyibPay service', [
                    'userSecret' => $userSecret ? 'set' : 'missing',
                    'categoryCode' => $categoryCode ? 'set' : 'missing'
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'ToyyibPay is not configured. Missing credentials.'
                    ], 500);
                }

                return back()->with('error', 'ToyyibPay is not configured. Missing credentials.');
            }

            $url = rtrim($baseUrl, '/') . '/index.php/api/createBill';

            // Shorten billName to max 30 characters
            $shortName = substr($request->donor_name, 0, 20);
            $billName = 'Donation-' . $shortName . '-' . substr($donationId, -4);
            
            if (strlen($billName) > 30) {
                $billName = substr($billName, 0, 30);
            }

            $billData = [
                'userSecretKey' => $userSecret,
                'categoryCode' => $categoryCode,
                'billName' => $billName,
                'billDescription' => 'Donation to Kasih Istimewa',
                'billPriceSetting' => 1,
                'billPayorInfo' => 1,
                'billAmount' => $request->amount * 100,
                'billReturnUrl' => route('payment.return'),
                'billCallbackUrl' => route('payment.callback'),
                'billExternalReferenceNo' => $donationId,
                'billTo' => $request->donor_name,
                'billEmail' => $request->donor_email,
                'billPhone' => $request->donor_phone ?? '',
                'billSplitPayment' => 0,
                'billMultiPayment' => 0,
                'billPaymentChannel' => 0,
            ];

            Log::info('ToyyibPay: Sending request to API');

            $response = Http::asForm()->post($url, $billData);

            Log::info('ToyyibPay: Response received', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // Decode response
            $responseBody = trim($response->body());
            $result = json_decode($responseBody, true);

            // Check if bill was created successfully
            if ($response->successful() && isset($result[0]['BillCode'])) {
                $billCode = $result[0]['BillCode'];
                $paymentUrl = $baseUrl . '/' . $billCode;
                

                Log::info('ToyyibPay: Bill created successfully', [
                    'bill_code' => $billCode,
                    'payment_url' => $paymentUrl,
                    'donation_id' => $donationId
                ]);

                // Check if this is an AJAX request
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'payment_url' => $paymentUrl,
                        'bill_code' => $billCode,
                        'donation_id' => $donationId,
                    ]);
                }

                // For non-AJAX requests, redirect
                return redirect()->away($paymentUrl);
            }

            // Bill creation failed - provide more diagnostic info
            Log::error('ToyyibPay: Bill creation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'decoded' => $result
            ]);

            $message = 'Bill creation failed. Please check ToyyibPay configuration and API response.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'debug' => $result
                ], 500);
            }

            return back()->with('error', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('ToyyibPay: Validation error', [
                'errors' => $e->errors()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error: ' . $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('ToyyibPay: Connection Error', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not connect to payment gateway: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Could not connect to payment gateway.');
        }
    }

    public function handleCallback(Request $request)
    {
        $statusId = (string) $request->input('status_id');
        $donationId = $request->input('order_id');
        $transactionId = $request->input('transaction_id');

        Log::info('ToyyibPay: Payment response received', [
            'method' => $request->method(),
            'status_id' => $statusId,
            'donation_id' => $donationId,
            'transaction_id' => $transactionId,
        ]);

        $donation = $donationId
            ? Donation::where('donation_id', $donationId)->first()
            : null;

        if ($donation) {
            if ($statusId === '1') {
                $wasAlreadySuccessful = $donation->donation_status === 'success';
                $donation->donation_status = 'success';
                $donation->donation_received_by = 'ToyyibPay';

                // Store only ToyyibPay's final gateway transaction ID.
                if ($transactionId) {
                    $donation->donation_transaction_id = $transactionId;
                }
                $donation->save();

                if (!$wasAlreadySuccessful) {
                    $this->recalculateAllocationsForDonation($donation);
                }

                Log::info('ToyyibPay: Donation successful', [
                    'donation_id' => $donationId,
                    'transaction_id' => $transactionId,
                    'already_processed' => $wasAlreadySuccessful,
                ]);
            } elseif ($statusId === '2' && $donation->donation_status !== 'success') {
                $donation->donation_status = 'pending';
                $donation->save();
            } elseif ($statusId === '3' && $donation->donation_status !== 'success') {
                $donation->donation_status = 'failed';
                $donation->save();
            }
        }

        // ToyyibPay's server callback only needs an acknowledgement.
        if ($request->isMethod('post')) {
            return response('OK', 200);
        }

        // The GET return is the customer's browser and should show a result page.
        if ($statusId === '1' && $donation) {
            return redirect()->route('toyyibpay.donation.success')->with([
                'success' => 'Thank you for your donation!',
                'donation_id' => $donation->donation_id,
                'amount' => $donation->donation_amount,
                'transaction_id' => $transactionId ?: $donation->donation_transaction_id,
                'payment_gateway' => 'ToyyibPay',
            ]);
        }

        $message = $statusId === '2'
            ? 'Your payment is still pending or was cancelled.'
            : 'Payment was unsuccessful. Please try again.';

        if (!$donationId || !$donation) {
            $message = 'We could not verify the ToyyibPay payment response.';
        }

        return redirect()->route('toyyibpay.donation.failed')->with('error', $message);
    }

    public function showSuccess()
    {
        return view('user.donation.stripe-success');
    }

    public function showFailed()
    {
        return view('user.donation.stripe-failed');
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
