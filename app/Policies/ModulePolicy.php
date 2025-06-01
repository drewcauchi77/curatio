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
     * @param User $user The user to authorize
     * @return bool True if user belongs to a company
     */
    public function viewAny(User $user): bool
    {
        return (bool)$user->company_id;
    }

    /**
     * Check if user can view a specific module.
     * 
     * @param User $user The user to authorize
     * @param Module $module The module to view
     * @return bool True if user's company matches module's company
     */
    public function view(User $user, Module $module): bool
    {
        return $user->company_id === $module->company_id;
    }

    /**
     * Check if user can access module creation form.
     * 
     * @param User $user The user to authorize
     * @return bool True if user has company and is admin (role_id 1)
     */
    public function create(User $user): bool
    {
        return $user->company_id && $user->role_id === 1;
    }

    /**
     * Check if user can store a module with given attributes.
     * 
     * @param User $user The user to authorize
     * @param array $attributes Module attributes to validate
     * @return bool True if company matches and user is admin
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
     * @param User $user The user to authorize
     * @param Module $module The module to update
     * @return bool True if user's company matches and is admin
     */
    public function update(User $user, Module $module): bool
    {
        return ($user->company_id === $module->company_id) && $user->role_id === 1;
    }

    /**
     * Check if user can delete a module.
     * 
     * @param User $user The user to authorize
     * @param Module $module The module to delete
     * @return bool True if user can update (same permissions)
     */
    public function delete(User $user, Module $module): bool
    {
        return $this->update($user, $module);
    }

    // restore(User $user, Module $module): bool
    // forceDelete(User $user, Module $module): bool
}
