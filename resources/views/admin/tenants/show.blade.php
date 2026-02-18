@extends('layouts.admin')

@section('title', 'Tenant Details')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.tenants.index') }}" class="text-brand hover:text-indigo-700 text-sm font-medium">&larr; Back to Tenants</a>
    <div class="flex gap-2">
        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">Edit Tenant</a>
        <form action="{{ route('admin.consent-forms.generate-tenant', $tenant) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium">Generate Consent Form</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Personal Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Name:</span>
                <span class="font-medium text-gray-900">{{ $tenant->name }}</span>
            </div>
            @if($tenant->email)
            <div class="flex justify-between">
                <span class="text-gray-500">Email:</span>
                <span class="font-medium text-gray-900">{{ $tenant->email }}</span>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-gray-500">Phone:</span>
                <span class="font-medium text-gray-900">{{ $tenant->phone }}</span>
            </div>
            @if($tenant->id_number)
            <div class="flex justify-between">
                <span class="text-gray-500">ID Number:</span>
                <span class="font-medium text-gray-900">{{ $tenant->id_number }}</span>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-gray-500">Status:</span>
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $tenant->status_color }}-100 text-{{ $tenant->status_color }}-800">
                    {{ ucfirst($tenant->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Lease Details -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Lease Details</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Apartment:</span>
                <span class="font-medium text-gray-900">{{ $tenant->apartment->title ?? 'Not Assigned' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Rent Amount:</span>
                <span class="font-medium text-gray-900">K{{ number_format($tenant->rent_amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Lease Start:</span>
                <span class="font-medium text-gray-900">{{ $tenant->lease_start ? $tenant->lease_start->format('M d, Y') : 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Lease End:</span>
                <span class="font-medium text-gray-900">{{ $tenant->lease_end ? $tenant->lease_end->format('M d, Y') : 'N/A' }}</span>
            </div>
            @if($tenant->emergency_contact_name)
            <div class="flex justify-between">
                <span class="text-gray-500">Emergency Contact:</span>
                <span class="font-medium text-gray-900">{{ $tenant->emergency_contact_name }} ({{ $tenant->emergency_contact_phone }})</span>
            </div>
            @endif
        </div>
    </div>
</div>

@if($tenant->notes)
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-2">Notes</h3>
    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $tenant->notes }}</p>
</div>
@endif

<!-- Rent Payments -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold">Rent Payments</h3>
        <a href="{{ route('admin.rent-payments.create') }}" class="text-sm text-brand hover:text-indigo-800">+ Add Payment</a>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($tenant->rentPayments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($payment->month . '-01')->format('F Y') }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">K{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $payment->status_color }}-100 text-{{ $payment->status_color }}-800">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">No rent payments recorded.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Complaints -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Complaints</h3>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($tenant->complaints as $complaint)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $complaint->subject }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $complaint->priority_color }}-100 text-{{ $complaint->priority_color }}-800">
                            {{ ucfirst($complaint->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $complaint->status_color }}-100 text-{{ $complaint->status_color }}-800">
                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $complaint->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.complaints.show', $complaint) }}" class="text-brand hover:text-indigo-800 text-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-sm">No complaints filed.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Consent Forms -->
@if($tenant->consentForms->count() > 0)
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Consent Forms</h3>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($tenant->consentForms as $form)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $form->check_in->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $form->check_out->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        @if($form->is_signed)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Signed</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.consent-forms.show', $form) }}" class="text-brand hover:text-indigo-800 text-sm">View</a>
                        <a href="{{ route('admin.consent-forms.pdf', $form) }}" class="text-green-600 hover:text-green-800 text-sm">PDF</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection

