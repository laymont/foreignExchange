<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property mixed $currency
 */
class UpdateCurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80', Rule::unique('currencies', 'name')->ignore($this->currency)],
            'acronym' => ['required', 'string', 'max:3', Rule::unique('currencies', 'acronym')->ignore($this->currency)],
            'symbol' => ['required', 'string', 'min:1', 'max:3', Rule::unique('currencies', 'symbol')->ignore($this->currency)],
            'is_active' => 'nullable|boolean',
        ];
    }
}
