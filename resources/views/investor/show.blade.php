<x-app-layout>

    <x-slot name="header">
        @include('investor.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.investor_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="name" class="w-full mb-1" :value="__('word.name')" />
                                <p id="name" class="w-full h-9 block mt-1" type="text" name="name">
                                    {{ $investor->name }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="phone" class="w-full mb-1" :value="__('word.phone')" />
                                <p id="phone" class="w-full h-9 block mt-1 " type="text" name="phone">
                                    {{ $investor->phone }}
                            </div>

                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="email" class="w-full mb-1" :value="__('word.email')" />
                                <p id="email" class="w-full h-9 block mt-1 " type="text" name="email">
                                    {{ $investor->email }}
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="address" class="w-full mb-1" :value="__('word.address')" />
                                <p id="address" class="w-full h-9 block mt-1" type="text" name="address">
                                    {{ $investor->address }}
                            </div>

                        </div>

                        <div class="flex">
                            @if (isset($investor->user_id_create))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_create') }} {{ $investor->user_create->name }}
                                    {{ $investor->created_at }}
                                </div>
                            @endif

                            @if (isset($investor->user_id_update))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_update') }} {{ $investor->user_update->name }}
                                    {{ $investor->updated_at }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
