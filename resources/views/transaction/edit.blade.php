<x-app-layout>

    <x-slot name="header">
        @include('account.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <form method="post" action="{{ route('account.update', $account->url_address) }}">
                            @csrf
                            @method('patch')
                            <input type="hidden" id="id" name="id" value="{{ $account->id }}">
                            <input type="hidden" id="url_address" name="url_address"
                                value="{{ $account->url_address }}">

                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.account_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="code" class="w-full mb-1" :value="__('word.code')" />
                                    <x-text-input id="code" class="w-full block mt-1" type="text" name="code"
                                        value="{{ old('code') ?? $account->code }}" />
                                    <x-input-error :messages="$errors->get('code')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="name" class="w-full mb-1" :value="__('word.name')" />
                                    <x-text-input id="name" class="w-full block mt-1" type="text" name="name"
                                        value="{{ old('name') ?? $account->name }}" />
                                    <x-input-error :messages="$errors->get('name')" class="w-full mt-2" />
                                </div>

                            </div>

                            <div class="flex">
                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="type" class="w-full mb-1" :value="__('word.type')" />

                                    <select id="type" class="js-example-basic-single w-full block mt-1 "
                                        name="type" data-placeholder="ادخل نوع الحساب   ">
                                        <option value="liability"
                                            {{ old('type') ?? $account->type == 'liability' ? 'selected' : '' }}>
                                            {{ __('اعباء') }}
                                        </option>
                                        <option value="asset"
                                            {{ old('type') ?? $account->type == 'asset' ? 'selected' : '' }}>
                                            {{ __('اصول') }}
                                        </option>
                                        <option value="expense"
                                            {{ old('type') ?? $account->type == 'expense' ? 'selected' : '' }}>
                                            {{ __('صرف') }}
                                        </option>
                                        <option value="income"
                                            {{ old('type') ?? $account->type == 'income' ? 'selected' : '' }}>
                                            {{ __('ايراد') }}
                                        </option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type')" class="w-full mt-2" />
                                </div>
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="class" class="w-full mb-1" :value="__('word.class')" />
                                    <x-text-input id="class" class="w-full block mt-1" type="text" name="class"
                                        value="{{ old('class') ?? $account->class }}" />
                                    <x-input-error :messages="$errors->get('class')" class="w-full mt-2" />
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
