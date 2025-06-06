<?php

namespace App\Http\Requests\Module;

use App\Models\Module;
use App\Traits\Module\HasModuleValidation;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates module creation requests.
 */
class StoreModuleRequest extends FormRequest
{
    use HasModuleValidation;

    /**
     * Check if user is authorized to create modules.
     * 
     * @return bool True if user has create permission
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Module::class);
    }

    /**
     * Get validation rules for the request.
     * 
     * @return array Module validation rules from trait
     */
    public function rules(): array
    {
        return $this->moduleRules();
    }
}
