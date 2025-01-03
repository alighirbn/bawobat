<?php

namespace App\Http\Controllers;

use App\DataTables\OpeningBalanceDataTable;
use App\Http\Requests\OpeningBalanceRequest;
use App\Models\Account\Account;
use App\Models\Account\OpeningBalance;
use App\Services\OpeningBalanceService;
use Illuminate\Http\Request;
use App\Models\Account\Period;

class OpeningBalanceController extends Controller
{
    protected $openingBalanceService;

    public function __construct(OpeningBalanceService $openingBalanceService)
    {
        $this->openingBalanceService = $openingBalanceService;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $url_address)
    {
        $openingBalance = $this->openingBalanceService->findByUrlAddress($url_address);

        if (!$openingBalance) {
            return redirect()->route('opening_balance.index')
                ->with('error', 'لم يتم العثور على الرصيد الافتتاحي');
        }

        return view('opening_balance.show', compact('openingBalance'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index(OpeningBalanceDataTable $dataTable, Request $request)
    {
        return $dataTable->render('opening_balance.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periods = Period::all();
        $accounts = Account::all();
        return view('opening_balance.create', compact('periods', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OpeningBalanceRequest $request)
    {
        $openingBalance = $this->openingBalanceService->store($request->validated());

        return redirect()->route('opening_balance.index')
            ->with('success', 'تم إنشاء الرصيد الافتتاحي بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $url_address)
    {
        $openingBalance = $this->openingBalanceService->findByUrlAddress($url_address);
        $accounts = Account::all();
        $periods = Period::all();
        $openingBalance->load('accounts');
        return view('opening_balance.edit', compact('openingBalance', 'accounts', 'periods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OpeningBalanceRequest $request, string $url_address)
    {
        $openingBalance = $this->openingBalanceService->findByUrlAddress($url_address);
        $this->openingBalanceService->update($openingBalance, $request->validated());

        return redirect()->route('opening_balance.index')
            ->with('success', 'تم تحديث الرصيد الافتتاحي بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $url_address)
    {
        $openingBalance = $this->openingBalanceService->findByUrlAddress($url_address);

        if (!$openingBalance) {
            return redirect()->route('opening_balance.index')
                ->with('error', 'لم يتم العثور على الرصيد الافتتاحي');
        }

        $this->openingBalanceService->destroy($openingBalance);

        return redirect()->route('opening_balance.index')
            ->with('success', 'تم حذف الرصيد الافتتاحي والقيد المحاسبي الخاص به بنجاح');
    }
}
