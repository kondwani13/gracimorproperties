<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Mail\PaymentConfirmation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LencoService
{
    protected $publicKey;
    protected $secretKey;
    protected $apiUrl;
    protected $webhookSecret;

    public function __construct()
    {
        $this->publicKey = config('services.lenco.public_key');
        $this->secretKey = config('services.lenco.secret_key');
        $this->apiUrl = config('services.lenco.api_url');
        $this->webhookSecret = config('services.lenco.webhook_secret');
    }

    /**
     * Convert USD to ZMW (Zambian Kwacha)
     */
    protected function convertUsdToZmw(float $usdAmount): float
    {
        // Exchange rate: 1 USD = ~27 ZMW (as of 2024)
        // You can make this configurable in .env if needed
        $exchangeRate = config('services.lenco.exchange_rate', 27.0);
        return round($usdAmount * $exchangeRate, 2);
    }

    /**
     * Generate unique payment reference
     */
    protected function generateUniqueReference(Booking $booking): string
    {
        // Create unique reference: booking_number + timestamp + random string
        return $booking->booking_number . '-' . time() . '-' . substr(md5(uniqid()), 0, 6);
    }

    /**
     * Initialize mobile money payment collection
     */
    public function initializeMobileMoneyPayment(Booking $booking, string $phone, string $operator, string $country = 'zm')
    {
        try {
            $url = $this->apiUrl . '/collections/mobile-money';

            // Convert USD amount to ZMW for Lenco payment
            $amountInZmw = $this->convertUsdToZmw($booking->total_amount);

            // Generate unique reference for this payment attempt
            $uniqueReference = $this->generateUniqueReference($booking);

            $payload = [
                'amount' => floatval($amountInZmw),
                'reference' => $uniqueReference,
                'phone' => $phone,
                'operator' => $operator, // airtel, mtn (zm), tnm (mw)
                'country' => $country, // zm (Zambia), mw (Malawi)
                'bearer' => 'merchant', // merchant pays fees
            ];

            // Log the request details
            Log::info('Lenco Mobile Money Payment Request', [
                'url' => $url,
                'payload' => $payload,
                'original_usd_amount' => $booking->total_amount,
                'converted_zmw_amount' => $amountInZmw,
            ]);

            $response = Http::timeout(90)->withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, $payload);

            // Log the response details
            Log::info('Lenco Mobile Money Payment Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Create pending payment record
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'transaction_id' => $data['data']['lencoReference'] ?? $booking->booking_number,
                    'payment_method' => 'lenco_mobile_money',
                    'amount' => $booking->total_amount,
                    'currency' => 'ZMW',
                    'status' => 'pending',
                    'payment_details' => json_encode($data),
                ]);

                return [
                    'success' => true,
                    'payment' => $payment,
                    'data' => $data,
                    'reference' => $data['data']['lencoReference'] ?? $booking->booking_number,
                    'message' => 'Please authorize the payment on your mobile phone',
                ];
            }

            Log::error('Lenco mobile money payment failed', [
                'response' => $response->json(),
                'status' => $response->status(),
            ]);

            throw new \Exception($response->json()['message'] ?? 'Failed to initialize mobile money payment');
        } catch (\Exception $e) {
            Log::error('Lenco mobile money payment error', [
                'message' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            throw new \Exception('Mobile money payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Initialize card payment collection (Coming soon - not yet active)
     */
    public function initializeCardPayment(Booking $booking, array $cardData)
    {
        try {
            $url = $this->apiUrl . '/collections/card';

            // Convert USD amount to ZMW for Lenco payment
            $amountInZmw = $this->convertUsdToZmw($booking->total_amount);

            // Generate unique reference for this payment attempt
            $uniqueReference = $this->generateUniqueReference($booking);

            // Prepare payload for card payment
            $payload = [
                'reference' => $uniqueReference,
                'email' => $booking->user->email,
                'amount' => strval($amountInZmw),
                'currency' => 'ZMW',
                'bearer' => 'merchant',
                'customer' => [
                    'firstName' => $cardData['firstName'] ?? $booking->user->name,
                    'lastName' => $cardData['lastName'] ?? '',
                ],
                'billing' => [
                    'streetAddress' => $cardData['streetAddress'] ?? $booking->user->address ?? '',
                    'city' => $cardData['city'] ?? $booking->user->city ?? '',
                    'state' => $cardData['state'] ?? $booking->user->state ?? '',
                    'postalCode' => $cardData['postalCode'] ?? $booking->user->postal_code ?? '',
                    'country' => $cardData['country'] ?? 'ZM',
                ],
                'card' => [
                    'number' => $cardData['number'],
                    'cvv' => $cardData['cvv'],
                    'expiryMonth' => $cardData['expiryMonth'],
                    'expiryYear' => $cardData['expiryYear'],
                ],
                'redirectUrl' => route('payments.lenco.callback', ['booking' => $booking->id]),
            ];

            Log::info('Lenco Card Payment Request', [
                'url' => $url,
                'reference' => $payload['reference'],
                'original_usd_amount' => $booking->total_amount,
                'converted_zmw_amount' => $amountInZmw,
            ]);

            $response = Http::timeout(90)->withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, $payload);

            Log::info('Lenco Card Payment Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Create pending payment record
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'transaction_id' => $data['data']['lencoReference'] ?? $booking->booking_number,
                    'payment_method' => 'lenco_card',
                    'amount' => $booking->total_amount,
                    'currency' => 'ZMW',
                    'status' => 'pending',
                    'payment_details' => json_encode($data),
                ]);

                // Check if 3DS authentication is required
                $redirectUrl = null;
                if (isset($data['data']['status']) && $data['data']['status'] === '3ds-auth-required') {
                    $redirectUrl = $data['data']['meta']['authorization']['redirect'] ?? null;
                }

                return [
                    'success' => true,
                    'payment' => $payment,
                    'data' => $data,
                    'authorization_url' => $redirectUrl,
                    'reference' => $data['data']['lencoReference'] ?? $booking->booking_number,
                ];
            }

            Log::error('Lenco card payment failed', [
                'response' => $response->json(),
                'status' => $response->status(),
            ]);

            throw new \Exception($response->json()['message'] ?? 'Failed to initialize card payment');
        } catch (\Exception $e) {
            Log::error('Lenco card payment error', [
                'message' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            throw new \Exception('Card payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Initialize payment - wrapper method for backwards compatibility
     */
    public function initializePayment(Booking $booking, string $paymentType = 'mobile_money', array $paymentData = [])
    {
        if ($paymentType === 'card') {
            return $this->initializeCardPayment($booking, $paymentData);
        }

        // Default to mobile money
        $phone = $paymentData['phone'] ?? '';
        $operator = $paymentData['operator'] ?? 'airtel';
        $country = $paymentData['country'] ?? 'zm';

        return $this->initializeMobileMoneyPayment($booking, $phone, $operator, $country);
    }

    /**
     * Verify a payment transaction
     */
    public function verifyPayment(string $reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Accept' => 'application/json',
            ])->get($this->apiUrl . '/collections/' . $reference);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => $data,
                    'status' => $data['data']['status'] ?? 'unknown',
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to verify payment',
            ];
        } catch (\Exception $e) {
            Log::error('Lenco payment verification error', [
                'message' => $e->getMessage(),
                'reference' => $reference,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process successful payment
     */
    public function processSuccessfulPayment(Payment $payment, array $paymentData)
    {
        try {
            // Update payment record
            $payment->update([
                'status' => 'completed',
                'payment_details' => json_encode($paymentData),
                'paid_at' => now(),
            ]);

            // Update booking status
            $payment->booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);

            // Send confirmation email (optional - won't fail if email view missing)
            try {
                Mail::to($payment->user->email)->send(
                    new PaymentConfirmation($payment->booking, $payment)
                );
            } catch (\Exception $emailError) {
                Log::warning('Could not send payment confirmation email', [
                    'payment_id' => $payment->id,
                    'error' => $emailError->getMessage(),
                ]);
            }

            Log::info('Payment completed successfully', [
                'payment_id' => $payment->id,
                'booking_number' => $payment->booking->booking_number,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error processing successful Lenco payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Process failed payment
     */
    public function processFailedPayment(Payment $payment, string $reason = null)
    {
        $payment->update([
            'status' => 'failed',
            'failure_reason' => $reason ?? 'Payment failed',
        ]);

        $payment->booking->update([
            'payment_status' => 'failed',
        ]);
    }

    /**
     * Handle Lenco webhook
     */
    public function handleWebhook(array $payload, string $signature = null)
    {
        try {
            // Verify webhook signature if provided
            if ($signature && $this->webhookSecret) {
                if (!$this->verifyWebhookSignature($payload, $signature)) {
                    Log::warning('Invalid Lenco webhook signature');
                    return ['success' => false, 'message' => 'Invalid signature'];
                }
            }

            $event = $payload['event'] ?? null;
            $data = $payload['data'] ?? null;

            if (!$event || !$data) {
                return ['success' => false, 'message' => 'Invalid webhook payload'];
            }

            // Find payment by reference
            $reference = $data['reference'] ?? null;
            $lencoReference = $data['lencoReference'] ?? null;

            if (!$reference && !$lencoReference) {
                return ['success' => false, 'message' => 'No reference provided'];
            }

            // Try to find payment by lencoReference first (stored in transaction_id)
            $payment = null;
            if ($lencoReference) {
                $payment = Payment::where('transaction_id', $lencoReference)
                    ->whereIn('payment_method', ['lenco_mobile_money', 'lenco_card', 'lenco'])
                    ->first();
            }

            // If not found, try to find by original reference (booking number pattern)
            if (!$payment && $reference) {
                // Extract booking number from reference (e.g., "BK-YTXPPF8WKB-1770815664-305877" -> "BK-YTXPPF8WKB")
                if (preg_match('/^(BK-[A-Z0-9]+)-/', $reference, $matches)) {
                    $bookingNumber = $matches[1];

                    // Find the most recent payment for this booking
                    $payment = Payment::whereHas('booking', function($query) use ($bookingNumber) {
                        $query->where('booking_number', $bookingNumber);
                    })
                    ->whereIn('payment_method', ['lenco_mobile_money', 'lenco_card', 'lenco'])
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->first();
                }
            }

            if (!$payment) {
                Log::warning('Lenco webhook: Payment not found', [
                    'reference' => $reference,
                    'lencoReference' => $lencoReference
                ]);
                return ['success' => false, 'message' => 'Payment not found'];
            }

            // Handle different webhook events
            switch ($event) {
                case 'collection.successful':
                case 'payment.successful':
                case 'collection.settled': // When payment is fully settled
                    $this->processSuccessfulPayment($payment, $data);
                    break;

                case 'collection.failed':
                case 'payment.failed':
                    $reason = $data['failure_reason'] ?? $data['message'] ?? 'Payment failed';
                    $this->processFailedPayment($payment, $reason);
                    break;

                default:
                    Log::info('Lenco webhook: Unhandled event', ['event' => $event]);
            }

            return ['success' => true, 'message' => 'Webhook processed'];
        } catch (\Exception $e) {
            Log::error('Lenco webhook error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Verify webhook signature
     */
    protected function verifyWebhookSignature(array $payload, string $signature): bool
    {
        // Implement signature verification based on Lenco's documentation
        // This is a placeholder - adjust based on actual Lenco webhook signature method
        $computedSignature = hash_hmac('sha512', json_encode($payload), $this->webhookSecret);
        return hash_equals($computedSignature, $signature);
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(string $reference)
    {
        $verification = $this->verifyPayment($reference);

        if ($verification['success']) {
            return $verification['status'];
        }

        return 'unknown';
    }

    /**
     * Initiate refund (if supported by Lenco)
     */
    public function initiateRefund(Payment $payment, float $amount, string $reason)
    {
        try {
            // Note: Check Lenco API documentation for refund endpoint
            // This is a placeholder implementation
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/refunds', [
                'transaction_reference' => $payment->transaction_id,
                'amount' => $amount,
                'reason' => $reason,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $payment->update([
                    'status' => 'refunded',
                    'refund_amount' => $amount,
                    'refunded_at' => now(),
                ]);

                $payment->booking->update([
                    'status' => 'refunded',
                    'payment_status' => 'refunded',
                ]);

                return ['success' => true, 'data' => $data];
            }

            return ['success' => false, 'message' => 'Refund failed'];
        } catch (\Exception $e) {
            Log::error('Lenco refund error', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Refund failed: ' . $e->getMessage());
        }
    }
}
