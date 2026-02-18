<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $query = Bill::with('apartment');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('apartment_id')) {
            $query->where('apartment_id', $request->apartment_id);
        }

        $bills = $query->latest('due_date')->paginate(20)->withQueryString();
        $apartments = Apartment::orderBy('title')->get();

        return view('admin.bills.index', compact('bills', 'apartments'));
    }

    public function create()
    {
        $apartments = Apartment::orderBy('title')->get();
        return view('admin.bills.create', compact('apartments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'apartment_id' => 'nullable|exists:apartments,id',
            'type' => 'required|in:electricity,water,internet,security,garbage,other',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date',
            'status' => 'required|in:pending,paid,overdue',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        Bill::create($validated);

        return redirect()->route('admin.bills.index')
            ->with('success', 'Bill recorded successfully.');
    }

    public function edit(Bill $bill)
    {
        $apartments = Apartment::orderBy('title')->get();
        return view('admin.bills.edit', compact('bill', 'apartments'));
    }

    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'apartment_id' => 'nullable|exists:apartments,id',
            'type' => 'required|in:electricity,water,internet,security,garbage,other',
            'description' => 'nullable|string|max:1000',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'paid_date' => 'nullable|date',
            'status' => 'required|in:pending,paid,overdue',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $bill->update($validated);

        return redirect()->route('admin.bills.index')
            ->with('success', 'Bill updated successfully.');
    }

    public function destroy(Bill $bill)
    {
        $bill->delete();

        return redirect()->route('admin.bills.index')
            ->with('success', 'Bill deleted successfully.');
    }
}
