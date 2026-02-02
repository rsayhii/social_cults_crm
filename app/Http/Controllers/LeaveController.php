<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Apply for leave (employee)
     */
    public function apply(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'leave_type' => 'required|string|max:100',
            'reason' => 'nullable|string',
        ]);

        $leave = Leave::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'date' => $data['date']
            ],
            [
                'leave_type' => $data['leave_type'],
                'reason' => $data['reason'] ?? null,
                'status' => 'pending'
            ]
        );

        return response()->json([
            'message' => 'Leave applied successfully',
            'leave' => $leave
        ], 201);
    }


    /**
     * Cancel leave (employee or admin)
     */
    public function cancel(Request $request, Leave $leave)
    {
        if (Auth::id() !== $leave->user_id && !Auth::user()->can('manage-leaves')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $leave->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Leave cancelled successfully'
        ]);
    }


    /**
     * Admin approve/reject leave
     */
    public function adminUpdate(Request $request, Leave $leave)
    {
        $this->authorize('manage-leaves');

        $data = $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $leave->update([
            'status' => $data['status'],
            'approved_by' => Auth::id()
        ]);

        return response()->json([
            'message' => 'Leave updated successfully',
            'leave' => $leave
        ]);
    }
}
