<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <div class="flex justify-start">
            @include('account.nav.navigation')
            @include('income.nav.navigation')
            @include('expense.nav.navigation')
            @include('costcenter.nav.navigation')
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

                    <div class="container a4-width p-4 bg-white mx-auto">
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>إنشاء قيد محاسبي</p>
                        </div>

                        <form id="form" class="form" action="{{ route('transaction.store') }}" method="POST">
                            @csrf
                            <div class="card shadow-sm mb-3">
                                <div class="card-header">
                                    <h5>تفاصيل القيد</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="description">الوصف</label>
                                        <input type="text" name="description" id="description" class="form-control"
                                            value="{{ old('description') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="date">التاريخ</label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            value="{{ old('date') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="flex space-x-3">

                                <!-- Credit Entries -->
                                <div class="card mx-1 w-full shadow-sm">
                                    <div class="card-header">
                                        <h5>قيود الدائن</h5>
                                    </div>
                                    <div class="card-body" id="credit_entries">
                                        @foreach (old('credit', [0 => []]) as $index => $credit)
                                            <div class="credit-entry p-3 border rounded mb-3 bg-white text-gray-900">
                                                <div class="form-group">
                                                    <label for="credit_account_id[]">الحساب</label>
                                                    <select name="credit[{{ $index }}][account_id]"
                                                        class="" required>
                                                        <option value="" disabled selected>اختر الحساب</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}"
                                                                {{ old("credit.$index.account_id") == $account->id ? 'selected' : '' }}>
                                                                {{ $account->name }} ({{ $account->code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="credit_amount[]">المبلغ</label>
                                                    <input type="text" name="credit[{{ $index }}][amount]"
                                                        class="form-control credit-amount"
                                                        value="{{ old("credit.$index.amount") }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="credit_cost_center_id[]">مركز التكلفة </label>
                                                    <select name="credit[{{ $index }}][cost_center_id]" required
                                                        class="">
                                                        <option value="" disabled selected>اختر مركز التكلفة
                                                        </option>
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
                                    <button type="button" class="btn btn-custom-show" id="add-credit-entry">إضافة قيد
                                        دائن</button>
                                </div>
                                <!-- Debit Entries -->
                                <div class="card mx-1 w-full shadow-sm">
                                    <div class="card-header">
                                        <h5>قيود المدين</h5>
                                    </div>
                                    <div class="card-body" id="debit_entries">
                                        @foreach (old('debit', [0 => []]) as $index => $debit)
                                            <div class="debit-entry p-3 border rounded mb-3 bg-white text-gray-900">
                                                <div class="form-group">
                                                    <label for="debit_account_id[]">الحساب</label>
                                                    <select name="debit[{{ $index }}][account_id]"
                                                        class="" required>
                                                        <option value="" disabled selected>اختر الحساب</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}"
                                                                {{ old("debit.$index.account_id") == $account->id ? 'selected' : '' }}>
                                                                {{ $account->name }} ({{ $account->code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="debit_amount[]">المبلغ</label>
                                                    <input type="text" name="debit[{{ $index }}][amount]"
                                                        class="form-control debit-amount"
                                                        value="{{ old("debit.$index.amount") }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="debit_cost_center_id[]">مركز التكلفة </label>
                                                    <select name="debit[{{ $index }}][cost_center_id]" required
                                                        class="">
                                                        <option value="" disabled selected>اختر مركز التكلفة
                                                        </option>
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
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-custom-show" id="add-debit-entry">إضافة قيد
                                        مدين</button>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-custom-add">إنشاء القيد</button>
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
                        `المبلغ: ${totalAmount.toLocaleString('en-US')} -- من ${creditAccounts.join(', ')} إلى ${debitAccounts.join(', ')}`;
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
            <div class="form-group">
                <label>الحساب</label>
                <select name="debit[${debitCounter}][account_id]" class="" required>
                    <option value="" disabled selected>اختر الحساب</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>المبلغ</label>
                <input type="text" name="debit[${debitCounter}][amount]" class="form-control debit-amount" required>
            </div>
            <div class="form-group">
                <label>مركز التكلفة</label>
                <select name="debit[${debitCounter}][cost_center_id]" class="" required>
                    <option value="" disabled selected>اختر مركز التكلفة</option>
                    @foreach ($costCenters as $costCenter)
                        <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn btn-custom-delete remove-entry mt-3">حذف</button>
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
            <div class="form-group">
                <label>الحساب</label>
                <select name="credit[${creditCounter}][account_id]" class="" required>
                    <option value="" disabled selected>اختر الحساب</option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>المبلغ</label>
                <input type="text" name="credit[${creditCounter}][amount]" class="form-control credit-amount" required>
            </div>
            <div class="form-group">
                <label>مركز التكلفة</label>
                <select name="credit[${creditCounter}][cost_center_id]" class="" required>
                    <option value="" disabled selected>اختر مركز التكلفة</option>
                    @foreach ($costCenters as $costCenter)
                        <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn btn-custom-delete remove-entry mt-3">حذف</button>
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
                    alert('القيد المحاسبي غير متوازن. تأكد من أن إجمالي المدين يساوي إجمالي الدائن.');
                }
            });


            // Initial totals update
            updateTotals();
        });
    </script>

</x-app-layout>
