<?php

namespace App\Http\Requests\Module;

use App\Models\Module;
use App\Traits\HasModuleRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

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
     * Handle failed validation for both JSON and web requests.
     * 
     * @param Validator $validator The failed validator instance
     * @throws HttpResponseException For JSON requests
     * @throws ValidationException For web requests
     */
    // TODO
    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors(),
                ], 422)
            );
        }

        $firstError = $validator->errors()->first();

        $this->session()->flash('type', 'error');
        $this->session()->flash('title', 'error.validation');
        $this->session()->flash('message', $firstError);

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * Get custom validation error messages.
     * 
     * @return array Custom messages for validation errors
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The module title is required.',
            'title.max' => 'The module title cannot exceed :max characters.',
            'description.required' => 'The module description is required.',
            'description.max' => 'The module description cannot exceed :max characters.',
            'status_id.required' => 'Please select a status for the module.',
            'status_id.exists' => 'The selected status is invalid.',
        ];
    }
}
