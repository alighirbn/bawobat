<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        <div class="flex justify-start">

            @include('expense.nav.navigation')

        </div>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                        @can('expense-delete')
                            <form action="{{ route('expense.destroy', $expense->url_address) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                                    {{ __('word.delete') }}
                                </button>

                            </form>
                        @endcan
                    </div>
                    <div class="header-buttons">
                        @if (!$expense->approved)
                            <form action="{{ route('expense.approve', $expense->url_address) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="cash_account_id">الصندوق</label>
                                <select name="cash_account_id" required>
                                    @foreach ($cash_accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-custom-edit">
                                    {{ __('word.expense_approve') }}</button>
                            </form>
                        @endif
                    </div>
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="print-container a4-width mx-auto  bg-white">

                        <div class="flex">
                            <div class=" mx-2 my-2 w-full ">
                                {!! QrCode::size(90)->generate($expense->id) !!}
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: 100%; height: auto;">
                            </div>
                            <div class=" mx-2 my-2 w-full ">

                                <p><strong>{{ __('عدد سند الصرف:') }}</strong>
                                    {{ $expense->id }}
                                </p>
                                <p><strong>{{ __('تاريخ سند الصرف:') }}</strong> {{ $expense->expense_date }}</p>

                            </div>
                        </div>
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>سند صرف </p>
                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="expense_id" class="w-full mb-1" :value="__('word.expense_id')" />
                                <p id="expense_id" class="w-full h-9 block mt-1" type="text" name="expense_id">
                                    {{ $expense->id }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="date" class="w-full mb-1" :value="__('word.date')" />
                                <p id="date" class="w-full h-9 block mt-1 " type="text" name="date">
                                    {{ $expense->date }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="expense_type_id" class="w-full mb-1" :value="__('word.expense_type_id')" />
                                <p id="expense_type_id" class="w-full h-9 block mt-1" type="text"
                                    name="expense_type_id">
                                    {{ $expense->expense_type->name }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="amount" class="w-full mb-1" :value="__('word.amount')" />
                                <p id="amount" class="w-full h-9 block mt-1 " type="text" name="amount">
                                    {{ number_format($expense->amount, 0) }} دينار
                                </p>
                            </div>

                        </div>

                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="description" class="w-full mb-1" :value="__('word.description')" />
                                <p id="description" class="w-full h-9 block mt-1" type="text" name="description">
                                    {{ $expense->description }}
                                </p>
                            </div>

                        </div>

                    </div>
                    <div class="flex">
                        @if (isset($expense->user_id_create))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_create') }} {{ $expense->user_create->name }}
                                {{ $expense->created_at }}
                            </div>
                        @endif

                        @if (isset($expense->user_id_update))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_update') }} {{ $expense->user_update->name }}
                                {{ $expense->updated_at }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
