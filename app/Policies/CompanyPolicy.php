<?php
// PHPSTAN CONFIRMED
namespace App\Policies;

use App\Models\Company;
use App\Models\User;

/**
 * Policy class for handling Company model authorization.
 */
class CompanyPolicy
{
    /**
     * Determine whether the user can view company modules.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function viewCompanyModules(User $user, Company $company): bool
    {
        return $user->company_id === $company->id;
    }

    /**
     * Determine whether the user can create company modules.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function createCompanyModule(User $user, Company $company): bool
    {
        return $user->company_id === $company->id && (int)$user->role_id === 1;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function view(User $user, Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function update(User $user, Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function delete(User $user, Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function restore(User $user, Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return false;
    }
}
