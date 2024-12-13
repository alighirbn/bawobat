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
            'url_address' => 'nullable|string|max:60',
            'accounts' => 'required|array',
            'accounts.*.account_id' => 'required|exists:accounts,id',
            'accounts.*.debit_credit' => 'required|in:debit,credit',
            'accounts.*.amount' => 'nullable|numeric|min:0',
            // Custom validation for balancing debits and credits
            'accounts' => ['required', function ($attribute, $value, $fail) {
                $debits = collect($value)->where('debit_credit', 'debit')->sum('amount');
                $credits = collect($value)->where('debit_credit', 'credit')->sum('amount');

                if ($debits !== $credits) {
                    $fail('The total of debits must equal the total of credits.');
                }
            }],
        ];
    }
}
