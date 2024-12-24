<?php

namespace App\Http\Controllers;

use App\Models\Yasmin\YasminPayment;
use App\Models\Account\Transaction;
use App\Models\PaymentImport;
use Illuminate\Http\Request;

class PaymentImportController extends Controller
{
    // Show the list of payments available for import
    public function showPaymentsForImport()
    {
        // Fetch payments from the 'yasmin' database
        $payments = YasminPayment::all();

        // Get the list of already imported payments (from 'bawobat' database)
        $importedPayments = PaymentImport::pluck('payment_id')->toArray();

        // Return the view with the payments and imported payment IDs
        return view('payments.import', compact('payments', 'importedPayments'));
    }

    // Import selected payments
    public function importPayments(Request $request)
    {
        // Get selected payment IDs from the form
        $selectedPayments = $request->input('selected_payments', []);

        // Iterate through each selected payment for import
        foreach ($selectedPayments as $paymentId) {
            // Fetch payment from 'yasmin' database
            $payment = YasminPayment::find($paymentId);

            // Ensure the payment exists
            if (!$payment) {
                continue;
            }

            // Create a new transaction in the 'bawobat' database
            $transaction = Transaction::create([
                'url_address' => $this->get_random_string(60),
                'date' => $payment->payment_date,
                'description' => $payment->payment_note,
                'transactionable_id' => $payment->id,
                'transactionable_type' => YasminPayment::class,  // Polymorphic relation
                'user_id_create' => auth()->id(),  // Assuming you have authentication enabled
            ]);
            // Add entries (debit/credit)
            $transaction->addEntry(, $payment->payment_amount, 'credit');
            $transaction->addEntry(, $payment->payment_amount, 'debit');


            // Track the payment import in the 'payment_imports' table (in 'bawobat' database)
            PaymentImport::create([
                'payment_id' => $payment->id,
                'transaction_id' => $transaction->id,
                'imported_at' => now(),
            ]);
        }

        // Redirect back with a success message
        return redirect()->route('payments.import')->with('success', 'Payments imported successfully.');
    }

    // (Optional) Show the list of imports if needed
    public function showImportedPayments()
    {
        // Fetch all imports from the 'bawobat' database
        $importedPayments = PaymentImport::with('payment')->get();

        // Return the view with the imported payments
        return view('payments.imported', compact('importedPayments'));
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
