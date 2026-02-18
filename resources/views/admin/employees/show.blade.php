@extends('layouts.admin')

@section('title', 'Employee Details')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.employees.index') }}" class="text-brand hover:text-indigo-800 text-sm font-medium">&larr; Back to Employees</a>
    <div class="flex gap-2">
        <a href="{{ route('admin.employees.edit', $employee) }}" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">Edit Employee</a>
        <a href="{{ route('admin.employees.salary.create', $employee) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium">Record Salary Payment</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Employee Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Employee Information</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Name:</span>
                <span class="font-medium text-gray-900">{{ $employee->name }}</span>
            </div>
            @if($employee->email)
            <div class="flex justify-between">
                <span class="text-gray-500">Email:</span>
                <span class="font-medium text-gray-900">{{ $employee->email }}</span>
            </div>
            @endif
            @if($employee->phone)
            <div class="flex justify-between">
                <span class="text-gray-500">Phone:</span>
                <span class="font-medium text-gray-900">{{ $employee->phone }}</span>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-gray-500">Position:</span>
                <span class="font-medium text-gray-900">{{ $employee->position }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Salary:</span>
                <span class="font-medium text-gray-900">K{{ number_format($employee->salary, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Hire Date:</span>
                <span class="font-medium text-gray-900">{{ $employee->hire_date->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status:</span>
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $employee->status_color }}-100 text-{{ $employee->status_color }}-800">
                    {{ ucfirst($employee->status) }}
                </span>
            </div>
        </div>
        @if($employee->notes)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-1">Notes:</p>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $employee->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Summary</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-gray-900">K{{ number_format($employee->salaryRecords->sum('amount'), 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Salary Paid</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $employee->salaryRecords->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Salary Records</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $employee->leaveRequests->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Leave Requests</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $employee->leaveRequests->where('status', 'approved')->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Approved Leaves</p>
            </div>
        </div>
    </div>
</div>

<!-- Salary Records -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold">Salary Records</h3>
        <a href="{{ route('admin.employees.salary.create', $employee) }}" class="text-sm text-brand hover:text-indigo-800">+ Add Record</a>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($employee->salaryRecords as $record)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($record->month . '-01')->format('F Y') }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">K{{ number_format($record->amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $record->payment_method)) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $record->payment_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($record->notes, 50) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">No salary records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Leave Requests -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Leave Requests</h3>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($employee->leaveRequests as $leave)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $leave->type_color }}-100 text-{{ $leave->type_color }}-800">
                            {{ ucfirst($leave->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $leave->start_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $leave->end_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $leave->status_color }}-100 text-{{ $leave->status_color }}-800">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($leave->reason, 50) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">No leave requests found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

