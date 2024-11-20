@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create New Transaction</h2>

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5>Transaction Details</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" name="description" id="description" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>Debit Entries</h5>
                </div>
                <div class="card-body">
                    <div id="debit_entries">
                        <div class="debit-entry">
                            <div class="form-group">
                                <label for="debit_account_id[]">Account</label>
                                <select name="debit[0][account_id]" class="form-control" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="debit_amount[]">Amount</label>
                                <input type="number" step="0.01" name="debit[0][amount]" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="debit_cost_center_id[]">Cost Center (Optional)</label>
                                <select name="debit[0][cost_center_id]" class="form-control">
                                    <option value="" disabled selected>Select Cost Center</option>
                                    @foreach ($costCenters as $costCenter)
                                        <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary" id="add-debit-entry">Add Debit Entry</button>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>Credit Entries</h5>
                </div>
                <div class="card-body">
                    <div id="credit_entries">
                        <div class="credit-entry">
                            <div class="form-group">
                                <label for="credit_account_id[]">Account</label>
                                <select name="credit[0][account_id]" class="form-control" required>
                                    <option value="" disabled selected>Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="credit_amount[]">Amount</label>
                                <input type="number" step="0.01" name="credit[0][amount]" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="credit_cost_center_id[]">Cost Center (Optional)</label>
                                <select name="credit[0][cost_center_id]" class="form-control">
                                    <option value="" disabled selected>Select Cost Center</option>
                                    @foreach ($costCenters as $costCenter)
                                        <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary" id="add-credit-entry">Add Credit Entry</button>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Create Transaction</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let debitCounter = 1;
            let creditCounter = 1;

            // Add new debit entry
            document.getElementById('add-debit-entry').addEventListener('click', function() {
                const debitContainer = document.getElementById('debit_entries');
                const newDebitEntry = document.createElement('div');
                newDebitEntry.classList.add('debit-entry');
                newDebitEntry.innerHTML = `
                    <div class="form-group">
                        <label for="debit_account_id[]">Account</label>
                        <select name="debit[${debitCounter}][account_id]" class="form-control" required>
                            <option value="" disabled selected>Select Account</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="debit_amount[]">Amount</label>
                        <input type="number" step="0.01" name="debit[${debitCounter}][amount]" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="debit_cost_center_id[]">Cost Center (Optional)</label>
                        <select name="debit[${debitCounter}][cost_center_id]" class="form-control">
                            <option value="" disabled selected>Select Cost Center</option>
                            @foreach ($costCenters as $costCenter)
                                <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                            @endforeach
                        </select>
                    </div>
                `;
                debitContainer.appendChild(newDebitEntry);
                debitCounter++;
            });

            // Add new credit entry
            document.getElementById('add-credit-entry').addEventListener('click', function() {
                const creditContainer = document.getElementById('credit_entries');
                const newCreditEntry = document.createElement('div');
                newCreditEntry.classList.add('credit-entry');
                newCreditEntry.innerHTML = `
                    <div class="form-group">
                        <label for="credit_account_id[]">Account</label>
                        <select name="credit[${creditCounter}][account_id]" class="form-control" required>
                            <option value="" disabled selected>Select Account</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="credit_amount[]">Amount</label>
                        <input type="number" step="0.01" name="credit[${creditCounter}][amount]" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="credit_cost_center_id[]">Cost Center (Optional)</label>
                        <select name="credit[${creditCounter}][cost_center_id]" class="form-control">
                            <option value="" disabled selected>Select Cost Center</option>
                            @foreach ($costCenters as $costCenter)
                                <option value="{{ $costCenter->id }}">{{ $costCenter->name }}</option>
                            @endforeach
                        </select>
                    </div>
                `;
                creditContainer.appendChild(newCreditEntry);
                creditCounter++;
            });
        });
    </script>
@endsection
