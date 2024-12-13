<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <div class="flex justify-start">
            @include('opening_balance.nav.navigation')
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="container a4-width p-4 bg-white mx-auto">
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>Opening Balance Details</p>
                        </div>

                        <div class="card shadow-sm mb-3">
                            <div class="card-header">
                                <h5>Opening Balance Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="flex">
                                    <div class="mx-2 my-2 w-full">
                                        <x-input-label for="name" class="mb-1" :value="__('Name')" />
                                        <p class="mt-1">{{ $openingBalance->name }}</p>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="mx-2 my-2 w-full">
                                        <x-input-label for="date" class="mb-1" :value="__('Date')" />
                                        <p class="mt-1">{{ $openingBalance->date }}</p>
                                    </div>

                                    <div class="mx-2 my-2 w-full">
                                        <x-input-label for="period_id" class="mb-1" :value="__('Period')" />
                                        <p class="mt-1">{{ $openingBalance->period->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <div class="card mx-1 w-full shadow-sm">
                                <div class="card-header">
                                    <h5>Account Entries</h5>
                                </div>
                                <div class="card-body">
                                    @foreach ($openingBalance->accounts as $account)
                                        <div class="account-entry p-3 border rounded mb-3 bg-white text-gray-900">
                                            <div class="flex">
                                                <div class="mx-2 my-2 w-full">
                                                    <label>Account</label>
                                                    <p class="mt-1">{{ $account->account->name }}</p>
                                                </div>
                                                <div class="mx-2 my-2 w-full">
                                                    <label>Amount</label>
                                                    <p class="mt-1">{{ number_format($account->amount ?? 0, 0) }}
                                                    </p>
                                                </div>
                                                <div class="mx-2 my-2 w-full">
                                                    <label>Type</label>
                                                    <p class="mt-1">{{ ucfirst($account->debit_credit ?? 'N/A') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('opening_balance.edit', $openingBalance->url_address) }}"
                                class="btn btn-custom-show">Edit</a>
                            <a href="{{ route('opening_balance.index') }}" class="btn btn-custom-delete">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
