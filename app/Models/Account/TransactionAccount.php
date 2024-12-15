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

    // Query Scopes
    public function scopeDebits($query)
    {
        return $query->where('debit_credit', self::DEBIT);
    }

    public function scopeCredits($query)
    {
        return $query->where('debit_credit', self::CREDIT);
    }

    // Calculate Opening Balance
    public static function getOpeningBalance($accountId, $startDate)
    {
        return DB::table('transaction_account')
            ->join('transactions', 'transaction_account.transaction_id', '=', 'transactions.id')  // Join with transactions table
            ->where('transaction_account.account_id', $accountId)
            ->where('transactions.date', '<', $startDate)  // Ensure it's before the start date
            ->selectRaw('SUM(CASE WHEN transaction_account.debit_credit = "debit" THEN transaction_account.amount ELSE -transaction_account.amount END) as balance')
            ->value('balance') ?? 0;  // Return balance or 0 if no value
    }

    // Fetch SOA (Statement of Account)
    public static function getStatementOfAccount($accountId, $startDate, $endDate)
    {
        // Step 1: Calculate the opening balance
        $openingBalance = self::getOpeningBalance($accountId, $startDate);

        // Step 2: Fetch transactions within the specified range
        $transactions = self::join('transactions', 'transactions.id', '=', 'transaction_account.transaction_id')
            ->where('transaction_account.account_id', $accountId)
            ->whereBetween('transactions.date', [$startDate, $endDate])  // Filter by date range
            ->select('transaction_account.amount', 'transaction_account.debit_credit', 'transactions.date', 'transactions.description')
            ->orderBy('transactions.date')  // Order by transaction date
            ->get();

        // Step 3: Calculate running balance
        $runningBalance = $openingBalance;
        $soa = $transactions->map(function ($entry) use (&$runningBalance) {
            // Calculate amount, applying debit or credit logic
            $amount = $entry->amount ?? 0;
            $amount = $entry->debit_credit === self::DEBIT ? $amount : -$amount;

            // Update running balance
            $runningBalance += $amount;

            // Return the formatted data for the SOA
            return [
                'date' => $entry->date,  // Transaction date
                'description' => $entry->description,
                'debit' => $entry->debit_credit === self::DEBIT ? $entry->amount : null,
                'credit' => $entry->debit_credit === self::CREDIT ? $entry->amount : null,
                'running_balance' => $runningBalance,
            ];
        });

        // Step 4: Prepend the opening balance to the SOA
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
