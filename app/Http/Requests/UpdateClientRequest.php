<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
        $clientId = $this->route('client') ? $this->route('client')->id : null;
        return [
            'firm_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'mobile_number' => 'required|numeric|unique:clients,mobile_number,' . $clientId,
            'email' => 'required|email|max:255|unique:clients,email,' . $clientId,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'boolean',
        ];
    }
}
