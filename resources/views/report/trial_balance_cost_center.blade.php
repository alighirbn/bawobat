<x-app-layout>

    <x-slot name="header">
        @include('report.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <table class="table bordered table-striped" border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Main Account Name</th>
                                <th>Child Account Name</th>
                                <th>Debits</th>
                                <th>Credits</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accountHierarchy as $account)
                                {{-- Process main accounts with no parent_id --}}
                                @if (is_null($account['parent_id']))
                                    @php
                                        // Calculate sums of children amounts
                                        $childDebits = $account['children']->sum('debits');
                                        $childCredits = $account['children']->sum('credits');
                                        $childBalance = $account['children']->sum('balance');

                                        // Total amounts for the main account (including its children)
                                        $totalDebits = $account['debits'] + $childDebits;
                                        $totalCredits = $account['credits'] + $childCredits;
                                        $totalBalance = $account['balance'] + $childBalance;
                                    @endphp

                                    {{-- Main account row --}}
                                    <tr>
                                        <td><strong>{{ $account['account_name'] }}</strong></td>
                                        <td>N/A</td>
                                        <td>{{ number_format($totalDebits, 2) }}</td>
                                        <td>{{ number_format($totalCredits, 2) }}</td>
                                        <td>{{ number_format($totalBalance, 2) }}</td>
                                    </tr>

                                    {{-- Display child accounts in their own rows --}}
                                    @if ($account['children']->isNotEmpty())
                                        @foreach ($account['children'] as $child)
                                            <tr>
                                                <td></td> {{-- Empty cell for main account column --}}
                                                <td>{{ $child['account_name'] }}</td>
                                                <td>{{ number_format($child['debits'], 2) }}</td>
                                                <td>{{ number_format($child['credits'], 2) }}</td>
                                                <td>{{ number_format($child['balance'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <h2>Cost Center Balances</h2>
                    <table class="table bordered table-striped" border="1" cellspacing="0" cellpadding="5">
                        <thead>
                            <tr>
                                <th>Cost Center</th>
                                <th>Total Debits</th>
                                <th>Total Credits</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($costCenters as $costCenter)
                                @php
                                    $totalDebits = $costCenter
                                        ->transactions()
                                        ->where('transaction_account.debit_credit', 'debit')
                                        ->sum('transaction_account.amount');
                                    $totalCredits = $costCenter
                                        ->transactions()
                                        ->where('transaction_account.debit_credit', 'credit')
                                        ->sum('transaction_account.amount');
                                @endphp
                                <tr>
                                    <td>{{ $costCenter->name }}</td>
                                    <td>{{ number_format($totalDebits, 2) }}</td>
                                    <td>{{ number_format($totalCredits, 2) }}</td>
                                    <td>{{ number_format($totalCredits - $totalDebits, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
