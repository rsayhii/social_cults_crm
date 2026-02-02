{{-- salary/slip.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Salary Slip</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: bold; }
        .salary-month { font-size: 18px; margin: 10px 0; }
        .info-section { margin-bottom: 20px; }
        .info-row { display: flex; margin-bottom: 10px; }
        .info-label { width: 150px; font-weight: bold; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .status-paid { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Your Company Name</div>
        <div class="salary-month">Salary Slip for {{ date('F Y', strtotime($salary->salary_month)) }}</div>
        <div>Generated on: {{ $salary->created_at->format('d-m-Y') }}</div>
    </div>
    
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Employee Name:</div>
            <div>{{ $salary->employee->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div>{{ $salary->employee->email }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Employee ID:</div>
            <div>{{ $salary->employee->id }}</div>
        </div>
    </div>
    
    <div class="info-section">
        <h3>Attendance Summary</h3>
        <div class="info-row">
            <div class="info-label">Present Days:</div>
            <div>{{ $salary->total_present_days }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Late Days:</div>
            <div>{{ $salary->total_late_days }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Half Days:</div>
            <div>{{ $salary->total_half_days }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Absent Days:</div>
            <div>{{ $salary->total_absent_days }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Per Day Salary:</div>
            <div>₹{{ number_format($salary->per_day_salary, 2) }}</div>
        </div>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td>{{ number_format($salary->basic_salary, 2) }}</td>
            </tr>
            
            @foreach($salary->details as $detail)
            <tr>
                <td>{{ $detail->description }}</td>
                <td>
                    @if($detail->type == 'allowance')
                    + {{ number_format($detail->amount, 2) }}
                    @else
                    - {{ number_format($detail->amount, 2) }}
                    @endif
                </td>
            </tr>
            @endforeach
            
            <tr class="total-row">
                <td><strong>Overtime Amount</strong></td>
                <td>+ {{ number_format($salary->overtime_amount, 2) }}</td>
            </tr>
            
            <tr class="total-row">
                <td><strong>Total Allowances</strong></td>
                <td>{{ number_format($salary->total_allowances, 2) }}</td>
            </tr>
            
            <tr class="total-row">
                <td><strong>Total Deductions</strong></td>
                <td>{{ number_format($salary->total_deductions, 2) }}</td>
            </tr>
            
            <tr class="total-row" style="background-color: #e8f4fd;">
                <td><strong>NET SALARY</strong></td>
                <td><strong>₹{{ number_format($salary->net_salary, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
    
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Payment Status:</div>
            <div class="status-{{ $salary->status }}">{{ strtoupper($salary->status) }}</div>
        </div>
        
        @if($salary->payment_date)
        <div class="info-row">
            <div class="info-label">Payment Date:</div>
            <div>{{ date('d-m-Y', strtotime($salary->payment_date)) }}</div>
        </div>
        @endif
        
        @if($salary->notes)
        <div class="info-row">
            <div class="info-label">Notes:</div>
            <div>{{ $salary->notes }}</div>
        </div>
        @endif
    </div>
    
    <div style="margin-top: 40px; text-align: center; font-style: italic;">
        <p>This is a computer-generated document and does not require a signature.</p>
        <p>© {{ date('Y') }} Your Company Name. All rights reserved.</p>
    </div>
    
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Print Salary Slip</button>
        <button onclick="window.close()">Close</button>
    </div>
</body>
</html>