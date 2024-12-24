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

<a href="{{ route('payments.import') }}"
    class="me-3 {{ request()->routeIs('payments.import') ? 'underline-active' : '' }}">
    {{ __('word.payment_import') }}
</a>

<a href="{{ route('payments.imported') }}"
    class="me-3 {{ request()->routeIs('payments.imported') ? 'underline-active' : '' }}">
    {{ __('word.payment_imported') }}
</a>
