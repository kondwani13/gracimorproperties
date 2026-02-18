<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gracimor Hyndland Estate Policies & Consent Form</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="max-w-3xl mx-auto px-4 py-8" x-data="{ agreed: false }">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-brand">🏠 {{ config('app.name', 'Gracimor Properties') }}</h1>
            <h2 class="text-xl font-semibold text-gray-800 mt-2">Hyndland Estate Policies & Consent Form</h2>
        </div>

        <!-- Client Details -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Guest Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Guest Name:</span>
                    <span class="font-medium text-gray-900 ml-2">{{ $consentForm->client_name }}</span>
                </div>
                @if($consentForm->client_email)
                <div>
                    <span class="text-gray-500">Email:</span>
                    <span class="font-medium text-gray-900 ml-2">{{ $consentForm->client_email }}</span>
                </div>
                @endif
                <div>
                    <span class="text-gray-500">Property:</span>
                    <span class="font-medium text-gray-900 ml-2">{{ $consentForm->apartment->title }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Location:</span>
                    <span class="font-medium text-gray-900 ml-2">{{ $consentForm->apartment->city }}, {{ $consentForm->apartment->state }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Check-in:</span>
                    <span class="font-medium text-gray-900 ml-2">{{ $consentForm->check_in->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Check-out:</span>
                    <span class="font-medium text-gray-900 ml-2">{{ $consentForm->check_out->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Policies -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Policies & Terms</h3>
            <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-md p-4 prose prose-sm text-gray-700">
                {!! $consentForm->policies_text !!}
            </div>
        </div>

        <!-- Agreement Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('consent-forms.sign', $consentForm) }}">
                @csrf

                <div class="flex items-start mb-6">
                    <input type="checkbox" name="agree" id="agree" value="1"
                           x-model="agreed"
                           class="h-5 w-5 text-brand border-gray-300 rounded focus:ring-brand mt-0.5">
                    <label for="agree" class="ml-3 text-sm text-gray-700">
                        I, <strong>{{ $consentForm->client_name }}</strong>, have read, understood, and agree to abide by all the policies and terms stated above during my stay at <strong>{{ $consentForm->apartment->title }}</strong>.
                    </label>
                </div>

                @error('agree')
                    <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
                @enderror

                <button type="submit"
                        :disabled="!agreed"
                        :class="agreed ? 'bg-brand hover:bg-brand-dark cursor-pointer' : 'bg-gray-300 cursor-not-allowed'"
                        class="w-full py-3 text-white font-semibold rounded-md transition">
                    Sign & Submit Consent Form
                </button>
            </form>

            <p class="text-xs text-gray-500 text-center mt-4">
                By signing, your IP address and timestamp will be recorded for verification purposes.
            </p>
        </div>
    </div>
</body>
</html>

