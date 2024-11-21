<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionAccount extends Model
{
    use HasFactory;

    protected $table = 'transaction_account'; // Define the table name explicitly

    protected $fillable = [
        'transaction_id',
        'account_id',
        'amount',
        'debit_credit',
        'cost_center_id',
    ];

    // Relationship with Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relationship with Account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Relationship with CostCenter (optional)
    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }
}
