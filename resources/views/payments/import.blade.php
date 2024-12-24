<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-start">
            @include('payments.nav.navigation')
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ __($message) }}</p>
                        </div>
                    @endif
                    <h1>{{ __('word.import_payments') }}</h1>

                    <!-- Success message if any -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ __(session('success')) }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('payments.import.post') }}">
                        @csrf

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('word.select') }}</th>
                                    <th>{{ __('word.payment_date') }}</th>
                                    <th>{{ __('word.amount') }}</th>
                                    <th>{{ __('word.note') }}</th>
                                    <th>{{ __('word.imported') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>
                                            @if (!in_array($payment->id, $importedPayments))
                                                <input type="checkbox" name="selected_payments[]"
                                                    value="{{ $payment->id }}">
                                            @endif
                                        </td>
                                        <td>{{ $payment->payment_date }}</td>
                                        <td>{{ $payment->payment_amount }}</td>
                                        <td>{{ $payment->payment_note }}</td>
                                        <td>
                                            {{ in_array($payment->id, $importedPayments) ? __('word.yes') : __('word.no') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit"
                            class="btn btn-primary">{{ __('word.import_selected_payments') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
