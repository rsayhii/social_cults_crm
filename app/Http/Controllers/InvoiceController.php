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
            $company = Company::findOrFail(auth()->user()->company_id);
            
            // Generate invoice number
            // $year = date('Y');
            // $lastInvoice = Invoice::where('company_id', $company->id)
            //     ->whereYear('created_at', $year)
            //     ->orderBy('id', 'desc')
            //     ->first();
                
            // $serial = $lastInvoice ? intval(Str::afterLast($lastInvoice->invoice_number, '-')) + 1 : 1;
            // $invoiceNumber = "INV-{$year}-" . str_pad($serial, 5, '0', STR_PAD_LEFT);
            
            // Calculate totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }
            
            $taxRate = $request->tax_rate ?? 0.18;
            $taxAmount = $subtotal * $taxRate;
            $discount = $request->discount ?? 0;
            $grandTotal = ($subtotal + $taxAmount) - $discount;
            
            // Handle signature if provided
            $adminSignature = null;
            if ($request->has('admin_signature') && !empty($request->admin_signature)) {
                $signatureData = $request->admin_signature;
                if (strpos($signatureData, 'data:image') === 0) {
                    $adminSignature = $signatureData;
                }
            }
            
            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'status' => $request->status ?? 'Pending',
                'currency' => $request->currency ?? '₹',
                'tax_rate' => $taxRate,
                'tax_mode' => $request->tax_mode ?? 'cgst',
                'discount' => $discount,
                'client_name' => $request->client_name,
                'client_phone' => $request->client_phone,
                'client_email' => $request->client_email,
                'client_address' => $request->client_address,
                'description' => $request->description,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'admin_signature' => $adminSignature,
                'terms' => json_encode($request->terms ?? []),
                'company_id' => $company->id,
                'user_id' => $user->id,
            ]);
            
           foreach ($request->items as $index => $item) {
    InvoiceItem::create([
        'invoice_id' => $invoice->id,
        'company_id' => $company->id, // ✅ REQUIRED
        'description' => $item['description'],
        'service_type' => $item['service_type'] ?? null,
        'quantity' => $item['quantity'],
        'price' => $item['price'],
        'total' => $item['quantity'] * $item['price'],
        'sort_order' => $index,
    ]);
}

            
            DB::commit();
            
            // Load the invoice with items for the response
    $invoice = Invoice::with('items')->find($invoice->id);


    notifyCompany(auth()->user()->company_id, [
    'title' => 'Invoice Generated',
    'message' => 'Invoice #' . $invoice->id . ' created',
    'module' => 'invoice',
    'url' => route('invoices.index'),
    'icon' => 'invoice',
]);

    
    return response()->json([
        'success' => true,
        'message' => 'Invoice created successfully!',
        'invoice' => $invoice  // Make sure invoice is included
    ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create invoice:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
            $discount = $request->discount ?? $invoice->discount;
            $grandTotal = ($subtotal + $taxAmount) - $discount;
            
            // Handle signature if provided
            if ($request->has('admin_signature') && !empty($request->admin_signature)) {
                $signatureData = $request->admin_signature;
                if (strpos($signatureData, 'data:image') === 0) {
                    $invoice->admin_signature = $signatureData;
                }
            }
            
            // Update invoice
            $invoice->update([
                'invoice_number' => $request->invoice_number ?? $invoice->invoice_number,
                'invoice_date' => $request->invoice_date,
                'status' => $request->status ?? $invoice->status,
                'currency' => $request->currency ?? $invoice->currency,
                'tax_rate' => $taxRate,
                'tax_mode' => $request->tax_mode ?? $invoice->tax_mode,
                'discount' => $discount,
                'client_name' => $request->client_name,
                'client_phone' => $request->client_phone,
                'client_email' => $request->client_email,
                'client_address' => $request->client_address,
                'description' => $request->description,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'terms' => json_encode($request->terms ?? json_decode($invoice->terms, true) ?? []),
            ]);
            
            // Delete existing items
            $invoice->items()->delete();
            
            // Create new items
            foreach ($request->items as $index => $item) {
    InvoiceItem::create([
        'invoice_id' => $invoice->id,
        'company_id' => $company->id, // ✅ REQUIRED
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
        
        // For multiple invoices, you might want to create a zip file
        // For simplicity, we'll just download the first one
        if ($invoices->count() === 1) {
            $invoice = $invoices->first();
            $pdf = Pdf::loadView('invoice.pdf', compact('invoice'));
            return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Multiple PDF download not implemented yet'
        ]);
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