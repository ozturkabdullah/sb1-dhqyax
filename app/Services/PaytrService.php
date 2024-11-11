<?php

namespace App\Services;

use App\Models\Rental;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaytrService
{
    private $merchantId;
    private $merchantKey;
    private $merchantSalt;
    private $testMode;

    public function __construct()
    {
        $this->merchantId = config('paytr.merchant_id');
        $this->merchantKey = config('paytr.merchant_key');
        $this->merchantSalt = config('paytr.merchant_salt');
        $this->testMode = config('paytr.test_mode');
    }

    public function createPaymentForm(Rental $rental)
    {
        try {
            $merchant_oid = 'RNT-' . $rental->id . '-' . time();
            $email = $rental->user->email;
            $payment_amount = $rental->total_amount * 100; // PayTR kuruş cinsinden istiyor
            
            $user_basket = base64_encode(json_encode([
                [
                    $rental->category->name . ' (' . $rental->start_date->format('d.m.Y') . ' - ' . $rental->end_date->format('d.m.Y') . ')',
                    $rental->total_amount,
                    1
                ],
            ]));

            $user_ip = request()->ip();
            $timeout_limit = "30";
            $debug_on = $this->testMode ? 1 : 0;
            $test_mode = $this->testMode ? 1 : 0;
            $no_installment = 1;
            $max_installment = 0;
            $currency = "TL";

            $hash_str = $this->merchantId . $user_ip . $merchant_oid . $email . $payment_amount .
                $user_basket . $no_installment . $max_installment . $currency . $test_mode;

            $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $this->merchantSalt, $this->merchantKey, true));

            $post_vals = [
                'merchant_id' => $this->merchantId,
                'user_ip' => $user_ip,
                'merchant_oid' => $merchant_oid,
                'email' => $email,
                'payment_amount' => $payment_amount,
                'paytr_token' => $paytr_token,
                'user_basket' => $user_basket,
                'debug_on' => $debug_on,
                'no_installment' => $no_installment,
                'max_installment' => $max_installment,
                'user_name' => $rental->user->name,
                'user_phone' => $rental->invoice->phone,
                'merchant_ok_url' => route('rentals.payment.success', $rental),
                'merchant_fail_url' => route('rentals.payment.failed', $rental),
                'timeout_limit' => $timeout_limit,
                'currency' => $currency,
                'test_mode' => $test_mode,
                'lang' => 'tr'
            ];

            $response = Http::asForm()->post('https://www.paytr.com/odeme/api/get-token', $post_vals);
            $result = $response->json();

            if ($result['status'] === 'success') {
                $rental->payment()->create([
                    'payment_method' => 'credit_card',
                    'transaction_id' => $merchant_oid,
                    'amount' => $rental->total_amount,
                    'status' => 'pending',
                    'payment_details' => [
                        'token' => $result['token'],
                        'test_mode' => $test_mode
                    ]
                ]);

                return $result['token'];
            }

            throw new \Exception($result['reason']);
        } catch (\Exception $e) {
            Log::error('PayTR token oluşturma hatası: ' . $e->getMessage());
            throw $e;
        }
    }

    public function verifyCallback(Request $request)
    {
        try {
            $hash = base64_encode(hash_hmac('sha256', $request->merchant_oid . $this->merchantSalt . 
                $request->status . $request->total_amount, $this->merchantKey, true));

            if ($hash != $request->hash) {
                throw new \Exception('Hash doğrulama hatası!');
            }

            preg_match('/RNT-(\d+)-/', $request->merchant_oid, $matches);
            $rentalId = $matches[1] ?? null;

            if (!$rentalId) {
                throw new \Exception('Geçersiz merchant_oid formatı');
            }

            $rental = Rental::findOrFail($rentalId);
            $payment = $rental->payment;

            if ($request->status === 'success') {
                $payment->update([
                    'status' => 'completed',
                    'payment_details' => array_merge($payment->payment_details ?? [], [
                        'paytr_response' => $request->all()
                    ])
                ]);

                $rental->update(['status' => 'active']);
                event(new PaymentSuccessful($rental));
            } else {
                $payment->update([
                    'status' => 'failed',
                    'payment_details' => array_merge($payment->payment_details ?? [], [
                        'paytr_response' => $request->all()
                    ])
                ]);

                $rental->update(['status' => 'cancelled']);
                event(new PaymentFailed($rental));
            }

            return true;
        } catch (\Exception $e) {
            Log::error('PayTR callback hatası: ' . $e->getMessage());
            throw $e;
        }
    }
}