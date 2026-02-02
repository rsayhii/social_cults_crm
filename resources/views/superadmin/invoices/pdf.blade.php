<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #3b82f6;
            margin: 0;
            font-size: 28px;
        }
        
        .header h2 {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 18px;
        }
        
        .company-info, .client-info {
            margin-bottom: 30px;
        }
        
        .section-title {
            background-color: #f3f4f6;
            padding: 8px 12px;
            font-weight: bold;
            margin-bottom: 10px;
            border-left: 4px solid #3b82f6;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals {
            margin-top: 30px;
            border-top: 2px solid #ddd;
            padding-top: 20px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        
        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #3b82f6;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-cancelled {
            background-color: #f3f4f6;
            color: #374151;
        }
        
        .watermark {
            position: fixed;
            opacity: 0.1;
            font-size: 120px;
            color: #3b82f6;
            transform: rotate(-45deg);
            left: 10%;
            top: 30%;
            z-index: -1;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">
        {{ $invoice->status === 'paid' ? 'PAID' : ($invoice->status === 'pending' ? 'PENDING' : 'INVOICE') }}
    </div>
    
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <h1>INVOICE</h1>
            <h2>#{{ $invoice->invoice_number }}</h2>
            <div>
                <span class="status-badge status-{{ $invoice->status }}">
                    {{ strtoupper($invoice->status) }}
                </span>
            </div>
        </div>
        
        <!-- Company & Client Info -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
            <div style="width: 45%;">
                <div class="section-title">FROM</div>
                <div style="font-weight: bold;">{{ $invoice->company_name }}</div>
                <div>{{ $invoice->company_address }}</div>
                @if($invoice->company_phone)
                <div>Phone: {{ $invoice->company_phone }}</div>
                @endif
                @if($invoice->company_email)
                <div>Email: {{ $invoice->company_email }}</div>
                @endif
                @if($invoice->company_gstin)
                <div>GSTIN: {{ $invoice->company_gstin }}</div>
                @endif
            </div>
            
            <div style="width: 45%;">
                <div class="section-title">BILL TO</div>
                <div style="font-weight: bold;">{{ $invoice->client_name }}</div>
                <div>{{ $invoice->client_address }}</div>
                @if($invoice->client_phone)
                <div>Phone: {{ $invoice->client_phone }}</div>
                @endif
                @if($invoice->client_email)
                <div>Email: {{ $invoice->client_email }}</div>
                @endif
                @if($invoice->client_gstin)
                <div>GSTIN: {{ $invoice->client_gstin }}</div>
                @endif
            </div>
        </div>
        
        <!-- Invoice Details -->
        <div style="margin-bottom: 30px;">
            <div class="section-title">INVOICE DETAILS</div>
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('d M, Y') }}
                </div>
                @if($invoice->due_date)
                <div>
                    <strong>Due Date:</strong> {{ $invoice->due_date->format('d M, Y') }}
                </div>
                @endif
                <div>
                    <strong>Currency:</strong> {{ $invoice->currency }}
                </div>
            </div>
        </div>
        
        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="40%">Description</th>
                    <th width="15%">Service Type</th>
                    <th width="10%">Qty</th>
                    <th width="15%">Unit Price</th>
                    <th width="15%" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @if($invoice->items && is_array($invoice->items))
                    @foreach($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['description'] ?? '' }}</td>
                        <td>{{ $item['service_type'] ?? '-' }}</td>
                        <td>{{ number_format($item['quantity'] ?? 0, 2) }}</td>
                        <td>{{ $invoice->currency }}{{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                        <td class="text-right">{{ $invoice->currency }}{{ number_format(($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0), 2) }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        
        <!-- Totals -->
        <div class="totals" style="margin-left: auto; width: 300px;">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>{{ $invoice->currency }}{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            
            @if($invoice->tax_amount > 0)
            <div class="total-row">
                <span>Tax ({{ $invoice->tax_rate }}% {{ strtoupper($invoice->tax_type) }}):</span>
                <span>{{ $invoice->currency }}{{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            @endif
            
            @if($invoice->discount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>{{ $invoice->currency }}{{ number_format($invoice->discount, 2) }}</span>
            </div>
            @endif
            
            <div class="grand-total total-row">
                <span>GRAND TOTAL:</span>
                <span>{{ $invoice->currency }}{{ number_format($invoice->grand_total, 2) }}</span>
            </div>
            
            @if($invoice->paid_amount > 0)
            <div class="total-row" style="color: #10b981;">
                <span>Paid Amount:</span>
                <span>{{ $invoice->currency }}{{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
            @endif
            
            @if(($invoice->grand_total - $invoice->paid_amount) > 0)
            <div class="total-row" style="color: #ef4444;">
                <span>Balance Due:</span>
                <span>{{ $invoice->currency }}{{ number_format($invoice->grand_total - $invoice->paid_amount, 2) }}</span>
            </div>
            @endif
        </div>
        
        <!-- Payment Information -->
        @if($invoice->status === 'paid' || $invoice->paid_amount > 0)
        <div style="margin-top: 30px;">
            <div class="section-title">PAYMENT INFORMATION</div>
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <strong>Payment Date:</strong> {{ $invoice->payment_date ? $invoice->payment_date->format('d M, Y') : 'N/A' }}
                </div>
                @if($invoice->payment_method)
                <div>
                    <strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $invoice->payment_method)) }}
                </div>
                @endif
                @if($invoice->transaction_id)
                <div>
                    <strong>Transaction ID:</strong> {{ $invoice->transaction_id }}
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Terms & Conditions -->
        @if($invoice->terms_conditions)
        <div style="margin-top: 30px;">
            <div class="section-title">TERMS & CONDITIONS</div>
            <div style="font-size: 12px; line-height: 1.4;">
                {!! nl2br(e($invoice->terms_conditions)) !!}
            </div>
        </div>
        @endif
        
        <!-- Description -->
        @if($invoice->description)
        <div style="margin-top: 20px;">
            <div class="section-title">DESCRIPTION</div>
            <div>{{ $invoice->description }}</div>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <div style="text-align: center;">
                <div>Generated on: {{ date('d M, Y h:i A') }}</div>
                <div>Thank you for your business!</div>
            </div>
        </div>
    </div>
</body>
</html>