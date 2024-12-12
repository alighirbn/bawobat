<?php

use App\Http\Controllers\OpeningBalanceController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'opening_balance'], function () {

    //index
    Route::get('/', [OpeningBalanceController::class, 'index'])->middleware(['auth', 'verified', 'permission:opening_balance-list'])->name('opening_balance.index');

    //create
    Route::get('/create', [OpeningBalanceController::class, 'create'])->middleware(['auth', 'verified', 'permission:opening_balance-create'])->name('opening_balance.create');
    Route::post('/create', [OpeningBalanceController::class, 'store'])->middleware(['auth', 'verified', 'permission:opening_balance-create'])->name('opening_balance.store');

    //show
    Route::get('/show/{url_address}', [OpeningBalanceController::class, 'show'])->middleware(['auth', 'verified', 'permission:opening_balance-show'])->name('opening_balance.show');

    //update
    Route::get('/edit/{url_address}', [OpeningBalanceController::class, 'edit'])->middleware(['auth', 'verified', 'permission:opening_balance-update'])->name('opening_balance.edit');
    Route::patch('/update/{url_address}', [OpeningBalanceController::class, 'update'])->middleware(['auth', 'verified', 'permission:opening_balance-update'])->name('opening_balance.update');

    //delete
    Route::delete('/delete/{url_address}', [OpeningBalanceController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:opening_balance-delete'])->name('opening_balance.destroy');
});
