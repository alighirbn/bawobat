<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'account'], function () {

    // Index - Show all accounts
    Route::get('/', [AccountController::class, 'index'])
        ->middleware(['auth', 'verified', 'permission:account-list'])
        ->name('account.index');

    // Create - Store a new account
    Route::post('/create', [AccountController::class, 'store'])
        ->middleware(['auth', 'verified', 'permission:account-create'])
        ->name('account.store');

    // Edit - Show the edit form
    Route::get('/{id}/edit', [AccountController::class, 'edit'])
        ->middleware(['auth', 'verified', 'permission:account-update'])
        ->name('account.edit');

    // Update - Handle the update request
    Route::put('/{id}', [AccountController::class, 'update'])
        ->middleware(['auth', 'verified', 'permission:account-update'])
        ->name('account.update');

    // Delete - Remove an account
    Route::delete('/{id}', [AccountController::class, 'destroy'])
        ->middleware(['auth', 'verified', 'permission:account-delete'])
        ->name('account.destroy');
});
