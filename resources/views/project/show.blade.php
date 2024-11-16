<!-- resources/views/projects/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $project->name }}</h1>

        <div class="project-details">
            <h3>Description</h3>
            <p>{{ $project->description }}</p>

            <h3>Budget</h3>
            <p>${{ number_format($project->budget, 2) }}</p>

            <h3>Start Date</h3>
            <p>{{ \Carbon\Carbon::parse($project->start_date)->toFormattedDateString() }}</p>

            <h3>End Date</h3>
            <p>{{ \Carbon\Carbon::parse($project->end_date)->toFormattedDateString() }}</p>

            <h3>Status</h3>
            <p>{{ $project->status }}</p>
        </div>

        <hr>

        <!-- Stages -->
        <h3>Project Stages</h3>
        <ul>
            @foreach ($project->stages as $stage)
                <li>
                    <strong>{{ $stage->name }}</strong><br>
                    <em>{{ $stage->start_date }} - {{ $stage->end_date }}</em><br>
                    <p>{{ $stage->description }}</p>
                </li>
            @endforeach
        </ul>

        <hr>

        <!-- Investors -->
        <h3>Investors</h3>
        <ul>
            @foreach ($project->investors as $investor)
                <li>
                    <strong>{{ $investor->name }}</strong><br>
                    Investment Amount: ${{ number_format($investor->pivot->investment_amount, 2) }}
                </li>
            @endforeach
        </ul>

        <hr>

        <!-- Transactions -->
        <h3>Transactions</h3>
        <ul>
            @foreach ($project->transactions as $transaction)
                <li>
                    <strong>{{ $transaction->transaction_type }}</strong><br>
                    Amount: ${{ number_format($transaction->amount, 2) }}<br>
                    Description: {{ $transaction->description }}<br>
                    Date: {{ \Carbon\Carbon::parse($transaction->created_at)->toFormattedDateString() }}
                </li>
            @endforeach
        </ul>
    </div>
@endsection
