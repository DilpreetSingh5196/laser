<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firm_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'mobile_number' => 'required|numeric|unique:clients,mobile_number',
            'email' => 'required|email|max:255|unique:clients,email',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'boolean',
        ];
    }
}
