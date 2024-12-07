<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;

    protected $table = 'costcenters';

    protected $fillable = [
        'url_address',
        'code',
        'name',
        'description',
        'user_id_create',
        'user_id_update',
    ];

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_account')
            ->withPivot('amount', 'debit_credit', 'cost_center_id')
            ->withTimestamps();
    }

    public function calculateRunningBalance()
    {
        $transactions = $this->transactions()->orderBy('date')->get();

        $balance = 0;
        $runningBalances = [];

        foreach ($transactions as $transaction) {
            $amount = $transaction->pivot->amount;
            $balance += ($transaction->pivot->debit_credit === 'debit') ? -$amount : $amount;

            $runningBalances[] = [
                'transaction' => $transaction,
                'running_balance' => $balance,
            ];
        }

        return $runningBalances;
    }
}
