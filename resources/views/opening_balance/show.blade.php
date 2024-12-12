@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Opening Balance Details</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $openingBalance->name }}</h5>
                <p class="card-text">
                    <strong>Date:</strong> {{ $openingBalance->transaction->date }}<br>
                    <strong>Period:</strong> {{ $openingBalance->period->name }}
                </p>

                <h6>Accounts</h6>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($openingBalance->accounts as $account)
                            <tr>
                                <td>{{ $account->account->name }}</td>
                                <td>{{ number_format($account->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    <a href="{{ route('opening_balance.edit', $openingBalance->url_address) }}"
                        class="btn btn-primary">Edit</a>
                    <form action="{{ route('opening_balance.destroy', $openingBalance->url_address) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    <a href="{{ route('opening_balance.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
@endsection
