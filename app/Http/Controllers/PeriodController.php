<?php

namespace App\Http\Controllers;

use App\DataTables\PeriodDataTable;
use App\Http\Requests\PeriodRequest;
use App\Models\Account\Period;
use Illuminate\Support\Facades\DB;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PeriodDataTable $dataTable)
    {
        return $dataTable->render('period.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('period.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PeriodRequest $request)
    {
        DB::beginTransaction();

        try {
            $period = Period::create($request->validated());

            DB::commit();
            return redirect()->route('period.index')->with('success', 'تم إنشاء الفترة المحاسبية بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ غير متوقع: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $period = Period::where('url_address', $url_address)->firstOrFail();
        return view('period.show', compact('period'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {

        $period = Period::where('url_address', '=', $url_address)->first();
        if (isset($period)) {
            return view('period.edit', compact('period'));
        } else {
            $ip = $this->getIPAddress();
            return view('period.accessdenied', ['ip' => $ip]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PeriodRequest $request, string $url_address)
    {
        DB::beginTransaction();

        try {
            $period = Period::where('url_address', $url_address)->firstOrFail();
            $period->update($request->validated());

            DB::commit();
            return redirect()->route('period.index')->with('success', 'تم تحديث الفترة المحاسبية بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ غير متوقع: ' . $e->getMessage());
        }
    }

    public function closePeriod(string $url_address)
    {
        $period = Period::where('url_address', $url_address)->firstOrFail();
        $period->is_closed = true;
        $period->save();
        return redirect()->back()->with('success', 'تم إغلاق الفترة بنجاح!');
    }

    public function openPeriod(string $url_address)
    {
        $period = Period::where('url_address', $url_address)->firstOrFail();
        $period->is_closed = false;
        $period->save();
        return redirect()->back()->with('success', 'تم فتح الفترة بنجاح!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $affected = Period::where('url_address', $url_address)->delete();
        return redirect()->route('period.index')
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
