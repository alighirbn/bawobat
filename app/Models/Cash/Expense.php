<?php

namespace App\Models\Cash;

use App\Models\Archive;
use App\Models\Project\Project;
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
        'transaction_id',
        'project_id',  // Add project_id to fillable
        'expense_type_id', // Add expense_type_id to fillable
        'category',
        'amount',
        'description',
        'date',
    ];

    /**
     * Relationships
     */

    // Relationship with Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relationship with Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Relationship with ExpenseType
    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }
    public function archives()
    {
        return $this->morphMany(Archive::class, 'archivable');
    }
}
