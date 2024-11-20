<?php

namespace App\Models\Account;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'type', 'class'];

    // Many-to-many relationship with transactions (through transaction_account pivot table)
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_account')
            ->withPivot('amount', 'debit_credit', 'cost_center_id'); // Include cost center in pivot
    }

    // Method to check if account is an asset
    public function isAsset()
    {
        return $this->type === 'asset';
    }

    // Method to check if account is a liability
    public function isLiability()
    {
        return $this->type === 'liability';
    }

    // Method to check if account is an income account
    public function isIncome()
    {
        return $this->type === 'income';
    }

    // Method to check if account is an expense account
    public function isExpense()
    {
        return $this->type === 'expense';
    }
}
