<?php

namespace App\Models\Project;

use App\Models\Investor\Investor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectInvestor extends Pivot
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'investor_id',
        'investment_amount',
    ];

    /**
     * Relationships
     */

    // Relationship to Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relationship to Investor
    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }
}
