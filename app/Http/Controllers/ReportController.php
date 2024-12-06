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
        // Retrieve all accounts along with their children (if any)
        $accounts = Account::with('transactions', 'children')->get();

        // Prepare an array to store the trial balance data
        $trialBalance = [];

        // Iterate through each account to prepare the trial balance data
        foreach ($accounts as $account) {
            // Skip the account if it is a child (we will handle children in the parent row)
            if ($account->parent_id) {
                continue;
            }

            // Get total debits and credits for the main account
            $debits = $account->transactions()
                ->where('transaction_account.debit_credit', 'debit')
                ->sum('transaction_account.amount');
            $credits = $account->transactions()
                ->where('transaction_account.debit_credit', 'credit')
                ->sum('transaction_account.amount');

            // Calculate the balance for the main account
            $balance = $credits - $debits;

            // Initialize an array to store children of the main account
            $children = [];

            // If the account has children, include them in the trial balance
            if ($account->children->isNotEmpty()) {
                foreach ($account->children as $child) {
                    // Sum the debits, credits, and balance for each child account
                    $childDebits = $child->transactions()
                        ->where('transaction_account.debit_credit', 'debit')
                        ->sum('transaction_account.amount');
                    $childCredits = $child->transactions()
                        ->where('transaction_account.debit_credit', 'credit')
                        ->sum('transaction_account.amount');

                    // Add the child account to the children array
                    $children[] = [
                        'account_name' => $child->name,
                        'debits' => $childDebits,
                        'credits' => $childCredits,
                        'balance' => $childCredits - $childDebits,
                    ];

                    // Add the child's debits and credits to the parent's totals
                    $debits += $childDebits;
                    $credits += $childCredits;
                    $balance += ($childCredits - $childDebits);
                }
            }

            // Add the main account along with its children (if any)
            $trialBalance[] = [
                'account_name' => $account->name,
                'debits' => $debits,
                'credits' => $credits,
                'balance' => $balance,
                'children' => $children,  // Store children here
            ];
        }

        // Calculate the total debits and credits
        $totalDebits = collect($trialBalance)->sum('debits');
        $totalCredits = collect($trialBalance)->sum('credits');
        $isBalanced = $totalDebits === $totalCredits;

        // Pass the trial balance data to the view
        return view('report.trial_balance', compact('trialBalance', 'totalDebits', 'totalCredits', 'isBalanced'));
    }

    /**
     * Generate Cost Center Report.
     */
    public function costCenterReport(Request $request, $costCenterId = null)
    {
        return view('report.cost_center_report');
    }



    /**
     * Generate Trial Balance by Cost Center.
     */
    public function trialBalanceByCostCenter()
    {
        // Fetch all accounts with parent-child relationships and transactions
        $accounts = Account::with('children', 'transactions')->get();

        // Fetch all cost centers
        $costCenters = CostCenter::with('transactions')->get();

        // Map accounts into a hierarchical structure with balances
        $accountHierarchy = $accounts->map(function ($account) {
            $debits = $account->transactions()
                ->where('transaction_account.debit_credit', 'debit')
                ->sum('transaction_account.amount');

            $credits = $account->transactions()
                ->where('transaction_account.debit_credit', 'credit')
                ->sum('transaction_account.amount');

            return [
                'account_name' => $account->name,
                'parent_id' => $account->parent_id,
                'debits' => $debits,
                'credits' => $credits,
                'balance' => $credits - $debits,
                'children' => $account->children->map(function ($child) {
                    $childDebits = $child->transactions()
                        ->where('transaction_account.debit_credit', 'debit')
                        ->sum('transaction_account.amount');

                    $childCredits = $child->transactions()
                        ->where('transaction_account.debit_credit', 'credit')
                        ->sum('transaction_account.amount');

                    return [
                        'account_name' => $child->name,
                        'debits' => $childDebits,
                        'credits' => $childCredits,
                        'balance' => $childCredits - $childDebits,
                    ];
                }),
            ];
        });

        return view('report.trial_balance_cost_center', compact('accountHierarchy', 'costCenters'));
    }



    public function balanceSheet()
    {
        // Retrieve only the accounts that are of type 'Asset', 'Liability', or 'Equity'
        $accounts = Account::with('transactions', 'children')
            ->whereIn('type', ['asset', 'liability', 'equity'])  // Filter only Asset, Liability, and Equity accounts
            ->get();

        // Initialize arrays to store the balance sheet data for each section
        $assets = [];
        $liabilities = [];
        $equity = [];

        // Iterate through each account to prepare the balance sheet data
        foreach ($accounts as $account) {
            // Skip the account if it is a child (we will handle children in the parent row)
            if ($account->parent_id) {
                continue;
            }

            // Get total debits and credits for the main account
            $debits = $account->transactions()
                ->where('transaction_account.debit_credit', 'debit')
                ->sum('transaction_account.amount');
            $credits = $account->transactions()
                ->where('transaction_account.debit_credit', 'credit')
                ->sum('transaction_account.amount');

            // Calculate the balance for the main account
            $balance = $credits - $debits;

            // Initialize an array to store children of the main account
            $children = [];

            // If the account has children, include them in the balance sheet
            if ($account->children->isNotEmpty()) {
                foreach ($account->children as $child) {
                    // Sum the debits, credits, and balance for each child account
                    $childDebits = $child->transactions()
                        ->where('transaction_account.debit_credit', 'debit')
                        ->sum('transaction_account.amount');
                    $childCredits = $child->transactions()
                        ->where('transaction_account.debit_credit', 'credit')
                        ->sum('transaction_account.amount');

                    // Add the child account to the children array
                    $children[] = [
                        'account_name' => $child->name,
                        'debits' => $childDebits,
                        'credits' => $childCredits,
                        'balance' => $childCredits - $childDebits,
                    ];

                    // Add the child's debits and credits to the parent's totals
                    $debits += $childDebits;
                    $credits += $childCredits;
                    $balance += ($childCredits - $childDebits);
                }
            }

            // Add the main account along with its children (if any) to the correct section of the balance sheet
            $accountData = [
                'account_name' => $account->name,
                'debits' => $debits,
                'credits' => $credits,
                'balance' => $balance,
                'children' => $children,  // Store children here
            ];

            // Assign the account data to the appropriate section based on account type
            switch ($account->type) {
                case 'asset':
                    $assets[] = $accountData;
                    break;
                case 'liability':
                    $liabilities[] = $accountData;
                    break;
                case 'equity':
                    $equity[] = $accountData;
                    break;
            }
        }

        // Calculate the total for each section
        $totalAssets = collect($assets)->sum('balance');
        $totalLiabilities = collect($liabilities)->sum('balance');
        $totalEquity = collect($equity)->sum('balance');


        // Pass the balance sheet data to the view
        return view('report.balance_sheet', compact(
            'assets',
            'liabilities',
            'equity',
            'totalAssets',
            'totalLiabilities',
            'totalEquity'

        ));
    }
}
