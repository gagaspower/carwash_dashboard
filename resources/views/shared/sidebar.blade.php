<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-simplebar>
        <div class="d-flex mb-4 align-items-center justify-content-between">
            <a href="index.html" class="text-nowrap logo-img ms-0 ms-md-1">
                <img src="{{asset('assets/images/logos/dark-logo.svg')}}" width="180" alt="">
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="mb-4 pb-2">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link primary-hover-bg" href="{{url('/')}}" aria-expanded="false">
                        <span class="aside-icon p-2 bg-light-primary rounded-3">
                            <i class="ti ti-layout-dashboard fs-7 text-primary"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
                    <span class="hide-menu">Master</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link warning-hover-bg" href="{{url('/customer')}}"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-light-warning rounded-3">
                            <i class="ti ti-users fs-7 text-warning"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">Pelanggan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link danger-hover-bg" href="{{ url('/merk-kendaraan') }}"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-light-danger rounded-3">
                            <i class="ti ti-car fs-7 text-danger"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">Merk Kendaraan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link primary-hover-bg" href="{{url('/role')}}" aria-expanded="false">
                        <span class="aside-icon p-2 bg-light-primary rounded-3">
                            <i class="ti ti-lock-access fs-7 text-primary"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">Hak Akses</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link success-hover-bg" href="{{url('/user')}}" aria-expanded="false">
                        <span class="aside-icon p-2 bg-light-success rounded-3">
                            <i class="ti ti-user fs-7 text-success"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">Admin & Pegawai</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
                    <span class="hide-menu">Pemasukan & Pengeluaran</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link warning-hover-bg" href="./authentication-login.html"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-light-warning rounded-3">
                            <i class="ti ti-arrow-narrow-down fs-7 text-warning"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">Pemasukan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link sidebar-link warning-hover-bg" href="./authentication-register.html"
                        aria-expanded="false">
                        <span class="aside-icon p-2 bg-light-warning rounded-3">
                            <i class="ti ti-arrow-narrow-up fs-7 text-warning"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">Pengeluaran</span>
                    </a>
                </li>
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>