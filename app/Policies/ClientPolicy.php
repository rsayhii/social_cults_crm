<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function manage(User $user, Client $client)
    {
        return $user->company_id === $client->company_id;
    }
}
