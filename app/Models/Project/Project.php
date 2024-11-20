<?php

namespace App\Models\Project;

use App\Models\Account\Transaction;
use App\Models\Archive;
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
        'name',
        'description',

        'cost_center_id',

        'budget',
        'start_date',
        'end_date',
        'status',
        'user_id_create',
        'user_id_update',
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
