<?php

namespace App\Http\Controllers;

use App\Models\EmployeePortal;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalenderController extends Controller
{
    public function index()
    {
        return view('admin.calender');
    }

public function getCalendarData(Request $request)
{
    $year = $request->input('year', date('Y'));
    $month = $request->input('month', date('m'));

    /* -------------------------
       1. REMOVE 🔥 EXTERNAL API HOLIDAY FETCH  
          (Improves Speed & Keeps Only Local JSON + DB)
    ------------------------- */

    $apiHolidays = []; // EMPTY → No external API request


    /* -------------------------
       2. DB HOLIDAYS
    ------------------------- */
    $dbHolidays = Holiday::whereYear('date', $year)
        ->whereMonth('date', $month)
        ->get()
        ->map(function ($holiday) {
            return [
                'id'       => $holiday->id,
                'title'    => $holiday->title,
                'date'     => $holiday->date->format('Y-m-d'),
                'category' => $holiday->category,
                'country'  => 'Local',
                'type'     => 'holiday'
            ];
        })
        ->toArray();


    /* -------------------------
       3. JSON INDIAN FESTIVALS
    ------------------------- */
    $festivalHolidays = [];
    $festivalFile = public_path('holidays/indian_festivals.json');

    if (!file_exists($festivalFile)) {
        return response()->json([
            'error' => "Festival JSON file not found at: $festivalFile"
        ]);
    }

    $jsonData = json_decode(file_get_contents($festivalFile), true);

    if (!$jsonData) {
        return response()->json([
            'error' => "Festival file is invalid or empty"
        ]);
    }

    foreach ($jsonData as $festival) {
        if (
            date('Y', strtotime($festival['date'])) == $year &&
            date('m', strtotime($festival['date'])) == $month
        ) {
            $festivalHolidays[] = [
                'id'       => null,
                'title'    => $festival['title'],
                'date'     => $festival['date'],
                'category' => $festival['category'],
                'country'  => 'India',
                'type'     => 'festival'
            ];
        }
    }


    /* -------------------------
       4. MERGE ALL HOLIDAYS
    ------------------------- */
    $holidays = array_merge($dbHolidays, $festivalHolidays);


   /* -------------------------
   5. LEAVES (From EmployeePortal)
------------------------- */
$leaves = EmployeePortal::get()
    ->map(function ($leave) {
        return [
            'id'         => $leave->id,
            'employee'   => $leave->employee_name,
            'type'       => $leave->leave_type,
            'fromDate'   => $leave->from_date->format('Y-m-d'),
            'toDate'     => $leave->to_date->format('Y-m-d'),
            'status'     => $leave->status,
            'total_days' => $leave->total_days,
        ];
    });


    return response()->json([
        'holidays' => $holidays,
        'leaves'   => $leaves
    ]);
}





    public function applyLeave(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:Vacation,Sick Leave,Personal Day,Maternity/Paternity',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'description' => 'required|string|max:500'
        ]);

        try {
            $leave = Leave::create([
                'user_id' => Auth::id(),
                'type' => $request->type,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'description' => $request->description,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave application submitted successfully!',
                'leave' => $leave->load('user')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting leave application: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addHoliday(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'category' => 'required|string|in:Public Holiday,Company Holiday,Observance',
            'description' => 'nullable|string|max:500'
        ]);

        try {
            $holiday = Holiday::create([
                'title' => $request->title,
                'date' => $request->date,
                'category' => $request->category,
                'description' => $request->description
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Holiday added successfully!',
                'holiday' => $holiday
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding holiday: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUsers()
    {
        $users = User::select('id', 'name')->get();
        return response()->json($users);
    }

    
}