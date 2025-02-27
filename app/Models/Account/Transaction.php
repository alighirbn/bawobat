<?php

namespace App\Models\Account;

use App\Models\Archive;
use App\Models\PaymentImport;
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
        'period_id',
        'user_id_create',
        'user_id_update',
    ];
    protected $casts = [
        'date' => 'datetime', // Ensure that the 'date' field is a datetime type
    ];
    // Polymorphic Relationship
    public function transactionable()
    {
        return $this->morphTo();
    }

    // Relationships
    public function accounts()
    {
        return $this->belongsToMany(Account::class, 'transaction_account')
            ->withPivot('amount', 'debit_credit', 'cost_center_id')
            ->withTimestamps();
    }

    public function entries()
    {
        return $this->hasMany(TransactionAccount::class, 'transaction_id');
    }

    // Helper Methods
    public function isBalanced()
    {
        $totals = $this->entries()
            ->selectRaw('SUM(CASE WHEN debit_credit = "debit" THEN amount ELSE 0 END) as total_debits, 
                         SUM(CASE WHEN debit_credit = "credit" THEN amount ELSE 0 END) as total_credits')
            ->first();

        return $totals->total_debits == $totals->total_credits;
    }

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

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
    public function archives()
    {
        return $this->morphMany(Archive::class, 'archivable');
    }

    // Relationship to PaymentImport
    public function paymentImports()
    {
        return $this->hasMany(PaymentImport::class, 'transaction_id', 'id');
    }
}
