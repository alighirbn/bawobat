<?php

namespace App\Http\Controllers;

use App\Models\Account\CostCenter;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    public function index()
    {
        $costCenters = CostCenter::all();
        return view('cost_centers.index', compact('costCenters'));
    }

    public function create()
    {
        return view('cost_centers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        CostCenter::create($request->all());
        return redirect()->route('cost_centers.index')->with('success', 'Cost Center created successfully');
    }
}
