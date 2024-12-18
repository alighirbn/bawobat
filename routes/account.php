
<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'account'], function () {

    //index
    Route::get('/', [AccountController::class, 'index'])->middleware(['auth', 'verified', 'permission:account-list'])->name('account.index');

    //create
    //Route::get('/soa', [AccountController::class, 'getSOA'])->middleware(['auth', 'verified', 'permission:account-list'])->name('account.soa');
    Route::post('/create', [AccountController::class, 'store'])->middleware(['auth', 'verified', 'permission:account-create'])->name('account.store');
});
