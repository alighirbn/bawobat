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
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.costcenter_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="code" class="w-full mb-1" :value="__('word.code')" />
                                <p id="code" class="w-full h-9 block mt-1 " type="text" name="code">
                                    {{ $costcenter->code }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="name" class="w-full mb-1" :value="__('word.name')" />
                                <p id="name" class="w-full h-9 block mt-1" type="text" name="name">
                                    {{ $costcenter->name }}
                            </div>

                        </div>
                        <div class="flex ">

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="description" class="w-full mb-1" :value="__('word.description')" />
                                <p id="description" class="w-full h-9 block mt-1" type="text" name="description">
                                    {{ $costcenter->description }}
                            </div>

                        </div>

                        <div class="flex">
                            @if (isset($costcenter->user_id_create))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_create') }} {{ $costcenter->user_create->name }}
                                    {{ $costcenter->created_at }}
                                </div>
                            @endif

                            @if (isset($costcenter->user_id_update))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_update') }} {{ $costcenter->user_update->name }}
                                    {{ $costcenter->updated_at }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
