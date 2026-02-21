{{-- Updated resources/views/myattendance/my-attendance.blade.php --}}
{{-- Changes: Fully responsive design for mobile, tablet, and desktop --}}

@extends('components.layout')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @if(isset($companyDetailsMissing) && $companyDetailsMissing)
    <!-- Setup Mode: Only show styles and the setup form -->
    <style>
        .shadow-custom { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    </style>
    
    <div id="company-details-modal" class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-building text-indigo-600 text-xl"></i>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Complete Setup
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ $company->name ?? 'Your Company' }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10 border border-gray-100">
                <form id="company-details-form" class="space-y-6">
                    
                    <!-- Location Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"> Office Location </label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <button type="button" id="get-location-btn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <i class="fas fa-map-marker-alt mr-2 mt-0.5"></i> Get Current Location
                            </button>
                        </div>
                        
                        <div class="mt-3 grid grid-cols-2 gap-3">
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm font-bold">Lat</span>
                                </div>
                                <input type="text" id="details_latitude" name="latitude" value="{{ $company->latitude ?? '' }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 py-3 sm:text-sm border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out" placeholder="0.0000" required>
                            </div>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm font-bold">Lng</span>
                                </div>
                                <input type="text" id="details_longitude" name="longitude" value="{{ $company->longitude ?? '' }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 py-3 sm:text-sm border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out" placeholder="0.0000" required>
                            </div>
                        </div>
                        <p id="location-error" class="mt-2 text-sm text-red-600 hidden"></p>
                    </div>

                    <!-- Working Days -->
                    <div>
                        <label for="total_working_days" class="block text-sm font-medium text-gray-700 mb-1"> Total Working Days </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar-day text-gray-400"></i>
                            </div>
                            <input type="number" step="0.5" name="total_working_days" id="total_working_days" value="{{ $company->total_working_days ?? '' }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-3 sm:text-sm border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out" placeholder="e.g. 26" required>
                        </div>
                    </div>

                    <!-- Office Hours -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="office_start_time" class="block text-sm font-medium text-gray-700 mb-1"> Start Time </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <input type="time" name="office_start_time" id="office_start_time" value="{{ $company->office_start_time ?? '' }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-3 sm:text-sm border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out" required>
                            </div>
                        </div>

                        <div>
                            <label for="office_end_time" class="block text-sm font-medium text-gray-700 mb-1"> End Time </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-history text-gray-400"></i>
                                </div>
                                <input type="time" name="office_end_time" id="office_end_time" value="{{ $company->office_end_time ?? '' }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 py-3 sm:text-sm border border-gray-300 rounded-lg shadow-sm transition duration-150 ease-in-out" required>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            Save & Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @else
    <!-- Dashboard Mode: Show standard styles and content -->
    <style>
        @media (max-width: 640px) {
            .container-padding {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .mobile-scroll {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .mobile-text-sm {
                font-size: 0.875rem;
            }

            .mobile-text-xs {
                font-size: 0.75rem;
            }

            .mobile-p-4 {
                padding: 1rem;
            }

            .mobile-mb-4 {
                margin-bottom: 1rem;
            }

            .mobile-stack {
                display: block;
            }

            .mobile-hide {
                display: none;
            }
        }

        @media (min-width: 641px) and (max-width: 1024px) {
            .tablet-text-lg {
                font-size: 1.125rem;
            }

            .tablet-p-5 {
                padding: 1.25rem;
            }

            .tablet-grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .shadow-custom {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .progress-bar {
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background-color: #10b981;
            transition: width 0.3s ease;
        }

        .location-status-valid {
            color: #10b981;
        }

        .location-status-invalid {
            color: #ef4444;
        }

        /* Mobile-first responsive table - REMOVED to avoid duplicate/broken view (using JS cards instead) */


        /* Touch-friendly buttons for mobile */
        .touch-button {
            min-height: 44px;
            min-width: 44px;
        }

        /* Better tap targets for mobile */
        @media (max-width: 640px) {

            button,
            a {
                min-height: 44px;
            }
        }

        /* Modal Styles */
        .confirmation-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .confirmation-modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .confirmation-modal-header {
            padding: 24px 24px 16px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .confirmation-modal-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .confirmation-modal-icon.location {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .confirmation-modal-title {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .confirmation-modal-body {
            padding: 20px 24px;
            color: #6b7280;
            line-height: 1.6;
        }

        .confirmation-modal-footer {
            padding: 16px 24px 24px 24px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .confirmation-modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            min-height: 44px;
        }

        .confirmation-modal-btn.cancel {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .confirmation-modal-btn.cancel:hover {
            background-color: #e5e7eb;
        }

        .confirmation-modal-btn.confirm {
            background-color: #2563eb;
            color: white;
        }

        .confirmation-modal-btn.confirm:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .confirmation-modal-btn:active {
            transform: translateY(0);
        }

        /* Mobile-specific modal adjustments */
        @media (max-width: 640px) {
            .confirmation-modal-content {
                margin: 5% auto;
                width: 95%;
                max-width: 95%;
            }

            .confirmation-modal-header {
                padding: 16px 16px 12px 16px;
            }

            .confirmation-modal-body {
                padding: 16px;
            }

            .confirmation-modal-footer {
                padding: 12px 16px 16px 16px;
                flex-direction: column;
            }

            .confirmation-modal-btn {
                width: 100%;
                text-align: center;
            }
        }

        /* Location Modal */
        @media (max-width: 768px) {
            #location-modal .bg-white {
                margin: 1rem;
                width: calc(100% - 2rem);
                max-height: calc(100vh - 2rem);
                overflow-y: auto;
            }

            #location-modal .h-64 {
                height: 200px;
            }
        }


        /* Add these styles to your existing CSS */

        /* Break timer animations */
        @keyframes pulse-break {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .break-timer-active {
            animation: pulse-break 2s infinite;
        }

        /* Progress bar animation */
        #break-progress-bar {
            transition: width 1s linear, background-color 0.5s ease;
        }

        /* Responsive adjustments for break timer */
        @media (max-width: 640px) {
            #break-timer-display {
                font-size: 1.5rem;
                /* Adjusted for better fit in bottom sheet */
            }

            /* Mobile specific adjustments if needed */
        }

        @media (min-width: 641px) and (max-width: 1024px) {
            #break-timer-display {
                font-size: 3rem;
            }
        }
    </style>

    <div class="max-w-7xl mx-auto py-3 md:py-6 px-3 sm:px-4 lg:px-8 container-padding">
        <!-- Employee Dashboard -->
        <div class="mb-8 md:mb-12">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 md:mb-6 mobile-mb-4">
                <div class="mb-3 md:mb-0">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Employee Attendance</h2>
                    <p class="text-sm md:text-base text-gray-600">By: Employee / My Attendance</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-sm md:text-base text-gray-600" id="current-date">Loading date...</p>
                    <p class="text-sm md:text-base text-gray-800 font-medium">Punch In: <span
                            id="current-punch-time">--:--</span></p>
                </div>
            </div>

            <!-- Employee Profile and Stats Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 mb-4 md:mb-6">
                <!-- Employee Profile Card -->
                <div class="bg-white rounded-xl shadow-custom p-4 md:p-6 md:col-span-1">
                    <div class="flex flex-col items-center text-center">
                        <img class="h-16 w-16 md:h-24 md:w-24 rounded-full mb-3 md:mb-4" src="{{ $profile['avatar'] }}"
                            alt="Employee avatar">
                        <h3 class="text-base md:text-lg font-bold text-gray-800 truncate w-full">{{ $profile['name'] }}
                        </h3>
                        <p class="text-xs md:text-sm text-gray-600 mb-1 md:mb-2 truncate w-full">{{ $profile['role'] }}
                        </p>
                        <p class="text-xs text-gray-500 mb-3 md:mb-4 truncate w-full">{{ $profile['company'] }}</p>

                        <!-- <div class="w-full border-t border-gray-200 pt-3 md:pt-4">
                            <div class="flex justify-between mb-1 md:mb-2">
                                <span class="text-xs md:text-sm text-gray-600">Employee ID:</span>
                                <span class="text-xs md:text-sm font-medium">{{ $profile['employee_id'] }}</span>
                            </div>
                            <div class="flex justify-between mb-1 md:mb-2">
                                <span class="text-xs md:text-sm text-gray-600">Department:</span>
                                <span class="text-xs md:text-sm font-medium">{{ $profile['department'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs md:text-sm text-gray-600">Join Date:</span>
                                <span class="text-xs md:text-sm font-medium">{{ $profile['join_date'] }}</span>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 md:col-span-3">
                    <div class="bg-white rounded-xl shadow-custom p-4 md:p-6">
                        <div class="flex items-center">
                            <div class="rounded-full bg-blue-100 p-2 md:p-3 mr-3 md:mr-4">
                                <i class="fas fa-calendar-check text-blue-600 text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm font-medium text-gray-500">Present Days</p>
                                <h3 class="text-lg md:text-xl font-bold text-gray-800">{{ $presentDays }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-custom p-4 md:p-6">
                        <div class="flex items-center">
                            <div class="rounded-full bg-green-100 p-2 md:p-3 mr-3 md:mr-4">
                                <i class="fas fa-clock text-green-600 text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm font-medium text-gray-500">Total Working Hours</p>
                                <h3 class="text-lg md:text-xl font-bold text-gray-800" id="total-hours">
                                    {{ $totalHours }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-custom p-4 md:p-6">
                        <div class="flex items-center">
                            <div class="rounded-full bg-yellow-100 p-2 md:p-3 mr-3 md:mr-4">
                                <i class="fas fa-utensils text-yellow-600 text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm font-medium text-gray-500">Break Duration</p>
                                <h3 class="text-lg md:text-xl font-bold text-gray-800" id="break-duration">
                                    {{ $todayBreakDuration }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- Active Break Timer Display -->
                    <!-- Compact Break Timer -->
                    <div id="active-break-timer"
                        class="hidden fixed bottom-0 inset-x-0 md:bottom-4 md:right-4 md:left-auto md:w-auto z-50 bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] md:shadow-lg rounded-t-2xl md:rounded-full px-6 py-4 md:px-4 md:py-2 flex flex-row md:items-center justify-between md:justify-start gap-4 md:gap-3 border-t md:border border-yellow-200">

                        <!-- Icon -->
                        <div class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock text-sm"></i>
                        </div>

                        <!-- Time -->
                        <div class="flex flex-col leading-tight">
                            <span class="text-xs text-gray-500">On Break</span>
                            <span id="break-timer-display" class="text-sm font-semibold text-gray-800">
                                00:00
                            </span>
                        </div>

                        <!-- Progress -->
                        <div class="w-16 h-1 bg-yellow-100 rounded-full overflow-hidden">
                            <div id="break-progress-bar" class="h-full bg-yellow-500 transition-all duration-1000"
                                style="width: 0%"></div>
                        </div>

                        <!-- End Button -->
                        <button id="end-break-timer-btn"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-red-500 hover:bg-red-600 text-white">
                            <i class="fas fa-stop text-xs"></i>
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-custom p-4 md:p-6">
                        <div class="flex items-center">
                            <div class="rounded-full bg-purple-100 p-2 md:p-3 mr-3 md:mr-4">
                                <i class="fas fa-business-time text-purple-600 text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm font-medium text-gray-500">Today's Progress</p>
                                <h3 class="text-lg md:text-xl font-bold text-gray-800" id="today-progress">
                                    {{ round($todayProgress) }}%
                                </h3>
                            </div>
                        </div>
                        <div class="mt-2 progress-bar">
                            <div class="progress-fill" id="progress-fill" style="width: {{ $todayProgress }}%"></div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-custom p-4 md:p-6">
                        <div class="flex items-center">
                            <div class="rounded-full bg-red-100 p-2 md:p-3 mr-3 md:mr-4">
                                <i class="fas fa-calendar-times text-red-600 text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm font-medium text-gray-500">Absent Days</p>
                                <h3 class="text-lg md:text-xl font-bold text-gray-800">{{ $absentDays }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-custom p-4 md:p-6">
                        <div class="flex items-center">
                            <div class="rounded-full bg-indigo-100 p-2 md:p-3 mr-3 md:mr-4">
                                <i class="fas fa-percentage text-indigo-600 text-lg md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs md:text-sm font-medium text-gray-500">Attendance %</p>
                                <h3 class="text-lg md:text-xl font-bold text-gray-800">{{ $attendancePercentage }}%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Punch Controls -->
            <div class="bg-white rounded-xl shadow-custom p-4 md:p-6 mb-4 md:mb-6">
                <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3 md:mb-4">Attendance Actions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                    <button id="punch-in-btn"
                        class="touch-button bg-green-500 hover:bg-green-600 text-white font-bold py-3 md:py-4 px-3 md:px-4 rounded-lg flex items-center justify-center {{ $todayRecord && $todayRecord->punch_in ? 'opacity-50 cursor-not-allowed' : 'pulse-animation' }}"
                        {{ $todayRecord && $todayRecord->punch_in ? 'disabled' : '' }}>
                        <i class="fas fa-sign-in-alt mr-2 text-sm md:text-base"></i>
                        <span class="text-sm md:text-base">Punch In</span>
                    </button>
                    <button id="punch-out-btn"
                        class="touch-button bg-red-500 hover:bg-red-600 text-white font-bold py-3 md:py-4 px-3 md:px-4 rounded-lg flex items-center justify-center {{ !$todayRecord || !$todayRecord->punch_in ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ !$todayRecord || !$todayRecord->punch_in ? 'disabled' : '' }}>
                        <i class="fas fa-sign-out-alt mr-2 text-sm md:text-base"></i>
                        <span class="text-sm md:text-base">Punch Out</span>
                    </button>
                    <button id="break-in-btn"
                        class="touch-button bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-lg">
                        <i class="fas fa-pause mr-2"></i> Break In
                    </button>

                    <button id="break-out-btn"
                        class="touch-button bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg">
                        <i class="fas fa-play mr-2"></i> Break Out
                    </button>


                </div>

                <div class="mt-4 md:mt-6">
                    <div class="flex items-center mb-1 md:mb-2">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2 text-sm md:text-base"></i>
                        <span class="text-sm md:text-base font-medium text-gray-700">Current Location:</span>
                        <span id="location-status" class="ml-2 text-xs md:text-sm font-medium"></span>
                    </div>
                    <div id="location-display" class="text-xs md:text-sm text-gray-600 bg-gray-100 p-2 md:p-3 rounded-lg">
                        {{ $todayRecord->location ?? 'Location will be captured when you punch in' }}
                    </div>
                    <div id="distance-display" class="text-xs text-gray-500 mt-1"></div>
                </div>
            </div>

            <!-- Attendance Log Table -->
            <div class="bg-white rounded-xl shadow-custom p-4 md:p-6 mb-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-3 md:mb-4">
                    <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-2 md:mb-0">Attendance Log</h3>
                    <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                        <div class="flex items-center">
                            <span class="text-xs md:text-sm text-gray-600 mr-2">Month:</span>
                            <select class="appearance-none bg-white/80 backdrop-blur-md
                                            border border-gray-200
                                            rounded-xl
                                            w-full md:w-auto
                                            px-4 py-2.5 pr-10
                                            text-gray-900 text-sm md:text-base
                                            shadow-sm
                                            transition-all duration-200
                                            hover:shadow-md hover:border-gray-300
                                            focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500"
                                id="attendance-month-filter">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option class="text-gray-900" value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                                        {{ date('F Y', mktime(0, 0, 0, $m, 1, now()->year)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs md:text-sm text-gray-600 mr-2">Page:</span>
                            <span class="text-xs md:text-sm font-medium">1/2</span>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto mobile-scroll">
                    <!-- Desktop Table -->
                    <table class="min-w-full divide-y divide-gray-200 hidden md:table responsive-table">
                        <thead>
                            <tr>
                                <th
                                    class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Punch In</th>
                                <th
                                    class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Punch Out</th>
                                <th
                                    class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Work Hours</th>
                                <th
                                    class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th>Breaks</th>
                                <th>Total Break</th>

                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="employee-attendance-log">
                            <!-- Rows populated by JS -->
                        </tbody>
                    </table>

                    <!-- Mobile Cards View -->
                    <div class="md:hidden" id="mobile-attendance-cards">
                        <!-- Cards will be populated by JS -->
                    </div>
                </div>
                <div class="mt-3 md:mt-4 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                    <div>
                        <span
                            class="text-xs md:text-sm text-gray-600">{{ date('F 1, Y', strtotime('first day of this month')) }}
                            of service</span>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="touch-button bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-2 md:px-3 rounded text-sm">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button
                            class="touch-button bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 md:px-3 rounded text-sm">
                            1
                        </button>
                        <button
                            class="touch-button bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-2 md:px-3 rounded text-sm">
                            2
                        </button>
                        <button
                            class="touch-button bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-1 px-2 md:px-3 rounded text-sm">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @if(!isset($companyDetailsMissing) || !$companyDetailsMissing)
    <div id="location-modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-2 md:p-4">
        <div class="bg-white rounded-xl shadow-custom p-4 md:p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-3 md:mb-4">
                <h3 class="text-base md:text-lg font-semibold text-gray-800">Location Details</h3>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700 touch-button">
                    <i class="fas fa-times text-lg md:text-xl"></i>
                </button>
            </div>
            <div class="bg-gray-100 h-48 md:h-64 rounded-lg flex items-center justify-center mb-3 md:mb-4">
                <div class="text-center p-4">
                    <i class="fas fa-map-marked-alt text-3xl md:text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500 text-sm md:text-base">GPS Location Map</p>
                    <p id="modal-location-text" class="text-xs md:text-sm text-gray-600 mt-2 break-words"></p>
                    <p id="modal-distance-text" class="text-xs md:text-sm font-medium mt-1"></p>
                </div>
            </div>
            <div class="flex justify-end">
                <button id="close-modal-btn"
                    class="touch-button bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg text-sm md:text-base">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Location Confirmation Modal -->
    <div id="locationConfirmationModal" class="confirmation-modal">
        <div class="confirmation-modal-content">
            <div class="confirmation-modal-header">
                <div class="confirmation-modal-icon location">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="confirmation-modal-title" id="confirmationModalTitle">Location Access Required</h3>
            </div>
            <div class="confirmation-modal-body">
                <p id="confirmationModalMessage">This action requires access to your GPS location to verify your
                    attendance.</p>
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                        <div>
                            <p class="text-sm text-blue-700 font-medium">Why we need your location?</p>
                            <p class="text-xs text-blue-600 mt-1">To ensure you're within the allowed office premises
                                and maintain accurate attendance records.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="confirmation-modal-footer">
                <button type="button" class="confirmation-modal-btn cancel" id="confirmationModalCancel">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="button" class="confirmation-modal-btn confirm" id="confirmationModalConfirm">
                    <i class="fas fa-check mr-2"></i>Allow Location
                </button>
            </div>
        </div>
    </div>

    <div id="breakDetailsModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl w-full max-w-md p-5 shadow-lg">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Break Details</h3>
                <button onclick="closeBreakDetails()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="breakDetailsList" class="space-y-2 max-h-80 overflow-y-auto text-sm text-gray-700">
            </div>
        </div>
    </div>
    @endif
    @endif

    <script>
    @if(isset($companyDetailsMissing) && $companyDetailsMissing)
        // Setup Mode Script
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('company-details-form');
            const locationBtn = document.getElementById('get-location-btn');
            const latInput = document.getElementById('details_latitude');
            const lngInput = document.getElementById('details_longitude');
            const errorMsg = document.getElementById('location-error');

            locationBtn.addEventListener('click', function() {
                if (navigator.geolocation) {
                    locationBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Getting Location...';
                    locationBtn.disabled = true;

                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            latInput.value = position.coords.latitude.toFixed(6);
                            lngInput.value = position.coords.longitude.toFixed(6);
                            errorMsg.classList.add('hidden');
                            locationBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Location Captured';
                            locationBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                            locationBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                            setTimeout(() => {
                                locationBtn.disabled = false;
                            }, 2000);
                        },
                        function(error) {
                            console.error("Error getting location:", error);
                            let msg = "Error getting location.";
                            switch(error.code) {
                                case error.PERMISSION_DENIED: msg = "User denied the request for Geolocation."; break;
                                case error.POSITION_UNAVAILABLE: msg = "Location information is unavailable."; break;
                                case error.TIMEOUT: msg = "The request to get user location timed out."; break;
                            }
                            errorMsg.textContent = msg;
                            errorMsg.classList.remove('hidden');
                            locationBtn.innerHTML = '<i class="fas fa-map-marker-alt mr-2"></i> Retry Location';
                            locationBtn.disabled = false;
                        }
                    );
                } else {
                    errorMsg.textContent = "Geolocation is not supported by this browser.";
                    errorMsg.classList.remove('hidden');
                }
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                
                // Add CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route("my-attendance.update-company-details") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.success) {
                        window.location.reload();
                    } else {
                        alert(result.error || result.message || 'Failed to update details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let message = error.message || error.error || 'An error occurred while saving details';
                    if (error.errors) {
                         message += '\n' + Object.values(error.errors).flat().join('\n');
                    }
                    alert(message);
                });
            });
        });
    @else
        // Dashboard Mode Script
        const BREAK_STORAGE_KEY = `active_break_${{{ auth()->id() }}}`;


        // ──────────────────────────────────────────────────────────────────────────────
        // CONSTANTS & CONFIG
        // ──────────────────────────────────────────────────────────────────────────────
        const OFFICE_LAT = {{ $company->latitude ?? 0 }};
        const OFFICE_LON = {{ $company->longitude ?? 0 }};
        const ALLOWED_DISTANCE_KM = 1;
        const MAX_BREAK_SECONDS = 3600; // 1 hour maximum break time

        // ──────────────────────────────────────────────────────────────────────────────
        // STATE VARIABLES
        // ──────────────────────────────────────────────────────────────────────────────
        let attendanceData = @json($jsAttendanceData);
        const initialAttendanceLog = @json($attendanceLog);

        let workTimer = null;
        let workSeconds = 0;
        let totalWorkSeconds = 0;

        let breakTimerInterval = null;
        let breakStartTime = null;
        let currentBreakSeconds = 0;

        let breakAlertShown = false;


        let punchInTime = attendanceData.punchIn ? new Date(attendanceData.punchIn) : null;
        let lunchStartTime = attendanceData.lunchStart ? new Date(attendanceData.lunchStart) : null;

        // ──────────────────────────────────────────────────────────────────────────────
        // DOM ELEMENTS
        // ──────────────────────────────────────────────────────────────────────────────
        const elements = {
            punchInBtn: document.getElementById('punch-in-btn'),
            punchOutBtn: document.getElementById('punch-out-btn'),
            breakInBtn: document.getElementById('break-in-btn'),
            breakOutBtn: document.getElementById('break-out-btn'),

            locationDisplay: document.getElementById('location-display'),
            locationStatus: document.getElementById('location-status'),
            distanceDisplay: document.getElementById('distance-display'),

            totalHours: document.getElementById('total-hours'),
            breakDurationEl: document.getElementById('break-duration'),
            currentPunchTime: document.getElementById('current-punch-time'),
            currentDate: document.getElementById('current-date'),

            todayProgress: document.getElementById('today-progress'),
            progressFill: document.getElementById('progress-fill'),

            employeeAttendanceLog: document.getElementById('employee-attendance-log'),
            mobileAttendanceCards: document.getElementById('mobile-attendance-cards'),

            locationModal: document.getElementById('location-modal'),
            modalLocationText: document.getElementById('modal-location-text'),
            modalDistanceText: document.getElementById('modal-distance-text'),

            // Break timer specific elements
            activeBreakTimer: document.getElementById('active-break-timer'),
            noActiveBreak: document.getElementById('no-active-break'),
            breakStartTimeEl: document.getElementById('break-start-time'),
            breakTimerDisplay: document.getElementById('break-timer-display'),
            breakProgressBar: document.getElementById('break-progress-bar'),
            breakTimerProgress: document.getElementById('break-timer-progress'),
            endBreakTimerBtn: document.getElementById('end-break-timer-btn'),
        };

        // CSRF Token
        // CSRF Token already declared in header.blade.php
        // const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // ──────────────────────────────────────────────────────────────────────────────
        // INITIALIZATION
        // ──────────────────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            initEventListeners();
            initializeTimersAndUI();
            initializeBreakTimer();

            // Periodic updates
            setInterval(updateCurrentDateTime, 60000);
            setInterval(checkBreakStatus, 30000);

            // Initial UI setup
            updateCurrentDateTime();
            generateEmployeeAttendanceLog(initialAttendanceLog);
            generateMobileAttendanceCards(initialAttendanceLog);
            updateButtonStates();
            handleResize();

            window.addEventListener('resize', handleResize);
        });

        // ──────────────────────────────────────────────────────────────────────────────
        // EVENT LISTENERS
        // ──────────────────────────────────────────────────────────────────────────────
        function initEventListeners() {
            elements.punchInBtn?.addEventListener('click', () => handleAttendanceAction('punchIn', '{{ route("my-attendance.punch-in") }}'));
            elements.punchOutBtn?.addEventListener('click', () => handleAttendanceAction('punchOut', '{{ route("my-attendance.punch-out") }}'));

            elements.breakInBtn?.addEventListener('click', handleBreakIn);
            elements.breakOutBtn?.addEventListener('click', () => handleBreakEndAction('breakOut', '{{ route("my-attendance.break-out") }}'));
            elements.endBreakTimerBtn?.addEventListener('click', () => handleBreakEndAction('breakOut', '{{ route("my-attendance.break-out") }}'));

            document.getElementById('close-modal')?.addEventListener('click', closeLocationModal);
            document.getElementById('close-modal-btn')?.addEventListener('click', closeLocationModal);

            document.getElementById('attendance-month-filter')?.addEventListener('change', filterEmployeeAttendance);
        }

        // ──────────────────────────────────────────────────────────────────────────────
        // BREAK TIMER MANAGEMENT
        // ──────────────────────────────────────────────────────────────────────────────
        function initializeBreakTimer() {
            const storedBreakTime = localStorage.getItem(BREAK_STORAGE_KEY);

            if (attendanceData.breakRunning && storedBreakTime) {
                breakStartTime = new Date(storedBreakTime);

                if (!isNaN(breakStartTime.getTime())) {
                    currentBreakSeconds = Math.floor(
                        (Date.now() - breakStartTime.getTime()) / 1000
                    );

                    showActiveBreakTimer();
                    startBreakTimerDisplay();
                }
            }

            checkBreakStatus();
        }





        async function checkBreakStatus() {
            try {
                const response = await fetch('{{ route("my-attendance.break-status") }}');
                if (!response.ok) throw new Error('Break status check failed');

                const data = await response.json();
                if (data.error) {
                    console.error('Break status error:', data.error);
                    return;
                }

                attendanceData.breakRunning = data.break_running;

                if (data.break_running && data.break_data?.break_in) {

                    // 🔒 SET ONLY ONCE
                    if (!breakStartTime) {
                        breakStartTime = new Date(data.break_data.break_in);

                        if (isNaN(breakStartTime.getTime())) return;

                        currentBreakSeconds = Math.floor(
                            data.break_data.break_duration_seconds || 0
                        );
                    }

                    showActiveBreakTimer();

                    if (!breakTimerInterval && breakStartTime) {
                        startBreakTimerDisplay();
                    }

                }




                updateButtonStates();
            } catch (err) {
                console.error('Failed to check break status:', err);
            }
        }

        function showActiveBreakTimer() {
            if (!elements.activeBreakTimer) return;

            elements.activeBreakTimer.classList.remove('hidden');
            elements.noActiveBreak?.classList.add('hidden');
        }



        function hideActiveBreakTimer() {
            if (!elements.activeBreakTimer) return;

            elements.activeBreakTimer.classList.add('hidden');
            elements.noActiveBreak?.classList.remove('hidden');

            if (breakTimerInterval) {
                clearInterval(breakTimerInterval);
                breakTimerInterval = null;
            }
        }


        function startBreakTimerDisplay() {
            if (breakTimerInterval) return;

            breakTimerInterval = setInterval(() => {
                // 🛑 HARD GUARD
                if (!breakStartTime || isNaN(breakStartTime.getTime())) {
                    return;
                }

                currentBreakSeconds = Math.floor(
                    (Date.now() - breakStartTime.getTime()) / 1000
                );

                updateBreakTimerDisplay();

                if (currentBreakSeconds >= MAX_BREAK_SECONDS && !breakAlertShown) {
                    breakAlertShown = true;
                    alert('⚠️ Maximum break time (1 hour) exceeded! Please end your break.');
                }
            }, 1000);
        }




        function updateBreakTimerDisplay() {
            if (
                !elements.breakTimerDisplay ||
                isNaN(currentBreakSeconds)
            ) return;

            const totalSeconds = Math.max(0, Math.floor(currentBreakSeconds));

            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;

            elements.breakTimerDisplay.textContent =
                `${padZero(minutes)}m ${padZero(seconds)}s`;

            const percent = Math.min(
                (totalSeconds / MAX_BREAK_SECONDS) * 100,
                100
            );

            elements.breakProgressBar.style.width = `${percent}%`;
        }




        // ──────────────────────────────────────────────────────────────────────────────
        // BREAK ACTIONS
        // ──────────────────────────────────────────────────────────────────────────────
        async function handleBreakIn() {
            if (attendanceData.breakRunning) {
                alert('ℹ️ Break is already in progress!');
                location.reload();
                return;
            }

            try {
                const confirmLocation = await showLocationConfirmation('breakIn');
                if (!confirmLocation) return;

                const locationData = await getLocation();
                const postData = createLocationPostData(locationData);

                const result = await ajaxPost('{{ route("my-attendance.break-in") }}', postData);

                if (result.error) {
                    alert(result.error);
                    return;
                }

                // ✅ SET STATE
                // ✅ SET STATE
                attendanceData.breakRunning = true;

                breakStartTime = new Date(result.break_in ?? Date.now());
                localStorage.setItem(BREAK_STORAGE_KEY, breakStartTime.toISOString());


                currentBreakSeconds = 0;
                breakAlertShown = false;

                showActiveBreakTimer();
                startBreakTimerDisplay();
                updateButtonStates();

                // location.reload();


            } catch (err) {
                console.error('Break in failed:', err);
                alert('Break in failed');
            }
        }


        async function handleBreakEndAction(actionType, route) {
            try {
                const confirmLocation = await showLocationConfirmation(actionType);
                if (!confirmLocation) return;

                const locationData = await getLocation();
                showLocationModal(locationData);

                if (!locationData.isWithinRange && !locationData.error) {
                    const proceed = confirm(
                        `📍 You are ${locationData.distance}km away from office (allowed: ${ALLOWED_DISTANCE_KM}km).\n\nDo you want to proceed anyway?`
                    );
                    if (!proceed) return;
                }

                const postData = createLocationPostData(locationData);
                const result = await ajaxPost(route, postData);

                if (result.error) {
                    alert(`❌ Error: ${result.error}`);
                    return;
                }

                // ────────────────────────────────
                // SUCCESS: API call succeeded
                // ────────────────────────────────

                updateLocationDisplay(locationData);

                let message = `✅ Break Ended Successfully!`;
                if (result.break_duration) message += `\nBreak Duration: ${result.break_duration}`;
                if (result.total_break_time) message += `\nTotal Break Today: ${result.total_break_time}`;
                alert(message);

                if (result.total_break_time) {
                    elements.breakDurationEl.textContent = result.total_break_time;
                }

                // ────────────────────────────────
                // FINAL CLEANUP & STATE RESET (ONLY ON SUCCESS)
                // ────────────────────────────────

                // Clear persisted break data from localStorage
                localStorage.removeItem(BREAK_STORAGE_KEY);

                // Stop and clear the break timer interval
                if (breakTimerInterval) {
                    clearInterval(breakTimerInterval);
                    breakTimerInterval = null;
                }

                // Reset all break-related variables
                breakStartTime = null;
                currentBreakSeconds = 0;
                breakAlertShown = false;

                // Update attendance state
                attendanceData.breakRunning = false;

                // Update UI
                hideActiveBreakTimer();
                updateButtonStates();

                // Refresh break history and reload page (or update UI dynamically)
                updateBreakHistory();
                location.reload(); // Consider replacing with dynamic UI updates in production


            } catch (error) {
                console.error('Break end error:', error);
                alert(`❌ Error during break out: ${error.message || error}`);
            }
        }

        // ──────────────────────────────────────────────────────────────────────────────
        // GENERAL ATTENDANCE ACTIONS
        // ──────────────────────────────────────────────────────────────────────────────
        async function handleAttendanceAction(actionType, route) {
            try {
                const confirmLocation = await showLocationConfirmation(actionType);
                if (!confirmLocation) return;

                const locationData = await getLocation();
                showLocationModal(locationData);

                if (!locationData.isWithinRange && !locationData.error) {
                    const proceed = confirm(`📍 Location Alert\n\nYou are ${locationData.distance}km away from office (allowed: ${ALLOWED_DISTANCE_KM}km).\n\nProceed with ${actionType.replace(/([A-Z])/g, ' $1').trim()} anyway?`);
                    if (!proceed) return;
                }

                const postData = createLocationPostData(locationData);
                const result = await ajaxPost(route, postData);

                if (result.error) {
                    alert(`❌ Error: ${result.error}`);
                    return;
                }

                updateLocationDisplay(locationData);


                const actionNames = {
                    'punchIn': 'Punched In',
                    'punchOut': 'Punched Out',
                    'breakIn': 'Break Started',
                    'breakOut': 'Break Ended'
                };

                // Update local state based on action
                if (actionType === 'punchIn') {
                    attendanceData.punchIn = result.punch_time; // Expecting time string or true
                } else if (actionType === 'punchOut') {
                    attendanceData.punchOut = result.punch_time;
                } else if (actionType === 'breakIn') {
                    attendanceData.breakRunning = true;
                    // handleBreakIn handles its own state/timer, but good to sync
                } else if (actionType === 'breakOut') {
                    attendanceData.breakRunning = false;
                }

                updateButtonStates();

                let successMessage = `✅ ${actionNames[actionType]} Successfully!`;
                if (result.work_hours) successMessage += `\nTotal Work Hours: ${result.work_hours}`;
                if (result.break_duration) successMessage += `\nBreak Duration: ${result.break_duration}`;
                if (result.total_break_time) successMessage += `\nTotal Break Today: ${result.total_break_time}`;
                if (locationData.distance) successMessage += `\nDistance: ${locationData.distance}km`;


                // alert(successMessage); // Optional: removing alert if we want smoother UX, or keep it. Keeping for now.
                // Use a non-blocking notification if possible, but alert is consistent with existing code.

                // Reload to refresh table/logs is good practice if we don't dynamically update the table
                location.reload();

            } catch (error) {
                console.error(`${actionType} error:`, error);
                alert(`❌ Error during ${actionType}: ${error.message}`);
            }
        }

        // ──────────────────────────────────────────────────────────────────────────────
        // HELPERS
        // ──────────────────────────────────────────────────────────────────────────────
        function padZero(num) {
            return num.toString().padStart(2, '0');
        }

        function createLocationPostData(locationData) {
            const data = { location: locationData.fullLocation || locationData.message };
            if (locationData.latitude && locationData.longitude) {
                Object.assign(data, {
                    latitude: locationData.latitude,
                    longitude: locationData.longitude,
                    accuracy: locationData.accuracy,
                    distance: locationData.distance,
                    is_within_range: locationData.isWithinRange
                });
            }
            return data;
        }

        function getLocation() {
            return new Promise((resolve) => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const { latitude, longitude, accuracy } = position.coords;
                            const distance = calculateDistance(latitude, longitude, OFFICE_LAT, OFFICE_LON);
                            const isWithinRange = distance <= ALLOWED_DISTANCE_KM;

                            resolve({
                                latitude: latitude.toFixed(6),
                                longitude: longitude.toFixed(6),
                                accuracy: accuracy.toFixed(2),
                                distance: distance.toFixed(2),
                                isWithinRange,
                                location: `Lat: ${latitude.toFixed(6)}, Lng: ${longitude.toFixed(6)}`,
                                fullLocation: `Lat: ${latitude.toFixed(6)}, Lng: ${longitude.toFixed(6)}, Accuracy: ${accuracy.toFixed(2)}m, Distance: ${distance.toFixed(2)}km`
                            });
                        },
                        () => {
                            resolve({
                                error: true,
                                message: 'Location access denied or unavailable',
                                isWithinRange: false
                            });
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                } else {
                    resolve({
                        error: true,
                        message: 'Geolocation not supported',
                        isWithinRange: false
                    });
                }
            });
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function updateLocationDisplay(locationData) {
            if (locationData.error) {
                elements.locationDisplay.textContent = locationData.message;
                elements.locationStatus.textContent = '❌ Location Error';
                elements.locationStatus.className = 'ml-2 text-sm font-medium location-status-invalid';
                elements.distanceDisplay.textContent = '';
                return false;
            }

            elements.locationDisplay.textContent = locationData.location;
            elements.distanceDisplay.textContent = `Distance from office: ${locationData.distance}km | Accuracy: ${locationData.accuracy}m`;

            if (locationData.isWithinRange) {
                elements.locationStatus.textContent = '✅ Within Range';
                elements.locationStatus.className = 'ml-2 text-sm font-medium location-status-valid';
            } else {
                elements.locationStatus.textContent = '❌ Out of Range';
                elements.locationStatus.className = 'ml-2 text-sm font-medium location-status-invalid';
            }

            return locationData.isWithinRange;
        }

        function showLocationConfirmation(actionType) {
            return new Promise((resolve) => {
                const actionNames = {
                    punchIn: 'Punch In',
                    punchOut: 'Punch Out',
                    breakIn: 'Break Start',
                    breakOut: 'Break End'
                };

                const modal = document.getElementById('locationConfirmationModal');
                if (!modal) {
                    resolve(true); // fallback if no confirmation modal
                    return;
                }

                const title = document.getElementById('confirmationModalTitle');
                const message = document.getElementById('confirmationModalMessage');
                const confirmBtn = document.getElementById('confirmationModalConfirm');
                const cancelBtn = document.getElementById('confirmationModalCancel');

                title.textContent = `📍 ${actionNames[actionType]} - Location Access`;
                message.textContent = `This action requires access to your GPS location to verify you're within office premises.`;

                modal.style.display = 'block';

                const handleConfirm = () => { cleanup(); resolve(true); };
                const handleCancel = () => { cleanup(); resolve(false); };
                const handleEscape = (e) => { if (e.key === 'Escape') handleCancel(); };
                const handleOutside = (e) => { if (e.target === modal) handleCancel(); };

                const cleanup = () => {
                    modal.style.display = 'none';
                    confirmBtn.removeEventListener('click', handleConfirm);
                    cancelBtn.removeEventListener('click', handleCancel);
                    document.removeEventListener('keydown', handleEscape);
                    modal.removeEventListener('click', handleOutside);
                };

                confirmBtn.addEventListener('click', handleConfirm);
                cancelBtn.addEventListener('click', handleCancel);
                document.addEventListener('keydown', handleEscape);
                modal.addEventListener('click', handleOutside);

                confirmBtn.focus();
            });
        }

        function showLocationModal(locationData) {
            if (locationData.error) {
                elements.modalLocationText.textContent = locationData.message;
                elements.modalDistanceText.textContent = '';
            } else {
                elements.modalLocationText.textContent = locationData.fullLocation;
                elements.modalDistanceText.textContent = `Distance from office: ${locationData.distance}km ${locationData.isWithinRange ? '✅' : '❌'}`;
                elements.modalDistanceText.className = locationData.isWithinRange ? 'location-status-valid' : 'location-status-invalid';
            }
            elements.locationModal?.classList.remove('hidden');
        }

        function closeLocationModal() {
            elements.locationModal?.classList.add('hidden');
        }

       function updateCurrentDateTime() {
    const now = new Date(
        new Date().toLocaleString('en-US', { timeZone: 'Asia/Kolkata' })
    );

    const yyyy = now.getFullYear();
    const mm = String(now.getMonth() + 1).padStart(2, '0');
    const dd = String(now.getDate()).padStart(2, '0');

    elements.currentDate.textContent = `${dd}-${mm}-${yyyy}`;
}


        async function ajaxPost(route, data = {}) {
            const response = await fetch(route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(data),
            });

            if (!response.ok) {
                let errorMessage = `HTTP error! status: ${response.status}`;
                try {
                    const errorData = await response.json();
                    if (errorData.error) {
                        errorMessage = errorData.error;
                    }
                } catch (e) {
                    console.error('Error parsing error response:', e);
                }
                throw new Error(errorMessage);
            }
            return await response.json();
        }

        function updateButtonStates() {
            const isBreakActive = attendanceData.breakRunning === true;

            // Punch In
            elements.punchInBtn.disabled = !!attendanceData.punchIn;
            if (attendanceData.punchIn) {
                elements.punchInBtn.title = 'You have already punched in today';
            }

            // Punch Out
            elements.punchOutBtn.disabled = !attendanceData.punchIn || !!attendanceData.punchOut || isBreakActive;
            if (attendanceData.punchOut) {
                elements.punchOutBtn.title = 'You have already punched out today';
            } else if (isBreakActive) {
                elements.punchOutBtn.title = 'Cannot punch out during break';
            } else if (!attendanceData.punchIn) {
                elements.punchOutBtn.title = 'Please punch in first';
            }

            // Break In
            elements.breakInBtn.disabled = !attendanceData.punchIn || isBreakActive || !!attendanceData.punchOut;
            if (isBreakActive) {
                elements.breakInBtn.title = 'Break already in progress';
            } else if (!attendanceData.punchIn) {
                elements.breakInBtn.title = 'Please punch in first';
            } else if (attendanceData.punchOut) {
                elements.breakInBtn.title = 'Cannot start break after punch out';
            }

            // Break Out
            elements.breakOutBtn.disabled = !isBreakActive || !!attendanceData.punchOut;
            if (!isBreakActive) {
                elements.breakOutBtn.title = 'No active break to end';
            } else if (attendanceData.punchOut) {
                elements.breakOutBtn.title = 'Cannot end break after punch out';
            }
        }

        // ──────────────────────────────────────────────────────────────────────────────
        // UI GENERATION
        // ──────────────────────────────────────────────────────────────────────────────
        function generateEmployeeAttendanceLog(data) {
            if (!elements.employeeAttendanceLog) return;
            elements.employeeAttendanceLog.innerHTML = '';

            data.forEach((record) => {
                const row = document.createElement('tr');
                const statusClass = record.status === 'Present' ? 'bg-green-100 text-green-800' :
                    record.status === 'Late' ? 'bg-yellow-100 text-yellow-800' :
                        record.status === 'Half Day' ? 'bg-blue-100 text-blue-800' :
                            'bg-red-100 text-red-800';

                row.innerHTML = `
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.date}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.punchIn || '--'}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.punchOut || '--'}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${record.workHours || '--'}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                        ${record.status}
                                    </span>
                                </td>
                              <td class="px-4 py-3 text-sm text-gray-700">
                        ${record.breaks?.length ? `
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">
                                    ${record.breaks.length} breaks
                                </span>

                                <button
                                    class="text-blue-600 text-xs underline hover:text-blue-800"
                                    onclick="showBreakDetails(${JSON.stringify(record.breaks).replace(/"/g, '&quot;')})">
                                    View
                                </button>
                            </div>
                        ` : '--'}
                    </td>

                                <td class="px-4 py-3 text-sm font-medium">
                                    ${record.totalBreak || '--'}
                                </td>
                            `;
                elements.employeeAttendanceLog.appendChild(row);
            });
        }

        function generateMobileAttendanceCards(data) {
            if (!elements.mobileAttendanceCards) return;
            elements.mobileAttendanceCards.innerHTML = '';

            data.forEach((record) => {
                const statusClass = record.status === 'Present' ? 'bg-green-100 text-green-800' :
                    record.status === 'Late' ? 'bg-yellow-100 text-yellow-800' :
                        record.status === 'Half Day' ? 'bg-blue-100 text-blue-800' :
                            'bg-red-100 text-red-800';

                const breaksHtml = record.breaks?.length
                    ? record.breaks.map(b => `<li>${b}</li>`).join('')
                    : '<li>--</li>';

                const card = document.createElement('div');
                card.className = 'mb-4 p-4 border border-gray-200 rounded-xl bg-white shadow-sm';

                card.innerHTML = `
                                <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-100">
                                    <div>
                                        <span class="font-medium text-gray-800">${record.date}</span>
                                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                            ${record.status}
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Punch In:</span>
                                        <span class="font-medium">${record.punchIn || '--'}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Punch Out:</span>
                                        <span class="font-medium">${record.punchOut || '--'}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Lunch In:</span>
                                        <span class="font-medium">${record.lunchIn || '--'}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Lunch Out:</span>
                                        <span class="font-medium">${record.lunchOut || '--'}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Work Hours:</span>
                                        <span class="font-medium">${record.workHours || '--'}</span>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-gray-100 text-sm">
                                    <p class="font-semibold text-gray-700 mb-1">Breaks:</p>
                                    <ul class="list-disc pl-5 text-gray-600 space-y-0.5">
                                        ${breaksHtml}
                                    </ul>
                                    <p class="mt-2 font-medium text-gray-700">
                                        <span class="font-semibold">Total Break:</span> 
                                        ${record.totalBreak || record.breakHours || '--'}
                                    </p>
                                </div>
                            `;

                elements.mobileAttendanceCards.appendChild(card);
            });
        }

        function handleResize() {
            const isMobile = window.innerWidth < 768;
            const table = document.querySelector('.responsive-table');
            const cards = elements.mobileAttendanceCards;

            if (isMobile) {
                table?.classList.add('hidden');
                cards?.classList.remove('hidden');
            } else {
                table?.classList.remove('hidden');
                cards?.classList.add('hidden');
            }
        }

        async function filterEmployeeAttendance() {
            const month = parseInt(document.getElementById('attendance-month-filter').value, 10);
            const year = {{ now()->year }};

            try {
                const response = await fetch(`{{ route("my-attendance.log") }}?month=${month}&year=${year}`);
                const logData = await response.json();
                generateEmployeeAttendanceLog(logData);
                generateMobileAttendanceCards(logData);
            } catch (error) {
                console.error('Error fetching attendance log:', error);
                alert('Error loading attendance data');
            }
        }

        async function updateBreakHistory() {
            try {
                const today = new Date().toISOString().split('T')[0];
                const response = await fetch(`{{ route("my-attendance.log") }}?month=${new Date().getMonth() + 1}&year=${new Date().getFullYear()}`);
                const logData = await response.json();

                const todayRecord = logData.find(record => {
                    const recordDate = record.date.split('-').reverse().join('-');
                    return recordDate === today;
                });

                const breakHistoryTable = document.getElementById('break-history-table');
                const noBreaksMessage = document.getElementById('no-breaks-message');

                if (todayRecord && todayRecord.breaks?.length > 0) {
                    breakHistoryTable.innerHTML = '';
                    noBreaksMessage.style.display = 'none';

                    let totalBreakSeconds = 0;
                    let breakCount = 0;

                    todayRecord.breaks.forEach((breakItem) => {
                        const [breakIn, breakOut] = breakItem.split(' - ');
                        const isActive = breakOut === 'Running';

                        let duration = '--';
                        if (!isActive && breakIn && breakOut) {
                            const inTime = parseTime(breakIn);
                            const outTime = parseTime(breakOut);
                            if (inTime && outTime) {
                                const diffMs = outTime - inTime;
                                const diffSec = Math.floor(diffMs / 1000);
                                const hours = Math.floor(diffSec / 3600);
                                const minutes = Math.floor((diffSec % 3600) / 60);
                                duration = `${padZero(hours)}:${padZero(minutes)}`;
                                totalBreakSeconds += diffSec;
                            }
                        }

                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50';
                        row.innerHTML = `
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${breakIn || '--'}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${breakOut || 'Running'}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium ${isActive ? 'text-yellow-600' : 'text-gray-700'}">
                                            ${duration}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${isActive ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">
                                                ${isActive ? 'Active' : 'Completed'}
                                            </span>
                                        </td>
                                    `;
                        breakHistoryTable.appendChild(row);
                        breakCount++;
                    });

                    document.getElementById('today-break-count').textContent = breakCount;
                    const totalH = Math.floor(totalBreakSeconds / 3600);
                    const totalM = Math.floor((totalBreakSeconds % 3600) / 60);
                    document.getElementById('today-total-break').textContent = `${padZero(totalH)}:${padZero(totalM)}`;
                } else {
                    breakHistoryTable.innerHTML = '';
                    noBreaksMessage.style.display = 'block';
                    document.getElementById('today-break-count').textContent = '0';
                    document.getElementById('today-total-break').textContent = '00:00';
                }
            } catch (error) {
                console.error('Error updating break history:', error);
            }
        }

        function parseTime(timeStr) {
            if (!timeStr || timeStr === '--' || timeStr === 'Running') return null;

            try {
                const [time, modifier] = timeStr.split(' ');
                let [hours, minutes] = time.split(':');

                if (modifier === 'PM' && hours !== '12') hours = parseInt(hours) + 12;
                if (modifier === 'AM' && hours === '12') hours = '00';

                const date = new Date();
                date.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                return date;
            } catch (e) {
                console.error('Error parsing time:', e);
                return null;
            }
        }

        // Optional: Keep these if you still use work timer display
        function startWorkTimer() {
            if (workTimer) return;
            workTimer = setInterval(() => {
                workSeconds++;
                // You can update work time display here if needed
            }, 1000);
        }

        function initializeTimersAndUI() {
            if (attendanceData.punchIn) {
                // punchIn is pre-formatted as "h:i A" (e.g. "09:30 AM") by the controller
                elements.currentPunchTime.textContent = attendanceData.punchIn;

                if (!attendanceData.punchOut) {
                    startWorkTimer();
                }
            }
        }




        function showBreakDetails(breaks) {
            const modal = document.getElementById('breakDetailsModal');
            const list = document.getElementById('breakDetailsList');

            list.innerHTML = '';

            breaks.forEach((b, index) => {
                const isRunning = b.includes('Running');

                list.innerHTML += `
                                <div class="flex justify-between items-center p-2 rounded-lg border ${isRunning ? 'bg-yellow-50' : 'bg-gray-50'}">
                                    <span>${index + 1}. ${b}</span>
                                    <span class="text-xs font-medium ${isRunning ? 'text-yellow-600' : 'text-green-600'}">
                                        ${isRunning ? 'Active' : 'Done'}
                                    </span>
                                </div>
                            `;
            });

            modal.classList.remove('hidden');
        }

        function closeBreakDetails() {
            document.getElementById('breakDetailsModal').classList.add('hidden');
        }

        // Company Details Modal Logic
        const companyDetailsForm = document.getElementById('company-details-form');
        const getLocationBtn = document.getElementById('get-location-btn');

        if (companyDetailsForm) {
            getLocationBtn.addEventListener('click', () => {
                if (navigator.geolocation) {
                    getLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Fetching...';
                    getLocationBtn.disabled = true;
                    
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            document.getElementById('details_latitude').value = position.coords.latitude.toFixed(6);
                            document.getElementById('details_longitude').value = position.coords.longitude.toFixed(6);
                            getLocationBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Location Set';
                            getLocationBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                            getLocationBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                            document.getElementById('location-error').classList.add('hidden');
                        },
                        (error) => {
                            console.error('Error getting location:', error);
                            document.getElementById('location-error').textContent = 'Could not get location. Please allow location access.';
                            document.getElementById('location-error').classList.remove('hidden');
                            getLocationBtn.innerHTML = '<i class="fas fa-map-marker-alt mr-2"></i> Retry Location';
                            getLocationBtn.disabled = false;
                        },
                        { enableHighAccuracy: true }
                    );
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });

            companyDetailsForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(companyDetailsForm);
                const data = Object.fromEntries(formData.entries());
                
                // Basic validation
                if (!data.latitude || !data.longitude) {
                    alert('Please set your location first.');
                    return;
                }

                try {
                    const response = await fetch('{{ route("my-attendance.update-company-details") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Company details updated successfully!');
                        location.reload();
                    } else {
                        alert(result.error || 'Failed to update details.');
                    }
                } catch (error) {
                    console.error('Error updating details:', error);
                    alert('An error occurred. Please try again.');
                }
            });
        }

    @endif
    </script>
@endsection