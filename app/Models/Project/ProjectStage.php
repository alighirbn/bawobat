<?php

namespace App\Models\Project;

use App\Models\Archive;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStage extends Model
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
        'project_id',
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
    ];

    /**
     * Relationships
     */

    // Belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class);
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
