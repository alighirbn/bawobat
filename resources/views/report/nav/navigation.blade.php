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
    <a href="{{ route('report.trialBalance') }}"
        class="me-3 {{ request()->routeIs('report.trialBalance') ? 'underline-active' : '' }}">
        {{ __('word.report_trialBalance') }}
    </a>
@endcan

@can('report-trialBalanceCostCenter')
    <a href="{{ route('report.trialBalanceCostCenter') }}"
        class="me-3 {{ request()->routeIs('report.trialBalanceCostCenter') ? 'underline-active' : '' }}">
        {{ __('word.report_trialBalanceCostCenter') }}
    </a>
@endcan
