<x-app-layout>
    <x-slot name="header">
        @include('report.nav.navigation')
    </x-slot>

    <div class="bg-gray-100 py-6">
        <div class="a4-width text-gray-700 mx-auto sm:px-6 lg:px-8">
            <!-- Date Filter Form -->
            <form method="GET" action="{{ route('report.balance-Sheet') }}" class="mb-6">
                <div class="flex items-center gap-4">
                    <label for="asOfDate" class="font-semibold text-gray-700">As of Date:</label>
                    <input type="date" id="asOfDate" name="as_of_date" value="{{ request('as_of_date', $asOfDate) }}"
                        class="border border-gray-300 rounded px-2 py-1" />
                    <button type="submit" class="bg-blue-500 text-white px-1 py-1 text-sm  rounded">
                        Filter
                    </button>
                </div>
            </form>

            <!-- Balance Sheet -->
            <div class="overflow-hidden shadow sm:rounded-lg bg-white p-6">
                <h2 class="text-xl font-bold mb-4">Balance Sheet (As of {{ $asOfDate }})</h2>

                <!-- Single Table for Assets, Liabilities, and Equity -->
                <table class="min-w-full border border-gray-300 border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-1 py-1 text-sm  text-left border border-gray-300">Category</th>
                            <th class="px-1 py-1 text-sm  text-left border border-gray-300">Account Code</th>
                            <!-- New Column -->
                            <th class="px-1 py-1 text-sm  text-left border border-gray-300">Account Name</th>
                            <th class="px-1 py-1 text-sm  text-right border border-gray-300">Debits</th>
                            <th class="px-1 py-1 text-sm  text-right border border-gray-300">Credits</th>
                            <th class="px-1 py-1 text-sm  text-right border border-gray-300">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Assets Section -->
                        <tr class="font-bold bg-gray-200">
                            <td colspan="6" class="border border-gray-300">Assets</td> <!-- Adjusted colspan -->
                        </tr>

                        <!-- Current Assets -->
                        <tr class="font-semibold bg-gray-100">
                            <td colspan="6" class="border border-gray-300">Current Assets</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($assetsCurrent as $asset)
                            <tr>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $asset['account_code'] }}</td>
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <!-- Account Code -->
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $asset['account_name'] }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($asset['debits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($asset['credits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($asset['balance'], 0) }}</td>
                            </tr>
                            @foreach ($asset['children'] as $child)
                                <tr class="bg-gray-200">
                                    <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_code'] }}
                                    </td>
                                    <!-- Account Code -->
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_name'] }}
                                    </td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['debits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['credits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['balance'], 0) }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                        <!-- Non-Current Assets -->
                        <tr class="font-semibold bg-gray-100">
                            <td colspan="6" class="border border-gray-300">Non-Current Assets</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($assetsNonCurrent as $asset)
                            <tr>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $asset['account_code'] }}</td>
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <!-- Account Code -->
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $asset['account_name'] }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($asset['debits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($asset['credits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($asset['balance'], 0) }}</td>
                            </tr>
                            @foreach ($asset['children'] as $child)
                                <tr class="bg-gray-200">
                                    <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_code'] }}
                                    </td>
                                    <!-- Account Code -->
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_name'] }}
                                    </td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['debits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['credits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['balance'], 0) }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                        <!-- Liabilities Section -->
                        <tr class="font-bold bg-gray-200">
                            <td colspan="6" class="border border-gray-300">Liabilities</td> <!-- Adjusted colspan -->
                        </tr>

                        <!-- Current Liabilities -->
                        <tr class="font-semibold bg-gray-100">
                            <td colspan="6" class="border border-gray-300">Current Liabilities</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($liabilitiesCurrent as $liability)
                            <tr>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $liability['account_code'] }}
                                </td>
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <!-- Account Code -->
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $liability['account_name'] }}
                                </td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($liability['debits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($liability['credits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($liability['balance'], 0) }}</td>
                            </tr>
                            @foreach ($liability['children'] as $child)
                                <tr class="bg-gray-200">
                                    <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_code'] }}
                                    </td>
                                    <!-- Account Code -->
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_name'] }}
                                    </td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['debits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['credits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['balance'], 0) }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                        <!-- Non-Current Liabilities -->
                        <tr class="font-semibold bg-gray-100">
                            <td colspan="6" class="border border-gray-300">Non-Current Liabilities</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($liabilitiesNonCurrent as $liability)
                            <tr>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $liability['account_code'] }}
                                </td>
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <!-- Account Code -->
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $liability['account_name'] }}
                                </td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($liability['debits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($liability['credits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($liability['balance'], 0) }}</td>
                            </tr>
                            @foreach ($liability['children'] as $child)
                                <tr class="bg-gray-200">
                                    <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_code'] }}
                                    </td>
                                    <!-- Account Code -->
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_name'] }}
                                    </td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['debits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['credits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['balance'], 0) }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                        <!-- Repeat similar structure for Equity section -->
                        <!-- Equity Section -->
                        <tr class="font-bold bg-gray-200">
                            <td colspan="6" class="border border-gray-300">Equity</td> <!-- Adjusted colspan -->
                        </tr>

                        <!-- Current Equity -->
                        <tr class="font-semibold bg-gray-100">
                            <td colspan="6" class="border border-gray-300">Current Equity</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($equityCurrent as $equity)
                            <tr>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $equity['account_code'] }}
                                </td>
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <!-- Account Code -->
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $equity['account_name'] }}
                                </td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($equity['debits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($equity['credits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($equity['balance'], 0) }}</td>
                            </tr>
                            @foreach ($equity['children'] as $child)
                                <tr class="bg-gray-200">
                                    <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_code'] }}
                                    </td>
                                    <!-- Account Code -->
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_name'] }}
                                    </td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['debits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['credits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['balance'], 0) }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                        <!-- Non-Current Equity -->
                        <tr class="font-semibold bg-gray-100">
                            <td colspan="6" class="border border-gray-300">Non-Current Equity</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($equityNonCurrent as $equity)
                            <tr>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $equity['account_code'] }}
                                </td>
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <!-- Account Code -->
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $equity['account_name'] }}
                                </td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($equity['debits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($equity['credits'], 0) }}</td>
                                <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                    {{ number_format($equity['balance'], 0) }}</td>
                            </tr>
                            @foreach ($equity['children'] as $child)
                                <tr class="bg-gray-200">
                                    <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_code'] }}
                                    </td>
                                    <!-- Account Code -->
                                    <td class="px-1 py-1 text-sm  border border-gray-300">{{ $child['account_name'] }}
                                    </td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['debits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['credits'], 0) }}</td>
                                    <td class="px-1 py-1 text-sm  text-right border border-gray-300">
                                        {{ number_format($child['balance'], 0) }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                    </tbody>
                </table>

                <!-- Totals -->
                <div class="border-t mt-4 pt-4">
                    <h4 class="text-lg font-bold">Totals</h4>
                    <div class="flex justify-between">
                        <span>Total Assets</span>
                        <span>{{ number_format($totalAssets, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Liabilities</span>
                        <span>{{ number_format($totalLiabilities, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Equity</span>
                        <span>{{ number_format($totalEquity, 0) }}</span>
                    </div>

                    <!-- Balance Sheet Equation -->
                    <div class="mt-4">
                        <h5 class="font-semibold text-gray-700">Accounting Equation</h5>
                        <p class="text-sm">
                            <strong>Assets</strong> = <strong>Liabilities</strong> + <strong>Equity</strong>
                        </p>
                        <div class="flex justify-between">
                            <span>Assets</span>
                            <span>{{ number_format($totalAssets, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Liabilities + Equity</span>
                            <span>{{ number_format($totalLiabilities + $totalEquity, 0) }}</span>
                        </div>

                        <div class="mt-2">
                            <!-- Check if Balance Sheet is Balanced -->
                            @php
                                $difference = $totalAssets - ($totalLiabilities + $totalEquity);
                            @endphp

                            @if ($difference == 0)
                                <p class="text-green-500">The balance sheet is balanced!</p>
                            @else
                                <p class="text-red-500">The balance sheet is not balanced. The difference is
                                    {{ number_format(abs($difference), 0) }}.</p>
                                <!-- Adjust Equity dynamically based on the difference -->
                                <p class="text-orange-500">Adjusting Total Equity to balance the sheet by
                                    {{ number_format(abs($difference), 0) }}.</p>

                                @php
                                    // Adjust equity only when necessary
                                    if ($difference > 0) {
                                        $totalEquity += $difference; // Add the difference to equity if Assets > Liabilities + Equity
                                    } else {
                                        $totalEquity -= abs($difference); // Subtract the difference if Liabilities + Equity > Assets
                                    }
                                @endphp

                                <!-- Display Adjusted Total Equity -->
                                <div class="flex justify-between mt-4">
                                    <span>Updated Total Equity</span>
                                    <span>{{ number_format($totalEquity, 0) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
