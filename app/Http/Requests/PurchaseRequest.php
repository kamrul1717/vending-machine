<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class PurchaseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'card_number' => ['required', 'string'],
            'machine_id' => ['required', 'integer', 'exists:machines,id'],
            'slot_number' => ['required', 'integer'],
            'product_price' => ['required', 'integer', 'min:1'],
            'timestamp' => ['sometimes', 'date'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     * 
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'card.required' => 'Employee card number is required',
            'machine_id.required' => 'Machine ID is required',
            'machine_id.exists' => 'Invalid machine ID',
            'slot_number.required' => 'Slot number is required',
            'product_price.required' => 'Product price is required',
            'product_price.min' => 'Product price must be at least 1 point',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator, 
            response()->json([
                'success' => false,
                'message' => $validator->errors->first(),
                'errors' => $validator->errors(),
            ], 422)
        );
    }

}
