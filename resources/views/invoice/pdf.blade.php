<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path("fonts/DejaVuSans.ttf") }}') format('truetype');
        }

        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: bold;
            src: url('{{ public_path("fonts/DejaVuSans-Bold.ttf") }}') format('truetype');
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'DejaVu Sans', sans-serif !important;
        }

        body {
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            background: #fff;
            padding: 10mm;
        }

        .invoice-container {
            width: 100%;
            max-width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            margin: 0 auto;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
        }

        /* ==================== HEADER - FIXED ==================== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: nowrap;
            gap: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .company-header {
            flex: 1;
            min-width: 0; /* Allows internal text to wrap */
        }

        .logo-container {
            margin-bottom: 5px;
            height: 40px;
            display: flex;
            align-items: center;
        }

        .logo {
            max-height: 40px;
            max-width: 150px;
            object-fit: contain;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #000;
        }

        .company-details {
            font-size: 10px;
            color: #555;
            line-height: 1.2;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .invoice-meta {
            flex-shrink: 0;
            min-width: 200px;
            text-align: right;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
            letter-spacing: 0.5px;
        }

        .invoice-number {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #333;
        }

        .invoice-date {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .status-paid { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-overdue { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* ==================== BILLING SECTION ==================== */
        .billing-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 15px;
        }

        .bill-to-section {
            flex: 1;
        }

        .bank-details {
            flex: 0 0 40%;
            max-width: 40%;
            padding: 8px;
            background: #f8f9fa;
            border-left: 2px solid #007bff;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 2px;
        }

        .address-box {
            font-size: 10px;
            line-height: 1.3;
            color: #333;
        }

        .bank-details .section-title {
            font-size: 10px;
            border: none;
            margin-bottom: 3px;
        }

        .bank-content {
            font-size: 9px;
            line-height: 1.2;
            color: #555;
            white-space: pre-line;
        }

        /* ==================== ITEMS TABLE ==================== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .items-table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 6px 5px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .items-table td {
            border: 1px solid #dee2e6;
            padding: 6px 5px;
            font-size: 10px;
            vertical-align: top;
        }

        .items-table tr:nth-child(even) {
            background: #fafbfc;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .description-sub {
            font-size: 9px;
            color: #6c757d;
            margin-top: 1px;
            font-style: italic;
        }

        /* ==================== TOTALS ==================== */
        .totals-section {
            width: 240px;
            margin-left: auto;
            margin-bottom: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 11px;
            padding: 2px 0;
        }

        .total-label { color: #6c757d; }
        .total-value {
            font-weight: 600;
            min-width: 100px;
            text-align: right;
            color: #333;
        }

        .grand-total-row {
            font-size: 13px;
            font-weight: bold;
            border-top: 2px solid #000;
            margin-top: 8px;
            padding-top: 6px;
            color: #000;
        }

        .tax-note {
            text-align: right;
            font-size: 9px;
            color: #6c757d;
            margin-top: 3px;
            font-style: italic;
        }

        /* ==================== DESCRIPTION & FOOTER ==================== */
        .description-section {
            margin-bottom: 15px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 3px;
        }

        .description-content {
            font-size: 10px;
            line-height: 1.3;
            color: #555;
            white-space: pre-line;
        }

        .footer {
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            min-height: 80px;
            margin-top: auto;
        }

        .terms-section {
            flex: 1;
            font-size: 9px;
            color: #6c757d;
            line-height: 1.3;
        }

        .terms-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 10px;
            color: #333;
        }

        .terms-list {
            padding-left: 12px;
            margin: 0;
        }

        .terms-list li {
            margin-bottom: 2px;
        }

        .signature-section {
            text-align: center;
            width: 160px;
        }

        .signature-box {
            height: 40px;
            border-bottom: 1px solid #000;
            margin-bottom: 3px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .signature-image {
            max-height: 30px;
            max-width: 120px;
            object-fit: contain;
        }

        .signature-label {
            font-size: 9px;
            color: #6c757d;
            font-style: italic;
        }

        .thank-you {
            text-align: center;
            font-style: italic;
            color: #6c757d;
            margin-top: 8px;
            font-size: 10px;
            width: 100%;
        }

        .page-info {
            text-align: center;
            font-size: 8px;
            color: #adb5bd;
            margin-top: 5px;
            width: 100%;
        }

        .spacer-row {
            height: 15px;
        }

        /* ==================== PRINT ==================== */
        /* @media print {
            body {
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .invoice-container {
                padding: 10mm;
                min-height: 277mm;
            }

            .footer {
                position: fixed;
                bottom: 10mm;
                left: 10mm;
                right: 10mm;
                min-height: auto;
            }
        } */
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="main-content">
            <!-- Header -->
            <div class="header " style="border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px;">
                <div class="company-header">
                    {{-- @if($invoice->company->logo)
                        @php
                            $logoPath = public_path('storage/' . $invoice->company->logo);
                            $logoExists = file_exists($logoPath);
                        @endphp
                        @if($logoExists)
                            <div class="logo-container">
                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}"
                                     class="logo"
                                     alt="{{ $invoice->company->name }}">
                            </div>
                        @endif
                    @endif --}}

                    <div class="company-name">{{ $invoice->company->name }}</div>

                    <div class="company-details">
                        @if($invoice->company->address)
                            <div>{{ $invoice->company->address }}</div>
                        @endif
                        @if($invoice->company->contact)
                            <div>{{ $invoice->company->contact }}</div>
                        @endif
                        @if($invoice->company->email)
                            <div>{{ $invoice->company->email }}</div>
                        @endif
                        @if($invoice->company->gstin)
                            <div><strong>GSTIN:</strong> {{ $invoice->company->gstin }}</div>
                        @endif
                    </div>
                </div>

                <div class="invoice-meta">

                    @if($invoice->tax_rate > 0)
                    
                            <div class="invoice-title">INVOICE</div>
                        @else
                            <div class="invoice-title">NO INVOICE</div>
                        
                    @endif

                        <div class="invoice-number">No: {{ $invoice->invoice_number }}</div>
                        <div class="invoice-date">Date: {{ $invoice->invoice_date->format('d M Y') }}</div>
                        <div class="status-badge status-{{ strtolower($invoice->status) }}">
                            {{ ucfirst($invoice->status) }}
                        </div>
                </div>
            </div>

            <!-- Billing Section -->
            <div class="billing-section">
                <div class="bill-to-section">
                    <div class="bill-to">
                        <div class="section-title">Bill To</div>
                        <div class="address-box">
                            <div><strong>{{ $invoice->client_name }}</strong></div>
                            @if($invoice->client_phone)
                                <div>Phone: {{ $invoice->client_phone }}</div>
                            @endif
                            @if($invoice->client_email)
                                <div>Email: {{ $invoice->client_email }}</div>
                            @endif
                            @if($invoice->client_address)
                                <div>{{ nl2br(e($invoice->client_address)) }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($invoice->company->bank_details)
                    <div class="bank-details">
                        <div class="section-title">Payment Instructions</div>
                        <div class="bank-content">{{ $invoice->company->bank_details }}</div>
                    </div>
                @endif
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 50%;">Description</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 15%;">Rate</th>
                        <th style="width: 20%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                {{ $item->description }}
                                @if($item->service_type)
                                    <div class="description-sub">{{ $item->service_type }}</div>
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($item->quantity, 0) }}</td>
                            <td class="text-right">{{ $invoice->currency }} {{ number_format($item->price, 2) }}</td>
                            <td class="text-right">{{ $invoice->currency }} {{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach

                    @for($i = count($invoice->items); $i < 3; $i++)
                        <tr class="spacer-row">
                            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            @php $currency = '₹'; @endphp

            <!-- Totals -->
            <div class="totals-section">
                <div class="total-row">
                    <span class="total-label">Subtotal</span>
                    <span class="total-value">{{ $currency }} {{ number_format($invoice->subtotal, 2) }}</span>
                </div>

                @if($invoice->tax_rate > 0)
                    @if($invoice->tax_mode === 'cgst')
                        <div class="total-row">
                            <span class="total-label">CGST @ {{ number_format(($invoice->tax_rate / 2) * 100, 1) }}%</span>
                            <span class="total-value">{{ $currency }} {{ number_format($invoice->tax_amount / 2, 2) }}</span>
                        </div>
                        <div class="total-row">
                            <span class="total-label">SGST @ {{ number_format(($invoice->tax_rate / 2) * 100, 1) }}%</span>
                            <span class="total-value">{{ $currency }} {{ number_format($invoice->tax_amount / 2, 2) }}</span>
                        </div>
                    @else
                        <div class="total-row">
                            <span class="total-label">IGST @ {{ number_format($invoice->tax_rate * 100, 1) }}%</span>
                            <span class="total-value">{{ $currency }} {{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                    @endif
                @endif

                @if($invoice->discount > 0)
                    <div class="total-row">
                        <span class="total-label">Discount</span>
                        <span class="total-value">{{ $currency }} {{ number_format($invoice->discount, 2) }}</span>
                    </div>
                @endif

                <div class="total-row grand-total-row">
                    <span>Grand Total</span>
                    <span>{{ $currency }} {{ number_format($invoice->grand_total, 2) }}</span>
                </div>

                @if($invoice->tax_rate > 0)
                    <div class="tax-note">
                        Total Tax: {{ $currency }} {{ number_format($invoice->tax_amount, 2) }}
                    </div>
                @endif
            </div>

            <!-- Additional Notes -->
            @if($invoice->description)
                <div class="description-section">
                    <div class="section-title">Additional Notes</div>
                    <div class="description-content">{{ $invoice->description }}</div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="terms-section">
                <div class="terms-title">Terms & Conditions</div>
                <ul class="terms-list">
                    @php $terms = json_decode($invoice->terms, true) ?? []; @endphp
                    @if(count($terms) > 0)
                        @foreach($terms as $term)
                            <li>{{ $term }}</li>
                        @endforeach
                    @else
                        <li>Payment due within 15 days.</li>
                        <li>Quote invoice number in correspondence.</li>
                        <li>Goods/services remain our property until paid.</li>
                    @endif
                </ul>
            </div>

            <div class="signature-section">
                <div class="signature-box">
                    @if($invoice->admin_signature)
                        @php
                            $signatureData = $invoice->admin_signature;
                            if (strpos($signatureData, 'data:image') === 0) {
                                echo '<img src="' . $signatureData . '" class="signature-image" alt="Signature">';
                            } else {
                                $sigPath = public_path('storage/' . $signatureData);
                                if (file_exists($sigPath)) {
                                    echo '<img src="data:image/png;base64,' . base64_encode(file_get_contents($sigPath)) . '" class="signature-image" alt="Signature">';
                                }
                            }
                        @endphp
                    @endif
                </div>
                <div class="signature-label">Authorized Signature</div>
            </div>

            <div class="thank-you">Thank you for your business!</div>
            {{-- <div class="page-info">Page 1 of 1</div> --}}
        </div>
    </div>
</body>
</html>