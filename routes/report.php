
<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'report'], function () {

    Route::get('/soa/{accountId}', [ReportController::class, 'statementOfAccount'])->middleware(['auth', 'verified', 'permission:report-soa'])->name('report.soa');
    Route::get('/trial-balance', [ReportController::class, 'trialBalance'])->middleware(['auth', 'verified', 'permission:report-trialBalance'])->name('report.trialBalance');
    Route::get('/cost-center/{costCenterId}', [ReportController::class, 'costCenterReport'])->middleware(['auth', 'verified', 'permission:report-costCenter'])->name('report.costCenter');
    Route::get('/trial-balance-cost-center', [ReportController::class, 'trialBalanceByCostCenter'])->middleware(['auth', 'verified', 'permission:report-trialBalanceCostCenter'])->name('report.trialBalanceCostCenter'); // balanceSheet
    Route::get('/balance-Sheet', [ReportController::class, 'balanceSheet'])->middleware(['auth', 'verified', 'permission:report-trialBalanceCostCenter'])->name('report.balanceSheet'); // balanceSheet
});
