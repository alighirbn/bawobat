<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <div class="flex justify-start">
            @include('account.nav.navigation')
            @include('costcenter.nav.navigation')
            @include('period.nav.navigation')
            @include('opening_balance.nav.navigation')
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto  text-gray-900 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mt-5">
                    <h2 class="text-center">{{ __('word.soa') }}</h2>
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
                    <form method="GET" action="{{ route('account.soa') }}">
                        <div class="flex">
                            <div class="mx-2 my-2 w-full">
                                <label for="account_id" class="form-label">{{ __('word.account_id') }}</label>
                                <select class="w-full block mt-1 " id="account_id" name="account_id" required>
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}"
                                            {{ old('account_id', request('account_id')) == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} ( {{ $account->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <label for="start_date" class="form-label">{{ __('word.start_date') }}</label>
                                <input type="date" class="w-full block mt-1 " id="start_date" name="start_date"
                                    value="{{ old('start_date', request('start_date', $startDate ?? '')) }}" required>
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <label for="end_date" class="form-label">{{ __('word.end_date') }}</label>
                                <input type="date" class="w-full block mt-1 " id="end_date" name="end_date"
                                    value="{{ old('end_date', request('end_date', $endDate ?? '')) }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-custom-add">{{ __('word.filter') }}</button>
                    </form>

                    <!-- Display the Filters Applied -->
                    @if (request('account_id') && request('start_date') && request('end_date'))
                        <div class="mt-4">
                            <p><strong>{{ __('word.filters_applied') }}:</strong> {{ __('word.account_id') }}:
                                {{ $accountName }} | {{ __('word.period') }}:
                                {{ request('start_date') }} {{ __('word.to') }} {{ request('end_date') }}</p>
                        </div>
                    @endif

                    <!-- Table for Statement of Account -->
                    @if (request('account_id') && request('start_date') && request('end_date'))
                        <table class="table table-bordered mt-4">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Running Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($soa as $entry)
                                    <tr>
                                        <td>{{ $entry['date'] }}</td>
                                        <td>{{ $entry['description'] }}</td>
                                        <td>{{ $entry['debit'] ? number_format($entry['debit'], 0) : '' }}</td>
                                        <td>{{ $entry['credit'] ? number_format($entry['credit'], 0) : '' }}</td>
                                        <td>{{ number_format($entry['running_balance'], 0) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No records found for the selected
                                            filters.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
