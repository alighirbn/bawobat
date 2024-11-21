<?php

namespace App\Http\Controllers;

use App\DataTables\CostCenterDataTable;
use App\Http\Requests\CostCenterRequest;
use App\Models\Account\CostCenter;


class CostCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CostCenterDataTable $dataTable)
    {
        return $dataTable->render('costcenter.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('costcenter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CostCenterRequest $request)
    {
        CostCenter::create($request->validated());

        //inform the user
        return redirect()->route('costcenter.index')
            ->with('success', 'تمت أضافة البيانات بنجاح ');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $costcenter = CostCenter::where('url_address', '=', $url_address)->first();
        if (isset($costcenter)) {
            return view('costcenter.show', compact('costcenter'));
        } else {
            $ip = $this->getIPAddress();
            return view('costcenter.accessdenied', ['ip' => $ip]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {

        $costcenter = CostCenter::where('url_address', '=', $url_address)->first();
        if (isset($costcenter)) {
            return view('costcenter.edit', compact('costcenter'));
        } else {
            $ip = $this->getIPAddress();
            return view('costcenter.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CostCenterRequest $request, string $url_address)
    {
        // insert the user input into model and lareval insert it into the database.
        CostCenter::where('url_address', $url_address)->update($request->validated());

        //inform the user
        return redirect()->route('costcenter.index')
            ->with('success', 'تمت تعديل البيانات  بنجاح ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $affected = CostCenter::where('url_address', $url_address)->delete();
        return redirect()->route('costcenter.index')
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
