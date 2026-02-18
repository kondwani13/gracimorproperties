<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\ConsentForm;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ConsentFormService
{
    public function generateForTenant(Tenant $tenant): ConsentForm
    {
        return ConsentForm::create([
            'tenant_id' => $tenant->id,
            'client_name' => $tenant->name,
            'client_email' => $tenant->email,
            'apartment_id' => $tenant->apartment_id,
            'check_in' => $tenant->lease_start ?? now(),
            'check_out' => $tenant->lease_end ?? now()->addYear(),
            'policies_text' => $this->getPoliciesText(),
        ]);
    }

    public function generateForBooking(Booking $booking): ConsentForm
    {
        $booking->load(['user', 'apartment']);

        return ConsentForm::create([
            'booking_id' => $booking->id,
            'client_name' => $booking->user->name,
            'client_email' => $booking->user->email,
            'apartment_id' => $booking->apartment_id,
            'check_in' => $booking->check_in,
            'check_out' => $booking->check_out,
            'policies_text' => $this->getPoliciesText(),
        ]);
    }

    public function getPoliciesText(): string
    {
        return view('consent-forms.policies-content')->render();
    }

    public function sign(ConsentForm $form, string $ip): ConsentForm
    {
        $form->update([
            'is_signed' => true,
            'signed_at' => now(),
            'signature_ip' => $ip,
        ]);

        // Generate and store the PDF
        $pdf = $this->generatePdf($form);
        $filename = 'consent-forms/consent_' . $form->id . '.pdf';

        Storage::disk('public')->put($filename, $pdf->output());

        $form->update(['pdf_path' => $filename]);

        return $form;
    }

    public function generatePdf(ConsentForm $form): \Barryvdh\DomPDF\PDF
    {
        $form->load(['apartment', 'tenant', 'booking.user']);

        $data = [
            'consentForm' => $form,
            'apartment' => $form->apartment,
        ];

        return Pdf::loadView('consent-forms.pdf', $data);
    }
}
