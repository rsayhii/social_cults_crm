<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MyAttendance;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        // Top-card counts
        $totalUsers    = User::count();
        $totalClients  = Client::count();
        $totalContacts = Contact::count();

        // Present employees for today:
        // assumption: MyAttendance has date column and punch_in (nullable).
        // counting distinct employee_id who punched in today.
        $presentToday = MyAttendance::whereDate('date', Carbon::today())
            ->whereNotNull('punch_in')
            ->distinct('employee_id')
            ->count('employee_id');

        // Chart data: last 12 months (labels & two datasets)
        $usersChartLabels = [];
        $usersChartData   = [];
        $attChartLabels   = [];
        $attChartData     = [];

        // iterate from 11 months ago -> this month
        for ($i = 11; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            $label = $dt->format('M Y'); // e.g. "Dec 2025"
            $year = $dt->year;
            $month = $dt->month;

            // users created in that month
            $usersCount = User::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            // attendance count in that month (you can change to distinct employees if needed)
            $attCount = MyAttendance::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->count();

            $usersChartLabels[] = $label;
            $usersChartData[]   = $usersCount;

            $attChartLabels[] = $label;
            $attChartData[]   = $attCount;
        }

        // Recent activity (tasks) - latest 6
        $recentTasks = Task::orderBy('created_at', 'desc')->limit(6)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'presentToday',
            'totalClients',
            'totalContacts',
            'usersChartLabels',
            'usersChartData',
            'attChartLabels',
            'attChartData',
            'recentTasks'
        ));
    }
}
