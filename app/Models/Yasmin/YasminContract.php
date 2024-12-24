<?php

namespace App\Models\Yasmin;

use Illuminate\Database\Eloquent\Model;

class YasminContract extends Model
{
    protected $connection = 'yasmin'; // Use the Yasmin database connection
    protected $table = 'contracts';

    public function payments()
    {
        return $this->hasMany(YasminPayment::class, 'payment_contract_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(YasminCustomer::class, 'contract_customer_id', 'id');
    }

    public function building()
    {
        return $this->belongsTo(YasminBuilding::class, 'contract_building_id', 'id');
    }
}
