<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningBalanceAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'opening_balance_id',
        'account_id',
        'debit',
        'credit',
    ];

    public function opening_balance()
    {
        return $this->belongsTo(OpeningBalance::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
