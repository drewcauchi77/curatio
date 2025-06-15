<?php
// PHPSTAN CONFIRMED
namespace App\Policies;

use App\Models\Module;
use App\Models\User;

/**
 * Policy class for handling Module model authorization.
 */
class ModulePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return (bool)$user->company_id;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Module $module
     * @return bool
     */
    public function view(User $user, Module $module): bool
    {
        return $user->company_id === $module->company_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->company_id && (int)$user->role_id === 1;
    }

    /**
     * Determine whether the user can store models with given attributes.
     *
     * @param User $user
     * @param array<string, mixed> $attributes
     * @return bool
     */
    public function store(User $user, array $attributes): bool
    {
        return isset($attributes['company_id']) &&
            $attributes['company_id'] === $user->company_id &&
            (int)$user->role_id === 1;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Module $module
     * @return bool
     */
    public function update(User $user, Module $module): bool
    {
        return ($user->company_id === $module->company_id) && (int)$user->role_id === 1;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Module $module
     * @return bool
     */
    public function delete(User $user, Module $module): bool
    {
        return $this->update($user, $module);
    }

    // restore(User $user, Module $module): bool
    // forceDelete(User $user, Module $module): bool
}
