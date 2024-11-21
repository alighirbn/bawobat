<h1>Trial Balance</h1>
<table>
    <thead>
        <tr>
            <th>Account</th>
            <th>Debits</th>
            <th>Credits</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trialBalance as $row)
            <tr>
                <td>{{ $row['account_name'] }}</td>
                <td>{{ number_format($row['debits'], 2) }}</td>
                <td>{{ number_format($row['credits'], 2) }}</td>
                <td>{{ number_format($row['balance'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td><strong>Total</strong></td>
            <td><strong>{{ number_format($totalDebits, 2) }}</strong></td>
            <td><strong>{{ number_format($totalCredits, 2) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<p>{{ $isBalanced ? 'The trial balance is balanced.' : 'The trial balance is NOT balanced.' }}</p>
