<?php

namespace App\Models\Investor;

use App\Models\Account\Account;
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
        'name',
        'email',
        'phone',
        'address',
        'account_id',
        'user_id_create',
        'user_id_update',
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

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_id_update', 'id');
    }
    protected static function boot()
    {
        parent::boot();

        static::created(function ($investor) {
            $parent_account = Account::findorfail(18);

            $account = Account::create([
                'url_address' => $investor->get_random_string(60),
                'code' => '455' . $parent_account->children->count() + 1,
                'name' => 'مستثمر ' . $investor->name,
                'parent_id' => 18,
                'type' => 'liability', // Assuming investments are liabilities
                'class' => '4',
                'user_id_create' => auth()->id(),
            ]);

            $investor->update(['account_id' => $account->id]);
        });
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
