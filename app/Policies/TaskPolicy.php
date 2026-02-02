<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Manage task (view / edit / update / delete)
     */
    public function manage(User $authUser, Task $task)
    {
        // Same company (CRITICAL)
        if ($authUser->company_id !== $task->company_id) {
            return false;
        }

        // Admin can manage all tasks
        if ($authUser->hasRole('admin')) {
            return true;
        }

        // Assigned individually
        if ($task->users->contains($authUser->id)) {
            return true;
        }

        // Assigned to team (role)
        if ($task->role && $authUser->roles->contains($task->role->id)) {
            return true;
        }

        return false;
    }
}
