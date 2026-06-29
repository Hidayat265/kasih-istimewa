<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationAllocation;
use Illuminate\Http\Request;
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
        // 1. Validate inputs
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone_number' => 'required|string|max:20',
            'amount' => 'required|numeric|min:1',
        ]);

        // 2. Generate ID
        $lastDonation = Donation::orderBy('donation_id', 'desc')->first();
        $number = $lastDonation ? intval(substr($lastDonation->donation_id, 4)) + 1 : 1;
        $donationId = 'DON-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        // 3. Save Pending Donation
        $donation = Donation::create([
            'donation_id' => $donationId,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'donor_phone' => $request->donor_phone_number,
            'donation_amount' => $request->amount,
            'donation_payment_method' => 'online',
            'donation_received_by' => 'ToyyibPay',
            'donation_status' => 'pending',
        ]);

        // 4. Call ToyyibPay API
        $baseUrl = env('TOYYIBPAY_URI', 'https://dev.toyyibpay.com');
        $url = $baseUrl . '/index.php/api/createBill';

        // ─── FIX: Shorten billName to max 30 characters ──────────────────────
        // Take first 20 characters of donor name + donation ID suffix
        $shortName = substr($request->donor_name, 0, 20);
        $billName = 'Donation-' . $shortName . '-' . substr($donationId, -4);
        
        // If still too long, truncate to 30 characters
        if (strlen($billName) > 30) {
            $billName = substr($billName, 0, 30);
        }
        
        // Alternative: Use a simple static name if the above is still too long
        // $billName = 'Donation-' . substr($donationId, -4);

        $billData = [
            'userSecretKey' => env('TOYYIBPAY_USER_SECRET_KEY'),
            'categoryCode' => env('TOYYIBPAY_CATEGORY_CODE'),
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
            'billPhone' => $request->donor_phone_number,
            'billSplitPayment' => 0,
            'billMultiPayment' => 0,
            'billPaymentChannel' => 0,
        ];

        try {

            $response = Http::asForm()->post($url, $billData);

            // Log raw response
            Log::info('ToyyibPay Raw Response:', [
                'body' => $response->body()
            ]);

            // Decode response manually
            $result = json_decode(trim($response->body()), true);

            // Success
            if (isset($result[0]['BillCode'])) {
                $billCode = $result[0]['BillCode'];
                return redirect()->away($baseUrl . '/' . $billCode);
            }

            // Failed response
            Log::error('Bill creation failed', [
                'response' => $result
            ]);

            return back()->with(
                'error',
                'Bill creation failed. Please try again.'
            );

        } catch (\Exception $e) {

            Log::error('ToyyibPay Connection Error', [
                'exception' => $e->getMessage()
            ]);

            return back()->with(
                'error',
                'Could not connect to payment gateway.'
            );
        }
    }

    public function handleCallback(Request $request)
    {
        $statusId = $request->input('status_id');
        $donationId = $request->input('order_id');
        $transactionId = $request->input('transaction_id');

        $donation = Donation::where('donation_id', $donationId)->first();

        if ($donation) {
            // Check success and ensure not already processed
            if ($statusId == 1 && $donation->donation_status !== 'success') {
                
                $donation->donation_status = 'success';
                $donation->donation_received_by = 'ToyyibPay';
                $donation->donation_transaction_id = $transactionId;
                $donation->save();

                // ============================================
                // RECALCULATE ALLOCATIONS FOR THIS MONTH
                // ============================================
                $this->recalculateAllocationsForDonation($donation);

                // --- GENERATE PDF & SEND EMAIL ---
                try {
                    // Use the PDF WRAPPER view, which includes your component
                    $html = view('pdf.wrapper', ['donation' => $donation])->render();

                    $pdfData = Browsershot::html($html)
                        // 1. Point to your REAL Chrome (Use double backslashes \\)
                        ->setChromePath('C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe') 

                        ->waitUntil('domcontentloaded') // <--- CRITICAL: Don't wait for network idle
                        ->disableHardwareAcceleration() // <--- CRITICAL: Prevents GPU crashes on Windows
                        
                        // 2. Keep these Node settings
                        ->setNodeBinary('C:\\Program Files\\nodejs\\node.exe')
                        ->setNpmBinary('C:\\Program Files\\nodejs\\npm.cmd')
                        
                        // 3. Standard settings
                        ->format('A4')
                        ->margins(10, 10, 10, 10)
                        ->showBackground()
                        ->timeout(120)
                        ->noSandbox() 
                        ->pdf();

                    Mail::to($donation->donor_email)
                        ->send(new DonationReceiptMail($donation, $pdfData));

                    Log::info("Receipt sent to {$donation->donor_email}");

                } catch (\Exception $e) {
                    Log::error('Failed to generate/send receipt PDF: ' . $e->getMessage());
                }

            } elseif ($statusId == 2) {
                $donation->donation_status = 'pending';
                $donation->save();
            } elseif ($statusId == 3) {
                $donation->donation_status = 'failed';
                $donation->save();
            }
        }

        $statusText = $statusId == 1 ? 'Donation successful! Thank you.' : 
                      ($statusId == 2 ? 'Payment pending.' : 'Payment failed.');

        return view('paymentGateway.toyyibpayPaymentStatus', [
            'status' => $statusId == 1 ? 'success' : 'failed',
            'message' => $statusText,
            'transaction_id' => $transactionId,
        ]);
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

        // Use the PDF WRAPPER view here too
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
}