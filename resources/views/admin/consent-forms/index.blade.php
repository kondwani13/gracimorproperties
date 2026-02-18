@extends('layouts.admin')

@section('title', 'Consent Forms')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Consent Forms</h2>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.consent-forms.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by client name..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
        </div>
        <div>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                <option value="">All Status</option>
                <option value="signed" {{ request('status') == 'signed' ? 'selected' : '' }}>Signed</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">Filter</button>
            <a href="{{ route('admin.consent-forms.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md text-sm font-medium">Clear</a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($consentForms as $form)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $form->client_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($form->tenant_id)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Tenant</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Booking Guest</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $form->apartment->title ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $form->check_in->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        @if($form->is_signed)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Signed</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $form->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('admin.consent-forms.show', $form) }}" class="text-brand hover:text-indigo-800">View</a>
                        <a href="{{ route('admin.consent-forms.pdf', $form) }}" class="text-green-600 hover:text-green-800">PDF</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">No consent forms found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($consentForms->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $consentForms->links() }}
        </div>
    @endif
</div>
@endsection

