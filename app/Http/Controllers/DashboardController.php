<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $companyId = $user->company_id;

        // Top-card counts
        $totalUsers    = User::where('company_id', $companyId)->count();
        $totalClients  = Client::where('company_id', $companyId)->count();
        $totalContacts = Contact::where('company_id', $companyId)->count();

        // Comparison Data (Growth vs Start of Month)
        // 1. Users Growth
        $usersLastMonthTotal = User::where('company_id', $companyId)
            ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->count();
        $userGrowth = $usersLastMonthTotal > 0 
            ? (($totalUsers - $usersLastMonthTotal) / $usersLastMonthTotal) * 100 
            : ($totalUsers > 0 ? 100 : 0);

        // 2. Clients Growth
        $clientsLastMonthTotal = Client::where('company_id', $companyId)
            ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->count();
        $clientGrowth = $clientsLastMonthTotal > 0 
            ? (($totalClients - $clientsLastMonthTotal) / $clientsLastMonthTotal) * 100 
            : ($totalClients > 0 ? 100 : 0);

        // 3. Contacts Growth
        $contactsLastMonthTotal = Contact::where('company_id', $companyId)
            ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->count();
        $contactGrowth = $contactsLastMonthTotal > 0 
            ? (($totalContacts - $contactsLastMonthTotal) / $contactsLastMonthTotal) * 100 
            : ($totalContacts > 0 ? 100 : 0);

        // Present employees for today:
        $presentToday = MyAttendance::where('company_id', $companyId)
            ->whereDate('date', Carbon::today())
            ->whereNotNull('punch_in')
            ->distinct('employee_id')
            ->count('employee_id');
        
        // Present employees yesterday:
        $presentYesterday = MyAttendance::where('company_id', $companyId)
            ->whereDate('date', Carbon::yesterday())
            ->whereNotNull('punch_in')
            ->distinct('employee_id')
            ->count('employee_id');

        $attendanceGrowth = $presentYesterday > 0 
            ? (($presentToday - $presentYesterday) / $presentYesterday) * 100 
            : ($presentToday > 0 ? 100 : 0);

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
            $usersCount = User::where('company_id', $companyId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            // attendance count in that month (you can change to distinct employees if needed)
            $attCount = MyAttendance::where('company_id', $companyId)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->count();

            $usersChartLabels[] = $label;
            $usersChartData[]   = $usersCount;

            $attChartLabels[] = $label;
            $attChartData[]   = $attCount;
        }

        // Recent activity (tasks) - latest 6
        $recentTasks = Task::where('company_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'presentToday',
            'totalClients',
            'totalContacts',
            'usersChartLabels',
            'usersChartData',
            'attChartLabels',
            'attChartData',
            'recentTasks',
            'userGrowth',
            'clientGrowth',
            'contactGrowth',
            'attendanceGrowth'
        ));
    }
}
