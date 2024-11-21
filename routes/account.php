
<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'account'], function () {

    //index
    Route::get('/', [AccountController::class, 'index'])->middleware(['auth', 'verified', 'permission:account-list'])->name('account.index');

    //create
    Route::get('/create', [AccountController::class, 'create'])->middleware(['auth', 'verified', 'permission:account-create'])->name('account.create');
    Route::post('/create', [AccountController::class, 'store'])->middleware(['auth', 'verified', 'permission:account-create'])->name('account.store');

    //show
    Route::get('/show/{url_address}', [AccountController::class, 'show'])->middleware(['auth', 'verified', 'permission:account-show'])->name('account.show');

    //update
    Route::get('/edit/{url_address}', [AccountController::class, 'edit'])->middleware(['auth', 'verified', 'permission:account-update'])->name('account.edit');
    Route::patch('/update/{url_address}', [AccountController::class, 'update'])->middleware(['auth', 'verified', 'permission:account-update'])->name('account.update');

    //delete
    Route::delete('/delete/{url_address}', [AccountController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:account-delete'])->name('account.destroy');
});
