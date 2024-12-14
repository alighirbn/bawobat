<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-start">
            @include('account.nav.navigation')
            @include('costcenter.nav.navigation')
            @include('period.nav.navigation')
            @include('opening_balance.nav.navigation')
        </div>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="font-semibold text-xl text-gray-900 leading-tight mb-6">
                        {{ __('word.period_details') }}
                    </h1>

                    <div class="bg-white p-6 rounded-lg shadow-md">

                        <!-- Period Information -->
                        <div class="mb-4">
                            <h2 class="text-lg font-medium text-gray-900">{{ __('word.period_info') }}</h2>
                            <div class="mt-2">
                                <p><strong>{{ __('word.name') }}:</strong> {{ $period->name }}</p>
                                <p><strong>{{ __('word.start_date') }}:</strong>
                                    {{ $period->start_date->format('Y-m-d') }}</p>
                                <p><strong>{{ __('word.end_date') }}:</strong> {{ $period->end_date->format('Y-m-d') }}
                                </p>
                            </div>
                        </div>

                        <!-- Transactions Information -->
                        <div class="mb-4">
                            <h2 class="text-lg font-medium text-gray-900">{{ __('word.transactions') }}</h2>
                            <div class="mt-2">
                                @if ($period->transactions->isEmpty())
                                    <p>{{ __('word.no_transactions') }}</p>
                                @else
                                    <ul>
                                        @foreach ($period->transactions as $transaction)
                                            <li class="mb-2">
                                                <a href="{{ route('transaction.show', $transaction->url_address) }}"
                                                    class="text-blue-500 hover:text-blue-700">
                                                    {{ $transaction->description }} -
                                                    {{ $transaction->date->format('Y-m-d') }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Buttons Section -->
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('period.edit', $period->url_address) }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            {{ __('word.edit') }}
                        </a>

                        <form method="POST" action="{{ route('period.destroy', $period->url_address) }}">
                            @csrf
                            @method('DELETE')
                            <x-primary-button class="bg-red-500 hover:bg-red-700" :type="'submit'">
                                {{ __('word.delete') }}
                            </x-primary-button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
