<?php

namespace App\Http\Requests;

use App\Enums\DepositType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateDailySaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:daily_sales,id',
            'update_submitted' => 'required|boolean',
            'cashier' => 'required|string',
            'deposits' => 'required',
            'deposits.*.amount' => 'required_with_all:deposits.*.type,deposits.*.deposited_on|numeric',
            'deposits.*.type' => [
                'required_with_all:deposits.*.amount,deposits.*.deposited_on',
                new Enum(DepositType::class),
            ],
            'deposits.*.deposited_on' => 'required_with_all:deposits.*.type,deposits.*.amount|date_format:d-m-Y',
            'sales_receipts' => 'required',
            'sales_receipts.*.is_manual' => 'required_with_all:sales_receipts.*.amount,sales_receipts.*.receipt|boolean',
            'sales_receipts.*.amount' => 'required_with_all:sales_receipts.*.is_manual,sales_receipts.*.receipt|numeric',
            'sales_receipts.*.receipt' => [
                'required_with_all:sales_receipts.*.amount,sales_receipts.*.is_manual',
                'string',
            ],
            'expenses.*.receipt' => 'required_with:expenses.*.amount',
            'expenses.*.amount' => 'required_with:expenses.*.receipt|numeric',
            'expenses.*.description' => 'string|nullable',
            'credits.*.receipt' => 'required_with_all:credits.*.client_name,credits.*.amount',
            'credits.*.client_name' => 'required_with_all:credits.*.receipt,credits.*.amount',
            'credits.*.amount' => 'required_with_all:credits.*.client_name,credits.*.receipt|numeric',
        ];
    }
}
