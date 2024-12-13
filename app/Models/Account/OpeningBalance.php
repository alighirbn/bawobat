<?php

namespace App\Models\Account;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'period_id',
        'url_address',
        'user_id_create',
        'user_id_update',
    ];

    /**
     * Relationships
     */
    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id_create');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'user_id_update');
    }

    public function accounts()
    {
        return $this->hasMany(OpeningBalanceAccount::class);
    }

    public function transaction()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
