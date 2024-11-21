<?php

namespace App\Http\Controllers;

use App\DataTables\TransactionDataTable;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Account\Transaction;
use App\Models\Account\Account;
use App\Models\Account\CostCenter;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  // Importing DB facade

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TransactionDataTable $dataTable)
    {
        return $dataTable->render('transaction.index');
    }


    public function create()
    {
        $accounts = Account::all();
        $costCenters = CostCenter::all();
        return view('transaction.create', compact('accounts', 'costCenters'));
    }

    public function show(string $url_address)
    {
        // Retrieve the transaction, including its debit and credit entries
        $transaction = Transaction::with([
            'debitEntries.account',
            'debitEntries.costCenter',
            'creditEntries.account',
            'creditEntries.costCenter'
        ])->where('url_address', '=', $url_address)->first();

        if (isset($transaction)) {
            return view('transaction.show', compact('transaction'));
        } else {
            $ip = $this->getIPAddress();
            return view('transaction.accessdenied', ['ip' => $ip]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {

        $transaction = Transaction::where('url_address', '=', $url_address)->first();
        if (isset($transaction)) {
            $accounts = Account::all();
            $costCenters = CostCenter::all();
            return view('transaction.edit', compact('transaction', 'accounts', 'costCenters'));
        } else {
            $ip = $this->getIPAddress();
            return view('transaction.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $url_address)
    {
        // insert the user input into model and lareval insert it into the database.
        Transaction::where('url_address', $url_address)->update($request->validated());

        //inform the user
        return redirect()->route('transaction.index')
            ->with('success', 'تمت تعديل البيانات  بنجاح ');
    }

    public function store(StoreTransactionRequest $request)
    {
        DB::beginTransaction();

        try {
            // Create the transaction
            $transaction = Transaction::create([
                'url_address' => $this->get_random_string(60),
                'user_id_create' => auth()->user()->id,
                'description' => $request->description,
                'date' => $request->date,
            ]);

            // Add debits and credits
            $this->addEntries($request->debit, $transaction, 'debit');
            $this->addEntries($request->credit, $transaction, 'credit');

            // Check if the transaction is balanced before committing
            if (!$transaction->isBalanced()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Transaction is not balanced.');
            }

            DB::commit();
            return redirect()->route('transaction.index')->with('success', 'Transaction created successfully!');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An unexpected error occurred.');
        }
    }

    private function addEntries($entries, $transaction, $debitCredit)
    {
        foreach ($entries as $entry) {
            $transaction->transactionAccounts()->create([
                'account_id' => $entry['account_id'],
                'amount' => $entry['amount'],
                'debit_credit' => $debitCredit,
                'cost_center_id' => $entry['cost_center_id'],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $affected = Transaction::where('url_address', $url_address)->delete();
        return redirect()->route('transaction.index')
            ->with('success', 'تمت حذف البيانات بنجاح ');
    }

    public function getIPAddress()
    {
        //whether ip is from the share internet
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from the proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from the remote address
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    function get_random_string($length)
    {
        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $text = "";
        $length = rand(22, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, 61);
            $text .= $array[$random];
        }
        return $text;
    }
}
