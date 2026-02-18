<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of tenants with search and status filtering.
     */
    public function index(Request $request)
    {
        $query = Tenant::with('apartment');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tenants = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->query());

        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new tenant.
     */
    public function create()
    {
        $apartments = Apartment::orderBy('title')->get();

        return view('admin.tenants.create', compact('apartments'));
    }

    /**
     * Store a newly created tenant in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'id_number' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'apartment_id' => 'nullable|exists:apartments,id',
            'lease_start' => 'nullable|date',
            'lease_end' => 'nullable|date|after_or_equal:lease_start',
            'rent_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,evicted',
            'notes' => 'nullable|string|max:5000',
        ]);

        Tenant::create($validated);

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified tenant with related data.
     */
    public function show(Tenant $tenant)
    {
        $tenant->load([
            'apartment',
            'rentPayments' => function ($query) {
                $query->orderBy('payment_date', 'desc');
            },
            'complaints' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'consentForms' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
        ]);

        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified tenant.
     */
    public function edit(Tenant $tenant)
    {
        $apartments = Apartment::orderBy('title')->get();

        return view('admin.tenants.edit', compact('tenant', 'apartments'));
    }

    /**
     * Update the specified tenant in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'id_number' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'apartment_id' => 'nullable|exists:apartments,id',
            'lease_start' => 'nullable|date',
            'lease_end' => 'nullable|date|after_or_equal:lease_start',
            'rent_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,evicted',
            'notes' => 'nullable|string|max:5000',
        ]);

        $tenant->update($validated);

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }

    /**
     * Soft delete the specified tenant.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant deleted successfully.');
    }
}
