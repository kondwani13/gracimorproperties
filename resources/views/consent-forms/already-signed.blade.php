<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consent Form Already Signed</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="max-w-lg mx-auto px-4 py-16 text-center">
        <div class="bg-white rounded-lg shadow p-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-6">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">Already Signed</h1>
            <p class="text-gray-600 mb-4">
                This consent form was already signed by <strong>{{ $consentForm->client_name }}</strong>
                on {{ $consentForm->signed_at->format('M d, Y \a\t h:i A') }}.
            </p>
            <p class="text-xs text-gray-500">No further action is required. You may close this page.</p>
        </div>
    </div>
</body>
</html>

