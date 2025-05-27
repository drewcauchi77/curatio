<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ModulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return (bool)$user->company_id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Module $module): bool
    {
        return $user->company_id === $module->company_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Check if the user has a company id and appropriate role.
        return $user->company_id && $user->role_id === 1;
    }

    /**
     * Determine whether the user can store a new module with specific attributes.
     */
    public function store(User $user, array $attributes): bool
    {
        // Check if the company_id in the attributes matches the user's company_id and the user has the appropriate role.
        return isset($attributes['company_id']) && $attributes['company_id'] === $user->company_id && $user->role_id === 1;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Module $module): bool
    {
        return ($user->company_id === $module->company_id) && $user->role_id === 1;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Module $module): bool
    {
        return $this->update($user, $module);
    }

    // restore(User $user, Module $module): bool
    // forceDelete(User $user, Module $module): bool
}
