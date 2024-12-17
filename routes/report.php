
<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'report'], function () {


    Route::get('/trialbalance', [ReportController::class, 'trialBalance'])->middleware(['auth', 'verified', 'permission:report-trialBalance'])->name('report.trial_balance');
    Route::get('/balanceSheet', [ReportController::class, 'balanceSheet'])->middleware(['auth', 'verified', 'permission:report-trialBalanceCostCenter'])->name('report.balance_sheet'); // balanceSheet
});
