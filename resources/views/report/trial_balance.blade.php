<x-app-layout>
    <x-slot name="header">
        @include('report.nav.navigation')
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="a4-width mx-auto">

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
                                @foreach ($trialBalance as $entry)
                                    {{-- Main Account Row --}}
                                    <tr>
                                        <td>{{ $entry['account_name'] }}</td>
                                        <td></td> {{-- Empty cell for the child account column in the main account row --}}
                                        <td>{{ number_format($entry['debits'], 0) }}</td>
                                        <td>{{ number_format($entry['credits'], 0) }}</td>
                                        <td>{{ number_format($entry['balance'], 0) }}</td>
                                    </tr>

                                    {{-- Child Account Rows --}}
                                    @foreach ($entry['children'] ?? [] as $child)
                                        <tr>
                                            <td></td> {{-- Empty cell for the parent account column for the child row --}}
                                            <td>{{ $child['account_name'] }}</td> {{-- Display child account in the new column --}}
                                            <td>{{ number_format($child['debits'], 0) }}</td>
                                            <td>{{ number_format($child['credits'], 0) }}</td>
                                            <td>{{ number_format($child['balance'], 0) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>

                        <h2>Total Debits: {{ number_format($totalDebits, 0) }}</h2>
                        <h2>Total Credits: {{ number_format($totalCredits, 0) }}</h2>
                        <h2>{{ $isBalanced ? 'The trial balance is balanced.' : 'The trial balance is not balanced.' }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
