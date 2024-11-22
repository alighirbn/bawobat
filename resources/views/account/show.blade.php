<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-start">
            @include('account.nav.navigation')
            @include('income.nav.navigation')
            @include('expense.nav.navigation')
            @include('costcenter.nav.navigation')
            @include('transaction.nav.navigation')
        </div>
    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.account_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="code" class="w-full mb-1" :value="__('word.code')" />
                                <p id="code" class="w-full h-9 block mt-1 " type="text" name="code">
                                    {{ $account->code }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="name" class="w-full mb-1" :value="__('word.name')" />
                                <p id="name" class="w-full h-9 block mt-1" type="text" name="name">
                                    {{ $account->name }}
                            </div>

                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="type" class="w-full mb-1" :value="__('word.type')" />
                                <p id="type" class="w-full h-9 block mt-1 " type="text" name="type">
                                    {{ $account->type }}
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="class" class="w-full mb-1" :value="__('word.class')" />
                                <p id="class" class="w-full h-9 block mt-1" type="text" name="class">
                                    {{ $account->class }}
                            </div>

                        </div>

                        <div class="flex">
                            @if (isset($account->user_id_create))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_create') }} {{ $account->user_create->name }}
                                    {{ $account->created_at }}
                                </div>
                            @endif

                            @if (isset($account->user_id_update))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_update') }} {{ $account->user_update->name }}
                                    {{ $account->updated_at }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
