<!DOCTYPE html>
<html>
<head>
    <title>Salary Report - {{ $monthName }} {{ $year }}</title>
  <style>
/* ------------------------------ */
/* Embed Unicode Font (Required for ₹) */
/* ------------------------------ */
@font-face {
    font-family: 'DejaVu Sans';
    src: url("{{ storage_path('fonts/DejaVuSans.ttf') }}") format('truetype');
    font-weight: normal;
    font-style: normal;
}

/* ------------------------------ */
/* Global Styles */
/* ------------------------------ */
body {
    font-family: 'DejaVu Sans', sans-serif !important;
    margin: 20px;
    color: #333;
    font-size: 13px;
}

.header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #444;
}

.company-name {
    font-size: 26px;
    font-weight: bold;
    letter-spacing: 0.5px;
}

.report-title {
    font-size: 18px;
    font-weight: bold;
    margin-top: 8px;
}

.report-period {
    font-size: 15px;
    color: #666;
    margin-bottom: 5px;
}

/* ------------------------------ */
/* Summary Box */
/* ------------------------------ */
.summary {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #ddd;
    margin-bottom: 25px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}

.summary-label {
    font-weight: bold;
    font-size: 14px;
    color: #444;
}

.summary-value {
    font-weight: bold;
    font-size: 14px;
}

/* ------------------------------ */
/* Table Design */
/* ------------------------------ */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 12px;
}

.table th {
    background-color: #f2f2f2;
    border: 1px solid #bbb;
    padding: 8px;
    text-align: left;
    font-weight: bold;
}

.table td {
    border: 1px solid #ccc;
    padding: 8px;
}

.table tr:nth-child(even) {
    background-color: #fafafa;
}

.table tr:hover {
    background-color: #f1f1f1;
}

/* ------------------------------ */
/* Status Colors */
/* ------------------------------ */
.status-paid {
    color: green;
    font-weight: bold;
}

.status-pending {
    color: #e67e22;
    font-weight: bold;
}

.status-not-generated {
    color: #888;
    font-style: italic;
}

/* ------------------------------ */
/* Totals Row */
/* ------------------------------ */
.total-row {
    background-color: #f8f9fa;
    font-weight: bold;
}

/* ------------------------------ */
/* Footer */
/* ------------------------------ */
.footer {
    margin-top: 40px;
    text-align: center;
    font-size: 12px;
    color: #666;
    border-top: 1px solid #ccc;
    padding-top: 15px;
}

</style>

</head>
<body>
    <div class="header">
        <div class="company-name">Your Company Name</div>
        <div class="report-title">Salary Report</div>
        <div class="report-period">{{ $monthName }} {{ $year }}</div>
        <div>Generated on: {{ now()->format('d-m-Y H:i:s') }}</div>
    </div>
    
    <!-- Summary -->
    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Total Employees:</span>
            <span class="summary-value">{{ $salaries->count() }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Salary Paid:</span>
            <span class="summary-value">₹{{ number_format($salaries->where('status', 'paid')->sum('net_salary')) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Salaries Generated:</span>
            <span class="summary-value">{{ $salaries->where('status', '!=', 'Not Generated')->count() }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Pending Generation:</span>
            <span class="summary-value">{{ $salaries->where('status', 'Not Generated')->count() }}</span>
        </div>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Email</th>
                <th>Basic Salary</th>
                <th>Present Days</th>
                <th>Absent Days</th>
                <th>Late Days</th>
                <th>Half Days</th>
                <th>Allowances</th>
                <th>Deductions</th>
                <th>Overtime</th>
                <th>Net Salary</th>
             
            </tr>
        </thead>
        <tbody>
            @foreach($salaries as $index => $salary)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $salary->employee->id }}</td>
                <td>{{ $salary->employee->name }}</td>
                <td>{{ $salary->employee->email }}</td>
                <td>{{ '₹'.number_format($salary->basic_salary ?? $salary->employee->salary) }}</td>
                <td>{{ $salary->total_present_days ?? 0 }}</td>
                <td>{{ $salary->total_absent_days ?? 0 }}</td>
                <td>{{ $salary->total_late_days ?? 0 }}</td>
                <td>{{ $salary->total_half_days ?? 0 }}</td>
                <td>{{ '₹'.number_format($salary->total_allowances ?? 0) }}</td>
                <td>{{ '₹'.number_format($salary->total_deductions ?? 0) }}</td>
                <td>{{ '₹'.number_format($salary->overtime_amount ?? 0) }}</td>
                <td>{{ '₹'.number_format($salary->net_salary ?? 0) }}</td>
              
            </tr>
            @endforeach
            
            <!-- Totals Row -->
            <tr class="total-row">
                <td colspan="4" style="text-align: right;"><strong>Totals:</strong></td>
                <td>₹{{ number_format($salaries->sum('basic_salary')) }}</td>
                <td>{{ $salaries->sum('total_present_days') }}</td>
                <td>{{ $salaries->sum('total_absent_days') }}</td>
                <td>{{ $salaries->sum('total_late_days') }}</td>
                <td>{{ $salaries->sum('total_half_days') }}</td>
                <td>₹{{ number_format($salaries->sum('total_allowances')) }}</td>
                <td>₹{{ number_format($salaries->sum('total_deductions')) }}</td>
                <td>₹{{ number_format($salaries->sum('overtime_amount')) }}</td>
                <td>₹{{ number_format($salaries->sum('net_salary')) }}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="footer">
        <p>This is a computer-generated document. No signature is required.</p>
        <p>© {{ date('Y') }} Your Company Name. All rights reserved.</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>