<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\LencoService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    protected $paymentService;
    protected $lencoService;

    public function __construct(PaymentService $paymentService, LencoService $lencoService)
    {
        $this->paymentService = $paymentService;
        $this->lencoService = $lencoService;
    }

    /**
     * Show payment page
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.show', $booking)
                ->with('info', 'This booking has already been paid.');
        }

        $booking->load('apartment');

        return view('payments.show', compact('booking'));
    }

    /**
     * Process payment
     */
    public function process(Request $request, Booking $booking)
    {
        $this->authorize('view', $booking);

        $request->validate([
            'payment_method' => 'required|in:stripe,lenco',
            'stripeToken' => 'required_if:payment_method,stripe',
            'lenco_type' => 'required_if:payment_method,lenco|in:mobile_money,card',
            'phone' => 'required_if:lenco_type,mobile_money',
            'operator' => 'required_if:lenco_type,mobile_money|in:airtel,mtn,tnm',
            'country' => 'nullable|in:zm,mw',
        ]);

        try {
            if ($request->payment_method === 'lenco') {
                $lencoType = $request->lenco_type;

                if ($lencoType === 'mobile_money') {
                    // Initialize mobile money payment
                    $result = $this->lencoService->initializePayment($booking, 'mobile_money', [
                        'phone' => $request->phone,
                        'operator' => $request->operator,
                        'country' => $request->country ?? 'zm',
                    ]);

                    if ($result['success']) {
                        return redirect()->route('payments.show', $booking)
                            ->with('success', $result['message'] ?? 'Payment initiated. Please authorize on your mobile phone.');
                    }
                } elseif ($lencoType === 'card') {
                    // Card payment (coming soon)
                    return redirect()->back()
                        ->with('info', 'Card payments via Lenco will be available soon. Please use mobile money for now.');
                }

                throw new \Exception('Failed to initialize Lenco payment');
            }

            // Process Stripe payment
            $payment = $this->paymentService->processPayment(
                $booking,
                $request->payment_method,
                $request->stripeToken
            );

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Payment successful! Your booking is confirmed.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Payment failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function webhook(Request $request)
    {
        return $this->paymentService->handleWebhook($request);
    }

    /**
     * Show payment success page
     */
    public function success(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking->load(['apartment', 'payment']);

        return view('payments.success', compact('booking'));
    }

    /**
     * Show payment cancelled page
     */
    public function cancelled()
    {
        return view('payments.cancelled');
    }

    /**
     * Handle Lenco payment callback
     */
    public function lencoCallback(Request $request, Booking $booking)
    {
        try {
            $reference = $request->query('reference');

            if (!$reference) {
                return redirect()->route('payments.show', $booking)
                    ->with('error', 'Invalid payment reference');
            }

            // Verify payment with Lenco
            $verification = $this->lencoService->verifyPayment($reference);

            if ($verification['success'] && $verification['status'] === 'success') {
                // Find payment record
                $payment = Payment::where('transaction_id', $reference)
                    ->where('booking_id', $booking->id)
                    ->first();

                if ($payment) {
                    $this->lencoService->processSuccessfulPayment($payment, $verification['data']);

                    return redirect()->route('payments.success', $booking)
                        ->with('success', 'Payment successful! Your booking is confirmed.');
                }
            }

            return redirect()->route('payments.show', $booking)
                ->with('error', 'Payment verification failed. Please try again.');
        } catch (\Exception $e) {
            return redirect()->route('payments.show', $booking)
                ->with('error', 'Payment processing error: ' . $e->getMessage());
        }
    }

    /**
     * Handle Lenco webhook
     */
    public function lencoWebhook(Request $request)
    {
        try {
            $payload = $request->all();
            $signature = $request->header('X-Lenco-Signature');

            $result = $this->lencoService->handleWebhook($payload, $signature);

            return response()->json($result, $result['success'] ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
