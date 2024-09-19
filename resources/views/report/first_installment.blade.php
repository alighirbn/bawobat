<x-app-layout>
    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <style>
            @media print {
                .no-print {
                    display: none;
                }
            }
        </style>
        @include('report.nav.navigation')
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        <button id="print" class="btn btn-custom-print" onclick="window.print();">
                            {{ __('word.print') }}
                        </button>
                    </div>
                    <div class="print-container a4-width mx-auto bg-white">
                        <div class="flex">
                            <div class="mx-2 my-2 w-full">
                                <h1 class="text-xl font-semibold mb-4">تقرير بالعقود التي لم تسدد المقدمة فيها </h1>
                            </div>
                            <div class="mx-2 my-2 w-full">
                                <img src="{{ asset('images/yasmine.png') }}" alt="Logo"
                                    style="h-6;max-width: auto; height: 90px;">
                            </div>
                        </div>

                        <h2>Unpaid Cash Contracts</h2>
                        <table class=" table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Contract ID</th>
                                    <th>Customer</th>
                                    <th>Building</th>
                                    <th>Amount</th>
                                    <th>Contract Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unpaidCashContracts as $contract)
                                    <tr>
                                        <td>{{ $contract->url_address }}</td>
                                        <td>{{ $contract->customer->customer_full_name }}</td>
                                        <td>{{ $contract->building->building_number }}</td>
                                        <td>{{ $contract->contract_amount }}</td>
                                        <td>{{ $contract->contract_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h2>Unpaid First Installment Contracts</h2>
                        <table class=" table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Contract ID</th>
                                    <th>Customer</th>
                                    <th>Building</th>
                                    <th>Amount</th>
                                    <th>Contract Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($unpaidFirstInstallmentContracts as $contract)
                                    <tr>
                                        <td>{{ $contract->id }}</td>
                                        <td>{{ $contract->customer->customer_full_name }}</td>
                                        <td>{{ $contract->building->building_number }}</td>
                                        <td>{{ $contract->contract_amount }}
                                        </td>
                                        <td>{{ $contract->contract_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>