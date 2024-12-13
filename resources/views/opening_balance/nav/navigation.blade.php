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
@can('opening_balance-list')
    <a href="{{ route('opening_balance.index') }}"
        class="me-3 {{ request()->routeIs('opening_balance.index') ? 'underline-active' : '' }}">
        {{ __('word.opening_balance_search') }}
    </a>
@endcan

@can('opening_balance-create')
    <a href="{{ route('opening_balance.create') }}"
        class="me-3 {{ request()->routeIs('opening_balance.create') ? 'underline-active' : '' }}">
        {{ __('word.opening_balance_add') }}
    </a>
@endcan
