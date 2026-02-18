<?php

namespace App\Services;

use App\Models\Apartment;
use App\Models\Booking;
use App\Models\User;
use App\Mail\BookingConfirmation;
use App\Mail\BookingCancellation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BookingService
{
    /**
     * Create a new booking
     */
    public function createBooking(User $user, array $data)
    {
        $apartment = Apartment::findOrFail($data['apartment_id']);

        // Validate availability
        if (!$apartment->isAvailableForDates($data['check_in'], $data['check_out'])) {
            throw new \Exception('This apartment is not available for the selected dates.');
        }

        // Validate guest capacity
        if ($data['number_of_guests'] > $apartment->max_guests) {
            throw new \Exception('The number of guests exceeds the apartment capacity.');
        }

        // Calculate pricing
        $pricing = $apartment->calculatePrice($data['check_in'], $data['check_out']);

        return DB::transaction(function () use ($user, $apartment, $data, $pricing) {
            $booking = Booking::create([
                'user_id' => $user->id,
                'apartment_id' => $apartment->id,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'number_of_guests' => $data['number_of_guests'],
                'number_of_nights' => $pricing['nights'],
                'price_per_night' => $pricing['price_per_night'],
                'subtotal' => $pricing['subtotal'],
                'cleaning_fee' => $pricing['cleaning_fee'],
                'service_fee' => $pricing['service_fee'],
                'tax_amount' => $pricing['tax'],
                'total_amount' => $pricing['total'],
                'special_requests' => $data['special_requests'] ?? null,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            return $booking;
        });
    }

    /**
     * Confirm a booking
     */
    public function confirmBooking(Booking $booking)
    {
        $booking->confirm();

        // Send confirmation email
        Mail::to($booking->user->email)->send(new BookingConfirmation($booking));

        return $booking;
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(Booking $booking, $reason = null)
    {
        if (!$booking->canBeCancelled()) {
            throw new \Exception('This booking cannot be cancelled.');
        }

        DB::transaction(function () use ($booking, $reason) {
            $booking->cancel($reason);

            // If payment was made, initiate refund
            if ($booking->payment_status === 'paid') {
                $this->processRefund($booking);
            }

            // Send cancellation email
            Mail::to($booking->user->email)->send(new BookingCancellation($booking));
        });

        return $booking;
    }

    /**
     * Process refund for cancelled booking
     */
    private function processRefund(Booking $booking)
    {
        if (!$booking->payment) {
            return;
        }

        $checkInDate = Carbon::parse($booking->check_in);
        $daysUntilCheckIn = now()->diffInDays($checkInDate, false);

        // Refund policy (example)
        // More than 14 days: 100% refund
        // 7-14 days: 50% refund
        // Less than 7 days: No refund
        $refundPercentage = 0;

        if ($daysUntilCheckIn >= 14) {
            $refundPercentage = 100;
        } elseif ($daysUntilCheckIn >= 7) {
            $refundPercentage = 50;
        }

        if ($refundPercentage > 0) {
            $refundAmount = ($booking->total_amount * $refundPercentage) / 100;

            app(PaymentService::class)->processRefund(
                $booking->payment,
                $refundAmount,
                'Booking cancellation'
            );
        }
    }

    /**
     * Complete a booking (after checkout)
     */
    public function completeBooking(Booking $booking)
    {
        if ($booking->check_out > now()) {
            throw new \Exception('Cannot complete booking before checkout date.');
        }

        $booking->complete();
        $booking->apartment->increment('booking_count');

        return $booking;
    }

    /**
     * Get upcoming bookings that need reminders
     */
    public function getUpcomingBookingsForReminders()
    {
        // Get bookings with check-in in 3 days
        return Booking::confirmed()
            ->whereDate('check_in', Carbon::now()->addDays(3)->toDateString())
            ->with(['user', 'apartment'])
            ->get();
    }

    /**
     * Get bookings that should be auto-completed
     */
    public function getBookingsToComplete()
    {
        // Get confirmed bookings where check-out date has passed
        return Booking::confirmed()
            ->whereDate('check_out', '<', Carbon::now()->toDateString())
            ->get();
    }

    /**
     * Auto-complete past bookings
     */
    public function autoCompleteBookings()
    {
        $bookings = $this->getBookingsToComplete();

        foreach ($bookings as $booking) {
            $this->completeBooking($booking);
        }

        return $bookings->count();
    }
}
