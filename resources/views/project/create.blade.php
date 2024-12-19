<x-app-layout>

    <x-slot name="header">
        @include('project.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div>
                        <form method="post" action="{{ route('project.store') }}">
                            @csrf
                            <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                                {{ __('word.project_info') }}
                            </h1>

                            <div class="flex ">
                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="name" class="w-full mb-1" :value="__('word.name')" />
                                    <x-text-input id="name" class="w-full block mt-1" type="text" name="name"
                                        value="{{ old('name') }}" />
                                    <x-input-error :messages="$errors->get('name')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="description" class="w-full mb-1" :value="__('word.description')" />
                                    <x-text-input id="description" class="w-full block mt-1" type="text"
                                        name="description" value="{{ old('description') }}" />
                                    <x-input-error :messages="$errors->get('description')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full ">
                                    <x-input-label for="budget" class="w-full mb-1" :value="__('word.budget')" />
                                    <x-text-input id="budget" class="w-full block mt-1" type="text" name="budget"
                                        value="{{ old('budget') }}" />
                                    <x-input-error :messages="$errors->get('budget')" class="w-full mt-2" />
                                </div>

                            </div>

                            <div class="flex">
                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="start_date" class="w-full mb-1" :value="__('word.start_date')" />
                                    <x-text-input id="start_date" class="w-full block mt-1" type="date"
                                        name="start_date" value="{{ old('start_date') }}" />
                                    <x-input-error :messages="$errors->get('start_date')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="end_date" class="w-full mb-1" :value="__('word.end_date')" />
                                    <x-text-input id="end_date" class="w-full block mt-1" type="date"
                                        name="end_date" value="{{ old('end_date') }}" />
                                    <x-input-error :messages="$errors->get('end_date')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="status" class="w-full mb-1" :value="__('word.status')" />
                                    <select id="status"
                                        class="w-full block mt-1 border-gray-300 rounded-md shadow-sm" name="status">
                                        <option value="">{{ __('word.select_status') }}</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="w-full mt-2" />
                                </div>

                                <div class=" mx-4 my-4 w-full">
                                    <x-input-label for="cost_center_id" class="w-full mb-1" :value="__('word.cost_center')" />
                                    <select id="cost_center_id"
                                        class="w-full block mt-1 border-gray-300 rounded-md shadow-sm"
                                        name="cost_center_id">
                                        <option value="">{{ __('word.select_cost_center') }}</option>
                                        @foreach ($cost_centers as $cost_center)
                                            <option value="{{ $cost_center->id }}"
                                                {{ old('cost_center_id') == $cost_center->id ? 'selected' : '' }}>
                                                {{ $cost_center->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('cost_center_id')" class="w-full mt-2" />
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
    <script>
        // Prevent double submission
        $('form').on('submit', function() {
            // Find the submit button
            var $submitButton = $(this).find('button[type="submit"]');

            // Change the button text to 'Submitting...'
            $submitButton.text('جاري الحفظ');

            // Disable the submit button
            $submitButton.prop('disabled', true);
        });
    </script>
</x-app-layout>
