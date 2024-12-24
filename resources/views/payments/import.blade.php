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
                            <p>{{ __($message) }}</p>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            <p>{{ __($message) }}</p>
                        </div>
                    @endif
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                    </div>
                    <h1>{{ __('word.import_payments') }}</h1>

                    <!-- Live Count and Sum Display -->
                    <div class="mb-4">
                        <p><strong>{{ __('word.selected_count') }}:</strong> <span id="selected-count">0</span></p>
                        <p><strong>{{ __('word.total_amount') }}:</strong> <span id="total-amount">0</span></p>
                    </div>

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
                                                    value="{{ $payment->id }}" class="payment-checkbox"
                                                    data-amount="{{ $payment->payment_amount }}">
                                            @endif
                                        </td>
                                        <td>{{ $payment->payment_date }}</td>
                                        <td>{{ number_format($payment->payment_amount, 0) }}</td>
                                        <td>{{ $payment->payment_note . ' - ' . $payment->contract->customer->customer_full_name . ' - ' . $payment->contract->building->building_number }}
                                        </td>
                                        <td>
                                            {{ in_array($payment->id, $importedPayments) ? __('word.yes') : __('word.no') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit"
                            class="btn btn-custom-archive">{{ __('word.import_selected_payments') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.payment-checkbox');
            const selectedCount = document.getElementById('selected-count');
            const totalAmount = document.getElementById('total-amount');

            const updateSummary = () => {
                let count = 0;
                let sum = 0;

                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        count++;
                        sum += parseFloat(checkbox.getAttribute('data-amount'));
                    }
                });

                selectedCount.textContent = count;
                totalAmount.textContent = new Intl.NumberFormat().format(sum);
            };

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSummary);
            });
        });
    </script>
</x-app-layout>
