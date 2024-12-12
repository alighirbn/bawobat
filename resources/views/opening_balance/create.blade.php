@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Opening Balance</h1>

        <form action="{{ route('opening_balance.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="period_id" class="form-label">Period</label>
                <select name="period_id" class="form-control @error('period_id') is-invalid @enderror" required>
                    @foreach ($periods as $period)
                        <option value="{{ $period->id }}">{{ $period->name }}</option>
                    @endforeach
                </select>
                @error('period_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="accounts-container">
                <h3>Accounts</h3>
                <div class="account-entry mb-3">
                    <select name="accounts[0][account_id]" class="form-control" required>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="accounts[0][amount]" class="form-control mt-2" placeholder="Amount"
                        required>
                </div>
            </div>

            <button type="button" class="btn btn-secondary mb-3" onclick="addAccountField()">Add Another Account</button>

            <div>
                <button type="submit" class="btn btn-primary">Create Opening Balance</button>
                <a href="{{ route('opening_balance.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let accountCount = 1;

            function addAccountField() {
                const container = document.getElementById('accounts-container');
                const div = document.createElement('div');
                div.className = 'account-entry mb-3';
                div.innerHTML = `
            <select name="accounts[${accountCount}][account_id]" class="form-control" required>
                @foreach ($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            </select>
            <input type="number" name="accounts[${accountCount}][amount]" class="form-control mt-2" placeholder="Amount" required>
        `;
                container.appendChild(div);
                accountCount++;
            }
        </script>
    @endpush
@endsection
