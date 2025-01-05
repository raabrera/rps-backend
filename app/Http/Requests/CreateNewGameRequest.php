<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
class CreateNewGameRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'player1_id' => 'required|exists:players,id',
            'player2_id' => 'required|exists:players,id',
            'rounds' => 'required|integer|min:1',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // If validation fails, return the errors as a JSON response
        throw new ValidationException(
            $validator,
            response()->json([
                'errors' => $validator->errors()
            ], 422) // 422 Unprocessable Entity
        );
    }
}
