<!DOCTYPE html>
<html>

    <head>
        <title>Cost Center Report</title>
    </head>

    <body>
        <h1>Cost Center Report: {{ $costCenter->name }}</h1>
        <p>Description: {{ $costCenter->description }}</p>
        <p>From: {{ $startDate }} To: {{ $endDate }}</p>

        <table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->date }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->pivot->debit_credit === 'debit' ? number_format($transaction->pivot->amount, 2) : '' }}
                        </td>
                        <td>{{ $transaction->pivot->debit_credit === 'credit' ? number_format($transaction->pivot->amount, 2) : '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><strong>Total</strong></td>
                    <td><strong>{{ number_format($totalDebits, 2) }}</strong></td>
                    <td><strong>{{ number_format($totalCredits, 2) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Net Balance</strong></td>
                    <td colspan="2"><strong>{{ number_format($balance, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </body>

</html>
