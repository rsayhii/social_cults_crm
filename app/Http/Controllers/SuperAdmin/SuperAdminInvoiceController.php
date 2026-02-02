<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\SuperAdmin\AdminInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuperAdminInvoiceController extends Controller
{
    /**
     * Display a listing of all invoices.
     */
    public function index(Request $request)
    {
        // Get all invoices with pagination
        $invoices = AdminInvoice::latest();
        
        // Apply filters if any
        if ($request->has('status') && $request->status != 'all') {
            $invoices->where('status', $request->status);
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $invoices->where(function($query) use ($search) {
                $query->where('invoice_number', 'like', "%{$search}%")
                      ->orWhere('client_name', 'like', "%{$search}%")
                      ->orWhere('client_email', 'like', "%{$search}%")
                      ->orWhere('company_name', 'like', "%{$search}%");
            });
        }
        
        // Date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $invoices->whereDate('invoice_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $invoices->whereDate('invoice_date', '<=', $request->date_to);
        }
        
        $invoices = $invoices->paginate(20);
        
        // Get statistics for dashboard
        $totalInvoices = AdminInvoice::count();
        $pendingInvoices = AdminInvoice::where('status', 'pending')->count();
        $paidInvoices = AdminInvoice::where('status', 'paid')->count();
        $totalRevenue = AdminInvoice::where('status', 'paid')->sum('grand_total');
        
        return view('superadmin.invoices.index', compact(
            'invoices', 
            'totalInvoices', 
            'pendingInvoices', 
            'paidInvoices', 
            'totalRevenue'
        ));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        // Default company information
        $defaultCompany = [
            'name' => config('app.name', 'Social Cults'),
            'address' => '123 Creative Street, New Delhi, India',
            'phone' => '+91 9876543210',
            'email' => 'contact@socialcults.com',
            'gstin' => 'GSTIN123456789',
            'bank_details' => "Example Bank\nA/C: 1234567890\nIFSC: EXMP0001234",
            'logo' => null,
        ];
        
        // Default terms and conditions
        $defaultTerms = [
            "Payment is due within 7 days from the invoice date",
            "Late payments may attract interest charges of 1.5% per month",
            "All services are provided as per the agreed scope of work",
            "Any discrepancies must be reported within 7 days of receipt",
            "The invoice is payable in Indian Rupees unless otherwise specified",
            "All taxes are as per applicable GST regulations"
        ];
        
        return view('superadmin.invoices.create', compact('defaultCompany', 'defaultTerms'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string',
            'client_gstin' => 'nullable|string|max:15',
            
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_gstin' => 'nullable|string|max:15',
            'company_bank_details' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'currency' => 'required|string|max:3',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_type' => 'required|string|in:cgst_sgst,igst',
            'discount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'status' => 'required|string|in:pending,paid,overdue,cancelled',
            
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.service_type' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Calculate invoice totals
            $subtotal = 0;
            $items = [];
            
            foreach ($request->items as $index => $item) {
                $quantity = floatval($item['quantity'] ?? 0);
                $unitPrice = floatval($item['unit_price'] ?? 0);
                $amount = $quantity * $unitPrice;
                
                $subtotal += $amount;
                
                $items[] = [
                    'description' => $item['description'] ?? 'Item ' . ($index + 1),
                    'service_type' => $item['service_type'] ?? null,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                ];
            }
            
            $taxRate = floatval($request->tax_rate ?? 0);
            $taxAmount = ($subtotal * $taxRate) / 100;
            $discount = floatval($request->discount ?? 0);
            $grandTotal = $subtotal + $taxAmount - $discount;
            
            // Handle file uploads
            $companyLogoPath = null;
            $signaturePath = null;
            
            if ($request->hasFile('company_logo')) {
                $companyLogoPath = $request->file('company_logo')->store('company-logos', 'public');
            }
            
            if ($request->hasFile('signature')) {
                $signaturePath = $request->file('signature')->store('signatures', 'public');
            }
            
            // Create invoice
            $invoice = AdminInvoice::create([
                'invoice_number' => AdminInvoice::generateInvoiceNumber(),
                'invoice_date' => $request->invoice_date ?? now()->format('Y-m-d'),
                'due_date' => $request->due_date,
                'status' => $request->status ?? 'pending',
                'currency' => $request->currency ?? '₹',
                
                'client_name' => $request->client_name,
                'client_email' => $request->client_email,
                'client_phone' => $request->client_phone,
                'client_address' => $request->client_address,
                'client_gstin' => $request->client_gstin,
                
                'company_name' => $request->company_name,
                'company_address' => $request->company_address,
                'company_phone' => $request->company_phone,
                'company_email' => $request->company_email,
                'company_gstin' => $request->company_gstin,
                'company_bank_details' => $request->company_bank_details,
                'company_logo' => $companyLogoPath,
                
                'items' => $items,
                'tax_rate' => $taxRate,
                'tax_type' => $request->tax_type ?? 'cgst_sgst',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                
                'description' => $request->description,
                'terms_conditions' => $request->terms_conditions,
                'signature' => $signaturePath,
                
                'payment_method' => null,
                'transaction_id' => null,
                'payment_date' => null,
            ]);
            
            // Commit transaction
            DB::commit();
            
            // Redirect to invoice show page with success message
            return redirect()->route('superadmin.invoices.show', $invoice->id)
                ->with('success', 'Invoice created successfully! Invoice #: ' . $invoice->invoice_number);
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            // Delete uploaded files if any
            if (isset($companyLogoPath) && Storage::disk('public')->exists($companyLogoPath)) {
                Storage::disk('public')->delete($companyLogoPath);
            }
            if (isset($signaturePath) && Storage::disk('public')->exists($signaturePath)) {
                Storage::disk('public')->delete($signaturePath);
            }
            
            return redirect()->back()
                ->with('error', 'Error creating invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show($id)
    {
        try {
            $invoice = AdminInvoice::findOrFail($id);
            
            // Decode items if they're stored as JSON string
            if (is_string($invoice->items)) {
                $invoice->items = json_decode($invoice->items, true);
            }
            
            return view('superadmin.invoices.show', compact('invoice'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('superadmin.invoices.index')
                ->with('error', 'Invoice not found.');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.invoices.index')
                ->with('error', 'Error loading invoice: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit($id)
    {
        try {
            $invoice = AdminInvoice::findOrFail($id);
            
            // Decode items if they're stored as JSON string
            if (is_string($invoice->items)) {
                $invoice->items = json_decode($invoice->items, true);
            }
            
            // Default company information for form
            $defaultCompany = [
                'name' => $invoice->company_name,
                'address' => $invoice->company_address,
                'phone' => $invoice->company_phone,
                'email' => $invoice->company_email,
                'gstin' => $invoice->company_gstin,
                'bank_details' => $invoice->company_bank_details,
                'logo' => $invoice->company_logo,
            ];
            
            // Default terms and conditions
            $defaultTerms = !empty($invoice->terms_conditions) ? 
                explode("\n", $invoice->terms_conditions) : 
                [
                    "Payment is due within 7 days from the invoice date",
                    "Late payments may attract interest charges of 1.5% per month",
                    "All services are provided as per the agreed scope of work",
                    "Any discrepancies must be reported within 7 days of receipt",
                    "The invoice is payable in Indian Rupees unless otherwise specified",
                    "All taxes are as per applicable GST regulations"
                ];
            
            return view('superadmin.invoices.edit', compact('invoice', 'defaultCompany', 'defaultTerms'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('superadmin.invoices.index')
                ->with('error', 'Invoice not found.');
        }
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $invoice = AdminInvoice::findOrFail($id);
            
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'client_name' => 'required|string|max:255',
                'client_email' => 'nullable|email|max:255',
                'client_phone' => 'nullable|string|max:20',
                'client_address' => 'nullable|string',
                'client_gstin' => 'nullable|string|max:15',
                
                'company_name' => 'required|string|max:255',
                'company_address' => 'required|string',
                'company_phone' => 'nullable|string|max:20',
                'company_email' => 'nullable|email|max:255',
                'company_gstin' => 'nullable|string|max:15',
                'company_bank_details' => 'nullable|string',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                
                'invoice_date' => 'required|date',
                'due_date' => 'nullable|date',
                'currency' => 'required|string|max:3',
                'tax_rate' => 'required|numeric|min:0|max:100',
                'tax_type' => 'required|string|in:cgst_sgst,igst',
                'discount' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'terms_conditions' => 'nullable|string',
                'status' => 'required|string|in:pending,paid,overdue,cancelled',
                
                'items' => 'required|array|min:1',
                'items.*.description' => 'required|string',
                'items.*.service_type' => 'nullable|string',
                'items.*.quantity' => 'required|numeric|min:0.01',
                'items.*.unit_price' => 'required|numeric|min:0',
                
                'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Begin database transaction
            DB::beginTransaction();
            
            try {
                // Calculate invoice totals
                $subtotal = 0;
                $items = [];
                
                foreach ($request->items as $item) {
                    $quantity = floatval($item['quantity']);
                    $unitPrice = floatval($item['unit_price']);
                    $amount = $quantity * $unitPrice;
                    
                    $subtotal += $amount;
                    
                    $items[] = [
                        'description' => $item['description'],
                        'service_type' => $item['service_type'] ?? null,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'amount' => $amount,
                    ];
                }
                
                $taxAmount = ($subtotal * floatval($request->tax_rate)) / 100;
                $discount = floatval($request->discount) ?? 0;
                $grandTotal = $subtotal + $taxAmount - $discount;
                
                // Handle file uploads
                $companyLogoPath = $invoice->company_logo;
                $signaturePath = $invoice->signature;
                
                if ($request->hasFile('company_logo')) {
                    // Delete old logo if exists
                    if ($companyLogoPath && Storage::disk('public')->exists($companyLogoPath)) {
                        Storage::disk('public')->delete($companyLogoPath);
                    }
                    $companyLogoPath = $request->file('company_logo')->store('company-logos', 'public');
                }
                
                if ($request->hasFile('signature')) {
                    // Delete old signature if exists
                    if ($signaturePath && Storage::disk('public')->exists($signaturePath)) {
                        Storage::disk('public')->delete($signaturePath);
                    }
                    $signaturePath = $request->file('signature')->store('signatures', 'public');
                }
                
                // Update invoice
                $invoice->update([
                    'invoice_date' => $request->invoice_date,
                    'due_date' => $request->due_date,
                    'status' => $request->status,
                    'currency' => $request->currency,
                    
                    'client_name' => $request->client_name,
                    'client_email' => $request->client_email,
                    'client_phone' => $request->client_phone,
                    'client_address' => $request->client_address,
                    'client_gstin' => $request->client_gstin,
                    
                    'company_name' => $request->company_name,
                    'company_address' => $request->company_address,
                    'company_phone' => $request->company_phone,
                    'company_email' => $request->company_email,
                    'company_gstin' => $request->company_gstin,
                    'company_bank_details' => $request->company_bank_details,
                    'company_logo' => $companyLogoPath,
                    
                    'items' => $items,
                    'tax_rate' => $request->tax_rate,
                    'tax_type' => $request->tax_type,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'discount' => $discount,
                    'grand_total' => $grandTotal,
                    
                    'description' => $request->description,
                    'terms_conditions' => $request->terms_conditions,
                    'signature' => $signaturePath,
                ]);
                
                // Commit transaction
                DB::commit();
                
                // Redirect to invoice show page with success message
                return redirect()->route('superadmin.invoices.show', $invoice->id)
                    ->with('success', 'Invoice updated successfully!');
                    
            } catch (\Exception $e) {
                // Rollback transaction on error
                DB::rollBack();
                
                return redirect()->back()
                    ->with('error', 'Error updating invoice: ' . $e->getMessage())
                    ->withInput();
            }
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('superadmin.invoices.index')
                ->with('error', 'Invoice not found.');
        }
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy($id)
    {
        try {
            $invoice = AdminInvoice::findOrFail($id);
            
            // Delete associated files
            if ($invoice->company_logo && Storage::disk('public')->exists($invoice->company_logo)) {
                Storage::disk('public')->delete($invoice->company_logo);
            }
            
            if ($invoice->signature && Storage::disk('public')->exists($invoice->signature)) {
                Storage::disk('public')->delete($invoice->signature);
            }
            
            // Soft delete the invoice
            $invoice->delete();
            
            return redirect()->route('superadmin.invoices.index')
                ->with('success', 'Invoice deleted successfully!');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('superadmin.invoices.index')
                ->with('error', 'Invoice not found.');
        } catch (\Exception $e) {
            return redirect()->route('superadmin.invoices.index')
                ->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
    }

   /**
 * Download PDF of the specified invoice.
 */
public function download($id)
{
    try {
        $invoice = AdminInvoice::findOrFail($id);
        
        // Decode items if they're stored as JSON string
        if (is_string($invoice->items)) {
            $invoice->items = json_decode($invoice->items, true);
        }
        
        // Check if DomPDF is available
        if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            // Generate PDF using DomPDF
            $pdf = PDF::loadView('superadmin.invoices.pdf', compact('invoice'));
            
            // Return PDF as download
            return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
        } else {
            // Temporary fix: Return HTML view with print option
            return view('superadmin.invoices.pdf', compact('invoice'));
        }
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->route('superadmin.invoices.index')
            ->with('error', 'Invoice not found.');
    } catch (\Exception $e) {
        // Log the error
        Log::error('PDF Generation Error: ' . $e->getMessage());
        
        // Temporary fallback
        return redirect()->route('superadmin.invoices.show', $id)
            ->with('error', 'PDF generation failed. Showing HTML version instead.')
            ->with('invoice', $invoice);
    }
}

    /**
     * Update invoice status.
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $invoice = AdminInvoice::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:pending,paid,overdue,cancelled',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', 'Invalid status value.');
            }
            
            $invoice->update([
                'status' => $request->status,
                'payment_date' => $request->status == 'paid' ? now() : null,
            ]);
            
            return redirect()->back()
                ->with('success', 'Invoice status updated successfully!');
                
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('superadmin.invoices.index')
                ->with('error', 'Invoice not found.');
        }
    }

    /**
     * Bulk delete invoices.
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'exists:invoices,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid invoice IDs'
            ], 400);
        }
        
        DB::beginTransaction();
        
        try {
            $invoices = AdminInvoice::whereIn('id', $request->invoice_ids)->get();
            
            foreach ($invoices as $invoice) {
                // Delete associated files
                if ($invoice->company_logo && Storage::disk('public')->exists($invoice->company_logo)) {
                    Storage::disk('public')->delete($invoice->company_logo);
                }
                
                if ($invoice->signature && Storage::disk('public')->exists($invoice->signature)) {
                    Storage::disk('public')->delete($invoice->signature);
                }
                
                // Soft delete the invoice
                $invoice->delete();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($invoices) . ' invoice(s) deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting invoices: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk download invoices as PDF.
     */
    public function bulkDownload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_ids' => 'required|array|min:1|max:10',
            'invoice_ids.*' => 'exists:invoices,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Invalid invoice IDs selected.');
        }
        
        try {
            $invoices = AdminInvoice::whereIn('id', $request->invoice_ids)->get();
            
            // Process each invoice to decode items
            foreach ($invoices as $invoice) {
                if (is_string($invoice->items)) {
                    $invoice->items = json_decode($invoice->items, true);
                }
            }
            
            // Generate combined PDF
            $pdf = Pdf::loadView('superadmin.invoices.bulk-pdf', compact('invoices'));
            
            // Generate filename
            $filename = 'invoices-' . date('Y-m-d-H-i-s') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error generating PDFs: ' . $e->getMessage());
        }
    }

    /**
     * Get invoice statistics for dashboard.
     */
    public function getStats()
    {
        try {
            $totalInvoices = AdminInvoice::count();
            $pendingInvoices = AdminInvoice::where('status', 'pending')->count();
            $paidInvoices = AdminInvoice::where('status', 'paid')->count();
            $overdueInvoices = AdminInvoice::where('status', 'overdue')->count();
            $totalRevenue = AdminInvoice::where('status', 'paid')->sum('grand_total');
            
            // Monthly revenue data for chart
            $monthlyRevenue = AdminInvoice::select(
                DB::raw('DATE_FORMAT(invoice_date, "%Y-%m") as month'),
                DB::raw('SUM(grand_total) as revenue')
            )
            ->where('status', 'paid')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'total_invoices' => $totalInvoices,
                    'pending_invoices' => $pendingInvoices,
                    'paid_invoices' => $paidInvoices,
                    'overdue_invoices' => $overdueInvoices,
                    'total_revenue' => $totalRevenue,
                    'monthly_revenue' => $monthlyRevenue,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export invoices to Excel/CSV.
     */
    public function export(Request $request)
    {
        try {
            $invoices = AdminInvoice::query();
            
            // Apply filters if any
            if ($request->has('status') && $request->status != 'all') {
                $invoices->where('status', $request->status);
            }
            
            if ($request->has('date_from') && !empty($request->date_from)) {
                $invoices->whereDate('invoice_date', '>=', $request->date_from);
            }
            
            if ($request->has('date_to') && !empty($request->date_to)) {
                $invoices->whereDate('invoice_date', '<=', $request->date_to);
            }
            
            $invoices = $invoices->get();
            
            // Prepare CSV data
            $csvData = [];
            
            // Add headers
            $csvData[] = [
                'Invoice Number',
                'Date',
                'Due Date',
                'Client Name',
                'Client Email',
                'Status',
                'Subtotal',
                'Tax',
                'Discount',
                'Total',
                'Currency',
            ];
            
            // Add invoice data
            foreach ($invoices as $invoice) {
                $csvData[] = [
                    $invoice->invoice_number,
                    $invoice->invoice_date->format('Y-m-d'),
                    $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '',
                    $invoice->client_name,
                    $invoice->client_email,
                    ucfirst($invoice->status),
                    $invoice->subtotal,
                    $invoice->tax_amount,
                    $invoice->discount,
                    $invoice->grand_total,
                    $invoice->currency,
                ];
            }
            
            // Generate CSV file
            $filename = 'invoices-export-' . date('Y-m-d-H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($csvData) {
                $file = fopen('php://output', 'w');
                foreach ($csvData as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error exporting invoices: ' . $e->getMessage());
        }
    }

    /**
     * Preview invoice before saving.
     */
    public function preview(Request $request)
    {
        // This method can be used for AJAX preview of invoice
        try {
            $invoiceData = $request->all();
            
            // Calculate totals
            $subtotal = 0;
            $items = [];
            
            foreach ($request->items as $item) {
                $quantity = floatval($item['quantity']);
                $unitPrice = floatval($item['unit_price']);
                $amount = $quantity * $unitPrice;
                
                $subtotal += $amount;
                
                $items[] = [
                    'description' => $item['description'],
                    'service_type' => $item['service_type'] ?? null,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                ];
            }
            
            $taxAmount = ($subtotal * floatval($request->tax_rate)) / 100;
            $discount = floatval($request->discount) ?? 0;
            $grandTotal = $subtotal + $taxAmount - $discount;
            
            // Prepare data for preview
            $previewData = [
                'invoice_number' => AdminInvoice::generateInvoiceNumber(),
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'status' => $request->status,
                'currency' => $request->currency,
                'client_name' => $request->client_name,
                'client_email' => $request->client_email,
                'client_phone' => $request->client_phone,
                'client_address' => $request->client_address,
                'client_gstin' => $request->client_gstin,
                'company_name' => $request->company_name,
                'company_address' => $request->company_address,
                'company_phone' => $request->company_phone,
                'company_email' => $request->company_email,
                'company_gstin' => $request->company_gstin,
                'company_bank_details' => $request->company_bank_details,
                'items' => $items,
                'tax_rate' => $request->tax_rate,
                'tax_type' => $request->tax_type,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'description' => $request->description,
                'terms_conditions' => $request->terms_conditions,
            ];
            
            return response()->json([
                'success' => true,
                'data' => $previewData,
                'html' => view('invoices.partials.preview', $previewData)->render()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating preview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search invoices for autocomplete.
     */
    public function search(Request $request)
    {
        try {
            $searchTerm = $request->get('q', '');
            
            if (empty($searchTerm)) {
                return response()->json([]);
            }
            
            $invoices = AdminInvoice::where('invoice_number', 'like', "%{$searchTerm}%")
                ->orWhere('client_name', 'like', "%{$searchTerm}%")
                ->orWhere('client_email', 'like', "%{$searchTerm}%")
                ->limit(10)
                ->get(['id', 'invoice_number', 'client_name', 'client_email', 'grand_total', 'status', 'currency']);
            
            $results = $invoices->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'text' => "{$invoice->invoice_number} - {$invoice->client_name} ({$invoice->currency}{$invoice->grand_total})",
                    'invoice_number' => $invoice->invoice_number,
                    'client_name' => $invoice->client_name,
                    'amount' => $invoice->grand_total,
                    'status' => $invoice->status,
                ];
            });
            
            return response()->json($results);
            
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }


    public function markAsPaid(AdminInvoice $invoice)
{
    try {
        $invoice->update([
            'status' => 'paid',
            'paid_amount' => $invoice->grand_total,
            'payment_date' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Invoice marked as paid successfully!'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}