<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionAccount extends Model
{
    use HasFactory;

    protected $table = 'transaction_account';

    protected $fillable = [
        'transaction_id',
        'account_id',
        'amount',
        'debit_credit',
        'cost_center_id',
    ];

    const DEBIT = 'debit';
    const CREDIT = 'credit';

    // Relationships
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    // Query Scopes
    public function scopeDebits($query)
    {
        return $query->where('debit_credit', self::DEBIT);
    }

    public function scopeCredits($query)
    {
        return $query->where('debit_credit', self::CREDIT);
    }
}
