<?php

namespace App\Http\Controllers;

use App\Models\MyAttendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceRecordController extends Controller
{
    private function baseQuery()
    {
        return MyAttendance::where('company_id', auth()->user()->company_id);
    }

    public function index(Request $request)
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear  = $now->year;
        $currentMonthYear = $now->format('Y-m');

        // 1️⃣ Fetch attendance for current company & month
        $monthlyAttendances = $this->baseQuery()
            ->with('employee')
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->get();

        $totalRecords = $monthlyAttendances->count();

        // 2️⃣ Summary cards
        $totalPresent  = $monthlyAttendances->where('status', 'Present')->count();
        $totalAbsent   = $monthlyAttendances->where('status', 'Absent')->count();
        $totalHalfDay  = $monthlyAttendances->where('status', 'Half Day')->count();

        $totalEmployees = $monthlyAttendances->unique('employee_id')->count();

        $totalSummary = [
            'total_employees' => $totalEmployees,
            'total_records'   => $totalRecords,

            'present_count'   => $totalPresent,
            'absent_count'    => $totalAbsent,
            'half_day_count'  => $totalHalfDay,

            'present_percent' => $totalRecords > 0
                ? round(($totalPresent / $totalRecords) * 100, 1)
                : 0,

            'absent_percent' => $totalRecords > 0
                ? round(($totalAbsent / $totalRecords) * 100, 1)
                : 0,

            'other_percent' => $totalRecords > 0
                ? round(($totalHalfDay / $totalRecords) * 100, 1)
                : 0,
        ];

        // 3️⃣ Per-employee summary
        $employeeSummaries = $monthlyAttendances
            ->groupBy('employee_id')
            ->map(function ($records) {
                $employee = $records->first()->employee;

                return [
                    'employee_id'    => $employee->id ?? null,
                    'employee_name'  => $employee->name ?? 'N/A',
                    'present_count'  => $records->where('status', 'Present')->count(),
                    'absent_count'   => $records->where('status', 'Absent')->count(),
                    'half_day_count' => $records->where('status', 'Half Day')->count(),
                ];
            })
            ->values()
            ->sortBy('employee_name');

        // 4️⃣ Employees dropdown
        $employees = $employeeSummaries
            ->map(fn ($e) => [
                'id'   => $e['employee_id'],
                'name' => $e['employee_name'],
            ])
            ->unique('id')
            ->values();

        return view('admin.attendancerecord', compact(
            'employeeSummaries',
            'employees',
            'totalSummary',
            'currentMonthYear'
        ));
    }

    /**
     * AJAX: Monthly attendance per employee
     */
    public function getMonthlyAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'month'       => 'required|date_format:Y-m',
        ]);

        return $this->baseQuery()
            ->where('employee_id', $request->employee_id)
            ->whereYear('date', substr($request->month, 0, 4))
            ->whereMonth('date', substr($request->month, 5, 2))
            ->orderBy('date')
            ->get();
    }
}
