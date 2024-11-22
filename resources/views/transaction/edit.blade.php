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

                    <div class="container a4-width p-4 bg-white mx-auto">
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>تعديل القيد المحاسبي</p>
                        </div>

                        <form id="form" class="form"
                            action="{{ route('transaction.update', $transaction->url_address) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="card shadow-sm mb-3">
                                <div class="card-header">
                                    <h5>تفاصيل القيد</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="description">الوصف</label>
                                        <input type="text" name="description" id="description" class="form-control"
                                            value="{{ old('description', $transaction->description) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="date">التاريخ</label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            value="{{ old('date', $transaction->date) }}" required>
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
                                        @foreach ($transaction->credits as $index => $credit)
                                            <div class="credit-entry p-3 border rounded mb-3 bg-white text-gray-900">
                                                <div class="form-group">
                                                    <label for="credit_account_id[]">الحساب</label>
                                                    <select name="credit[{{ $index }}][account_id]"
                                                        class="" required>
                                                        <option value="" disabled selected>اختر الحساب</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}"
                                                                {{ old("credit.$index.account_id", $credit->account_id) == $account->id ? 'selected' : '' }}>
                                                                {{ $account->name }} ({{ $account->code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="credit_amount[]">المبلغ</label>
                                                    <input type="text" name="credit[{{ $index }}][amount]"
                                                        class="form-control credit-amount"
                                                        value="{{ old("credit.$index.amount", $credit->amount) }}"
                                                        required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="credit_cost_center_id[]">مركز التكلفة </label>
                                                    <select name="credit[{{ $index }}][cost_center_id]" required
                                                        class="">
                                                        <option value="" disabled selected>اختر مركز التكلفة
                                                        </option>
                                                        @foreach ($costCenters as $costCenter)
                                                            <option value="{{ $costCenter->id }}"
                                                                {{ old("credit.$index.cost_center_id", $credit->cost_center_id) == $costCenter->id ? 'selected' : '' }}>
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
                                        @foreach ($transaction->debits as $index => $debit)
                                            <div class="debit-entry p-3 border rounded mb-3 bg-white text-gray-900">
                                                <div class="form-group">
                                                    <label for="debit_account_id[]">الحساب</label>
                                                    <select name="debit[{{ $index }}][account_id]"
                                                        class="" required>
                                                        <option value="" disabled selected>اختر الحساب</option>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}"
                                                                {{ old("debit.$index.account_id", $debit->account_id) == $account->id ? 'selected' : '' }}>
                                                                {{ $account->name }} ({{ $account->code }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="debit_amount[]">المبلغ</label>
                                                    <input type="text" name="debit[{{ $index }}][amount]"
                                                        class="form-control debit-amount"
                                                        value="{{ old("debit.$index.amount", $debit->amount) }}"
                                                        required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="debit_cost_center_id[]">مركز التكلفة </label>
                                                    <select name="debit[{{ $index }}][cost_center_id]" required
                                                        class="">
                                                        <option value="" disabled selected>اختر مركز التكلفة
                                                        </option>
                                                        @foreach ($costCenters as $costCenter)
                                                            <option value="{{ $costCenter->id }}"
                                                                {{ old("debit.$index.cost_center_id", $debit->cost_center_id) == $costCenter->id ? 'selected' : '' }}>
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
                                <button type="submit" class="btn btn-custom-add">تحديث القيد</button>
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
                        `من ${creditAccounts.join(', ')} إلى ${debitAccounts.join(', ')}, المجموع: ${totalAmount}`;
                }

                // Update the description field
                $('#description').val(description);
            }

            // Trigger description generation on any input change
            $(document).on('change input',
                '.debit-entry select, .debit-entry input, .credit-entry select, .credit-entry input',
                generateDescription);

            // Initialize the form

            generateDescription(); // Initial call to generate description
        });


        // Function to calculate the total of a given class (debit or credit)
        function calculateTotal(className) {
            let total = 0;
            document.querySelectorAll(`.${className}`).forEach(input => {
                let amount = parseFloat(input.value) || 0;
                total += amount;
            });
            return total;
        }

        // Function to update the totals and balance
        function updateTotals() {
            // Calculate totals for debit and credit entries
            const totalDebit = calculateTotal('debit-amount');
            const totalCredit = calculateTotal('credit-amount');

            // Update the display of total debit, credit, and balance
            document.getElementById('total-debit').textContent = totalDebit.toFixed(0);
            document.getElementById('total-credit').textContent = totalCredit.toFixed(0);
            document.getElementById('balance').textContent = (totalDebit - totalCredit).toFixed(0);
        }

        // Function to add a new debit or credit entry
        function addEntry(type) {
            let entryHtml = `
                <div class="${type}-entry p-3 border rounded mb-3 bg-white text-gray-900">
                    <div class="form-group">
                        <label for="${type}_account_id[]">الحساب</label>
                        <select name="${type}[new][account_id]" required>
                            <option value="" disabled selected>اختر الحساب</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="form-group">
                        <label for="${type}_amount[]">المبلغ</label>
                        <input type="text" name="${type}[new][amount]" class="form-control ${type}-amount" required>
                    </div>
    
                    <div class="form-group">
                        <label for="${type}_cost_center_id[]">مركز التكلفة</label>
                        <select name="${type}[new][cost_center_id]" required>
                            <option value="" disabled selected>اختر مركز التكلفة</option>
                            @foreach ($costCenters as $costCenter)
                                <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-custom-delete remove-entry mt-3">حذف</button>
                </div>
            `;

            document.getElementById(`${type}_entries`).insertAdjacentHTML('beforeend', entryHtml);
            updateTotals();
        }

        // Function to remove an entry (either debit or credit)
        function removeEntry(e) {
            e.target.closest('.debit-entry, .credit-entry').remove();
            updateTotals();
        }

        // Event listeners to handle adding and removing entries dynamically
        document.getElementById('add-debit-entry').addEventListener('click', function() {
            addEntry('debit');
        });

        document.getElementById('add-credit-entry').addEventListener('click', function() {
            addEntry('credit');
        });

        // Event listener to remove entries
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-entry')) {
                removeEntry(e);
            }
        });

        // Event listener to update totals when the amount is changed
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('debit-amount') || e.target.classList.contains('credit-amount')) {
                updateTotals();
            }
        });

        // Initialize the totals on page load
        updateTotals();
    </script>

</x-app-layout>
