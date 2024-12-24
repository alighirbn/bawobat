<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-start">
            <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
            @include('payments.nav.navigation')
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                    </div>
                    <div class="container">
                        <h1>{{ __('word.imported_payments') }}</h1>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('word.payment_id') }}</th>
                                    <th>{{ __('word.payment_date') }}</th>
                                    <th>{{ __('word.amount') }}</th>
                                    <th>{{ __('word.note') }}</th>
                                    <th>{{ __('word.imported_at') }}</th>
                                    <th>{{ __('word.transaction_id') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($importedPayments as $import)
                                    <tr>
                                        <td>{{ $import->payment->id }}</td>
                                        <td>{{ $import->payment->payment_date }}</td>
                                        <td>{{ number_format($import->payment->payment_amount, 0) }}</td>
                                        <td>{{ $import->payment->payment_note . ' - ' . $import->payment->contract->customer->customer_full_name . ' - ' . $import->payment->contract->building->building_number }}
                                        </td>
                                        <td>{{ $import->imported_at }}</td>
                                        <td>{{ $import->transaction_id }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
