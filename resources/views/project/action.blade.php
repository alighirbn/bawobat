<!-- app css-->
<link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

<div class="flex ">
    @can('project-show')
        <a href="{{ route('project.show', $url_address) }}" class="my-1 mx-1 btn btn-custom-show">
            {{ __('word.view') }}
        </a>
    @endcan

    @can('project-update')
        <a href="{{ route('project.edit', $url_address) }}" class="my-1 mx-1 btn btn-custom-edit">
            {{ __('word.edit') }}
        </a>
    @endcan
    @can('project-delete')
        <form action="{{ route('project.destroy', $url_address) }}" method="post">
            @csrf
            @method('DELETE')

            <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                {{ __('word.delete') }}
            </button>

        </form>
    @endcan
</div>
