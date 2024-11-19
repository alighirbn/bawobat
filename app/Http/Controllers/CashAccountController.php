<?php

namespace App\Http\Controllers;

use App\DataTables\CashAccountDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CashAccountRequest;
use App\Models\Cash\CashAccount;
use Illuminate\Http\Request;


class CashAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(CashAccountDataTable $dataTable)
    {
        return $dataTable->render('cash_account.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cash_account.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CashAccountRequest $request)
    {
        $cash_account = CashAccount::create($request->validated());

        // Return a success message and redirect
        return redirect()->route('cash_account.index')
            ->with('success', 'تمت إضافة الصندوق بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $cash_account = CashAccount::where('url_address', $url_address)->first();

        $newBalance = $cash_account->recalculateBalance();

        if (isset($cash_account)) {
            return view('cash_account.show', compact('cash_account'));
        } else {
            $ip = $this->getIPAddress();
            return view('cash_account.accessdenied', ['ip' => $ip]);
        }
    }

    public function statement(Request $request, $url_address)
    {
        // Retrieve the cash account by its URL address
        $cashAccount = CashAccount::where('url_address', $url_address)->firstOrFail();
        $newBalance = $cashAccount->recalculateBalance();
        // Get all transactions for the cash account, sorted by date
        $transactions = $cashAccount->transactions()
            ->orderBy('date', 'asc')
            ->get();

        // Initialize the running balance
        $runningBalance = 0;

        // Iterate over transactions to calculate the running balance
        $transactions->each(function ($transaction) use (&$runningBalance) {
            if ($transaction->type === 'credit') {
                $runningBalance += $transaction->amount;
            } elseif ($transaction->type === 'debit') {
                $runningBalance -= $transaction->amount;
            }

            // Attach the running balance for display
            $transaction->running_balance = $runningBalance;

            // Load the polymorphic relation (e.g., Payment, Expense)
            $transaction->transactionable;
        });

        // Get start and end dates from the request for filtering
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return view('cash_account.statement', compact('cashAccount', 'transactions', 'startDate', 'endDate'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {
        $cash_account = CashAccount::where('url_address', $url_address)->first();

        if (isset($cash_account)) {
            return view('cash_account.edit', compact(['cash_account']));
        } else {
            $ip = $this->getIPAddress();
            return view('cash_account.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CashAccountRequest $request, string $url_address)
    {
        $cash_account = CashAccount::where('url_address', $url_address)->first();

        if (isset($cash_account)) {
            $cash_account->update($request->validated());
            return redirect()->route('cash_account.index')
                ->with('success', 'تمت تعديل الصندوق بنجاح.');
        } else {
            $ip = $this->getIPAddress();
            return view('cash_account.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $cash_account = CashAccount::where('url_address', $url_address)->first();

        if (isset($cash_account)) {
            // Check if the cash account ID is 1
            if ($cash_account->id == 1) {
                return redirect()->route('cash_account.index')
                    ->with('error', 'لا يمكن حذف هذا الصندوق.');
            }

            // Delete the cash_account
            $cash_account->delete();

            return redirect()->route('cash_account.index')
                ->with('success', 'تم حذف الصندوق بنجاح.');
        } else {
            $ip = $this->getIPAddress();
            return view('cash_account.accessdenied', ['ip' => $ip]);
        }
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
