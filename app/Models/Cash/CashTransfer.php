<?php

namespace App\Models\Cash;

use App\Models\Cash\CashAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransfer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url_address',
        'user_id_create',
        'user_id_update',
        'from_account_id',  // ID of the source CashAccount
        'to_account_id',    // ID of the target CashAccount
        'amount',            // Amount transferred
        'description',       // Optional description for the transfer
        'transaction_date',  // Date of the transfer
    ];

    /**
     * Relationships
     */

    // Relationship with from CashAccount
    public function fromAccount()
    {
        return $this->belongsTo(CashAccount::class, 'from_account_id');
    }

    // Relationship with to CashAccount
    public function toAccount()
    {
        return $this->belongsTo(CashAccount::class, 'to_account_id');
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
