@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Complete Your Payment</h1>
        <p class="text-gray-600 mt-2">Booking ID: {{ $booking->booking_number }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Payment Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-6">Payment Information</h2>

                <!-- Payment Method Selector -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Payment Method</label>
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" id="select-stripe" onclick="selectPaymentMethod('stripe')"
                                class="payment-method-btn active flex items-center justify-center p-4 border-2 border-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                            <svg class="w-8 h-8 mr-2" viewBox="0 0 24 24" fill="#635BFF">
                                <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-2.404 0-5.49-.981-7.876-2.513l-.917 5.628C5.073 22.717 7.405 23.5 10.916 23.5c2.676 0 4.849-.673 6.453-2.002 1.697-1.407 2.554-3.49 2.554-6.196 0-3.905-2.505-5.804-6.947-7.152z"/>
                            </svg>
                            <span class="font-medium">Stripe</span>
                        </button>
                        <button type="button" id="select-lenco" onclick="selectPaymentMethod('lenco')"
                                class="payment-method-btn flex items-center justify-center p-4 border-2 border-gray-300 bg-white rounded-lg hover:border-indigo-400 hover:bg-gray-50 transition">
                            <svg class="w-8 h-8 mr-2" viewBox="0 0 24 24" fill="#000">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/>
                            </svg>
                            <span class="font-medium">Lenco</span>
                        </button>
                    </div>
                </div>

                <!-- Stripe Payment Form -->
                <form id="stripe-form" action="{{ route('payments.process', $booking) }}" method="POST" style="display: block;">
                    @csrf
                    <input type="hidden" name="payment_method" value="stripe">

                    <!-- Card Information -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                        <div id="card-number" class="px-4 py-3 border border-gray-300 rounded-md"></div>
                        <div id="card-number-errors" class="mt-2 text-sm text-red-600"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                            <div id="card-expiry" class="px-4 py-3 border border-gray-300 rounded-md"></div>
                            <div id="card-expiry-errors" class="mt-2 text-sm text-red-600"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CVC</label>
                            <div id="card-cvc" class="px-4 py-3 border border-gray-300 rounded-md"></div>
                            <div id="card-cvc-errors" class="mt-2 text-sm text-red-600"></div>
                        </div>
                    </div>

                    <!-- Billing Information -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name</label>
                        <input type="text" name="cardholder_name" value="{{ auth()->user()->name }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Billing Address</label>
                        <input type="text" name="billing_address" value="{{ auth()->user()->address }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    </div>

                    <!-- Stripe Token (hidden) -->
                    <input type="hidden" name="stripeToken" id="stripe-token">

                    <!-- Error Display -->
                    <div id="card-errors" class="mb-4 text-sm text-red-600"></div>

                    <!-- Submit Button -->
                    <button type="submit" id="stripe-submit"
                            class="w-full py-3 bg-brand hover:bg-brand-dark text-white font-semibold rounded-md transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <span id="stripe-button-text">Pay K{{ number_format($booking->total_amount, 2) }}</span>
                        <span id="stripe-spinner" class="hidden">Processing...</span>
                    </button>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        🔒 Your payment is secure and encrypted
                    </p>
                </form>

                <!-- Lenco Payment Form -->
                <form id="lenco-form" action="{{ route('payments.process', $booking) }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="payment_method" value="lenco">

                    <!-- Payment Type Selector -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Payment Type</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <input type="radio" name="lenco_type" value="mobile_money" id="lenco-mobile" checked
                                       class="peer sr-only" onchange="toggleLencoType()">
                                <label for="lenco-mobile"
                                       class="flex flex-col p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-indigo-600 peer-checked:bg-indigo-50 hover:bg-gray-50">
                                    <span class="font-medium text-sm">📱 Mobile Money</span>
                                    <span class="text-xs text-gray-600 mt-1">Airtel, MTN, TNM</span>
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" name="lenco_type" value="card" id="lenco-card" disabled
                                       class="peer sr-only">
                                <label for="lenco-card"
                                       class="flex flex-col p-4 border-2 border-gray-200 rounded-lg cursor-not-allowed bg-gray-100 opacity-60">
                                    <span class="font-medium text-sm">💳 Card Payment</span>
                                    <span class="text-xs text-gray-500 mt-1">Coming Soon</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Money Fields -->
                    <div id="mobile-money-fields">
                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-blue-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-900">Mobile Money Payment</h3>
                                    <p class="mt-1 text-sm text-blue-700">
                                        You'll receive a prompt on your mobile phone to authorize the payment.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <select name="country" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                                <option value="zm">Zambia</option>
                                <option value="mw">Malawi</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Operator</label>
                            <select name="operator" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                                <option value="airtel">Airtel</option>
                                <option value="mtn">MTN</option>
                                <option value="tnm">TNM (Malawi only)</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                            <input type="tel" name="phone" placeholder="e.g., 0977123456" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                            <p class="mt-1 text-xs text-gray-500">Enter your mobile money number</p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="lenco-submit"
                            class="w-full py-3 bg-brand hover:bg-brand-dark text-white font-semibold rounded-md transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <span id="lenco-button-text">Initiate Mobile Money Payment</span>
                        <span id="lenco-spinner" class="hidden">Processing...</span>
                    </button>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        🔒 Secure payment powered by Lenco
                    </p>
                </form>

                <!-- Payment Processing Status (Hidden by default) -->
                <div id="payment-processing-status" class="hidden mt-6 bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 mb-4">
                            <svg class="animate-spin h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Processing Your Payment</h3>
                        <p class="text-sm text-blue-700 mb-4">
                            Please check your phone for the mobile money payment prompt and authorize the payment.
                        </p>
                        <p class="text-xs text-blue-600">
                            This page will automatically update when payment is confirmed...
                        </p>
                    </div>
                </div>
            </div>

            <!-- Security Info -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-900">Secure Payment</h3>
                        <p class="mt-1 text-sm text-blue-700" id="security-text">
                            Your payment information is processed securely. We never store your card or banking details.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Summary Sidebar -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h3 class="text-lg font-semibold mb-4">Booking Summary</h3>

                <!-- Apartment -->
                <div class="mb-4">
                    @if($booking->apartment->images->first())
                        <img src="{{ $booking->apartment->images->first()->url }}"
                             alt="{{ $booking->apartment->title }}"
                             class="w-full h-32 object-cover rounded-md mb-3">
                    @endif
                    <h4 class="font-medium">{{ $booking->apartment->title }}</h4>
                    <p class="text-sm text-gray-600">{{ $booking->apartment->city }}, {{ $booking->apartment->state }}</p>
                </div>

                <hr class="my-4">

                <!-- Dates -->
                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Check-in</span>
                        <span class="font-medium">{{ $booking->check_in->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Check-out</span>
                        <span class="font-medium">{{ $booking->check_out->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Guests</span>
                        <span class="font-medium">{{ $booking->number_of_guests }}</span>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Price Breakdown -->
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">K{{ number_format($booking->price_per_night) }} × {{ $booking->number_of_nights }} nights</span>
                        <span class="font-medium">K{{ number_format($booking->subtotal) }}</span>
                    </div>
                    @if($booking->cleaning_fee)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cleaning fee</span>
                            <span class="font-medium">K{{ number_format($booking->cleaning_fee) }}</span>
                        </div>
                    @endif
                    @if($booking->service_fee)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Service fee</span>
                            <span class="font-medium">K{{ number_format($booking->service_fee) }}</span>
                        </div>
                    @endif
                    @if($booking->tax_amount)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium">K{{ number_format($booking->tax_amount) }}</span>
                        </div>
                    @endif
                </div>

                <hr class="my-4">

                <!-- Total -->
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-lg">Total (USD)</span>
                    <span class="font-bold text-2xl text-brand">K{{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Payment method selection
    let currentPaymentMethod = 'stripe';

    function selectPaymentMethod(method) {
        currentPaymentMethod = method;

        // Update button styles
        const stripeBtn = document.getElementById('select-stripe');
        const lencoBtn = document.getElementById('select-lenco');

        if (method === 'stripe') {
            stripeBtn.classList.add('active', 'border-indigo-600', 'bg-indigo-50');
            stripeBtn.classList.remove('border-gray-300', 'bg-white');
            lencoBtn.classList.remove('active', 'border-indigo-600', 'bg-indigo-50');
            lencoBtn.classList.add('border-gray-300', 'bg-white');

            // Show/hide forms
            document.getElementById('stripe-form').style.display = 'block';
            document.getElementById('lenco-form').style.display = 'none';
        } else {
            lencoBtn.classList.add('active', 'border-indigo-600', 'bg-indigo-50');
            lencoBtn.classList.remove('border-gray-300', 'bg-white');
            stripeBtn.classList.remove('active', 'border-indigo-600', 'bg-indigo-50');
            stripeBtn.classList.add('border-gray-300', 'bg-white');

            // Show/hide forms
            document.getElementById('stripe-form').style.display = 'none';
            document.getElementById('lenco-form').style.display = 'block';
        }
    }

    // Initialize Stripe
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();

    // Create card elements
    const cardNumber = elements.create('cardNumber');
    const cardExpiry = elements.create('cardExpiry');
    const cardCvc = elements.create('cardCvc');

    cardNumber.mount('#card-number');
    cardExpiry.mount('#card-expiry');
    cardCvc.mount('#card-cvc');

    // Handle errors
    cardNumber.on('change', (event) => {
        const displayError = document.getElementById('card-number-errors');
        displayError.textContent = event.error ? event.error.message : '';
    });

    cardExpiry.on('change', (event) => {
        const displayError = document.getElementById('card-expiry-errors');
        displayError.textContent = event.error ? event.error.message : '';
    });

    cardCvc.on('change', (event) => {
        const displayError = document.getElementById('card-cvc-errors');
        displayError.textContent = event.error ? event.error.message : '';
    });

    // Handle Stripe form submission
    const stripeForm = document.getElementById('stripe-form');
    const stripeSubmitButton = document.getElementById('stripe-submit');

    stripeForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        stripeSubmitButton.disabled = true;
        document.getElementById('stripe-button-text').classList.add('hidden');
        document.getElementById('stripe-spinner').classList.remove('hidden');

        const {paymentMethod, error} = await stripe.createPaymentMethod({
            type: 'card',
            card: cardNumber,
            billing_details: {
                name: document.querySelector('[name="cardholder_name"]').value,
                address: {
                    line1: document.querySelector('[name="billing_address"]').value,
                }
            }
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            stripeSubmitButton.disabled = false;
            document.getElementById('stripe-button-text').classList.remove('hidden');
            document.getElementById('stripe-spinner').classList.add('hidden');
        } else {
            document.getElementById('stripe-token').value = paymentMethod.id;
            stripeForm.submit();
        }
    });

    // Handle Lenco form submission
    const lencoForm = document.getElementById('lenco-form');
    const lencoSubmitButton = document.getElementById('lenco-submit');

    lencoForm.addEventListener('submit', (event) => {
        lencoSubmitButton.disabled = true;
        document.getElementById('lenco-button-text').classList.add('hidden');
        document.getElementById('lenco-spinner').classList.remove('hidden');

        // Start polling for payment status after form submission
        setTimeout(() => {
            startPaymentStatusPolling();
        }, 5000); // Start polling after 5 seconds
    });

    // Payment status polling
    let pollInterval = null;
    let pollAttempts = 0;
    const maxPollAttempts = 100; // Poll for up to 5 minutes (100 * 3 seconds = 5 minutes)

    function startPaymentStatusPolling() {
        console.log('Starting payment status polling...');

        // Clear any existing polling
        if (pollInterval) {
            clearInterval(pollInterval);
        }

        // Show processing status indicator
        const processingStatus = document.getElementById('payment-processing-status');
        if (processingStatus) {
            processingStatus.classList.remove('hidden');
            // Scroll to the indicator
            processingStatus.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Hide the payment forms
        document.getElementById('stripe-form').style.display = 'none';
        document.getElementById('lenco-form').style.display = 'none';

        // Poll every 3 seconds
        pollInterval = setInterval(checkPaymentStatus, 3000);

        // Also check immediately
        checkPaymentStatus();
    }

    async function checkPaymentStatus() {
        pollAttempts++;

        try {
            const response = await fetch('{{ route("api.booking.status", $booking) }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error('Failed to check payment status');
            }

            const data = await response.json();
            console.log('Payment status check:', data);

            // Check if payment is completed
            if (data.payment_status === 'paid' || data.status === 'confirmed') {
                clearInterval(pollInterval);
                console.log('Payment confirmed! Redirecting...');

                // Show success message briefly before redirect
                lencoSubmitButton.innerHTML = '<span class="text-green-600">✓ Payment Confirmed! Redirecting...</span>';

                // Redirect to success page
                setTimeout(() => {
                    window.location.href = '{{ route("payments.success", $booking) }}';
                }, 1500);
            }

            // Stop polling after max attempts
            if (pollAttempts >= maxPollAttempts) {
                clearInterval(pollInterval);
                console.log('Polling timeout reached');

                // Hide processing indicator
                const processingStatus = document.getElementById('payment-processing-status');
                if (processingStatus) {
                    processingStatus.classList.add('hidden');
                }

                // Show the Lenco form again
                document.getElementById('lenco-form').style.display = 'block';

                lencoSubmitButton.disabled = false;
                document.getElementById('lenco-button-text').classList.remove('hidden');
                document.getElementById('lenco-spinner').classList.add('hidden');
                document.getElementById('lenco-button-text').textContent = 'Retry Payment';

                // Show message to user
                alert('Payment is taking longer than expected. Please check your phone for the payment prompt or refresh the page to check payment status.');
            }

        } catch (error) {
            console.error('Error checking payment status:', error);

            // Don't stop polling on error, just log it
            if (pollAttempts >= maxPollAttempts) {
                clearInterval(pollInterval);
            }
        }
    }

    // Auto-start polling if we're coming back to this page and payment was already initiated
    window.addEventListener('load', () => {
        // Only auto-start if there's an actual payment record (meaning user already clicked payment button)
        const hasPaymentRecord = {{ $booking->payment ? 'true' : 'false' }};
        const paymentStatus = '{{ $booking->payment_status }}';

        // Only auto-poll if payment was already initiated (has payment record) and is still pending
        if (hasPaymentRecord && paymentStatus === 'pending') {
            console.log('Detected pending payment that was already initiated, starting auto-polling...');
            // Start polling after a short delay
            setTimeout(startPaymentStatusPolling, 2000);
        }
    });

    // Toggle Lenco payment type (mobile money vs card)
    function toggleLencoType() {
        const selectedType = document.querySelector('input[name="lenco_type"]:checked').value;
        const mobileFields = document.getElementById('mobile-money-fields');
        const buttonText = document.getElementById('lenco-button-text');

        if (selectedType === 'mobile_money') {
            mobileFields.style.display = 'block';
            buttonText.textContent = 'Initiate Mobile Money Payment';
        } else {
            mobileFields.style.display = 'none';
            buttonText.textContent = 'Pay with Card (Coming Soon)';
        }
    }
</script>

<style>
    .payment-method-btn {
        cursor: pointer;
        transition: all 0.2s;
    }
    .payment-method-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .payment-method-btn.active {
        box-shadow: 0 4px 12px rgba(99, 91, 255, 0.3);
    }
</style>
@endpush
@endsection

