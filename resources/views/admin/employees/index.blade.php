@extends('layouts.admin')

@section('title', 'Employees')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Employees</h1>
    <a href="{{ route('admin.employees.create') }}" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md">
        + Add Employee
    </a>
</div>

<!-- Search & Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone..."
               class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
        </select>
        <button type="submit" class="px-6 py-2 bg-brand text-white rounded-md hover:bg-brand-dark">
            Search
        </button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                Clear
            </a>
        @endif
    </form>
</div>

<!-- Employees Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Salary</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hire Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($employees as $employee)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                        @if($employee->email)
                            <div class="text-sm text-gray-500">{{ $employee->email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $employee->position }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $employee->phone ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        K{{ number_format($employee->salary, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $employee->hire_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($employee->status === 'active')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                        @elseif($employee->status === 'inactive')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        @elseif($employee->status === 'terminated')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Terminated</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.employees.show', $employee) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        <a href="{{ route('admin.employees.edit', $employee) }}" class="text-brand hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this employee?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        No employees found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($employees->hasPages())
    <div class="mt-6">
        {{ $employees->links() }}
    </div>
@endif
@endsection

