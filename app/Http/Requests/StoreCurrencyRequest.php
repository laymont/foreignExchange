<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 *  @OA\Schema(
 *      title="StoreCurrencyRequest",
 *      description="Store Currency Request body data",
 *      type="object",
 *      required={"name", "acronym","symbol","is_active","last_value","slug"}
 * )
 */
class StoreCurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80', Rule::unique('currencies', 'name')],
            'acronym' => ['required', 'string', 'max:3', Rule::unique('currencies', 'acronym')],
            'symbol' => ['required', 'string', 'min:1', 'max:3', Rule::unique('currencies', 'symbol')],
            'is_active' => ['nullable', 'boolean'],
            'slug' => ['nullable','boolean', Rule::unique('currencies', 'slug')],
        ];
    }
}
