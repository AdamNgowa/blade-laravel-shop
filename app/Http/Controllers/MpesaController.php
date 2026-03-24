<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    /**
     * Initiate STK Push
     */
    public function stkPush(Request $request)
    {
        $request->validate([
            'phone'    => ['required', 'regex:/^0[17]\d{8}$/'],
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Format phone (0712... → 254712...)
        $phone = '254' . substr($request->phone, 1);

        // Ensure valid amount
        $amount = max(1, (int) round($order->total));

        // Clean reference
        $reference = 'ORD' . $order->id;

        // Callback URL
        $callbackUrl = config('mpesa.callback_url');

        try {
            /**
             *  STEP 1: Get Access Token
             */
            $credentials = base64_encode(
                config('mpesa.mpesa_consumer_key') . ':' . config('mpesa.mpesa_consumer_secret')
            );

            $tokenResponse = Http::withHeaders([
                'Authorization' => 'Basic ' . $credentials
            ])->get('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');

            if (!$tokenResponse->successful()) {
                Log::error('MPESA TOKEN ERROR', $tokenResponse->json());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to authenticate with M-Pesa'
                ]);
            }

            $accessToken = $tokenResponse['access_token'];

            /**
             * STEP 2: Generate Password
             */
            $timestamp = now()->format('YmdHis');

            $password = base64_encode(
                config('mpesa.shortcode') .
                config('mpesa.passkey') .
                $timestamp
            );

            /**
             *  STEP 3: STK Push Request
             */
            $stkResponse = Http::withToken($accessToken)->post(
                'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
                [
                    "BusinessShortCode" => config('mpesa.shortcode'),
                    "Password" => $password,
                    "Timestamp" => $timestamp,
                    "TransactionType" => "CustomerPayBillOnline",
                    "Amount" => $amount,
                    "PartyA" => $phone,
                    "PartyB" => config('mpesa.shortcode'),
                    "PhoneNumber" => $phone,
                    "CallBackURL" => $callbackUrl,
                    "AccountReference" => $reference,
                    "TransactionDesc" => "Order Payment"
                ]
            );

            $response = $stkResponse->json();

            Log::info('MPESA STK RESPONSE', $response);

            if (isset($response['ResponseCode']) && $response['ResponseCode'] == '0') {

                $order->update([
                    'checkout_request_id' => $response['CheckoutRequestID'],
                    'status' => 'processing',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment prompt sent! Check your phone.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['errorMessage'] ?? 'STK Push failed'
            ]);

        } catch (\Exception $e) {

            Log::error('MPESA STK ERROR', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Try again.'
            ]);
        }
    }

    /**
     * Handle M-Pesa Callback
     */
    public function callback(Request $request)
    {
        Log::info('MPESA CALLBACK', $request->all());

        $data = $request->all();

        if (!isset($data['Body']['stkCallback'])) {
            return response()->json(['message' => 'Invalid callback']);
        }

        $callback = $data['Body']['stkCallback'];

        $resultCode = $callback['ResultCode'];
        $checkoutRequestId = $callback['CheckoutRequestID'];

        $order = Order::where('checkout_request_id', $checkoutRequestId)->first();

        if (!$order) {
            Log::warning('Order not found: ' . $checkoutRequestId);
            return response()->json(['message' => 'Order not found']);
        }

        if ($resultCode == 0) {
            $metadata = $callback['CallbackMetadata']['Item'] ?? [];

            $receipt = collect($metadata)
                ->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;

            $order->update([
                'status' => 'completed',
                'mpesa_receipt_number' => $receipt,
            ]);

            Log::info('PAYMENT SUCCESS', ['order_id' => $order->id]);

        } else {
            $order->update(['status' => 'failed']);
            Log::info('PAYMENT FAILED', ['order_id' => $order->id]);
        }

        return response()->json(['message' => 'Callback received']);
    }
}