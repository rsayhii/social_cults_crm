<?php

namespace App\Policies;

use App\Models\Link;
use App\Models\User;

class LinkPolicy
{
    public function manage(User $user, Link $link)
    {
        return $user->company_id === $link->company_id;
    }
}
