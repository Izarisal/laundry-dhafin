<nav class="navbar navbar-top navbar-expand navbar-dashboard navbar-dark ps-0 pe-2 pb-0">
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between w-100">
            <div class="d-flex align-items-center">
                <span class="fw-bold text-gray-700 d-none d-md-inline">Sistem Informasi Laundry</span>
            </div>
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle pt-1 px-0" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="media d-flex align-items-center">
                            <div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
                                <span class="mb-0 font-small fw-bold text-gray-900">{{ auth()->user()->name }}</span>
                                <span class="badge bg-{{ auth()->user()->isAdmin() ? 'danger' : 'info' }} ms-1">
                                    {{ auth()->user()->role }}
                                </span>
                            </div>
                            <span class="d-lg-none fw-bold">{{ auth()->user()->name }}</span>
                        </div>
                    </a>
                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-end mt-2 py-1">
                        <span class="dropdown-item font-small text-muted">{{ auth()->user()->email }}</span>
                        <div role="separator" class="dropdown-divider my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item fw-bold text-danger">Logout</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
