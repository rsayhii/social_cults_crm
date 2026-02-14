@extends('components.layout')
@section('content')
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Social Cults — Invoice Generator Pro</title>
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            /* Your CSS styles here - copy all CSS from your original file */
            body {
                background: #f8fafc;
                color: #0f172a;
                font-family: system-ui, -apple-system, sans-serif;
            }

            .card {
                background: white;
                border-radius: 10px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            }

            .pill {
                border-radius: 999px;
                padding: 4px 10px;
                font-weight: 500;
                font-size: 11px;
                display: inline-block;
            }

            .dropzone {
                border: 2px dashed #cbd5e1;
                border-radius: 6px;
                padding: 10px;
                text-align: center;
                cursor: pointer;
                background: #f8fafc;
                transition: border-color 0.2s;
            }

            .dropzone:hover {
                border-color: #94a3b8;
            }

            .sig-line {
                border-top: 1px solid #e2e8f0;
                margin-top: 8px;
                padding-top: 4px;
                text-align: center;
                font-size: 11px;
                color: #64748b;
            }

            .compact-input {
                border: 1px solid #e2e8f0;
                border-radius: 6px;
                padding: 6px 10px;
                font-size: 13px;
                width: 100%;
                transition: border-color 0.2s;
            }

            .compact-input:focus {
                outline: none;
                border-color: #94a3b8;
            }

            .compact-label {
                font-size: 12px;
                font-weight: 500;
                color: #475569;
                margin-bottom: 4px;
                display: block;
            }

            .tab-btn {
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 500;
                border: 1px solid #e2e8f0;
                background: white;
                transition: all 0.2s;
            }

            .tab-btn:hover {
                background: #f1f5f9;
            }

            .tab-btn.active {
                background: #0f172a;
                color: white;
                border-color: #0f172a;
            }

            .action-btn {
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 500;
                transition: all 0.2s;
            }

            .primary-btn {
                background: #0f172a;
                color: white;
            }

            .primary-btn:hover {
                background: #1e293b;
            }

            .secondary-btn {
                background: #f1f5f9;
                color: #475569;
                flex: none
            }

            .secondary-btn:hover {
                background: #e2e8f0;
            }

            .danger-btn {
                background: #fee2e2;
                color: #dc2626;
            }

            .danger-btn:hover {
                background: #fecaca;
            }

            .success-btn {
                background: #dcfce7;
                color: #16a34a;
            }

            .success-btn:hover {
                background: #bbf7d0;
            }

            /* PDF print styles */
            @media print {
                body * {
                    visibility: hidden;
                    margin: 0 !important;
                    padding: 0 !important;
                }

                #invoice-preview,
                #invoice-preview * {
                    visibility: visible;
                    position: relative !important;
                    box-shadow: none !important;
                    border: none !important;
                }

                #invoice-preview {
                    position: fixed !important;
                    left: 0 !important;
                    top: 0 !important;
                    width: 100% !important;
                    height: auto !important;
                    margin: 0 !important;
                    padding: 15mm !important;
                    background: white !important;
                }

                .no-print,
                .edit-terms-btn,
                [class*="no-print"] {
                    display: none !important;
                }

                .print-only {
                    display: block !important;
                }

                /* Ensure proper positioning for PDF */
                .print-description,
                .print-signature,
                .print-terms {
                    position: static !important;
                    margin-top: 20px !important;
                    page-break-inside: avoid !important;
                }

                /* Signature alignment for print */
                .print-signature {
                    float: right !important;
                    text-align: right !important;
                    width: 200px !important;
                    margin-top: 40px !important;
                    margin-right: 0 !important;
                }

                .print-description {
                    clear: both !important;
                }
            }

            .modal-center {
                max-width: 900px;
                width: calc(100% - 40px);
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .fade-in {
                animation: fadeIn 5.1s ease;
            }

            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            /* Custom print styles for A4 - FIXED */
            @media print {
                @page {
                    margin: 15mm !important;
                    size: A4 portrait !important;
                }

                body {
                    margin: 0 !important;
                    padding: 0 !important;
                    width: 210mm !important;
                    height: 297mm !important;
                }

                #invoice-preview {
                    width: 100% !important;
                    max-width: 100% !important;
                    padding: 0 !important;
                    margin: 0 auto !important;
                    box-shadow: none !important;
                    border: none !important;
                    background: white !important;
                    position: static !important;
                    top: auto !important;
                    left: auto !important;
                }

                /* Fix for terms positioning */
                .print-terms {
                    position: relative !important;
                    bottom: auto !important;
                    left: auto !important;
                    right: auto !important;
                    margin-top: 30px !important;
                    margin-bottom: 10px !important;
                    padding-top: 15px !important;
                    border-top: 1px solid #e2e8f0 !important;
                    font-size: 10px !important;
                    color: #64748b !important;
                    page-break-inside: avoid !important;
                    clear: both !important;
                }

                /* Signature on right side for print */
                .print-signature {
                    position: relative !important;
                    float: right !important;
                    text-align: right !important;
                    width: 200px !important;
                    margin-top: 40px !important;
                    margin-right: 0 !important;
                    bottom: auto !important;
                    left: auto !important;
                    page-break-inside: avoid !important;
                }

                .print-description {
                    position: relative !important;
                    bottom: auto !important;
                    left: auto !important;
                    right: auto !important;
                    margin-top: 20px !important;
                    font-size: 11px !important;
                    color: #475569 !important;
                    page-break-inside: avoid !important;
                    clear: both !important;
                }

                /* Hide screen elements */
                .screen-only {
                    display: none !important;
                }

                /* Ensure content fits on one page */
                .invoice-content {
                    min-height: 240mm !important;
                    max-height: 240mm !important;
                    overflow: hidden !important;
                }
            }

            .icon-btn {
                width: 28px;
                height: 28px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.2s;
            }

            .icon-btn-sm {
                width: 24px;
                height: 24px;
                font-size: 12px;
            }

            .terms-item {
                display: flex;
                gap: 8px;
                align-items: center;
                margin-bottom: 4px;
            }

            .terms-input {
                flex: 1;
                border: 1px solid #e2e8f0;
                border-radius: 4px;
                padding: 4px 8px;
                font-size: 12px;
            }

            .edit-terms-btn {
                position: absolute;
                top: 10px;
                right: 10px;
            }

            /* Special class for PDF generation */
            .pdf-mode .print-only {
                display: block !important;
            }

            .pdf-mode .screen-only {
                display: none !important;
            }

            .pdf-mode .edit-terms-btn {
                display: none !important;
            }

            /* Signature alignment for PDF mode */
            .pdf-mode .print-signature {
                float: right !important;
                text-align: right !important;
                width: 200px !important;
                margin-top: 40px !important;
                margin-right: 0 !important;
                clear: none !important;
            }

            .pdf-mode .print-description {
                clear: both !important;
            }

            /* Screen-only signature layout */
            .signature-right {
                float: right;
                text-align: right;
                width: 200px;
                margin-top: 20px;
            }

            /* Responsive breakpoints */
            @media (max-width: 640px) {
                .xs\:hidden {
                    display: none !important;
                }

                .xs\:inline {
                    display: inline !important;
                }
            }

            @media (max-width: 768px) {
                .table-responsive {
                    display: block;
                    width: 100%;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }

                .compact-input {
                    font-size: 14px;
                    padding: 8px 12px;
                }
            }

            /* Toast Notifications */
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }

                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }

                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }

            .toast-notification {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 9999;
                padding: 12px 24px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                display: flex;
                align-items: center;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                transition: all 0.3s ease;
                max-width: 400px;
            }

            .toast-success {
                background-color: #10b981;
            }

            .toast-error {
                background-color: #ef4444;
            }
        </style>
    </head>

    <body class="font-sans antialiased">
        <div class="max-w-7xl mx-auto p-5">
            <!-- HEADER -->
            <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div class="flex items-center gap-3 hidden md:block">
                    {{-- <img id="main-logo" src="{{ asset('/storage/'. $company->logo) }}" class="h-12 object-contain"
                        alt="Logo" /> --}}
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">{{ $company->name }} Invoice Pro</h1>
                        <p class="text-xs text-slate-500">Professional invoice management with GST</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 items-center no-print">
                    <div class="flex items-center gap-2">
                        <select id="header-company-select" class="compact-input text-sm w-48">
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        </select>
                        <button id="btn-manage-company" class="action-btn secondary-btn text-xs">
                            <i class="fas fa-building mr-1"></i> Edit Company
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <button id="btn-create-invoice" class="tab-btn active">
                            <i class="fas fa-plus-circle mr-1"></i> Create Invoice
                        </button>
                        <button id="btn-history" class="tab-btn">
                            <i class="fas fa-history mr-1"></i> History
                        </button>
                        <button id="btn-share" class="action-btn success-btn no-print hidden">
                            <i class="fas fa-share-alt mr-1 hidden"></i> Share
                        </button>
                    </div>
                </div>
            </header>
            <main>
                <!-- CREATE TAB -->
                <section id="tab-create" class="space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                        <!-- LEFT COLUMN: Client & Settings -->
                        <div class="space-y-5">

                            <!-- Client Information Card -->
                            <div class="card p-4">
                                <h3 class="text-sm font-semibold text-slate-800 mb-3">
                                    <i class="fas fa-user-circle mr-2"></i>Client Information
                                </h3>
                                <div class="space-y-3">
                                    <input id="client-name" class="compact-input" placeholder="Client / Company Name" />
                                    <div class="grid grid-cols-2 gap-2">
                                        <input id="client-phone" class="compact-input" type="tel" placeholder="Phone"
                                            required pattern="[6-9][0-9]{9}"
                                            title="Enter a valid 10-digit Indian phone number" />
                                        <input id="client-email" class="compact-input" type="email" placeholder="Email"
                                            required />
                                    </div>
                                    <textarea id="client-address" rows="3" class="compact-input"
                                        placeholder="Address & GSTIN"></textarea>
                                </div>
                            </div>

                            <!-- Invoice Settings Card -->
                            <div class="card p-4">
                                <h3 class="text-sm font-semibold text-slate-800 mb-3">
                                    <i class="fas fa-cog mr-2"></i>Invoice Settings
                                </h3>
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="compact-label">Invoice #</label>
                                            <input id="invoice-number" class="compact-input"
                                                value="{{ $invoice_number }}" />
                                        </div>
                                        <div>
                                            <label class="compact-label">Date</label>
                                            <input id="invoice-date" type="date" class="compact-input" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="compact-label">Status</label>
                                            <select id="invoice-status" class="compact-input">
                                                <option value="Pending">Pending</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Overdue">Overdue</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="compact-label">Currency</label>
                                            <select id="currency" class="compact-input">
                                                <option value="₹">INR (₹)</option>
                                                <option value="$">USD ($)</option>
                                                <option value="€">EUR (€)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="compact-label">GST Slab</label>
                                            <select id="tax-slab" class="compact-input">
                                                <option value="0">No Tax</option>
                                                <option value="0.05">5%</option>
                                                <option value="0.12">12%</option>
                                                <option value="0.18" selected>18%</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="compact-label">GST Mode</label>
                                            <select id="tax-mode" class="compact-input">
                                                <option value="cgst">CGST+SGST</option>
                                                <option value="igst">IGST</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="compact-label">Discount (flat)</label>
                                        <input id="discount" type="number" min="0" step="0.01" class="compact-input"
                                            value="0" />
                                    </div>
                                    <div>
                                        <label class="compact-label">Description for Invoice</label>
                                        <textarea id="invoice-description" rows="2" class="compact-input"
                                            placeholder="Add description that will appear above signature"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Admin Signature Card -->
                            <div class="card p-4">
                                <h3 class="text-sm font-semibold text-slate-800 mb-3">
                                    <i class="fas fa-signature mr-2"></i>Admin Signature
                                </h3>
                                <div id="sig-dropzone" class="dropzone mb-2 text-xs">
                                    Click or drop signature image (PNG, JPG, WebP)
                                </div>
                                <input id="admin-sig-file" type="file" accept="image/*" class="hidden" />
                                <div id="admin-sig-preview"
                                    class="border rounded p-2 h-20 flex items-center justify-center text-xs text-slate-500">
                                    No signature uploaded
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <label for="admin-sig-file" class="action-btn secondary-btn flex-1 text-center">
                                        <i class="fas fa-upload mr-1"></i> Upload
                                    </label>
                                    <button id="remove-admin-sig" class="action-btn danger-btn flex-1">
                                        <i class="fas fa-trash mr-1"></i> Remove
                                    </button>
                                </div>
                            </div>

                        </div>

                        <!-- RIGHT COLUMN (2/3 width): Items & Preview -->
                        <div class="lg:col-span-2 space-y-5">

                            <!-- Items Table Card -->
                            <div class="card p-4">
                                <div
                                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-800">
                                            <i class="fas fa-list-alt mr-2"></i>Invoice Items
                                        </h3>
                                        <p class="text-xs text-slate-500">Add services, quantity, and unit price</p>
                                    </div>
                                    <div class="flex gap-2 w-full sm:w-auto">
                                        <button id="add-item" class="action-btn primary-btn text-xs flex-1 sm:flex-none">
                                            <i class="fas fa-plus mr-1"></i> <span class="hidden xs:inline">Add
                                                Item</span><span class="xs:hidden">Add</span>
                                        </button>
                                        <button id="clear-items"
                                            class="action-btn secondary-btn text-xs flex-1 sm:flex-none hidden">
                                            <i class="fas fa-trash mr-1"></i> <span class="hidden xs:inline">Clear
                                                All</span><span class="xs:hidden">Clear</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="overflow-x-auto -mx-2 sm:mx-0">
                                    <div class="min-w-[640px] sm:min-w-full">
                                        <table class="w-full text-sm">
                                            <thead class="text-xs text-slate-600 bg-slate-50">
                                                <tr>
                                                    <th class="p-2 text-left w-8 sm:w-10">#</th>
                                                    <th class="p-2 text-left min-w-[180px]">Description</th>
                                                    <th class="p-2 text-left min-w-[120px] sm:w-40">Service Type</th>
                                                    <th class="p-2 text-right w-16 sm:w-20">Qty</th>
                                                    <th class="p-2 text-right w-20 sm:w-28">Rate</th>
                                                    <th class="p-2 text-right w-20 sm:w-28">Amount</th>
                                                    <th class="p-2 w-8 sm:w-10 no-print"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="items-body"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Preview & Actions -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                                <!-- Preview Card (2/3 width) - UPDATED FOR PDF -->
                                <div id="invoice-preview" class="card p-5 lg:col-span-2 relative"
                                    style="min-height: 800px;">
                                    <!-- Main Content -->
                                    <div class="flex justify-between items-start">
                                        <div>
                                            {{-- <img id="preview-logo" src="{{ asset('/storage/'. $company->logo) }}"
                                                class="h-10 object-contain" alt="Logo" /> --}}
                                            <div>
                                                <h1 id="nonTaxInvoice" class="text-xl font-bold text-slate-800 hidden">
                                                    Non Tax Invoice
                                                </h1>
                                                <h1 id="taxInvoice" class="text-xl font-bold text-slate-800">
                                                    Invoice
                                                </h1>
                                            </div>
                                            <div id="preview-company"
                                                class="mt-2 text-xs text-slate-600 whitespace-pre-line"></div>
                                        </div>
                                        <div class="text-right">
                                            <div id="preview-number" class="font-bold text-lg"></div>
                                            <div id="preview-date" class="text-xs text-slate-500"></div>
                                            <div id="preview-status" class="mt-1"></div>
                                        </div>
                                    </div>

                                    <hr class="my-4 border-slate-200" />

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <div class="font-semibold text-slate-700">Bill To</div>
                                            <div id="preview-client"
                                                class="text-xs text-slate-600 whitespace-pre-line mt-1"></div>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-700">Payment Details</div>
                                            <div id="preview-bank" class="text-xs text-slate-600 whitespace-pre-line mt-1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <table class="w-full text-xs">
                                            <thead class="bg-slate-50">
                                                <tr>
                                                    <th class="p-2 text-left">#</th>
                                                    <th class="p-2 text-left">Item</th>
                                                    <th class="p-2 text-right">Qty</th>
                                                    <th class="p-2 text-right">Rate</th>
                                                    <th class="p-2 text-right">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="preview-lines"></tbody>
                                        </table>
                                    </div>

                                    <div class="mt-4 flex justify-end">
                                        <div class="w-64 text-sm space-y-1">
                                            <div class="flex justify-between"><span
                                                    class="text-slate-600">Subtotal</span><span
                                                    id="preview-subtotal"></span></div>
                                            <div class="flex justify-between"><span id="preview-tax-label"
                                                    class="text-slate-600">Tax</span><span id="preview-tax"></span></div>
                                            <div class="flex justify-between"><span
                                                    class="text-slate-600">Discount</span><span
                                                    id="preview-discount"></span></div>
                                            <hr class="my-2 border-slate-200" />
                                            <div class="flex justify-between font-bold text-lg"><span>Total</span><span
                                                    id="preview-total"></span></div>
                                        </div>
                                    </div>

                                    <!-- Print-only elements (hidden on screen, visible in print/PDF) -->
                                    <div class="print-description hidden print:block">
                                        <div class="font-semibold text-xs text-slate-700 mb-1">Description</div>
                                        <div id="print-description-text"
                                            class="text-xs text-slate-600 whitespace-pre-line border border-slate-200 rounded p-3 mt-1">
                                        </div>
                                    </div>

                                    <div class="print-signature hidden print:block"
                                        style="float: right; text-align: right; width: 200px; margin-top: 40px;">
                                        <div class="font-semibold text-xs text-slate-700">Authorized Signature</div>
                                        <div id="print-sig-block"
                                            class="border border-slate-200 rounded p-3 h-24 flex items-center justify-center mt-1">
                                            <div id="print-sig-image" class="text-slate-400 text-xs">No signature uploaded
                                            </div>
                                        </div>
                                        <div class="sig-line">Authorized Signature</div>
                                    </div>

                                    <div class="print-terms hidden print:block mt-6" style="clear: both;">
                                        <div class="font-semibold text-xs text-slate-700 mb-1">Terms & Conditions</div>
                                        <ul id="print-terms-list" class="list-disc pl-4 text-xs text-slate-600 space-y-0">
                                            <!-- Dynamic terms will be inserted here -->
                                        </ul>
                                        <div class="text-xs text-slate-500 mt-2 text-center">Thank you for your business!
                                        </div>
                                    </div>

                                    <!-- Screen-only elements (hidden in print) -->
                                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 screen-only">
                                        <div>
                                            <div class="font-semibold text-xs text-slate-700">Description</div>
                                            <div id="screen-description"
                                                class="text-xs text-slate-600 whitespace-pre-line mt-1 border border-slate-200 rounded p-3 min-h-20">
                                            </div>
                                        </div>
                                        <div class="signature-right">
                                            <div class="font-semibold text-xs text-slate-700">Authorized Signature</div>
                                            <div id="sig-block"
                                                class="border border-slate-200 rounded p-3 h-28 flex items-center justify-center mt-1">
                                                <div id="sig-image" class="text-slate-400 text-xs">No signature uploaded
                                                </div>
                                            </div>
                                            <div class="sig-line">Authorized Signature</div>
                                        </div>
                                    </div>

                                    <div class="mt-4 screen-only relative" style="clear: both;">
                                        <div class="font-semibold text-xs text-slate-700 mb-2">Terms & Conditions</div>
                                        <button id="edit-terms-btn"
                                            class="edit-terms-btn icon-btn secondary-btn icon-btn-sm no-print">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <ul id="terms-list" class="list-disc pl-4 text-xs text-slate-600 mt-1 space-y-1">
                                            <!-- Dynamic terms will be inserted here -->
                                        </ul>
                                    </div>
                                </div>

                                <!-- Actions & Totals Sidebar -->
                                <div class="space-y-4">
                                    <!-- Totals Card -->
                                    <div class="card p-4">
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm"><span
                                                    class="text-slate-600">Subtotal</span><span
                                                    id="side-subtotal">₹0.00</span></div>
                                            <div class="flex justify-between text-sm"><span id="side-tax-label"
                                                    class="text-slate-600">Tax</span><span id="side-tax">₹0.00</span></div>
                                            <div class="flex justify-between text-sm"><span
                                                    class="text-slate-600">Discount</span><span
                                                    id="side-discount">₹0.00</span></div>
                                            <hr class="my-2 border-slate-200" />
                                            <div class="flex justify-between font-bold text-lg"><span>Grand
                                                    Total</span><span id="side-total">₹0.00</span></div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="card p-4 no-print space-y-2">
                                        <button id="btn-save" class="action-btn primary-btn w-full">
                                            <i class="fas fa-save mr-2"></i> Save Invoice
                                        </button>
                                        <button id="btn-pdf" class="action-btn primary-btn w-full"
                                            style="background: #2563eb;">
                                            <i class="fas fa-file-pdf mr-2"></i> Download PDF
                                        </button>
                                        <button id="btn-print" class="action-btn primary-btn w-full hidden"
                                            style="background: #059669;">
                                            <i class="fas fa-print mr-2"></i> Print
                                        </button>
                                        <button id="btn-email" class="action-btn secondary-btn w-full hidden">
                                            <i class="fas fa-envelope mr-2"></i> Send Email
                                        </button>
                                        <button id="btn-share-invoice" class="action-btn success-btn w-full hidden">
                                            <i class="fas fa-share-alt mr-2 hidden"></i> Share Invoice
                                        </button>
                                    </div>

                                    <!-- Recent Invoices -->
                                    <div class="card p-4">
                                        <div class="font-semibold text-sm text-slate-800 mb-3">
                                            <i class="fas fa-clock mr-2"></i>Recent Invoices
                                        </div>
                                        <div id="saved-list" class="space-y-2 max-h-48 overflow-y-auto scrollbar-hide">
                                            <!-- Dynamic content -->
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </section>

                <!-- HISTORY TAB -->
                <section id="tab-history" class="hidden">
                    <div class="card p-4">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800">
                                    <i class="fas fa-history mr-2"></i>Invoice History
                                </h3>
                                <p class="text-xs text-slate-500">All invoices saved locally</p>
                            </div>
                            <div class="flex gap-2 no-print">
                                <input id="history-search" class="compact-input w-48"
                                    placeholder="Search by client or number" />
                                <select id="history-filter" class="compact-input w-36">
                                    <option value="all">All Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Paid">Paid</option>
                                    <option value="Overdue">Overdue</option>
                                </select>
                                <button id="btn-share-bulk" class="action-btn success-btn text-xs hidden ">
                                    <i class="fas fa-share-alt mr-1 hidden"></i> Share Selected
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 text-xs text-slate-600">
                                    <tr>
                                        <th class="p-2 text-center w-10"><input id="history-check-all" type="checkbox" />
                                        </th>
                                        <th class="p-2 text-left w-12">#</th>
                                        <th class="p-2 text-left">Invoice #</th>
                                        <th class="p-2 text-left">Client</th>
                                        <th class="p-2 text-left">Date</th>
                                        <th class="p-2 text-right">Total</th>
                                        <th class="p-2 text-center">Status</th>
                                        <th class="p-2 text-center no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="history-table-body"></tbody>
                            </table>
                        </div>

                        <div class="mt-4 no-print" id="bulk-toolbar" style="display: none;">
                            <div class="flex flex-wrap gap-2 items-center">
                                <button id="bulk-delete" class="action-btn danger-btn text-xs">
                                    <i class="fas fa-trash mr-1"></i> Delete Selected
                                </button>
                                <select id="bulk-status" class="compact-input text-xs w-32">
                                    <option value="">Bulk Status</option>
                                    <option value="Paid">Mark Paid</option>
                                    <option value="Pending">Mark Pending</option>
                                    <option value="Overdue">Mark Overdue</option>
                                </select>
                                <button id="bulk-pdf" class="action-btn secondary-btn text-xs">
                                    <i class="fas fa-file-pdf mr-1"></i> Download PDFs
                                </button>
                                <button id="bulk-print" class="action-btn secondary-btn text-xs">
                                    <i class="fas fa-print mr-1"></i> Print Selected
                                </button>
                                <button id="bulk-email" class="action-btn secondary-btn text-xs">
                                    <i class="fas fa-envelope mr-1"></i> Email Selected
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
                <style>
                    /* ===============================
                           Invoice History – Mobile View
                           =============================== */
                    @media (max-width: 640px) {

                        /* Header layout */
                        #tab-history .flex.md\:flex-row {
                            flex-direction: column;
                            align-items: flex-start;
                        }

                        /* Search & filter stack */
                        #tab-history .no-print {
                            flex-wrap: wrap;
                            gap: 6px;
                            width: 100%;
                        }

                        #history-search,
                        #history-filter {
                            width: 100% !important;
                        }

                        /* Hide table header */
                        #tab-history thead {
                            display: none;
                        }

                        /* Table → card layout */
                        #tab-history table,
                        #tab-history tbody,
                        #tab-history tr {
                            display: block;
                            width: 100%;
                        }

                        #tab-history tr {
                            background: #fff;
                            border: 1px solid #e5e7eb;
                            border-radius: 12px;
                            padding: 10px;
                            margin-bottom: 12px;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                        }

                        #tab-history td {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            padding: 6px 0;
                            border: none;
                            font-size: 13px;
                        }

                        /* Label before each value */
                        #tab-history td::before {
                            content: attr(data-label);
                            font-weight: 600;
                            color: #64748b;
                            font-size: 12px;
                        }

                        /* Checkbox row */
                        #tab-history td:first-child {
                            justify-content: flex-start;
                            margin-bottom: 6px;
                        }

                        /* Total amount alignment */
                        #tab-history td.text-right {
                            justify-content: space-between;
                        }

                        /* Status badge center */
                        #tab-history td.text-center {
                            justify-content: space-between;
                        }

                        /* Actions stacked */
                        #tab-history td.no-print {
                            justify-content: flex-end;
                            gap: 6px;
                            margin-top: 6px;
                        }

                        /* Bulk toolbar */
                        #bulk-toolbar .flex {
                            flex-direction: column;
                            align-items: stretch;
                        }

                        #bulk-toolbar button,
                        #bulk-toolbar select {
                            width: 100%;
                        }
                    }
                </style>

            </main>
        </div>

        <!-- Toast Notification Container -->
        <div id="toast-container"></div>

        <!-- All your modals here -->
        <script>
            /* =========================        ===
        SOCIAL CULTS INVOICE PRO
        Database Integration Version
        ============================ */
            // Configuration
            const API_BASE_URL = '{{ url("/") }}';
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
            let currentCompany = @json($company);
            // DOM Helper
            const $ = id => document.getElementById(id);
            // Service List
            const SERVICE_LIST = [
                "Social Media Management", "Social Media Ads", "Content Creation", "Graphic Designing",
                "Website Development", "Website Re-Design", "Website Maintenance", "E-Commerce Development",
                "SEO / Local SEO", "Google Ads (SEM)", "Meta Ads", "ORM (Online Reputation Management)",
                "Influencer Marketing", "Brand Strategy", "UI/UX Design", "Video Editing", "Motion Graphics",
                "Paid Campaign Management", "Content Calendar", "Reels / Short Video Editing", "Custom Service"
            ];
            // Default Terms & Conditions
            const DEFAULT_TERMS = [
                "Payment is due within 7 days from the invoice date",
                "Late payments may attract interest charges of 1.5% per month",
                "All services are provided as per the agreed scope of work",
                "Any discrepancies must be reported within 7 days of receipt",
                "The invoice is payable in Indian Rupees unless otherwise specified",
                "All taxes are as per applicable GST regulations"
            ];
            /* ========== UTILITY FUNCTIONS ========== */
            function showToast(message, type = 'success') {
                const container = document.getElementById('toast-container');
                if (!container) return;

                const toast = document.createElement('div');
                toast.className = `toast-notification ${type === 'success' ? 'toast-success' : 'toast-error'}`;
                toast.innerHTML = `
                                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                                <span>${message}</span>
                            `;

                // Animation
                toast.style.animation = 'slideInRight 0.3s forwards';

                container.appendChild(toast);

                // Remove after 3 seconds
                setTimeout(() => {
                    toast.style.animation = 'slideOutRight 0.3s forwards';
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            }

            function money(sym, n) {
                n = Number(n || 0);
                return sym + n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
            function statusPill(status) {
                if (status === 'Paid') return `<span class="pill bg-emerald-100 text-emerald-800">${status}</span>`;
                if (status === 'Overdue') return `<span class="pill bg-red-100 text-red-700">${status}</span>`;
                return `<span class="pill bg-yellow-100 text-yellow-800">${status}</span>`;
            }
            /* ========== COMPANY MANAGEMENT ========== */
            async function loadCompanies() {
                try {
                    const response = await fetch(`${API_BASE_URL}/company`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });
                    const data = await response.json();

                    if (data.success) {
                        return data.companies;
                    }
                    return [];
                } catch (error) {
                    console.error('Error loading companies:', error);
                    return [];
                }
            }
            async function saveCompany(companyData) {
                try {
                    const formData = new FormData();

                    // Add all company data to FormData
                    Object.keys(companyData).forEach(key => {
                        if (key === 'logo' && companyData[key] instanceof File) {
                            formData.append('logo', companyData[key]);
                        } else if (key === 'logo_data' && companyData[key]) {
                            formData.append('logo_data', companyData[key]);
                        } else {
                            formData.append(key, companyData[key]);
                        }
                    });

                    const response = await fetch(`${API_BASE_URL}/company`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();
                    if (result.success) {
                        currentCompany = result.company;
                    }
                    return result;
                } catch (error) {
                    console.error('Error saving company:', error);
                    return { success: false, message: 'Network error' };
                }
            }
            async function deleteCompany(id) {
                try {
                    const response = await fetch(`${API_BASE_URL}/company/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        }
                    });

                    return await response.json();
                } catch (error) {
                    console.error('Error deleting company:', error);
                    return { success: false, message: 'Network error' };
                }
            }
            /* ========== COMPANY MANAGEMENT UI ========== */
            function setupCompanyManagement() {
                const editCompanyBtn = $('btn-manage-company');
                if (editCompanyBtn) {
                    editCompanyBtn.addEventListener('click', openCompanyEditor);
                }
            }
            function openCompanyEditor(e) {
                // Prevent any default behavior if this was triggered by a click event
                if (e && e.preventDefault) e.preventDefault();

                // Close any existing modal first
                closeCompanyEditor();

                // Create modal HTML for company editor
                const modalHTML = `
                                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" id="company-modal">
                                    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl modal-center " id="company-modal-content">
                                        <div class="p-6">
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-lg font-semibold text-slate-800">Edit Company Details</h3>
                                                <button type="button" class="icon-btn secondary-btn" id="modal-close-btn">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>

                                            <form id="company-form" class="space-y-4">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="compact-label">Company Name *</label>
                                                        <input type="text" id="edit-company-name" class="compact-input" value="${currentCompany.name || ''}" required>
                                                    </div>
                                                    <div>
                                                        <label class="compact-label">Logo</label>
                                                        <div id="logo-dropzone" class="dropzone mb-2 text-xs">
                                                            Click or drop logo image (PNG, JPG, WebP)
                                                        </div>
                                                        <input id="edit-logo-file" type="file" accept="image/*" class="hidden" />
                                                        <div id="logo-preview" class="border rounded p-2 h-20 flex items-center justify-center text-xs text-slate-500">
                                                            ${currentCompany.logo ? `<img src="/storage/${currentCompany.logo}" class="max-h-16 object-contain" alt="Logo">` : 'No logo uploaded'}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="compact-label">Address</label>
                                                    <textarea id="edit-company-address" rows="2" class="compact-input" placeholder="Full company address">${currentCompany.address || ''}</textarea>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="compact-label">Contact Info</label>
                                                        <input type="text" id="edit-company-contact" class="compact-input" value="${currentCompany.contact || ''}" placeholder="Phone, email, etc.">
                                                    </div>
                                                    <div>
                                                        <label class="compact-label">GSTIN</label>
                                                        <input type="text" id="edit-company-gstin" class="compact-input" value="${currentCompany.gstin || ''}" placeholder="Company GST Number">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="compact-label">Bank Details</label>
                                                    <textarea id="edit-company-bank" rows="3" class="compact-input" placeholder="Bank name, account number, IFSC, etc.">${currentCompany.bank_details || ''}</textarea>
                                                </div>

                                                <div class="pt-4 border-t border-slate-200">
                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" class="action-btn secondary-btn" id="modal-cancel-btn">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" class="action-btn primary-btn" id="modal-save-btn">
                                                            Save Company Details
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            `;

                // Add new modal to body
                document.body.insertAdjacentHTML('beforeend', modalHTML);

                // Setup event listeners with proper event delegation
                const modal = document.getElementById('company-modal');

                // Click outside to close
                modal.addEventListener('click', function (event) {
                    if (event.target === this) {
                        closeCompanyEditor();
                    }
                });

                // Close button
                document.getElementById('modal-close-btn').addEventListener('click', closeCompanyEditor);

                // Cancel button
                document.getElementById('modal-cancel-btn').addEventListener('click', closeCompanyEditor);

                // Form submission
                document.getElementById('company-form').addEventListener('submit', function (e) {
                    e.preventDefault();
                    saveCompanyDetails(e);
                });

                // Setup logo upload functionality
                setTimeout(() => {
                    setupLogoUpload();
                }, 100);
            }
            function closeCompanyEditor() {
                const modal = document.getElementById('company-modal');
                if (modal) {
                    modal.remove();
                }
            }
            function setupLogoUpload() {
                const dropzone = document.getElementById('logo-dropzone');
                const fileInput = document.getElementById('edit-logo-file');
                const preview = document.getElementById('logo-preview');

                if (!dropzone || !fileInput || !preview) return;

                // Remove any existing event listeners by cloning and replacing
                const newDropzone = dropzone.cloneNode(true);
                dropzone.parentNode.replaceChild(newDropzone, dropzone);

                const newFileInput = fileInput.cloneNode(true);
                fileInput.parentNode.replaceChild(newFileInput, fileInput);

                // Get fresh references
                const freshDropzone = document.getElementById('logo-dropzone');
                const freshFileInput = document.getElementById('edit-logo-file');
                const freshPreview = document.getElementById('logo-preview');

                freshDropzone.addEventListener('click', () => freshFileInput.click());
                freshDropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    freshDropzone.style.borderColor = '#94a3b8';
                });
                freshDropzone.addEventListener('dragleave', () => {
                    freshDropzone.style.borderColor = '#cbd5e1';
                });
                freshDropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    freshDropzone.style.borderColor = '#cbd5e1';

                    if (e.dataTransfer.files.length) {
                        handleLogoUpload(e.dataTransfer.files[0]);
                    }
                });

                freshFileInput.addEventListener('change', (e) => {
                    if (e.target.files.length) {
                        handleLogoUpload(e.target.files[0]);
                    }
                });
            }
            function handleLogoUpload(file) {
                if (!file.type.match('image.*')) {
                    showToast('Please select an image file (PNG, JPG, WebP)', 'error');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) { // 5MB limit
                    showToast('File size must be less than 5MB', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.getElementById('logo-preview');
                    if (preview) {
                        preview.innerHTML = `<img src="${e.target.result}" class="max-h-16 object-contain" alt="Logo" />`;
                        // Store the image data for saving
                        preview.dataset.logoData = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
            async function saveCompanyDetails(e) {
                e.preventDefault();

                const formData = {
                    company_id: currentCompany.id,
                    name: document.getElementById('edit-company-name').value.trim(),
                    address: document.getElementById('edit-company-address').value.trim(),
                    contact: document.getElementById('edit-company-contact').value.trim(),
                    gstin: document.getElementById('edit-company-gstin').value.trim(),
                    bank_details: document.getElementById('edit-company-bank').value.trim(),
                };

                // Get logo data if uploaded
                const preview = document.getElementById('logo-preview');
                if (preview && preview.dataset.logoData) {
                    formData.logo_data = preview.dataset.logoData;
                }

                if (!formData.name) {
                    showToast('Company name is required', 'error');
                    return;
                }

                try {
                    const result = await saveCompany(formData);
                    if (result.success) {
                        // Update current company data
                        currentCompany = result.company;

                        // Update logo in header and preview
                        const mainLogo = document.getElementById('main-logo');
                        const previewLogo = document.getElementById('preview-logo');

                        if (mainLogo && result.company.logo) {
                            mainLogo.src = `/storage/${result.company.logo}`;
                        }
                        if (previewLogo && result.company.logo) {
                            previewLogo.src = `/storage/${result.company.logo}`;
                        }

                        // Update company info in preview
                        const previewCompany = $('preview-company');
                        if (previewCompany) {
                            previewCompany.textContent = `${currentCompany.name || ''}\n${currentCompany.address || ''}\n${currentCompany.contact || ''}${currentCompany.gstin ? '\nGSTIN: ' + currentCompany.gstin : ''}`;
                        }

                        // Update bank details in preview
                        const previewBank = $('preview-bank');
                        if (previewBank) {
                            previewBank.textContent = currentCompany.bank_details || '';
                        }

                        showToast('Company details saved successfully!', 'success');
                        closeCompanyEditor();
                    } else {
                        showToast('Error saving company: ' + (result.message || 'Unknown error'), 'error');
                    }
                } catch (error) {
                    console.error('Error saving company:', error);
                    showToast('Error saving company details. Please try again.', 'error');
                }
            }
            /* ========== INVOICE MANAGEMENT ========== */
            async function saveInvoice(invoiceData) {
                const url = invoiceData.id ?
                    `${API_BASE_URL}/invoices/${invoiceData.id}` :
                    `${API_BASE_URL}/invoices`;

                const method = invoiceData.id ? 'PUT' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify(invoiceData)
                    });

                    return await response.json();
                } catch (error) {
                    console.error('Error saving invoice:', error);
                    return { success: false, message: 'Network error' };
                }
            }
            async function loadInvoice(id) {
                try {
                    const response = await fetch(`${API_BASE_URL}/invoices/${id}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });

                    return await response.json();
                } catch (error) {
                    console.error('Error loading invoice:', error);
                    return { success: false, message: 'Network error' };
                }
            }
            async function deleteInvoice(id) {
                try {
                    const response = await fetch(`${API_BASE_URL}/invoices/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });

                    return await response.json();
                } catch (error) {
                    console.error('Error deleting invoice:', error);
                    return { success: false, message: 'Network error' };
                }
            }
            async function bulkUpdateStatus(ids, status) {
                try {
                    const response = await fetch(`${API_BASE_URL}/invoices/bulk-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({ ids, status })
                    });

                    return await response.json();
                } catch (error) {
                    console.error('Error bulk updating:', error);
                    return { success: false, message: 'Network error' };
                }
            }
            async function bulkDeleteInvoices(ids) {
                try {
                    const response = await fetch(`${API_BASE_URL}/invoices/bulk-delete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({ ids })
                    });

                    return await response.json();
                } catch (error) {
                    console.error('Error bulk deleting:', error);
                    return { success: false, message: 'Network error' };
                }
            }
            /* ========== SIGNATURE MANAGEMENT ========== */
            async function saveSignature(signatureData) {
                try {
                    const response = await fetch(`${API_BASE_URL}/company/signature`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({ signature: signatureData })
                    });

                    return await response.json();
                } catch (error) {
                    console.error('Error saving signature:', error);
                    return { success: false, message: 'Network error' };
                }
            }
            async function loadSignature() {
                try {
                    const response = await fetch(`${API_BASE_URL}/company/signature`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });

                    const data = await response.json();
                    if (data.success && data.signature) {
                        return data.signature;
                    }
                    return null;
                } catch (error) {
                    console.error('Error loading signature:', error);
                    return null;
                }
            }
            /* ========== TERMS & CONDITIONS MANAGEMENT ========== */
            function loadTerms() {
                // For now, use localStorage for terms
                try {
                    const savedTerms = localStorage.getItem('sc_terms_pro');
                    return savedTerms ? JSON.parse(savedTerms) : [...DEFAULT_TERMS];
                } catch {
                    return [...DEFAULT_TERMS];
                }
            }
            function saveTerms(terms) {
                localStorage.setItem('sc_terms_pro', JSON.stringify(terms));
            }
            function renderTerms() {
                const terms = loadTerms();
                const screenList = $('terms-list');
                const printList = $('print-terms-list');

                if (!screenList || !printList) return;

                screenList.innerHTML = '';
                printList.innerHTML = '';

                terms.forEach(term => {
                    const screenLi = document.createElement('li');
                    screenLi.textContent = term;
                    screenLi.className = 'mb-1';
                    screenList.appendChild(screenLi);

                    const printLi = document.createElement('li');
                    printLi.textContent = term;
                    printLi.className = 'mb-1';
                    printList.appendChild(printLi);
                });
            }
            /* ========== INVOICE ITEMS MANAGEMENT ========== */
            function addItemRow(data = {}) {
                const id = 'row_' + Date.now() + '_' + Math.floor(Math.random() * 999);
                const tr = document.createElement('tr');
                tr.id = id;

                // Set default values
                const defaultDesc = data.desc || '';
                const defaultService = data.service || '';
                const defaultQty = data.qty || 1;
                const defaultPrice = data.price || 0;
                tr.innerHTML = `
                        <td class="p-2 text-xs sno w-8 sm:w-10">0</td>
                        <td class="p-2 min-w-[180px]">
                            <input class="w-full compact-input desc" placeholder="Item description"
                                   value="${defaultDesc}"
                                   required />
                        </td>
                        <td class="p-2 min-w-[120px] sm:w-40">
                          <select class="w-full compact-input service">
                            <option value="">Service Type</option>
                            ${SERVICE_LIST.map(s => `<option value="${s}" ${defaultService === s ? 'selected' : ''}>${s}</option>`).join('')}
                          </select>
                        </td>
                        <td class="p-2 text-right w-16 sm:w-20">
                            <input type="number" min="1" step="1" class="w-full compact-input qty text-right"
                                   value="${defaultQty}" required />
                        </td>
                        <td class="p-2 text-right w-20 sm:w-28">
                            <input type="number" min="0" step="0.01" class="w-full compact-input price text-right"
                                   value="${defaultPrice}" required />
                        </td>
                        <td class="p-2 text-right line-total font-medium text-xs w-20 sm:w-28">₹0.00</td>
                        <td class="p-2 text-center w-8 sm:w-10 no-print">
                          <button class="icon-btn danger-btn icon-btn-sm" onclick="removeItemRow('${id}')" title="Remove item">
                            <i class="fas fa-times"></i>
                          </button>
                        </td>
                        `;
                const tbody = $('items-body');
                if (tbody) {
                    tbody.appendChild(tr);

                    // Add event listeners with validation
                    ['input', 'change'].forEach(ev => {
                        tr.querySelector('.qty').addEventListener(ev, function () {
                            if (this.value < 1) this.value = 1;
                            calculateAndRender();
                        });

                        tr.querySelector('.price').addEventListener(ev, function () {
                            if (this.value < 0) this.value = 0;
                            calculateAndRender();
                        });

                        tr.querySelector('.desc').addEventListener(ev, calculateAndRender);
                        tr.querySelector('.service').addEventListener(ev, calculateAndRender);
                    });

                    updateItemSerials();
                    calculateAndRender();

                    // Focus on description field
                    setTimeout(() => tr.querySelector('.desc').focus(), 10);
                }
            }
            function removeItemRow(id) {
                const el = document.getElementById(id);
                if (el) el.remove();
                updateItemSerials();
                calculateAndRender();
            }
            window.removeItemRow = removeItemRow;
            function updateItemSerials() {
                const tbody = $('items-body');
                if (!tbody) return;

                document.querySelectorAll('#items-body tr').forEach((tr, index) => {
                    const snoCell = tr.querySelector('.sno');
                    if (snoCell) snoCell.textContent = index + 1;
                });
            }
            /* ========== INVOICE DATA GATHERING ========== */
            let currentInvoiceId = null;
            function gatherInvoiceForm() {
                const items = [];
                const tbody = $('items-body');

                if (tbody) {
                    document.querySelectorAll('#items-body tr').forEach(tr => {
                        const desc = tr.querySelector('.desc')?.value || '';
                        const service = tr.querySelector('.service')?.value || '';
                        const qty = Number(tr.querySelector('.qty')?.value || 0);
                        const price = Number(tr.querySelector('.price')?.value || 0);
                        const line = qty * price;

                        // Only add if there's a description or service
                        if (desc || service) {
                            items.push({
                                description: desc,
                                service_type: service,
                                quantity: qty,
                                price: price,
                                line: line
                            });
                        }
                    });
                }

                // Get signature from preview
                const sigImage = document.querySelector('#sig-image img');
                const adminSignature = sigImage ? sigImage.src : null;

                const terms = loadTerms();

                // Get invoice date - ensure it's properly formatted
                let invoiceDate = $('invoice-date')?.value;
                if (!invoiceDate) {
                    // Default to today if no date is set
                    invoiceDate = new Date().toISOString().slice(0, 10);
                }

                // Get client name - ensure it's not empty
                const clientName = $('client-name')?.value.trim() || '';

                // Get invoice number
                let invoiceNumber = $('invoice-number')?.value || '';

                // If no invoice number, generate a temporary one (will be replaced by backend)
                if (!invoiceNumber && !currentInvoiceId) {
                    const year = new Date().getFullYear();
                    invoiceNumber = `TEMP-INV-${year}-${Date.now()}`;
                }

                // Get discount value
                const discount_percent = parseFloat($('discount')?.value || 0);


                return {
                    id: currentInvoiceId,
                    invoice_number: invoiceNumber,
                    invoice_date: invoiceDate,
                    status: $('invoice-status')?.value || 'Pending',
                    currency: $('currency')?.value || '₹',
                    tax_rate: parseFloat($('tax-slab')?.value || 0),
                    tax_mode: $('tax-mode')?.value || 'cgst',
                    discount_percent: parseFloat($('discount')?.value || 0),

                   
                    client_name: clientName,
                    client_phone: $('client-phone')?.value.trim() || '',
                    client_email: $('client-email')?.value.trim() || '',
                    client_address: $('client-address')?.value.trim() || '',
                    description: $('invoice-description')?.value.trim() || '',
                    items: items,
                    admin_signature: adminSignature,
                    terms: terms,
                };

            }
            /* ========== CALCULATIONS & RENDERING ========== */
            function computeTotals(items, taxRate, discountPercent) {
                const subtotal = items.reduce((sum, item) => sum + (item.line || 0), 0);
                const taxAmount = subtotal * (taxRate || 0);

                const grossTotal = subtotal + taxAmount;
                const discountAmount = grossTotal * ((discountPercent || 0) / 100);

                const grandTotal = grossTotal - discountAmount;

                return {
                    subtotal,
                    taxAmount,
                    discountAmount,
                    grandTotal
                };
            }

            async function calculateAndRender() {
                const inv = gatherInvoiceForm();
                const totals = computeTotals(
                    inv.items,
                    inv.tax_rate,
                    inv.discount_percent
                );

                const sym = inv.currency || '₹';

                // Update line totals in items table
                document.querySelectorAll('#items-body tr').forEach((tr, idx) => {
                    const line = inv.items[idx]?.line || 0;
                    const lineTotal = tr.querySelector('.line-total');
                    if (lineTotal) {
                        lineTotal.textContent = money(sym, line);
                    }
                });

                // Update preview header
                const previewCompany = $('preview-company');
                if (previewCompany) {
                    previewCompany.textContent = `${currentCompany.name || ''}\n${currentCompany.address || ''}\n${currentCompany.contact || ''}${currentCompany.gstin ? '\nGSTIN: ' + currentCompany.gstin : ''}`;
                }

                const previewNumber = $('preview-number');
                if (previewNumber) {
                    previewNumber.textContent = inv.number;
                }

                const previewDate = $('preview-date');
                if (previewDate) {
                    previewDate.textContent = inv.date;
                }

                const previewStatus = $('preview-status');
                if (previewStatus) {
                    previewStatus.innerHTML = statusPill(inv.status);
                }

                // Update client info
                const clientLines = [];
                if (inv.client_name) clientLines.push(inv.client_name);
                if (inv.client_phone) clientLines.push(`Phone: ${inv.client_phone}`);
                if (inv.client_email) clientLines.push(`Email: ${inv.client_email}`);
                if (inv.client_address) clientLines.push(inv.client_address);

                const previewClient = $('preview-client');
                if (previewClient) {
                    previewClient.textContent = clientLines.join('\n');
                }

                // Update description
                const screenDescription = $('screen-description');
                if (screenDescription) {
                    screenDescription.textContent = inv.description || 'No description provided';
                }

                const printDescriptionText = $('print-description-text');
                if (printDescriptionText) {
                    printDescriptionText.textContent = inv.description || '';
                }

                // Update items in preview
                const previewLines = $('preview-lines');
                if (previewLines) {
                    previewLines.innerHTML = '';

                    inv.items.forEach((item, idx) => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                                    <td class="p-2 text-xs">${idx + 1}</td>
                                    <td class="p-2 text-xs">${item.description || item.service_type || ''}</td>
                                    <td class="p-2 text-right text-xs">${item.quantity}</td>
                                    <td class="p-2 text-right text-xs">${money(sym, item.price)}</td>
                                    <td class="p-2 text-right text-xs">${money(sym, item.line)}</td>
                                `;
                        previewLines.appendChild(tr);
                    });
                }

                // Update tax labels and amounts
                const ratePct = (inv.tax_rate * 100).toFixed(0);

                const previewTaxLabel = $('preview-tax-label');
                const previewTax = $('preview-tax');
                const sideTaxLabel = $('side-tax-label');

                if (previewTaxLabel && previewTax && sideTaxLabel) {
                    if (inv.tax_rate === 0) {
                        previewTaxLabel.textContent = 'Tax';
                        previewTax.textContent = money(sym, 0);
                        sideTaxLabel.textContent = 'Tax';
                    } else if (inv.tax_mode === 'cgst') {
                        const half = totals.taxAmount / 2;
                        previewTaxLabel.textContent = `CGST ${ratePct / 2}% + SGST ${ratePct / 2}%`;
                        previewTax.textContent = `${money(sym, half)} + ${money(sym, half)}`;
                        sideTaxLabel.textContent = `GST ${ratePct}% (CGST+SGST)`;
                    } else {
                        previewTaxLabel.textContent = `IGST ${ratePct}%`;
                        previewTax.textContent = money(sym, totals.taxAmount);
                        sideTaxLabel.textContent = `GST ${ratePct}% (IGST)`;
                    }
                }

                // Update totals
                const updateElement = (id, value) => {
                    const el = $(id);
                    if (el) el.textContent = value;
                };

                updateElement('preview-subtotal', money(sym, totals.subtotal));
                updateElement('preview-discount', money(sym, totals.discountAmount));
                updateElement('preview-total', money(sym, totals.grandTotal));

                updateElement('side-subtotal', money(sym, totals.subtotal));
                updateElement('side-tax', money(sym, totals.taxAmount));
                updateElement('side-discount', money(sym, totals.discountAmount));
                updateElement('side-total', money(sym, totals.grandTotal));

                // Update bank details
                const previewBank = $('preview-bank');
                if (previewBank) {
                    previewBank.textContent = currentCompany.bank_details || '';
                }

                // Update signature
                const sigImage = $('sig-image');
                const printSigImage = $('print-sig-image');

                if (inv.admin_signature) {
                    if (sigImage) {
                        sigImage.innerHTML = `<img src="${inv.admin_signature}" class="max-h-20 object-contain" alt="Signature" />`;
                        sigImage.classList.remove('text-slate-400');
                    }
                    if (printSigImage) {
                        printSigImage.innerHTML = `<img src="${inv.admin_signature}" class="max-h-16 object-contain" alt="Signature" />`;
                        printSigImage.classList.remove('text-slate-400');
                    }
                } else {
                    if (sigImage) {
                        sigImage.innerHTML = 'No signature uploaded';
                        sigImage.classList.add('text-slate-400');
                    }
                    if (printSigImage) {
                        printSigImage.innerHTML = 'No signature uploaded';
                        printSigImage.classList.add('text-slate-400');
                    }
                }
            }
            /* ========== INVOICE SAVING ========== */
            async function saveInvoiceToStorage() {
                // Validate required fields
                const clientName = $('client-name')?.value.trim();
                if (!clientName) {
                    showToast('Please enter client name', 'error');
                    $('client-name').focus();
                    return;
                }

                const itemsBody = $('items-body');
                if (!itemsBody || itemsBody.children.length === 0) {
                    showToast('Please add at least one item to the invoice', 'error');
                    $('add-item').focus();
                    return;
                }

                // Validate that all items have descriptions
                let hasValidItems = false;
                const invalidItems = [];

                document.querySelectorAll('#items-body tr').forEach((tr, index) => {
                    const desc = tr.querySelector('.desc')?.value.trim() || '';
                    const service = tr.querySelector('.service')?.value || '';
                    const qty = parseFloat(tr.querySelector('.qty')?.value || 0);
                    const price = parseFloat(tr.querySelector('.price')?.value || 0);

                    if (!desc && !service) {
                        invalidItems.push(index + 1);
                    }

                    if (desc || service) {
                        if (qty <= 0) {
                            showToast(`Item ${index + 1}: Quantity must be greater than 0`, 'error');
                            tr.querySelector('.qty').focus();
                            throw new Error('Invalid quantity');
                        }

                        if (price < 0) {
                            showToast(`Item ${index + 1}: Price cannot be negative`, 'error');
                            tr.querySelector('.price').focus();
                            throw new Error('Invalid price');
                        }

                        hasValidItems = true;
                    }
                });

                if (invalidItems.length > 0) {
                    showToast(`Please add description or service type for item(s): ${invalidItems.join(', ')}`, 'error');
                    return;
                }

                if (!hasValidItems) {
                    showToast('Please add at least one valid item with description or service type', 'error');
                    return;
                }

                // Validate invoice date
                const invoiceDate = $('invoice-date')?.value;
                if (!invoiceDate) {
                    showToast('Please select an invoice date', 'error');
                    $('invoice-date').focus();
                    return;
                }

               const inv = gatherInvoiceForm();
    const totals = computeTotals(inv.items, inv.tax_rate, inv.discount_percent); // ✅ Make sure this is discount_percent

    // Add calculated totals
    inv.subtotal = totals.subtotal;
    inv.tax_amount = totals.taxAmount;
    inv.discount_amount = totals.discountAmount;
    inv.grand_total = totals.grandTotal;

    console.log('Saving invoice data:', inv); // ✅ Check console to verify discount_percent is present

    try {
        const result = await saveInvoice(inv);

        if (result.success) {
            $('btn-save').textContent = currentInvoiceId ? 'Invoice Updated!' : 'Invoice Saved!';

            // Update invoice number if it was generated
            if (result.invoice && result.invoice.invoice_number) {
                $('invoice-number').value = result.invoice.invoice_number;
                currentInvoiceId = result.invoice.id;
            }

            // Update UI
            await renderHistory();
            await renderSavedList();

            setTimeout(() => {
                $('btn-save').textContent = currentInvoiceId ? 'Update Invoice' : 'Save Invoice';
            }, 2000);

            if (!$('tab-history').classList.contains('hidden')) {
                await renderHistory();
            }

        } else {
            showToast('Error saving invoice: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error in saveInvoiceToStorage:', error);
        showToast('Error saving invoice: ' + error.message, 'error');
    }
}
            /* ========== EVENT LISTENERS SETUP ========== */
            function setupEventListeners() {
                const addItemBtn = $('add-item');
                if (addItemBtn) {
                    addItemBtn.addEventListener('click', () => addItemRow());
                }

                // Add a default item button if needed
                const addDefaultBtn = document.createElement('button');
                addDefaultBtn.className = 'action-btn secondary-btn text-xs ml-2';
                addDefaultBtn.innerHTML = '<i class="fas fa-magic mr-1"></i> Add Sample';
                addDefaultBtn.addEventListener('click', addDefaultItem);

                const buttonContainer = addItemBtn?.parentNode;
                if (buttonContainer) {
                    buttonContainer.appendChild(addDefaultBtn);
                }

                const clearItemsBtn = $('clear-items');
                if (clearItemsBtn) {
                    clearItemsBtn.addEventListener('click', () => {
                        const itemsBody = $('items-body');
                        if (!itemsBody || itemsBody.children.length === 0) {
                            showToast('No items to clear', 'error');
                            return;
                        }

                        if (!confirm('Clear all items?')) return;
                        itemsBody.innerHTML = '';
                        // Add one default item after clearing
                        addDefaultItem();
                        calculateAndRender();
                    });
                }

                const saveBtn = $('btn-save');
                if (saveBtn) {
                    saveBtn.addEventListener('click', async () => {
                        await calculateAndRender();
                        await saveInvoiceToStorage();
                    });
                }

                // Add auto-calculation event listeners
                const autoCalcFields = [
                    'client-name', 'client-phone', 'client-email', 'client-address',
                    'invoice-status', 'currency', 'tax-slab', 'tax-mode', 'discount',
                    'invoice-description'
                ];

                autoCalcFields.forEach(id => {
                    const el = $(id);
                    if (el) {
                        el.addEventListener('input', calculateAndRender);
                        el.addEventListener('change', calculateAndRender);
                    }
                });

                // Add date field change listener
                const invoiceDate = $('invoice-date');
                if (invoiceDate) {
                    invoiceDate.addEventListener('change', calculateAndRender);
                }
            }
            /* ========== INITIALIZATION ========== */
            async function init() {
                // Load terms
                renderTerms();

                // Set default date if not already set
                const invoiceDate = $('invoice-date');
                if (invoiceDate && !invoiceDate.value) {
                    invoiceDate.valueAsDate = new Date();
                }

                // Prepare new invoice
                prepareNewInvoice();

                // Setup event listeners
                setupEventListeners();

                // Calculate and render initial state
                calculateAndRender();
            }
            function prepareNewInvoice() {
                currentInvoiceId = null;

                // Clear client fields
                const clearField = (id) => {
                    const el = $(id);
                    if (el) el.value = '';
                };

                clearField('client-name');
                clearField('client-phone');
                clearField('client-email');
                clearField('client-address');
                clearField('invoice-description');

                // Set default values
                const setValue = (id, value) => {
                    const el = $(id);
                    if (el) el.value = value;
                };

                // Set default date (today)
                const today = new Date().toISOString().slice(0, 10);
                setValue('invoice-date', today);


                setValue('invoice-status', 'Pending');
                setValue('currency', '₹');
                setValue('tax-slab', '0.18');
                setValue('tax-mode', 'cgst');
                setValue('discount', '0');

                // Clear items and add one default item
                const itemsBody = $('items-body');
                if (itemsBody) {
                    itemsBody.innerHTML = '';
                    // Add one default item
                    addDefaultItem();
                }

                // Update button text
                const saveBtn = $('btn-save');
                if (saveBtn) {
                    saveBtn.textContent = 'Save Invoice';
                }

                calculateAndRender();

                // Focus on client name field
                setTimeout(() => {
                    const clientName = $('client-name');
                    if (clientName) clientName.focus();
                }, 100);
            }
            function addDefaultItem() {
                addItemRow({
                    desc: 'Website Development Service',
                    service: 'Website Development',
                    qty: 1,
                    price: 5000
                });
            }
            /* ========== PDF DOWNLOAD ========== */
            async function downloadInvoicePDF(id = null) {
                if (id) {
                    // Download existing invoice
                    window.location.href = `${API_BASE_URL}/invoices/${id}/download`;
                } else {
                    // Save current invoice first, then download
                    const result = await saveInvoice(gatherInvoiceForm());
                    if (result.success) {
                        window.location.href = `${API_BASE_URL}/invoices/${result.invoice.id}/download`;
                    }
                }
            }
            /* ========== PRINT ========== */
            async function printInvoice(id = null) {
                if (id) {
                    // Load existing invoice for printing
                    const result = await loadInvoice(id);
                    if (result.success) {
                        loadInvoiceForPrint(result.invoice);
                        setTimeout(() => window.print(), 500);
                    }
                } else {
                    // Print current invoice
                    calculateAndRender();
                    setTimeout(() => {
                        // Apply print styles
                        document.querySelectorAll('.print-only, .print-description, .print-signature, .print-terms').forEach(el => {
                            el.classList.remove('hidden');
                            if (el.classList.contains('print-signature')) {
                                el.style.float = 'right';
                                el.style.textAlign = 'right';
                                el.style.width = '200px';
                                el.style.marginTop = '40px';
                                el.style.marginRight = '0';
                            }
                            if (el.classList.contains('print-description')) {
                                el.style.clear = 'both';
                            }
                        });

                        document.querySelectorAll('.screen-only, .edit-terms-btn').forEach(el => {
                            el.classList.add('hidden');
                        });

                        window.print();

                        // Restore after printing
                        setTimeout(() => {
                            document.querySelectorAll('.print-only, .print-description, .print-signature, .print-terms').forEach(el => {
                                el.classList.add('hidden');
                                el.style.float = '';
                                el.style.textAlign = '';
                                el.style.width = '';
                                el.style.marginTop = '';
                                el.style.marginRight = '';
                                el.style.clear = '';
                            });

                            document.querySelectorAll('.screen-only, .edit-terms-btn').forEach(el => {
                                el.classList.remove('hidden');
                            });
                        }, 500);
                    }, 100);
                }
            }
            function loadInvoiceForPrint(invoice) {
    // Load invoice data into the form for printing
    currentInvoiceId = invoice.id;

    // Set basic invoice info
    if ($('invoice-number')) $('invoice-number').value = invoice.invoice_number;
    if ($('invoice-date')) $('invoice-date').value = invoice.invoice_date;
    if ($('invoice-status')) $('invoice-status').value = invoice.status;
    if ($('currency')) $('currency').value = invoice.currency;
    if ($('tax-slab')) $('tax-slab').value = invoice.tax_rate;
    if ($('tax-mode')) $('tax-mode').value = invoice.tax_mode;
    
    // ✅ CRITICAL FIX: Load discount_percent, not discount
    if ($('discount')) {
        $('discount').value = invoice.discount_percent || 0;
    }

    if ($('client-name')) $('client-name').value = invoice.client_name;
    if ($('client-phone')) $('client-phone').value = invoice.client_phone;
    if ($('client-email')) $('client-email').value = invoice.client_email;
    if ($('client-address')) $('client-address').value = invoice.client_address;
    if ($('invoice-description')) $('invoice-description').value = invoice.description;

    // Load items
    const itemsBody = $('items-body');
    if (itemsBody) {
        itemsBody.innerHTML = '';
        if (invoice.items && invoice.items.length > 0) {
            invoice.items.forEach(item => {
                addItemRow({
                    desc: item.description,
                    service: item.service_type,
                    qty: item.quantity,
                    price: item.price
                });
            });
        } else {
            addDefaultItem();
        }
    }

    // Load signature if exists
    if (invoice.admin_signature) {
        const sigImage = $('sig-image');
        if (sigImage) {
            sigImage.innerHTML = `<img src="${invoice.admin_signature}" class="max-h-20 object-contain" alt="Signature" />`;
            sigImage.classList.remove('text-slate-400');
        }
    }

    // Load terms
    if (invoice.terms) {
        try {
            const terms = typeof invoice.terms === 'string' ? JSON.parse(invoice.terms) : invoice.terms;
            saveTerms(terms);
            renderTerms();
        } catch (e) {
            console.error('Error loading terms:', e);
        }
    }

    calculateAndRender();
}
            /* ========== EMAIL ========== */
            async function emailInvoice(id = null) {
                if (!id) {
                    // Save current invoice first
                    const result = await saveInvoice(gatherInvoiceForm());
                    if (result.success) {
                        id = result.invoice.id;
                    } else {
                        return;
                    }
                }

                const email = prompt('Enter recipient email address:', '');
                if (!email) return;

                const message = prompt('Enter email message (optional):', 'Please find attached invoice.');

                try {
                    const response = await fetch(`${API_BASE_URL}/invoices/${id}/email`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({ email, message })
                    });

                    const result = await response.json();
                    if (result.success) {
                        showToast('Invoice sent via email successfully!', 'success');
                    } else {
                        showToast('Error sending email: ' + result.message, 'error');
                    }
                } catch (error) {
                    console.error('Error sending email:', error);
                    showToast('Error sending email. Please try again.', 'error');
                }
            }
            /* ========== SHARE INVOICE ========== */
            async function shareInvoice(id = null) {
                if (!id) {
                    // Save current invoice first
                    const result = await saveInvoice(gatherInvoiceForm());
                    if (result.success) {
                        id = result.invoice.id;
                    } else {
                        return;
                    }
                }

                try {
                    const response = await fetch(`${API_BASE_URL}/invoices/${id}/share-link`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        const shareUrl = result.share_url;
                        if (navigator.share) {
                            // Use Web Share API if available
                            navigator.share({
                                title: 'Invoice',
                                text: 'Check out this invoice',
                                url: shareUrl,
                            });
                        } else {
                            // Fallback to copy to clipboard
                            navigator.clipboard.writeText(shareUrl).then(() => {
                                showToast('Share link copied to clipboard!', 'success');
                            }).catch(() => {
                                prompt('Copy this link to share:', shareUrl);
                            });
                        }
                    } else {
                        showToast('Error generating share link: ' + result.message, 'error');
                    }
                } catch (error) {
                    console.error('Error sharing invoice:', error);
                    showToast('Error sharing invoice. Please try again.', 'error');
                }
            }
            /* ========== RECENT INVOICES ========== */
            async function loadRecentInvoices() {
                try {
                    const response = await fetch(`${API_BASE_URL}/invoices/recent`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        return result.invoices;
                    }
                    return [];
                } catch (error) {
                    console.error('Error loading recent invoices:', error);
                    return [];
                }
            }
            async function renderSavedList() {
                const invoices = await loadRecentInvoices();
                const container = $('saved-list');

                if (!container) return;

                container.innerHTML = '';

                if (invoices.length === 0) {
                    container.innerHTML = '<div class="text-xs text-slate-500 text-center py-4">No invoices yet</div>';
                    return;
                }

                invoices.forEach(inv => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center justify-between py-2 border-b border-slate-100 last:border-b-0 hover:bg-slate-50 px-1 rounded cursor-pointer';
                    div.onclick = () => openInvoiceModal(inv.id);
                    div.innerHTML = `
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-medium truncate">${inv.invoice_number}</div>
                                        <div class="text-xs text-slate-500 truncate">${inv.client_name || '-'} • ${new Date(inv.invoice_date).toLocaleDateString()}</div>
                                    </div>
                                    <div class="flex gap-1">
                                        <button class="icon-btn secondary-btn icon-btn-sm" onclick="event.stopPropagation(); downloadInvoicePDF(${inv.id})" title="Download PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    </div>
                                `;
                    container.appendChild(div);
                });
            }
            /* ========== HISTORY TAB FUNCTIONS ========== */
            async function renderHistory() {
                const search = $('history-search')?.value || '';
                const filter = $('history-filter')?.value || 'all';

                try {
                    console.log('Loading history with search:', search, 'filter:', filter);

                    const response = await fetch(`${API_BASE_URL}/invoices/history?search=${encodeURIComponent(search)}&filter=${filter}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });

                    const result = await response.json();
                    console.log('History API response:', result);

                    if (!result.success) {
                        console.error('Error loading history:', result.message);
                        return;
                    }

                    const tbody = $('history-table-body');
                    if (!tbody) {
                        console.error('History table body not found');
                        return;
                    }

                    tbody.innerHTML = '';

                    if (!result.invoices || result.invoices.length === 0) {
                        tbody.innerHTML = `
                                        <tr>
                                            <td colspan="8" class="p-4 text-center text-slate-500">
                                                No invoices found. Create your first invoice!
                                            </td>
                                        </tr>
                                    `;
                        return;
                    }

                    console.log('Rendering', result.invoices.length, 'invoices');

                    result.invoices.forEach((inv, index) => {
                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-slate-50';
                        tr.innerHTML = `
                                        <td class="p-2 text-center">
                                            <input type="checkbox" class="history-check" data-id="${inv.id}" />
                                        </td>
                                        <td class="p-2 text-xs">${index + 1}</td>
                                        <td class="p-2 font-medium text-xs">${inv.invoice_number}</td>
                                        <td class="p-2 text-xs">${inv.client_name || '-'}</td>
                                        <td class="p-2 text-xs">${new Date(inv.invoice_date).toLocaleDateString()}</td>
                                        <td class="p-2 text-right font-medium text-xs">${money(inv.currency, inv.grand_total || 0)}</td>
                                        <td class="p-2 text-center">${statusPill(inv.status)}</td>
                                        <td class="p-2 text-center no-print ">
                                            <div class="flex gap-1 justify-center">
                                                <button class="icon-btn secondary-btn icon-btn-sm" onclick="viewInvoice(${inv.id})" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="icon-btn secondary-btn icon-btn-sm" onclick="editInvoice(${inv.id})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="icon-btn secondary-btn icon-btn-sm" onclick="downloadInvoicePDF(${inv.id})" title="Download PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>

                                                <button class="icon-btn danger-btn icon-btn-sm" onclick="deleteInvoice(${inv.id})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    `;
                        tbody.appendChild(tr);
                    });

                    // Setup bulk selection
                    setupBulkSelection();

                } catch (error) {
                    console.error('Error rendering history:', error);
                    const tbody = $('history-table-body');
                    if (tbody) {
                        tbody.innerHTML = `
                                        <tr>
                                            <td colspan="8" class="p-4 text-center text-red-500">
                                                Error loading invoices: ${error.message}
                                            </td>
                                        </tr>
                                    `;
                    }
                }
            }
            // Temporary debug function
            async function debugCheckInvoices() {
                try {
                    const response = await fetch(`${API_BASE_URL}/invoices/history`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });
                    const result = await response.json();
                    console.log('Debug - All invoices from server:', result);
                    return result.invoices || [];
                } catch (error) {
                    console.error('Debug - Error checking invoices:', error);
                    return [];
                }
            }
            /* ========== INVOICE ACTIONS ========== */
            async function viewInvoice(id) {
                const result = await loadInvoice(id);
                if (result.success) {
                    loadInvoiceForPrint(result.invoice);
                    // Switch to create tab if not already
                    if ($('btn-create-invoice')) {
                        $('btn-create-invoice').click();
                    }
                } else {
                    alert('Error loading invoice: ' + result.message);
                }
            }
            async function editInvoice(id) {
                const result = await loadInvoice(id);
                if (result.success) {
                    currentInvoiceId = result.invoice.id;

                    // Load invoice data into form
                    loadInvoiceForPrint(result.invoice);

                    // Switch to create tab
                    if ($('btn-create-invoice')) {
                        $('btn-create-invoice').click();
                    }

                    // Update button text
                    const saveBtn = $('btn-save');
                    if (saveBtn) {
                        saveBtn.textContent = 'Update Invoice';
                    }

                    // alert('Invoice loaded for editing. Make changes and click "Update Invoice".');
                } else {
                    showToast('Error loading invoice: ' + result.message, 'error');
                }
            }
            async function deleteInvoice(id, confirmFirst = true) {
                if (confirmFirst && !confirm('Are you sure you want to delete this invoice?')) {
                    return;
                }

                const result = await deleteInvoiceFromServer(id);
                if (result.success) {
                    showToast('Invoice deleted successfully!', 'success');

                    // If we're deleting the current invoice, reset form
                    if (currentInvoiceId === id) {
                        prepareNewInvoice();
                    }

                    // Refresh lists
                    renderHistory();
                    renderSavedList();
                } else {
                    showToast('Error deleting invoice: ' + result.message, 'error');
                }
            }
            async function deleteInvoiceFromServer(id) {
                try {
                    const response = await fetch(`${API_BASE_URL}/invoices/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        }
                    });

                    return await response.json();
                } catch (error) {
                    console.error('Error deleting invoice:', error);
                    return { success: false, message: 'Network error' };
                }
            }
            /* ========== BULK OPERATIONS ========== */
            function setupBulkSelection() {
                const checkAll = $('history-check-all');
                if (checkAll) {
                    checkAll.addEventListener('change', function () {
                        const allChecks = document.querySelectorAll('.history-check');
                        allChecks.forEach(check => {
                            check.checked = this.checked;
                        });
                        toggleBulkToolbar();
                    });
                }

                const allChecks = document.querySelectorAll('.history-check');
                allChecks.forEach(check => {
                    check.addEventListener('change', toggleBulkToolbar);
                });

                // Bulk actions
                const bulkDeleteBtn = $('bulk-delete');
                if (bulkDeleteBtn) {
                    bulkDeleteBtn.addEventListener('click', bulkDeleteInvoices);
                }

                const bulkStatusSelect = $('bulk-status');
                if (bulkStatusSelect) {
                    bulkStatusSelect.addEventListener('change', bulkUpdateStatus);
                }

                const bulkPdfBtn = $('bulk-pdf');
                if (bulkPdfBtn) {
                    bulkPdfBtn.addEventListener('click', bulkDownloadPDFs);
                }

                const bulkPrintBtn = $('bulk-print');
                if (bulkPrintBtn) {
                    bulkPrintBtn.addEventListener('click', bulkPrintInvoices);
                }

                const bulkEmailBtn = $('bulk-email');
                if (bulkEmailBtn) {
                    bulkEmailBtn.addEventListener('click', bulkEmailInvoices);
                }
            }
            function toggleBulkToolbar() {
                const toolbar = $('bulk-toolbar');
                if (!toolbar) return;

                const checkedCount = document.querySelectorAll('.history-check:checked').length;
                if (checkedCount > 0) {
                    toolbar.style.display = 'block';
                } else {
                    toolbar.style.display = 'none';
                }
            }
            function getSelectedInvoiceIds() {
                const ids = [];
                document.querySelectorAll('.history-check:checked').forEach(check => {
                    ids.push(check.getAttribute('data-id'));
                });
                return ids;
            }
            async function bulkDeleteInvoices() {
                const ids = getSelectedInvoiceIds();
                if (ids.length === 0) {
                    showToast('Please select at least one invoice', 'error');
                    return;
                }

                if (!confirm(`Are you sure you want to delete ${ids.length} invoice(s)?`)) {
                    return;
                }

                const result = await bulkDeleteInvoicesFromServer(ids);
                if (result.success) {
                    showToast(result.message, 'success');
                    renderHistory();
                    renderSavedList();

                    // Clear selection
                    const checkAll = $('history-check-all');
                    if (checkAll) checkAll.checked = false;
                    toggleBulkToolbar();
                } else {
                    showToast('Error deleting invoices: ' + result.message, 'error');
                }
            }
            async function bulkUpdateStatus() {
                const ids = getSelectedInvoiceIds();
                if (ids.length === 0) {
                    showToast('Please select at least one invoice', 'error');
                    return;
                }

                const status = $('bulk-status').value;
                if (!status) {
                    showToast('Please select a status', 'error');
                    return;
                }

                const result = await bulkUpdateStatusOnServer(ids, status);
                if (result.success) {
                    showToast(result.message, 'success');
                    renderHistory();
                    renderSavedList();

                    // Reset select
                    $('bulk-status').value = '';
                } else {
                    showToast('Error updating invoices: ' + result.message, 'error');
                }
            }
            async function bulkDownloadPDFs() {
                const ids = getSelectedInvoiceIds();
                if (ids.length === 0) {
                    showToast('Please select at least one invoice', 'error');
                    return;
                }

                if (ids.length === 1) {
                    // Single invoice download
                    window.location.href = `${API_BASE_URL}/invoices/${ids[0]}/download`;
                    return;
                }

                // For multiple invoices, we need server-side zip generation
                showToast('Multiple PDF download feature coming soon! For now, please download invoices one by one.', 'info');
            }
            async function bulkPrintInvoices() {
                const ids = getSelectedInvoiceIds();
                if (ids.length === 0) {
                    showToast('Please select at least one invoice', 'error');
                    return;
                }

                if (ids.length === 1) {
                    // Single invoice print
                    printInvoice(ids[0]);
                    return;
                }

                showToast('Multiple invoice print feature coming soon! For now, please print invoices one by one.', 'info');
            }
            async function bulkEmailInvoices() {
                const ids = getSelectedInvoiceIds();
                if (ids.length === 0) {
                    showToast('Please select at least one invoice', 'error');
                    return;
                }

                const email = prompt('Enter recipient email address:', '');
                if (!email) return;

                const message = prompt('Enter email message (optional):', 'Please find attached invoices.');

                // For single invoice
                if (ids.length === 1) {
                    emailInvoice(ids[0]);
                    return;
                }

                showToast('Multiple invoice email feature coming soon! For now, please email invoices one by one.', 'info');
            }
            async function bulkShareInvoices() {
                const ids = getSelectedInvoiceIds();
                if (ids.length === 0) {
                    showToast('Please select at least one invoice', 'error');
                    return;
                }

                if (ids.length === 1) {
                    shareInvoice(ids[0]);
                    return;
                }

                // For multiple invoices, generate a share link for each
                showToast('Sharing multiple invoices is not supported yet.', 'info');
            }
            /* ========== TAB SWITCHING ========== */
            // function setupTabSwitching() {
            // const createTabBtn = $('btn-create-invoice');
            // const historyTabBtn = $('btn-history');
            // const createTab = $('tab-create');
            // const historyTab = $('tab-history');

            // if (createTabBtn && historyTabBtn && createTab && historyTab) {
            // createTabBtn.addEventListener('click', () => {
            // createTabBtn.classList.add('active');
            // historyTabBtn.classList.remove('active');
            // createTab.classList.remove('hidden');
            // historyTab.classList.add('hidden');
            // });

            // historyTabBtn.addEventListener('click', () => {
            // historyTabBtn.classList.add('active');
            // createTabBtn.classList.remove('active');
            // historyTab.classList.remove('hidden');
            // createTab.classList.add('hidden');
            // renderHistory(); // Load history when tab is clicked
            // });
            // }
            function setupTabSwitching() {
                const createTabBtn = $('btn-create-invoice');
                const historyTabBtn = $('btn-history');
                const createTab = $('tab-create');
                const historyTab = $('tab-history');
                if (!createTabBtn || !historyTabBtn || !createTab || !historyTab) return;
                // 👉 DEFAULT STATE: SHOW HISTORY FIRST
                historyTabBtn.classList.add('active');
                createTabBtn.classList.remove('active');
                historyTab.classList.remove('hidden');
                createTab.classList.add('hidden');
                renderHistory(); // load history on first page load
                // 👉 CLICK: CREATE INVOICE TAB
                createTabBtn.addEventListener('click', () => {
                    createTabBtn.classList.add('active');
                    historyTabBtn.classList.remove('active');
                    createTab.classList.remove('hidden');
                    historyTab.classList.add('hidden');
                });
                // 👉 CLICK: HISTORY TAB
                historyTabBtn.addEventListener('click', () => {
                    historyTabBtn.classList.add('active');
                    createTabBtn.classList.remove('active');
                    historyTab.classList.remove('hidden');
                    createTab.classList.add('hidden');
                    renderHistory();
                });

                // Share button
                const shareBtn = $('btn-share');
                if (shareBtn) {
                    shareBtn.addEventListener('click', () => {
                        const inv = gatherInvoiceForm();
                        if (inv.client_name && inv.items.length > 0) {
                            shareInvoice();
                        } else {
                            showToast('Please create an invoice first before sharing.', 'error');
                        }
                    });
                }
            }
            /* ========== SIGNATURE UPLOAD ========== */
            function setupSignatureUpload() {
                const dropzone = $('sig-dropzone');
                const fileInput = $('admin-sig-file');
                const preview = $('admin-sig-preview');
                const removeBtn = $('remove-admin-sig');

                if (!dropzone || !fileInput || !preview) return;

                dropzone.addEventListener('click', () => fileInput.click());
                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.style.borderColor = '#94a3b8';
                });
                dropzone.addEventListener('dragleave', () => {
                    dropzone.style.borderColor = '#cbd5e1';
                });
                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.style.borderColor = '#cbd5e1';

                    if (e.dataTransfer.files.length) {
                        handleSignatureUpload(e.dataTransfer.files[0]);
                    }
                });

                fileInput.addEventListener('change', (e) => {
                    if (e.target.files.length) {
                        handleSignatureUpload(e.target.files[0]);
                    }
                });

                if (removeBtn) {
                    removeBtn.addEventListener('click', removeSignature);
                }

                // Load saved signature on init
                loadSavedSignature();
            }
            async function loadSavedSignature() {
                const signature = await loadSignature();
                if (signature) {
                    updateSignaturePreview(signature);
                }
            }
            function handleSignatureUpload(file) {
                if (!file.type.match('image.*')) {
                    showToast('Please select an image file (PNG, JPG, WebP)', 'error');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) { // 5MB limit
                    showToast('File size must be less than 5MB', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    const signatureData = e.target.result;
                    updateSignaturePreview(signatureData);
                    saveSignatureToServer(signatureData);
                };
                reader.readAsDataURL(file);
            }
            function updateSignaturePreview(signatureData) {
                // Update admin signature preview
                const adminPreview = $('admin-sig-preview');
                if (adminPreview) {
                    adminPreview.innerHTML = `<img src="${signatureData}" class="max-h-16 object-contain" alt="Signature" />`;
                }

                // Update invoice preview signature
                const sigImage = $('sig-image');
                const printSigImage = $('print-sig-image');

                if (sigImage) {
                    sigImage.innerHTML = `<img src="${signatureData}" class="max-h-20 object-contain" alt="Signature" />`;
                    sigImage.classList.remove('text-slate-400');
                }

                if (printSigImage) {
                    printSigImage.innerHTML = `<img src="${signatureData}" class="max-h-16 object-contain" alt="Signature" />`;
                    printSigImage.classList.remove('text-slate-400');
                }
            }
            function removeSignature() {
                // Clear admin signature preview
                const adminPreview = $('admin-sig-preview');
                if (adminPreview) {
                    adminPreview.innerHTML = 'No signature uploaded';
                    adminPreview.classList.remove('text-slate-400');
                }

                // Clear invoice preview signatures
                const sigImage = $('sig-image');
                const printSigImage = $('print-sig-image');

                if (sigImage) {
                    sigImage.innerHTML = 'No signature uploaded';
                    sigImage.classList.add('text-slate-400');
                }

                if (printSigImage) {
                    printSigImage.innerHTML = 'No signature uploaded';
                    printSigImage.classList.add('text-slate-400');
                }

                // Remove from server
                saveSignatureToServer(null);
            }
            async function saveSignatureToServer(signatureData) {
                const result = await saveSignature({ signature: signatureData });
                if (!result.success) {
                    console.error('Failed to save signature:', result.message);
                }
            }
            /* ========== TERMS & CONDITIONS EDITOR ========== */
            function setupTermsEditor() {
                const editBtn = $('edit-terms-btn');
                if (editBtn) {
                    editBtn.addEventListener('click', openTermsEditor);
                }
            }
            function openTermsEditor() {
                const terms = loadTerms();

                let html = `
                                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                                    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
                                        <div class="p-6">
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-lg font-semibold text-slate-800">Edit Terms & Conditions</h3>
                                                <button class="icon-btn secondary-btn" onclick="closeTermsEditor()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="space-y-3 mb-6">
                                                <div id="terms-items">
                            `;

                terms.forEach((term, index) => {
                    html += `
                                    <div class="terms-item" data-index="${index}">
                                        <input type="text" class="terms-input" value="${term}" placeholder="Enter term">
                                        <button class="icon-btn danger-btn" onclick="removeTerm(${index})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                `;
                });

                html += `
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <button class="action-btn secondary-btn" onclick="addTerm()">
                                                    <i class="fas fa-plus mr-2"></i> Add Term
                                                </button>
                                                <button class="action-btn primary-btn" onclick="saveTermsFromEditor()">
                                                    <i class="fas fa-save mr-2"></i> Save Terms
                                                </button>
                                                <button class="action-btn secondary-btn" onclick="resetTerms()">
                                                    <i class="fas fa-undo mr-2"></i> Reset to Default
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                const modal = document.createElement('div');
                modal.id = 'terms-editor-modal';
                modal.innerHTML = html;
                document.body.appendChild(modal);
            }
            function closeTermsEditor() {
                const modal = document.getElementById('terms-editor-modal');
                if (modal) modal.remove();
            }
            function addTerm() {
                const container = document.getElementById('terms-items');
                if (!container) return;

                const index = container.children.length;
                const div = document.createElement('div');
                div.className = 'terms-item';
                div.setAttribute('data-index', index);
                div.innerHTML = `
                                <input type="text" class="terms-input" placeholder="Enter term">
                                <button class="icon-btn danger-btn" onclick="removeTerm(${index})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `;
                container.appendChild(div);
            }
            function removeTerm(index) {
                const container = document.getElementById('terms-items');
                if (!container) return;

                const item = container.querySelector(`[data-index="${index}"]`);
                if (item) item.remove();

                // Reindex remaining items
                container.querySelectorAll('.terms-item').forEach((item, idx) => {
                    item.setAttribute('data-index', idx);
                    const button = item.querySelector('button');
                    if (button) {
                        button.setAttribute('onclick', `removeTerm(${idx})`);
                    }
                });
            }
            function saveTermsFromEditor() {
                const container = document.getElementById('terms-items');
                if (!container) return;

                const terms = [];
                container.querySelectorAll('.terms-input').forEach(input => {
                    if (input.value.trim()) {
                        terms.push(input.value.trim());
                    }
                });

                if (terms.length === 0) {
                    showToast('Please add at least one term', 'error');
                    return;
                }

                saveTerms(terms);
                renderTerms();
                closeTermsEditor();

                showToast('Terms & Conditions saved successfully!', 'success');
            }
            function resetTerms() {
                if (confirm('Reset to default terms?')) {
                    saveTerms([...DEFAULT_TERMS]);
                    renderTerms();
                    closeTermsEditor();
                }
            }
            /* ========== MAIN ACTION BUTTONS SETUP ========== */
            function setupMainActionButtons() {
                // PDF Download button
                const pdfBtn = $('btn-pdf');
                if (pdfBtn) {
                    pdfBtn.addEventListener('click', () => {
                        const inv = gatherInvoiceForm();
                        if (inv.client_name && inv.items.length > 0) {
                            downloadInvoicePDF(currentInvoiceId);
                        } else {
                            showToast('Please create an invoice first before downloading PDF.', 'error');
                        }
                    });
                }

                // Print button
                const printBtn = $('btn-print');
                if (printBtn) {
                    printBtn.addEventListener('click', () => {
                        const inv = gatherInvoiceForm();
                        if (inv.client_name && inv.items.length > 0) {
                            printInvoice(currentInvoiceId);
                        } else {
                            showToast('Please create an invoice first before printing.', 'error');
                        }
                    });
                }

                // Email button
                const emailBtn = $('btn-email');
                if (emailBtn) {
                    emailBtn.addEventListener('click', () => {
                        const inv = gatherInvoiceForm();
                        if (inv.client_name && inv.items.length > 0) {
                            emailInvoice(currentInvoiceId);
                        } else {
                            showToast('Please create an invoice first before sending email.', 'error');
                        }
                    });
                }

                // Share Invoice button
                const shareInvoiceBtn = $('btn-share-invoice');
                if (shareInvoiceBtn) {
                    shareInvoiceBtn.addEventListener('click', () => {
                        const inv = gatherInvoiceForm();
                        if (inv.client_name && inv.items.length > 0) {
                            shareInvoice(currentInvoiceId);
                        } else {
                            showToast('Please create an invoice first before sharing.', 'error');
                        }
                    });
                }

                // Bulk Share button
                const bulkShareBtn = $('btn-share-bulk');
                if (bulkShareBtn) {
                    bulkShareBtn.addEventListener('click', bulkShareInvoices);
                }
            }
            /* ========== INITIALIZATION ========== */
            document.addEventListener('DOMContentLoaded', function () {
                init();

                // Set up all event listeners
                setupTabSwitching();
                setupCompanyManagement();
                setupSignatureUpload();
                setupTermsEditor();
                setupMainActionButtons();

                // Setup search and filter for history
                const historySearch = $('history-search');
                const historyFilter = $('history-filter');

                if (historySearch) {
                    historySearch.addEventListener('input', debounce(renderHistory, 300));
                }

                if (historyFilter) {
                    historyFilter.addEventListener('change', renderHistory);
                }

                // Load recent invoices on startup
                renderSavedList();
            });
            // Debounce function for search
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
            // Make functions globally available
            window.downloadInvoicePDF = downloadInvoicePDF;
            window.printInvoice = printInvoice;
            window.emailInvoice = emailInvoice;
            window.shareInvoice = shareInvoice;
            window.viewInvoice = viewInvoice;
            window.editInvoice = editInvoice;
            window.deleteInvoice = deleteInvoice;
            window.removeItemRow = removeItemRow;
        </script>
        <script>
            const taxSlabSelect = document.getElementById('tax-slab');
            const nonTaxInvoice = document.getElementById('nonTaxInvoice');
            const taxInvoice = document.getElementById('taxInvoice');
            function toggleInvoiceTitle() {
                const selectedValue = taxSlabSelect.value;
                if (selectedValue === "0") {
                    // No Tax selected
                    nonTaxInvoice.classList.remove('hidden');
                    taxInvoice.classList.add('hidden');
                } else {
                    // Tax selected
                    nonTaxInvoice.classList.add('hidden');
                    taxInvoice.classList.remove('hidden');
                }
            }
            // Run on change
            taxSlabSelect.addEventListener('change', toggleInvoiceTitle);
            // Run once on page load (important for default selected value)
            toggleInvoiceTitle();
        </script>
    </body>

    </html>
@endsection