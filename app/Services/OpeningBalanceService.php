<?php

namespace App\Services;

use App\Models\Account\OpeningBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OpeningBalanceService
{
    /**
     * Store a new Opening Balance along with its related transactions and accounts.
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

            // Create transaction using polymorphic relationship
            $transaction = $openingBalance->transaction()->create([
                'url_address' => Str::random(60),
                'period_id' => $data['period_id'],
                'date' => $data['date'],
                'description' => 'Opening Balance Transaction',
                'user_id_create' => auth()->id(),
            ]);

            // Create accounts for both opening balance and transaction
            foreach ($data['accounts'] as $accountData) {
                // Create opening balance account
                $openingBalance->accounts()->create([
                    'account_id' => $accountData['account_id'],
                    'amount' => $accountData['amount'],
                    'debit_credit' => $accountData['debit_credit'],
                ]);

                // Create transaction account
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
     * Update an existing Opening Balance along with its related transactions and accounts.
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

            // Update or create transaction using polymorphic relationship
            $transaction = $openingBalance->transaction()->updateOrCreate(
                [],
                [
                    'period_id' => $data['period_id'],
                    'date' => $data['date'],
                    'description' => 'Opening Balance Transaction',
                    'user_id_update' => auth()->id(),
                ]
            );

            // Delete old accounts and recreate them
            $openingBalance->accounts()->delete();
            $transaction->entries()->delete();

            foreach ($data['accounts'] as $accountData) {
                // Create opening balance account
                $openingBalance->accounts()->create([
                    'account_id' => $accountData['account_id'],
                    'amount' => $accountData['amount'],
                    'debit_credit' => $accountData['debit_credit'],
                ]);

                // Create transaction account
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
