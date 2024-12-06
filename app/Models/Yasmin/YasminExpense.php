<?php

namespace App\Models\Yasmin;

use Illuminate\Database\Eloquent\Model;

class YasminExpense extends Model
{
    protected $connection = 'yasmin'; // Use the Yasmin database connection
    protected $table = 'expenses';
}
