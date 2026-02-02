<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UpgradePlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        $trialRemaining = '';
        $isTrial = false;

        if ($company) {
            if (!$company->is_paid && $company->trial_ends_at && Carbon::now()->lt($company->trial_ends_at)) {
                $diff = Carbon::now()->diff($company->trial_ends_at);
                $trialRemaining = $diff->days . ' Days ' . $diff->h . ' Hours';
                $isTrial = true;
            }
        }

        return view('admin.upgrade.index', compact('company', 'trialRemaining', 'isTrial'));
    }
}
