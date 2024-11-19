<x-app-layout>

    <x-slot name="header">
        @include('investor.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <form method="post" action="{{ route('investor.update', $investor->url_address) }}">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $investor->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $investor->url_address }}">

                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.investor_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="name" class="w-full mb-1" :value="__('word.name')" />
                                    <x-text-input id="name" class="w-full block mt-1" type="text" name="name"
                                        value="{{ old('name') ?? $investor->name }}" />
                                    <x-input-error :messages="$errors->get('name')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="phone" class="w-full mb-1" :value="__('word.phone')" />
                                    <x-text-input id="phone" class="w-full block mt-1" type="text" name="phone"
                                        value="{{ old('phone') ?? $investor->phone }}" />
                                    <x-input-error :messages="$errors->get('phone')" class="w-full mt-2" />
                                </div>

                            </div>

                            <div class="flex">
                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="email" class="w-full mb-1" :value="__('word.email')" />
                                    <x-text-input id="email" class="w-full block mt-1" type="text" name="email"
                                        value="{{ old('email') ?? $investor->email }}" />
                                    <x-input-error :messages="$errors->get('email')" class="w-full mt-2" />
                                </div>
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="address" class="w-full mb-1" :value="__('word.address')" />
                                    <x-text-input id="address" class="w-full block mt-1" type="text" name="address"
                                        value="{{ old('address') ?? $investor->address }}" />
                                    <x-input-error :messages="$errors->get('address')" class="w-full mt-2" />
                                </div>

                            </div>

                            <div class=" mx-4 my-4 w-full">
                                <x-primary-button x-primary-button class="ml-4">
                                    {{ __('word.save') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
