@extends('components.layout')

@section('content')
<div class="py-4 md:py-6">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg mb-4 md:mb-6">
            <div class="p-4 md:p-6 bg-white shadow text-black">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="mb-3 sm:mb-0">
                        <h2 class="text-xl sm:text-2xl font-bold">Edit Salary</h2>
                        <p class="text-blue-900 mt-1 text-sm sm:text-base">Edit salary record for {{ $salary->employee->name }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:gap-3">
                        <a href="{{ route('salary.list') }}" 
                           class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-white/40 hover:bg-white/30 rounded-lg transition-colors text-sm sm:text-base min-w-[120px]">
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 mr-2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM16 18H8v-2h8v2zm0-4H8v-2h8v2zm-3-8V3.5L18.5 9H13a1 1 0 0 1-1-1z"/>
                            </svg>
                            <span class="truncate">Salary Records</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Salary Edit Form -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                    <div class="p-4 md:p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Salary Details</h3>
                        <button type="button" id="recalculateBtn" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Recalculate from Attendance
                        </button>
                    </div>
                    
                    <form action="{{ route('salary.update', $salary->id) }}" method="POST" class="p-4 md:p-6 space-y-4 md:space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Employee Info (Read Only) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                                <input type="text" value="{{ $salary->employee->name }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                                <input type="text" value="{{ \Carbon\Carbon::parse($salary->salary_month)->format('F Y') }}" disabled class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                            </div>
                        </div>

                        <!-- Basic Salary -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Basic Salary</label>
                            <input type="number" name="basic_salary" value="{{ $salary->basic_salary }}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Allowances & Deductions -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Total Allowances</label>
                                <input type="number" name="total_allowances" value="{{ $salary->total_allowances }}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Total Deductions</label>
                                <input type="number" name="total_deductions" value="{{ $salary->total_deductions }}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <!-- Overtime -->
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Amount</label>
                            <input type="number" name="overtime_amount" value="{{ $salary->overtime_amount }}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Net Salary -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Net Salary</label>
                            <input type="number" name="net_salary" value="{{ $salary->net_salary }}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 font-bold text-lg">
                        </div>
                        
                         <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="pending" {{ $salary->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $salary->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="approved" {{ $salary->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Update Salary
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Summary Card -->
            <div class="lg:col-span-1">
                 <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg sticky top-6">
                    <div class="p-4 md:p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Attendance Summary</h3>
                    </div>
                    <div class="p-4 md:p-6 space-y-4">
                         <div class="flex justify-between items-center">
                            <span class="text-gray-600">Present Days</span>
                            <span id="summary_present" class="font-semibold">{{ $salary->total_present_days }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Absent Days</span>
                            <span id="summary_absent" class="font-semibold text-red-600">{{ $salary->total_absent_days }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Half Days</span>
                            <span id="summary_half" class="font-semibold text-yellow-600">{{ $salary->total_half_days }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Late Days</span>
                            <span id="summary_late" class="font-semibold text-orange-600">{{ $salary->total_late_days }}</span>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('recalculateBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will overwrite current values with calculated ones based on attendance.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, recalculate it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = document.getElementById('recalculateBtn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="animate-spin mr-2">&#9696;</span> Calculating...';
                btn.disabled = true;

                fetch(`{{ route('salary.recalculate', $salary->id) }}`)
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            const data = result.data;
                            
                            // Update form fields
                            document.querySelector('input[name="basic_salary"]').value = data.basic_salary;
                            document.querySelector('input[name="total_allowances"]').value = data.total_allowances;
                            document.querySelector('input[name="total_deductions"]').value = data.total_deductions;
                            document.querySelector('input[name="overtime_amount"]').value = data.overtime_amount;
                            document.querySelector('input[name="net_salary"]').value = data.net_salary;
                            
                            // Update attendance summary if elements exist
                            if(document.getElementById('summary_present')) document.getElementById('summary_present').innerText = data.total_present_days;
                            if(document.getElementById('summary_absent')) document.getElementById('summary_absent').innerText = data.total_absent_days;
                            if(document.getElementById('summary_half')) document.getElementById('summary_half').innerText = data.total_half_days;
                            if(document.getElementById('summary_late')) document.getElementById('summary_late').innerText = data.total_late_days;
                            
                            Swal.fire(
                                'Recalculated!',
                                'Salary values have been updated based on attendance.',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to recalculate salary.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'An error occurred while recalculating.',
                            'error'
                        );
                    })
                    .finally(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
            }
        });
    });
</script>
@endsection
