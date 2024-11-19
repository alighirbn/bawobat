<?php

namespace App\Models\Cash;

use App\Models\Archive;
use App\Models\Project\Project;
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

        'project_id',  // Add project_id to fillable
        'expense_type_id', // Add expense_type_id to fillable

        'amount',
        'description',
        'date',


        'cash_account_id', // cash_account
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

    // Relationship with Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relationship with Transaction
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    // Relationship with ExpenseType
    public function expense_type()
    {
        return $this->belongsTo(ExpenseType::class);
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
