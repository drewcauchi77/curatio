<?php

namespace App\Traits;

trait HasModuleRules
{
    protected function moduleRules(): array
    {
        // TODO rules with more uniqueness
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'status_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
