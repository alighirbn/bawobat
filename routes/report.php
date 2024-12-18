
<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'report'], function () {


    Route::get('/trialbalance', [ReportController::class, 'trialBalance'])->middleware(['auth', 'verified', 'permission:report-trialBalance'])->name('report.trial_balance');
    Route::get('/balanceSheet', [ReportController::class, 'balanceSheet'])->middleware(['auth', 'verified', 'permission:report-balanceSheet'])->name('report.balance_sheet'); // balanceSheet
    Route::get('/profitAndLoss', [ReportController::class, 'profitAndLoss'])->middleware(['auth', 'verified', 'permission:report-profitAndLoss'])->name('report.profit_and_loss'); // profitAndLoss
    Route::get('/statementOfAccount', [ReportController::class, 'statementOfAccount'])->middleware(['auth', 'verified', 'permission:report-statementOfAccount'])->name('report.statement_of_account'); // statementOfAccount
});
