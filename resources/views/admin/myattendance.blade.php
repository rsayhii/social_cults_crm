@extends('components.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-0 text-sm">
    <div class="container mx-auto px-4 py-4">
        <!-- Breadcrumb -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 text-xs">
            <div class="mb-2 md:mb-0">
                <h2 class="text-lg font-bold text-gray-800 mb-1">Employee Attendance</h2>
                <nav class="flex text-xs" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1">
                        <li><a href="#" class="text-gray-500 hover:text-gray-700"><i class="fas fa-home mr-1"></i></a></li>
                        <li><span class="text-gray-400 mx-1">/</span><span class="text-gray-500">Employee</span></li>
                        <li><span class="text-gray-400 mx-1">/</span><span class="text-gray-700 font-medium">Attendance</span></li>
                    </ol>
                </nav>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <div class="flex items-center border bg-white rounded p-1">
                    <a href="#" class="px-2 py-1 bg-gray-800 text-white rounded text-xs"><i class="fas fa-calendar-day mr-1"></i></a>
                    <a href="#" class="px-2 py-1 text-gray-600 text-xs"><i class="fas fa-calendar-alt mr-1"></i></a>
                </div>
                <button class="flex items-center bg-white border rounded px-3 py-1 text-gray-700 text-xs">
                    <i class="fas fa-file-export mr-1"></i>Export
                </button>
                <button class="flex items-center bg-gray-800 text-white px-3 py-1 rounded text-xs">
                    <i class="fas fa-chart-bar mr-1"></i>Report
                </button>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-3 text-xs">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-3 text-xs">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
            <!-- Employee Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-4 text-xs">
                    <div class="text-center mb-3">
                        <h6 class="text-gray-500 mb-1">Good Morning, {{ Auth::user()->name }}</h6>
                        <h4 class="text-sm font-semibold">{{ $currentDateTime }}</h4>
                    </div>
                    <div class="relative w-28 h-28 mx-auto mb-3">
                        <div class="absolute inset-0 rounded-full border-8 border-gray-200"></div>
                        <div class="absolute inset-0 rounded-full border-8 border-green-500" style="clip-path: polygon(0 0, 50% 0, 50% 100%, 0 100%);"></div>
                        <div class="absolute inset-0 rounded-full border-8 border-green-500 transform rotate-180" style="clip-path: polygon(50% 0, 100% 0, 100% 65%, 50% 65%);"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <img src="https://smarthr.dreamstechnologies.com/laravel/template/public/build/img/profiles/avatar-27.jpg" alt="Profile" class="w-20 h-20 rounded-full">
                        </div>
                    </div>

                    <div class="text-center">
                        @if($todayAttendance && $todayAttendance->check_in)
                            <span class="inline-block bg-gray-600 text-white text-xs px-2 py-1 rounded-full mb-2">
                                Production: {{ $todayAttendance->production_hours ?? '0h 0m' }}
                            </span>
                            <h6 class="flex items-center justify-center text-gray-700 mb-3 text-xs">
                                <i class="fas fa-fingerprint text-primary mr-1"></i>
                                Punch In: 
                                @if($todayAttendance && $todayAttendance->check_in)
                                    {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('h:i A') }}
                                @else
                                    Not Punched In
                                @endif
                            </h6>

                            @if(!$todayAttendance->check_out)
                                <form action="{{ route('punch.out') }}" method="POST" onsubmit="return confirm('Are you sure you want to Punch Out?');">
                                    @csrf
                                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-1.5 rounded text-xs transition">
                                        Punch Out
                                    </button>
                                </form>
                            @else
                                <button class="w-full bg-gray-400 text-white py-1.5 rounded text-xs cursor-not-allowed" disabled>
                                    Already Punched Out
                                </button>
                            @endif
                        @else
                            <span class="inline-block bg-gray-500 text-white text-xs px-2 py-1 rounded-full mb-2">
                                Not Punched In
                            </span>
                            <button id="punchInBtn" type="button" class="w-full bg-gray-900 hover:bg-gray-500 text-white py-1.5 rounded-xl text-xs mb-2">
                                Punch In
                            </button>

                            <form id="punchInForm" method="POST" action="{{ route('punch.in') }}" style="display:none;">
                                @csrf
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                            </form>
                            <p id="locationStatus" class="text-xs text-gray-600 mt-1"></p>
                        @endif

                        <!-- Break Toggle -->
                        <div class="flex items-center justify-center space-x-2 mt-3 text-xs">
                            <label>Break</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="breakToggle" class="sr-only peer"
                                    {{ !$todayAttendance || !$todayAttendance->check_in || $todayAttendance->check_out ? 'disabled' : '' }}>
                                <div class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-green-500 peer-disabled:bg-gray-200"></div>
                                <div class="absolute left-0.5 top-0.5 bg-white w-4 h-4 rounded-full transition-all duration-300 peer-checked:translate-x-5"></div>
                            </label>
                            <span id="breakStatus" class="text-xs">
                                @if(!$todayAttendance || !$todayAttendance->check_in)
                                    PUNCH IN FIRST
                                @elseif($todayAttendance->check_out)
                                    PUNCHED OUT
                                @elseif($todayAttendance->break_in && !$todayAttendance->break_out)
                                    ON
                                @else
                                    OFF
                                @endif
                            </span>
                        </div>
                        <div id="breakTimeDisplay" class="text-xs text-gray-600 mt-1">
                            @if($todayAttendance && $todayAttendance->break_time)
                                Total Break: {{ floor($todayAttendance->break_time / 60) }}h {{ $todayAttendance->break_time % 60 }}m
                            @elseif($todayAttendance && $todayAttendance->break_in && !$todayAttendance->break_out)
                                Break started at: {{ \Carbon\Carbon::parse($todayAttendance->break_in)->setTimezone('Asia/Kolkata')->format('h:i A') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="lg:col-span-3">
                <!-- Cards Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="border-b border-gray-200 pb-3 mb-3">
                            <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white mb-2">
                                <i class="fas fa-stopwatch"></i>
                            </div>
                            <h2 class="text-lg font-bold">
                                {{ number_format($todayTotalHours, 1) }} <span class="text-lg text-gray-500">/ 9</span>
                            </h2>
                            <p class="font-medium truncate">Total Hours Today</p>
                        </div>
                        <div>
                            <p class="flex items-center text-sm">
                                <span class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white mr-2">
                                    <i class="fas fa-arrow-up text-xs"></i>
                                </span>
                                <span>5% This Week</span>
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="border-b border-gray-200 pb-3 mb-3">
                            <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-white mb-2">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h2 class="text-lg font-bold">{{ number_format($weeklyHours, 1) }} <span class="text-lg text-gray-500">/ 40</span></h2>
                            <p class="font-medium truncate">Total Hours Week</p>
                        </div>
                        <div>
                            <p class="flex items-center text-sm">
                                <span class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-white mr-2">
                                    <i class="fas fa-arrow-up text-xs"></i>
                                </span>
                                <span>7% Last Week</span>
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="border-b border-gray-200 pb-3 mb-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white mb-2">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <h2 class="text-lg font-bold">{{ number_format($totalMonthlyHours, 1) }} <span class="text-lg text-gray-500">/ 98</span></h2>
                            <p class="font-medium truncate">Total Hours Month</p>
                        </div>
                        <div>
                            <p class="flex items-center text-sm truncate">
                                <span class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white mr-2">
                                    <i class="fas fa-arrow-down text-xs"></i>
                                </span>
                                <span>8% Last Month</span>
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="border-b border-gray-200 pb-3 mb-3">
                            <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center text-white mb-2">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <h2 class="text-lg font-bold">{{ floor($totalOvertime / 60) }} <span class="text-lg text-gray-500">/ 28</span></h2>
                            <p class="font-medium truncate">Overtime this Month</p>
                        </div>
                        <div>
                            <p class="flex items-center text-sm truncate">
                                <span class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white mr-2">
                                    <i class="fas fa-arrow-down text-xs"></i>
                                </span>
                                <span>6% Last Month</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ✅ Separate Section for Total Salary (Dynamic Calculation) -->
                <div class="bg-white rounded-lg shadow p-3">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Total Salary Summary</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <p class="text-gray-600 text-sm">Total Working Days</p>
                            <h3 id="totalDays" class="text-xl font-bold text-gray-800">{{ $totalWorkingDays }}</h3>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Total Present Days</p>
                            <h3 id="presentDays" class="text-xl font-bold text-gray-800">{{ $presentDays }}</h3>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Late Days (after 10:00 AM)</p>
                            <h3 id="lateDays" class="text-xl font-bold text-gray-800">{{ $lateDays }}</h3>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Base Monthly Salary</p>
                            <h3 id="baseSalary" class="text-xl font-bold text-gray-800">₹{{ number_format($user->salary) }}</h3>
                        </div>
                    </div>

                    <div class="border-t pt-4 flex flex-col md:flex-row items-start md:items-center justify-between">
                        <div class="text-gray-700">
                            <p class="text-sm">
                                <strong>Salary Calculation:</strong>  
                                (Base Salary ÷ Total Days × Present Days) − (Late Days ÷ 2 × Per-Day Salary)
                            </p>
                        </div>

                        <div class="mt-4 md:mt-0 text-right">
                            <p class="text-gray-600 text-sm">Final Calculated Salary</p>
                            <h2 id="finalSalary" class="text-xl font-bold text-green-600">₹{{ number_format($finalSalary) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <!-- Hours Breakdown -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <p class="flex items-center mb-1">
                            <i class="fas fa-circle text-gray-400 mr-1"></i>Total Working hours
                        </p>
                        <h3 class="text-xl font-bold">
                            @if($todayAttendance && $todayAttendance->total_hours)
                                {{ $todayAttendance->total_hours }}
                            @else
                                0h 0m
                            @endif
                        </h3>
                    </div>
                    <div>
                        <p class="flex items-center mb-1">
                            <i class="fas fa-circle text-green-500 mr-1"></i>Productive Hours
                        </p>
                        <h3 class="text-xl font-bold">
                            @if($todayAttendance && $todayAttendance->production_hours)
                                {{ $todayAttendance->production_hours }}
                            @else
                                0h 0m
                            @endif
                        </h3>
                    </div>
                    <div>
                        <p class="flex items-center mb-1">
                            <i class="fas fa-circle text-yellow-500 mr-1"></i>Break hours
                        </p>
                        <h3 class="text-xl font-bold">
                            @if($todayAttendance && $todayAttendance->break_time)
                                {{ floor($todayAttendance->break_time / 60) }}h {{ $todayAttendance->break_time % 60 }}m
                            @else
                                0h 0m
                            @endif
                        </h3>
                    </div>
                    <div>
                        <p class="flex items-center mb-1">
                            <i class="fas fa-circle text-blue-500 mr-1"></i>Overtime
                        </p>
                        <h3 class="text-xl font-bold">
                            @if($todayAttendance && $todayAttendance->overtime_minutes)
                                {{ floor($todayAttendance->overtime_minutes / 60) }}h {{ $todayAttendance->overtime_minutes % 60 }}m
                            @else
                                0h 0m
                            @endif
                        </h3>
                    </div>
                </div>
                <div>
                    <!-- Dynamic Progress Bar -->
                    <div class="h-6 bg-gray-300 rounded-full mb-3 flex overflow-hidden">
                        <div class="bg-gray-400" style="width: {{ $timeDistribution['gray'] }}%"></div>
                        <div class="bg-green-500" style="width: {{ $timeDistribution['green'] }}%"></div>
                        <div class="bg-yellow-500" style="width: {{ $timeDistribution['yellow'] }}%"></div>
                        <div class="bg-blue-500" style="width: {{ $timeDistribution['blue'] }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600 mb-2">
                        <span>Non-Working: {{ $timeDistribution['gray'] }}%</span>
                        <span>Productive: {{ $timeDistribution['green'] }}%</span>
                        <span>Break: {{ $timeDistribution['yellow'] }}%</span>
                        <span>Overtime: {{ $timeDistribution['blue'] }}%</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span>12:00 AM</span>
                        <span>06:00 AM</span>
                        <span>12:00 PM</span>
                        <span>06:00 PM</span>
                        <span>12:00 AM</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow text-xs">
            <div class="p-3 border-b flex flex-col md:flex-row justify-between gap-2">
                <h5 class="font-semibold">Attendance Log</h5>
              <form method="GET" action="{{ route('myattendance') }}" class="flex flex-wrap gap-2 text-xs" id="filterForm">
    <div class="flex gap-1">
        <input type="date" name="start_date" id="startDate" class="px-2 py-1 border rounded text-xs" 
               value="@php
                    $dateRange = request('date_range', '');
                    $startValue = '';
                    if (!empty($dateRange)) {
                        $parts = explode(' - ', trim($dateRange));
                        if (isset($parts[0]) && trim($parts[0])) {
                            $date = DateTime::createFromFormat('d/m/Y', trim($parts[0]));
                            $startValue = $date ? $date->format('Y-m-d') : '';
                        }
                    }
                    echo $startValue;
                @endphp">
        <span class="self-center text-gray-500">-</span>
        <input type="date" name="end_date" id="endDate" class="px-2 py-1 border rounded text-xs" 
               value="@php
                    $dateRange = request('date_range', '');
                    $endValue = '';
                    if (!empty($dateRange)) {
                        $parts = explode(' - ', trim($dateRange));
                        if (isset($parts[1]) && trim($parts[1])) {
                            $date = DateTime::createFromFormat('d/m/Y', trim($parts[1]));
                            $endValue = $date ? $date->format('Y-m-d') : '';
                        }
                    }
                    echo $endValue;
                @endphp">
    </div>
    <input type="hidden" name="date_range" id="dateRangeHidden">
    <select name="status" onchange="updateDateRangeAndSubmit()" class="px-2 py-1 border rounded text-xs">
        <option value="">All Status</option>
        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
    </select>
    <button type="submit" class="bg-gray-900 text-white px-2 py-1 rounded text-xs"><i class="fas fa-filter mr-1"></i>Apply</button>
    <a href="{{ route('myattendance') }}" class="bg-gray-500 text-white px-2 py-1 rounded text-xs"><i class="fas fa-refresh mr-1"></i>Reset</a>
</form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-xs">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Date</th>
                            <th class="px-3 py-2 text-left">In</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-left">Out</th>
                            <th class="px-3 py-2 text-left">Break In</th>
                            <th class="px-3 py-2 text-left">Break Out</th>
                            <th class="px-3 py-2 text-left">Late</th>
                            <th class="px-3 py-2 text-left">OT</th>
                            <th class="px-3 py-2 text-left">Prod</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTable">
                        @forelse($attendance as $record)
                        <tr>
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                            <td class="px-3 py-2">{{ $record->check_in ?? '--' }}</td>
                            <td class="px-3 py-2">
                                <span class="px-2 py-1 rounded text-xs {{ $record->status == 'Present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                            <td class="px-3 py-2">{{ $record->check_out ?? '--' }}</td>
                            <td class="px-3 py-2">{{ $record->break_in ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $record->break_out ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $record->late_minutes ? $record->late_minutes.'m' : '-' }}</td>
                            <td class="px-3 py-2">{{ $record->overtime_minutes ? floor($record->overtime_minutes / 60).'h '.($record->overtime_minutes % 60).'m' : '-' }}</td>
                            <td class="px-3 py-2">
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">
                                    {{ $record->production_hours ?? '0h 0m' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center py-3 text-gray-500">No records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 flex justify-between text-xs">
                <p>Showing {{ $attendance->firstItem() }} - {{ $attendance->lastItem() }} of {{ $attendance->total() }}</p>
                {{ $attendance->links() }}
            </div>
        </div>
    </div>

    <script>
        // Confirm Punch In
        document.getElementById('punchInBtn')?.addEventListener('click', function () {
            if (confirm('Are you sure you want to Punch In?')) {
                const statusEl = document.getElementById('locationStatus');
                if (navigator.geolocation) {
                    statusEl.textContent = "Fetching location...";
                    navigator.geolocation.getCurrentPosition(
                        pos => {
                            const dist = getDistanceFromLatLonInKm(28.6039311, 77.3984592, pos.coords.latitude, pos.coords.longitude);
                            if (dist <= 0.3) {
                                document.getElementById('latitude').value = pos.coords.latitude;
                                document.getElementById('longitude').value = pos.coords.longitude;
                                statusEl.textContent = `Inside range (${dist.toFixed(2)} km)`;
                                document.getElementById('punchInForm').submit();
                            } else {
                                statusEl.textContent = `Outside range (${dist.toFixed(2)} km)`;
                            }
                        },
                        () => statusEl.textContent = "Location access denied."
                    );
                } else {
                    statusEl.textContent = "Geolocation not supported.";
                }
            }
        });

        function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = deg2rad(lat2 - lat1);
            const dLon = deg2rad(lon2 - lon1);
            const a = Math.sin(dLat/2)**2 + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2)**2;
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }
        function deg2rad(deg) { return deg * (Math.PI / 180); }

        // Break Toggle
        const breakToggle = document.getElementById('breakToggle');
        const breakStatus = document.getElementById('breakStatus');
        const breakTimeDisplay = document.getElementById('breakTimeDisplay');

        breakToggle?.addEventListener('change', function () {
            const active = this.checked;
            
            if (!confirm(active ? 'Are you sure you want to start break?' : 'Are you sure you want to end break?')) {
                this.checked = !active;
                return;
            }

            breakStatus.textContent = active ? 'ON' : 'OFF';
            breakStatus.className = active ? 'text-green-600 font-semibold' : 'text-gray-600';

            fetch('{{ route("toggle.break") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ break_active: active })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (active) {
                        breakTimeDisplay.innerHTML = `Break started at: ${data.break_in}`;
                        alert(data.message);
                    } else {
                        breakTimeDisplay.innerHTML = `Total Break: ${data.break_display}`;
                        alert(data.message);
                    }
                } else {
                    alert(data.error);
                    this.checked = !active;
                    breakStatus.textContent = this.checked ? 'ON' : 'OFF';
                    breakStatus.className = this.checked ? 'text-green-600 font-semibold' : 'text-gray-600';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                this.checked = !active;
                breakStatus.textContent = this.checked ? 'ON' : 'OFF';
                breakStatus.className = this.checked ? 'text-green-600 font-semibold' : 'text-gray-600';
            });
        });

        // Initialize break status
        function initializeBreakStatus() {
            @if($todayAttendance && $todayAttendance->break_in && !$todayAttendance->break_out)
                breakToggle.checked = true;
                breakStatus.textContent = 'ON';
                breakStatus.className = 'text-green-600 font-semibold';
                breakTimeDisplay.innerHTML = `Break started at: {{ $todayAttendance->break_in?->setTimezone('Asia/Kolkata')->format('h:i A') }}`;
            @endif
        }

        // Call initialization when page loads
        document.addEventListener('DOMContentLoaded', initializeBreakStatus);
    </script>

<script>
function formatDate(date) {
    if (!date) return '';
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}/${month}/${year}`;
}

function updateDateRange() {
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const hidden = document.getElementById('dateRangeHidden');
    if (start && end) {
        hidden.value = `${formatDate(start)} - ${formatDate(end)}`;
    } else {
        hidden.value = '';
    }
}

function updateDateRangeAndSubmit() {
    updateDateRange();
    document.getElementById('filterForm').submit();
}

// Update on date changes
document.getElementById('startDate').addEventListener('change', updateDateRange);
document.getElementById('endDate').addEventListener('change', updateDateRange);

// Initial update if values present
updateDateRange();
</script>



</div>
@endsection