<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLeaveRequest;
use Illuminate\Http\Request;

class EmployeeLeaveController extends Controller
{
    /**
     * Display a listing of leave requests with filters.
     */
    public function index(Request $request)
    {
        $query = EmployeeLeaveRequest::with('employee');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $leaveRequests = $query->latest('request_date')->paginate(20)->withQueryString();
        $employees = Employee::orderBy('name')->get();

        return view('admin.employee-leaves.index', compact('leaveRequests', 'employees'));
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('name')->get();

        return view('admin.employee-leaves.create', compact('employees'));
    }

    /**
     * Store a newly created leave request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'request_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:annual,sick,personal',
            'reason' => 'nullable|string|max:1000',
        ]);

        $validated['status'] = 'pending';

        EmployeeLeaveRequest::create($validated);

        return redirect()->route('admin.employee-leaves.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Update the status of a leave request (approve/reject).
     */
    public function updateStatus(Request $request, EmployeeLeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $leaveRequest->update($validated);

        $action = $validated['status'] === 'approved' ? 'approved' : 'rejected';

        return redirect()->back()
            ->with('success', "Leave request {$action} successfully.");
    }
}
