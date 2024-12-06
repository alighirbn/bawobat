<?php

namespace App\Models\Yasmin;

use Illuminate\Database\Eloquent\Model;

class YasminIncome extends Model
{
    protected $connection = 'yasmin'; // Use the Yasmin database connection
    protected $table = 'incomes';
}
