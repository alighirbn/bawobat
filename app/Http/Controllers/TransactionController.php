<?php

namespace App\Http\Controllers;

use App\Models\Account\Transaction;
use App\Models\Account\Account;
use App\Models\Account\CostCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Importing DB facade

class TransactionController extends Controller
{
    public function create()
    {
        $accounts = Account::all();
        $costCenters = CostCenter::all();
        return view('transactions.create', compact('accounts', 'costCenters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'debit.*.account_id' => 'required|exists:accounts,id',
            'debit.*.amount' => 'required|numeric|min:0.01',
            'debit.*.cost_center_id' => 'nullable|exists:cost_centers,id',
            'credit.*.account_id' => 'required|exists:accounts,id',
            'credit.*.amount' => 'required|numeric|min:0.01',
            'credit.*.cost_center_id' => 'nullable|exists:cost_centers,id',
        ]);

        DB::beginTransaction();  // Start a transaction

        try {
            // Create the transaction
            $transaction = Transaction::create([
                'description' => $request->description,
                'date' => $request->date,
            ]);

            // Add debit entries
            foreach ($request->debit as $entry) {
                $transactionEntry = $transaction->entries()->create([
                    'account_id' => $entry['account_id'],
                    'amount' => $entry['amount'],
                    'type' => 'debit',
                ]);

                // Attach cost centers to the debit entry
                if (isset($entry['cost_center_id'])) {
                    $transactionEntry->costCenters()->attach($entry['cost_center_id']);
                }
            }

            // Add credit entries
            foreach ($request->credit as $entry) {
                $transactionEntry = $transaction->entries()->create([
                    'account_id' => $entry['account_id'],
                    'amount' => $entry['amount'],
                    'type' => 'credit',
                ]);

                // Attach cost centers to the credit entry
                if (isset($entry['cost_center_id'])) {
                    $transactionEntry->costCenters()->attach($entry['cost_center_id']);
                }
            }

            DB::commit();  // Commit the transaction

            return redirect()->route('transactions.index')->with('success', 'Transaction created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();  // Rollback the transaction on failure
            return redirect()->back()->with('error', 'Transaction creation failed.');
        }
    }
}
