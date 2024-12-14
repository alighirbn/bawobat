<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <!-- select2 css and js-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        <div class="flex justify-start">
            @include('income.nav.navigation')
            @include('expense.nav.navigation')
            @include('transaction.nav.navigation')
        </div>

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>

                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                {{-- Loop through the errors and display each one --}}
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div>
                        <form method="post" action="{{ route('expense.store') }}">
                            @csrf
                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.expense_info') }}
                            </h1>
                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="cost_center_id" class="w-full mb-1" :value="__('word.cost_center_id')" />
                                    <select id="cost_center_id" class="js-example-basic-single w-full block mt-1 "
                                        name="cost_center_id" data-placeholder="ادخل مركز الكلفة   ">
                                        <option value="">

                                        </option>
                                        @foreach ($cost_centers as $cost_center)
                                            <option value="{{ $cost_center->id }}"
                                                {{ old('cost_center_id') == $cost_center->id ? 'selected' : '' }}>
                                                {{ $cost_center->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('cost_center_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="credit_account_id" class="w-full mb-1" :value="__('word.credit_account_id')" />
                                    <select id="credit_account_id" class="js-example-basic-single w-full block mt-1 "
                                        name="credit_account_id" data-placeholder="ادخل حساب النقد   ">
                                        <option value="">

                                        </option>
                                        @foreach ($cashAccounts as $cashAccount)
                                            <option value="{{ $cashAccount->id }}"
                                                {{ old('credit_account_id') == $cashAccount->id ? 'selected' : '' }}>
                                                {{ $cashAccount->name }} ({{ $cashAccount->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('credit_account_id')" class="w-full mt-2" />
                                </div>
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="debit_account_id" class="w-full mb-1" :value="__('word.debit_account_id')" />
                                    <select id="debit_account_id" class="js-example-basic-single w-full block mt-1 "
                                        name="debit_account_id" data-placeholder="ادخل حساب الصرف   ">
                                        <option value="">

                                        </option>
                                        @foreach ($expenseAccounts as $expenseAccount)
                                            <option value="{{ $expenseAccount->id }}"
                                                {{ old('debit_account_id') == $expenseAccount->id ? 'selected' : '' }}>
                                                {{ $expenseAccount->name }} ({{ $expenseAccount->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('debit_account_id')" class="w-full mt-2" />
                                </div>
                            </div>

                            <div class="flex">
                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="amount_display" class="w-full mb-1" :value="__('word.amount')" />

                                    <!-- Displayed input for formatted number -->
                                    <x-text-input id="amount_display" class="w-full block mt-1" type="text"
                                        value="{{ number_format(old('amount', 0), 0) }}" placeholder="0" />

                                    <!-- Hidden input for the actual number -->
                                    <input type="hidden" id="amount" name="amount" value="{{ old('amount') }}">

                                    <x-input-error :messages="$errors->get('amount')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="date" class="w-full mb-1" :value="__('word.date')" />
                                    <x-text-input id="date" class="w-full block mt-1" type="text" name="date"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                                    <x-input-error :messages="$errors->get('date')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="description" class="w-full mb-1" :value="__('word.description')" />
                                    <x-text-input id="description" class="w-full block mt-1" type="text"
                                        name="description" value="{{ old('description') }}" />
                                    <x-input-error :messages="$errors->get('description')" class="w-full mt-2" />
                                </div>

                            </div>

                            <div class=" mx-4 my-4 w-full">
                                <x-primary-button x-primary-button class="ml-4">
                                    {{ __('word.save') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var displayInput = document.getElementById('amount_display');
            var hiddenInput = document.getElementById('amount');

            function formatNumber(value) {
                return value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            function unformatNumber(value) {
                return value.replace(/,/g, '');
            }

            displayInput.addEventListener('input', function() {
                var formattedValue = formatNumber(displayInput.value);
                displayInput.value = formattedValue;
                hiddenInput.value = unformatNumber(formattedValue);
            });

            // On form submission, make sure the hidden input is set correctly
            document.querySelector('form').addEventListener('submit', function() {
                hiddenInput.value = unformatNumber(displayInput.value);
            });
        });
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        $('form').on('submit', function() {
            // Find the submit button
            var $submitButton = $(this).find('button[type="submit"]');

            // Change the button text to 'Submitting...'
            $submitButton.text('جاري الحفظ');

            // Disable the submit button
            $submitButton.prop('disabled', true);
        });
    </script>
</x-app-layout>
