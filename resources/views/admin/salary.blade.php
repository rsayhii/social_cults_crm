{{-- resources/views/myattendance/salary.blade.php --}}
@extends('components.layout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary - Employee Attendance Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .shadow-custom {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-50">
 
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Salary Calculation -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Payroll Calculation</h2>
           
            <div class="bg-white rounded-xl shadow-custom p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Salary Calculation Panel</h3>
               
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="employee-select">
                            <option value="john">John Doe</option>
                            <option value="jane">Jane Smith</option>
                            <option value="robert">Robert Johnson</option>
                            <option value="emily">Emily Davis</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                        <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" id="salary-month">
                            <option>January 2024</option>
                            <option>February 2024</option>
                            <option>March 2024</option>
                            <option>April 2024</option>
                            <option>May 2024</option>
                            <option selected>June 2024</option>
                            <option>July 2024</option>
                            <option>August 2024</option>
                            <option>September 2024</option>
                            <option>October 2024</option>
                            <option>November 2024</option>
                            <option>December 2024</option>
                        </select>
                    </div>
                </div>
               
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Basic Salary:</span>
                            <span id="basic-salary" class="font-medium">₹50,000.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Working Days:</span>
                            <span id="working-days" class="font-medium">22</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Present Days:</span>
                            <span id="present-days" class="font-medium">20</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Working Hours:</span>
                            <span id="total-work-hours" class="font-medium">160</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Overtime Hours:</span>
                            <span id="overtime-hours" class="font-medium">8</span>
                        </div>
                    </div>
                   
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Per Day Salary:</span>
                            <span id="per-day-salary" class="font-medium">₹2,272.73</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Overtime Pay:</span>
                            <span id="overtime-pay" class="font-medium">₹1,200.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Late Penalty:</span>
                            <span id="late-penalty" class="font-medium text-red-600">-₹500.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Leave Deduction:</span>
                            <span id="leave-deduction" class="font-medium text-red-600">-₹4,545.46</span>
                        </div>
                        <hr>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-800 font-semibold">Final Salary:</span>
                            <span id="final-salary" class="font-bold text-green-600 text-xl">₹46,427.27</span>
                        </div>
                    </div>
                </div>
               
                <div class="flex justify-end">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg">
                        Generate Payslip
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Update Salary Calculation
        function updateSalaryCalculation() {
            // This would typically fetch data from backend
            // For demo, we'll use fixed values in Indian Rupees
            document.getElementById('basic-salary').textContent = '₹50,000.00';
            document.getElementById('working-days').textContent = '22';
            document.getElementById('present-days').textContent = '20';
            document.getElementById('total-work-hours').textContent = '160';
            document.getElementById('overtime-hours').textContent = '8';
            document.getElementById('per-day-salary').textContent = '₹2,272.73';
            document.getElementById('overtime-pay').textContent = '₹1,200.00';
            document.getElementById('late-penalty').textContent = '-₹500.00';
            document.getElementById('leave-deduction').textContent = '-₹4,545.46';
            document.getElementById('final-salary').textContent = '₹46,427.27';
        }
        // Initialize the application
        document.addEventListener('DOMContentLoaded', function() {
            // Generate sample data
            updateSalaryCalculation();
           
            // Add event listener for employee select to update calculation
            document.getElementById('employee-select').addEventListener('change', updateSalaryCalculation);
            document.getElementById('salary-month').addEventListener('change', updateSalaryCalculation);
        });
    </script>
</body>
</html>
@endsection