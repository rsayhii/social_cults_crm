<?php

namespace App\Policies;

use App\Models\Mylead;
use App\Models\User;

class MyleadPolicy
{
    public function manage(User $user, Mylead $lead)
    {
        return $user->company_id === $lead->company_id;
    }
}
