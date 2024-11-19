<x-app-layout>

    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/select2.min.css') }}" />
        <script src="{{ asset('js/select2.min.js') }}"></script>
        <div class="flex justify-start">
            @include('income.nav.navigation')

        </div>

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <form method="post" action="{{ route('income.update', $income->url_address) }}">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $income->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $income->url_address }}">

                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.income_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="project_id" class="w-full mb-1" :value="__('word.project_id')" />
                                    <select id="project_id" class="w-full block mt-1 " name="project_id">
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ (old('project_id') ?? $project->project_id) == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('project_id')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="income_type_id" class="w-full mb-1" :value="__('word.income_type_id')" />
                                    <select id="income_type_id" class="w-full block mt-1 " name="income_type_id">
                                        @foreach ($income_types as $income_type)
                                            <option value="{{ $income_type->id }}"
                                                {{ (old('income_type_id') ?? $income_type->id) == $income_type->id ? 'selected' : '' }}>
                                                {{ $income_type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('income_type_id')" class="w-full mt-2" />
                                </div>

                            </div>

                            <div class="flex">

                                <div class="mx-4 my-4 w-full">
                                    <x-input-label for="amount_display" class="w-full mb-1" :value="__('word.amount')" />

                                    <!-- Displayed input for formatted number -->
                                    <x-text-input id="amount_display" class="w-full block mt-1" type="text"
                                        value="{{ number_format(old('amount') ?? $income->amount, 0) }}"
                                        placeholder="0" />

                                    <!-- Hidden input for the actual number -->
                                    <input type="hidden" id="amount" name="amount"
                                        value="{{ old('amount') ?? $income->amount }}">

                                    <x-input-error :messages="$errors->get('amount')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="date" class="w-full mb-1" :value="__('word.date')" />
                                    <x-text-input id="date" class="w-full block mt-1" type="text" name="date"
                                        value="{{ old('date') ?? $income->date }}" />
                                    <x-input-error :messages="$errors->get('date')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="description" class="w-full mb-1" :value="__('word.description')" />
                                    <x-text-input id="description" class="w-full block mt-1" type="text"
                                        name="description" value="{{ old('description') ?? $income->description }}" />
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
