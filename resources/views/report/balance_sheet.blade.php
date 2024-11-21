<div class="container">
    <h1>Detailed Balance Sheet</h1>

    <!-- Display Assets -->
    <h3>Assets</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Debits</th>
                <th>Credits</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assetDetails as $asset)
                <tr>
                    <td>{{ $asset['account_name'] }}</td>
                    <td>{{ number_format($asset['debits'], 2) }}</td>
                    <td>{{ number_format($asset['credits'], 2) }}</td>
                    <td>{{ number_format($asset['balance'], 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>Total Assets</strong></td>
                <td>{{ number_format($totalAssets, 2) }}</td>
                <td>-</td>
                <td>{{ number_format($totalAssets, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Display Liabilities -->
    <h3>Liabilities</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Debits</th>
                <th>Credits</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($liabilityDetails as $liability)
                <tr>
                    <td>{{ $liability['account_name'] }}</td>
                    <td>{{ number_format($liability['debits'], 2) }}</td>
                    <td>{{ number_format($liability['credits'], 2) }}</td>
                    <td>{{ number_format($liability['balance'], 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>Total Liabilities</strong></td>
                <td>-</td>
                <td>{{ number_format($totalLiabilities, 2) }}</td>
                <td>{{ number_format($totalLiabilities, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Display Equity -->
    <h3>Equity</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Debits</th>
                <th>Credits</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equityDetails as $equityItem)
                <tr>
                    <td>{{ $equityItem['account_name'] }}</td>
                    <td>{{ number_format($equityItem['debits'], 2) }}</td>
                    <td>{{ number_format($equityItem['credits'], 2) }}</td>
                    <td>{{ number_format($equityItem['balance'], 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>Total Equity</strong></td>
                <td>-</td>
                <td>{{ number_format($totalEquity, 2) }}</td>
                <td>{{ number_format($totalEquity, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Balance Sheet Summary -->
    <h3>Balance Sheet Summary</h3>
    <table class="table">
        <tr>
            <td><strong>Total Assets</strong></td>
            <td>{{ number_format($totalAssets, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Total Liabilities</strong></td>
            <td>{{ number_format($totalLiabilities, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Total Equity</strong></td>
            <td>{{ number_format($totalEquity, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Is the Balance Sheet Balanced?</strong></td>
            <td>{{ $isBalanced ? 'Yes' : 'No' }}</td>
        </tr>
    </table>
</div>
