
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

    //archive
    Route::get('/archiveshow/{url_address}', [ProjectController::class, 'archiveshow'])->middleware(['auth', 'verified', 'permission:project-archiveshow'])->name('project.archiveshow');
    Route::get('/archive/{url_address}', [ProjectController::class, 'archivecreate'])->middleware(['auth', 'verified', 'permission:project-archive'])->name('project.archivecreate');
    Route::post('/archive/{url_address}', [ProjectController::class, 'archivestore'])->middleware(['auth', 'verified', 'permission:project-archive'])->name('project.archivestore');

    //scan
    Route::get('/scan/{url_address}', [ProjectController::class, 'scancreate'])->middleware(['auth', 'verified', 'permission:project-archive'])->name('project.scancreate');
    Route::post('/scan', [ProjectController::class, 'scanstore'])->middleware(['auth', 'verified', 'permission:project-archive'])->name('project.scanstore');
});
