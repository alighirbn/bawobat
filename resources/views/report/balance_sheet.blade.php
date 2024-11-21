<div class="container">
    <h1>Balance Sheet</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Account Type</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Assets</strong></td>
                <td>{{ number_format($totalAssets, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Liabilities</strong></td>
                <td>{{ number_format($totalLiabilities, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Equity</strong></td>
                <td>{{ number_format($totalEquity, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Liabilities & Equity</strong></td>
                <td>{{ number_format($totalLiabilities + $totalEquity, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Is Balanced?</strong></td>
                <td>{{ $isBalanced ? 'Yes' : 'No' }}</td>
            </tr>
        </tbody>
    </table>
</div>
