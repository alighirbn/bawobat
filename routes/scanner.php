<?php

use App\Http\Controllers\UniversalScannerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'attachment'], function () {
    Route::get('{model}/{id}/{url_address}/scan/create', [UniversalScannerController::class, 'scanCreate'])->name('scan.create');
    Route::post('{model}/{id}/scan/store', [UniversalScannerController::class, 'scanStore'])->name('scan.store');
    Route::get('{model}/{id}/{url_address}/archive/create', [UniversalScannerController::class, 'archiveCreate'])->name('archive.create');
    Route::post('{model}/{id}/archive/store', [UniversalScannerController::class, 'archiveStore'])->name('archive.store');

    Route::get('{model}/{id}/{url_address}/archive/show', [UniversalScannerController::class, 'archiveShow'])->name('archive.show');

    // Add this route for deleting an archive
    Route::delete('archive/{archive}/delete', [UniversalScannerController::class, 'archiveDelete'])->name('archive.delete');
});
