<?php

namespace App\Http\Controllers;

use App\Models\EmployeePortal;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\CompanyHoliday;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Imports\CompanyHolidaysImport;

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
        $companyId = Auth::user()->company_id;

        $hasCompanyHolidays = CompanyHoliday::where('company_id', $companyId)->exists();
        \Illuminate\Support\Facades\Log::info('Calendar Data Request', ['year' => $year, 'month' => $month, 'companyId' => $companyId, 'hasCompanyHolidays' => $hasCompanyHolidays]);

        $holidays = [];

        if ($hasCompanyHolidays) {
            \Illuminate\Support\Facades\Log::info('Fetching Company Holidays from DB');
            // 1. COMPANY CUSTOM HOLIDAYS (Overrides default)
            $holidays = CompanyHoliday::where('company_id', $companyId)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get()
                ->map(function ($holiday) {
                    return [
                        'id' => $holiday->id,
                        'title' => $holiday->title,
                        'date' => $holiday->date->format('Y-m-d'),
                        'category' => $holiday->category,
                        'country' => 'Company',
                        'type' => 'holiday' // treated mainly as holiday
                    ];
                })
                ->toArray();
        } else {
            // 2. FALLBACK TO DEFAULT (DB + JSON)

            /* DB Global Holidays */
            $dbHolidays = Holiday::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get()
                ->map(function ($holiday) {
                    return [
                        'id' => $holiday->id,
                        'title' => $holiday->title,
                        'date' => $holiday->date->format('Y-m-d'),
                        'category' => $holiday->category,
                        'country' => 'Local',
                        'type' => 'holiday'
                    ];
                })
                ->toArray();

            /* JSON Indian Festivals */
            $festivalHolidays = [];
            $festivalFile = public_path('holidays/indian_festivals.json');

            if (file_exists($festivalFile)) {
                $jsonData = json_decode(file_get_contents($festivalFile), true);
                if ($jsonData) {
                    foreach ($jsonData as $festival) {
                        if (
                            date('Y', strtotime($festival['date'])) == $year &&
                            date('m', strtotime($festival['date'])) == $month
                        ) {
                            $festivalHolidays[] = [
                                'id' => null,
                                'title' => $festival['title'],
                                'date' => $festival['date'],
                                'category' => $festival['category'],
                                'country' => 'India',
                                'type' => 'festival'
                            ];
                        }
                    }
                }
            }

            $holidays = array_merge($dbHolidays, $festivalHolidays);
        }

        /* -------------------------
        5. LEAVES (From EmployeePortal)
     ------------------------- */
        $leaves = EmployeePortal::get()
            ->map(function ($leave) {
                return [
                    'id' => $leave->id,
                    'employee' => $leave->employee_name,
                    'type' => $leave->leave_type,
                    'fromDate' => $leave->from_date->format('Y-m-d'),
                    'toDate' => $leave->to_date->format('Y-m-d'),
                    'status' => $leave->status,
                    'total_days' => $leave->total_days,
                ];
            });


        return response()->json([
            'holidays' => $holidays,
            'leaves' => $leaves
        ]);
    }

    public function importHolidays(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        if ($request->hasFile('file')) {
            $extension = strtolower($request->file('file')->getClientOriginalExtension());
            if (!in_array($extension, ['xlsx', 'csv', 'xls'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'The file must be a file of type: xlsx, csv, xls.'
                ], 422);
            }
        }

        try {
            $companyId = Auth::user()->company_id;

            // Overwrite existing holidays
            CompanyHoliday::where('company_id', $companyId)->delete();

            // Validate Headings
            $headings = (new \Maatwebsite\Excel\HeadingRowImport)->toArray($request->file('file'));

            // HeadingRowImport returns [0 => [['title', 'date', ...]]]
            if (isset($headings[0][0])) {
                $fileHeadings = $headings[0][0];

                // key is usually normalized to slug (lowercase, no spaces) by default in Laravel Excel
                // but HeadingRowImport might return raw if not configured, usually it returns slugged
                // Let's check for containment of 'title' and 'date'

                $required = ['title', 'date', 'category', 'description'];
                $missing = array_diff($required, $fileHeadings);

                \Illuminate\Support\Facades\Log::info('Header Validation:', [
                    'file_headers' => $fileHeadings,
                    'required' => $required,
                    'missing' => $missing
                ]);

                if (count($missing) > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => "The import file format doesn't match. Please check the sample file and do not count on editing the headings."
                    ], 400);
                }
            }

            Excel::import(new CompanyHolidaysImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Holidays imported successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing holidays: ' . $e->getMessage()
            ], 500);
        }
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