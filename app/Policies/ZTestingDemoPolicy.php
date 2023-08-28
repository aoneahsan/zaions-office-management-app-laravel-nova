<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ZTestingDemo;
use Illuminate\Auth\Access\Response;

class ZTestingDemoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ZTestingDemo $zTestingDemo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ZTestingDemo $zTestingDemo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ZTestingDemo $zTestingDemo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ZTestingDemo $zTestingDemo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ZTestingDemo $zTestingDemo): bool
    {
        return false;
    }
}
