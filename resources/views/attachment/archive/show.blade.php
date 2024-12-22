<x-app-layout>

    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

    </x-slot>

    <div class="bg-custom py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="header-buttons mb-4">
                        <a href="{{ url()->previous() }}" class="btn btn-custom-back">
                            {{ __('word.back') }}
                        </a>
                        <a href="{{ route(strtolower(class_basename($model)) . '.show', $url_address) }}"
                            class="btn btn-custom-show">
                            {{ __('word.view') }}
                        </a>
                    </div>

                    <div class="row">
                        @if ($archives->isEmpty())
                            <p>No archived images available.</p>
                        @else
                            <div class="row">
                                @foreach ($archives as $image)
                                    <div class="col-md-3">
                                        <img src="{{ asset($image) }}" class="card-img-top" alt="Contract Image">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
