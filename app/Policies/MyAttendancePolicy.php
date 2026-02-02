<?php

namespace App\Policies;

use App\Models\MyAttendance;
use App\Models\User;

class MyAttendancePolicy
{
    public function manage(User $user, MyAttendance $attendance)
    {
        return $user->company_id === $attendance->company_id;
    }
}
