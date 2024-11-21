<!-- app css-->
<link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

<div class="flex ">
    @can('account-show')
        <a href="{{ route('account.show', $url_address) }}" class="my-1 mx-1 btn btn-custom-show">
            {{ __('word.view') }}
        </a>
    @endcan
    @can('account-update')
        <a href="{{ route('account.edit', $url_address) }}" class="my-1 mx-1 btn btn-custom-edit">
            {{ __('word.edit') }}
        </a>
    @endcan
    @can('account-delete')
        <form action="{{ route('account.destroy', $url_address) }}" method="post">
            @csrf
            @method('DELETE')

            <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                {{ __('word.delete') }}
            </button>

        </form>
    @endcan
</div>
