<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    /**
     * Generate invoice PDF for a booking
     */
    public function generateInvoice(Booking $booking)
    {
        $booking->load(['user', 'apartment', 'payment']);

        $data = [
            'booking' => $booking,
            'user' => $booking->user,
            'apartment' => $booking->apartment,
            'payment' => $booking->payment,
            'invoice_number' => 'INV-' . $booking->booking_number,
            'invoice_date' => now()->format('F d, Y'),
        ];

        $pdf = Pdf::loadView('invoices.template', $data);
        
        return $pdf;
    }

    /**
     * Get invoice data as array
     */
    public function getInvoiceData(Booking $booking)
    {
        return [
            'invoice_number' => 'INV-' . $booking->booking_number,
            'invoice_date' => now()->format('F d, Y'),
            'booking_number' => $booking->booking_number,
            'booking_date' => $booking->created_at->format('F d, Y'),
            
            // Customer details
            'customer_name' => $booking->user->name,
            'customer_email' => $booking->user->email,
            'customer_phone' => $booking->user->phone,
            'customer_address' => $booking->user->full_address,
            
            // Booking details
            'apartment_name' => $booking->apartment->title,
            'apartment_address' => $booking->apartment->full_address,
            'check_in' => $booking->check_in->format('F d, Y'),
            'check_out' => $booking->check_out->format('F d, Y'),
            'nights' => $booking->number_of_nights,
            'guests' => $booking->number_of_guests,
            
            // Pricing
            'price_per_night' => $booking->price_per_night,
            'subtotal' => $booking->subtotal,
            'cleaning_fee' => $booking->cleaning_fee,
            'service_fee' => $booking->service_fee,
            'tax_amount' => $booking->tax_amount,
            'total_amount' => $booking->total_amount,
            
            // Payment details
            'payment_method' => $booking->payment?->payment_method ?? 'N/A',
            'payment_status' => $booking->payment_status,
            'transaction_id' => $booking->payment?->transaction_id ?? 'N/A',
            'payment_date' => $booking->payment?->paid_at?->format('F d, Y') ?? 'N/A',
        ];
    }

    /**
     * Send invoice email
     */
    public function sendInvoiceEmail(Booking $booking)
    {
        $pdf = $this->generateInvoice($booking);
        
        // Implementation would use Laravel's mail system
        // Mail::to($booking->user->email)
        //     ->send(new InvoiceEmail($booking, $pdf));
    }
}
