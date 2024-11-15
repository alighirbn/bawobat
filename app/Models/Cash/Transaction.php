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
    ];

    /**
     * Relationships
     */

    // Belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Belongs to a cash account
    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }
}
