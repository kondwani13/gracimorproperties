<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .container {
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 40px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 20px;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .company-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 11px;
            color: #666;
        }

        .invoice-info {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 10px;
        }

        .invoice-details {
            font-size: 11px;
            color: #666;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 10px;
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 5px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }

        .info-row {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #6B7280;
            font-size: 11px;
        }

        .info-value {
            color: #1F2937;
            font-size: 12px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table thead {
            background-color: #F3F4F6;
        }

        .table th {
            padding: 12px 10px;
            text-align: left;
            font-weight: bold;
            color: #1F2937;
            border-bottom: 2px solid #E5E7EB;
            font-size: 11px;
        }

        .table td {
            padding: 10px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 11px;
        }

        .table th:last-child,
        .table td:last-child {
            text-align: right;
        }

        .totals-section {
            margin-top: 20px;
            float: right;
            width: 45%;
        }

        .totals-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .totals-label {
            display: table-cell;
            text-align: left;
            color: #6B7280;
            font-size: 11px;
        }

        .totals-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
            color: #1F2937;
            font-size: 11px;
        }

        .total-final {
            border-top: 2px solid #E5E7EB;
            padding-top: 10px;
            margin-top: 10px;
        }

        .total-final .totals-label {
            font-size: 14px;
            font-weight: bold;
            color: #1F2937;
        }

        .total-final .totals-value {
            font-size: 16px;
            color: #4F46E5;
        }

        .payment-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-completed {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .status-failed {
            background-color: #FEE2E2;
            color: #991B1B;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="company-info">
                    <div class="company-name">Gracimor Properties</div>
                    <div class="company-tagline">Gracimor Hyndland Estate, Lusaka</div>
                </div>
                <div class="invoice-info">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-details">
                        <strong>{{ $invoice_number }}</strong><br>
                        Date: {{ $invoice_date }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <div class="section-title">Bill To</div>
            <div class="info-row">
                <div class="info-label">Customer Name</div>
                <div class="info-value">{{ $user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $user->email }}</div>
            </div>
            @if($user->phone)
            <div class="info-row">
                <div class="info-label">Phone</div>
                <div class="info-value">{{ $user->phone }}</div>
            </div>
            @endif
        </div>

        <!-- Booking Details -->
        <div class="section">
            <div class="section-title">Booking Details</div>
            <div class="info-grid">
                <div class="info-column">
                    <div class="info-row">
                        <div class="info-label">Booking Number</div>
                        <div class="info-value">{{ $booking->booking_number }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Booking Date</div>
                        <div class="info-value">{{ $booking->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Check-in</div>
                        <div class="info-value">{{ $booking->check_in->format('M d, Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Check-out</div>
                        <div class="info-value">{{ $booking->check_out->format('M d, Y') }}</div>
                    </div>
                </div>
                <div class="info-column">
                    <div class="info-row">
                        <div class="info-label">Apartment</div>
                        <div class="info-value">{{ $apartment->title }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Location</div>
                        <div class="info-value">{{ $apartment->city }}, {{ $apartment->state }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Number of Nights</div>
                        <div class="info-value">{{ $booking->number_of_nights }} night{{ $booking->number_of_nights != 1 ? 's' : '' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Number of Guests</div>
                        <div class="info-value">{{ $booking->number_of_guests }} guest{{ $booking->number_of_guests != 1 ? 's' : '' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Details -->
        <div class="section">
            <div class="section-title">Charges</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $apartment->title }} - Accommodation</td>
                        <td>{{ $booking->number_of_nights }} night{{ $booking->number_of_nights != 1 ? 's' : '' }}</td>
                        <td>K{{ number_format($booking->price_per_night, 2) }}/night</td>
                        <td>K{{ number_format($booking->subtotal, 2) }}</td>
                    </tr>
                    @if($booking->cleaning_fee > 0)
                    <tr>
                        <td>Cleaning Fee</td>
                        <td>1</td>
                        <td>K{{ number_format($booking->cleaning_fee, 2) }}</td>
                        <td>K{{ number_format($booking->cleaning_fee, 2) }}</td>
                    </tr>
                    @endif
                    @if($booking->service_fee > 0)
                    <tr>
                        <td>Service Fee</td>
                        <td>1</td>
                        <td>K{{ number_format($booking->service_fee, 2) }}</td>
                        <td>K{{ number_format($booking->service_fee, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <!-- Totals -->
            <div class="clearfix">
                <div class="totals-section">
                    <div class="totals-row">
                        <span class="totals-label">Subtotal:</span>
                        <span class="totals-value">K{{ number_format($booking->subtotal + $booking->cleaning_fee + $booking->service_fee, 2) }}</span>
                    </div>
                    @if($booking->tax_amount > 0)
                    <div class="totals-row">
                        <span class="totals-label">Tax:</span>
                        <span class="totals-value">K{{ number_format($booking->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="totals-row total-final">
                        <span class="totals-label">Total Amount:</span>
                        <span class="totals-value">K{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="section" style="clear: both; padding-top: 20px;">
            <div class="section-title">Payment Information</div>
            <div class="info-grid">
                <div class="info-column">
                    <div class="info-row">
                        <div class="info-label">Payment Status</div>
                        <div class="info-value">
                            <span class="payment-status status-{{ $booking->payment_status }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                    </div>
                    @if($payment)
                    <div class="info-row">
                        <div class="info-label">Payment Method</div>
                        <div class="info-value">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</div>
                    </div>
                    @endif
                </div>
                <div class="info-column">
                    @if($payment && $payment->transaction_id)
                    <div class="info-row">
                        <div class="info-label">Transaction ID</div>
                        <div class="info-value">{{ $payment->transaction_id }}</div>
                    </div>
                    @endif
                    @if($payment && $payment->paid_at)
                    <div class="info-row">
                        <div class="info-label">Payment Date</div>
                        <div class="info-value">{{ $payment->paid_at->format('M d, Y h:i A') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing Gracimor Properties!</p>
            <p>For questions, contact us at info@gracimorproperties.com or 0973 580 350.</p>
            <p style="margin-top: 10px;">This is a computer-generated invoice and does not require a signature.</p>
        </div>
    </div>
</body>
</html>

