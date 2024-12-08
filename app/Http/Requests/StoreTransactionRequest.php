<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Customize as per your authorization logic
    }

    public function rules()
    {
        return [
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'period_id' => 'required',
            'debit.*.account_id' => 'required|exists:accounts,id',
            'debit.*.amount' => 'required|numeric|min:0.01',
            'debit.*.cost_center_id' => 'nullable|exists:costcenters,id',
            'credit.*.account_id' => 'required|exists:accounts,id',
            'credit.*.amount' => 'required|numeric|min:0.01',
            'credit.*.cost_center_id' => 'nullable|exists:costcenters,id',
        ];
    }
}
