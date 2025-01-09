<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />
        <div class="flex justify-start">
            @include('income.nav.navigation')
            @include('expense.nav.navigation')
            @include('transaction.nav.navigation')
        </div>
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
                                @foreach ($archives as $archive)
                                    <div class="col-md-3">
                                        <div class="card mb-3">
                                            <img src="{{ asset($archive->image_path) }}" class="card-img-top"
                                                alt="Contract Image">
                                            <div class="card-body text-center">
                                                <form action="{{ route('archive.delete', $archive->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('هل انت متأكد من حذف الصورة ؟?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-custom-delete">
                                                        {{ __('word.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
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
