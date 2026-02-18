<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consent Form Signed</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="max-w-lg mx-auto px-4 py-16 text-center">
        <div class="bg-white rounded-lg shadow p-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">Consent Form Signed Successfully</h1>
            <p class="text-gray-600 mb-6">
                Thank you, <strong>{{ $consentForm->client_name }}</strong>. Your consent form has been recorded.
            </p>

            <div class="bg-gray-50 rounded-md p-4 text-sm text-left space-y-2 mb-6">
                <div class="flex justify-between">
                    <span class="text-gray-500">Apartment:</span>
                    <span class="font-medium">{{ $consentForm->apartment->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Signed at:</span>
                    <span class="font-medium">{{ $consentForm->signed_at->format('M d, Y h:i A') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Check-in:</span>
                    <span class="font-medium">{{ $consentForm->check_in->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Check-out:</span>
                    <span class="font-medium">{{ $consentForm->check_out->format('M d, Y') }}</span>
                </div>
            </div>

            <p class="text-xs text-gray-500">You may close this page. A copy has been saved for your records.</p>
        </div>
    </div>
</body>
</html>

