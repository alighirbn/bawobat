<?php

namespace App\Models\Account;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningBalance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'opening_balances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url_address',
        'account_id',
        'period_id',
        'balance',
        'user_id_create',
        'user_id_update',
    ];

    /**
     * Relationship: The account associated with this opening balance.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Relationship: The period associated with this opening balance.
     */
    public function period()
    {
        return $this->belongsTo(Period::class);
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
