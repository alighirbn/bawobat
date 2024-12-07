<x-app-layout>
    <x-slot name="header">
        @include('report.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="a4-width mx-auto">
                        <!-- Assets Section -->
                        <h3 class="text-lg font-bold">Assets</h3>

                        <!-- Current Assets -->
                        <h4 class="text-md font-semibold">Current Assets</h4>
                        <table class="table-bordered w-full text-sm border-collapse border border-gray-400 mb-6"
                            cellspacing="0" cellpadding="5">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-400 px-2 py-1">Account Name</th>
                                    <th class="border border-gray-400 px-2 py-1">Category</th>
                                    <th class="border border-gray-400 px-2 py-1">Debit</th>
                                    <th class="border border-gray-400 px-2 py-1">Credit</th>
                                    <th class="border border-gray-400 px-2 py-1">Children Balance</th>
                                    <th class="border border-gray-400 px-2 py-1 text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assetsCurrent as $asset)
                                    <tr>
                                        <td class="border border-gray-400 px-2 py-1">{{ $asset['account_name'] }}</td>
                                        <td class="border border-gray-400 px-2 py-1">{{ $asset['category'] }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($asset['debits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($asset['credits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1">
                                            @if (!empty($asset['children']))
                                                @foreach ($asset['children'] as $child)
                                                    <div>{{ $child['account_name'] }}: {{ $child['category'] }} -
                                                        {{ number_format($child['balance'], 0) }}</div>
                                                @endforeach
                                            @else
                                                <em>No Children</em>
                                            @endif
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($asset['balance'], 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Non-Current Assets -->
                        <h4 class="text-md font-semibold">Non-Current Assets</h4>
                        <table class="table-bordered w-full text-sm border-collapse border border-gray-400 mb-6"
                            cellspacing="0" cellpadding="5">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-400 px-2 py-1">Account Name</th>
                                    <th class="border border-gray-400 px-2 py-1">Category</th>
                                    <th class="border border-gray-400 px-2 py-1">Debit</th>
                                    <th class="border border-gray-400 px-2 py-1">Credit</th>
                                    <th class="border border-gray-400 px-2 py-1">Children Balance</th>
                                    <th class="border border-gray-400 px-2 py-1 text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assetsNonCurrent as $asset)
                                    <tr>
                                        <td class="border border-gray-400 px-2 py-1">{{ $asset['account_name'] }}</td>
                                        <td class="border border-gray-400 px-2 py-1">{{ $asset['category'] }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($asset['debits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($asset['credits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1">
                                            @if (!empty($asset['children']))
                                                @foreach ($asset['children'] as $child)
                                                    <div>{{ $child['account_name'] }}: {{ $child['category'] }} -
                                                        {{ number_format($child['balance'], 0) }}</div>
                                                @endforeach
                                            @else
                                                <em>No Children</em>
                                            @endif
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($asset['balance'], 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right mb-6">
                            <strong>Total Assets: {{ number_format($totalAssets, 0) }}</strong>
                        </div>

                        <!-- Liabilities Section -->
                        <h3 class="text-lg font-bold">Liabilities</h3>

                        <!-- Current Liabilities -->
                        <h4 class="text-md font-semibold">Current Liabilities</h4>
                        <table class="table-bordered w-full text-sm border-collapse border border-gray-400 mb-6"
                            cellspacing="0" cellpadding="5">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-400 px-2 py-1">Account Name</th>
                                    <th class="border border-gray-400 px-2 py-1">Category</th>
                                    <th class="border border-gray-400 px-2 py-1">Debit</th>
                                    <th class="border border-gray-400 px-2 py-1">Credit</th>
                                    <th class="border border-gray-400 px-2 py-1">Children Balance</th>
                                    <th class="border border-gray-400 px-2 py-1 text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($liabilitiesCurrent as $liability)
                                    <tr>
                                        <td class="border border-gray-400 px-2 py-1">{{ $liability['account_name'] }}
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1">{{ $liability['category'] }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($liability['debits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($liability['credits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1">
                                            @if (!empty($liability['children']))
                                                @foreach ($liability['children'] as $child)
                                                    <div>{{ $child['account_name'] }}: {{ $child['category'] }} -
                                                        {{ number_format($child['balance'], 0) }}</div>
                                                @endforeach
                                            @else
                                                <em>No Children</em>
                                            @endif
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($liability['balance'], 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Non-Current Liabilities -->
                        <h4 class="text-md font-semibold">Non-Current Liabilities</h4>
                        <table class="table-bordered w-full text-sm border-collapse border border-gray-400 mb-6"
                            cellspacing="0" cellpadding="5">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-400 px-2 py-1">Account Name</th>
                                    <th class="border border-gray-400 px-2 py-1">Category</th>
                                    <th class="border border-gray-400 px-2 py-1">Debit</th>
                                    <th class="border border-gray-400 px-2 py-1">Credit</th>
                                    <th class="border border-gray-400 px-2 py-1">Children Balance</th>
                                    <th class="border border-gray-400 px-2 py-1 text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($liabilitiesNonCurrent as $liability)
                                    <tr>
                                        <td class="border border-gray-400 px-2 py-1">{{ $liability['account_name'] }}
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1">{{ $liability['category'] }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($liability['debits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($liability['credits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1">
                                            @if (!empty($liability['children']))
                                                @foreach ($liability['children'] as $child)
                                                    <div>{{ $child['account_name'] }}: {{ $child['category'] }} -
                                                        {{ number_format($child['balance'], 0) }}</div>
                                                @endforeach
                                            @else
                                                <em>No Children</em>
                                            @endif
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($liability['balance'], 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right mb-6">
                            <strong>Total Liabilities: {{ number_format($totalLiabilities, 0) }}</strong>
                        </div>

                        <!-- Equity Section -->
                        <h3 class="text-lg font-bold">Equity</h3>

                        <!-- Current Equity -->
                        <h4 class="text-md font-semibold">Current Equity</h4>
                        <table class="table-bordered w-full text-sm border-collapse border border-gray-400 mb-6"
                            cellspacing="0" cellpadding="5">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-400 px-2 py-1">Account Name</th>
                                    <th class="border border-gray-400 px-2 py-1">Category</th>
                                    <th class="border border-gray-400 px-2 py-1">Debit</th>
                                    <th class="border border-gray-400 px-2 py-1">Credit</th>
                                    <th class="border border-gray-400 px-2 py-1">Children Balance</th>
                                    <th class="border border-gray-400 px-2 py-1 text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($equityCurrent as $equityItem)
                                    <tr>
                                        <td class="border border-gray-400 px-2 py-1">{{ $equityItem['account_name'] }}
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1">{{ $equityItem['category'] }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($equityItem['debits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($equityItem['credits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1">
                                            @if (!empty($equityItem['children']))
                                                @foreach ($equityItem['children'] as $child)
                                                    <div>{{ $child['account_name'] }}: {{ $child['category'] }} -
                                                        {{ number_format($child['balance'], 0) }}</div>
                                                @endforeach
                                            @else
                                                <em>No Children</em>
                                            @endif
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($equityItem['balance'], 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Non-Current Equity -->
                        <h4 class="text-md font-semibold">Non-Current Equity</h4>
                        <table class="table-bordered w-full text-sm border-collapse border border-gray-400 mb-6"
                            cellspacing="0" cellpadding="5">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-400 px-2 py-1">Account Name</th>
                                    <th class="border border-gray-400 px-2 py-1">Category</th>
                                    <th class="border border-gray-400 px-2 py-1">Debit</th>
                                    <th class="border border-gray-400 px-2 py-1">Credit</th>
                                    <th class="border border-gray-400 px-2 py-1">Children Balance</th>
                                    <th class="border border-gray-400 px-2 py-1 text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($equityNonCurrent as $equityItem)
                                    <tr>
                                        <td class="border border-gray-400 px-2 py-1">{{ $equityItem['account_name'] }}
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1">{{ $equityItem['category'] }}
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($equityItem['debits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($equityItem['credits'], 0) }}</td>
                                        <td class="border border-gray-400 px-2 py-1">
                                            @if (!empty($equityItem['children']))
                                                @foreach ($equityItem['children'] as $child)
                                                    <div>{{ $child['account_name'] }}: {{ $child['category'] }} -
                                                        {{ number_format($child['balance'], 0) }}</div>
                                                @endforeach
                                            @else
                                                <em>No Children</em>
                                            @endif
                                        </td>
                                        <td class="border border-gray-400 px-2 py-1 text-right">
                                            {{ number_format($equityItem['balance'], 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right mb-6">
                            <strong>Total Equity: {{ number_format($totalEquity, 0) }}</strong>
                        </div>

                        <!-- Balance Sheet Equation -->
                        <div class="mt-6 font-bold text-xl">
                            <p><strong>Balance Sheet Equation:</strong></p>
                            <p>Assets = Liabilities + Equity</p>
                            <p>
                                {{ number_format($totalAssets, 0) }} =
                                {{ number_format($totalLiabilities, 0) }} +
                                {{ number_format($totalEquity, 0) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
