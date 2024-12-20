<?php

namespace App\Models\Project;

use App\Models\Account\CostCenter;
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

    public function cost_center()
    {
        return $this->belongsTo(CostCenter::class);
    }

    // Relationship with Investor (many-to-many)
    public function investors()
    {
        return $this->belongsToMany(Investor::class, 'project_investors')->withPivot('investment_amount');
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
    function get_random_string($length)
    {
        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $text = "";
        $length = rand(22, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, 61);
            $text .= $array[$random];
        }
        return $text;
    }
}
