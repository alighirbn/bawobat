<style>
    a.underline-active {
        position: relative;
    }

    a.underline-active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        /* Adjust this value to control how far down the underline appears */
        width: 100%;
        height: 3px;
        /* Thickness of the underline */
        background-color: #e8f8ff;
        /* Color of the underline */
    }
</style>
@can('investor-list')
    <a href="{{ route('investor.index') }}" class="me-3 {{ request()->routeIs('investor.index') ? 'underline-active' : '' }}">
        {{ __('word.investor_search') }}
    </a>
@endcan

@can('investor-create')
    <a href="{{ route('investor.create') }}"
        class="me-3 {{ request()->routeIs('investor.create') ? 'underline-active' : '' }}">
        {{ __('word.investor_add') }}
    </a>
@endcan
