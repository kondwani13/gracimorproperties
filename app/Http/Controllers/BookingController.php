<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;

    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display user's bookings
     */
    public function index(Request $request)
    {
        $query = auth()->user()->bookings()->with(['apartment.images', 'payment']);

        $status = $request->get('status', 'upcoming');

        switch ($status) {
            case 'upcoming':
                $query->upcoming();
                break;
            case 'current':
                $query->current();
                break;
            case 'past':
                $query->past();
                break;
            case 'cancelled':
                $query->where('status', 'cancelled');
                break;
            case 'all':
                // Show all bookings
                break;
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10)->appends(['status' => $status]);

        return view('bookings.index', compact('bookings', 'status'));
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load(['apartment.images', 'payment', 'review']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show booking form
     */
    public function create(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
        ]);

        $apartment = Apartment::with('images')->findOrFail($request->apartment_id);

        // Check availability
        if (!$apartment->isAvailableForDates($request->check_in, $request->check_out)) {
            return redirect()->back()->with('error', 'This apartment is not available for the selected dates.');
        }

        // Check guest capacity
        if ($request->guests > $apartment->max_guests) {
            return redirect()->back()->with('error', 'This apartment can accommodate a maximum of ' . $apartment->max_guests . ' guests.');
        }

        $pricing = $apartment->calculatePrice($request->check_in, $request->check_out);

        // Pass individual variables for the view
        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $guests = $request->guests;

        return view('bookings.create', compact('apartment', 'pricing', 'checkIn', 'checkOut', 'guests'));
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        try {
            $booking = $this->bookingService->createBooking(
                auth()->user(),
                $validated
            );

            return redirect()->route('payments.show', $booking)
                ->with('success', 'Booking created successfully. Please complete the payment.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show edit booking form (for unpaid bookings only)
     */
    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);

        // Only allow editing unpaid bookings
        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Cannot edit a paid booking. Please cancel and create a new booking if needed.');
        }

        $apartment = $booking->apartment()->with('images')->first();
        $pricing = $apartment->calculatePrice($booking->check_in->format('Y-m-d'), $booking->check_out->format('Y-m-d'));

        // Pass individual variables for the view
        $checkIn = $booking->check_in->format('Y-m-d');
        $checkOut = $booking->check_out->format('Y-m-d');
        $guests = $booking->number_of_guests;

        return view('bookings.edit', compact('booking', 'apartment', 'pricing', 'checkIn', 'checkOut', 'guests'));
    }

    /**
     * Update a booking (for unpaid bookings only)
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        // Only allow updating unpaid bookings
        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Cannot update a paid booking.');
        }

        $validated = $request->validate([
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        try {
            $apartment = $booking->apartment;

            // Check availability (excluding current booking)
            if (!$apartment->isAvailableForDates($validated['check_in'], $validated['check_out'], $booking->id)) {
                return redirect()->back()
                    ->with('error', 'This apartment is not available for the selected dates.')
                    ->withInput();
            }

            // Check guest capacity
            if ($validated['number_of_guests'] > $apartment->max_guests) {
                return redirect()->back()
                    ->with('error', 'This apartment can accommodate a maximum of ' . $apartment->max_guests . ' guests.')
                    ->withInput();
            }

            // Recalculate pricing
            $pricing = $apartment->calculatePrice($validated['check_in'], $validated['check_out']);

            // Update booking
            $booking->update([
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'number_of_guests' => $validated['number_of_guests'],
                'special_requests' => $validated['special_requests'],
                'number_of_nights' => $pricing['number_of_nights'],
                'price_per_night' => $pricing['price_per_night'],
                'subtotal' => $pricing['subtotal'],
                'cleaning_fee' => $pricing['cleaning_fee'],
                'service_fee' => $pricing['service_fee'],
                'tax_amount' => $pricing['tax_amount'],
                'total_amount' => $pricing['total_amount'],
            ]);

            return redirect()->route('payments.show', $booking)
                ->with('success', 'Booking updated successfully. Please complete the payment.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Cancel a booking
     */
    public function cancel(Booking $booking, Request $request)
    {
        $this->authorize('cancel', $booking);

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->bookingService->cancelBooking($booking, $request->cancellation_reason);

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Booking cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Download invoice
     */
    public function downloadInvoice(Booking $booking)
    {
        $this->authorize('view', $booking);

        if ($booking->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Invoice is only available for paid bookings.');
        }

        $pdf = app(\App\Services\InvoiceService::class)->generateInvoice($booking);

        return $pdf->download('invoice-' . $booking->booking_number . '.pdf');
    }

    /**
     * Check booking payment status (API endpoint for polling)
     */
    public function checkStatus(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load('payment');

        return response()->json([
            'booking_id' => $booking->id,
            'booking_number' => $booking->booking_number,
            'status' => $booking->status,
            'payment_status' => $booking->payment_status,
            'payment_method' => $booking->payment?->payment_method,
            'transaction_id' => $booking->payment?->transaction_id,
        ]);
    }
}
