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
                    <button id="print" class="btn btn-custom-print" onclick="window.print();">
                        {{ __('word.print') }}
                    </button>
                    <div class="a4-width mx-auto">
                        <form method="GET" action="{{ route('report.profit_and_loss') }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="start_date">{{ __('word.start_date') }}:</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="{{ request('start_date', $startDate) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date">{{ __('word.end_date') }}:</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="{{ request('end_date', $endDate) }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="cost_center_id">{{ __('word.cost_center') }}:</label>
                                    <select name="cost_center_id" id="cost_center_id" class="form-control">
                                        <option value="">{{ __('word.all') }}</option>
                                        @foreach ($costCenters as $costCenter)
                                            <option value="{{ $costCenter->id }}"
                                                {{ request('cost_center_id') == $costCenter->id ? 'selected' : '' }}>
                                                {{ $costCenter->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit"
                                        class="bg-blue-500 text-white px-1 py-1 text-sm rounded">{{ __('word.filter') }}</button>
                                </div>
                            </div>
                        </form>
                        <div class="overflow-hidden shadow sm:rounded-lg bg-white p-6 print-container">

                            <!-- Logo and Title Section -->

                            <div class="flex items-center justify-between p-4 bg-gray-100 rounded-lg shadow-sm">
                                <!-- Title Section -->
                                <div class="text-2xl font-bold text-gray-800">
                                    بيان الإيرادات والمصروفات
                                </div>

                                <!-- Spacer (Optional, can be removed if not needed) -->
                                <div class="flex-grow"></div>

                                <!-- Logo Section -->
                                <div class="flex justify-end">
                                    <img src="{{ asset('images/yasmine.png') }}" alt="Logo" class="h-16 w-auto">
                                </div>
                            </div>

                            <!-- Display the Filters Applied -->
                            @if (request('start_date', $startDate) && request('end_date', $endDate))
                                <div class="mt-4">
                                    <p><strong>{{ __('word.filters_applied') }}:</strong>
                                        @if (request('cost_center_id', $costCenterId))
                                            {{ __('word.cost_center') }}:
                                            {{ $costCenters->where('id', request('cost_center_id', $costCenterId))->first()->name }}
                                            |
                                        @endif
                                        {{ __('word.period') }}: {{ request('start_date', $startDate) }}
                                        {{ __('word.to') }}
                                        {{ request('end_date', $endDate) }}
                                    </p>
                                </div>
                            @endif
                            <!-- Income Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h2>{{ __('word.income') }}</h2>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('word.account_code') }}</th>
                                                <th>{{ __('word.account_name') }}</th>
                                                <th class="text-end">{{ __('word.balance') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($incomeAccounts as $account)
                                                <tr class="font-semibold">
                                                    <td class="px-1 py-1 text-sm border border-gray-300">
                                                        {{ $account['account_code'] }}</td>
                                                    <td class="px-1 py-1 text-sm border border-gray-300">
                                                        {{ $account['account_name'] }}</td>
                                                    <td class="px-1 py-1 text-sm text-right border border-gray-300">
                                                        {{ number_format($account['balance'], 0) }}
                                                    </td>
                                                </tr>
                                                @if (!empty($account['children']))
                                                    @foreach ($account['children'] as $child)
                                                        <tr class="bg-gray-50">
                                                            <td class="px-1 py-1 text-sm border border-gray-300">
                                                                {{ $child['account_code'] }}</td>
                                                            <td class="px-1 py-1 text-sm border border-gray-300">
                                                                {{ $child['account_name'] }}</td>
                                                            <td class="px-1 py-1 text-sm border border-gray-300">
                                                                {{ number_format($child['balance'], 0) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">
                                                        {{ __('word.no_income_data') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">{{ __('word.total_income') }}</th>
                                                <th class="text-end">{{ number_format($totalIncome, 0) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- Expenses Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h2>{{ __('word.expenses') }}</h2>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('word.account_code') }}</th>
                                                <th>{{ __('word.account_name') }}</th>
                                                <th class="text-end">{{ __('word.balance') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($expenseAccounts as $account)
                                                <tr class="font-semibold">
                                                    <td class="px-1 py-1 text-sm border border-gray-300">
                                                        {{ $account['account_code'] }}</td>
                                                    <td class="px-1 py-1 text-sm border border-gray-300">
                                                        {{ $account['account_name'] }}</td>
                                                    <td class="px-1 py-1 text-sm border border-gray-300">
                                                        {{ number_format($account['balance'], 0) }}
                                                    </td>
                                                </tr>
                                                @if (!empty($account['children']))
                                                    @foreach ($account['children'] as $child)
                                                        <tr class="bg-gray-50">
                                                            <td class="px-1 py-1 text-sm border border-gray-300">
                                                                {{ $child['account_code'] }}</td>
                                                            <td class="px-1 py-1 text-sm border border-gray-300">
                                                                {{ $child['account_name'] }}</td>
                                                            <td class="px-1 py-1 text-sm border border-gray-300">
                                                                {{ number_format($child['balance'], 0) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">
                                                        {{ __('word.no_expense_data') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">{{ __('word.total_expenses') }}</th>
                                                <th class="text-end">{{ number_format($totalExpenses, 0) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
