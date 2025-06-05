<?php

namespace App\Traits;

/**
 * Provides validation rules for module-related requests.
 */
trait HasModuleRules
{
    /**
     * Get validation rules for module fields.
     * 
     * @return array<string, list<string>> Validation rules for title, description, and status_id
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
}
