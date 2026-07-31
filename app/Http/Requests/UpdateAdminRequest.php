<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
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
        $adminId = $this->route('admin') ? $this->route('admin')->id : null;
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $adminId,
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }
}
