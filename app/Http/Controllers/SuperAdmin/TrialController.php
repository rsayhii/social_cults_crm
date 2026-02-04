<?php
// app/Http/Controllers/TrialController.php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    public function index()
{
    // All trial companies (active + expired)
    $activeTrials = Company::where('is_paid', false)
        ->orderBy('trial_ends_at', 'asc')
        ->paginate(10);

    // Stats
    $trialsStats = [
        'active' => Company::where('is_paid', false)
            ->whereDate('trial_ends_at', '>=', now())
            ->count(),

        'converted' => Company::where('is_paid', true)->count(),

        'expired' => Company::where('is_paid', false)
            ->whereDate('trial_ends_at', '<', now())
            ->count(),
    ];

    return view('superadmin.trials', compact('activeTrials', 'trialsStats'));
}


    public function convertToPaid($id)
    {
        $company = Company::findOrFail($id);

        $company->update([
            'is_paid' => true,
            'status' => 'active',
            'trial_ends_at' => null,
        ]);

        return back()->with('success', 'Company converted to paid successfully.');
    }
}
