<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Load Report page
     */
    public function index()
    {
        return view('admin.report');
    }

    /**
     * Fetch reports with filters, search, pagination
     */
    public function getReports(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->get('per_page', 5);
            $page = (int) $request->get('page', 1);

            $query = Report::where('company_id', auth()->user()->company_id);


            // Filter: status
            if ($request->status && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Filter: date range
            if ($request->start_date) {
                $query->where('date', '>=', $request->start_date);
            }
            if ($request->end_date) {
                $query->where('date', '<=', $request->end_date);
            }

            // Search in summary or tasks JSON
            if ($request->search) {
                $searchTerm = $request->search;

                $query->where(function ($q) use ($searchTerm) {
                    $q->where('summary', 'like', "%{$searchTerm}%")
                      ->orWhere('tasks', 'like', "%{$searchTerm}%");
                });
            }

            $reports = $query->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'reports' => $reports
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reports',
            ], 500);
        }
    }

    /**
     * Dashboard stats: total, weekly, productivity
     */
    public function getStats(): JsonResponse
    {
        try {
            $userId = Auth::id();
            $now = Carbon::now();

            $startOfWeek = $now->copy()->startOfWeek();
            $endOfWeek = $now->copy()->endOfWeek();

            // Total reports
            $totalReports = Report::where('user_id', $userId)->count();

            // Reports this week
            $weekReports = Report::where('user_id', $userId)
                ->whereBetween('date', [$startOfWeek, $endOfWeek])
                ->count();

            // Reports last 30 days
            $last30 = Report::where('user_id', $userId)
                ->where('date', '>=', $now->copy()->subDays(30))
                ->get();

            // Average tasks
            $totalTasks = 0;
            foreach ($last30 as $report) {
                $totalTasks += is_array($report->tasks) ? count($report->tasks) : 0;
            }

            $avgTasks = $last30->count() > 0 
                ? round($totalTasks / $last30->count(), 1)
                : 0;

            // Productivity % (completed / total)
            $completed = $last30->where('status', 'completed')->count();
            $productivity = $last30->count() > 0 
                ? round(($completed / $last30->count()) * 100)
                : 0;

            return response()->json([
                'success' => true,
                'total_reports' => $totalReports,
                'week_reports' => $weekReports,
                'avg_tasks' => $avgTasks,
                'productivity' => $productivity,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }

    /**
     * Create a report
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'date' => 'required|date',
                'summary' => 'required|string|max:1000',
                'status' => 'required|in:pending,inprogress,completed',
                'tasks' => 'required|array|min:1',
                'tasks.*.title' => 'required|string|max:255',
                'tasks.*.time' => 'required|numeric|min:0.5',
                'tasks.*.unit' => 'required|in:hours,minutes',
            ]);

            $report = Report::create([
                'date' => $validated['date'],
                'summary' => $validated['summary'],
                'status' => $validated['status'],
                'tasks' => $validated['tasks'],
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Report created successfully',
                'report' => $report,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create report'
            ], 500);
        }
    }

    /**
     * View a single report
     */
    public function show($id): JsonResponse
    {
        try {
            $report = Report::findOrFail($id);
$this->authorize('manage', $report);


            return response()->json([
                'success' => true,
                'report' => $report
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], 404);
        }
    }

    /**
     * Update a report
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
           $report = Report::findOrFail($id);
$this->authorize('manage', $report);


            $validated = $request->validate([
                'date' => 'required|date',
                'summary' => 'required|string|max:1000',
                'status' => 'required|in:pending,inprogress,completed',
                'tasks' => 'required|array|min:1',
                'tasks.*.title' => 'required|string|max:255',
                'tasks.*.time' => 'required|numeric|min:0.5',
                'tasks.*.unit' => 'required|in:hours,minutes',
            ]);

            $report->update([
                'date' => $validated['date'],
                'summary' => $validated['summary'],
                'status' => $validated['status'],
                'tasks' => $validated['tasks'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Report updated successfully',
                'report' => $report->fresh(), // Reload to get updated data
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update report'
            ], 500);
        }
    }

    /**
     * Delete report
     */
    public function destroy($id): JsonResponse
    {
        try {
           $report = Report::findOrFail($id);
$this->authorize('manage', $report);

            $report->delete();

            return response()->json([
                'success' => true,
                'message' => 'Report deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete report'
            ], 500);
        }
    }
}