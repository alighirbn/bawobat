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
                        <h2 class="text-center">{{ __('word.statement_of_account') }}</h2>

                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('report.statement_of_account') }}">
                            <div class="flex">
                                <div class="mx-2 my-2 w-full">
                                    <label for="account_id" class="form-label">{{ __('word.account') }}</label>
                                    <select class="w-full block mt-1 " id="account_id" name="account_id" required>
                                        <option value="">{{ __('word.select_account') }}</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ old('account_id', request('account_id')) == $account->id ? 'selected' : '' }}>
                                                {{ $account->name }} ( {{ $account->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mx-2 my-2 w-full">
                                    <label for="cost_center_id" class="form-label">{{ __('word.cost_center') }}</label>
                                    <select class="w-full block mt-1 " id="cost_center_id" name="cost_center_id">
                                        <option value="">{{ __('word.select_cost_center') }}</option>
                                        @foreach ($costCenters as $costCenter)
                                            <option value="{{ $costCenter->id }}"
                                                {{ old('cost_center_id', request('cost_center_id')) == $costCenter->id ? 'selected' : '' }}>
                                                {{ $costCenter->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mx-2 my-2 w-full">
                                    <label for="start_date" class="form-label">{{ __('word.start_date') }}</label>
                                    <input type="date" class="w-full block mt-1 " id="start_date" name="start_date"
                                        value="{{ old('start_date', request('start_date', $startDate ?? '')) }}"
                                        required>
                                </div>
                                <div class="mx-2 my-2 w-full">
                                    <label for="end_date" class="form-label">{{ __('word.end_date') }}</label>
                                    <input type="date" class="w-full block mt-1 " id="end_date" name="end_date"
                                        value="{{ old('end_date', request('end_date', $endDate ?? '')) }}" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom-add">{{ __('word.filter') }}</button>
                        </form>
                        <div class="overflow-hidden shadow sm:rounded-lg bg-white p-6">

                            <!-- Display the Filters Applied -->
                            @if (request('account_id') && request('start_date') && request('end_date'))
                                <div class="mt-4">
                                    <p><strong>{{ __('word.filters_applied') }}:</strong> {{ __('word.account') }}:
                                        {{ $accountName }} | {{ __('word.cost_center') }}:
                                        {{ $costCenterName }} | {{ __('word.period') }}:
                                        {{ request('start_date') }} {{ __('word.to') }} {{ request('end_date') }}</p>
                                </div>
                            @endif
                            <!-- Table for Statement of Account -->
                            @if (request('account_id') && request('start_date') && request('end_date'))
                                <table class="table table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th style="width: 12%;">{{ __('word.date') }}</th>
                                            <th style="width: 43%;">{{ __('word.description') }}</th>
                                            <th style="width: 15%;">{{ __('word.debit') }}</th>
                                            <th style="width: 15%;">{{ __('word.credit') }}</th>
                                            <th style="width: 15%;">{{ __('word.running_balance') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($soa as $entry)
                                            <tr class="font-semibold">
                                                <td class="px-1 py-1 text-sm border border-gray-300">
                                                    {{ $entry['date'] }}</td>
                                                <td class="px-1 py-1 text-sm border border-gray-300">
                                                    {{ $entry['description'] }}</td>
                                                <td class="px-1 py-1 text-sm border border-gray-300">
                                                    {{ $entry['debit'] ? number_format($entry['debit'], 0) : '' }}</td>
                                                <td class="px-1 py-1 text-sm border border-gray-300">
                                                    {{ $entry['credit'] ? number_format($entry['credit'], 0) : '' }}
                                                </td>
                                                <td class="px-1 py-1 text-sm border border-gray-300">
                                                    {{ number_format($entry['running_balance'], 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    {{ __('word.no_records_found') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
