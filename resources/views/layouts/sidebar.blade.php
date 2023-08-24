
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Koperasi APP</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item{{ request()->is('companies*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('companies*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-city"></i>
                        <p>
                            Perusahaan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('companies.index') }}"
                                class="nav-link {{ request()->is('companies*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>company</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item{{ request()->is('kas*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('kas*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Keuangan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('kas.index') }}"
                                class="nav-link {{ request()->is('kas*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item{{ request()->is('produk*') || request()->is('ledgers*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('produk*') || request()->is('ledgers*')? 'active' : '' }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>
                            Produk
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('produk.index') }}"
                                class="nav-link {{ request()->is('produk*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('ledgers.index') }}"
                                class="nav-link {{ request()->is('ledgers*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ledger</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item{{ request()->is('anggota*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('anggota*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-address-card"></i>
                        <p>
                            Anggota
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('anggota.index') }}"
                                class="nav-link {{ request()->is('anggota*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Anggota</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item{{ request()->is('users*') || request()->is('roles*') || request()->is('permissions*') ? ' menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('users*') || request()->is('roles*') || request()->is('permissions*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Management Users
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}"
                                class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}"
                                class="nav-link {{ request()->is('permissions*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                    </ul>
                </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
