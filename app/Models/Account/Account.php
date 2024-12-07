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
        'parent_id',
        'user_id_create',
        'user_id_update',
    ];

    // Relationships
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_account')
            ->withPivot('amount', 'debit_credit', 'cost_center_id')
            ->withTimestamps();
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }

    // Query Scopes
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper Methods
    public function calculateBalance()
    {
        $transactions = $this->transactions()->sum('transaction_account.amount');
        $childrenBalance = $this->children->sum(function ($child) {
            return $child->calculateBalance();
        });

        return $transactions + $childrenBalance;
    }
}
