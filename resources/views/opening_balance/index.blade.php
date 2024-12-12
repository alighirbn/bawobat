@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Opening Balances</h1>
        <div class="mb-3">
            <a href="{{ route('opening_balance.create') }}" class="btn btn-primary">Create New Opening Balance</a>
        </div>

        {{ $dataTable->table() }}
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
