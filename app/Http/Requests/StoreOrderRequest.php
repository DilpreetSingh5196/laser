<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'items' => 'required|array|min:1',
            'items.*.item_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.length' => 'nullable|numeric|min:0',
            'items.*.breadth' => 'nullable|numeric|min:0',
            'items.*.unit' => 'required|in:inch,cm',
            'items.*.description' => 'nullable|string',
        ];
    }
}
