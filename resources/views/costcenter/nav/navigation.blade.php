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
@can('costcenter-list')
    <a href="{{ route('costcenter.index') }}"
        class="me-3 {{ request()->routeIs('costcenter.index') ? 'underline-active' : '' }}">
        {{ __('word.costcenter_search') }}
    </a>
@endcan

@can('costcenter-create')
    <a href="{{ route('costcenter.create') }}"
        class="me-3 {{ request()->routeIs('costcenter.create') ? 'underline-active' : '' }}">
        {{ __('word.costcenter_add') }}
    </a>
@endcan
