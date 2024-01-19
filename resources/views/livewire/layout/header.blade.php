<header class="header header-sticky mb-4">
    <div class="container-fluid">
        <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
            <svg class="icon icon-lg">
                <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
            </svg>
        </button>
        <a class="header-brand d-md-none" href="#">
            <svg width="118" height="46" alt="CoreUI Logo">
                <use xlink:href="{{ asset('/assets/admin/assets/brand/coreui.svg#full') }}"></use>
            </svg>
        </a>

        <ul class="header-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-bell') }}"></use>
                    </svg>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-list-rich') }}"></use>
                    </svg>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-envelope-open') }}"></use>
                    </svg>
                </a>
            </li>
        </ul>
        <ul class="header-nav ms-3">
            <li class="nav-item dropdown">
                <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md">
                        <img class="avatar-img" src="{{ asset('/assets/admin/assets/img/avatars/8.jpg') }}" alt="user@email.com">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-light py-2">
                        <div class="fw-semibold">Settings</div>
                    </div>
                    <a class="dropdown-item" href="#">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                        </svg> Profile </a>
                    <div class="dropdown-divider"></div>
                    @livewire('auth.logout')
                </div>
            </li>
        </ul>
    </div>
    <div class="header-divider"></div>
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0 ms-2">
                <li class="breadcrumb-item">
                    <a href="/" style="color: rgba(44, 56, 74, 0.95); text-decoration: none">
                        <svg class="icon me-2">
                            <use xlink:href="{{ asset('/assets/admin/vendors/@coreui/icons/svg/free.svg#cil-home') }}"></use>
                        </svg>
                    </a>
                    <!-- if breadcrumb is single-->
                </li>
                @if(!empty($breadcrumbs))
                    @foreach($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item @if($loop->last) active @endif">
                            @if($loop->last)
                            <span>{{ $breadcrumb['name'] }}</span>
                            @else
                                <a href="{{ $breadcrumb['url'] ?? '#' }}" style="color: rgba(44, 56, 74, 0.95); text-decoration: none">{{ $breadcrumb['name'] }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            </ol>
        </nav>
    </div>
</header>
