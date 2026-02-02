<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContactPolicy
{
    public function manage(User $user, Contact $contact)
    {
        return $user->company_id === $contact->company_id;
    }
}
