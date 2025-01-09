<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('report.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="a4-width text-gray-700 mx-auto sm:px-6 lg:px-8">
            <button id="print" class="btn btn-custom-print" onclick="window.print();">
                {{ __('word.print') }}
            </button>
            <!-- Date Filter Form -->
            <form method="GET" action="{{ route('report.balance_sheet') }}" class="mb-6">
                <div class="flex items-center gap-4">
                    <!-- As of Date Filter -->
                    <label for="asOfDate" class="font-semibold text-gray-700">{{ __('word.as_of_date') }}</label>
                    <input type="date" id="asOfDate" name="as_of_date"
                        value="{{ request('as_of_date', $asOfDate) }}"
                        class="border border-gray-300 rounded px-2 py-1" />

                    <!-- Cost Center Filter -->
                    <label for="costCenter" class="font-semibold text-gray-700">{{ __('word.cost_center') }}</label>
                    <select id="costCenter" name="cost_center_id" class="border border-gray-300 rounded px-2 py-1">
                        <option value="">{{ __('word.select_cost_center') }}</option>
                        @foreach ($costCenters as $costCenter)
                            <option value="{{ $costCenter->id }}"
                                {{ request('cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                                {{ $costCenter->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Submit Button -->
                    <button type="submit" class="bg-blue-500 text-white px-1 py-1 text-sm rounded">
                        {{ __('word.filter') }}
                    </button>
                </div>
            </form>

            <!-- Balance Sheet -->
            <div class="overflow-hidden shadow sm:rounded-lg bg-white p-6 print-container">
                <h2 class="text-xl font-bold mb-4">
                    {{ __('word.balance_sheet_as_of', ['date' => $asOfDate]) }}
                    @if ($costCenterId)
                        - {{ $costCenters->where('id', $costCenterId)->first()->name }}
                    @endif
                </h2>

                <!-- Single Table for Assets, Liabilities, and Equity -->
                <table class="w-full border border-gray-300 border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-1 py-1 text-sm  text-left border border-gray-300">
                                {{ __('word.category') }}</th>
                            <th class="px-1 py-1 text-sm  text-left border border-gray-300">
                                {{ __('word.account_code') }}</th>
                            <!-- New Column -->
                            <th class="px-1 py-1 text-sm  text-left border border-gray-300">
                                {{ __('word.account_name') }}</th>
                            <th class="px-1 py-1 text-sm  text-right border border-gray-300">
                                {{ __('word.debits') }}</th>
                            <th class="px-1 py-1 text-sm  text-right border border-gray-300">
                                {{ __('word.credits') }}</th>
                            <th class="px-1 py-1 text-sm  text-right border border-gray-300">
                                {{ __('word.balance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Assets Section -->
                        <tr class="font-bold bg-gray-200">
                            <td colspan="6" class="border border-gray-300">{{ __('word.assets') }}</td>
                            <!-- Adjusted colspan -->
                        </tr>

                        <!-- Current Assets -->
                        <tr class="font-semibold bg-gray-100">
                            <td colspan="6" class="border border-gray-300">{{ __('word.current_assets') }}
                            </td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($assetsCurrent as $asset)
                            <tr class="font-semibold">
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $asset['account_code'] }}</td>
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
                                <tr class="bg-gray-50">
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
                            <td colspan="6" class="border border-gray-300">
                                {{ __('word.non_current_assets') }}</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($assetsNonCurrent as $asset)
                            <tr class="font-semibold">
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $asset['account_code'] }}</td>
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
                                <tr class="bg-gray-50">
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
                            <td colspan="6" class="border border-gray-300">{{ __('word.liabilities') }}
                            </td> <!-- Adjusted colspan -->
                        </tr>

                        <!-- Current Liabilities -->
                        <tr class="font-semibold bg-gray-100">
                            <td colspan="6" class="border border-gray-300">
                                {{ __('word.current_liabilities') }}</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($liabilitiesCurrent as $liability)
                            <tr class="font-semibold">
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $liability['account_code'] }}
                                </td>
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
                                <tr class="bg-gray-50">
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
                            <td colspan="6" class="border border-gray-300">
                                {{ __('word.non_current_liabilities') }}</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($liabilitiesNonCurrent as $liability)
                            <tr class="font-semibold">
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $liability['account_code'] }}
                                </td>
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
                                <tr class="bg-gray-50">
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
                            <td colspan="6" class="border border-gray-300">{{ __('word.equity') }}</td>
                            <!-- Adjusted colspan -->
                        </tr>

                        <!-- Current Equity -->
                        <tr class="font-semibold bg-gray-100">
                            <td colspan="6" class="border border-gray-300">
                                {{ __('word.current_equity') }}</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($equityCurrent as $equity)
                            <tr class="font-semibold">
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $equity['account_code'] }}
                                </td>
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
                                <tr class="bg-gray-50">
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
                            <td colspan="6" class="border border-gray-300">
                                {{ __('word.non_current_equity') }}</td>
                            <!-- Adjusted colspan -->
                        </tr>
                        @foreach ($equityNonCurrent as $equity)
                            <tr class="font-semibold">
                                <td class="px-1 py-1 text-sm  border border-gray-300"></td>
                                <td class="px-1 py-1 text-sm  border border-gray-300">{{ $equity['account_code'] }}
                                </td>
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
                                <tr class="bg-gray-50">
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
                    <h4 class="text-lg font-bold">{{ __('word.totals') }}</h4>
                    <div class="flex justify-between">
                        <span>{{ __('word.total_assets') }}</span>
                        <span>{{ number_format($totalAssets, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('word.total_liabilities') }}</span>
                        <span>{{ number_format($totalLiabilities, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('word.total_equity') }}</span>
                        <span>{{ number_format($totalEquity, 0) }}</span>
                    </div>

                    <!-- Balance Sheet Equation -->
                    <div class="mt-4">
                        <h5 class="font-semibold text-gray-700">{{ __('word.accounting_equation') }}</h5>
                        <p class="text-sm">
                            <strong>{{ __('word.assets_equation') }}</strong>
                        </p>
                        <div class="flex justify-between">
                            <span>{{ __('word.assets') }}</span>
                            <span>{{ number_format($totalAssets, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('word.liabilities_plus_equity') }}</span>
                            <span>{{ number_format($totalLiabilities + $totalEquity, 0) }}</span>
                        </div>

                        <div class="mt-2">
                            <!-- Check if Balance Sheet is Balanced -->
                            @php
                                $difference = $totalAssets - ($totalLiabilities + $totalEquity);
                            @endphp

                            @if ($difference == 0)
                                <p class="text-green-500">{{ __('word.word_balanced') }}</p>
                            @else
                                <p class="text-red-500">
                                    {{ __('word.balance_sheet_not_balanced', ['difference' => number_format(abs($difference), 0)]) }}
                                </p>
                                <!-- Adjust Equity dynamically based on the difference -->
                                <p class="text-orange-500">
                                    {{ __('word.adjusting_equity', ['amount' => number_format(abs($difference), 0)]) }}
                                </p>

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
                                    <span>{{ __('word.updated_total_equity') }}</span>
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
