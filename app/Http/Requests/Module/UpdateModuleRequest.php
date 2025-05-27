<?php

namespace App\Http\Requests\Module;

use App\Traits\HasModuleRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateModuleRequest extends FormRequest
{
    use HasModuleRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $module = $this->route('module');
        return $this->user()->can('update', $module);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array<string, Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->moduleRules();
    }
}
