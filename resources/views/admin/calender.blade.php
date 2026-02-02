@extends('components.layout')
@section('content')

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#4f46e5',
                    secondary: '#64748b',
                    dark: '#0f172a'
                }
            }
        }
    }
</script>
<style>
.calendar-day {
    position: relative;
}
</style>

<div class="max-w-7xl mx-auto py-2 px-2 md:py-6 md:px-4 sm:px-6 lg:px-8">
    <!-- Admin Dashboard -->
    <div class="mb-12">

        <div class="container mx-auto px-2 py-2">
            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Leave Calendar</h2>
                    <p class="text-gray-600 mt-1">Manage holidays and team leaves in one place</p>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <a href="{{ route('employeeportal.index') }}" class="bg-indigo-600 text-white text-xs py-2 px-4 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-300 shadow-sm hover:shadow-md flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Apply for Leave
                    </a>
                    <button id="addHolidayBtn1" class="bg-white text-gray-700 py-2 px-4 rounded-lg text-xs font-medium border border-gray-300 hover:bg-gray-50 transition-all duration-300 shadow-sm hover:shadow-md flex items-center">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Add Holiday
                    </button>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Calendar Section -->
                <div class="bg-white rounded-xl shadow-sm px-2 py-2 flex-1">
                    <!-- Calendar Header -->
                    <div class="flex items-center justify-between mb-6">
                        <button id="prevMonth" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h3 id="currentMonth" class="text-xl font-bold text-gray-800">Loading...</h3>
                        <button id="nextMonth" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <!-- Weekday Headers -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Sun</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Mon</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Tue</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Wed</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Thu</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Fri</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Sat</div>
                    </div>

                    <!-- Calendar Grid -->
                    <div id="calendarGrid" class="grid grid-cols-7 gap-1">
                        <div class="col-span-7 text-center py-8 text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Loading calendar...
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-red-500 mr-2"></div>
                                <span class="text-sm text-gray-600">Holiday</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-sm text-gray-600">Approved Leave</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                                <span class="text-sm text-gray-600">Pending Leave</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-gray-500 mr-2"></div>
                                <span class="text-sm text-gray-600">Rejected Leave</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Side Panel -->
                <div class="bg-white rounded-xl shadow-sm p-6 w-full lg:w-80">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 today-title">
                        Today's Overview
                    </h3>
                    
                    <!-- Today's Leaves -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-user-clock text-indigo-500 mr-2"></i>
                            Today's Leaves
                        </h4>
                        <div id="todayLeaves" class="space-y-2">
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Loading...
                            </div>
                        </div>
                        <p id="noLeavesToday" class="text-sm text-gray-500 text-center py-2 hidden">No leaves today</p>
                    </div>

                    <!-- Upcoming Holidays -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-gift text-red-500 mr-2"></i>
                            Upcoming Holidays
                        </h4>
                        <div id="upcomingHolidays" class="space-y-2">
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Loading...
                            </div>
                        </div>
                    </div>

                    <!-- Pending Leave Requests -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i>
                            Pending Requests
                        </h4>
                        <div id="pendingRequests" class="space-y-2">
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Loading...
                            </div>
                        </div>
                        <p id="noPendingRequests" class="text-sm text-gray-500 text-center py-2 hidden">No pending requests</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Apply Leave Modal -->
        <div id="applyLeaveModal" class="fixed inset-0 z-50 flex items-center justify-center modal-overlay hidden">
            <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 fade-in">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Apply for Leave</h2>
                    <button id="closeLeaveModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="leaveForm" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label for="leaveType" class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                        <select id="leaveType" name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" required>
                            <option value="">Select Leave Type</option>
                            <option value="Vacation">Vacation</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Personal Day">Personal Day</option>
                            <option value="Maternity/Paternity">Maternity/Paternity</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="fromDate" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" id="fromDate" name="from_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" required>
                        </div>
                        <div>
                            <label for="toDate" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" id="toDate" name="to_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="leaveDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="leaveDescription" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" placeholder="Reason for leave" required></textarea>
                    </div>
                    
                    <div class="flex space-x-4 pt-4">
                        <button type="button" id="cancelLeave" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition-all duration-300">
                            Cancel
                        </button>
                        <button type="submit" id="submitLeave" class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-300 shadow-sm hover:shadow-md flex items-center justify-center">
                            <i class="fas fa-spinner fa-spin hidden mr-2"></i>
                            Apply for Leave
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Holiday Modal -->
        <div id="addHolidayModal" class="fixed inset-0 z-50 flex items-center justify-center modal-overlay hidden bg-gray-800 bg-opacity-50">
            <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 fade-in">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Add Holiday</h2>
                    <button id="closeHolidayModal" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="holidayForm" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label for="holidayTitle" class="block text-sm font-medium text-gray-700 mb-1">Holiday Title</label>
                        <input type="text" id="holidayTitle" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" placeholder="Enter holiday name" required>
                    </div>
                    
                    <div>
                        <label for="holidayDate" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" id="holidayDate" name="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" required>
                    </div>
                    
                    <div>
                        <label for="holidayCategory" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select id="holidayCategory" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" required>
                            <option value="">Select Category</option>
                            <option value="Public Holiday">Public Holiday</option>
                            <option value="Company Holiday">Company Holiday</option>
                            <option value="Observance">Observance</option>
                        </select>
                    </div>

                    <div>
                        <label for="holidayDescription" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                        <textarea id="holidayDescription" name="description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" placeholder="Holiday description"></textarea>
                    </div>
                    
                    <div class="flex space-x-4 pt-4">
                        <button type="button" id="cancelHoliday" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition-all duration-300">
                            Cancel
                        </button>
                        <button type="submit" id="submitHoliday" class="flex-1 bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 transition-all duration-300 shadow-sm hover:shadow-md flex items-center justify-center">
                            <i class="fas fa-spinner fa-spin hidden mr-2"></i>
                            Save Holiday
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function parseDate(dateStr) {
    const [year, month, day] = dateStr.split('-').map(Number);
    return new Date(year, month - 1, day); 
}

    // Debug flag
    const DEBUG = true;

    function log(message) {
        if (DEBUG) {
            console.log(message);
        }
    }

    // Calendar data
    let currentDate = new Date();
    let holidays = [];
    let leaves = [];

    // DOM Elements
    const calendarGrid = document.getElementById('calendarGrid');
    const currentMonthElement = document.getElementById('currentMonth');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    
    const applyLeaveBtn = document.getElementById('applyLeaveBtn');
    const addHolidayBtn1 = document.getElementById('addHolidayBtn1');
    
    const applyLeaveModal = document.getElementById('applyLeaveModal');
    const addHolidayModal = document.getElementById('addHolidayModal');
    
    const closeLeaveModal = document.getElementById('closeLeaveModal');
    const closeHolidayModal = document.getElementById('closeHolidayModal');
    
    const cancelLeave = document.getElementById('cancelLeave');
    const cancelHoliday = document.getElementById('cancelHoliday');
    
    const leaveForm = document.getElementById('leaveForm');
    const holidayForm = document.getElementById('holidayForm');
    
    const todayLeaves = document.getElementById('todayLeaves');
    const noLeavesToday = document.getElementById('noLeavesToday');
    const upcomingHolidays = document.getElementById('upcomingHolidays');
    const pendingRequests = document.getElementById('pendingRequests');
    const noPendingRequests = document.getElementById('noPendingRequests');

    // CSRF Token for Ajax
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Initialize calendar
    function initCalendar() {
        log('Initializing calendar...');
        
        loadCalendarData();
        
        // Set up event listeners for navigation
        prevMonthBtn.addEventListener('click', () => {
            currentDate.setDate(1); // Important fix
            currentDate.setMonth(currentDate.getMonth() - 1);
            loadCalendarData();
        });

        nextMonthBtn.addEventListener('click', () => {
            currentDate.setDate(1); // Important fix
            currentDate.setMonth(currentDate.getMonth() + 1);
            loadCalendarData();
        });

        // Modal open events
        if (applyLeaveBtn) {
            applyLeaveBtn.addEventListener('click', (e) => {
                e.preventDefault();
                log('Apply leave button clicked');
                openModal(applyLeaveModal);
            });
        }
        
        if (addHolidayBtn1) {
            addHolidayBtn1.addEventListener('click', () => {
                log('Add holiday button clicked');
                openModal(addHolidayModal);
            });
        }
        
        // Modal close events
        if (closeLeaveModal) {
            closeLeaveModal.addEventListener('click', () => {
                log('Close leave modal clicked');
                closeModal(applyLeaveModal);
            });
        }
        
        if (closeHolidayModal) {
            closeHolidayModal.addEventListener('click', () => {
                log('Close holiday modal clicked');
                closeModal(addHolidayModal);
            });
        }
        
        if (cancelLeave) {
            cancelLeave.addEventListener('click', () => {
                log('Cancel leave clicked');
                closeModal(applyLeaveModal);
            });
        }
        
        if (cancelHoliday) {
            cancelHoliday.addEventListener('click', () => {
                log('Cancel holiday clicked');
                closeModal(addHolidayModal);
            });
        }
        
        // Form submissions
        if (leaveForm) {
            leaveForm.addEventListener('submit', handleLeaveSubmit);
        }
        if (holidayForm) {
            holidayForm.addEventListener('submit', handleHolidaySubmit);
        }

        // Set minimum dates to today
        const today = new Date().toISOString().split('T')[0];
        const fromDateInput = document.getElementById('fromDate');
        const toDateInput = document.getElementById('toDate');
        const holidayDateInput = document.getElementById('holidayDate');
        if (fromDateInput) fromDateInput.min = today;
        if (toDateInput) toDateInput.min = today;
        if (holidayDateInput) holidayDateInput.min = today;

        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target === applyLeaveModal) {
                closeModal(applyLeaveModal);
            }
            if (e.target === addHolidayModal) {
                closeModal(addHolidayModal);
            }
        });

        log('Calendar initialization complete');
    }

    // Load calendar data via Ajax
    function loadCalendarData() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth() + 1;
        
        log(`Loading calendar data for ${year}-${month}`);
        
        // Show loading state
        if (calendarGrid) {
            calendarGrid.innerHTML = '<div class="col-span-7 text-center py-8 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading calendar...</div>';
        }
        
        fetch(`/calendar/data?year=${year}&month=${month}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            log('Calendar data loaded successfully:', data);
            holidays = data.holidays || [];
            leaves = data.leaves || [];
            renderCalendar();
            updateSidePanel(data);
        })
        .catch(error => {
            console.error('Error loading calendar data:', error);
            if (calendarGrid) {
                calendarGrid.innerHTML = '<div class="col-span-7 text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle mr-2"></i>Error loading calendar data</div>';
            }
        });
    }

  let activeDayElement = null; // keep selected day globally

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    // Update month header
    if (currentMonthElement) {
        currentMonthElement.textContent = new Date(year, month).toLocaleDateString(
            'en-US',
            { month: 'long', year: 'numeric' }
        );
    }

    // Clear previous calendar
    calendarGrid.innerHTML = '';

    // Get first & last day info
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDay = firstDay.getDay();

    // Today info
    const today = new Date();
    const todayFormatted = today.toISOString().split('T')[0];

    // Empty cells before first day
    for (let i = 0; i < startingDay; i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'h-12 md:h-24 bg-gray-50 rounded-lg';
        calendarGrid.appendChild(emptyDay);
    }

    // Render days
    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('div');
        dayElement.className =
            'calendar-day h-12 md:h-24 p-2 border border-gray-200 rounded-lg bg-white flex flex-col cursor-pointer';

        const dateFormatted = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const date = new Date(year, month, day);
        const isWeekend = date.getDay() === 0 || date.getDay() === 6;
        const isToday = dateFormatted === todayFormatted;

        if (isWeekend) {
            dayElement.classList.add('bg-gray-50');
        }

        if (isToday) {
            dayElement.classList.add('border-2', 'border-indigo-500', 'bg-indigo-50');
        }

        // Day number
        const dayNumber = document.createElement('div');
        dayNumber.className = `text-sm font-medium ${isToday ? 'text-indigo-600' : 'text-gray-700'}`;
        dayNumber.textContent = day;
        dayElement.appendChild(dayNumber);

        // Events container
        const eventsContainer = document.createElement('div');
        eventsContainer.className = 'flex-1 overflow-y-auto mt-1 space-y-1';
        dayElement.appendChild(eventsContainer);

        // Holidays
        const dayHolidays = holidays.filter(h => h.date === dateFormatted);
        if (dayHolidays.length) {
            const icon = document.createElement('div');
            icon.innerHTML =
                `<i class="fa-solid fa-champagne-glasses text-red-500 text-xs absolute top-1 right-1"></i>`;
            dayElement.appendChild(icon);
        }

        dayHolidays.forEach(h => {
            const badge = document.createElement('div');
            badge.className = 'text-xs bg-red-100 text-red-800 px-1 py-0.5 rounded truncate';
            badge.textContent = h.title;
            eventsContainer.appendChild(badge);
        });

        // Leaves
        const dayLeaves = leaves.filter(l => {
            const from = parseDate(l.fromDate);
            const to = parseDate(l.toDate);
            const current = parseDate(dateFormatted);
            return current >= from && current <= to;
        });

        dayLeaves.forEach(l => {
            const badge = document.createElement('div');
            const color =
                l.status === 'approved'
                    ? 'bg-green-100 text-green-800'
                    : l.status === 'rejected'
                    ? 'bg-gray-100 text-gray-800'
                    : 'bg-yellow-100 text-yellow-800';

            badge.className = `text-xs ${color} px-1 py-0.5 rounded truncate`;
            badge.textContent = `${l.employee.split(' ')[0]} - ${l.type}`;
            eventsContainer.appendChild(badge);
        });

        // CLICK HANDLER (IMPORTANT PART)
        dayElement.addEventListener('click', () => {
            showDateDetails(dateFormatted);

            // Remove previous selection
            if (activeDayElement && activeDayElement !== dayElement) {
                activeDayElement.classList.remove(
                    'border-2',
                    'border-green-400',
                    'scale-105',
                    'bg-green-50'
                );
            }

            // Remove today's blue border when selected
            // dayElement.classList.remove('border-indigo-500');

            // Add active styles
            dayElement.classList.add(
                'border-2',
                'border-green-400',
                'transition-all',
                'duration-300',
                'ease-in-out',
                'transform',
                'scale-105',
                'bg-green-50'
            );

            activeDayElement = dayElement;
        });

        calendarGrid.appendChild(dayElement);
    }
}


    // Update side panel
    function updateSidePanel(data) {
        log('Updating side panel with data:', data);
        
        // Today's leaves
        if (todayLeaves) {
            todayLeaves.innerHTML = '';
            if (data.todayLeaves && data.todayLeaves.length > 0) {
                if (noLeavesToday) noLeavesToday.classList.add('hidden');
                data.todayLeaves.forEach(leave => {
                    const leaveItem = document.createElement('div');
                    leaveItem.className = 'flex items-center justify-between bg-gray-50 p-2 rounded';
                    leaveItem.innerHTML = `
                        <div>
                            <div class="text-sm font-medium text-gray-800">${leave.user.name}</div>
                            <div class="text-xs text-gray-500">${leave.type}</div>
                        </div>
                        <span class="text-xs ${leave.status === 'approved' ? 'bg-green-100 text-green-800' : 
                                        leave.status === 'rejected' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800'} px-2 py-1 rounded-full">
                            ${leave.status}
                        </span>
                    `;
                    todayLeaves.appendChild(leaveItem);
                });
            } else {
                if (noLeavesToday) noLeavesToday.classList.remove('hidden');
            }
        }
        
        // Upcoming holidays
        if (upcomingHolidays) {
            upcomingHolidays.innerHTML = '';
            if (data.upcomingHolidays && data.upcomingHolidays.length > 0) {
                data.upcomingHolidays.forEach(holiday => {
                    const holidayItem = document.createElement('div');
                    holidayItem.className = 'flex items-center justify-between bg-gray-50 p-2 rounded';
                    const holidayDate = new Date(holiday.date);
                    holidayItem.innerHTML = `
                        <div>
                            <div class="text-sm font-medium text-gray-800">${holiday.title}</div>
                            <div class="text-xs text-gray-500">${holidayDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}</div>
                        </div>
                        <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">
                            ${holiday.category}
                        </span>
                    `;
                    upcomingHolidays.appendChild(holidayItem);
                });
            } else {
                upcomingHolidays.innerHTML = '<div class="text-center py-2 text-gray-500 text-sm">No upcoming holidays</div>';
            }
        }
        
        // Pending requests
        if (pendingRequests) {
            pendingRequests.innerHTML = '';
            if (data.pendingRequests && data.pendingRequests.length > 0) {
                if (noPendingRequests) noPendingRequests.classList.add('hidden');
                data.pendingRequests.forEach(leave => {
                    const requestItem = document.createElement('div');
                    requestItem.className = 'flex items-center justify-between bg-gray-50 p-2 rounded';
                    const fromDate = new Date(leave.from_date);
                    const toDate = new Date(leave.to_date);
                    requestItem.innerHTML = `
                        <div>
                            <div class="text-sm font-medium text-gray-800">${leave.user.name}</div>
                            <div class="text-xs text-gray-500">${fromDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${toDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}</div>
                        </div>
                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                            Pending
                        </span>
                    `;
                    pendingRequests.appendChild(requestItem);
                });
            } else {
                if (noPendingRequests) noPendingRequests.classList.remove('hidden');
            }
        }
    }

    // Modal functions
    function openModal(modal) {
        log(`Opening modal: ${modal.id}`);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modal) {
        log(`Closing modal: ${modal.id}`);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // Form handlers
    function handleLeaveSubmit(e) {
        e.preventDefault();
        log('Leave form submitted');
        
        const submitBtn = document.getElementById('submitLeave');
        const spinner = submitBtn ? submitBtn.querySelector('.fa-spinner') : null;
        
        // Show loading state
        if (submitBtn) submitBtn.disabled = true;
        if (spinner) spinner.classList.remove('hidden');
        
        const formData = new FormData(leaveForm);
        
        // Validate form
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;
        
        if (new Date(toDate) < new Date(fromDate)) {
            alert('To date cannot be before from date');
            if (submitBtn) submitBtn.disabled = false;
            if (spinner) spinner.classList.add('hidden');
            return;
        }
        
        fetch('/calendar/apply-leave', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            log('Leave submission response:', data);
            if (data.success) {
                // Close modal and reset form
                closeModal(applyLeaveModal);
                if (leaveForm) leaveForm.reset();
                
                // Reload calendar data
                loadCalendarData();
                
                // Show success message
                alert(data.message);
            } else {
                alert(data.message || 'Error submitting leave application');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting leave application: ' + error.message);
        })
        .finally(() => {
            // Reset loading state
            if (submitBtn) submitBtn.disabled = false;
            if (spinner) spinner.classList.add('hidden');
        });
    }

    function handleHolidaySubmit(e) {
        e.preventDefault();
        log('Holiday form submitted');
        
        const submitBtn = document.getElementById('submitHoliday');
        const spinner = submitBtn ? submitBtn.querySelector('.fa-spinner') : null;
        
        // Show loading state
        if (submitBtn) submitBtn.disabled = true;
        if (spinner) spinner.classList.remove('hidden');
        
        const formData = new FormData(holidayForm);
        
        fetch('/calendar/add-holiday', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            log('Holiday submission response:', data);
            if (data.success) {
                // Close modal and reset form
                closeModal(addHolidayModal);
                if (holidayForm) holidayForm.reset();
                
                // Reload calendar data
                loadCalendarData();
                
                // Show success message
                alert(data.message);
            } else {
                alert(data.message || 'Error adding holiday');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding holiday: ' + error.message);
        })
        .finally(() => {
            // Reset loading state
            if (submitBtn) submitBtn.disabled = false;
            if (spinner) spinner.classList.add('hidden');
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initCalendar();
    });

    //---------------------------------------------------------------------
    function showDateDetails(selectedDate) {
        const dateHolidays = holidays.filter(h => h.date === selectedDate);

        const dateLeaves = leaves.filter(l => {
            const from = parseDate(l.fromDate);
            const to   = new Date(l.toDate);
            const d    = new Date(selectedDate);
            return d >= from && d <= to;
        });

        // Update Title
        const todayTitle = document.querySelector(".today-title");
        if (todayTitle) {
            todayTitle.innerHTML = `Details for <span class="font-semibold">${selectedDate}</span>`;
        }

        // Show Holidays
        if (upcomingHolidays) {
            upcomingHolidays.innerHTML = "";
            if (dateHolidays.length > 0) {
                dateHolidays.forEach(h => {
                    upcomingHolidays.innerHTML += `
                        <div class="p-2 bg-red-50 rounded border border-red-200">
                            <div class="font-bold text-red-700">${h.title}</div>
                            <div class="text-xs text-gray-600">${h.category} (${h.country})</div>
                        </div>
                    `;
                });
            } else {
                upcomingHolidays.innerHTML = `<div class="text-center text-gray-500 text-sm">No Holidays for this date</div>`;
            }
        }

        // Show Leaves
        if (todayLeaves) {
            todayLeaves.innerHTML = "";
            if (dateLeaves.length > 0) {
                dateLeaves.forEach(l => {
                    todayLeaves.innerHTML += `
                        <div class="p-2 bg-green-50 border border-green-200 rounded">
                            <div class="font-semibold">${l.employee}</div>
                            <div class="text-xs">${l.type} (${l.status})</div>
                        </div>
                    `;
                });
            } else {
                todayLeaves.innerHTML = `<div class="text-center text-gray-500 text-sm">No Leaves for this date</div>`;
            }
        }
    }
</script>

@endsection