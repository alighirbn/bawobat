
<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'project'], function () {

    //index
    Route::get('/', [ProjectController::class, 'index'])->middleware(['auth', 'verified', 'permission:project-list'])->name('project.index');

    //create
    Route::get('/create', [ProjectController::class, 'create'])->middleware(['auth', 'verified', 'permission:project-create'])->name('project.create');
    Route::post('/create', [ProjectController::class, 'store'])->middleware(['auth', 'verified', 'permission:project-create'])->name('project.store');

    //show
    Route::get('/show/{url_address}', [ProjectController::class, 'show'])->middleware(['auth', 'verified', 'permission:project-show'])->name('project.show');

    //update
    Route::get('/edit/{url_address}', [ProjectController::class, 'edit'])->middleware(['auth', 'verified', 'permission:project-update'])->name('project.edit');
    Route::patch('/update/{url_address}', [ProjectController::class, 'update'])->middleware(['auth', 'verified', 'permission:project-update'])->name('project.update');

    //delete

    Route::delete('/delete/{url_address}', [ProjectController::class, 'destroy'])->middleware(['auth', 'verified', 'permission:project-delete'])->name('project.destroy');

    // add
    Route::post('/{projectId}/add-investor', [ProjectController::class, 'addInvestor'])->middleware(['auth', 'verified', 'permission:project-addInvestor'])->name('project.addInvestor');
    Route::post('/{projectId}/add-stage', [ProjectController::class, 'addStage'])->middleware(['auth', 'verified', 'permission:project-addStage'])->name('project.addStage');
});
