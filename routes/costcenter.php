
<?php

use App\Http\Controllers\CostCenterController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'costcenter'], function () {

    //index
    Route::get('/', [CostCenterController::class, 'index'])->middleware(['auth', 'verified', 'permission:costcenter-list'])->name('costcenter.index');

    //create
    Route::get('/create', [CostCenterController::class, 'create'])->middleware(['auth', 'verified', 'permission:costcenter-create'])->name('costcenter.create');
    Route::post('/create', [CostCenterController::class, 'store'])->middleware(['auth', 'verified', 'permission:costcenter-create'])->name('costcenter.store');

    //show
    Route::get('/show/{url_address}', [CostCenterController::class, 'show'])->middleware(['auth', 'verified', 'permission:costcenter-show'])->name('costcenter.show');

    //update
    Route::get('/edit/{url_address}', [CostCenterController::class, 'edit'])->middleware(['auth', 'verified', 'permission:costcenter-update'])->name('costcenter.edit');
    Route::patch('/update/{url_address}', [CostCenterController::class, 'update'])->middleware(['auth', 'verified', 'permission:costcenter-update'])->name('costcenter.update');

    //delete
    Route::delete('/delete/{url_address}', [CostCenterController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:costcenter-delete'])->name('costcenter.destroy');
});
