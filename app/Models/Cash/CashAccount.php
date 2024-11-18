<?php

namespace App\Models\Cash;

use App\Models\Archive;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url_address',
        'user_id_create',
        'user_id_update',
        'name',
        'account_number',
        'balance',
    ];

    public function adjustBalance($amount, $type)
    {
        // Type can be 'credit' or 'debit'
        if ($type === 'credit') {
            $this->balance += $amount;
        } else if ($type === 'debit') {
            $this->balance -= $amount;
        }

        $this->save();
    }

    public function recalculateBalance()
    {
        // Sum all credits (transaction_type = 'credit')
        $creditSum = $this->transactions()->where('transaction_type', 'credit')->sum('transaction_amount');

        // Sum all debits (transaction_type = 'debit')
        $debitSum = $this->transactions()->where('transaction_type', 'debit')->sum('transaction_amount');

        // Set the new balance by subtracting debits from credits
        $this->balance = $creditSum - $debitSum;

        // Save the new balance
        $this->save();

        return $this->balance;
    }

    /**
     * Relationships
     */

    // Relationship with Transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'cash_account_id', 'id');
    }
    public function archives()
    {
        return $this->morphMany(Archive::class, 'archivable');
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
