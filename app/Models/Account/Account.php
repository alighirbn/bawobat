<?php

namespace App\Models\Account;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_address',
        'code',
        'name',
        'type',
        'class',
        'parent_id',  // Added parent_id field
        'user_id_create',
        'user_id_update',
    ];

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

    // Relationship: Child accounts (the ones that belong to this parent)
    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    // Relationship: Parent account (if this account has a parent)
    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    // Relationship with the user who created the account
    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    // Relationship with the user who updated the account
    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }
}
