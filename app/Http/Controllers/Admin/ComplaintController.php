<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Tenant;
use App\Models\Booking;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['tenant', 'booking']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('complainant_name', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
            });
        }

        $complaints = $query->latest()->paginate(20)->withQueryString();

        return view('admin.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $tenants = Tenant::active()->orderBy('name')->get();
        $bookings = Booking::where('status', 'confirmed')
            ->with('user', 'apartment')
            ->latest()
            ->limit(50)
            ->get();

        return view('admin.complaints.create', compact('tenants', 'bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'complainant_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => 'required|in:low,medium,high',
        ]);

        $validated['status'] = 'open';

        Complaint::create($validated);

        return redirect()->route('admin.complaints.index')
            ->with('success', 'Complaint recorded successfully.');
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['tenant.apartment', 'booking.apartment']);

        return view('admin.complaints.show', compact('complaint'));
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'resolution_notes' => 'nullable|string|max:5000',
        ]);

        $complaint->status = $validated['status'];

        if (in_array($validated['status'], ['resolved', 'closed'])) {
            $complaint->resolved_at = now();
            $complaint->resolution_notes = $validated['resolution_notes'] ?? $complaint->resolution_notes;
        }

        if ($validated['resolution_notes']) {
            $complaint->resolution_notes = $validated['resolution_notes'];
        }

        $complaint->save();

        return redirect()->route('admin.complaints.show', $complaint)
            ->with('success', 'Complaint status updated successfully.');
    }
}
