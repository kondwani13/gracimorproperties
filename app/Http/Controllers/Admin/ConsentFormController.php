<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ConsentForm;
use App\Models\Tenant;
use App\Services\ConsentFormService;
use Illuminate\Http\Request;

class ConsentFormController extends Controller
{
    protected $consentFormService;

    public function __construct(ConsentFormService $consentFormService)
    {
        $this->consentFormService = $consentFormService;
    }

    public function index(Request $request)
    {
        $query = ConsentForm::with(['apartment', 'tenant', 'booking.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('client_name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'signed') {
                $query->where('is_signed', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_signed', false);
            }
        }

        $consentForms = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->query());

        return view('admin.consent-forms.index', compact('consentForms'));
    }

    public function adminView(ConsentForm $consentForm)
    {
        $consentForm->load(['apartment', 'tenant', 'booking.user']);

        return view('admin.consent-forms.show', compact('consentForm'));
    }

    public function generateForTenant(Tenant $tenant)
    {
        if (!$tenant->apartment_id) {
            return redirect()->back()->with('error', 'Tenant must be assigned to an apartment before generating a consent form.');
        }

        $consentForm = $this->consentFormService->generateForTenant($tenant);

        return redirect()->route('admin.consent-forms.show', $consentForm)
            ->with('success', 'Consent form generated successfully.');
    }

    public function generateForBooking(Booking $booking)
    {
        $consentForm = $this->consentFormService->generateForBooking($booking);

        return redirect()->route('admin.consent-forms.show', $consentForm)
            ->with('success', 'Consent form generated successfully.');
    }

    public function showSignPage(ConsentForm $consentForm)
    {
        if ($consentForm->is_signed) {
            return view('consent-forms.already-signed', compact('consentForm'));
        }

        $consentForm->load('apartment');

        return view('consent-forms.show', compact('consentForm'));
    }

    public function sign(Request $request, ConsentForm $consentForm)
    {
        if ($consentForm->is_signed) {
            return redirect()->back()->with('error', 'This form has already been signed.');
        }

        $request->validate([
            'agree' => 'required|accepted',
        ]);

        $this->consentFormService->sign($consentForm, $request->ip());

        return view('consent-forms.signed-success', compact('consentForm'));
    }

    public function downloadPdf(ConsentForm $consentForm)
    {
        if ($consentForm->is_signed && $consentForm->pdf_path) {
            $path = storage_path('app/public/' . $consentForm->pdf_path);
            if (file_exists($path)) {
                return response()->download($path, 'consent-form-' . $consentForm->id . '.pdf');
            }
        }

        // Generate on-the-fly if no stored PDF
        $pdf = $this->consentFormService->generatePdf($consentForm);
        return $pdf->download('consent-form-' . $consentForm->id . '.pdf');
    }
}
