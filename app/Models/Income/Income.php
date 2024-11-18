<?php

namespace App\Models\Income;

use App\Models\Archive;
use App\Models\Cash\CashAccount;
use App\Models\Cash\Transaction;
use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url_address',

        'project_id',  // Add project_id to fillable
        'income_type_id', // Add income_type_id to fillable

        'amount',
        'description',
        'date',

        'approved', // New field
        'cash_account_id', // cash_account
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

    // Relationship with Transaction
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function income_type()
    {
        return $this->belongsTo(IncomeType::class);
    }
    public function cash_account()
    {
        return $this->belongsTo(CashAccount::class, 'cash_account_id', 'id');
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
