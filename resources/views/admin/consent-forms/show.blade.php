@extends('layouts.admin')

@section('title', 'Consent Form Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.consent-forms.index') }}" class="text-brand hover:text-indigo-800 text-sm">&larr; Back to Consent Forms</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Client Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Client Information</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Client Name:</span>
                <span class="font-medium text-gray-900">{{ $consentForm->client_name }}</span>
            </div>
            @if($consentForm->client_email)
            <div class="flex justify-between">
                <span class="text-gray-500">Email:</span>
                <span class="font-medium text-gray-900">{{ $consentForm->client_email }}</span>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-gray-500">Type:</span>
                <span>
                    @if($consentForm->tenant_id)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Tenant</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Booking Guest</span>
                    @endif
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Apartment:</span>
                <span class="font-medium text-gray-900">{{ $consentForm->apartment->title ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Check-in:</span>
                <span class="font-medium text-gray-900">{{ $consentForm->check_in->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Check-out:</span>
                <span class="font-medium text-gray-900">{{ $consentForm->check_out->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Signature Status -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Signature Status</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Status:</span>
                @if($consentForm->is_signed)
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Signed</span>
                @else
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending Signature</span>
                @endif
            </div>
            @if($consentForm->is_signed)
            <div class="flex justify-between">
                <span class="text-gray-500">Signed At:</span>
                <span class="font-medium text-gray-900">{{ $consentForm->signed_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">IP Address:</span>
                <span class="font-medium text-gray-900">{{ $consentForm->signature_ip }}</span>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-gray-500">Created:</span>
                <span class="font-medium text-gray-900">{{ $consentForm->created_at->format('M d, Y h:i A') }}</span>
            </div>
        </div>

        <div class="mt-6 space-y-3">
            @if(!$consentForm->is_signed)
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                    <p class="text-sm text-yellow-800 font-medium mb-2">Share this link with the client to sign:</p>
                    <div class="flex items-center gap-2">
                        <input type="text" readonly value="{{ route('consent-forms.sign-page', $consentForm) }}"
                               class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-md bg-white" id="sign-url">
                        <button onclick="navigator.clipboard.writeText(document.getElementById('sign-url').value); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 2000)"
                                class="px-3 py-1.5 bg-brand hover:bg-brand-dark text-white text-xs rounded-md whitespace-nowrap">Copy</button>
                    </div>
                </div>
            @endif

            <a href="{{ route('admin.consent-forms.pdf', $consentForm) }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium">
                Download PDF
            </a>
        </div>
    </div>
</div>

<!-- Policies Content -->
<div class="bg-white rounded-lg shadow p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Policies & Terms</h3>
    <div class="prose prose-sm max-w-none text-gray-700 max-h-96 overflow-y-auto border border-gray-200 rounded-md p-4">
        {!! $consentForm->policies_text !!}
    </div>
</div>
@endsection

