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

{{-- @can('report-soa')
    <a href="{{ route('report.soa') }}" class="me-3 {{ request()->routeIs('report.soa') ? 'underline-active' : '' }}">
        {{ __('word.report_soa') }}
    </a>
@endcan --}}

@can('report-costCenter')
    <a href="{{ route('report.costCenter') }}"
        class="me-3 {{ request()->routeIs('report.costCenter') ? 'underline-active' : '' }}">
        {{ __('word.report_costCenter') }}
    </a>
@endcan

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

@can('report-trialBalanceCostCenter')
    <a href="{{ route('report.balance-Sheet') }}"
        class="me-3 {{ request()->routeIs('report.balance-Sheet') ? 'underline-active' : '' }}">
        {{ __('word.report_balance_sheet') }}
    </a>
@endcan
