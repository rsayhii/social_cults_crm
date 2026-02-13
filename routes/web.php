<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\Sales\BesdexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientServiceInterationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HelpAndSupportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LinkAndRemarkController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MyAttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController as ControllersProposalController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Sales\MyLeadsController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SalaryConfigController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPortalController;
use App\Http\Controllers\EmployeePortalController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProjectManagementController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SuperAdmin\CustomerController;
use App\Http\Controllers\SuperAdmin\PaymentController;
use App\Http\Controllers\SuperAdmin\RevenueController;
use App\Http\Controllers\SuperAdmin\SettingsController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminInvoiceController;
use App\Http\Controllers\SuperAdmin\TrialController;
use App\Http\Controllers\FreeTrialController;
use App\Http\Controllers\Superadmin\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketRecordController;
use App\Http\Controllers\UpgradePlanController;
use App\Http\Middleware\CheckCompanyAccess;

Route::get('/', function () {
    return view('admin.home');
});

Route::get('/home2', function () {
    return view('admin.home2');
});


// Public routes
Route::get('/loginshow', [LoginController::class, 'showLoginForm'])->name('login.show');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Protected routes
Route::middleware(['auth',  CheckCompanyAccess::class])->group(function () {

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
        // ->middleware('permission:dashboard');

   
   // Clients
Route::middleware('permission:besdex')->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index'); // Note: renamed from 'clients' to 'clients.index' for consistency
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create'); // Optional: if you want to separate add too, but keeping modal for now
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit'); // NEW
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::post('/clients/import', [ClientController::class, 'import'])->name('clients.import');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
});

    // Campaigns
    Route::get('/campaigns', [CampaignsController::class, 'index'])
        ->name('campaigns')
        ->middleware('permission:campaigns');

    // Tasks
    Route::get('/task', [TaskController::class, 'index'])
        ->name('task')
        ->middleware('permission:tasks');

    // Attendance
    Route::middleware('permission:attendance records')->group(function () {
    
    // Main view for attendance summary
    Route::get('/attendance-record', [AttendanceRecordController::class, 'index'])->name('attendance-record.index');
    
    // AJAX endpoint for monthly details
    Route::get('/attendance-record/monthly', [AttendanceRecordController::class, 'getMonthlyAttendance'])->name('attendance-record.monthly');

});

