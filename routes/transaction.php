
<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'transaction'], function () {

    //index
    Route::get('/', [TransactionController::class, 'index'])->middleware(['auth', 'verified', 'permission:transaction-list'])->name('transaction.index');

    //create
    Route::get('/create', [TransactionController::class, 'create'])->middleware(['auth', 'verified', 'permission:transaction-create'])->name('transaction.create');
    Route::post('/create', [TransactionController::class, 'store'])->middleware(['auth', 'verified', 'permission:transaction-create'])->name('transaction.store');

    //show
    Route::get('/show/{url_address}', [TransactionController::class, 'show'])->middleware(['auth', 'verified', 'permission:transaction-show'])->name('transaction.show');

    //update
    Route::get('/edit/{url_address}', [TransactionController::class, 'edit'])->middleware(['auth', 'verified', 'permission:transaction-update'])->name('transaction.edit');
    Route::patch('/update/{url_address}', [TransactionController::class, 'update'])->middleware(['auth', 'verified', 'permission:transaction-update'])->name('transaction.update');

    //delete
    Route::delete('/delete/{url_address}', [TransactionController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:transaction-delete'])->name('transaction.destroy');
});
