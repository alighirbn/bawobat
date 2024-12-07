<?php

namespace App\Models\Account;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetainedEarning extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'retained_earnings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'period_id',
        'beginning_balance',
        'net_income',
        'dividends',
        'ending_balance',
        'user_id_create',
        'user_id_update',
    ];

    /**
     * Relationship: The period associated with the retained earnings.
     */
    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    /**
     * Relationship: The user who created this retained earnings record.
     */
    public function user_create()
    {
        return $this->belongsTo(User::class, 'user_id_create', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }
}
