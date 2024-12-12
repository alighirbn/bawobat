@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Opening Balance</h1>

        <form action="{{ route('opening_balance.update', $openingBalance->url_address) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="period_id" class="form-label">Period</label>
                <select name="period_id" class="form-control @error('period_id') is-invalid @enderror" required>
                    @foreach ($periods as $period)
                        <option value="{{ $period->id }}"
                            {{ $openingBalance->period_id == $period->id ? 'selected' : '' }}>
                            {{ $period->name }}
                        </option>
                    @endforeach
                </select>
                @error('period_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" value="{{ $openingBalance->transaction->date }}"
                    class="form-control @error('date') is-invalid @enderror" required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" value="{{ $openingBalance->transaction->name }}"
                    class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div id="accounts-container">
                <h3>Accounts</h3>
                @foreach ($openingBalance->accounts as $index => $accountEntry)
                    <div class="account-entry mb-3">
                        <select name="accounts[{{ $index }}][account_id]" class="form-control" required>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ $accountEntry->account_id == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="accounts[{{ $index }}][amount]"
                            value="{{ $accountEntry->amount }}" class="form-control mt-2" placeholder="Amount" required>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-secondary mb-3" onclick="addAccountField()">Add Another Account</button>

            <div>
                <button type="submit" class="btn btn-primary">Update Opening Balance</button>
                <a href="{{ route('opening_balance.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let accountCount = {{ count($openingBalance->accounts) }};

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
