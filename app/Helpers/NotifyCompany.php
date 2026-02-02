<?php

use App\Models\User;
use App\Notifications\SystemNotification;

if (!function_exists('notifyCompany')) {
    function notifyCompany(int $companyId, array $data)
    {
        User::where('company_id', $companyId)
            ->get()
            ->each(fn ($user) => $user->notify(
                new SystemNotification($data)
            ));
    }
}
