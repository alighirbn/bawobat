<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_address',
        'date',
        'description',
        'transactionable_id',
        'transactionable_type',

        'user_id_create',
        'user_id_update',
    ];

    // Polymorphic relationship
    public function transactionable()
    {
        return $this->morphTo();
    }

    // Many-to-many relationship with accounts (debits and credits)
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'transaction_account')
            ->withPivot('amount', 'debit_credit', 'cost_center_id'); // Include cost center in pivot
    }

    // Direct relationship with transaction_account pivot table
    public function entries()
    {
        return $this->hasMany(TransactionAccount::class, 'transaction_id');
    }

    // Ensure transaction is balanced (debits == credits)
    public function isBalanced()
    {
        $debits = $this->accounts()
            ->where('transaction_account.debit_credit', 'debit')
            ->sum('transaction_account.amount');

        $credits = $this->accounts()
            ->where('transaction_account.debit_credit', 'credit')
            ->sum('transaction_account.amount');

        return $debits == $credits;
    }

    // Add an entry (debit/credit) to a transaction and link it to a cost center
    public function addEntry(Account $account, $amount, $debitCredit, CostCenter $costCenter = null)
    {
        $this->accounts()->attach($account->id, [
            'amount' => $amount,
            'debit_credit' => $debitCredit,
            'cost_center_id' => $costCenter ? $costCenter->id : null,
        ]);
    }
    public function debits()
    {
        return $this->hasMany(TransactionAccount::class)->where('debit_credit', 'debit');
    }

    public function credits()
    {
        return $this->hasMany(TransactionAccount::class)->where('debit_credit', 'credit');
    }
}
