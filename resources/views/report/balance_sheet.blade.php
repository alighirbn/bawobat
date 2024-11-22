<x-app-layout>
    <x-slot name="header">
        <h2>Balance Sheet</h2>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Assets Section -->
                    <h3>Assets</h3>
                    <table class="table bordered table-striped" border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>Children Balance</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assets as $asset)
                                <tr>
                                    <td>{{ $asset['account_name'] }}</td>
                                    <td>
                                        @foreach ($asset['children'] as $child)
                                            <div>{{ $child['account_name'] }}: {{ number_format($child['balance'], 2) }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{{ number_format($asset['balance'], 2) }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div><strong>Total Assets: {{ number_format($totalAssets, 2) }}</strong></div>

                    <!-- Liabilities Section -->
                    <h3>Liabilities</h3>
                    <table class="table bordered table-striped" border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>Children Balance</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($liabilities as $liability)
                                <tr>
                                    <td>{{ $liability['account_name'] }}</td>
                                    <td>
                                        @foreach ($liability['children'] as $child)
                                            <div>{{ $child['account_name'] }}: {{ number_format($child['balance'], 2) }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{{ number_format($liability['balance'], 2) }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div><strong>Total Liabilities: {{ number_format($totalLiabilities, 2) }}</strong></div>

                    <!-- Equity Section -->
                    <h3>Equity</h3>
                    <table class="table bordered table-striped" border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>Children Balance</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($equity as $equityItem)
                                <tr>
                                    <td>{{ $equityItem['account_name'] }}</td>
                                    <td>
                                        @foreach ($equityItem['children'] as $child)
                                            <div>{{ $child['account_name'] }}:
                                                {{ number_format($child['balance'], 2) }}</div>
                                        @endforeach
                                    </td>
                                    <td>{{ number_format($equityItem['balance'], 2) }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div><strong>Total Equity: {{ number_format($totalEquity, 2) }}</strong></div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
