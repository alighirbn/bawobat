<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpeningBalanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period_id' => 'required|exists:periods,id',
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'accounts' => 'required|array',
            'accounts.*.account_id' => 'required|exists:accounts,id',
            'accounts.*.debit' => 'nullable|numeric|min:0',
            'accounts.*.credit' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'accounts.*.account_id.required' => 'The account field is required.',
            'accounts.*.account_id.exists' => 'The selected account is invalid.',
            'accounts.*.debit.numeric' => 'The debit amount must be a number.',
            'accounts.*.debit.min' => 'The debit amount must be at least 0.',
            'accounts.*.credit.numeric' => 'The credit amount must be a number.',
            'accounts.*.credit.min' => 'The credit amount must be at least 0.',
        ];
    }
}
