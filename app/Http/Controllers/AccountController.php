<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Account\Account;
use App\Models\Account\Period;
use App\Models\Account\TransactionAccount;
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

    public function getSOA(Request $request)
    {
        // Fetch all available accounts for the select dropdown
        $accounts = Account::whereNotNull('parent_id')
            ->orderBy('parent_id')
            ->orderBy('code')
            ->get();

        // Retrieve the active period (ensure there is one active period)
        $activePeriod = Period::where('is_active', 1)->first(); // Assuming 'is_active' is used to mark the active period

        if (!$activePeriod) {
            return back()->with('error', 'No active period found.');
        }

        // Set the default period dates if the request does not provide them
        $startDate = $request->input('start_date', $activePeriod->start_date->format('Y-m-d'));
        $endDate = $request->input('end_date', $activePeriod->end_date->format('Y-m-d'));

        // If the form is submitted, validate the data
        if ($request->isMethod('get') && $request->has('account_id')) {
            try {
                // Validate input on form submission
                $validated = $request->validate([
                    'account_id' => 'required|integer|exists:accounts,id',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                ]);

                // Get filter values after validation
                $accountId = $validated['account_id'];
                $startDate = $validated['start_date'];
                $endDate = $validated['end_date'];

                // Fetch account details
                $account = Account::findOrFail($accountId);
                $accountName = null;

                $accountName = $account ? $account->name : null;


                // Fetch SOA with filters
                $soa = TransactionAccount::getStatementOfAccount($accountId, $startDate, $endDate);

                // Return the view with filtered SOA
                return view('account.soa', [
                    'soa' => $soa,
                    'account' => $account,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'accounts' => $accounts, // Pass accounts to the view
                    'accountName' => $accountName,
                ]);
            } catch (\Exception $e) {
                // Catch general errors
                return back()->with('error', 'Error generating statement of account: ' . $e->getMessage());
            }
        }

        // If the form has not been submitted, just return the view with empty filter fields
        return view('account.soa', [
            'accounts' => $accounts, // Pass accounts to the view
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
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
