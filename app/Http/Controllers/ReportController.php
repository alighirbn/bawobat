<?php

namespace App\Http\Controllers;

use App\Models\Account\Account;
use App\Models\Account\CostCenter;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Generate Statement of Account for a specific account.
     */
    public function statementOfAccount(Request $request, $accountId)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $account = Account::findOrFail($accountId);

        $transactions = $account->transactions()
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->orderBy('transactions.date')
            ->get();

        $totalDebits = $transactions
            ->where('transaction_account.debit_credit', 'debit')
            ->sum('transaction_account.amount');
        $totalCredits = $transactions
            ->where('transaction_account.debit_credit', 'credit')
            ->sum('transaction_account.amount');
        $balance = $totalCredits - $totalDebits;

        return view('report.statement_of_account', compact('account', 'transactions', 'totalDebits', 'totalCredits', 'balance'));
    }

    /**
     * Generate Trial Balance report.
     */
    public function trialBalance()
    {
        $accounts = Account::with('transactions')->get();

        $trialBalance = $accounts->map(function ($account) {
            $debits = $account->transactions()
                ->where('transaction_account.debit_credit', 'debit')
                ->sum('transaction_account.amount');
            $credits = $account->transactions()
                ->where('transaction_account.debit_credit', 'credit')
                ->sum('transaction_account.amount');

            return [
                'account_name' => $account->name,
                'debits' => $debits,
                'credits' => $credits,
                'balance' => $credits - $debits,
            ];
        });

        $totalDebits = $trialBalance->sum('debits');
        $totalCredits = $trialBalance->sum('credits');
        $isBalanced = $totalDebits === $totalCredits;

        return view('report.trial_balance', compact('trialBalance', 'totalDebits', 'totalCredits', 'isBalanced'));
    }

    /**
     * Generate Cost Center Report.
     */
    public function costCenterReport(Request $request, $costCenterId)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $costCenter = CostCenter::findOrFail($costCenterId);

        $transactions = $costCenter->transactions()
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->orderBy('transactions.date')
            ->get();

        $totalDebits = $transactions
            ->where('transaction_account.debit_credit', 'debit')
            ->sum('transaction_account.amount');
        $totalCredits = $transactions
            ->where('transaction_account.debit_credit', 'credit')
            ->sum('transaction_account.amount');
        $balance = $totalCredits - $totalDebits;

        return view('report.cost_center_report', compact('costCenter', 'transactions', 'totalDebits', 'totalCredits', 'balance'));
    }

    /**
     * Generate Trial Balance by Cost Center.
     */
    public function trialBalanceByCostCenter()
    {
        $costCenters = CostCenter::with('transactions')->get();

        $trialBalanceByCostCenter = $costCenters->map(function ($costCenter) {
            $debits = $costCenter->transactions()
                ->where('transaction_account.debit_credit', 'debit')
                ->sum('transaction_account.amount');
            $credits = $costCenter->transactions()
                ->where('transaction_account.debit_credit', 'credit')
                ->sum('transaction_account.amount');

            return [
                'cost_center_name' => $costCenter->name,
                'debits' => $debits,
                'credits' => $credits,
                'balance' => $credits - $debits,
            ];
        });

        return view('report.trial_balance_cost_center', compact('trialBalanceByCostCenter'));
    }

    public function balanceSheet(Request $request)
    {
        // Define the start and end dates
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Retrieve assets (debit accounts) and liabilities (credit accounts)
        $assets = Account::where('type', 'asset')->with('transactions')->get();
        $liabilities = Account::where('type', 'liability')->with('transactions')->get();
        $equity = Account::where('type', 'equity')->with('transactions')->get();

        // Calculate total assets, liabilities, and equity
        $totalAssets = $assets->sum(function ($account) use ($startDate, $endDate) {
            return $account->transactions()
                ->whereBetween('transactions.date', [$startDate, $endDate])
                ->where('transaction_account.debit_credit', 'debit')
                ->sum('transaction_account.amount');
        });

        $totalLiabilities = $liabilities->sum(function ($account) use ($startDate, $endDate) {
            return $account->transactions()
                ->whereBetween('transactions.date', [$startDate, $endDate])
                ->where('transaction_account.debit_credit', 'credit')
                ->sum('transaction_account.amount');
        });

        $totalEquity = $equity->sum(function ($account) use ($startDate, $endDate) {
            return $account->transactions()
                ->whereBetween('transactions.date', [$startDate, $endDate])
                ->where('transaction_account.debit_credit', 'credit')
                ->sum('transaction_account.amount');
        });

        // Calculate the balance (Assets = Liabilities + Equity)
        $isBalanced = $totalAssets === ($totalLiabilities + $totalEquity);

        // Return the view with the calculated values
        return view('report.balance_sheet', compact('totalAssets', 'totalLiabilities', 'totalEquity', 'isBalanced'));
    }
}
