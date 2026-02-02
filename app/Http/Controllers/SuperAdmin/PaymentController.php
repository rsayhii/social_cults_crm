<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\SuperAdmin\Payment;
use App\Models\SuperAdmin\Customer;
use App\Models\SuperAdmin\AdminInvoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    // PaymentController.php me index method update karo
public function index(Request $request)
{
    $query = Payment::with(['customer', 'invoice']);
    
    // Search functionality
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('payment_id', 'like', "%{$search}%")
              ->orWhere('transaction_id', 'like', "%{$search}%")
              ->orWhereHas('customer', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%");
              });
        });
    }
    
    // Filter by status
    if ($request->has('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }
    
    // Filter by payment method
    if ($request->has('payment_method') && $request->payment_method !== 'all') {
        $query->where('payment_method', $request->payment_method);
    }
    
    // Date range filter
    if ($request->has('start_date') && $request->has('end_date')) {
        $query->dateRange($request->start_date, $request->end_date);
    }
    
    // Sorting
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');
    $query->orderBy($sortBy, $sortOrder);
    
    // Pagination
    $perPage = $request->get('per_page', 15);
    $payments = $query->paginate($perPage);
    
    // Calculate all statistics
    $totalPayments = Payment::count();
    $totalAmount = Payment::completed()->sum('total_amount');
    $pendingPayments = Payment::pending()->count();
    $completedPayments = Payment::completed()->count();
    $failedPayments = Payment::failed()->count();
    
    $summary = [
        'total_payments' => $totalPayments,
        'total_amount' => $totalAmount,
        'pending_payments' => $pendingPayments,
        'completed_payments' => $completedPayments,
        'failed_payments' => $failedPayments,
    ];
    
    return view('superadmin.payments.index', compact('payments', 'summary'));
}
    
    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        $customers = Customer::active()->get();
        $invoices = AdminInvoice::unpaid()->get();
        
        return view('payments.create', compact('customers', 'invoices'));
    }
    
    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'tax_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:credit_card,bank_transfer,upi,cash,cheque,online,wallet',
            'status' => 'required|in:completed,pending,failed,refunded,cancelled,partially_paid',
            'payment_date' => 'required|date',
            'currency' => 'required|string|size:3',
            'transaction_id' => 'nullable|string|unique:payments,transaction_id',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            $payment = Payment::create(array_merge($request->all(), [
                'total_amount' => $request->amount + ($request->tax_amount ?? 0)
            ]));
            
            // If payment is completed and linked to an invoice, update invoice status
            if ($payment->isCompleted() && $payment->invoice_id) {
                $invoice = AdminInvoice::find($payment->invoice_id);
                if ($invoice) {
                    $invoice->update([
                        'payment_status' => 'paid',
                        'paid_amount' => ($invoice->paid_amount ?? 0) + $payment->amount
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('superadmin.payments.index')
                ->with('success', 'Payment created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error creating payment: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['customer', 'invoice']);
        
        return view('superadmin.payments.show', compact('payment'));
    }
    
    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        $customers = Customer::active()->get();
        $invoices = AdminInvoice::all();
        
        return view('payments.edit', compact('payment', 'customers', 'invoices'));
    }
    
    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'tax_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:credit_card,bank_transfer,upi,cash,cheque,online,wallet',
            'status' => 'required|in:completed,pending,failed,refunded,cancelled,partially_paid',
            'payment_date' => 'required|date',
            'currency' => 'required|string|size:3',
            'transaction_id' => 'nullable|string|unique:payments,transaction_id,' . $payment->id,
            'notes' => 'nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $payment->status;
            $oldInvoiceId = $payment->invoice_id;
            
            $payment->update(array_merge($request->all(), [
                'total_amount' => $request->amount + ($request->tax_amount ?? 0)
            ]));
            
            // Handle invoice status changes
            if ($payment->invoice_id) {
                $invoice = AdminInvoice::find($payment->invoice_id);
                
                if ($invoice) {
                    if ($payment->isCompleted()) {
                        $invoice->update([
                            'payment_status' => 'paid',
                            'paid_amount' => ($invoice->paid_amount ?? 0) + $payment->amount
                        ]);
                    } elseif ($oldStatus === 'completed' && !$payment->isCompleted()) {
                        // If payment was completed but is no longer, adjust invoice
                        $invoice->update([
                            'payment_status' => 'partial',
                            'paid_amount' => max(0, ($invoice->paid_amount ?? 0) - $payment->amount)
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('superadmin.payments.index')
                ->with('success', 'Payment updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error updating payment: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Remove the specified payment (soft delete).
     */
    public function destroy(Payment $payment)
    {
        try {
            $payment->delete();
            
            return redirect()->route('superadmin.payments.index')
                ->with('success', 'Payment deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting payment: ' . $e->getMessage());
        }
    }
    
    /**
     * Restore soft deleted payment.
     */
    public function restore($id)
    {
        try {
            $payment = Payment::withTrashed()->findOrFail($id);
            $payment->restore();
            
            return redirect()->route('superadmin.payments.index')
                ->with('success', 'Payment restored successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error restoring payment: ' . $e->getMessage());
        }
    }
    
    /**
     * Permanently delete payment.
     */
    public function forceDelete($id)
    {
        try {
            $payment = Payment::withTrashed()->findOrFail($id);
            $payment->forceDelete();
            
            return redirect()->route('superadmin.payments.index')
                ->with('success', 'Payment permanently deleted!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting payment: ' . $e->getMessage());
        }
    }
    
    /**
     * Show trashed payments.
     */
    public function trashed()
    {
        $payments = Payment::onlyTrashed()
            ->with(['customer', 'invoice'])
            ->paginate(15);
            
        return view('payments.trashed', compact('payments'));
    }
    
    /**
     * Process payment (mark as completed).
     */
    public function processPayment(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string|unique:payments,transaction_id,' . $payment->id,
            'notes' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            $payment->markAsCompleted($request->transaction_id);
            
            if ($request->notes) {
                $payment->update(['notes' => $request->notes]);
            }
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Payment processed successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error processing payment: ' . $e->getMessage());
        }
    }
    
    /**
     * Get payment statistics for dashboard.
     */
    public function getStatistics(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());
        
        $statistics = [
            'total_revenue' => Payment::completed()
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->sum('total_amount'),
                
            'payment_methods' => Payment::completed()
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as amount'))
                ->groupBy('payment_method')
                ->get(),
                
            'daily_revenue' => Payment::completed()
                ->whereBetween('payment_date', [$startDate, $endDate])
                ->select(DB::raw('DATE(payment_date) as date'), DB::raw('SUM(total_amount) as revenue'))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
                
            'status_distribution' => Payment::whereBetween('created_at', [$startDate, $endDate])
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get(),
        ];
        
        return response()->json($statistics);
    }
}