<x-app-layout>

    <x-slot name="header">
        <!-- app css-->
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

        @include('project.nav.navigation')

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        @can('project-archive')
                            <a href="{{ route('project.archivecreate', $project->url_address) }}"
                                class="btn btn-custom-archive">
                                {{ __('word.project_archive') }}
                            </a>

                            <a href="{{ route('project.scancreate', $project->url_address) }}"
                                class="btn btn-custom-archive">
                                {{ __('word.project_scan') }}
                            </a>
                        @endcan
                        @can('project-archiveshow')
                            <a href="{{ route('project.archiveshow', $project->url_address) }}"
                                class="btn btn-custom-archive">
                                {{ __('word.archiveshow') }}
                            </a>
                        @endcan

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
                    <div>
                        <h1 class=" font-semibold underline text-l text-gray-900 leading-tight mx-4  w-full">
                            {{ __('word.project_info') }}
                        </h1>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="name" class="w-full mb-1" :value="__('word.name')" />
                                <p id="name" class="w-full h-9 block mt-1" type="text" name="name">
                                    {{ $project->name }}
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="description" class="w-full mb-1" :value="__('word.description')" />
                                <p id="description" class="w-full h-9 block mt-1" type="text" name="description">
                                    {{ $project->description }}
                            </div>
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="budget" class="w-full mb-1" :value="__('word.budget')" />
                                <p id="budget" class="w-full h-9 block mt-1 " type="text" name="budget">
                                    {{ number_format($project->budget, 0) . ' دينار ' }}
                            </div>

                        </div>
                        <div class="flex ">
                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="start_date" class="w-full mb-1" :value="__('word.start_date')" />
                                <p id="start_date" class="w-full h-9 block mt-1" type="text" name="start_date">
                                    {{ $project->start_date }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="end_date" class="w-full mb-1" :value="__('word.end_date')" />
                                <p id="end_date" class="w-full h-9 block mt-1 " type="text" name="end_date">
                                    {{ $project->end_date }}
                            </div>

                            <div class=" mx-4 my-4 w-full ">
                                <x-input-label for="status" class="w-full mb-1" :value="__('word.status')" />
                                <p id="status" class="w-full h-9 block mt-1 " type="text" name="status">
                                    {{ $project->status }}
                            </div>

                        </div>

                        <div class="flex">
                            @if (isset($project->user_id_create))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_create') }} {{ $project->user_create->name }}
                                    {{ $project->created_at }}
                                </div>
                            @endif

                            @if (isset($project->user_id_update))
                                <div class="mx-4 my-4 ">
                                    {{ __('word.user_update') }} {{ $project->user_update->name }}
                                    {{ $project->updated_at }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
