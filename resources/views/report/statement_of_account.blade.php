<h1>Statement of Account: {{ $account->name }}</h1>
<p>From: {{ $startDate }} To: {{ $endDate }}</p>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Cost Center</th>
            <th>Debit</th>
            <th>Credit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->date }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ optional($transaction->pivot->cost_center)->name }}</td>
                <td>{{ $transaction->pivot->debit_credit === 'debit' ? $transaction->pivot->amount : '' }}</td>
                <td>{{ $transaction->pivot->debit_credit === 'credit' ? $transaction->pivot->amount : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<p>Total Debits: {{ number_format($totalDebits, 2) }}</p>
<p>Total Credits: {{ number_format($totalCredits, 2) }}</p>
<p>Balance: {{ number_format($balance, 2) }}</p>
