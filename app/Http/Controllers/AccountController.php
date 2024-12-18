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
        // Get all top-level accounts with their children (eager loading)
        $accounts = Account::with('children')->whereNull('parent_id')->get();
        // Retrieve the active period
        $activePeriod = Period::where('is_active', 1)->first(); // Ensure you have the 'is_active' column

        // Check if there's an active period
        if (!$activePeriod) {
            return back()->with('error', 'No active period found.');
        }

        // Pass the active period dates to the view
        return view('account.index', [
            'accounts' => $accounts,
            'startDate' => $activePeriod->start_date->format('Y-m-d'),
            'endDate' => $activePeriod->end_date->format('Y-m-d')
        ]);
    }


    // Store a new account (with parent)
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:accounts,id',
            'last_digits' => 'required|numeric',
            'code' => 'required|unique:accounts,code', // Ensure the full code is unique
        ]);

        // Fetch the parent account
        $parentAccount = Account::find($request->parent_id);

        // Create the new account
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

        // Redirect to the accounts page
        return redirect()->route('account.index');
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
