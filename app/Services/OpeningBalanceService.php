<?php

namespace App\Services;

use App\Models\Account\OpeningBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OpeningBalanceService
{
    /**
     * Store a new Opening Balance with related data.
     */
    public function store(array $data): OpeningBalance
    {
        return DB::transaction(function () use ($data) {
            // Create opening balance
            $openingBalance = OpeningBalance::create([
                'url_address' => Str::random(60),
                'period_id' => $data['period_id'],
                'name' => $data['name'],
                'date' => $data['date'],
                'user_id_create' => auth()->id(),
            ]);

            // Validate balance
            $this->validateBalance($data['accounts']);

            // Create transaction and associate with opening balance
            $transaction = $openingBalance->transaction()->create([
                'url_address' => Str::random(60),
                'period_id' => $data['period_id'],
                'date' => $data['date'],
                'description' => 'Opening Balance Transaction',
                'user_id_create' => auth()->id(),
            ]);

            // Create accounts for both opening balance and transaction
            foreach ($data['accounts'] as $accountData) {
                // Create opening balance account and associate with opening balance
                $openingBalance->accounts()->create([
                    'account_id' => $accountData['account_id'],
                    'amount' => $accountData['amount'],
                    'debit_credit' => $accountData['debit_credit'],
                ]);

                // Create transaction entry and associate with transaction
                $transaction->entries()->create([
                    'account_id' => $accountData['account_id'],
                    'amount' => $accountData['amount'],
                    'debit_credit' => $accountData['debit_credit'],
                    'cost_center_id' => $accountData['cost_center_id'] ?? null,
                ]);
            }

            return $openingBalance;
        });
    }

    /**
     * Update an existing Opening Balance with related data.
     */
    public function update(OpeningBalance $openingBalance, array $data): OpeningBalance
    {
        return DB::transaction(function () use ($openingBalance, $data) {

            // Update opening balance
            $openingBalance->update([
                'period_id' => $data['period_id'],
                'name' => $data['name'],
                'date' => $data['date'],
                'user_id_update' => auth()->id(),
            ]);

            // Validate balance
            $this->validateBalance($data['accounts']);

            // Get the transaction associated with the opening balance
            $transaction = $openingBalance->transaction()->first();

            if (!$transaction) {
                // If no transaction exists, create one and associate it with the opening balance
                $transaction = $openingBalance->transaction()->create([
                    'url_address' => Str::random(60),
                    'period_id' => $data['period_id'],
                    'date' => $data['date'],
                    'description' => 'Opening Balance Transaction',
                    'user_id_create' => auth()->id(),
                ]);
            } else {
                // Update the existing transaction
                $transaction->update([
                    'period_id' => $data['period_id'],
                    'date' => $data['date'],
                    'description' => 'Opening Balance Transaction',
                    'user_id_update' => auth()->id(),
                ]);
            }

            // Delete old accounts and transaction entries before recreating them
            $openingBalance->accounts()->delete();
            $transaction->entries()->delete();

            // Create accounts for both opening balance and transaction
            foreach ($data['accounts'] as $accountData) {
                // Create opening balance account and associate with opening balance
                $openingBalance->accounts()->create([
                    'account_id' => $accountData['account_id'],
                    'amount' => $accountData['amount'],
                    'debit_credit' => $accountData['debit_credit'],
                ]);

                // Create transaction entry and associate with transaction
                $transaction->entries()->create([
                    'account_id' => $accountData['account_id'],
                    'amount' => $accountData['amount'],
                    'debit_credit' => $accountData['debit_credit'],
                    'cost_center_id' => $accountData['cost_center_id'] ?? null,
                ]);
            }
            return $openingBalance;
        });
    }

    /**
     * Validate that debits equal credits.
     */
    public function validateBalance(array $accounts)
    {
        $debits = collect($accounts)->where('debit_credit', 'debit')->sum('amount');
        $credits = collect($accounts)->where('debit_credit', 'credit')->sum('amount');

        if ($debits !== $credits) {
            throw new \Exception('The total debits must equal the total credits.');
        }
    }

    /**
     * Delete an Opening Balance and its related data.
     */
    public function destroy(OpeningBalance $openingBalance): bool
    {
        return DB::transaction(function () use ($openingBalance) {
            // Delete related transaction and accounts
            $openingBalance->transaction()->delete();
            $openingBalance->accounts()->delete();

            // Delete opening balance itself
            return $openingBalance->delete();
        });
    }

    /**
     * Find an Opening Balance by its unique URL address.
     */
    public function findByUrlAddress(string $url_address)
    {
        return OpeningBalance::where('url_address', $url_address)->firstOrFail();
    }
}
