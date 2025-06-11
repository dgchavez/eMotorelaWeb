<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        $this->updateFullName($user);
    }

    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        $this->updateFullName($user);
    }

    /**
     * Update the full name based on name components
     */
    private function updateFullName(User $user): void
    {
        $nameParts = array_filter([
            $user->first_name,
            $user->middle_name,
            $user->last_name,
            $user->suffix
        ]);

        $user->name = implode(' ', $nameParts);
    }
} 