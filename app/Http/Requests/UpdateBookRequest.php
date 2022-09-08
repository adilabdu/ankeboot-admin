<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:books,id',
            'code' => 'required|unique:books,code',
            'type' => [
                'required',
                new Enum(PurchaseType::class)
            ],
            'title' => 'required|string',
            'author' => 'string|nullable',
            'category' => 'string',
            'supplier_id' => 'exists:suppliers,id'
        ];
    }
}
