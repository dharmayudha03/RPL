<?php

namespace App\Policies;

use App\Models\CetakanNaik;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CetakanNaikPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'user', 'maintenance']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CetakanNaik $cetakanNaik): bool
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
    public function update(User $user, CetakanNaik $cetakanNaik): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CetakanNaik $cetakanNaik): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CetakanNaik $cetakanNaik): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CetakanNaik $cetakanNaik): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }
}
