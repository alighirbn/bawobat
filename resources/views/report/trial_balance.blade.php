<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('report.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="a4-width mx-auto">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('report.trial_balance') }}" class="mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex">
                                    <!-- Start Date -->
                                    <div class="mx-4 my-4 w-full">
                                        <label for="start_date"
                                            class="block font-medium text-sm">{{ __('word.start_date') }}:</label>
                                        <input type="date" id="start_date" name="start_date"
                                            value="{{ request('start_date', $startDate) }}"
                                            class="w-full border-gray-300 rounded-md shadow-sm">
                                    </div>

                                    <!-- End Date -->
                                    <div class="mx-4 my-4 w-full">
                                        <label for="end_date"
                                            class="block font-medium text-sm">{{ __('word.end_date') }}:</label>
                                        <input type="date" id="end_date" name="end_date"
                                            value="{{ request('end_date', $endDate) }}"
                                            class="w-full border-gray-300 rounded-md shadow-sm">
                                    </div>

                                    <!-- Cost Center -->
                                    <div class="mx-4 my-4 w-full">
                                        <label for="cost_center_id"
                                            class="block font-medium text-sm">{{ __('word.cost_center') }}:</label>
                                        <select id="cost_center_id" name="cost_center_id"
                                            class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">-- {{ __('word.all') }} --</option>
                                            @foreach ($costCenters as $costCenter)
                                                <option value="{{ $costCenter->id }}"
                                                    {{ request('cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                                                    {{ $costCenter->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="flex">
                                    <!-- From Account (Select Input) -->
                                    <div class="mx-4 my-4 w-full">
                                        <label for="from_account"
                                            class="block font-medium text-sm">{{ __('word.from_account') }}:</label>
                                        <select id="from_account" name="from_account"
                                            class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">-- {{ __('word.select_account') }} --</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}"
                                                    {{ request('from_account') == $account->id ? 'selected' : '' }}>
                                                    {{ $account->name }} ({{ $account->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- To Account (Select Input) -->
                                    <div class="mx-4 my-4 w-full">
                                        <label for="to_account"
                                            class="block font-medium text-sm">{{ __('word.to_account') }}:</label>
                                        <select id="to_account" name="to_account"
                                            class="w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">-- {{ __('word.select_account') }} --</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}"
                                                    {{ request('to_account') == $account->id ? 'selected' : '' }}>
                                                    {{ $account->name }} ({{ $account->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex items-end">
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600">
                                        {{ __('word.apply_filters') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="overflow-hidden shadow sm:rounded-lg bg-white p-6">
                            <h1 class="text-xl font-bold mb-2">{{ __('word.trial_balance') }}</h1>

                            <!-- Applied Filters -->
                            <div class="text-gray-600 mb-4">
                                <strong>{{ __('word.applied_filters') }}:</strong>
                                {{ __('word.start_date') }}: {{ request('start_date', $startDate) ?? __('word.na') }},
                                {{ __('word.end_date') }}: {{ request('end_date', $endDate) ?? __('word.na') }},
                                {{ __('word.cost_center') }}: {{ $selectedCostCenter ?? __('word.all') }},
                                {{ __('word.from_account') }}: {{ $selectedFromAccount ?? __('word.na') }},
                                {{ __('word.to_account') }}: {{ $selectedToAccount ?? __('word.na') }}
                            </div>

                            <!-- Report Table -->
                            <table class="w-full border border-gray-300 border-collapse">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="px-1 py-1 text-sm text-left border border-gray-300">
                                            {{ __('word.account_code') }}</th>
                                        <th class="px-1 py-1 text-sm text-left border border-gray-300">
                                            {{ __('word.account_name') }}</th>
                                        <th class="px-1 py-1 text-sm text-right border border-gray-300">
                                            {{ __('word.debits') }}</th>
                                        <th class="px-1 py-1 text-sm text-right border border-gray-300">
                                            {{ __('word.credits') }}</th>
                                        <th class="px-1 py-1 text-sm text-right border border-gray-300">
                                            {{ __('word.balance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trialBalanceData as $row)
                                        <!-- Parent Account Row -->
                                        <tr class="font-semibold">
                                            <td class="px-1 py-1 text-sm border border-gray-300">
                                                {{ $row['account_code'] }}</td>
                                            <td class="px-1 py-1 text-sm border border-gray-300">
                                                {{ $row['account_name'] }}</td>
                                            <td class="px-1 py-1 text-sm text-right border border-gray-300">
                                                {{ number_format($row['debits'], 0) }}
                                            </td>
                                            <td class="px-1 py-1 text-sm text-right border border-gray-300">
                                                {{ number_format($row['credits'], 0) }}
                                            </td>
                                            <td
                                                class="px-1 py-1 text-sm text-right border border-gray-300 {{ $row['balance'] < 0 ? 'text-red-600' : '' }}">
                                                {{ number_format($row['balance'], 0) }}
                                            </td>
                                        </tr>

                                        <!-- Child Account Rows -->
                                        @foreach ($row['children'] as $child)
                                            <tr class="bg-gray-50">
                                                <td class="px-1 py-1 text-sm border border-gray-300 pl-8">
                                                    {{ $child['account_code'] }}</td>
                                                <td class="px-1 py-1 text-sm border border-gray-300">
                                                    {{ $child['account_name'] }}</td>
                                                <td class="px-1 py-1 text-sm text-right border border-gray-300">
                                                    {{ number_format($child['debits'], 0) }}
                                                </td>
                                                <td class="px-1 py-1 text-sm text-right border border-gray-300">
                                                    {{ number_format($child['credits'], 0) }}
                                                </td>
                                                <td
                                                    class="px-1 py-1 text-sm text-right border border-gray-300 {{ $child['balance'] < 0 ? 'text-red-600' : '' }}">
                                                    {{ number_format($child['balance'], 0) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="font-bold bg-gray-200">
                                        <th class="px-4 py-2 text-left" colspan="2">{{ __('word.totals') }}</th>
                                        <th class="px-4 py-2 text-right">{{ number_format($totalDebits, 0) }}</th>
                                        <th class="px-4 py-2 text-right">{{ number_format($totalCredits, 0) }}</th>
                                        <th
                                            class="px-4 py-2 text-right {{ $totalCredits - $totalDebits < 0 ? 'text-red-600' : '' }}">
                                            {{ number_format($totalCredits - $totalDebits, 0) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
