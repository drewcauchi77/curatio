<?php

namespace App\Traits\Module;

/**
 * Provides validation rules for module-related requests.
 */
trait HasModuleValidation
{
    /**
     * Get validation rules for module fields.
     * 
     * @return  array<string, list<string>>
     */
    protected function moduleRules(): array
    {
        // TODO rules with more uniqueness
        return [
            'title' => [
                'required',
                'string',
                'min:10',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
                'min:20',
                'max:1000',
            ],
            'status_id' => [
                'required',
                'integer',
            ],
        ];
    }

    /**
     * Get validation messages for module fields.
     * 
     * @return  array<string, string>
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
