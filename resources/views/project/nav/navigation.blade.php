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
@can('project-list')
    <a href="{{ route('project.index') }}" class="me-3 {{ request()->routeIs('project.index') ? 'underline-active' : '' }}">
        {{ __('word.project_search') }}
    </a>
@endcan

@can('project-create')
    <a href="{{ route('project.create') }}" class="me-3 {{ request()->routeIs('project.create') ? 'underline-active' : '' }}">
        {{ __('word.project_add') }}
    </a>
@endcan
