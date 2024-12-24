<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentImport extends Model
{
    use HasFactory;

    protected $table = 'payment_imports';

    protected $fillable = [
        'payment_id',
        'transaction_id',
        'imported_at',
    ];
}
