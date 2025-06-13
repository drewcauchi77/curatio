<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;

/**
 * Authorization policy for module operations.
 */
class ModulePolicy
{
    /**
     * Check if user can view module listings.
     * 
     * @param   User $user
     * @return  bool
     */
    public function viewAny(User $user): bool
    {
        return (bool)$user->company_id;
    }

    /**
     * Check if user can view a specific module.
     * 
     * @param   User $user
     * @param   Module $module
     * @return  bool
     */
    public function view(User $user, Module $module): bool
    {
        return $user->company_id === $module->company_id;
    }

    /**
     * Check if user can access module creation form.
     * 
     * @param   User $user
     * @return  bool
     */
    public function create(User $user): bool
    {
        return $user->company_id && $user->role_id === 1;
    }

    /**
     * Check if user can store a module with given attributes.
     * 
     * @param   User $user
     * @param   array $attributes
     * @return  bool
     */
    public function store(User $user, array $attributes): bool
    {
        return isset($attributes['company_id']) &&
            $attributes['company_id'] === $user->company_id &&
            $user->role_id === 1;
    }

    /**
     * Check if user can update a module.
     * 
     * @param   User $user
     * @param   Module $module
     * @return  bool
     */
    public function update(User $user, Module $module): bool
    {
        return ($user->company_id === $module->company_id) && $user->role_id === 1;
    }

    /**
     * Check if user can delete a module.
     * 
     * @param   User $user
     * @param   Module $module
     * @return  bool
     */
    public function delete(User $user, Module $module): bool
    {
        return $this->update($user, $module);
    }

    // restore(User $user, Module $module): bool
    // forceDelete(User $user, Module $module): bool
}
