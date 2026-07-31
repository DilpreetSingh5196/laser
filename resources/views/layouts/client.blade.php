<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal - Jai Maa Durga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            overflow-x: hidden;
        }
        #wrapper {
            display: flex;
            width: 100vw;
            height: 100vh;
        }
        #sidebar {
            width: 250px;
            background-color: #0d4b80;
            color: #fff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            transition: all 0.3s;
        }
        .sidebar-brand {
            padding: 20px;
            font-size: 1.25rem;
            font-weight: 700;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-nav {
            padding: 15px 0;
            flex-grow: 1;
            overflow-y: auto;
        }
        .nav-item {
            padding: 5px 15px;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }
        .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-footer .nav-link {
            color: rgba(255,255,255,0.8);
        }
        .sidebar-footer .nav-link:hover {
            color: #fff;
        }
        
        #content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        #topbar {
            background-color: #fff;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            z-index: 10;
        }
        .topbar-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }
        #main-content {
            padding: 25px;
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar">
            <div class="sidebar-brand">
                Jai Maa Durga
            </div>
            <div class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('client.dashboard') }}" class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </div>
                
                <div class="px-3 mt-4 mb-2 text-uppercase text-white-50" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Operations</div>
                
                <div class="nav-item">
                    <a href="{{ route('client.orders.create') }}" class="nav-link {{ request()->routeIs('client.orders.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i> Create Order
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('client.orders.index') }}" class="nav-link {{ request()->routeIs('client.orders.index') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i> My Orders
                    </a>
                </div>
            </div>
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link w-100 text-start text-decoration-none p-2">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div id="content-wrapper">
            <!-- Topbar -->
            <div id="topbar">
                <h1 class="topbar-title">@yield('page_title', 'Client Dashboard')</h1>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a class="text-dark text-decoration-none dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 me-2"></i> {{ auth()->guard('client')->user()->client_name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('client.profile.edit') }}"><i class="bi bi-person"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div id="main-content">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    timer: 4000,
                    showConfirmButton: true
                });
            @endif
        });
    </script>
    @yield('scripts')
</body>
</html>
