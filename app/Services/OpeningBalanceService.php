<?php

namespace App\Services;

use App\Models\Account\OpeningBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OpeningBalanceService
{
    public function store(array $data): OpeningBalance
    {
        return DB::transaction(function () use ($data) {
            // Create opening balance
            $openingBalance = OpeningBalance::create([
                'url_address' => Str::random(60),
                'period_id' => $data['period_id'],
                'user_id_create' => auth()->id(),
            ]);

            // Create transaction using polymorphic relationship
            $transaction = $openingBalance->transaction()->create([
                'url_address' => Str::random(60),
                'period_id' => $data['period_id'],
                'date' => $data['date'],
                'name' => $data['name'],
                'description' => 'Opening Balance Transaction',
                'user_id_create' => auth()->id(),
            ]);

            // Create accounts for both opening balance and transaction
            foreach ($data['accounts'] as $accountData) {
                $openingBalance->accounts()->create($accountData);
                $transaction->accounts()->create($accountData);
            }

            return $openingBalance;
        });
    }

    public function update(OpeningBalance $openingBalance, array $data): OpeningBalance
    {
        return DB::transaction(function () use ($openingBalance, $data) {
            // Update opening balance
            $openingBalance->update([
                'period_id' => $data['period_id'],
                'user_id_update' => auth()->id(),
            ]);

            // Update or create transaction using polymorphic relationship
            $transaction = $openingBalance->transaction()->updateOrCreate(
                [], // Empty array since we're using the relationship
                [
                    'period_id' => $data['period_id'],
                    'date' => $data['date'],
                    'name' => $data['name'],
                    'description' => 'Opening Balance Transaction',
                    'user_id_update' => auth()->id(),
                ]
            );

            // Delete old accounts and create new ones
            $openingBalance->accounts()->delete();
            $transaction->accounts()->delete();

            foreach ($data['accounts'] as $accountData) {
                $openingBalance->accounts()->create($accountData);
                $transaction->accounts()->create($accountData);
            }

            return $openingBalance;
        });
    }

    public function destroy(OpeningBalance $openingBalance): bool
    {
        return DB::transaction(function () use ($openingBalance) {
            // The transaction will be automatically deleted if you set up cascading deletes
            // Otherwise, delete it manually:
            $openingBalance->transaction()->delete();

            // Delete opening balance and its accounts
            $openingBalance->accounts()->delete();
            return $openingBalance->delete();
        });
    }

    public function findByUrlAddress(string $url_address)
    {
        return OpeningBalance::where('url_address', $url_address)->firstOrFail();
    }
}
