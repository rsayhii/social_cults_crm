<?php

namespace App\Http\Controllers;

use App\Models\EmployeePortal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EmployeePortalController extends Controller
{
    /**
     * Display the employee portal page
     */
    public function index()
    {
        return view('admin.employeeportal');
    }

    /**
     * Get all leave applications
     */
    public function getLeaves(Request $request)
    {
        try {
            $query = EmployeePortal::where('company_id', auth()->user()->company_id)
    ->latest();

            
            // Apply filters
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('leave_type') && $request->leave_type) {
                $query->where('leave_type', $request->leave_type);
            }
            
            // Filter by date range if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('from_date', [$request->start_date, $request->end_date]);
            }
            
            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('employee_name', 'LIKE', "%{$search}%")
                      ->orWhere('employee_email', 'LIKE', "%{$search}%")
                      ->orWhere('subject', 'LIKE', "%{$search}%")
                      ->orWhere('employee_position', 'LIKE', "%{$search}%");
                });
            }
            
            $leaves = $query->get();

            $base = EmployeePortal::where('company_id', auth()->user()->company_id);
            
            return response()->json([
                'success' => true,
                'leaves' => $leaves,
                'total' => $leaves->count(),
                'pending_count' => $base->where('status', 'pending')->count(),
    'approved_count' => $base->where('status', 'approved')->count(),
    'rejected_count' => $base->where('status', 'rejected')->count(),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load leave applications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific leave application
     */
    public function getLeave($id)
    {
        try {
           $leave = EmployeePortal::where('company_id', auth()->user()->company_id)
    ->findOrFail($id);

$this->authorize('manage', $leave);

            
            return response()->json([
                'success' => true,
                'leave' => $leave
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Leave application not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store new leave application
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee_name' => 'required|string|max:255',
                'employee_email' => 'required|email|max:255',
                'employee_mobile' => 'required|string|max:20',
                'employee_position' => 'required|string|max:255',
                'sent_to' => 'required|string|max:255',
                'subject' => 'required|string|max:500',
                'from_date' => 'required|date|after_or_equal:today',
                'to_date' => 'required|date|after_or_equal:from_date',
                'reason' => 'required|string',
                'total_days' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file uploads if any
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('leave_attachments', 'public');
                    $attachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                    ];
                }
            }

            // Create timeline
            $timeline = [[
                'date' => now()->format('Y-m-d H:i:s'),
                'action' => 'Leave application submitted',
                'icon' => 'fas fa-paper-plane'
            ]];

            $leave = EmployeePortal::create([
                'employee_name' => $request->employee_name,
                'employee_email' => $request->employee_email,
                'employee_mobile' => $request->employee_mobile,
                'employee_position' => $request->employee_position,
                'sent_to' => $request->sent_to,
                'subject' => $request->subject,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'total_days' => $request->total_days,
                'reason' => $request->reason,
                'status' => 'pending',
                'attachments' => $attachments,
                'timeline' => $timeline,
                'applied_date' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave application submitted successfully!',
                'leave' => $leave
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit leave application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update leave application (for admin)
     */
    public function update(Request $request, $id)
    {
        try {
           $leave = EmployeePortal::where('company_id', auth()->user()->company_id)
    ->findOrFail($id);

$this->authorize('manage', $leave);

            
            $validator = Validator::make($request->all(), [
                'status' => 'sometimes|in:pending,approved,rejected',
                'admin_remarks' => 'nullable|string',
                'from_date' => 'sometimes|date',
                'to_date' => 'sometimes|date|after_or_equal:from_date',
                'reason' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update status and add to timeline if status changed
            if ($request->has('status') && $request->status !== $leave->status) {
                $oldStatus = $leave->status;
                $leave->status = $request->status;
                
                $action = match($request->status) {
                    'approved' => 'Leave approved by admin',
                    'rejected' => 'Leave rejected by admin',
                    default => 'Status updated to ' . $request->status,
                };
                
                $leave->addTimelineEntry($action, 
                    match($request->status) {
                        'approved' => 'fas fa-check-circle',
                        'rejected' => 'fas fa-times-circle',
                        default => 'fas fa-info-circle'
                    }
                );
            }

            // Update other fields
            $leave->fill($request->only([
                'admin_remarks', 'from_date', 'to_date', 'reason'
            ]));
            
            // Recalculate total days if dates changed
            if ($request->has('from_date') || $request->has('to_date')) {
                $from = Carbon::parse($request->from_date ?? $leave->from_date);
                $to = Carbon::parse($request->to_date ?? $leave->to_date);
                $leave->total_days = $to->diffInDays($from) + 1;
            }

            $leave->save();

            return response()->json([
                'success' => true,
                'message' => 'Leave application updated successfully!',
                'leave' => $leave
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leave application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete leave application
     */
    public function destroy($id)
    {
        try {
           $leave = EmployeePortal::where('company_id', auth()->user()->company_id)
    ->findOrFail($id);

$this->authorize('manage', $leave);

$leave->delete();


            return response()->json([
                'success' => true,
                'message' => 'Leave application deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        try {
            $base = EmployeePortal::where('company_id', auth()->user()->company_id);

            $total = $base->count();
$pending = $base->where('status', 'pending')->count();
$approved = $base->where('status', 'approved')->count();
$rejected = $base->where('status', 'rejected')->count();
            
            // Get leave type distribution
            $typeDistribution = EmployeePortal::select('leave_type', DB::raw('count(*) as count'))
                ->groupBy('leave_type')
                ->get()
                ->keyBy('leave_type');
            
            // Get monthly statistics
            $monthlyStats = EmployeePortal::select(
                    DB::raw('MONTH(applied_date) as month'),
                    DB::raw('YEAR(applied_date) as year'),
                    DB::raw('count(*) as total'),
                    DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved')
                )
                ->whereYear('applied_date', date('Y'))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            return response()->json([
                'success' => true,
                'statistics' => [
                    'total' => $total,
                    'pending' => $pending,
                    'approved' => $approved,
                    'rejected' => $rejected,
                    'type_distribution' => $typeDistribution,
                    'monthly_stats' => $monthlyStats,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export leave applications
     */
    public function export(Request $request, $format = 'csv')
    {
        try {
            // Apply filters if provided
           $query = EmployeePortal::where('company_id', auth()->user()->company_id);

            
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('leave_type') && $request->leave_type) {
                $query->where('leave_type', $request->leave_type);
            }
            
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('from_date', [$request->start_date, $request->end_date]);
            }
            
            $leaves = $query->get();
            
            if ($format === 'csv') {
                return $this->exportToCsv($leaves);
            } elseif ($format === 'excel') {
                return $this->exportToExcel($leaves);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Unsupported export format'
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($leaves)
    {
        $filename = 'leave_applications_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($leaves) {
            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($output, [
                'ID', 
                'Employee Name', 
                'Email', 
                'Mobile',
                'Position', 
                'Leave Type',
                'Subject',
                'From Date', 
                'To Date', 
                'Total Days', 
                'Reason',
                'Status', 
                'Applied Date',
                'Sent To',
                'Admin Remarks'
            ]);

            // Add data rows
            foreach ($leaves as $leave) {
                $reason = strip_tags($leave->reason);
                $adminRemarks = $leave->admin_remarks ? strip_tags($leave->admin_remarks) : '';
                
                fputcsv($output, [
                    $leave->id,
                    $leave->employee_name,
                    $leave->employee_email,
                    $leave->employee_mobile,
                    $leave->employee_position,
                    $this->getLeaveTypeName($leave->leave_type),
                    $leave->subject,
                    $leave->from_date ? $leave->from_date->format('Y-m-d') : 'N/A',
                    $leave->to_date ? $leave->to_date->format('Y-m-d') : 'N/A',
                    $leave->total_days,
                    $reason,
                    ucfirst($leave->status),
                    $leave->applied_date ? $leave->applied_date->format('Y-m-d H:i:s') : 'N/A',
                    $leave->sent_to,
                    $adminRemarks
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

   /**
 * Export to Excel
 */
private function exportToExcel($leaves)
{
    try {
        $filename = 'leave_applications_' . date('Y-m-d_His') . '.xlsx';
        
        // Use Laravel Excel if available, otherwise fallback to simple CSV
        if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
            // Create a simple export class inline
            $export = new class($leaves) {
                protected $leaves;
                
                public function __construct($leaves)
                {
                    $this->leaves = $leaves;
                }
                
                public function array(): array
                {
                    $data = [];
                    
                    // Add headers
                    $data[] = [
                        'ID', 'Employee Name', 'Email', 'Mobile', 'Position', 
                        'Leave Type', 'Subject', 'From Date', 'To Date', 
                        'Total Days', 'Reason', 'Status', 'Applied Date', 
                        'Sent To', 'Admin Remarks'
                    ];
                    
                    // Add data rows
                    foreach ($this->leaves as $leave) {
                        $reason = strip_tags($leave->reason);
                        $adminRemarks = $leave->admin_remarks ? strip_tags($leave->admin_remarks) : '';
                        
                        $data[] = [
                            $leave->id,
                            $leave->employee_name,
                            $leave->employee_email,
                            $leave->employee_mobile,
                            $leave->employee_position,
                            $this->getLeaveTypeName($leave->leave_type),
                            $leave->subject,
                            $leave->from_date ? $leave->from_date->format('Y-m-d') : 'N/A',
                            $leave->to_date ? $leave->to_date->format('Y-m-d') : 'N/A',
                            $leave->total_days,
                            $reason,
                            ucfirst($leave->status),
                            $leave->applied_date ? $leave->applied_date->format('Y-m-d H:i:s') : 'N/A',
                            $leave->sent_to,
                            $adminRemarks
                        ];
                    }
                    
                    return $data;
                }
                
                private function getLeaveTypeName($type)
                {
                    $types = [
                        'casual' => 'Casual Leave',
                        'sick' => 'Sick Leave',
                        'paid' => 'Paid Leave',
                        'emergency' => 'Emergency Leave',
                        'halfday' => 'Half Day',
                        'wfh' => 'Work From Home',
                    ];
                    
                    return $types[$type] ?? ucfirst($type);
                }
            };
            
            return Excel::download($export, $filename);
            
        } else {
            // Fallback to CSV if Excel is not available
            return $this->exportToCsv($leaves);
        }
        
    } catch (\Exception $e) {
        \Log::error('Excel export failed: ' . $e->getMessage());
        return $this->exportToCsv($leaves);
    }
}

    /**
     * Get leave type name
     */
    private function getLeaveTypeName($type)
    {
        $types = [
            'casual' => 'Casual Leave',
            'sick' => 'Sick Leave',
            'paid' => 'Paid Leave',
            'emergency' => 'Emergency Leave',
            'halfday' => 'Half Day',
            'wfh' => 'Work From Home',
        ];
        
        return $types[$type] ?? ucfirst($type);
    }



public function getRoles()
{
    try {
        $roles = \App\Models\Role::forCompany(auth()->user()->company_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'roles' => $roles
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to load roles: ' . $e->getMessage()
        ], 500);
    }
}



    /**
     * Get filters data
     */
    public function getFilters()
    {
        try {
            $leaveTypes = [
                'casual' => 'Casual Leave',
                'sick' => 'Sick Leave',
                'paid' => 'Paid Leave',
                'emergency' => 'Emergency Leave',
                'halfday' => 'Half Day',
                'wfh' => 'Work From Home',
            ];
            
            $statuses = [
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
            ];
            
            // Get unique years from applications
            $years = EmployeePortal::where('company_id', auth()->user()->company_id)
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');
            
            return response()->json([
                'success' => true,
                'filters' => [
                    'leave_types' => $leaveTypes,
                    'statuses' => $statuses,
                    'years' => $years,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load filters: ' . $e->getMessage()
            ], 500);
        }
    }
}
