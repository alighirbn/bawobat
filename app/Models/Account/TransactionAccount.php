<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransactionAccount extends Model
{
    use HasFactory;

    protected $table = 'transaction_account';

    protected $fillable = [
        'transaction_id',
        'account_id',
        'amount',
        'debit_credit',
        'cost_center_id',
    ];

    const DEBIT = 'debit';
    const CREDIT = 'credit';

    // Relationships
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class);
    }

    // Fetch Statement of Account
    public static function getStatementOfAccount($accountId, $startDate, $endDate, $costCenterId = null)
    {
        // Step 1: Calculate the opening balance
        $openingBalanceQuery = DB::table('transaction_account')
            ->join('transactions', 'transaction_account.transaction_id', '=', 'transactions.id')
            ->where('transaction_account.account_id', $accountId)
            ->where('transactions.date', '<', $startDate);

        if ($costCenterId) {
            $openingBalanceQuery->where('transaction_account.cost_center_id', $costCenterId);
        }

        $openingBalance = $openingBalanceQuery
            ->selectRaw('SUM(CASE WHEN transaction_account.debit_credit = "debit" THEN transaction_account.amount ELSE -transaction_account.amount END) as balance')
            ->value('balance') ?? 0;

        // Step 2: Fetch transactions within the date range
        $transactionsQuery = self::join('transactions', 'transactions.id', '=', 'transaction_account.transaction_id')
            ->where('transaction_account.account_id', $accountId)
            ->whereBetween('transactions.date', [$startDate, $endDate]);

        if ($costCenterId) {
            $transactionsQuery->where('transaction_account.cost_center_id', $costCenterId);
        }

        $transactions = $transactionsQuery
            ->select('transaction_account.amount', 'transaction_account.debit_credit', 'transactions.date', 'transactions.description')
            ->orderBy('transactions.date')
            ->get();

        // Step 3: Calculate running balance and format SOA
        $runningBalance = $openingBalance;
        $soa = $transactions->map(function ($entry) use (&$runningBalance) {
            $amount = $entry->debit_credit === self::DEBIT ? $entry->amount : -$entry->amount;
            $runningBalance += $amount;

            return [
                'date' => $entry->date,
                'description' => $entry->description,
                'debit' => $entry->debit_credit === self::DEBIT ? $entry->amount : null,
                'credit' => $entry->debit_credit === self::CREDIT ? $entry->amount : null,
                'running_balance' => $runningBalance,
            ];
        });

        // Step 4: Prepend opening balance
        $soa->prepend([
            'date' => $startDate,
            'description' => 'Opening Balance',
            'debit' => null,
            'credit' => null,
            'running_balance' => $openingBalance,
        ]);

        return $soa;
    }
}
