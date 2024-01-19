<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        <svg class="sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('/assets/admin/assets/brand/coreui.svg#full') }}"></use>
        </svg>
        <svg class="sidebar-brand-narrow" width="46" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('/assets/admin/assets/brand/coreui.svg#signet') }}"></use>
        </svg>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
        <li class="nav-item">
            <a class="nav-link" href="/" wire:navigate>
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                </svg> {{ $isManager ? 'Manager' : 'Teacher' }} Dashboard
            </a>
        </li>
        @if($isManager)
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.shifts.index') }}" wire:navigate>
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}"></use>
                    </svg> シフト管理
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.roles.index') }}" wire:navigate>
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-drop') }}"></use>
                    </svg> Roles
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.schools.index') }}" wire:navigate>
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-drop') }}"></use>
                    </svg> Schools
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.time.create') }}" wire:navigate>
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-pencil') }}"></use>
                    </svg> 勤怠申請
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.shift.application') }}" wire:navigate>
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-calendar') }}"></use>
                    </svg> シフト申請
                </a>
            </li>
        @endif
    </ul>
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>
