<?php

namespace App\Http\Controllers;

use App\DataTables\IncomeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\IncomeRequest;
use App\Models\Account\Account;
use App\Models\Account\CostCenter;
use App\Models\Account\Period;
use App\Models\Account\Transaction;
use App\Models\Income\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IncomeDataTable $dataTable, Request $request)
    {

        $onlyPending = $request->input('onlyPending');
        return $dataTable->onlyPending($onlyPending)->render('income.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $cost_center_id = $request->cost_center_id;
        $cost_centers = CostCenter::all();

        $revenueAccounts = Account::whereNotNull('parent_id')
            ->where('type', 'Income')
            ->orderBy('parent_id')
            ->orderBy('code') // Additional sorting by 'code'
            ->get();
        $cashAccounts = Account::whereNotNull('parent_id')
            ->where('type', 'Asset')
            ->where('class', 5)
            ->orderBy('parent_id')
            ->orderBy('code') // Additional sorting by 'code'
            ->get();

        return view('income.create', compact(['cost_centers', 'revenueAccounts', 'cashAccounts', 'cost_center_id']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeRequest $request)
    {
        DB::beginTransaction();
        try {
            $income = Income::create($request->validated());
            DB::commit();

            return redirect()->route('income.show', $income->url_address)
                ->with('success', 'تمت أضافة الايراد بنجاح في انتظار الموافقة عليها ');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة الايراد. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $income = Income::with(['cost_center', 'debit_account', 'credit_account'])->where('url_address', '=', $url_address)->first();

        if (isset($income)) {

            return view('income.show', compact(['income']));
        } else {
            $ip = $this->getIPAddress();
            return view('Income.accessdenied', ['ip' => $ip]);
        }
    }
    public function approve(Request $request, string $url_address)
    {
        DB::beginTransaction();
        try {
            $income = Income::where('url_address', '=', $url_address)->first();
            $periodId = $request->period_id ?? $this->getActivePeriodId();
            if (!isset($income)) {
                DB::rollBack();
                $ip = $this->getIPAddress();
                return view('Income.accessdenied', ['ip' => $ip]);
            }

            // Create a transaction for the approved income
            $transaction = Transaction::create([
                'url_address' => $this->get_random_string(60),
                'user_id_create' => auth()->user()->id,
                'date' => now(),
                'period_id' => $periodId,
                'description' => $income->description,
                'transactionable_id' => $income->id,
                'transactionable_type' => income::class,
            ]);

            $debitAccount = Account::find($income->debit_account_id);
            $creditAccount = Account::find($income->credit_account_id);

            if (!$debitAccount || !$creditAccount) {
                DB::rollBack();
                return redirect()->route('income.show', $income->url_address)
                    ->with('error', 'Invalid debit or credit account specified.');
            }

            $transaction->addEntry(
                $debitAccount,
                $income->amount,
                'debit',
                $income->cost_center_id ? CostCenter::find($income->cost_center_id) : null
            );

            $transaction->addEntry(
                $creditAccount,
                $income->amount,
                'credit',
                $income->cost_center_id ? CostCenter::find($income->cost_center_id) : null
            );

            // Approve the income
            $income->approve();

            DB::commit();
            return redirect()->route('income.show', $income->url_address)
                ->with('success', 'تم قبول الايراد بنجاح وتم تسجيل المعاملة في الحساب النقدي.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('income.show', $income->url_address)
                ->with('error', 'حدث خطأ أثناء معالجة الايراد. يرجى المحاولة مرة أخرى.');
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {

        $income = Income::where('url_address', '=', $url_address)->first();

        if (isset($income)) {
            if ($income->approved) {
                return redirect()->route('income.index')
                    ->with('error', 'لا يمكن تعديل ايراد موافق عليه.');
            }

            $cost_centers = CostCenter::all();
            $revenueAccounts = Account::whereNotNull('parent_id')
                ->where('type', 'Income')
                ->orderBy('parent_id')
                ->orderBy('code') // Additional sorting by 'code'
                ->get();
            $cashAccounts = Account::whereNotNull('parent_id')
                ->where('type', 'Asset')
                ->where('class', 5)
                ->orderBy('parent_id')
                ->orderBy('code') // Additional sorting by 'code'
                ->get();

            return view('income.edit', compact(['income', 'cost_centers', 'revenueAccounts', 'cashAccounts']));
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
        DB::beginTransaction();
        try {
            Income::where('url_address', $url_address)->update($request->validated());
            DB::commit();

            return redirect()->route('income.index')
                ->with('success', 'تمت تعديل الايراد  بنجاح ');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تعديل الايراد. يرجى المحاولة مرة أخرى.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        DB::beginTransaction();
        try {
            $income = Income::where('url_address', $url_address)->first();

            if (!isset($income)) {
                DB::rollBack();
                $ip = $this->getIPAddress();
                return view('income.accessdenied', ['ip' => $ip]);
            }

            if ($income->approved) {
                // Delete related transactions
                $income->transactions()->delete();
            }

            // Delete the income
            $income->delete();
            DB::commit();

            return redirect()->route('income.index')
                ->with('success', 'تمت حذف الايراد بنجاح ');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('income.index')
                ->with('error', 'حدث خطأ أثناء حذف الايراد. يرجى المحاولة مرة أخرى.');
        }
    }

    protected function getActivePeriodId()
    {
        $activePeriod = Period::where('is_active', true)->first();
        return $activePeriod ? $activePeriod->id : null;
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
