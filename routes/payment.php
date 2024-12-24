
<?php

use App\Http\Controllers\PaymentImportController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'payment'], function () {

    Route::get('/import', [PaymentImportController::class, 'showPaymentsForImport'])->name('payments.import');
    Route::post('/import', [PaymentImportController::class, 'importPayments'])->name('payments.import.post');
    Route::get('/imported', [PaymentImportController::class, 'showImportedPayments'])->name('payments.imported');
});
