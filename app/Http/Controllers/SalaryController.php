<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Salary;
use App\Models\MyAttendance;
use App\Models\SalaryConfig;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /* =========================================================
     |  BASE COMPANY SCOPED QUERIES
     ========================================================= */
    private function baseSalaryQuery()
    {
        return Salary::where('company_id', auth()->user()->company_id);
    }

    private function baseAttendanceQuery()
    {
        return MyAttendance::where('company_id', auth()->user()->company_id);
    }

    private function baseEmployeeQuery()
    {
        return User::where('company_id', auth()->user()->company_id);
    }

    /* =========================================================
     |  GENERATE SALARY FORM
     ========================================================= */
    public function showGenerateForm()
    {
        $employees = $this->baseEmployeeQuery()
            ->where('salary', '>', 0)
            ->get();

        $recentSalaries = $this->baseSalaryQuery()
            ->with('employee')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.salary_generate', compact('employees', 'recentSalaries'));
    }

    /* =========================================================
     |  GENERATE SINGLE SALARY
     ========================================================= */
    public function generateSalary(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|digits:4',
        ]);

        $employee = $this->baseEmployeeQuery()
            ->findOrFail($request->employee_id);

        // Prevent duplicate salary
        $existingSalary = $this->baseSalaryQuery()
            ->where('employee_id', $employee->id)
            ->whereYear('salary_month', $request->year)
            ->whereMonth('salary_month', $request->month)
            ->first();

        if ($existingSalary) {
            return response()->json([
                'success' => false,
                'error' => 'Salary already generated for this month',
            ]);
        }

        try {
            $salary = (new Salary())->calculateSalary(
                $employee,
                $request->month,
                $request->year
            );

            $salary->load(['employee', 'details']);

            return response()->json([
                'success' => true,
                'message' => 'Salary generated successfully',
                'salary' => $salary,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /* =========================================================
     |  BULK SALARY GENERATION
     ========================================================= */
    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|digits:4',
        ]);

        $employees = $this->baseEmployeeQuery()
            ->where('salary', '>', 0)
            ->get();

        $results = [];

        foreach ($employees as $employee) {
            try {
                $exists = $this->baseSalaryQuery()
                    ->where('employee_id', $employee->id)
                    ->whereYear('salary_month', $request->year)
                    ->whereMonth('salary_month', $request->month)
                    ->exists();

                if ($exists) {
                    $results[] = [
                        'employee' => $employee->name,
                        'status' => 'skipped',
                        'message' => 'Salary already exists',
                    ];
                    continue;
                }

                $salary = (new Salary())->calculateSalary(
                    $employee,
                    $request->month,
                    $request->year
                );

                $results[] = [
                    'employee' => $employee->name,
                    'status' => 'success',
                    'net_salary' => $salary->net_salary,
                ];

            } catch (\Exception $e) {
                $results[] = [
                    'employee' => $employee->name,
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }

    /* =========================================================
     |  SALARY LIST
     ========================================================= */
    public function index(Request $request)
    {
        $query = $this->baseSalaryQuery()->with('employee');

        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->month) {
            $query->whereMonth('salary_month', $request->month);
        }

        if ($request->year) {
            $query->whereYear('salary_month', $request->year);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $salaries = $query->orderByDesc('id')->paginate(20);

        $employees = $this->baseEmployeeQuery()->get();

        $statusCounts = [
            'paid' => $this->baseSalaryQuery()->where('status', 'paid')->count(),
            'pending' => $this->baseSalaryQuery()->where('status', 'pending')->count(),
            'approved' => $this->baseSalaryQuery()->where('status', 'approved')->count(),
        ];

        $totalPaidAmount = $this->baseSalaryQuery()
            ->where('status', 'paid')
            ->sum('net_salary');

        return view('admin.salary_list', compact(
            'salaries',
            'employees',
            'statusCounts',
            'totalPaidAmount'
        ));
    }

    /* =========================================================
     |  SALARY SLIP
     ========================================================= */
    public function showSlip($id)
    {
        $salary = $this->baseSalaryQuery()
            ->with(['employee.company', 'details'])
            ->findOrFail($id);

        return view('admin.salary_slip', compact('salary'));
    }

    /* =========================================================
     |  MARK AS PAID
     ========================================================= */
    public function markAsPaid($id)
    {
        $salary = $this->baseSalaryQuery()->findOrFail($id);

        $salary->update([
            'status' => 'paid',
            'payment_date' => now()->toDateString(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Salary marked as paid',
        ]);
    }

    /* =========================================================
     |  EMPLOYEE SALARY HISTORY
     ========================================================= */
    public function getEmployeeSalaries($employeeId)
    {
        $employee = $this->baseEmployeeQuery()->findOrFail($employeeId);

        $salaries = $this->baseSalaryQuery()
            ->where('employee_id', $employeeId)
            ->orderByDesc('salary_month')
            ->get();

        return response()->json([
            'success' => true,
            'employee' => $employee,
            'salaries' => $salaries,
        ]);
    }

    /* =========================================================
     |  EXPORT (CSV / PDF)
     ========================================================= */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:excel,pdf',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|digits:4',
        ]);

        $month = $request->month;
        $year = $request->year;
        $format = $request->format;

        $salaries = $this->baseSalaryQuery()
            ->with('employee')
            ->whereYear('salary_month', $year)
            ->whereMonth('salary_month', $month)
            ->get();

        if ($format === 'excel') {
            return $this->exportExcel($salaries, $month, $year);
        }

        return $this->exportPdf($salaries, $month, $year);
    }

    private function exportExcel($salaries, $month, $year)
    {
        $filename = "Salary_Report_{$month}_{$year}.csv";

        return response()->stream(function () use ($salaries) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Employee', 'Email', 'Month', 'Net Salary', 'Status'
            ]);

            foreach ($salaries as $salary) {
                fputcsv($file, [
                    $salary->employee->name,
                    $salary->employee->email,
                    Carbon::parse($salary->salary_month)->format('F Y'),
                    $salary->net_salary,
                    $salary->status,
                ]);
            }

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    private function exportPdf($salaries, $month, $year)
    {
        $html = view('admin.salary_exportpdf', compact('salaries', 'month', 'year'))->render();
        return \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
            ->setPaper('A4', 'landscape')
            ->download("Salary_Report_{$month}_{$year}.pdf");
    }

    /* =========================================================
     |  EDIT SALARY
     ========================================================= */
    public function edit($id)
    {
        $salary = $this->baseSalaryQuery()->with('employee')->findOrFail($id);
        return view('admin.salary_edit', compact('salary'));
    }

    /* =========================================================
     |  UPDATE SALARY
     ========================================================= */
    public function update(Request $request, $id)
    {
        $salary = $this->baseSalaryQuery()->findOrFail($id);
        
        $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'total_allowances' => 'required|numeric|min:0',
            'total_deductions' => 'required|numeric|min:0',
            'overtime_amount' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,approved',
        ]);

        $salary->update([
            'basic_salary' => $request->basic_salary,
            'total_allowances' => $request->total_allowances,
            'total_deductions' => $request->total_deductions,
            'overtime_amount' => $request->overtime_amount,
            'net_salary' => $request->net_salary,
            'status' => $request->status,
        ]);

        if ($request->status == 'paid' && !$salary->payment_date) {
            $salary->update(['payment_date' => now()->toDateString()]);
        }

        return redirect()->route('salary.list')->with('success', 'Salary updated successfully');
    }

    /* =========================================================
     |  RECALCULATE SALARY DATA
     ========================================================= */
    public function recalculate($id)
    {
        $salary = $this->baseSalaryQuery()->with('employee')->findOrFail($id);
        
        $date = Carbon::parse($salary->salary_month);
        $month = $date->month;
        $year = $date->year;
        
        // Pass existing basic_salary as override in case User profile has no salary set
        $calculatedData = (new Salary())->calculateSalaryData($salary->employee, $month, $year, $salary->basic_salary);
        
        // Include attendance summary for the UI
        $calculatedData['attendance_summary'] = [
            'total_present_days' => $calculatedData['total_present_days'],
            'total_absent_days' => $calculatedData['total_absent_days'],
            'total_half_days' => $calculatedData['total_half_days'],
            'total_late_days' => $calculatedData['total_late_days'],
        ];

        return response()->json([
            'success' => true,
            'data' => $calculatedData
        ]);
    }

    /* =========================================================
     |  DELETE SALARY
     ========================================================= */
    public function destroy($id)
    {
        $salary = $this->baseSalaryQuery()->findOrFail($id);
        $salary->delete();
        
        if (request()->wantsJson()) {
             return response()->json(['success' => true]);
        }
        
        return redirect()->route('salary.list')->with('success', 'Salary record deleted successfully');
    }
}
