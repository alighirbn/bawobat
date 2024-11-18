<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <div class="flex justify-start">
            @include('income.nav.navigation')
            @include('expense.nav.navigation')
            @include('cash_account.nav.navigation')
            @include('cash_transfer.nav.navigation')
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
                        @can('project-show')
                            <a href="{{ route('project.show', $income->project->url_address) }}"
                                class="btn btn-custom-show">
                                {{ __('word.project_view') }}
                            </a>
                        @endcan

                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                        @can('income-delete')
                            <form action="{{ route('income.destroy', $income->url_address) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                                    {{ __('word.delete') }}
                                </button>

                            </form>
                        @endcan
                    </div>
                    <div class="header-buttons">
                        @if (!$income->approved)
                            <form action="{{ route('income.approve', $income->url_address) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <label for="cash_account_id">الصندوق</label>
                                <select name="cash_account_id" required>
                                    @foreach ($cash_accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-custom-edit">
                                    {{ __('word.income_approve') }}</button>
                            </form>
                        @endif
                    </div>

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
                    <div class="print-container a4-width mx-auto  bg-white">

                        <div class="flex">
                            <div class=" mx-2 my-2 w-full ">
                                {!! QrCode::size(90)->generate($income->project->id) !!}
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: 100%; height: auto;">
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($income->id, 'C39') }}"
                                    alt="barcode" />

                                <p><strong>{{ __('عدد الدفعة:') }}</strong>
                                    {{ $income->id }}
                                </p>
                                <p><strong>{{ __('تاريخ الدفعة:') }}</strong> {{ $income->income_date }}</p>

                            </div>
                        </div>
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>سند قبض </p>
                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="income_id" class="w-full mb-1" :value="__('word.income_id')" />
                                <p id="income_id" class="w-full h-9 block mt-1" type="text" name="income_id">
                                    {{ $income->id }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="date" class="w-full mb-1" :value="__('word.date')" />
                                <p id="date" class="w-full h-9 block mt-1 " type="text" name="date">
                                    {{ $income->date }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="amount" class="w-full mb-1" :value="__('word.amount')" />
                                <p id="amount" class="w-full h-9 block mt-1 " type="text" name="amount">
                                    {{ number_format($income->amount, 0) }} دينار
                                </p>
                            </div>

                        </div>

                        <div class="flex ">

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="income_type_id" class="w-full mb-1" :value="__('word.income_type_id')" />
                                <p id="income_type_id" class="w-full h-9 block mt-1" type="text"
                                    name="income_type_id">
                                    {{ $income->income_type->name }}
                                </p>
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="project_id" class="w-full mb-1" :value="__('word.project_id')" />
                                <p id="project_id" class="w-full h-9 block mt-1" type="text" name="project_id">
                                    {{ $income->project->id }}
                                </p>
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="project_date" class="w-full mb-1" :value="__('word.project_date')" />
                                <p id="project_date" class="w-full h-9 block mt-1 " type="text" name="project_date">
                                    {{ $income->project->project_date }}
                                </p>
                            </div>

                        </div>

                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="description" class="w-full mb-1" :value="__('word.description')" />
                                <p id="description" class="w-full h-9 block mt-1" type="text" name="description">
                                    {{ $income->description }}
                                </p>
                            </div>

                        </div>

                    </div>
                    <div class="flex">
                        @if (isset($income->user_id_create))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_create') }} {{ $income->user_create->name }}
                                {{ $income->created_at }}
                            </div>
                        @endif

                        @if (isset($income->user_id_update))
                            <div class="mx-4 my-4 ">
                                {{ __('word.user_update') }} {{ $income->user_update->name }}
                                {{ $income->updated_at }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
