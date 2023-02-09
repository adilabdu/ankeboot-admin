<?php

namespace App\Http\Requests;

use App\Models\Book;
use App\Models\StoreBook;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ConsignmentReturnRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|array',
            'quantity.*.store_id' => 'required|exists:stores,id',
            'quantity.*.amount' => 'required|integer',
            'transaction_on' => 'required|date_format:d-m-Y',
            'receipt' => 'string',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Throw error if any `store_id` has no balance of `book_id`
            // Throw error if `amount` is over available balance in corresponding `store_id`
            // Throw error if sum of `amount` is over `max_returnable`

            $sum = 0;
            $max_returnable = Book::find($this->input('book_id'))->max_returnable();

            foreach ($this->input('quantity') as $store) {
                $sum += $store['amount'];

                $storeBook = StoreBook::where([
                    'store_id' => $store['store_id'],
                    'book_id' => $this->input('book_id'),
                ])->first();

                if ($storeBook?->balance < $store['amount']) {
                    $validator->errors()->add('quantity.*.store_id', 'Store ('.$storeBook->store->name.') does not have enough balance in stock');
                }
            }

            if ($sum <= 0) {
                $validator->errors()->add('quantity', 'The return amount must be greater than 0');
            }

            if ($sum > $max_returnable) {
                $validator->errors()->add('quantity', 'The total return amount exceeds the maximum allowed returnable amount ('.$max_returnable.')');
            }
        });
    }
}
