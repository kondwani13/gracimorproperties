<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\MaintenanceCost;
use Illuminate\Http\Request;

class MaintenanceCostController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceCost::with('apartment');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('apartment_id')) {
            $query->where('apartment_id', $request->apartment_id);
        }

        $costs = $query->latest('date')->paginate(20)->withQueryString();
        $apartments = Apartment::orderBy('title')->get();

        return view('admin.maintenance.index', compact('costs', 'apartments'));
    }

    public function create()
    {
        $apartments = Apartment::orderBy('title')->get();
        return view('admin.maintenance.create', compact('apartments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'apartment_id' => 'nullable|exists:apartments,id',
            'description' => 'required|string|max:1000',
            'category' => 'required|in:plumbing,electrical,cleaning,general,painting,appliance',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'vendor' => 'nullable|string|max:255',
            'vendor_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        MaintenanceCost::create($validated);

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Maintenance cost recorded successfully.');
    }

    public function edit(MaintenanceCost $maintenance)
    {
        $apartments = Apartment::orderBy('title')->get();
        return view('admin.maintenance.edit', compact('maintenance', 'apartments'));
    }

    public function update(Request $request, MaintenanceCost $maintenance)
    {
        $validated = $request->validate([
            'apartment_id' => 'nullable|exists:apartments,id',
            'description' => 'required|string|max:1000',
            'category' => 'required|in:plumbing,electrical,cleaning,general,painting,appliance',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'vendor' => 'nullable|string|max:255',
            'vendor_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $maintenance->update($validated);

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Maintenance cost updated successfully.');
    }

    public function destroy(MaintenanceCost $maintenance)
    {
        $maintenance->delete();

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Maintenance cost deleted successfully.');
    }
}
