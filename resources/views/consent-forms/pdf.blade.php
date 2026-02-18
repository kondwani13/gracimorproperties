<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consent Form - {{ $consentForm->client_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; line-height: 1.6; color: #333; }
        .container { padding: 40px; max-width: 800px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #4F46E5; padding-bottom: 20px; }
        .company-name { font-size: 22px; font-weight: bold; color: #4F46E5; }
        .form-title { font-size: 16px; font-weight: bold; color: #1F2937; margin-top: 8px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 13px; font-weight: bold; color: #1F2937; margin-bottom: 10px; border-bottom: 1px solid #E5E7EB; padding-bottom: 5px; }
        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; padding: 4px 10px 4px 0; font-weight: bold; color: #6B7280; width: 140px; font-size: 10px; }
        .info-value { display: table-cell; padding: 4px 0; color: #1F2937; font-size: 11px; }
        .policies { margin-top: 15px; }
        .policies h2 { font-size: 14px; color: #1F2937; margin-bottom: 10px; }
        .policies h3 { font-size: 11px; color: #1F2937; margin-top: 12px; margin-bottom: 4px; }
        .policies p { font-size: 10px; color: #4B5563; margin-bottom: 6px; }
        .signature-section { margin-top: 30px; border: 2px solid #E5E7EB; border-radius: 8px; padding: 20px; }
        .signature-title { font-size: 13px; font-weight: bold; color: #1F2937; margin-bottom: 15px; }
        .signature-grid { display: table; width: 100%; }
        .signature-row { display: table-row; }
        .signature-label { display: table-cell; padding: 6px 10px 6px 0; font-weight: bold; color: #6B7280; width: 120px; font-size: 10px; }
        .signature-value { display: table-cell; padding: 6px 0; color: #1F2937; font-size: 11px; }
        .status-signed { display: inline-block; padding: 3px 10px; background-color: #D1FAE5; color: #065F46; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .status-pending { display: inline-block; padding: 3px 10px; background-color: #FEF3C7; color: #92400E; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #E5E7EB; text-align: center; font-size: 9px; color: #6B7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-name">Gracimor Properties</div>
            <div class="form-title">Hyndland Estate Policies & Consent Form</div>
        </div>

        <div class="section">
            <div class="section-title">Guest Information</div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Guest Name</div>
                    <div class="info-value">{{ $consentForm->client_name }}</div>
                </div>
                @if($consentForm->client_email)
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $consentForm->client_email }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Apartment</div>
                    <div class="info-value">{{ $apartment->title }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Location</div>
                    <div class="info-value">{{ $apartment->city }}, {{ $apartment->state }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Check-in Date</div>
                    <div class="info-value">{{ $consentForm->check_in->format('F d, Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Check-out Date</div>
                    <div class="info-value">{{ $consentForm->check_out->format('F d, Y') }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Policies & Terms</div>
            <div class="policies">
                {!! $consentForm->policies_text !!}
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-title">Consent & Signature</div>
            <div class="signature-grid">
                <div class="signature-row">
                    <div class="signature-label">Status</div>
                    <div class="signature-value">
                        @if($consentForm->is_signed)
                            <span class="status-signed">SIGNED</span>
                        @else
                            <span class="status-pending">PENDING</span>
                        @endif
                    </div>
                </div>
                @if($consentForm->is_signed)
                <div class="signature-row">
                    <div class="signature-label">Signed By</div>
                    <div class="signature-value">{{ $consentForm->client_name }}</div>
                </div>
                <div class="signature-row">
                    <div class="signature-label">Signed At</div>
                    <div class="signature-value">{{ $consentForm->signed_at->format('F d, Y \a\t h:i A') }}</div>
                </div>
                <div class="signature-row">
                    <div class="signature-label">IP Address</div>
                    <div class="signature-value">{{ $consentForm->signature_ip }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="footer">
            <p>This document was generated by Gracimor Properties on {{ now()->format('F d, Y') }}.</p>
            <p>This is a computer-generated document and does not require a physical signature.</p>
        </div>
    </div>
</body>
</html>

