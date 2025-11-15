<?php

namespace App\Http\Controllers;

use App\Models\Account\Account;
use App\Models\Account\CostCenter;
use App\Models\Account\Period;
use App\Models\Yasmin\YasminPayment;
use App\Models\Account\Transaction;
use App\Models\PaymentImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentImportController extends Controller
{
    // Show the list of payments available for import
    public function showPaymentsForImport()
    {

        // Fetch only approved payments from the 'yasmin' database
        $payments = YasminPayment::where('approved', true)->get();

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
        if (!$selectedPayments) {
            return redirect()->back()->with('error', 'الرجاء اختيار المدفوعات التي تم اختيارها');
        }

        DB::beginTransaction();
        $periodId = $request->period_id ?? $this->getActivePeriodId();

        try {
            foreach ($selectedPayments as $paymentId) {
                // Fetch payment from 'yasmin' database
                $payment = YasminPayment::find($paymentId);
                if (!$payment) {
                    continue;
                }

                // Determine the correct credit account code based on cash_account_id
                $accountMap = [
                    5 => '7010202',
                    9 => '7010204',
                    10 => '7010205',
                    11 => '7010206',
                ];
                $creditAccountCode = $accountMap[$payment->cash_account_id] ?? '7010202';

                // Retrieve accounts and cost center
                $creditAccount = Account::where('code', $creditAccountCode)->first();
                $debitAccount = Account::where('code', '5300002')->first();
                $costCenter = CostCenter::find(2);

                // Create the transaction record
                $transaction = Transaction::create([
                    'url_address' => $this->get_random_string(60),
                    'date' => $payment->payment_date,
                    'description' => $payment->payment_note . ' - ' .
                        $payment->contract->customer->customer_full_name . ' - ' .
                        $payment->contract->building->building_number,
                    'period_id' => $periodId,
                    'transactionable_id' => $payment->id,
                    'transactionable_type' => YasminPayment::class,
                    'user_id_create' => auth()->id(),
                ]);

                // Add debit/credit entries
                $transaction->addEntry($creditAccount, $payment->payment_amount, 'credit', $costCenter);
                $transaction->addEntry($debitAccount, $payment->payment_amount, 'debit', $costCenter);

                // Record the import
                PaymentImport::create([
                    'payment_id' => $payment->id,
                    'transaction_id' => $transaction->id,
                    'imported_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('payments.import')->with('success', 'تم استيراد المدفوعات بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء استيراد المدفوعات: ' . $e->getMessage());
        }
    }


    // (Optional) Show the list of imports if needed
    public function showImportedPayments()
    {
        // Fetch all imports from the 'bawobat' database
        $importedPayments = PaymentImport::with('payment')->get();

        // Return the view with the imported payments
        return view('payments.imported', compact('importedPayments'));
    }

    protected function getActivePeriodId()
    {
        $activePeriod = Period::where('is_active', true)->first();
        return $activePeriod ? $activePeriod->id : null;
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
