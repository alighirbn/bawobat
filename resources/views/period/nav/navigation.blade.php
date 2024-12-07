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
@can('period-list')
    <a href="{{ route('period.index') }}" class="me-3 {{ request()->routeIs('period.index') ? 'underline-active' : '' }}">
        {{ __('word.period_search') }}
    </a>
@endcan

@can('period-create')
    <a href="{{ route('period.create') }}" class="me-3 {{ request()->routeIs('period.create') ? 'underline-active' : '' }}">
        {{ __('word.period_add') }}
    </a>
@endcan
