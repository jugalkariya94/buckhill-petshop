<?php

namespace App\Http\Requests;

class UpdateUserRequest extends APIRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'password' => 'sometimes|string|min:8|confirmed',
            'phone_number' => 'required|string|max:15',
            'is_marketing' => 'sometimes|boolean',
            'avatar' => 'sometimes|string'
        ];
    }
}
