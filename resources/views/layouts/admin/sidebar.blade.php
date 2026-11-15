@php $user = auth()->user(); @endphp

{{-- Navbar mobile --}}
<nav class="navbar navbar-dark navbar-theme-primary px-4 col-12 d-lg-none">
    <a class="navbar-brand me-lg-5 fw-bold text-white" href="{{ route('dashboard') }}">Laundry</a>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
    <div class="sidebar-inner px-4 pt-3">
        <div class="pb-3 d-none d-lg-block">
            <h2 class="h5 mb-0">Laundry</h2>
            <small class="text-gray-400">Halo, {{ $user->name }}</small>
        </div>

        <ul class="nav flex-column pt-3 pt-md-0">
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <span class="sidebar-icon">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                    </span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            @if ($user->isAdmin())
                <li class="nav-item {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
                    <a href="{{ route('pelanggan.index') }}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Transaksi Laundry</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                        </span>
                        <span class="sidebar-text">Kelola User</span>
                    </a>
                </li>
            @else
                <li class="nav-item {{ request()->routeIs('pesanan.create') ? 'active' : '' }}">
                    <a href="{{ route('pesanan.create') }}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Buat Pesanan</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('pesanan.index') ? 'active' : '' }}">
                    <a href="{{ route('pesanan.index') }}" class="nav-link">
                        <span class="sidebar-icon">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </span>
                        <span class="sidebar-text">Pesanan Saya</span>
                    </a>
                </li>
            @endif

            <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-start w-100 text-danger">
                        <span class="sidebar-text">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
