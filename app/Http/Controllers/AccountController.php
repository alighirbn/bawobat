<?php

namespace App\Http\Controllers;

use App\DataTables\AccountDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Models\Account\Account;

class accountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AccountDataTable $dataTable)
    {
        return $dataTable->render('account.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountRequest $request)
    {
        Account::create($request->validated());

        //inform the user
        return redirect()->route('account.index')
            ->with('success', 'تمت أضافة البيانات بنجاح ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $account = Account::where('url_address', '=', $url_address)->first();
        if (isset($account)) {
            return view('account.show', compact('account'));
        } else {
            $ip = $this->getIPAddress();
            return view('account.accessdenied', ['ip' => $ip]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {

        $account = Account::where('url_address', '=', $url_address)->first();
        if (isset($account)) {
            return view('account.edit', compact('account'));
        } else {
            $ip = $this->getIPAddress();
            return view('account.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountRequest $request, string $url_address)
    {
        // insert the user input into model and lareval insert it into the database.
        Account::where('url_address', $url_address)->update($request->validated());

        //inform the user
        return redirect()->route('account.index')
            ->with('success', 'تمت تعديل البيانات  بنجاح ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $affected = Account::where('url_address', $url_address)->delete();
        return redirect()->route('account.index')
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
}
