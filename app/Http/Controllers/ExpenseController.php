<?php

namespace App\Http\Controllers;

use App\DataTables\ExpenseDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Cash\Expense;
use App\Models\Cash\Transaction;
use App\Models\Cash\CashAccount;
use App\Models\Cash\ExpenseType;
use App\Models\Project\Project;
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
        $projects = Project::all();
        $expense_types = ExpenseType::all();
        return view('expense.create', compact(['expense_types', 'projects']));
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
        $expense = Expense::where('url_address', $url_address)->first();

        if (isset($expense)) {
            $cash_accounts = CashAccount::all();
            return view('expense.show', compact(['expense', 'cash_accounts']));
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
            $projects = Project::all();
            $expense_types = ExpenseType::all();
            return view('expense.edit', compact(['expense', 'expense_types', 'projects']));
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
                $cashAccount = CashAccount::find(1); // or find based on your logic
                $cashAccount->adjustBalance($expense->expense_amount, 'credit');
            }

            // Delete related transactions
            $expense->transactions()->delete();

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
            // Approve the expense
            $expense->approve();

            $cash_account_id = $request->cash_account_id;
            $expense->cash_account_id = $cash_account_id;
            $expense->save(); // Save the updated payment model


            // Adjust cash account balance
            $cashAccount = CashAccount::find($cash_account_id); // or find based on your logic
            $cashAccount->adjustBalance($expense->amount, 'debit');

            // Create a transaction for the approved expense
            Transaction::create([

                'cash_account_id' => $cashAccount->id,
                'project_id' => $expense->project->id,
                'amount' => $expense->amount,
                'date' => now(),
                'type' => 'debit', // Since it's an expense
                'transactionable_id' => $expense->id,
                'transactionable_type' => Expense::class,
            ]);

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
