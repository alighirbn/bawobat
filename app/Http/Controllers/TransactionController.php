<?php

namespace App\Http\Controllers;

use App\DataTables\TransactionDataTable;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Account\Transaction;
use App\Models\Account\Account;
use App\Models\Account\CostCenter;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * عرض قائمة القيود الحسابية.
     */
    public function index(TransactionDataTable $dataTable)
    {
        return $dataTable->render('transaction.index');
    }

    /**
     * عرض صفحة إنشاء قيد حسابي جديد.
     */
    public function create()
    {
        $accounts = Account::whereNotNull('parent_id')
            ->orderBy('parent_id')
            ->orderBy('code') // Additional sorting by 'code'
            ->get();

        $costCenters = CostCenter::all();
        return view('transaction.create', compact('accounts', 'costCenters'));
    }

    /**
     * عرض تفاصيل قيد حسابي محدد.
     */
    public function show(string $url_address)
    {
        $transaction = Transaction::with([
            'debits.account',
            'debits.costCenter',
            'credits.account',
            'credits.costCenter'
        ])->where('url_address', '=', $url_address)->first();

        if (isset($transaction)) {
            return view('transaction.show', compact('transaction'));
        } else {
            $ip = $this->getIPAddress();
            return view('transaction.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * عرض صفحة تعديل قيد حسابي محدد.
     */
    public function edit(string $url_address)
    {
        $transaction = Transaction::with(['debits', 'credits'])->where('url_address', '=', $url_address)->first();

        if (isset($transaction)) {
            $accounts = Account::whereNotNull('parent_id')
                ->orderBy('parent_id')
                ->orderBy('code') // Additional sorting by 'code'
                ->get();

            $costCenters = CostCenter::all();

            return view('transaction.edit', compact('transaction', 'accounts', 'costCenters'));
        } else {
            $ip = $this->getIPAddress();
            return view('transaction.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * تحديث قيد حسابي محدد.
     */
    public function update(StoreTransactionRequest $request, string $url_address)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::with(['debits', 'credits'])
                ->where('url_address', '=', $url_address)
                ->firstOrFail();

            $transaction->update([
                'description' => $request->description,
                'date' => $request->date,
            ]);

            $transaction->debits()->delete();
            $transaction->credits()->delete();

            $this->addEntries($request->debit, $transaction, 'debit');
            $this->addEntries($request->credit, $transaction, 'credit');

            if (!$transaction->isBalanced()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'القيد الحسابي غير متوازن.');
            }

            DB::commit();

            return redirect()->route('transaction.index')->with('success', 'تم تحديث القيد الحسابي بنجاح.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطأ في قاعدة البيانات: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ غير متوقع.');
        }
    }

    /**
     * إنشاء قيد حسابي جديد.
     */
    public function store(StoreTransactionRequest $request)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'url_address' => $this->get_random_string(60),
                'user_id_create' => auth()->user()->id,
                'description' => $request->description,
                'date' => $request->date,
            ]);

            $this->addEntries($request->debit, $transaction, 'debit');
            $this->addEntries($request->credit, $transaction, 'credit');

            if (!$transaction->isBalanced()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'القيد الحسابي غير متوازن.');
            }

            DB::commit();
            return redirect()->route('transaction.index')->with('success', 'تم إنشاء القيد الحسابي بنجاح!');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطأ في قاعدة البيانات: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ غير متوقع.');
        }
    }

    /**
     * حذف قيد حسابي محدد.
     */
    public function destroy(string $url_address)
    {
        $affected = Transaction::where('url_address', $url_address)->delete();
        return redirect()->route('transaction.index')->with('success', 'تم حذف القيد الحسابي بنجاح.');
    }

    private function addEntries($entries, $transaction, $debitCredit)
    {
        foreach ($entries as $entry) {
            $transaction->entries()->create([
                'account_id' => $entry['account_id'],
                'amount' => $entry['amount'],
                'debit_credit' => $debitCredit,
                'cost_center_id' => $entry['cost_center_id'],
            ]);
        }
    }

    /**
     * استرداد عنوان IP للمستخدم.
     */
    public function getIPAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * إنشاء سلسلة عشوائية.
     */
    function get_random_string($length)
    {
        $array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $text = "";
        $length = rand(22, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, count($array) - 1);
            $text .= $array[$random];
        }
        return $text;
    }
}
