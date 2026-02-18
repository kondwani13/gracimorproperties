<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentPayment;
use App\Models\Tenant;
use Illuminate\Http\Request;

class RentPaymentController extends Controller
{
    /**
     * Display a listing of rent payments.
     */
    public function index(Request $request)
    {
        $query = RentPayment::with('tenant.apartment');

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(20)->withQueryString();
        $tenants = Tenant::active()->orderBy('name')->get();

        return view('admin.rent-payments.index', compact('payments', 'tenants'));
    }

    /**
     * Show the form for creating a new rent payment.
     */
    public function create()
    {
        $tenants = Tenant::active()->with('apartment')->orderBy('name')->get();

        return view('admin.rent-payments.create', compact('tenants'));
    }

    /**
     * Store a newly created rent payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|date_format:Y-m',
            'payment_method' => 'required|in:cash,mobile_money,bank_transfer',
            'payment_date' => 'required|date',
            'receipt_number' => 'nullable|string|max:255',
            'status' => 'required|in:paid,unpaid,partial',
            'notes' => 'nullable|string|max:1000',
        ]);

        RentPayment::create($validated);

        return redirect()->route('admin.rent-payments.index')
            ->with('success', 'Rent payment recorded successfully.');
    }

    /**
     * Display the specified rent payment.
     */
    public function show(RentPayment $rentPayment)
    {
        $rentPayment->load('tenant.apartment');

        return view('admin.rent-payments.show', compact('rentPayment'));
    }
}
