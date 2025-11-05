<?php

namespace App\Http\Controllers;

use App\Models\Myattendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Kolkata');
        $currentDate = $now->format('Y-m-d');
        $currentDateTime = $now->format('h:i A, d M Y');

        // Today's attendance
        $todayAttendance = Myattendance::where('user_id', $user->id)
            ->where('date', $currentDate)
            ->first();

        $todayTotalHours = 0;
        $todayProductiveHours = 0;

        if ($todayAttendance && $todayAttendance->check_in && $todayAttendance->check_out) {
            $checkIn = Carbon::parse($todayAttendance->check_in, 'Asia/Kolkata');
            $checkOut = Carbon::parse($todayAttendance->check_out, 'Asia/Kolkata');
            $totalMinutes = $checkOut->diffInMinutes($checkIn);
            $todayTotalHours = round($totalMinutes / 60, 2);
            $breakMinutes = $todayAttendance->break_time ?? 0;
            $productiveMinutes = max(0, $totalMinutes - $breakMinutes);
            $todayProductiveHours = round($productiveMinutes / 60, 2);
        }

        // Filters
        $query = Myattendance::where('user_id', $user->id);

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]), 'Asia/Kolkata')->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]), 'Asia/Kolkata')->format('Y-m-d');
                    $query->whereBetween('date', [$startDate, $endDate]);
                } catch (\Exception $e) {
                    $startDate = $now->startOfMonth()->format('Y-m-d');
                    $endDate = $now->endOfMonth()->format('Y-m-d');
                    $query->whereBetween('date', [$startDate, $endDate]);
                }
            }
        } else {
            $startDate = $now->startOfMonth()->format('Y-m-d');
            $endDate = $now->endOfMonth()->format('Y-m-d');
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderBy('date', 'desc');
        $attendance = $query->paginate($request->per_page ?? 10);

        // Monthly stats
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $monthlyStats = Myattendance::where('user_id', $user->id)
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->get();

        $totalPresent = $monthlyStats->where('status', 'Present')->count();
        $totalAbsent = $monthlyStats->where('status', 'Absent')->count();

        $totalMonthlyHours = 0;
        foreach ($monthlyStats as $record) {
            if ($record->check_in && $record->check_out) {
                $in = Carbon::parse($record->check_in, 'Asia/Kolkata');
                $out = Carbon::parse($record->check_out, 'Asia/Kolkata');
                $totalMonthlyHours += $out->diffInHours($in);
            }
        }

        $totalLate = $monthlyStats->sum('late_minutes');
        $totalOvertime = $monthlyStats->sum('overtime_minutes');

        // Weekly stats
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();

        $weeklyStats = Myattendance::where('user_id', $user->id)
            ->whereBetween('date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
            ->get();

        $weeklyHours = 0;
        foreach ($weeklyStats as $record) {
            if ($record->check_in && $record->check_out) {
                $in = Carbon::parse($record->check_in, 'Asia/Kolkata');
                $out = Carbon::parse($record->check_out, 'Asia/Kolkata');
                $weeklyHours += $out->diffInHours($in);
            }
        }

        // Salary
        $baseSalary = 30000;
        $totalWorkingDays = 30;
        $presentDays = $totalPresent;
        $lateDays = $monthlyStats->where('late_minutes', '>', 0)->count();

        $perDaySalary = $baseSalary / $totalWorkingDays;
        $halfDayPenalty = floor($lateDays / 3) * ($perDaySalary / 2);
        $salaryForPresentDays = $perDaySalary * $presentDays;
        $finalSalary = round($salaryForPresentDays - $halfDayPenalty);

        $timeDistribution = $this->calculateTimeDistribution($todayAttendance);



        return view('admin.myattendance', compact(
            'attendance', 'todayAttendance', 'currentDateTime', 'todayTotalHours',
            'todayProductiveHours', 'totalPresent', 'totalAbsent', 'totalLate',
            'totalOvertime', 'weeklyHours', 'totalMonthlyHours', 'monthlyStats',
            'baseSalary', 'totalWorkingDays', 'presentDays', 'lateDays',
            'finalSalary', 'user', 'timeDistribution'
        ));
    }

    private function calculateTimeDistribution($attendance)
    {
        $default = ['gray' => 100, 'green' => 0, 'yellow' => 0, 'blue' => 0];
        if (!$attendance || !$attendance->check_in || !$attendance->check_out) return $default;

        $checkIn = Carbon::parse($attendance->check_in, 'Asia/Kolkata');
        $checkOut = Carbon::parse($attendance->check_out, 'Asia/Kolkata');
        $totalMinutes = $checkOut->diffInMinutes($checkIn);
        $breakMinutes = $attendance->break_time ?? 0;
        $standardMinutes = 8 * 60;
        $overtimeMinutes = max(0, $totalMinutes - $standardMinutes - $breakMinutes);
        $productiveMinutes = max(0, $totalMinutes - $breakMinutes - $overtimeMinutes);
        $nonWorkingMinutes = (24 * 60) - $totalMinutes;

        return [
            'gray' => round(($nonWorkingMinutes / 1440) * 100),
            'green' => round(($productiveMinutes / 1440) * 100),
            'yellow' => round(($breakMinutes / 1440) * 100),
            'blue' => round(($overtimeMinutes / 1440) * 100),
        ];
    }

    public function punchIn(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Kolkata');
        $currentDate = $now->format('Y-m-d');

        $existing = Myattendance::where('user_id', $user->id)->where('date', $currentDate)->first();
        if ($existing) return back()->with('error', 'You have already punched in today.');

        $checkInTime = $now;
        $lateMinutes = 0;
        $standardStart = Carbon::createFromTime(10, 0, 0, 'Asia/Kolkata');
        if ($checkInTime->gt($standardStart)) {
            $lateMinutes = $checkInTime->diffInMinutes($standardStart);
        }

        Myattendance::create([
            'user_id' => $user->id,
            'date' => $currentDate,
            'check_in' => $checkInTime,
            'status' => 'Present',
            'late_minutes' => $lateMinutes,
        ]);

        return back()->with('success', 'Punched in successfully at ' . $checkInTime->format('h:i A'));
    }

    public function punchOut(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Kolkata');
        $currentDate = $now->format('Y-m-d');

        $attendance = Myattendance::where('user_id', $user->id)->where('date', $currentDate)->first();
        if (!$attendance) return back()->with('error', 'You need to punch in first.');
        if ($attendance->check_out) return back()->with('error', 'You have already punched out today.');

        $checkOutTime = $now;
        $checkIn = Carbon::parse($attendance->check_in, 'Asia/Kolkata');
        $totalMinutes = $checkOutTime->diffInMinutes($checkIn);
        $standardMinutes = 9 * 60;
        $overtimeMinutes = max(0, $totalMinutes - $standardMinutes - ($attendance->break_time ?? 0));

        $attendance->update([
            'check_out' => $checkOutTime,
            'overtime_minutes' => $overtimeMinutes,
            'total_hours' => floor($totalMinutes / 60) . 'h ' . ($totalMinutes % 60) . 'm',
            'production_hours' => floor(($totalMinutes - ($attendance->break_time ?? 0)) / 60) . 'h ' . (($totalMinutes - ($attendance->break_time ?? 0)) % 60) . 'm'
        ]);

        return back()->with('success', 'Punched out successfully at ' . $checkOutTime->format('h:i A'));
    }

    public function toggleBreak(Request $request)
    {
        $user = Auth::user();
        $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d');
        $now = Carbon::now('Asia/Kolkata');

        $attendance = Myattendance::where('user_id', $user->id)->where('date', $currentDate)->first();
        if (!$attendance) {
            return response()->json(['error' => 'Please punch in first.'], 400);
        }
        if ($attendance->check_out) {
            return response()->json(['error' => 'Cannot take break after punch out.'], 400);
        }

        $currentBreakTime = $attendance->break_time ?? 0;

        if ($request->break_active) {
            // Start break
            if ($attendance->break_in && !$attendance->break_out) {
                return response()->json(['error' => 'Break already in progress.'], 400);
            }

            $attendance->update([
                'break_in' => $now,
                'break_time' => $currentBreakTime + 15
            ]);

            return response()->json([
                'success' => true,
                'break_time' => $currentBreakTime + 15,
                'break_display' => floor(($currentBreakTime + 15) / 60) . 'h ' . (($currentBreakTime + 15) % 60) . 'm',
                'break_in' => $now->format('h:i A'),
                'message' => 'Break started'
            ]);
        } else {
            // End break
            if (!$attendance->break_in || $attendance->break_out) {
                return response()->json(['error' => 'No active break to end.'], 400);
            }

            $attendance->update([
                'break_out' => $now
            ]);

            return response()->json([
                'success' => true,
                'break_time' => $currentBreakTime,
                'break_display' => floor($currentBreakTime / 60) . 'h ' . ($currentBreakTime % 60) . 'm',
                'break_out' => $now->format('h:i A'),
                'message' => 'Break ended'
            ]);
        }
    }
}