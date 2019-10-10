<?php

namespace App\Http\Requests;

use App\Wallet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WalletRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'wallet_id' => [
                'required',
                'numeric'
            ],
            'transaction_type' => [
                'required',
                Rule::in([Wallet::TYPE_DEBIT, Wallet::TYPE_CREDIT])
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0'
            ],
            'currency' => [
                'required',
                Rule::in([Wallet::CUR_RUB, Wallet::CUR_USD])
            ]
        ];
    }
}
