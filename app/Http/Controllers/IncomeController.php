<?php

namespace App\Http\Controllers;

use App\DataTables\IncomeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeRequest;
use App\Models\Cash\CashAccount;
use App\Models\Cash\Transaction;

use App\Models\Income\Income;
use App\Models\User;
use App\Notifications\IncomeNotify;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IncomeDataTable $dataTable, Request $request)
    {
        return $dataTable->render('Income.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        return view('Income.create', compact(['contracts']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeRequest $request)
    {
        $Income = Income::create($request->validated());

        return redirect()->route('Income.show', $Income->url_address)
            ->with('success', 'تمت أضافة الدفعة بنجاح في انتظار الموافقة عليها ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $Income = Income::with(['contract.customer', 'contract.building.building_category', 'contract_installment.installment'])->where('url_address', '=', $url_address)->first();

        if (isset($Income)) {
            $cash_accounts = CashAccount::all();
            return view('Income.show', compact(['Income', 'cash_accounts']));
        } else {
            $ip = $this->getIPAddress();
            return view('Income.accessdenied', ['ip' => $ip]);
        }
    }
    public function approve(Request $request, string $url_address)
    {
        $Income = Income::where('url_address', '=', $url_address)->first();

        if (isset($Income)) {
            // Approve the Income
            $Income->approve();

            $cash_account_id = $request->cash_account_id;

            // Update the cash_account_id in the Income model
            $Income->cash_account_id = $cash_account_id;
            $Income->save(); // Save the updated Income model

            // Get the cash account (assuming main account with ID 1)
            $cashAccount = CashAccount::find($cash_account_id); // or find based on your logic

            // Adjust the cash account balance by crediting the Income amount
            $cashAccount->adjustBalance($Income->Income_amount, 'credit');

            // Create a transaction for the approved Income
            Transaction::create([
                'url_address' => $this->get_random_string(60),
                'cash_account_id' => $cashAccount->id,
                'transactionable_id' => $Income->id,
                'transactionable_type' => Income::class,
                'transaction_amount' => $Income->Income_amount,
                'transaction_date' => now(),
                'transaction_type' => 'credit', // Since it's a Income
            ]);



            return redirect()->route('contract.show', $Income->contract->url_address)
                ->with('success', 'تم قبول الدفعة بنجاح وتم تسجيل المعاملة في الحساب النقدي.');
        } else {
            $ip = $this->getIPAddress();
            return view('Income.accessdenied', ['ip' => $ip]);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {

        $Income = Income::where('url_address', '=', $url_address)->first();

        if (isset($Income)) {
            if ($Income->approved) {
                return redirect()->route('Income.index')
                    ->with('error', 'لا يمكن تعديل دفعة موافق عليها.');
            }

            return view('Income.edit', compact(['Income', 'contracts']));
        } else {
            $ip = $this->getIPAddress();
            return view('Income.accessdenied', ['ip' => $ip]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(IncomeRequest $request, string $url_address)
    {
        // insert the user input into model and lareval insert it into the database.
        Income::where('url_address', $url_address)->update($request->validated());

        //inform the user
        return redirect()->route('Income.index')
            ->with('success', 'تمت تعديل الدفعة  بنجاح ');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $Income = Income::where('url_address', $url_address)->first();

        if (isset($Income)) {
            if ($Income->approved) {
                // Adjust the cash account balance by debiting the Income amount
                $cashAccount = Cash_Account::find($Income->cash_account_id); // or find based on your logic
                $cashAccount->adjustBalance($Income->Income_amount, 'debit');

                // Delete related transactions
                $Income->transactions()->delete();
            }

            // Delete the Income
            $Income->delete();

            return redirect()->route('Income.index')
                ->with('success', 'تمت حذف الدفعة بنجاح ');
        } else {
            $ip = $this->getIPAddress();
            return view('Income.accessdenied', ['ip' => $ip]);
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
