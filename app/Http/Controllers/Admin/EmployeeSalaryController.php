<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeSalaryRecord;
use Illuminate\Http\Request;

class EmployeeSalaryController extends Controller
{
    /**
     * Show the salary payment form for a specific employee.
     */
    public function create(Employee $employee)
    {
        return view('admin.employees.salary-create', compact('employee'));
    }

    /**
     * Store a new salary payment record for the employee.
     */
    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'month' => 'required|date_format:Y-m',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['employee_id'] = $employee->id;

        EmployeeSalaryRecord::create($validated);

        return redirect()->route('admin.employees.show', $employee)
            ->with('success', 'Salary payment recorded successfully.');
    }
}
