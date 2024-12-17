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

@can('report-trialBalance')
    <a href="{{ route('report.trial_balance') }}"
        class="me-3 {{ request()->routeIs('report.trial_balance') ? 'underline-active' : '' }}">
        {{ __('word.trial_balance') }}
    </a>
@endcan

@can('report-trialBalanceCostCenter')
    <a href="{{ route('report.balance_sheet') }}"
        class="me-3 {{ request()->routeIs('report.balance_sheet') ? 'underline-active' : '' }}">
        {{ __('word.report_balance_sheet') }}
    </a>
@endcan
