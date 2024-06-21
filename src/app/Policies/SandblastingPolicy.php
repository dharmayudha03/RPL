<?php

namespace App\Policies;

use App\Models\Sandblasting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SandblastingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'maintenance', 'user']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sandblasting $sandblasting): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sandblasting $sandblasting): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sandblasting $sandblasting): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sandblasting $sandblasting): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sandblasting $sandblasting): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }
}