// Remove ALL other role routes and keep only this:
// Route::middleware('permission:roles')->group(function () {
    Route::get('/roles', [RolesController::class, 'index'])->name('roles');
    Route::get('/roles/create', [RolesController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolesController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}', [RolesController::class, 'show'])->name('roles.show');
    Route::get('/roles/{id}/edit', [RolesController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [RolesController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RolesController::class, 'destroy'])->name('roles.destroy');
// });


  // Users
// Route::middleware('permission:users')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');  // Simplified path
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');  // Fixed path and binding
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');  // Fixed path
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');  // Fixed path
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');  // Fixed to DELETE
// });




    Route::middleware('permission:salary')->group(function () {

    Route::get('/salary/generate', [SalaryController::class, 'showGenerateForm'])->name('salary.index');

    // Salary list page (missing route)
    Route::get('/salary/list', [SalaryController::class, 'index'])->name('salary.list');

    // HTML slip
    Route::get('/salary/slip/{id}', [SalaryController::class, 'showSlip'])->name('salary.slip');

    // Generate salary
    Route::post('/salary/generate', [SalaryController::class, 'generateSalary'])->name('salary.generate');

    // Bulk generate
    Route::post('/salary/bulk-generate', [SalaryController::class, 'bulkGenerate'])->name('salary.bulk-generate');

    // JSON slip
    Route::get('/salary/slip-json/{id}', [SalaryController::class, 'getSalarySlip']);

    // Mark as paid
    Route::put('/salary/mark-paid/{id}', [SalaryController::class, 'markAsPaid']);

    // Employee salary history
    Route::get('/salary/employee/{id}', [SalaryController::class, 'getEmployeeSalaries']);

    // Config
    Route::get('/salary/config', [SalaryConfigController::class, 'getConfig'])->name('salary.config');
    Route::put('/salary/config', [SalaryConfigController::class, 'updateConfig']);


    // Export route
Route::get('/salary/export', [SalaryController::class, 'export'])->name('salary.export');



        Route::get('/edit/{id}', [SalaryController::class, 'edit'])->name('salary.edit');
        Route::delete('/salary/{id}', [SalaryController::class, 'destroy'])
    ->name('salary.delete');


});






    // Profile
    Route::get('/profile', [ProfileController::class, 'viewProfile'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Besdex
    //  Route::middleware('permission:besdex')->group(function(){
    //      Route::get('/besdex',[BesdexController::class,'index'])->name('besdex');
    //  });



    // Proposal
    Route::middleware('permission:proposal')->group(function () {
        Route::get('/propsal', [ProposalController::class, 'index'])->name('proposal');
        // Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');
        Route::get('/proposals/create', [ProposalController::class, 'create'])->name('proposals.create');
        Route::post('/proposals', [ProposalController::class, 'store'])->name('proposals.store');
        Route::get('/proposals/{proposal}/edit', [ProposalController::class, 'edit'])->name('proposals.edit');
        Route::put('/proposals/{proposal}', [ProposalController::class, 'update'])->name('proposals.update');
        Route::delete('/proposals/{proposal}', [ProposalController::class, 'destroy'])->name('proposals.destroy');
    });



    // My Leads
    Route::middleware('permission:my leads')->group(function () {
        Route::get('/myleads', [MyLeadsController::class, 'index'])->name('myleads');
        Route::post('/myleads/store', [MyLeadsController::class, 'store'])->name('myleads.store');
        Route::get('/myleads/edit/{id}', [MyLeadsController::class, 'edit'])->name('myleads.edit');
        Route::put('/myleads/{id}', [MyLeadsController::class, 'update'])->name('myleads.update');
        Route::get('/myleads/{id}', [MyLeadsController::class, 'show'])->name('myleads.show');
        Route::get('/myleads/{id}/history', [MyLeadsController::class, 'history'])->name('myleads.history');
    });



  Route::middleware('permission:my attendance')->group(function () {  
    Route::get('/my-attendance', [MyAttendanceController::class, 'index'])->name('my-attendance.index');
    Route::post('/my-attendance/punch-in', [MyAttendanceController::class, 'punchIn'])->name('my-attendance.punch-in');
    Route::post('/my-attendance/punch-out', [MyAttendanceController::class, 'punchOut'])->name('my-attendance.punch-out');
    
    // ✅ BREAK ROUTES
    Route::post('/my-attendance/break-in', [MyAttendanceController::class, 'breakIn'])->name('my-attendance.break-in');
    Route::post('/my-attendance/break-out', [MyAttendanceController::class, 'breakOut'])->name('my-attendance.break-out');
    Route::get('/my-attendance/break-status', [MyAttendanceController::class, 'getCurrentBreakStatus'])->name('my-attendance.break-status');
    
    Route::get('/my-attendance/log', [MyAttendanceController::class, 'getLog'])->name('my-attendance.log');
});



    // Route::middleware('permission:salary')->group(function () {
    //     Route::get('/my-salary', [SalaryController::class, 'index'])->name('salary.index');
    //     Route::get('/salary/{id}', [SalaryController::class, 'show'])->name('salary.show');
    //     Route::get('/salary/{id}/download', [SalaryController::class, 'downloadPdf'])->name('salary.download');
    // });


    // Employee Portal Routes with Permission Middleware
Route::middleware('permission:employeeportal')->group(function () {
    Route::get('/employeeportal', [EmployeePortalController::class, 'index'])->name('employeeportal.index');
    Route::prefix('employeeportal')->group(function () {
    Route::get('/leaves', [EmployeePortalController::class, 'getLeaves'])->name('employeeportal.leaves');
    Route::get('/leaves/{id}', [EmployeePortalController::class, 'getLeave'])->name('employeeportal.leave.show');
    Route::post('/leaves', [EmployeePortalController::class, 'store'])->name('employeeportal.leave.store');
    Route::put('/leaves/{id}', [EmployeePortalController::class, 'update'])->name('employeeportal.leave.update');
    Route::delete('/leaves/{id}', [EmployeePortalController::class, 'destroy'])->name('employeeportal.leave.destroy');
    Route::get('/statistics', [EmployeePortalController::class, 'getStatistics'])->name('employeeportal.statistics');
    Route::get('/filters', [EmployeePortalController::class, 'getFilters'])->name('employeeportal.filters');
    Route::get('/export/{format}', [EmployeePortalController::class, 'export'])->name('employeeportal.export');

    Route::get('/employeeportal/roles', [App\Http\Controllers\EmployeePortalController::class, 'getRoles'])->name('employeeportal.roles');
    });
});



   Route::middleware('permission:todo')->group(function () {
    Route::get('/todo', [TodoController::class, 'index'])->name('todo.index');
    Route::post('/todo', [TodoController::class, 'store'])->name('todo.store');
    Route::put('/todo/{todo}', [TodoController::class, 'update'])->name('todo.update');
    Route::delete('/todo/{todo}', [TodoController::class, 'destroy'])->name('todo.destroy');
    Route::post('/todo/toggle-completed/{todo}', [TodoController::class, 'toggleCompleted'])->name('todo.toggle');
    Route::post('/todo/toggle-starred/{todo}', [TodoController::class, 'toggleStarred'])->name('todo.toggle-starred');
});




        // Project Management Routes with Permission Middleware
        Route::middleware('permission:project management')->group(function () {
            Route::get('/projectmanagement', [ProjectManagementController::class, 'index'])->name('projectmanagement.index');
            Route::get('/projectmanagement/projects', [ProjectManagementController::class, 'getProjects'])->name('projectmanagement.projects');
            Route::get('/projectmanagement/projects/{id}', [ProjectManagementController::class, 'getProject'])->name('projectmanagement.project.show');
            Route::post('/projectmanagement/projects', [ProjectManagementController::class, 'store'])->name('projectmanagement.project.store');
            Route::put('/projectmanagement/projects/{id}', [ProjectManagementController::class, 'update'])->name('projectmanagement.project.update');
            Route::delete('/projectmanagement/projects/{id}', [ProjectManagementController::class, 'destroy'])->name('projectmanagement.project.destroy');
            Route::get('/projectmanagement/statistics', [ProjectManagementController::class, 'getStatistics'])->name('projectmanagement.statistics');
            Route::get('/projectmanagement/filters', [ProjectManagementController::class, 'getFilters'])->name('projectmanagement.filters');
        });









    Route::middleware('permission:task')->group(function () { 
         Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
         Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
         Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
         Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
         Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
         Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
         Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });


    Route::middleware('permission:calender')->group(function () {
    Route::get('/calender', [CalenderController::class, 'index'])->name('calender.index');
    Route::get('/calendar/data', [CalenderController::class, 'getCalendarData'])->name('calendar.data');
    Route::post('/calendar/apply-leave', [CalenderController::class, 'applyLeave'])->name('calendar.apply-leave');
    Route::post('/calendar/add-holiday', [CalenderController::class, 'addHoliday'])->name('calendar.add-holiday');
    Route::get('/calendar/users', [CalenderController::class, 'getUsers'])->name('calendar.users');

    });




    


    Route::middleware('permission:links and remark')->group(function () {
        Route::get('/linksandremark', [LinkAndRemarkController::class, 'index'])->name('linksandremark.index');
    });








 Route::middleware('permission:links and remark')->group(function () {    
    Route::get('/links', [LinkAndRemarkController::class, 'index'])->name('index');
    Route::get('/links/fetch-all', [LinkAndRemarkController::class, 'fetchAll'])->name('fetch');
    Route::post('/links/store', [LinkAndRemarkController::class, 'store'])->name('store');
    Route::put('/links/update/{id}', [LinkAndRemarkController::class, 'update'])->name('update');
    Route::delete('/links/delete/{id}', [LinkAndRemarkController::class, 'destroy'])->name('delete');
    Route::get('/links/show/{id}', [LinkAndRemarkController::class, 'show'])->name('show');
});


     //Admin Portal Routes with Permission Middleware
    
Route::middleware('permission:admin portal')->group(function () {
    Route::get('/adminportal', [AdminPortalController::class, 'index'])->name('adminportal.index');
    Route::prefix('adminportal')->group(function () {
    Route::get('/leaves', [AdminPortalController::class, 'getLeaves'])->name('adminportal.leaves');
    Route::get('/leaves/{id}', [AdminPortalController::class, 'getLeave'])->name('adminportal.leave.show');
    Route::post('/leaves/{id}/approve', [AdminPortalController::class, 'approveLeave'])->name('adminportal.leave.approve');
    Route::post('/leaves/{id}/reject', [AdminPortalController::class, 'rejectLeave'])->name('adminportal.leave.reject');
    Route::post('/leaves/{id}/remarks', [AdminPortalController::class, 'addRemarks'])->name('adminportal.leave.remarks');
    Route::put('/leaves/{id}', [AdminPortalController::class, 'update'])->name('adminportal.leave.update');
    Route::delete('/leaves/{id}', [AdminPortalController::class, 'destroy'])->name('adminportal.leave.destroy');
    Route::get('/analytics', [AdminPortalController::class, 'getAnalytics'])->name('adminportal.analytics');
    Route::get('/filters', [AdminPortalController::class, 'getFilters'])->name('adminportal.filters');
    Route::get('/export/{format}', [AdminPortalController::class, 'export'])->name('adminportal.export');
    });
});


// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------

//   Route::middleware(['auth', 'permission:client serive interation'])->group(function () {

//     Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
//     Route::get('/chat/list', [ChatController::class, 'chatList']);
//     Route::get('/chat/messages/{id}', [ChatController::class, 'getMessages']);
//     Route::post('/chat/send', [ChatController::class, 'sendMessage']);
//     Route::post('/chat/create-conversation', [ChatController::class, 'createConversation']);
//     Route::get('/chat/teams', [ChatController::class, 'teams']);
//     Route::get('/chat/clients', [ChatController::class, 'clients']);

// });

Route::middleware(['auth', 'permission:client serive interation'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/list', [ChatController::class, 'chatList']);
    Route::get('/chat/messages/{id}', [ChatController::class, 'getMessages']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
    Route::post('/chat/create-conversation', [ChatController::class, 'createConversation']);
    Route::get('/chat/teams', [ChatController::class, 'teams']);
    Route::get('/chat/clients', [ChatController::class, 'clients']);
    // Role-based chat routes
    Route::get('/chat/same-role-users', [ChatController::class, 'sameRoleUsers']);
    Route::get('/chat/role-groups', [ChatController::class, 'roleGroupChats']);
    Route::get('/chat/group-members/{id}', [ChatController::class, 'getGroupMembers']);
    Route::get('/chat/all-groups', [ChatController::class, 'allGroupChats']); // Admin only
    // Admin Group Management Routes
    Route::post('/chat/create-group', [ChatController::class, 'createGroup']); // Admin only
    Route::post('/chat/add-group-members', [ChatController::class, 'addGroupMembers']); // Admin only
    Route::post('/chat/remove-group-member', [ChatController::class, 'removeGroupMember']); // Admin only
    Route::delete('/chat/delete-group', [ChatController::class, 'deleteGroup']); // Admin only
    Route::get('/chat/users-by-role/{roleId}', [ChatController::class, 'getUsersByRole']);
    Route::get('/chat/clients-list', [ChatController::class, 'getClientsForGroup']);
    Route::get('/chat/roles-list', [ChatController::class, 'getAllRoles']);
    // Client Team Targeting
    Route::get('/chat/group-teams/{id}', [ChatController::class, 'getGroupTeams']);
});

// ------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------



// routes/web.php
Route::middleware(['auth', 'permission:invoice'])->group(function () {

    // Invoice listing
    Route::get('/invoices', [InvoiceController::class, 'index'])
        ->name('invoices.index');

    // ✅ Invoice History (NEW)
    Route::get('/invoices/history', [InvoiceController::class, 'getHistory'])
        ->name('invoices.history');


        Route::get('/invoices/recent', [InvoiceController::class, 'getRecentInvoices']);
Route::post('/invoices/{id}/email', [InvoiceController::class, 'sendEmail']);
Route::get('/invoices/{id}/share-link', [InvoiceController::class, 'getShareLink']);


    // Company routes
    Route::prefix('company')->group(function () {
        Route::get('/', [CompanyController::class, 'index']);
        Route::post('/', [CompanyController::class, 'store'])->name('company.store');
        Route::delete('/{id}', [CompanyController::class, 'destroy']);
        Route::get('/signature', [CompanyController::class, 'getSignature']);
        Route::post('/signature', [CompanyController::class, 'saveSignature']);
    });

    // Invoice routes
    Route::prefix('invoices')->group(function () {

        // ✅ Already exists (NO duplication)
        Route::post('/', [InvoiceController::class, 'store']);
        Route::put('/{id}', [InvoiceController::class, 'update']);

        Route::get('/{id}', [InvoiceController::class, 'show']);
        Route::delete('/{id}', [InvoiceController::class, 'destroy']);
        Route::get('/{id}/download', [InvoiceController::class, 'downloadPDF'])
            ->name('invoice.download');

        Route::post('/bulk-status', [InvoiceController::class, 'bulkUpdateStatus']);
        Route::post('/bulk-delete', [InvoiceController::class, 'bulkDelete']);
    });
});


 Route::middleware('permission:contact')->group(function () {
        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
        Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
        Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
        Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
        Route::get('/contacts/export/{format}', [ContactController::class, 'export'])->name('contacts.export');
    });


    Route::middleware('permission:help and support')->group(function () {
    Route::get('/helpandsupport', [HelpAndSupportController::class, 'index'])->name('helpandsupport.index');
    Route::get('/helpandsupport/tickets', [HelpAndSupportController::class, 'getTickets']);
    Route::post('/helpandsupport/tickets', [HelpAndSupportController::class, 'storeTicket']);
    Route::get('/helpandsupport/tickets/{id}', [HelpAndSupportController::class, 'getTicket']);
    Route::put('/helpandsupport/tickets/{id}', [HelpAndSupportController::class, 'updateTicket']);
    Route::post('/helpandsupport/tickets/{ticketId}/conversation', [HelpAndSupportController::class, 'addConversation']);
    Route::get('/helpandsupport/knowledge-base', [HelpAndSupportController::class, 'getKnowledgeBase']);
    Route::get('/helpandsupport/knowledge-base/search', [HelpAndSupportController::class, 'searchKnowledgeBase']);
    Route::post('/helpandsupport/export-tickets', [HelpAndSupportController::class, 'exportTickets']);
});


Route::middleware('permission:report')->group(function () {
    Route::get('/reportsindex', [ReportController::class, 'index'])->name('rr.index');
    Route::get('/reports', [ReportController::class, 'getReports'])->name('rr.reports');
    Route::get('/reports/stats', [ReportController::class, 'getStats'])->name('rr.stats');

    Route::post('/reports', [ReportController::class, 'store'])->name('rr.store');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('rr.show');
    Route::put('/reports/{id}', [ReportController::class, 'update'])->name('rr.update');
    Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('rr.destroy');
});



Route::middleware('permission:notepad')->group(function () {
Route::get('/notepad', function () {
    return view('admin.notepad');
})->name('notepad.index');

  Route::middleware(['auth'])->group(function () {
    // Dashboard view
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.dashboard');
    
    // API routes for AJAX calls
    Route::prefix('api/notes')->group(function () {
        Route::get('/', [NoteController::class, 'getNotes'])->name('notes.get');
        Route::get('/stats', [NoteController::class, 'getStats'])->name('notes.stats');
        Route::get('/{id}', [NoteController::class, 'show'])->name('notes.show');
        Route::post('/', [NoteController::class, 'store'])->name('notes.store');
        Route::put('/{id}', [NoteController::class, 'update'])->name('notes.update');
        Route::delete('/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');
        Route::patch('/{id}/toggle-pin', [NoteController::class, 'togglePin'])->name('notes.toggle-pin');
    });
});




});


//     Route::middleware('permission:project')->group(function () {
// Route::get('/project', function () {
//     return view('admin.project');
// })->name('project.index');


//     });







Route::middleware('permission:ticket raise')->prefix('user/support/ticket')
    ->name('user.support.ticket.')
    ->group(function () {

        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{id}', [TicketController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [TicketController::class, 'reply'])->name('reply');
});






});




// ***********************************************************************
// ************************** END OF ROUTES FILE *************************
// ***********************************************************************







































// ***********************************************************************
// ******************** START SUPERADMIN OF ROUTES FILE ******************
// ***********************************************************************
Route::prefix('superadmin')->group(function () {


Route::get('login', [AuthController::class, 'showLogin'])->name('superadmin.login');
    Route::post('login', [AuthController::class, 'login'])->name('superadmin.login.submit');





 Route::middleware('superadmin')->group(function () {

// Dashboard
Route::get('/', [SuperAdminDashboardController::class, 'index'])->name('superadmin.dashboard');

// Customers


    Route::get('customers', [CustomerController::class, 'index'])->name('superadmin.customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('superadmin.customers.create');
    Route::post('customers', [CustomerController::class, 'store'])->name('superadmin.customers.store');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('superadmin.customers.show');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('superadmin.customers.edit');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('superadmin.customers.update');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('superadmin.customers.destroy');


// Trials

    Route::get('trials', [TrialController::class, 'index'])->name('superadmin.trials.index');
    Route::post('trials/{id}/convert', [TrialController::class, 'convertToPaid'])->name('superadmin.trials.convert');


    // Subscription Routes

        Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('superadmin.subscriptions.index');
   

// Payments Routes

    Route::get('payments', [PaymentController::class, 'index'])->name('superadmin.payments.index');
    Route::get('payments/create', [PaymentController::class, 'index'])->name('superadmin.payments.create');
    Route::post('payments', [PaymentController::class, 'store'])->name('superadmin.payments.store');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('superadmin.payments.show');
    Route::get('payments/{payment}/edit', [PaymentController::class, 'edit'])->name('superadmin.payments.edit');
    Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('superadmin.payments.update');
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('superadmin.payments.destroy');


// Additional routes

    Route::post('/payments/{payment}/process', [PaymentController::class, 'processPayment'])->name('superadmin.payments.process');
    Route::get('/payments/trashed', [PaymentController::class, 'trashed'])->name('superadmin.payments.trashed');
    Route::post('/payments/{id}/restore', [PaymentController::class, 'restore'])->name('superadmin.payments.restore');
    Route::delete('/payments/{id}/force-delete', [PaymentController::class, 'forceDelete'])->name('superadmin.payments.force-delete');
    Route::get('/payments/statistics', [PaymentController::class, 'getStatistics'])->name('superadmin.payments.statistics');



    Route::get('invoices', [SuperAdminInvoiceController::class, 'index'])->name('superadmin.invoices.index');
    Route::get('invoices/create', [SuperAdminInvoiceController::class, 'create'])->name('superadmin.invoices.create');
    Route::post('invoices', [SuperAdminInvoiceController::class, 'store'])->name('superadmin.invoices.store');
    Route::get('invoices/{id}', [SuperAdminInvoiceController::class, 'show'])->name('superadmin.invoices.show');
    Route::get('invoices/{id}/edit', [SuperAdminInvoiceController::class, 'edit'])->name('superadmin.invoices.edit');
    Route::put('invoices/{id}', [SuperAdminInvoiceController::class, 'update'])->name('superadmin.invoices.update');
    Route::delete('invoices/{id}', [SuperAdminInvoiceController::class, 'destroy'])->name('superadmin.invoices.destroy');
    Route::get('invoices/{id}/download', [SuperAdminInvoiceController::class, 'download'])->name('superadmin.invoices.download');

    // Additional invoice routes
    Route::post('invoices/{id}/status', [SuperAdminInvoiceController::class, 'updateStatus'])->name('superadmin.invoices.status.update');
    Route::post('invoices/bulk-delete', [SuperAdminInvoiceController::class, 'bulkDelete'])->name('superadmin.invoices.bulk.delete');
    Route::post('invoices/bulk-download', [SuperAdminInvoiceController::class, 'bulkDownload'])->name('superadmin.invoices.bulk.download');
    Route::get('invoices/stats', [SuperAdminInvoiceController::class, 'getStats'])->name('superadmin.invoices.stats');
    Route::get('invoices/export', [SuperAdminInvoiceController::class, 'export'])->name('superadmin.invoices.export');
    Route::post('invoices/preview', [InvoiceController::class, 'preview'])->name('superadmin.invoices.preview');
    Route::get('invoices/search', [SuperAdminInvoiceController::class, 'search'])->name('superadmin.invoices.search');

    Route::post('/invoices/{invoice}/mark-paid', [SuperAdminInvoiceController::class, 'markAsPaid'])->name('superadmin.invoices.mark-paid');




     Route::get('logout', [AuthController::class, 'logout'])->name('superadmin.logout');
    


    Route::get('/ticket/record', [TicketRecordController::class, 'index'])->name('ticket.record.index');
    Route::get('/ticket/record/{id}', [TicketRecordController::class, 'show'])->name('ticket.record.show');
    Route::put('/ticket/record/{id}', [TicketRecordController::class, 'update'])->name('ticket.record.update');
    Route::delete('/ticket/record/{id}', [TicketRecordController::class, 'destroy'])->name('ticket.record.destroy');
    Route::post('/ticket/record/{id}/reply', [TicketRecordController::class, 'reply'])->name('ticket.record.reply');


// Revenue


    Route::get('revenue', [RevenueController::class, 'index'])->name('superadmin.revenue.index');


// Settings

    Route::get('settings', [SettingsController::class, 'index'])->name('superadmin.settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('superadmin.settings.update');



// Profile
Route::get('/profile', function () {
    return view('profile');
})->name('superadmin.profile');


  });
});
// ***********************************************************************
// ******************** END SUPERADMIN OF ROUTES FILE ********************
// ***********************************************************************









// -------------------------------------------------------------------
// -------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/start-trial', [FreeTrialController::class, 'create'])->name('trial.create');
    Route::post('/start-trial', [FreeTrialController::class, 'store'])->name('trial.store');
});



// ----------------------------------------------------------------------
// ----------------------------------------------------------------------
// Upgrade Plan
 Route::middleware(['auth'])->group(function () {
    Route::get('/upgrade-plan', [UpgradePlanController::class, 'index'])
        ->name('upgrade.index');
});





// ----------------------------------------------------------------------
// ----------------------------------------------------------------------
Route::get('/debug-roles', function () {
    $results = [];

    // 1. Check Database Schema
    $hasCompanyId = \Illuminate\Support\Facades\Schema::hasColumn('roles', 'company_id');
    $results['Database Check'] = $hasCompanyId 
        ? '✅ SUCCESS: roles table has company_id column.' 
        : '❌ FAILED: roles table MISSING company_id column. Run migrations!';

    // 2. Check Role Model Override
    try {
        $reflection = new ReflectionMethod(\App\Models\Role::class, 'create');
        $declaringClass = $reflection->getDeclaringClass()->getName();
        $results['Role Model Check'] = ($declaringClass === 'App\Models\Role')
            ? '✅ SUCCESS: Role::create is overridden in App\Models\Role.'
            : '❌ FAILED: Role::create is NOT overridden. It is using ' . $declaringClass;
    } catch (Exception $e) {
        $results['Role Model Check'] = '❌ ERROR: ' . $e->getMessage();
    }

    // 4. Test Creation (Rolled back)
    \Illuminate\Support\Facades\DB::beginTransaction();
    try {
        $testCompany = \App\Models\Company::create([
            'name' => 'Debug Test Company ' . \Illuminate\Support\Str::random(5),
            'slug' => 'debug-test-' . \Illuminate\Support\Str::random(5),
            'status' => 'active'
        ]);
        
        $role = \App\Models\Role::where('name', 'admin')->where('company_id', $testCompany->id)->first();
        
        if ($role) {
            $results['Observer Test'] = '✅ SUCCESS: Observer ran and created an admin role (ID: ' . $role->id . ')';
        } else {
            $results['Observer Test'] = '❌ FAILED: Company created but NO admin role found. Observer did not fire or failed silently.';
        }

    } catch (Exception $e) {
        $results['Observer Test'] = '❌ EXCEPTION: ' . $e->getMessage();
    }
    \Illuminate\Support\Facades\DB::rollBack();

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});
// ----------------------------------------------------------------------
// ----------------------------------------------------------------------


Route::post('/notifications/read', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return response()->json(['success' => true]);
});
