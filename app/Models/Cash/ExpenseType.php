<?php

namespace App\Models\Cash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relationships
     */

    // Relationship with Expense
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
