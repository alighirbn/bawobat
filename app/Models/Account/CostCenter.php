<?php

namespace App\Models\Account;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'description'];

    // Many-to-many relationship with transactions (through transaction_account pivot table)
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_account')
            ->withPivot('amount', 'debit_credit');
    }
}
