<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->shop->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return
            [
                'name' => 'required|string|max:255|min:3',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
                'count' => 'required|integer',
                'category_id' => 'required|exists:categories,id'
            ];
    }
}
