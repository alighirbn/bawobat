<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <div class="flex justify-start">
            @include('account.nav.navigation')
            @include('income.nav.navigation')
            @include('expense.nav.navigation')
            @include('costcenter.nav.navigation')
            @include('transaction.nav.navigation')
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-full mx-auto sm:px-4 lg:px-6">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900 m-4">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success mb-2">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="print-container a4-width p-4 bg-white mx-auto " dir="rtl">
                        <div class="flex">
                            <div class=" mx-2 my-2 w-full ">
                                {!! QrCode::size(90)->generate($transaction->id) !!}
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="{{ asset('images/yasmine.png') }}" alt="الشعار"
                                    style="h-6;max-width: 70%; height: auto;">
                            </div>
                            <div class=" mx-2 my-2 w-full ">
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($transaction->id, 'C39') }}"
                                    alt="باركود" />

                                <p><strong>{{ __('عدد القيد:') }}</strong>
                                    {{ $transaction->id }}
                                </p>
                                <p><strong>{{ __('تاريخ القيد:') }}</strong> {{ $transaction->date }}</p>

                            </div>
                        </div>
                        <div style="text-align: center; margin: 0.8rem auto; font-size: 1.2rem; font-weight: bold;">
                            <p>قيد محاسبي</p>
                        </div>

                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-gray-100 p-2 rounded-t-md">
                                <h5 class="text-md font-semibold text-gray-800">{{ __('معلومات القيد') }}</h5>
                            </div>
                            <div class="card-body p-3 bg-gray-50">
                                <div class="flex">
                                    <div class="mb-1 mx-1 w-full">
                                        <strong class="text-sm font-medium text-gray-700">{{ __('الوصف:') }}</strong>
                                        <p class="text-sm">{{ $transaction->description }}</p>
                                    </div>
                                    <div class="mb-1 mx-1 w-full">
                                        <strong class="text-sm font-medium text-gray-700">{{ __('التاريخ:') }}</strong>
                                        <p class="text-sm">{{ $transaction->date }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <div class="card mx-1 w-full shadow-sm">
                                <div class="card-header bg-gray-100 p-2 rounded-t-md">
                                    <h5 class="text-md font-semibold text-gray-800">{{ __('الإدخالات الدائنة') }}</h5>
                                </div>
                                <div class="card-body bg-gray-50 p-2">
                                    <div id="credit_entries">
                                        @php
                                            $totalCredit = 0;
                                        @endphp
                                        @foreach ($transaction->credits as $credit)
                                            <div class="credit-entry p-2 border rounded-md mb-2 bg-white">
                                                <div class="flex">
                                                    <div class="mb-1 mx-1 w-full">
                                                        <strong
                                                            class="text-xs font-medium text-gray-700">{{ __('الحساب:') }}</strong>
                                                        <p class="text-sm">{{ $credit->account->name }}
                                                            ({{ $credit->account->code }})
                                                        </p>
                                                    </div>
                                                    <div class="mb-1 mx-1 w-full">
                                                        <strong
                                                            class="text-xs font-medium text-gray-700">{{ __('المبلغ:') }}</strong>
                                                        <p class="text-sm">{{ number_format($credit->amount, 0) }}</p>
                                                    </div>
                                                    <div class="mb-1 mx-1 w-full">
                                                        <strong
                                                            class="text-xs font-medium text-gray-700">{{ __('مركز التكلفة:') }}</strong>
                                                        <p class="text-sm">{{ $credit->costCenter->name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                                $totalCredit += $credit->amount;
                                            @endphp
                                        @endforeach
                                    </div>
                                    <div class="mt-2 font-semibold text-sm text-gray-700">
                                        <strong>{{ __('إجمالي الدائن:') }}</strong>
                                        {{ number_format($totalCredit, 0) }}
                                    </div>
                                </div>
                            </div>
                            <div class="card mx-1 w-full shadow-sm">
                                <div class="card-header bg-gray-100 p-2 rounded-t-md">
                                    <h5 class="text-md font-semibold text-gray-800">{{ __('الإدخالات المدينة') }}</h5>
                                </div>
                                <div class="card-body bg-gray-50 p-2">
                                    <div id="debit_entries">
                                        @php
                                            $totalDebit = 0;
                                        @endphp
                                        @foreach ($transaction->debits as $debit)
                                            <div class="debit-entry p-2 border rounded-md mb-2 bg-white">
                                                <div class="flex">
                                                    <div class="mb-1 mx-1 w-full">
                                                        <strong
                                                            class="text-xs font-medium text-gray-700">{{ __('الحساب:') }}</strong>
                                                        <p class="text-sm">{{ $debit->account->name }}
                                                            ({{ $debit->account->code }})
                                                        </p>
                                                    </div>
                                                    <div class="mb-1 mx-1 w-full">
                                                        <strong
                                                            class="text-xs font-medium text-gray-700">{{ __('المبلغ:') }}</strong>
                                                        <p class="text-sm">{{ number_format($debit->amount, 0) }}</p>
                                                    </div>
                                                    <div class="mb-1 mx-1 w-full">
                                                        <strong
                                                            class="text-xs font-medium text-gray-700">{{ __('مركز التكلفة:') }}</strong>
                                                        <p class="text-sm">{{ $debit->costCenter->name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @php
                                                $totalDebit += $debit->amount;
                                            @endphp
                                        @endforeach
                                    </div>
                                    <div class="mt-2 font-semibold text-sm text-gray-700">
                                        <strong>{{ __('إجمالي المدين:') }}</strong>
                                        {{ number_format($totalDebit, 0) }}
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
