<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'apartment', 'payment']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('apartment', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->where('check_in', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('check_out', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'apartment.images', 'payment', 'review']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,refunded',
        ]);

        $booking->update(['status' => $validated['status']]);

        // Send notification email based on status
        // This would be handled by events/listeners in production

        return redirect()->back()
            ->with('success', 'Booking status updated successfully.');
    }

    /**
     * Cancel booking (admin)
     */
    public function cancel(Request $request, Booking $booking)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        try {
            $this->bookingService->cancelBooking($booking, $request->cancellation_reason);

            return redirect()->back()
                ->with('success', 'Booking cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Process refund
     */
    public function refund(Request $request, Booking $booking)
    {
        if ($booking->payment_status !== 'paid') {
            return redirect()->back()
                ->with('error', 'Only paid bookings can be refunded.');
        }

        $validated = $request->validate([
            'refund_amount' => 'required|numeric|min:0|max:' . $booking->total_amount,
            'refund_reason' => 'required|string|max:500',
        ]);

        try {
            app(\App\Services\PaymentService::class)->processRefund(
                $booking->payment,
                $validated['refund_amount'],
                $validated['refund_reason']
            );

            return redirect()->back()
                ->with('success', 'Refund processed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Refund failed: ' . $e->getMessage());
        }
    }

    /**
     * Export bookings to CSV
     */
    public function export(Request $request)
    {
        $query = Booking::with(['user', 'apartment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('check_in', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('check_out', '<=', $request->date_to);
        }

        $bookings = $query->get();

        $filename = 'bookings_' . now()->format('Y-m-d_His') . '.csv';
        $handle = fopen('php://output', 'w');
        
        ob_start();
        
        // Headers
        fputcsv($handle, [
            'Booking Number',
            'Guest Name',
            'Guest Email',
            'Apartment',
            'Check In',
            'Check Out',
            'Nights',
            'Guests',
            'Total Amount',
            'Status',
            'Payment Status',
            'Created At',
        ]);

        // Data
        foreach ($bookings as $booking) {
            fputcsv($handle, [
                $booking->booking_number,
                $booking->user->name,
                $booking->user->email,
                $booking->apartment->title,
                $booking->check_in->format('Y-m-d'),
                $booking->check_out->format('Y-m-d'),
                $booking->number_of_nights,
                $booking->number_of_guests,
                $booking->total_amount,
                $booking->status,
                $booking->payment_status,
                $booking->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($handle);
        $csv = ob_get_clean();

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
