@extends('layouts.admin')

@section('title', 'Submit Leave Request')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.employee-leaves.index') }}" class="text-brand hover:text-indigo-800 text-sm font-medium">&larr; Back to Leave Requests</a>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <h2 class="text-xl font-semibold mb-6">Submit Leave Request</h2>

    <form method="POST" action="{{ route('admin.employee-leaves.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Employee -->
            <div class="md:col-span-2">
                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employee <span class="text-red-500">*</span></label>
                <select name="employee_id" id="employee_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="">Select an employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} - {{ $employee->position }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Leave Type <span class="text-red-500">*</span></label>
                <select name="type" id="type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="annual" {{ old('type') == 'annual' ? 'selected' : '' }}>Annual Leave</option>
                    <option value="sick" {{ old('type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                    <option value="personal" {{ old('type') == 'personal' ? 'selected' : '' }}>Personal Leave</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Request Date -->
            <div>
                <label for="request_date" class="block text-sm font-medium text-gray-700 mb-1">Request Date <span class="text-red-500">*</span></label>
                <input type="date" name="request_date" id="request_date" value="{{ old('request_date', now()->format('Y-m-d')) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('request_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- End Date -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('end_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Reason -->
        <div class="mt-6">
            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
            <textarea name="reason" id="reason" rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">{{ old('reason') }}</textarea>
            @error('reason')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6 flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-brand text-white rounded-md hover:bg-brand-dark">
                Submit Request
            </button>
            <a href="{{ route('admin.employee-leaves.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

