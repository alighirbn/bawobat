<?php

namespace App\Models\Expense;

use App\Models\Account\Account;
use App\Models\Account\CostCenter;
use App\Models\Account\Transaction;
use App\Models\Archive;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url_address',

        'cost_center_id',

        'debit_account_id',
        'credit_account_id',

        'date',
        'amount',
        'description',

        'approved', // New field

        'user_id_create',
        'user_id_update',
    ];
    public function approve()
    {
        $this->approved = true;
        $this->save();
    }
    /**
     * Relationships
     */


    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
    public function cost_center()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id', 'id');
    }

    public function debit_account()
    {
        return $this->belongsTo(Account::class, 'debit_account_id', 'id');
    }
    public function credit_account()
    {
        return $this->belongsTo(Account::class, 'credit_account_id', 'id');
    }
    public function archives()
    {
        return $this->morphMany(Archive::class, 'archivable');
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
