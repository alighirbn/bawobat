<?php

use App\Http\Controllers\UniversalScannerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'attachment'], function () {
    Route::get('{model}/{id}/scan/create', [UniversalScannerController::class, 'scanCreate'])->name('scan.create');
    Route::post('{model}/{id}/scan/store', [UniversalScannerController::class, 'scanStore'])->name('scan.store');
    Route::get('{model}/{id}/archive/create', [UniversalScannerController::class, 'archiveCreate'])->name('archive.create');
    Route::post('{model}/{id}/archive/store', [UniversalScannerController::class, 'archiveStore'])->name('archive.store');

    Route::get('{model}/{id}/archive/show', [UniversalScannerController::class, 'archiveShow'])->name('archive.show');
});
