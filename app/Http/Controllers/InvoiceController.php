<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

class InvoiceController extends Controller
{
    private function baseQuery()
{
    return Invoice::where('company_id', auth()->user()->company_id);
}

    public function index()
    {
        // First check if user has a company
        $user = Auth::user();
       $company = Company::where('id', auth()->user()->company_id)->firstOrFail();
        
       $invoices = $this->baseQuery()
    ->with('company')
    ->latest()
    ->paginate(20);

            $year = date('Y');
            $lastInvoice = Invoice::where('company_id', $company->id)
                ->whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();
                
            $serial = $lastInvoice ? intval(Str::afterLast($lastInvoice->invoice_number, '-')) + 1 : 1;
            $invoiceNumber = "INV-{$year}-" . str_pad($serial, 5, '0', STR_PAD_LEFT);
            
        // Pass the company and CSRF token to the view
        return view('invoice.invoices', [
            'company' => $company,
            'invoices' => $invoices,
            'csrf_token' => csrf_token(),
            'invoice_number' => $invoiceNumber
        ]);
    }

    public function getRecentInvoices()
    {
        $user = Auth::user();
        $invoices = Invoice::with('company')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return response()->json([
            'success' => true,
            'invoices' => $invoices
        ]);
    }

    public function getHistory(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search', '');
        $filter = $request->get('filter', 'all');
        
      $query = $this->baseQuery()->with('company');

if ($filter !== 'all') {
    $query->where('status', $filter);
}

if ($search) {
    $query->where(function ($q) use ($search) {
        $q->where('client_name', 'like', "%{$search}%")
          ->orWhere('invoice_number', 'like', "%{$search}%");
    });
}

        
        $invoices = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'invoices' => $invoices
        ]);
    }

    public function store(Request $request)
{
    Log::info('Invoice store request data:', $request->all());
    
    $request->validate([
        'client_name' => 'required|string|max:255',
        'invoice_date' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.description' => 'required|string',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();
    
    try {
        $user = Auth::user();
        
        // ✅ Add null check for company_id
        if (!$user->company_id) {
            throw new \Exception('User has no company assigned');
        }
        
        $company = Company::findOrFail($user->company_id);
        
        // Calculate totals
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }
        
        $taxRate = $request->tax_rate ?? 0.18;
        $taxAmount = $subtotal * $taxRate;
        
        // ✅ FIX: Handle both discount and discount_percent
        $discountPercent = $request->discount_percent ?? $request->discount ?? 0;
        $grossTotal = $subtotal + $taxAmount;
        $discountAmount = $grossTotal * ($discountPercent / 100);
        $grandTotal = $grossTotal - $discountAmount;
        
        // Handle signature if provided
        $adminSignature = null;
        if ($request->has('admin_signature') && !empty($request->admin_signature)) {
            $signatureData = $request->admin_signature;
            if (is_string($signatureData) && strpos($signatureData, 'data:image') === 0) {
                $adminSignature = $signatureData;
            }
        }
        
        // ✅ FIX: Safely encode terms
        $terms = $request->terms ?? [];
        if (is_string($terms)) {
            $terms = json_decode($terms, true) ?? [];
        }
        
        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'status' => $request->status ?? 'Pending',
            'currency' => $request->currency ?? '₹',
            'tax_rate' => $taxRate,
            'tax_mode' => $request->tax_mode ?? 'cgst',
            'discount' => $discountAmount, // ✅ Store calculated discount amount
            'discount_percent' => $discountPercent, // ✅ Store discount percentage (add this column to migration)
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'client_email' => $request->client_email,
            'client_address' => $request->client_address,
            'description' => $request->description,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
            'admin_signature' => $adminSignature,
            'terms' => json_encode($terms),
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);
        
        // ✅ FIX: Add proper error handling for items
        foreach ($request->items as $index => $item) {
            try {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'company_id' => $company->id,
                    'description' => $item['description'],
                    'service_type' => $item['service_type'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                    'sort_order' => $index,
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to create invoice item {$index}: " . $e->getMessage());
                throw $e;
            }
        }
        
        DB::commit();
        
        // Load the invoice with items for the response
        $invoice = Invoice::with('items')->find($invoice->id);

        // ✅ FIX: Add try-catch for notification
        // try {
        //     notifyCompany(auth()->user()->company_id, [
        //         'title' => 'Invoice Generated',
        //         'message' => 'Invoice #' . $invoice->invoice_number . ' created',
        //         'module' => 'invoice',
        //         'url' => route('invoices.index'),
        //         'icon' => 'invoice',
        //     ]);
        // } catch (\Exception $e) {
        //     Log::warning('Failed to send notification: ' . $e->getMessage());
        //     // Don't fail the entire request if notification fails
        // }
        
        return response()->json([
            'success' => true,
            'message' => 'Invoice created successfully!',
            'invoice' => $invoice
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Failed to create invoice:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to create invoice: ' . $e->getMessage()
        ], 500);
    }
}

    public function update(Request $request, $id)
{
    $request->validate([
        'client_name' => 'required|string|max:255',
        'invoice_date' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.description' => 'required|string',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();
    
    try {
        $user = Auth::user();
        $company = Company::findOrFail(auth()->user()->company_id);
        $invoice = $this->baseQuery()
            ->with(['company','items'])
            ->findOrFail($id);

        $this->authorize('manage', $invoice);
        
        // Calculate totals
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }
        
        $taxRate = $request->tax_rate ?? $invoice->tax_rate;
        $taxAmount = $subtotal * $taxRate;
        $grossTotal = $subtotal + $taxAmount;
        
        // ✅ CRITICAL FIX: Get discount_percent from request, not discount
        // If discount_percent is not in request, try to get it from the invoice
        $discountPercent = 0;
        if ($request->has('discount_percent')) {
            $discountPercent = floatval($request->discount_percent);
        } elseif ($invoice->discount_percent !== null) {
            $discountPercent = floatval($invoice->discount_percent);
        }
        
        // Calculate discount amount based on percentage
        $discountAmount = $grossTotal * ($discountPercent / 100);
        $grandTotal = $grossTotal - $discountAmount;
        
        // ✅ Add logging to debug
        Log::info('Update Invoice Discount Debug:', [
            'invoice_id' => $id,
            'request_discount_percent' => $request->discount_percent ?? 'not set',
            'calculated_discount_percent' => $discountPercent,
            'gross_total' => $grossTotal,
            'discount_amount' => $discountAmount,
            'grand_total' => $grandTotal
        ]);
        
        // Handle signature if provided
        if ($request->has('admin_signature') && !empty($request->admin_signature)) {
            $signatureData = $request->admin_signature;
            if (is_string($signatureData) && strpos($signatureData, 'data:image') === 0) {
                $invoice->admin_signature = $signatureData;
            }
        }
        
        // Safely handle terms
        $terms = $request->terms ?? json_decode($invoice->terms, true) ?? [];
        if (is_string($terms)) {
            $terms = json_decode($terms, true) ?? [];
        }
        
        // Update invoice
        $invoice->update([
            'invoice_number' => $request->invoice_number ?? $invoice->invoice_number,
            'invoice_date' => $request->invoice_date,
            'status' => $request->status ?? $invoice->status,
            'currency' => $request->currency ?? $invoice->currency,
            'tax_rate' => $taxRate,
            'tax_mode' => $request->tax_mode ?? $invoice->tax_mode,
            'discount' => $discountAmount, // ✅ Store calculated discount amount
            'discount_percent' => $discountPercent, // ✅ Store discount percentage
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'client_email' => $request->client_email,
            'client_address' => $request->client_address,
            'description' => $request->description,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
            'terms' => json_encode($terms),
        ]);
        
        // Delete existing items
        $invoice->items()->delete();
        
        // Create new items
        foreach ($request->items as $index => $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'company_id' => $company->id,
                'description' => $item['description'],
                'service_type' => $item['service_type'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['quantity'] * $item['price'],
                'sort_order' => $index,
            ]);
        }
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Invoice updated successfully!',
            'invoice' => $invoice->load('items')
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Failed to update invoice:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'line' => $e->getLine()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Failed to update invoice: ' . $e->getMessage()
        ], 500);
    }
}

    public function show($id)
    {
        $user = Auth::user();
       $invoice = $this->baseQuery()
    ->with(['company','items'])
    ->findOrFail($id);

$this->authorize('manage', $invoice);

            
        return response()->json([
            'success' => true,
            'invoice' => $invoice,
            'terms' => json_decode($invoice->terms, true) ?? []
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
      $invoice = $this->baseQuery()
    ->with(['company','items'])
    ->findOrFail($id);

$this->authorize('manage', $invoice);

            
        $invoice->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Invoice deleted successfully!'
        ]);
    }

   public function downloadPDF($id)
{
    $user = Auth::user();
    $invoice = Invoice::with(['company', 'items'])
        ->where('id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();
    
    // Set paper size and orientation
   $pdf = Pdf::loadView('invoice.pdf', compact('invoice'))
    ->setPaper('a4', 'portrait')
    ->setOption('defaultFont', 'DejaVu Sans');

    
    return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
}

   public function generatePDF($id)
{
    $user = Auth::user();
    $invoice = Invoice::with(['company', 'items'])
        ->where('id', $id)
        ->where('user_id', $user->id)
        ->firstOrFail();

    $pdf = Pdf::loadView('invoice.pdf', compact('invoice'))
        ->setPaper('a4', 'portrait')
        ->setOption('defaultFont', 'Helvetica');
    
    return $pdf->output();
}

    public function sendEmail(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string'
        ]);
        
        $user = Auth::user();
        $invoice = Invoice::with(['company', 'items'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        try {
            // Generate PDF
            $pdf = Pdf::loadView('invoice.pdf', compact('invoice'));
            $pdfData = $pdf->output();
            
            // Send email
            Mail::raw($request->message ?? 'Please find attached invoice.', function($message) use ($request, $invoice, $pdfData) {
                $message->to($request->email)
                        ->subject("Invoice {$invoice->invoice_number} from {$invoice->company->name}")
                        ->attachData($pdfData, "invoice-{$invoice->invoice_number}.pdf", [
                            'mime' => 'application/pdf',
                        ]);
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice sent via email successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:invoices,id',
            'status' => 'required|in:Pending,Paid,Overdue'
        ]);
        
        $user = Auth::user();
        $updated = Invoice::whereIn('id', $request->ids)
            ->where('user_id', $user->id)
            ->update(['status' => $request->status]);
            
        return response()->json([
            'success' => true,
            'message' => "Updated {$updated} invoice(s) status to {$request->status}"
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:invoices,id'
        ]);
        
        $user = Auth::user();
        $deleted = Invoice::whereIn('id', $request->ids)
            ->where('user_id', $user->id)
            ->delete();
            
        return response()->json([
            'success' => true,
            'message' => "Deleted {$deleted} invoice(s)"
        ]);
    }

    public function bulkDownload(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:invoices,id'
        ]);
        
        $user = Auth::user();
        $invoices = Invoice::with(['company', 'items'])
            ->whereIn('id', $request->ids)
            ->where('user_id', $user->id)
            ->get();
            
        if ($invoices->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No invoices found'
            ], 404);
        }
        
        if ($invoices->count() === 1) {
            $invoice = $invoices->first();
            $pdf = Pdf::loadView('invoice.pdf', compact('invoice'));
            return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
        }

        // Create a ZIP archive with multiple invoice PDFs
        try {
            $zip = new \ZipArchive();
            $timestamp = now()->format('Y-m-d-H-i-s');
            $zipFileName = "invoices-{$timestamp}.zip";
            $zipPath = storage_path("app/{$zipFileName}");

            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to create ZIP file'
                ], 500);
            }

            foreach ($invoices as $invoice) {
                $pdf = Pdf::loadView('invoice.pdf', compact('invoice'))
                    ->setPaper('a4', 'portrait')
                    ->setOption('defaultFont', 'DejaVu Sans');
                $pdfData = $pdf->output();
                $entryName = "invoice-{$invoice->invoice_number}.pdf";
                $zip->addFromString($entryName, $pdfData);
            }

            $zip->close();

            return response()->download($zipPath, $zipFileName, [
                'Content-Type' => 'application/zip',
                'X-Filename' => $zipFileName,
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Bulk download ZIP error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate ZIP: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getShareLink($id)
    {
        $user = Auth::user();
        $invoice = Invoice::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        // Generate a simple share token (in a real app, use proper authentication)
        $token = Str::random(32);
        $invoice->update(['share_token' => $token]);
        
        $shareUrl = url("/invoices/share/{$token}");
        
        return response()->json([
            'success' => true,
            'share_url' => $shareUrl
        ]);
    }

    public function viewSharedInvoice($token)
    {
        $invoice = Invoice::with(['company', 'items'])
            ->where('share_token', $token)
            ->firstOrFail();
            
        return view('invoices.shared', compact('invoice'));
    }
}
