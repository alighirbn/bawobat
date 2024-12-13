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
            'url_address' => 'nullable|url|max:255',  // Added validation for URL
            'accounts' => 'required|array',
            'accounts.*.account_id' => 'required|exists:accounts,id',
            'accounts.*.debit_credit' => 'required|in:debit,credit', // Ensure debit_credit is present and valid
            'accounts.*.amount' => 'nullable|numeric|min:0', // Validation for amount field
        ];
    }
}
