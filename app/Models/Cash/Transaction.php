<?php

namespace App\Models\Cash;

use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'cash_account_id',
        'amount',
        'type',
        'description',
        'date',
        'transactionable_id',    // Polymorphic ID
        'transactionable_type',  // Polymorphic type (Payment, Expense, etc.)
    ];

    /**
     * Relationships
     */

    // Belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Polymorphic relationship
    public function transactionable()
    {
        return $this->morphTo();
    }

    // Belongs to a cash account
    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class, 'cash_account_id', 'id');
    }
}
