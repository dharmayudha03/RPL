<?php

namespace App\Policies;

use App\Models\CetakanTurun;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CetakanTurunPolicy
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
    public function view(User $user, CetakanTurun $cetakanTurun): bool
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
    public function update(User $user, CetakanTurun $cetakanTurun): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CetakanTurun $cetakanTurun): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CetakanTurun $cetakanTurun): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CetakanTurun $cetakanTurun): bool
    {
        return $user->hasRole(['admin', 'maintenance']);
    }
}
