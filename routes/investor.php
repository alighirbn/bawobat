
<?php

use App\Http\Controllers\InvestorController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'investor'], function () {

    //index
    Route::get('/', [InvestorController::class, 'index'])->middleware(['auth', 'verified', 'permission:investor-list'])->name('investor.index');

    //create
    Route::get('/create', [InvestorController::class, 'create'])->middleware(['auth', 'verified', 'permission:investor-create'])->name('investor.create');
    Route::post('/create', [InvestorController::class, 'store'])->middleware(['auth', 'verified', 'permission:investor-create'])->name('investor.store');

    //show
    Route::get('/show/{url_address}', [InvestorController::class, 'show'])->middleware(['auth', 'verified', 'permission:investor-show'])->name('investor.show');

    //update
    Route::get('/edit/{url_address}', [InvestorController::class, 'edit'])->middleware(['auth', 'verified', 'permission:investor-update'])->name('investor.edit');
    Route::patch('/update/{url_address}', [InvestorController::class, 'update'])->middleware(['auth', 'verified', 'permission:investor-update'])->name('investor.update');

    //delete
    Route::delete('/delete/{url_address}', [InvestorController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:investor-delete'])->name('investor.destroy');
});
