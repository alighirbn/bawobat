<?php

namespace App\Models\Income;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeType extends Model
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

    // Relationship with Income
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
