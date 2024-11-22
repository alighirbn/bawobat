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
                                                        class="form-control" required>
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
                                                    <input type="number" step="0.01"
                                                        name="debit[{{ $index }}][amount]"
                                                        class="form-control debit-amount"
                                                        value="{{ old("debit.$index.amount") }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="debit_cost_center_id[]">مركز التكلفة </label>
                                                    <select name="debit[{{ $index }}][cost_center_id]" required
                                                        class="form-control">
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
                                                        class="form-control" required>
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
                                                    <input type="number" step="0.01"
                                                        name="credit[{{ $index }}][amount]"
                                                        class="form-control credit-amount"
                                                        value="{{ old("credit.$index.amount") }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="credit_cost_center_id[]">مركز التكلفة </label>
                                                    <select name="credit[{{ $index }}][cost_center_id]" required
                                                        class="form-control">
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
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-custom-add">إنشاء القيد</button>
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

            // Add new debit entry
            $('#add-debit-entry').on('click', function() {
                const debitContainer = $('#debit_entries');
                const newDebitEntry = `
                    <div class="debit-entry p-3 border rounded mb-3 bg-white text-gray-900">
                        <div class="form-group">
                            <label>الحساب</label>
                            <select name="debit[${debitCounter}][account_id]" class="form-control" required>
                                <option value="" disabled selected>اختر الحساب</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>المبلغ</label>
                            <input type="number" step="0.01" name="debit[${debitCounter}][amount]" class="form-control debit-amount" required>
                        </div>
                        <div class="form-group">
                            <label>مركز التكلفة</label>
                            <select name="debit[${debitCounter}][cost_center_id]" class="form-control" required>
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
            });

            // Add new credit entry
            $('#add-credit-entry').on('click', function() {
                const creditContainer = $('#credit_entries');
                const newCreditEntry = `
                    <div class="credit-entry p-3 border rounded mb-3 bg-white text-gray-900">
                        <div class="form-group">
                            <label>الحساب</label>
                            <select name="credit[${creditCounter}][account_id]" class="form-control" required>
                                <option value="" disabled selected>اختر الحساب</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>المبلغ</label>
                            <input type="number" step="0.01" name="credit[${creditCounter}][amount]" class="form-control credit-amount" required>
                        </div>
                        <div class="form-group">
                            <label>مركز التكلفة</label>
                            <select name="credit[${creditCounter}][cost_center_id]" class="form-control" required>
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
            });

            // Remove entry
            $(document).on('click', '.remove-entry', function() {
                $(this).closest('.debit-entry, .credit-entry').remove();
            });

            // Form submission validation
            $('form').on('submit', function(event) {
                let totalDebit = 0;
                let totalCredit = 0;

                // Calculate total debit
                $('.debit-amount').each(function() {
                    totalDebit += parseFloat($(this).val()) || 0;
                });

                // Calculate total credit
                $('.credit-amount').each(function() {
                    totalCredit += parseFloat($(this).val()) || 0;
                });

                // Check if balanced
                if (totalDebit !== totalCredit) {
                    event.preventDefault();
                    alert('القيد المحاسبي غير متوازن. تأكد من أن إجمالي المدين يساوي إجمالي الدائن.');
                }
            });
        });
    </script>

</x-app-layout>
