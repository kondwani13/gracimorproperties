<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Mail\PaymentConfirmation;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Refund;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Process payment for a booking
     */
    public function processPayment(Booking $booking, string $paymentMethod, string $token)
    {
        if ($booking->payment_status === 'paid') {
            throw new \Exception('This booking has already been paid.');
        }

        try {
            // Create Stripe charge
            $charge = Charge::create([
                'amount' => $booking->total_amount * 100, // Convert to cents
                'currency' => 'usd',
                'source' => $token,
                'description' => 'Booking ' . $booking->booking_number,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'apartment_id' => $booking->apartment_id,
                ],
            ]);

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'transaction_id' => $charge->id,
                'payment_method' => $paymentMethod,
                'amount' => $booking->total_amount,
                'currency' => 'USD',
                'status' => 'completed',
                'payment_details' => json_encode($charge),
                'receipt_url' => $charge->receipt_url,
                'paid_at' => now(),
            ]);

            // Update booking status
            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);

            // Send confirmation email
            Mail::to($booking->user->email)->send(new PaymentConfirmation($booking, $payment));

            return $payment;
        } catch (\Stripe\Exception\CardException $e) {
            // Card was declined
            $this->createFailedPayment($booking, $paymentMethod, $e->getMessage());
            throw new \Exception('Payment failed: ' . $e->getError()->message);
        } catch (\Exception $e) {
            $this->createFailedPayment($booking, $paymentMethod, $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a failed payment record
     */
    private function createFailedPayment(Booking $booking, string $paymentMethod, string $reason)
    {
        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'transaction_id' => 'FAILED-' . uniqid(),
            'payment_method' => $paymentMethod,
            'amount' => $booking->total_amount,
            'currency' => 'USD',
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);

        $booking->update(['payment_status' => 'failed']);
    }

    /**
     * Process refund
     */
    public function processRefund(Payment $payment, float $amount, string $reason)
    {
        if ($payment->status !== 'completed') {
            throw new \Exception('Only completed payments can be refunded.');
        }

        try {
            // Create Stripe refund
            $refund = Refund::create([
                'charge' => $payment->transaction_id,
                'amount' => $amount * 100, // Convert to cents
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'reason' => $reason,
                    'booking_id' => $payment->booking_id,
                ],
            ]);

            // Update payment record
            $payment->processRefund($amount);

            // Update booking
            $payment->booking->update([
                'status' => 'refunded',
                'payment_status' => 'refunded',
            ]);

            return $payment;
        } catch (\Exception $e) {
            throw new \Exception('Refund failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function handleWebhook($request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            switch ($event->type) {
                case 'charge.succeeded':
                    $this->handleChargeSucceeded($event->data->object);
                    break;
                case 'charge.failed':
                    $this->handleChargeFailed($event->data->object);
                    break;
                case 'charge.refunded':
                    $this->handleChargeRefunded($event->data->object);
                    break;
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Handle successful charge
     */
    private function handleChargeSucceeded($charge)
    {
        $payment = Payment::where('transaction_id', $charge->id)->first();
        
        if ($payment && $payment->status !== 'completed') {
            $payment->markAsCompleted();
            $payment->booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);
        }
    }

    /**
     * Handle failed charge
     */
    private function handleChargeFailed($charge)
    {
        $payment = Payment::where('transaction_id', $charge->id)->first();
        
        if ($payment && $payment->status !== 'failed') {
            $payment->markAsFailed($charge->failure_message);
            $payment->booking->update(['payment_status' => 'failed']);
        }
    }

    /**
     * Handle refunded charge
     */
    private function handleChargeRefunded($charge)
    {
        $payment = Payment::where('transaction_id', $charge->id)->first();
        
        if ($payment && $payment->status !== 'refunded') {
            $refundAmount = $charge->amount_refunded / 100; // Convert from cents
            $payment->processRefund($refundAmount);
            $payment->booking->update([
                'status' => 'refunded',
                'payment_status' => 'refunded',
            ]);
        }
    }

    /**
     * Get payment intent for frontend
     */
    public function createPaymentIntent(Booking $booking)
    {
        try {
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $booking->total_amount * 100,
                'currency' => 'usd',
                'metadata' => [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                ],
            ]);

            return $intent;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create payment intent: ' . $e->getMessage());
        }
    }
}
