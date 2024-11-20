<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'description',
        'transactionable_id',    // Polymorphic ID
        'transactionable_type',
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

    // Ensure transaction is balanced (debits == credits)
    public function isBalanced()
    {
        $debits = $this->accounts()->wherePivot('debit_credit', 'debit')->sum('pivot_amount');
        $credits = $this->accounts()->wherePivot('debit_credit', 'credit')->sum('pivot_amount');

        return $debits == $credits;
    }

    // Add an entry (debit/credit) to a transaction and link it to a cost center
    public function addEntry(Account $account, $amount, $debitCredit, CostCenter $costCenter = null)
    {
        $this->accounts()->attach($account->id, [
            'amount' => $amount,
            'debit_credit' => $debitCredit,
            'cost_center_id' => $costCenter ? $costCenter->id : null, // Attach cost center if provided
        ]);
    }
}
