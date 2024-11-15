<?php

namespace App\Models\Project;

use App\Models\Cash\Transaction;
use App\Models\Investor\Investor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
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
        'name',
        'description',
        'budget',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Relationships
     */

    // Relationship with ProjectStage
    public function stages()
    {
        return $this->hasMany(ProjectStage::class);
    }

    // Relationship with Investor (many-to-many)
    public function investors()
    {
        return $this->belongsToMany(Investor::class, 'project_investors')->withPivot('investment_amount');
    }

    // Relationship with Transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
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
