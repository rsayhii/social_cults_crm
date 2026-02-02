<?php

namespace App\Http\Controllers;

use App\Models\EmployeePortal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPortalController extends Controller
{
    private function baseQuery()
{
    return EmployeePortal::where('company_id', auth()->user()->company_id);
}

    /**
     * Display the admin portal page
     */
    public function index()
    {
        return view('admin.adminportal');
    }

    /**
     * Get all leave applications for admin
     */
    public function getLeaves(Request $request)
    {
        try {
           $query = $this->baseQuery()->latest();
            
            // Apply filters
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('leave_type') && $request->leave_type) {
                $query->where('leave_type', $request->leave_type);
            }
            
            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('employee_name', 'LIKE', "%{$search}%")
                      ->orWhere('employee_email', 'LIKE', "%{$search}%")
                      ->orWhere('employee_position', 'LIKE', "%{$search}%")
                      ->orWhere('subject', 'LIKE', "%{$search}%");
                });
            }
            
            $leaves = $query->get();
            
            return response()->json([
                'success' => true,
                'leaves' => $leaves,
                'stats' => $this->getAdminStats(),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load leave applications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get admin dashboard statistics
     */
    private function getAdminStats()
{
    $base = $this->baseQuery();

    return [
        'pending' => (clone $base)->where('status', 'pending')->count(),
        'approved' => (clone $base)->where('status', 'approved')->count(),
        'rejected' => (clone $base)->where('status', 'rejected')->count(),
        'total_employees' => (clone $base)
            ->distinct('employee_email')
            ->count('employee_email'),
        'recent_pending' => (clone $base)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subDays(7))
            ->count(),
        'type_distribution' => (clone $base)
            ->select('leave_type', DB::raw('count(*) as count'))
            ->groupBy('leave_type')
            ->get()
            ->keyBy('leave_type'),
    ];
}


    /**
     * Get specific leave application for admin
     */
    public function getLeave($id)
    {
        try {
           $leave = $this->baseQuery()->findOrFail($id);

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
     * Approve leave application
     */
    public function approveLeave(Request $request, $id)
    {
        try {
           $leave = $this->baseQuery()->findOrFail($id);
$this->authorize('manage', $leave);

            
            if ($leave->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave is already approved'
                ], 400);
            }
            
            $oldStatus = $leave->status;
            $leave->status = 'approved';
            
            // Add timeline entry
            $leave->addTimelineEntry('Leave approved by Admin', 'fas fa-check-circle');
            
            $leave->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Leave application approved successfully!',
                'leave' => $leave,
                'stats' => $this->getAdminStats()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve leave: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject leave application
     */
    public function rejectLeave(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'admin_remarks' => 'required|string|min:10'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
           $leave = $this->baseQuery()->findOrFail($id);
$this->authorize('manage', $leave);

            
            if ($leave->status === 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave is already rejected'
                ], 400);
            }
            
            $leave->status = 'rejected';
            $leave->admin_remarks = $request->admin_remarks;
            
            // Add timeline entry
            $leave->addTimelineEntry('Leave rejected by Admin', 'fas fa-times-circle');
            
            $leave->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Leave application rejected successfully!',
                'leave' => $leave,
                'stats' => $this->getAdminStats()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject leave: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add admin remarks to leave application
     */
    public function addRemarks(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'admin_remarks' => 'required|string|min:10'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
           $leave = $this->baseQuery()->findOrFail($id);
$this->authorize('manage', $leave);

            
            $leave->admin_remarks = $request->admin_remarks;
            
            // Add timeline entry
            $leave->addTimelineEntry('Admin remarks added', 'fas fa-comment');
            
            $leave->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Remarks added successfully!',
                'leave' => $leave
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add remarks: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update leave application (for admin)
     */
    public function update(Request $request, $id)
    {
        try {
           $leave = $this->baseQuery()->findOrFail($id);
$this->authorize('manage', $leave);

            
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,approved,rejected',
                'admin_remarks' => 'nullable|string',
                'from_date' => 'sometimes|date',
                'to_date' => 'sometimes|date|after_or_equal:from_date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update status and add to timeline if status changed
            if ($request->status !== $leave->status) {
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

            // Update admin remarks if provided
            if ($request->has('admin_remarks')) {
                $leave->admin_remarks = $request->admin_remarks;
            }

            // Update dates if provided
            if ($request->has('from_date')) {
                $leave->from_date = $request->from_date;
            }
            
            if ($request->has('to_date')) {
                $leave->to_date = $request->to_date;
            }

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
                'leave' => $leave,
                'stats' => $this->getAdminStats()
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
           $leave = $this->baseQuery()->findOrFail($id);
$this->authorize('manage', $leave);

            $leave->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leave application deleted successfully!',
                'stats' => $this->getAdminStats()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics data for admin dashboard
     */
    public function getAnalytics(Request $request)
    {
        try {
            $startDate = $request->get('start_date', Carbon::now()->subMonth());
            $endDate = $request->get('end_date', Carbon::now());
            
          $base = $this->baseQuery();

$monthlyStats = (clone $base)
    ->select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('YEAR(created_at) as year'),
        DB::raw('count(*) as total'),
        DB::raw('SUM(CASE WHEN status="pending" THEN 1 ELSE 0 END) as pending'),
        DB::raw('SUM(CASE WHEN status="approved" THEN 1 ELSE 0 END) as approved'),
        DB::raw('SUM(CASE WHEN status="rejected" THEN 1 ELSE 0 END) as rejected')
    )
    ->whereBetween('created_at', [$startDate, $endDate])
    ->groupBy('year', 'month')
    ->orderBy('year')
    ->orderBy('month')
    ->get();
            // Department/Position statistics
          $positionStats = (clone $base)
    ->select('employee_position', DB::raw('count(*) as count'))
    ->groupBy('employee_position')
    ->orderBy('count', 'desc')
    ->limit(10)
    ->get();

$typeStats = (clone $base)
    ->select('leave_type', DB::raw('count(*) as count'))
    ->groupBy('leave_type')
    ->get();

$dailyStats = (clone $base)
    ->select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('count(*) as count')
    )
    ->where('created_at', '>=', Carbon::now()->subDays(30))
    ->groupBy('date')
    ->orderBy('date')
    ->get();

            
            return response()->json([
                'success' => true,
                'analytics' => [
                    'monthly_stats' => $monthlyStats,
                    'position_stats' => $positionStats,
                    'type_stats' => $typeStats,
                    'daily_stats' => $dailyStats,
                    'overall_stats' => $this->getAdminStats(),
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export leave applications for admin
     */
    public function export(Request $request, $format = 'csv')
    {
        try {
            $leaves = $this->baseQuery()->get();
            
            $filename = 'leave_applications_admin_' . date('Y-m-d') . '.' . $format;
            
            if ($format === 'csv') {
                return $this->exportToCsv($leaves, $filename);
            } elseif ($format === 'excel') {
                return $this->exportToExcel($leaves, $filename);
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
    private function exportToCsv($leaves, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($leaves) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Employee Name', 'Email', 'Position', 'Leave Type',
                'From Date', 'To Date', 'Total Days', 'Status', 'Subject',
                'Admin Remarks', 'Applied Date', 'Sent To'
            ]);

            // Add data rows
            foreach ($leaves as $leave) {
                fputcsv($file, [
                    $leave->id,
                    $leave->employee_name,
                    $leave->employee_email,
                    $leave->employee_position,
                    $leave->leave_type_name,
                    $leave->from_date->format('Y-m-d'),
                    $leave->to_date->format('Y-m-d'),
                    $leave->total_days,
                    ucfirst($leave->status),
                    $leave->subject,
                    $leave->admin_remarks ?? '',
                    $leave->applied_date->format('Y-m-d H:i:s'),
                    $leave->sent_to,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to Excel
     */
    private function exportToExcel($leaves, $filename)
    {
        // For Excel export, you would typically use a package like Laravel Excel
        // This is a simplified version
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->json([
            'success' => true,
            'message' => 'Excel export would be implemented with Laravel Excel package',
            'filename' => $filename
        ]);
    }

    /**
     * Get filters data for admin
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
            
            // Get unique positions
            $positions = $this->baseQuery()
    ->select('employee_position')
    ->distinct()
    ->orderBy('employee_position')
    ->pluck('employee_position');

            
            return response()->json([
                'success' => true,
                'filters' => [
                    'leave_types' => $leaveTypes,
                    'statuses' => $statuses,
                    'positions' => $positions,
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