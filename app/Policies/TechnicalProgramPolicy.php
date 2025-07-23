<?php

namespace App\Policies;

use App\Models\TechnicalProgram;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TechnicalProgramPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage system') ||
               $user->hasPermissionTo('view reports');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TechnicalProgram $technicalProgram): bool
    {
        return $user->hasPermissionTo('manage system') ||
               $user->hasPermissionTo('view reports');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage system');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TechnicalProgram $technicalProgram): bool
    {
        return $user->hasPermissionTo('manage system');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TechnicalProgram $technicalProgram): bool
    {
        // Only allow deletion if user has manage system permission
        // and the program has no associations
        if (!$user->hasPermissionTo('manage system')) {
            return false;
        }

        // Check if program has any associations
        $hasUsers = $technicalProgram->users()->count() > 0;
        $hasClassrooms = $technicalProgram->classrooms()->count() > 0;
        $hasLoans = $technicalProgram->toolLoans()->count() > 0;

        return !($hasUsers || $hasClassrooms || $hasLoans);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TechnicalProgram $technicalProgram): bool
    {
        return $user->hasPermissionTo('manage system');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TechnicalProgram $technicalProgram): bool
    {
        return $user->hasPermissionTo('manage system');
    }
}
