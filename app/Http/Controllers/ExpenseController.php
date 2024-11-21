<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Account\Account;
use App\Models\Account\CostCenter;
use App\Models\Account\Transaction;
use App\Models\Expense\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    public function index(ExpenseDataTable $dataTable, Request $request)
    {
        $onlyPending = $request->input('onlyPending');
        return $dataTable->onlyPending($onlyPending)->render('expense.index');
    }

    public function create()
    {
        $cost_centers = CostCenter::all();
        $expenseAccounts = Account::where('type', 'expense')->get();
        $cashAccounts = Account::where('type', 'asset')->where('class', 5)->get();

        return view('expense.create', compact(['cost_centers', 'expenseAccounts', 'cashAccounts']));
    }


    public function store(ExpenseRequest $request)
    {
        $expense = Expense::create($request->validated());

        // Return a success message and redirect
        return redirect()->route('expense.show', $expense->url_address)
            ->with('success', 'تمت إضافة المصروف بنجاح، في انتظار الموافقة.');
    }


    public function show(string $url_address)
    {
        $expense = Expense::with(['cost_center', 'debit_account', 'credit_account'])->where('url_address', '=', $url_address)->first();

        if (isset($expense)) {

            return view('expense.show', compact(['expense']));
        } else {
            $ip = $this->getIPAddress();
            return view('expense.accessdenied', ['ip' => $ip]);
        }
    }


    public function edit(string $url_address)
    {
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            if ($expense->approved) {
                return redirect()->route('expense.index')
                    ->with('error', 'لا يمكن تعديل مصروف تمت الموافقة عليه.');
            }
            $cost_centers = CostCenter::all();
            $expenseAccounts = Account::where('type', 'expense')->get();
            $cashAccounts = Account::where('type', 'asset')->where('class', 5)->get();

            return view('expense.edit', compact(['expense', 'cost_centers', 'expenseAccounts', 'cashAccounts']));
        } else {
            $ip = $this->getIPAddress();
            return view('expense.accessdenied', ['ip' => $ip]);
        }
    }


    public function update(ExpenseRequest $request, string $url_address)
    {
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            $expense->update($request->validated());

            return redirect()->route('expense.index')
                ->with('success', 'تمت تعديل المصروف بنجاح.');
        } else {
            $ip = $this->getIPAddress();
            return view('expense.accessdenied', ['ip' => $ip]);
        }
    }

    public function destroy(string $url_address)
    {
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            // Adjust cash account balance if necessary
            if ($expense->approved) {
                // Delete related transactions
                $expense->transactions()->delete();
            }

            // Delete the expense
            $expense->delete();

            return redirect()->route('expense.index')
                ->with('success', 'تمت حذف المصروف بنجاح.');
        } else {
            $ip = $this->getIPAddress();
            return view('expense.accessdenied', ['ip' => $ip]);
        }
    }


    public function approve(Request $request, string $url_address)
    {
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            $transaction = Transaction::create([
                'url_address' => $this->get_random_string(60),
                'user_id_create' => auth()->user()->id,
                'date' => now(),
                'description' => '',
                'transactionable_id' => $expense->id,
                'transactionable_type' => expense::class,
            ]);

            $debitAccount = Account::find($expense->debit_account_id);
            $creditAccount = Account::find($expense->credit_account_id);

            if (!$debitAccount || !$creditAccount) {
                return redirect()->route('expense.show', $expense->url_address)
                    ->with('error', 'Invalid debit or credit account specified.');
            }

            $transaction->addEntry(
                $debitAccount,
                $expense->amount,
                'debit',
                $expense->cost_center_id ? CostCenter::find($expense->cost_center_id) : null
            );

            $transaction->addEntry(
                $creditAccount,
                $expense->amount,
                'credit',
                $expense->cost_center_id ? CostCenter::find($expense->cost_center_id) : null
            );

            // Approve the expense
            $expense->approve();

            return redirect()->route('expense.index')
                ->with('success', 'تمت الموافقة على المصروف وتم تسجيل المعاملة في الحساب النقدي.');
        } else {
            $ip = $this->getIPAddress();
            return view('expense.accessdenied', ['ip' => $ip]);
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
