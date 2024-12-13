<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-start">
            @include('opening_balance.nav.navigation')
        </div>
    </x-slot>
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('opening_balance.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <input type="text" name="name"
                                        class="w-full rounded-md shadow-sm border-gray-300 @error('name') border-red-500 @enderror"
                                        required>
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="date"
                                        class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <input type="date" name="date"
                                        class="w-full rounded-md shadow-sm border-gray-300 @error('date') border-red-500 @enderror"
                                        required>
                                    @error('date')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="period_id"
                                        class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                                    <select name="period_id"
                                        class="w-full rounded-md shadow-sm border-gray-300 @error('period_id') border-red-500 @enderror"
                                        required>
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}">{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('period_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div>
                                <div id="accounts-container" class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-900">Accounts</h3>
                                    <div class="account-entry p-4 border rounded-lg bg-gray-50">
                                        <select name="accounts[0][account_id]"
                                            class="w-full rounded-md shadow-sm border-gray-300 mb-2" required>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="number" name="accounts[0][amount]"
                                            class="w-full rounded-md shadow-sm border-gray-300 mb-2"
                                            placeholder="Amount" required>

                                        <select name="accounts[0][debit_credit]"
                                            class="w-full rounded-md shadow-sm border-gray-300 mb-2" required>
                                            <option value="debit">Debit</option>
                                            <option value="credit">Credit</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="button"
                                    class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    onclick="addAccountField()">
                                    Add Another Account
                                </button>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center space-x-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Opening Balance
                            </button>
                            <a href="{{ route('opening_balance.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let accountCount = 1;

        function addAccountField() {
            const container = document.getElementById('accounts-container');
            const div = document.createElement('div');
            div.className = 'account-entry p-4 border rounded-lg bg-gray-50 mt-4';
            div.innerHTML = `
                <select name="accounts[${accountCount}][account_id]" class="w-full rounded-md shadow-sm border-gray-300 mb-2" required>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="accounts[${accountCount}][amount]" 
                    class="w-full rounded-md shadow-sm border-gray-300 mb-2"
                    placeholder="Amount" required>
                <select name="accounts[${accountCount}][debit_credit]" 
                    class="w-full rounded-md shadow-sm border-gray-300 mb-2" required>
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                </select>
            `;
            container.appendChild(div);
            accountCount++;
        }
    </script>

</x-app-layout>
