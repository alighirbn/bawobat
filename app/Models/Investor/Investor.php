<?php

namespace App\Models\Investor;

use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
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
        'email',
        'phone',
        'address',
    ];

    /**
     * Relationships
     */

    // Relationship with Project (many-to-many)
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_investors')->withPivot('investment_amount')->withTimestamps();
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
