<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Account\Account;
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

        return view('account.index', compact('accounts'));
    }

    // Store a new account (with parent)
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|exists:accounts,id',  // Ensure the parent account exists
        ]);

        // Fetch the parent account's details
        $parentAccount = Account::find($request->parent_id);

        // Create a new account with the parent's data
        Account::create([
            'name' => $request->name, // New account name (from the user input)
            'url_address' => $this->get_random_string(60), // Parent's URL Address
            'code' => $parentAccount->code . $parentAccount->children()->count() + 1, // Parent's Code
            'type' => $parentAccount->type, // Parent's Type
            'category' => $parentAccount->category, // Parent's category
            'class' => $parentAccount->class, // Parent's Class
            'parent_id' => $request->parent_id, // Set the parent ID for the new child account
            'user_id_create' => auth()->id(), // ID of the user creating the account
            'user_id_update' => auth()->id(), // ID of the user updating the account (initially same as creator)
        ]);

        // Redirect back to the accounts page
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
