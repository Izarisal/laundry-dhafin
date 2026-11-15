<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - {{ config('app.name', 'Laundry') }}</title>
    @include('layouts.admin.css')
</head>

<body>
    <main>
        <section class="d-flex align-items-center my-5 mt-lg-6 mb-lg-5">
            <div class="container">
                <div class="row justify-content-center form-bg-image">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                            <div class="text-center text-md-center mb-4 mt-md-0">
                                <h1 class="mb-1 h3">@yield('heading')</h1>
                                <p class="text-muted mb-0">Sistem Informasi Manajemen Laundry</p>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="{{ asset('assets-admin/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>

</html>
