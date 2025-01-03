<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <div class="flex justify-start">
            @include('income.nav.navigation')
            @include('expense.nav.navigation')
            @include('transaction.nav.navigation')
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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

                    <div class="container  p-4 bg-white mx-auto font-semibold text-sm">
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>إنشاء قسيمة يومية </p>
                        </div>

                        <form id="form" class="form" action="{{ route('transaction.store') }}" method="POST">
                            @csrf
                            <div class="card shadow-sm mb-3">
                                <div class="card-header">
                                    <h5>تفاصيل القسيمة يومية</h5>
                                </div>
                                <div class="card-body">
                                    <div class="flex">
                                        <div class=" mx-1 my-1 w-full">
                                            <x-input-label for="description" class=" mb-1" :value="__('word.description')" />
                                            <x-text-input id="description" class="w-full block mt-1" type="text"
                                                value="{{ old('description') }}" name="description" />
                                            <x-input-error :messages="$errors->get('description')" class="w-full mt-2" />
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <div class=" mx-1 my-1 w-full">
                                            <x-input-label for="date" class=" mb-1" :value="__('word.date')" />
                                            <x-text-input id="date" class="w-full block mt-1" type="date"
                                                value="{{ old('date') }}" name="date" />
                                            <x-input-error :messages="$errors->get('date')" class="w-full mt-2" />
                                        </div>

                                        <div class=" mx-1 my-1 w-full">
                                            <x-input-label for="period_id" class="w-full mb-1" :value="__('word.period_id')" />
                                            <select id="period_id" class="w-full block mt-1 " name="period_id">
                                                @foreach ($activePeriods as $activePeriod)
                                                    <option value="{{ $activePeriod->id }}"
                                                        {{ old('period_id') == $activePeriod->id ? 'selected' : '' }}>
                                                        {{ $activePeriod->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('period_id')" class="w-full mt-2" />
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <!-- Debit Entries -->
                                <div class="card mx-1 w-full shadow-sm">
                                    <div class="card-header">
                                        <h5>قيود المدين - {{ __('word.to_account') }}</h5>
                                    </div>
                                    <div class="card-body" id="debit_entries">
                                        @foreach (old('debit', [0 => []]) as $index => $debit)
                                            <div class="debit-entry p-3 border rounded mb-3 bg-white text-gray-900">
                                                <div class="mx-1 my-1 w-full">
                                                    <label for="debit_account_id[]">الحساب</label>
                                                    <select name="debit[{{ $index }}][account_id]"
                                                        class="w-full block mt-1" required>
                                                        <option value="" disabled selected>اختر الحساب</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}"
                                                                {{ old("debit.$index.account_id") == $account->id ? 'selected' : '' }}>
                                                                {{ $account->name }} ({{ $account->code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="flex">

                                                    <div class="mx-1 my-1 w-full">
                                                        <label for="debit_amount[]">المبلغ</label>
                                                        <input type="text" name="debit[{{ $index }}][amount]"
                                                            class="w-full block mt-1 debit-amount"
                                                            value="{{ old("debit.$index.amount") }}" required>
                                                    </div>

                                                    <div class="mx-1 my-1 w-full">
                                                        <label for="debit_cost_center_id[]">مركز التكلفة </label>
                                                        <select name="debit[{{ $index }}][cost_center_id]"
                                                            required class="w-full block mt-1">

                                                            @foreach ($costCenters as $costCenter)
                                                                <option value="{{ $costCenter->id }}"
                                                                    {{ old("debit.$index.cost_center_id") == $costCenter->id ? 'selected' : '' }}>
                                                                    {{ $costCenter->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-custom-delete remove-entry mt-3">حذف</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-custom-show" id="add-debit-entry">إضافة قسيمة
                                        يومية
                                        مدين</button>
                                </div>
                                <!-- Credit Entries -->
                                <div class="card mx-1 w-full shadow-sm">
                                    <div class="card-header">
                                        <h5>قيود الدائن - {{ __('word.from_account') }}</h5>
                                    </div>
                                    <div class="card-body" id="credit_entries">
                                        @foreach (old('credit', [0 => []]) as $index => $credit)
                                            <div class="credit-entry p-3 border rounded mb-3 bg-white text-gray-900">
                                                <div class="mx-1 my-1 w-full">
                                                    <label for="credit_account_id[]">الحساب</label>
                                                    <select name="credit[{{ $index }}][account_id]"
                                                        class="w-full block mt-1" required>
                                                        <option value="" disabled selected>اختر الحساب</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}"
                                                                {{ old("credit.$index.account_id") == $account->id ? 'selected' : '' }}>
                                                                {{ $account->name }} ({{ $account->code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="flex">

                                                    <div class="mx-1 my-1 w-full">
                                                        <label for="credit_amount[]">المبلغ</label>
                                                        <input type="text"
                                                            name="credit[{{ $index }}][amount]"
                                                            class="w-full block mt-1 credit-amount"
                                                            value="{{ old("credit.$index.amount") }}" required>
                                                    </div>

                                                    <div class="mx-1 my-1 w-full">
                                                        <label for="credit_cost_center_id[]">مركز التكلفة </label>
                                                        <select name="credit[{{ $index }}][cost_center_id]"
                                                            required class="w-full block mt-1">

                                                            @foreach ($costCenters as $costCenter)
                                                                <option value="{{ $costCenter->id }}"
                                                                    {{ old("credit.$index.cost_center_id") == $costCenter->id ? 'selected' : '' }}>
                                                                    {{ $costCenter->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-custom-delete remove-entry mt-3">حذف</button>
                                                </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="button" class="btn btn-custom-show" id="add-credit-entry">إضافة
                                    قسيمة يومية
                                    دائن</button>
                            </div>

                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-custom-add">إنشاء القسيمة يومية</button>
                    </div>
                    <!-- Debit, Credit, and Balance -->
                    <div class="mt-4">
                        <div class="flex justify-between">
                            <div class="font-bold">إجمالي المدين:</div>
                            <div id="total-debit" class="text-gray-900">0</div>
                        </div>
                        <div class="flex justify-between">
                            <div class="font-bold">إجمالي الدائن:</div>
                            <div id="total-credit" class="text-gray-900">0</div>
                        </div>
                        <div class="flex justify-between">
                            <div class="font-bold">الرصيد:</div>
                            <div id="balance" class="text-gray-900">0</div>
                        </div>
                    </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    </div>

    <script>
        $(document).ready(function() {

            let debitCounter = 1;
            let creditCounter = 1;

            // Set today's date in the date input field
            function setTodayDate() {
                const today = new Date();
                const formattedDate = today.toISOString().split('T')[0]; // Format date as YYYY-MM-DD
                $('#date').val(formattedDate); // Set the value of the date input
            }

            // Generate the transaction description
            function generateDescription() {
                const debitAccounts = [];
                const creditAccounts = [];
                let totalAmount = 0;

                // Collect debit account names
                $('.debit-entry').each(function() {
                    const accountName = $(this).find('select[name$="[account_id]"] option:selected').text();
                    const amount = parseFloat($(this).find('input[name$="[amount]"]').val().replace(/,/g,
                        '')) || 0;

                    if (accountName && amount) {
                        debitAccounts.push(accountName.trim());
                        totalAmount += amount;
                    }
                });

                // Collect credit account names
                $('.credit-entry').each(function() {
                    const accountName = $(this).find('select[name$="[account_id]"] option:selected').text();
                    const amount = parseFloat($(this).find('input[name$="[amount]"]').val().replace(/,/g,
                        '')) || 0;

                    if (accountName && amount) {
                        creditAccounts.push(accountName.trim());
                    }
                });

                // Generate description
                let description = '';
                if (creditAccounts.length > 0 && debitAccounts.length > 0) {
                    description =
                        ` من حـ / ${creditAccounts.join('و حـ / ')} إلى حـ / ${debitAccounts.join('و حـ / ')}`;
                }

                // Update the description field
                $('#description').val(description);
            }

            // Trigger description generation on any input change
            $(document).on('change input',
                '.debit-entry select, .debit-entry input, .credit-entry select, .credit-entry input',
                generateDescription);

            // Initialize the form
            setTodayDate(); // Set today's date
            generateDescription(); // Initial call to generate description

            // Function to calculate and update totals
            function updateTotals() {
                let totalDebit = 0;
                let totalCredit = 0;

                // Calculate total debit
                $('.debit-amount').each(function() {
                    totalDebit += parseFloat($(this).val().replace(/,/g, '')) || 0;
                });

                // Calculate total credit
                $('.credit-amount').each(function() {
                    totalCredit += parseFloat($(this).val().replace(/,/g, '')) || 0;
                });

                // Update the total debit and credit
                $('#total-debit').text(totalDebit.toFixed(0));
                $('#total-credit').text(totalCredit.toFixed(0));

                // Update the balance (should show the difference between total debit and total credit)
                let balance = totalDebit - totalCredit;
                $('#balance').text(balance.toFixed());

                // Check if balanced
                if (totalDebit !== totalCredit) {
                    $('#balance').css('color', 'red');
                } else {
                    $('#balance').css('color', 'green');
                }
            }

            // Format amount inputs
            $(document).on('input', '.debit-amount, .credit-amount', function() {
                const input = $(this);
                let value = input.val();

                // Remove any non-numeric characters except for "." (for decimals)
                value = value.replace(/[^0-9.]/g, '');

                // Split the value into whole and decimal parts
                const parts = value.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Add commas to the whole part

                // Join the formatted parts
                const formattedValue = parts.join('.');
                input.val(formattedValue);

                // Update totals after input change
                updateTotals();
            });

            // Add new debit entry
            $('#add-debit-entry').on('click', function() {
                const debitContainer = $('#debit_entries');
                const newDebitEntry = `
        <div class="debit-entry p-3 border rounded mb-3 bg-white text-gray-900">
            <div class="mx-1 my-1 w-full">
                <label>الحساب</label>
                <select name="debit[${debitCounter}][account_id]" class="" required>
                    <option value="w-full block mt-1" disabled selected>اختر الحساب</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex">
            <div class="mx-1 my-1 w-full">
                <label>المبلغ</label>
                <input type="text" name="debit[${debitCounter}][amount]" class="w-full block mt-1 debit-amount" required>
            </div>
            <div class="mx-1 my-1 w-full">
                <label>مركز التكلفة</label>
                <select name="debit[${debitCounter}][cost_center_id]" class="w-full block mt-1" required>
                   
                    @foreach ($costCenters as $costCenter)
                        <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn btn-custom-delete remove-entry mt-3">حذف</button>
            </flex>
        </div>
    `;
                debitContainer.append(newDebitEntry);
                debitCounter++;

                // Update totals after adding new debit entry
                updateTotals();
            });

            // Add new credit entry
            $('#add-credit-entry').on('click', function() {
                const creditContainer = $('#credit_entries');
                const newCreditEntry = `
        <div class="credit-entry p-3 border rounded mb-3 bg-white text-gray-900">
            <div class="mx-1 my-1 w-full">
                <label>الحساب</label>
                <select name="credit[${creditCounter}][account_id]" class="w-full block mt-1" required>
                    <option value="" disabled selected>اختر الحساب</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex">
            <div class="mx-1 my-1 w-full">
                <label>المبلغ</label>
                <input type="text" name="credit[${creditCounter}][amount]" class="w-full block mt-1 credit-amount" required>
            </div>
            <div class="mx-1 my-1 w-full">
                <label>مركز التكلفة</label>
                <select name="credit[${creditCounter}][cost_center_id]" class="w-full block mt-1" required>
                   
                    @foreach ($costCenters as $costCenter)
                        <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn btn-custom-delete remove-entry mt-3">حذف</button>
            </flex>
        </div>
    `;
                creditContainer.append(newCreditEntry);
                creditCounter++;

                // Update totals after adding new credit entry
                updateTotals();
            });

            // Remove entry
            $(document).on('click', '.remove-entry', function() {
                $(this).closest('.debit-entry, .credit-entry').remove();

                // Update totals after removing an entry
                updateTotals();
            });

            // Form submission validation
            $('form').on('submit', function(event) {
                let totalDebit = 0;
                let totalCredit = 0;

                // Convert all debit and credit amounts to numbers
                $('.debit-amount').each(function() {
                    let amount = $(this).val().replace(/,/g, ''); // Remove commas
                    $(this).val(parseFloat(amount) || 0); // Set the input value to a valid number
                    totalDebit += parseFloat(amount) || 0;
                });

                $('.credit-amount').each(function() {
                    let amount = $(this).val().replace(/,/g, ''); // Remove commas
                    $(this).val(parseFloat(amount) || 0); // Set the input value to a valid number
                    totalCredit += parseFloat(amount) || 0;
                });

                // Check if the totals balance
                if (totalDebit !== totalCredit) {
                    event.preventDefault();
                    alert(
                        'القسيمة يومية المحاسبي غير متوازن. تأكد من أن إجمالي المدين يساوي إجمالي الدائن.'
                    );
                }
            });


            // Initial totals update
            updateTotals();
        });
    </script>

</x-app-layout>
