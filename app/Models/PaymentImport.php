<?php

namespace App\Models;

use App\Models\Account\Transaction;
use App\Models\Yasmin\YasminPayment;
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

    // Define the relationship to the Payment model
    public function payment()
    {
        // Since the Payment model is in a different database ('yasmin'), specify its namespace.
        return $this->setConnection('yasmin')->belongsTo(YasminPayment::class, 'payment_id', 'id');
    }

    // Define the relationship to the Transaction model
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
