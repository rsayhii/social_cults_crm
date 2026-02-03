<?php

use App\Models\User;
use App\Notifications\SystemNotification;

if (!function_exists('notifyCompany')) {
    function notifyCompany(int $companyId, array $data)
    {
        User::where('company_id', $companyId)
            ->where('id', '<>', auth()->id())
            ->get()
            ->each(fn ($user) => $user->notify(
                new SystemNotification($data)
            ));
    }
}
