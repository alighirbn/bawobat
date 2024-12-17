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


    /**
     * Generate Trial Balance report.
     */
    // public function trialBalance()
    // {
    //     // Retrieve all accounts along with their children (if any)
    //     $accounts = Account::with('transactions', 'children')->get();

    //     // Prepare an array to store the trial balance data
    //     $trialBalance = [];

    //     // Iterate through each account to prepare the trial balance data
    //     foreach ($accounts as $account) {
    //         // Skip the account if it is a child (we will handle children in the parent row)
    //         if ($account->parent_id) {
    //             continue;
    //         }

    //         // Get total debits and credits for the main account
    //         $debits = $account->transactions()
    //             ->where('transaction_account.debit_credit', 'debit')
    //             ->sum('transaction_account.amount');
    //         $credits = $account->transactions()
    //             ->where('transaction_account.debit_credit', 'credit')
    //             ->sum('transaction_account.amount');

    //         // Calculate the balance for the main account
    //         $balance = $credits - $debits;

    //         // Initialize an array to store children of the main account
    //         $children = [];

    //         // If the account has children, include them in the trial balance
    //         if ($account->children->isNotEmpty()) {
    //             foreach ($account->children as $child) {
    //                 // Sum the debits, credits, and balance for each child account
    //                 $childDebits = $child->transactions()
    //                     ->where('transaction_account.debit_credit', 'debit')
    //                     ->sum('transaction_account.amount');
    //                 $childCredits = $child->transactions()
    //                     ->where('transaction_account.debit_credit', 'credit')
    //                     ->sum('transaction_account.amount');

    //                 // Add the child account to the children array
    //                 $children[] = [
    //                     'account_name' => $child->name,
    //                     'debits' => $childDebits,
    //                     'credits' => $childCredits,
    //                     'balance' => $childCredits - $childDebits,
    //                 ];

    //                 // Add the child's debits and credits to the parent's totals
    //                 $debits += $childDebits;
    //                 $credits += $childCredits;
    //                 $balance += ($childCredits - $childDebits);
    //             }
    //         }

    //         // Add the main account along with its children (if any)
    //         $trialBalance[] = [
    //             'account_name' => $account->name,
    //             'debits' => $debits,
    //             'credits' => $credits,
    //             'balance' => $balance,
    //             'children' => $children,  // Store children here
    //         ];
    //     }

    //     // Calculate the total debits and credits
    //     $totalDebits = collect($trialBalance)->sum('debits');
    //     $totalCredits = collect($trialBalance)->sum('credits');
    //     $isBalanced = $totalDebits === $totalCredits;

    //     // Pass the trial balance data to the view
    //     return view('report.trial_balance', compact('trialBalance', 'totalDebits', 'totalCredits', 'isBalanced'));
    // }
    public function trialBalance(Request $request)
    {
        // Fetch input filters from the request
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $costCenterId = $request->input('cost_center_id', null);
        $fromAccount = $request->input('from_account', null);
        $toAccount = $request->input('to_account', null);

        // Fetch available cost centers (ensure you have a CostCenter model)
        $costCenters = CostCenter::all();

        // Retrieve accounts, optionally filtering by the 'from' and 'to' account range
        $accounts = Account::with('transactions')
            ->when($fromAccount && $toAccount, function ($query) use ($fromAccount, $toAccount) {
                return $query->whereBetween('code', [$fromAccount, $toAccount]);
            })
            ->orderBy('code') // Sort by account code
            ->get();

        // Initialize array to store trial balance data
        $trialBalanceData = [];

        // Iterate through each account and calculate balances
        foreach ($accounts as $account) {
            // Get total debits and credits for the account within the date range and optional cost center filter
            $debits = $account->transactions()
                ->when($costCenterId, function ($query) use ($costCenterId) {
                    return $query->where('transaction_account.cost_center_id', $costCenterId);
                })
                ->where('transaction_account.debit_credit', 'debit')
                ->whereBetween('transactions.date', [$startDate, $endDate])
                ->sum('transaction_account.amount');

            $credits = $account->transactions()
                ->when($costCenterId, function ($query) use ($costCenterId) {
                    return $query->where('transaction_account.cost_center_id', $costCenterId);
                })
                ->where('transaction_account.debit_credit', 'credit')
                ->whereBetween('transactions.date', [$startDate, $endDate])
                ->sum('transaction_account.amount');

            // Include only accounts with non-zero debits or credits
            if ($debits > 0 || $credits > 0) {
                $trialBalanceData[] = [
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'debits' => $debits,
                    'credits' => $credits,
                ];
            }
        }

        // Calculate total debits and credits for the trial balance
        $totalDebits = collect($trialBalanceData)->sum('debits');
        $totalCredits = collect($trialBalanceData)->sum('credits');

        // Pass data to the view
        return view('report.trial_balance', compact(
            'startDate',
            'endDate',
            'costCenters',
            'trialBalanceData',
            'totalDebits',
            'totalCredits',
            'costCenterId',
            'fromAccount',
            'toAccount',
            'accounts', // Include this variable
        ));
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



    public function balanceSheet(Request $request)
    {
        // Default to today's date if no date is provided
        $asOfDate = $request->input('as_of_date', now()->toDateString());

        // Accept the cost center filter (if any)
        $costCenterId = $request->input('cost_center_id', null);

        // Fetch the available cost centers (ensure that you have a CostCenter model and data)
        $costCenters = CostCenter::all();  // or use appropriate logic to get the list of cost centers

        // Retrieve only the accounts that are of type 'Asset', 'Liability', or 'Equity'
        $accounts = Account::with('transactions', 'children')
            ->whereIn('type', ['Asset', 'Liability', 'Equity']) // Filter only Asset, Liability, and Equity accounts
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

            // Get total debits and credits for the main account up to the specified date, with optional cost center filter
            $debits = $account->transactions()
                ->when($costCenterId, function ($query) use ($costCenterId) {
                    return $query->where('transaction_account.cost_center_id', $costCenterId);
                })
                ->where('transaction_account.debit_credit', 'debit')
                ->whereDate('transactions.date', '<=', $asOfDate)
                ->sum('transaction_account.amount');

            $credits = $account->transactions()
                ->when($costCenterId, function ($query) use ($costCenterId) {
                    return $query->where('transaction_account.cost_center_id', $costCenterId);
                })
                ->where('transaction_account.debit_credit', 'credit')
                ->whereDate('transactions.date', '<=', $asOfDate)
                ->sum('transaction_account.amount');

            // Calculate the balance based on the account type
            $balance = match ($account->type) {
                'Asset' => $debits - $credits, // Asset: Debit - Credit
                default => $credits - $debits, // Liability/Equity: Credit - Debit
            };

            // Initialize an array to store children of the main account
            $children = [];

            // If the account has children, include them in the balance sheet
            if ($account->children->isNotEmpty()) {
                foreach ($account->children as $child) {
                    // Sum the debits, credits, and balance for each child account up to the specified date, with optional cost center filter
                    $childDebits = $child->transactions()
                        ->when($costCenterId, function ($query) use ($costCenterId) {
                            return $query->where('transaction_account.cost_center_id', $costCenterId);
                        })
                        ->where('transaction_account.debit_credit', 'debit')
                        ->whereDate('transactions.date', '<=', $asOfDate)
                        ->sum('transaction_account.amount');

                    $childCredits = $child->transactions()
                        ->when($costCenterId, function ($query) use ($costCenterId) {
                            return $query->where('transaction_account.cost_center_id', $costCenterId);
                        })
                        ->where('transaction_account.debit_credit', 'credit')
                        ->whereDate('transactions.date', '<=', $asOfDate)
                        ->sum('transaction_account.amount');

                    // Calculate the child's balance based on the account type
                    $childBalance = match ($account->type) {
                        'Asset' => $childDebits - $childCredits, // Asset: Debit - Credit
                        default => $childCredits - $childDebits, // Liability/Equity: Credit - Debit
                    };

                    // Add the child account to the children array
                    $children[] = [
                        'account_code' => $child->code,
                        'account_name' => $child->name,
                        'debits' => $childDebits,
                        'credits' => $childCredits,
                        'balance' => $childBalance,
                        'category' => $child->category, // Add category for children
                    ];

                    // Add the child's debits, credits, and balance to the parent's totals
                    $debits += $childDebits;
                    $credits += $childCredits;
                    $balance += $childBalance;
                }
            }

            // Add the main account along with its children (if any) to the correct section of the balance sheet
            $accountData = [
                'account_code' => $account->code,
                'account_name' => $account->name,
                'debits' => $debits,
                'credits' => $credits,
                'balance' => $balance,
                'category' => $account->category, // Add category for main account
                'children' => $children, // Store children here
            ];

            // Assign the account data to the appropriate section based on account type
            switch ($account->type) {
                case 'Asset':
                    $assets[] = $accountData;
                    break;
                case 'Liability':
                    $liabilities[] = $accountData;
                    break;
                case 'Equity':
                    $equity[] = $accountData;
                    break;
            }
        }

        // Group assets, liabilities, and equity by current and non-current categories
        $assetsCurrent = collect($assets)->where('category', 'Current');
        $assetsNonCurrent = collect($assets)->where('category', 'Non-Current');

        $liabilitiesCurrent = collect($liabilities)->where('category', 'Current');
        $liabilitiesNonCurrent = collect($liabilities)->where('category', 'Non-Current');

        $equityCurrent = collect($equity)->where('category', 'Current');
        $equityNonCurrent = collect($equity)->where('category', 'Non-Current');

        // Calculate totals for each section
        $totalAssets = collect($assets)->sum('balance');
        $totalLiabilities = collect($liabilities)->sum('balance');
        $totalEquity = collect($equity)->sum('balance');

        // Pass the grouped data to the view
        return view('report.balance_sheet', compact(
            'asOfDate',
            'costCenters', // Make sure this is passed to the view
            'assetsCurrent',
            'assetsNonCurrent',
            'liabilitiesCurrent',
            'liabilitiesNonCurrent',
            'equityCurrent',
            'equityNonCurrent',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'costCenterId' // Pass the cost center filter to the view
        ));
    }
}
