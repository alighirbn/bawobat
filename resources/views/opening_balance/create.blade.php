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
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
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
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="container  p-4 bg-white mx-auto">
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>Create Opening Balance</p>
                        </div>

                        <form action="{{ route('opening_balance.store') }}" method="POST">
                            @csrf
                            <div class="card shadow-sm mb-3">
                                <div class="card-header">
                                    <h5>Opening Balance Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="flex">
                                        <div class="mx-2 my-2 w-full">
                                            <x-input-label for="name" class="mb-1" :value="__('Name')" />
                                            <x-text-input id="name" class="w-full block mt-1" type="text"
                                                name="name" required />
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <div class="mx-2 my-2 w-full">
                                            <x-input-label for="date" class="mb-1" :value="__('Date')" />
                                            <x-text-input id="date" class="w-full block mt-1" type="date"
                                                name="date" required />
                                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                                        </div>

                                        <div class="mx-2 my-2 w-full">
                                            <x-input-label for="period_id" class="mb-1" :value="__('Period')" />
                                            <select id="period_id" class="w-full block mt-1" name="period_id" required>
                                                @foreach ($periods as $period)
                                                    <option value="{{ $period->id }}">{{ $period->name }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('period_id')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <div class="card mx-1 w-full shadow-sm">
                                    <div class="card-header">
                                        <h5>Account Entries</h5>
                                    </div>
                                    <div class="card-body" id="accounts-container">
                                        <div class="account-entry p-3 border rounded mb-3 bg-white text-gray-900">
                                            <div class="flex">
                                                <div class="mx-2 my-2 w-full">
                                                    <label>Account</label>
                                                    <select name="accounts[0][account_id]" class="w-full block mt-1"
                                                        required>
                                                        @foreach ($accounts as $account)
                                                            <option value="{{ $account->id }}">{{ $account->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mx-2 my-2 w-full">
                                                    <label>Amount</label>
                                                    <input type="text" name="accounts[0][amount]"
                                                        class="w-full block mt-1" required>
                                                </div>
                                                <div class="mx-2 my-2 w-full">
                                                    <label>Type</label>
                                                    <select name="accounts[0][debit_credit]" class="w-full block mt-1"
                                                        required>
                                                        <option value="debit">Debit</option>
                                                        <option value="credit">Credit</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-custom-show" id="add-account-btn">Add Another
                                        Account</button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div>
                                    <p><strong>Total Debit:</strong> <span id="total-debit">0.00</span></p>
                                    <p><strong>Total Credit:</strong> <span id="total-credit">0.00</span></p>
                                    <p><strong>Balance:</strong> <span id="balance">0.00</span></p>
                                </div>
                                <button type="submit" class="btn btn-custom-add">Create Opening Balance</button>
                                <a href="{{ route('opening_balance.index') }}" class="btn btn-custom-delete">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let accountCount = 1;

            // Function to calculate total debit, credit, and balance
            function updateTotals() {
                let totalDebit = 0;
                let totalCredit = 0;

                $('input[name$="[amount]"]').each(function() {
                    const amount = parseFloat($(this).val().replace(/,/g, '') || 0);
                    const type = $(this).closest('.account-entry').find('select[name$="[debit_credit]"]')
                        .val();

                    if (type === 'debit') {
                        totalDebit += amount;
                    } else if (type === 'credit') {
                        totalCredit += amount;
                    }
                });

                const balance = totalDebit - totalCredit;

                // Update the totals on the page
                $('#total-debit').text(number_format(totalDebit, 0));
                $('#total-credit').text(number_format(totalCredit, 0));
                $('#balance').text(number_format(balance, 0));
            }

            // Format amount inputs
            $(document).on('input', 'input[name$="[amount]"]', function() {
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

                updateTotals(); // Update totals on input change
            });

            // Add new account entry
            $('#add-account-btn').on('click', function() {
                const container = $('#accounts-container');
                const newAccountEntry = `
                    <div class="account-entry p-3 border rounded mb-3 bg-white text-gray-900">
                        <div class="flex">
                            <div class="mx-2 my-2 w-full">
                                <label>Account</label>
                                <select name="accounts[${accountCount}][account_id]" class="w-full block mt-1" required>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <label>Amount</label>
                                <input type="text" name="accounts[${accountCount}][amount]" class="w-full block mt-1" required>
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <label>Type</label>
                                <select name="accounts[${accountCount}][debit_credit]" class="w-full block mt-1" required>
                                    <option value="debit">Debit</option>
                                    <option value="credit">Credit</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-custom-delete remove-entry mt-3">Delete</button>
                    </div>
                `;
                container.append(newAccountEntry);
                accountCount++;

                updateTotals(); // Update totals after adding a new account entry
            });

            // Add event listener for removing entries
            $(document).on('click', '.remove-entry', function() {
                $(this).closest('.account-entry').remove();
                updateTotals(); // Update totals after removing an account entry
            });

            // Form submission validation - convert formatted numbers back to plain numbers
            $('form').on('submit', function(event) {
                $('input[name$="[amount]"]').each(function() {
                    let amount = $(this).val().replace(/,/g, ''); // Remove commas
                    $(this).val(parseFloat(amount) || 0); // Set the input value to a valid number
                });
            });

            // Initial call to update totals
            updateTotals();
        });

        function number_format(number, decimals) {
            const fixed = number.toFixed(decimals);
            const parts = fixed.split('.');
            const wholePart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            const decimalPart = parts[1] ? '.' + parts[1] : '';
            return wholePart + decimalPart;
        }
    </script>
</x-app-layout>
