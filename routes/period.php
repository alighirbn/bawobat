
<?php

use App\Http\Controllers\PeriodController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'period'], function () {

    //index
    Route::get('/', [PeriodController::class, 'index'])->middleware(['auth', 'verified', 'permission:period-list'])->name('period.index');

    //create
    Route::get('/create', [PeriodController::class, 'create'])->middleware(['auth', 'verified', 'permission:period-create'])->name('period.create');
    Route::post('/create', [PeriodController::class, 'store'])->middleware(['auth', 'verified', 'permission:period-create'])->name('period.store');

    //show
    Route::get('/show/{url_address}', [PeriodController::class, 'show'])->middleware(['auth', 'verified', 'permission:period-show'])->name('period.show');

    //update
    Route::get('/edit/{url_address}', [PeriodController::class, 'edit'])->middleware(['auth', 'verified', 'permission:period-update'])->name('period.edit');
    Route::patch('/update/{url_address}', [PeriodController::class, 'update'])->middleware(['auth', 'verified', 'permission:period-update'])->name('period.update');

    //delete
    Route::delete('/delete/{url_address}', [PeriodController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:period-delete'])->name('period.destroy');
});
