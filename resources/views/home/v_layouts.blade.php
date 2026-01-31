<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard &mdash; @yield('title')</title>

    {{-- CSS Utama --}}
    <link rel="stylesheet" href="{{ asset('/template-stisla/dist/assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/template-stisla/dist/assets/modules/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/template-stisla/dist/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/template-stisla/dist/assets/css/components.css') }}">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>

    {{-- Tambahan jika sidebar mobile perlu di-toggle --}}
    <style>
        @media (max-width: 768px) {
            .main-sidebar {
                position: fixed;
                left: -260px;
                transition: all 0.3s ease-in-out;
                z-index: 999;
            }
            .main-sidebar.active {
                left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div id="app">
        <div class="main-wrapper">
            {{-- Navbar --}}
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <ul class="navbar-nav mr-3">
                    <li>
                        <a href="" data-toggle="sidebar" id="sidebarToggle" class="nav-link nav-link-lg">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <div class="fas fa-user mr-3"></div>
                        <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-title">Logged in 5 min ago</div>
                            <a href="" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            {{-- Sidebar --}}
            <div class="main-sidebar" id="sidebar">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="">Gps Tracking</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="">GT</a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Dashboard {{ Auth::user()->role }}</li>
                        <li class="{{ Route::is('indexAdmin') ? 'active' : '' }}">
                            <a href="{{ route('indexAdmin') }}" class="nav-link active"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                        </li>
                        <li class="menu-header">Menu {{ Auth::user()->role }}</li>
                        <li class="{{ Route::is('lastTracking') ? 'active' : '' }}">
                            <a href="{{ route('lastTracking') }}" class="nav-link active"><i class="fas fa-map-marker-alt"></i><span>Riwayat Lokasi</span></a>
                        </li>
                    </ul>
                </aside>
            </div>

            {{-- Konten Utama --}}
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>Dashboard</h1>
                        <div class="section-header-breadcrumb">
                            @yield('breadcrumb')
                        </div>
                    </div>
                    <div class="section-body">
                        @yield('content')
                    </div>
                </section>
            </div>

            {{-- Footer --}}
            <footer class="main-footer">
                <div class="footer-left">
                    &copy; {{ date('Y') }} - Sistem Tracking GPS
                </div>
            </footer>
        </div>
    </div>

    {{-- Script JS --}}
    <script src="{{ asset('/template-stisla/dist/assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('/template-stisla/dist/assets/modules/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- WAJIB: NICE SCROLL --}}
    <script src="{{ asset('/template-stisla/dist/assets/modules/jquery.nicescroll.min.js') }}"></script>

    <script src="{{ asset('/template-stisla/dist/assets/js/stisla.js') }}"></script>
    <script src="{{ asset('/template-stisla/dist/assets/js/scripts.js') }}"></script>

    {{-- Toggle Sidebar Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggle = document.getElementById("sidebarToggle");
            const sidebar = document.querySelector(".main-sidebar");

            if (toggle && sidebar) {
                toggle.addEventListener("click", function (e) {
                    e.preventDefault();
                    sidebar.classList.toggle("active");
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
