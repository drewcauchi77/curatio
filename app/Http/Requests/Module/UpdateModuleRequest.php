<?php

namespace App\Http\Requests\Module;

use App\Traits\Module\HasModuleValidation;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates module update requests.
 */
class UpdateModuleRequest extends FormRequest
{
    use HasModuleValidation;

    /**
     * Check if user is authorized to update the module.
     * 
     * @return bool True if user has update permission for the route module
     */
    public function authorize(): bool
    {
        $module = $this->route('module');
        return $this->user()->can('update', $module);
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
