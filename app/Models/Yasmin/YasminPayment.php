<?php

namespace App\Models\Yasmin;

use Illuminate\Database\Eloquent\Model;

class YasminPayment extends Model
{
    protected $connection = 'yasmin'; // Use the Yasmin database connection
    protected $table = 'payments';

    protected $fillable = [
        'url_address',
        'payment_amount',
        'payment_date',
        'payment_note',
        'approved', // New field

        'payment_contract_id',
        'contract_installment_id',
        'cash_account_id',

        'user_id_create',
        'user_id_update',
    ];

    public function contract()
    {
        return $this->belongsTo(YasminContract::class, 'payment_contract_id', 'id');
    }
}
