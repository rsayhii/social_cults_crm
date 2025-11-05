<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Sales\BesdexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\MyAttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController as ControllersProposalController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Sales\MyLeadsController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.show');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:dashboard');

    // Clients
    Route::middleware('permission:besdex')->group(function () {
        Route::get('/clients', [ClientController::class, 'index'])->name('clients');
        Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
        Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
        Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
        Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
        Route::post('/clients/import', [ClientController::class, 'import'])->name('clients.import');
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
    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance')
        ->middleware('permission:attendance records');

    // Roles
    Route::middleware('permission:roles')->group(function () {
        Route::get('/roles', [RolesController::class, 'index'])->name('roles');
        Route::get('/roles/create', [RolesController::class, 'create'])->name('roles.create');
        Route::post('/roles/store', [RolesController::class, 'store'])->name('roles.store');
        Route::put('/roles/update/{id}', [RolesController::class, 'update'])->name('roles.update');
        Route::get('/roles/show/{id}', [RolesController::class, 'show'])->name('roles.show');
        Route::get('/roles/edit/{id}', [RolesController::class, 'edit'])->name('roles.edit');
        Route::get('/roles/destroy/{id}', [RolesController::class, 'destroy'])->name('roles.destroy');
    });

    // Users
    Route::middleware('permission:users')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
        Route::get('/users/show/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
        Route::get('/users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');
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
    Route::middleware('permission:proposal')->group(function(){
        Route::get('/propsal',[ProposalController::class,'index'])->name('proposal');

    //      Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');
    Route::get('/proposals/create', [ProposalController::class, 'create'])->name('proposals.create');
    Route::post('/proposals', [ProposalController::class, 'store'])->name('proposals.store');
    Route::get('/proposals/{proposal}/edit', [ProposalController::class, 'edit'])->name('proposals.edit');
    Route::put('/proposals/{proposal}', [ProposalController::class, 'update'])->name('proposals.update');
    Route::delete('/proposals/{proposal}', [ProposalController::class, 'destroy'])->name('proposals.destroy');
    });



    // My Leads
    // Route::middleware('permission:my leads')->group(function(){
        Route::get('/myleads',[MyLeadsController::class,'index'])->name('myleads');
        Route::post('/myleads/store', [MyLeadsController::class, 'store'])->name('myleads.store');


        Route::get('/myleads/edit/{id}', [MyLeadsController::class, 'edit'])->name('myleads.edit');
Route::put('/myleads/{id}', [MyLeadsController::class, 'update'])->name('myleads.update');


      Route::get('/myleads/{id}', [MyLeadsController::class, 'show'])->name('myleads.show');
    // });



      // My Attendence
    Route::middleware('permission:my attendance')->group(function(){
        Route::get('/myattendance',[MyAttendanceController::class,'index'])->name('myattendance');
    Route::post('/punch-in', [MyAttendanceController::class, 'punchIn'])->name('punch.in');
    Route::post('/punch-out', [MyAttendanceController::class, 'punchOut'])->name('punch.out');
    Route::post('/toggle-break', [MyAttendanceController::class, 'toggleBreak'])->name('toggle.break');
    });











});
