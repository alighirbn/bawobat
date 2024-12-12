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

    protected $fillable = [
        'url_address',
        'period_id',
        'name',
        'date',
        'user_id_create',
        'user_id_update',
    ];

    public function accounts()
    {
        return $this->hasMany(OpeningBalanceAccount::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update');
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
