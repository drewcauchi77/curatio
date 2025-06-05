<?php

namespace App\Http\Requests\Module;

use App\Models\Module;
use App\Traits\HasModuleRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates module creation requests.
 */
class StoreModuleRequest extends FormRequest
{
    use HasModuleRules;

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

    /**
     * Get custom validation error messages.
     * 
     * @return array Custom messages for validation errors
     */
    public function messages(): array
    {
        return [
            'title.required' => 'errors.validation.required|title',
            'title.string' => 'errors.validation.format|title',
            'title.max' => 'errors.validation.max|title,:max',
            'title.min' => 'errors.validation.min|title,:min',
            'description.required' => 'errors.validation.required|description',
            'description.string' => 'errors.validation.format|description',
            'description.max' => 'errors.validation.max|description,:max',
            'description.min' => 'errors.validation.min|description,:min',
            'status_id.required' => 'errors.validation.required|status',
            'status_id.integer' => 'errors.validation.format|status',
        ];
    }
}
