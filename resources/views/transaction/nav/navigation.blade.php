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
@can('transaction-list')
    <a href="{{ route('transaction.index') }}"
        class="me-3 {{ request()->routeIs('transaction.index') ? 'underline-active' : '' }}">
        {{ __('word.transaction_search') }}
    </a>
@endcan

@can('transaction-create')
    <a href="{{ route('transaction.create') }}"
        class="me-3 {{ request()->routeIs('transaction.create') ? 'underline-active' : '' }}">
        {{ __('word.transaction_add') }}
    </a>
@endcan
