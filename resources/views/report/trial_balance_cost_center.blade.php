<!DOCTYPE html>
<html>

    <head>
        <title>Trial Balance by Cost Center</title>
    </head>

    <body>
        <h1>Trial Balance by Cost Center</h1>

        <table border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>Cost Center</th>
                    <th>Debits</th>
                    <th>Credits</th>
                    <th>Net Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trialBalanceByCostCenter as $row)
                    <tr>
                        <td>{{ $row['cost_center_name'] }}</td>
                        <td>{{ number_format($row['debits'], 2) }}</td>
                        <td>{{ number_format($row['credits'], 2) }}</td>
                        <td>{{ number_format($row['balance'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>{{ number_format($trialBalanceByCostCenter->sum('debits'), 2) }}</strong></td>
                    <td><strong>{{ number_format($trialBalanceByCostCenter->sum('credits'), 2) }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </body>

</html>
