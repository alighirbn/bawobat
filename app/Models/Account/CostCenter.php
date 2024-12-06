<?php

namespace App\Models\Account;

use App\Models\User;
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

    // Many-to-many relationship with transactions (through transaction_account pivot table)
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_account')
            ->withPivot('amount', 'debit_credit', 'cost_center_id') // Include cost_center_id if applicable
            ->withTimestamps(); // Track created_at and updated_at for pivot table
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

    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }
}
