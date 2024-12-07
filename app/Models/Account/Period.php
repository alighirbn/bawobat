<?php

namespace App\Models\Account;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $table = 'periods';

    protected $fillable = [
        'url_address',
        'name',
        'start_date',
        'end_date',
        'is_active',
        'user_id_create',
        'user_id_update',
    ];

    // Ensure that start_date and end_date are cast to Carbon instances
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get all transactions for the period.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'period_id');
    }

    /**
     * Check if the period is active.
     *
     * @return bool
     */
    public function isActive()
    {
        $now = now();
        return $this->start_date <= $now && $this->end_date >= $now;
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
