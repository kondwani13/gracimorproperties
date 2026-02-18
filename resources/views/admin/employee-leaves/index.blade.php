@extends('layouts.admin')

@section('title', 'Leave Requests')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Leave Requests</h1>
    <a href="{{ route('admin.employee-leaves.create') }}"
       class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
        Submit Leave Request
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <select name="employee_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Employees</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->name }}
                </option>
            @endforeach
        </select>

        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>

        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Types</option>
            <option value="annual" {{ request('type') == 'annual' ? 'selected' : '' }}>Annual</option>
            <option value="sick" {{ request('type') == 'sick' ? 'selected' : '' }}>Sick</option>
            <option value="personal" {{ request('type') == 'personal' ? 'selected' : '' }}>Personal</option>
        </select>

        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">Filter</button>
            @if(request()->hasAny(['employee_id', 'status', 'type']))
                <a href="{{ route('admin.employee-leaves.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md text-sm font-medium">Clear</a>
            @endif
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($leaveRequests as $leave)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $leave->employee->name }}</div>
                        <div class="text-xs text-gray-500">{{ $leave->employee->position }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $leave->type_color }}-100 text-{{ $leave->type_color }}-800">
                            {{ ucfirst($leave->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $leave->start_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $leave->end_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $leave->start_date->diffInDays($leave->end_date) + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $leave->status_color }}-100 text-{{ $leave->status_color }}-800">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $leave->reason }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        @if($leave->status === 'pending')
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.employee-leaves.update-status', $leave) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="text-green-600 hover:text-green-800 font-medium">Approve</button>
                                </form>
                                <form action="{{ route('admin.employee-leaves.update-status', $leave) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Reject</button>
                                </form>
                            </div>
                        @else
                            <span class="text-gray-400">{{ ucfirst($leave->status) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">No leave requests found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($leaveRequests->hasPages())
    <div class="mt-6">
        {{ $leaveRequests->links() }}
    </div>
@endif
@endsection

