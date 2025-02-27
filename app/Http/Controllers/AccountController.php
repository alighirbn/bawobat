<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use App\Models\Account\Period;
use Illuminate\Http\Request;

class accountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::with('children')->whereNull('parent_id')->get();
        $activePeriod = Period::where('is_active', 1)->first();

        if (!$activePeriod) {
            return back()->with('error', 'No active period found.');
        }

        return view('account.index', [
            'accounts' => $accounts,
            'startDate' => $activePeriod->start_date->format('Y-m-d'),
            'endDate' => $activePeriod->end_date->format('Y-m-d')
        ]);
    }

    /**
     * Store a new account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:accounts,id',
            'last_digits' => 'required|numeric',
            'code' => 'required|unique:accounts,code',
        ]);

        $parentAccount = Account::find($request->parent_id);

        Account::create([
            'name' => $request->name,
            'url_address' => $this->get_random_string(60),
            'code' => $request->code,
            'type' => $parentAccount->type,
            'category' => $parentAccount->category,
            'class' => $parentAccount->class,
            'parent_id' => $request->parent_id,
            'user_id_create' => auth()->id(),
            'user_id_update' => auth()->id(),
        ]);

        return redirect()->route('account.index');
    }

    /**
     * Show the form for editing an account.
     */
    public function edit($id)
    {
        $account = Account::findOrFail($id);
        return view('account.edit', compact('account'));
    }

    /**
     * Update an account.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|unique:accounts,code,' . $id,
        ]);

        $account = Account::findOrFail($id);
        $account->update([
            'name' => $request->name,
            'code' => $request->code,
            'user_id_update' => auth()->id(),
        ]);

        return redirect()->route('account.index');
    }

    /**
     * Delete an account.
     */
    public function destroy($id)
    {
        $account = Account::findOrFail($id);

        // تحقق مما إذا كان الحساب يحتوي على معاملات
        if ($account->transactions()->exists()) {
            return back()->with('error', 'لا يمكن حذف الحساب لأنه يحتوي على معاملات.');
        }

        $account->delete();

        return redirect()->route('account.index')->with('success', 'تم حذف الحساب بنجاح.');
    }


    /**
     * Generate a random string.
     */
    private function get_random_string($length)
    {
        $array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $text = "";
        $length = rand(22, $length);

        for ($i = 0; $i < $length; $i++) {
            $text .= $array[array_rand($array)];
        }
        return $text;
    }
}
