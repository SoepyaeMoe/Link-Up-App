<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard" class="brand-link">
        <img src="{{ asset('image/share-link.png') }}" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Linkup Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <div class="my-2">
            <p class="text-white mb-0 text-center">{{ Auth::user() ? Auth::user()->name : '' }}</p>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2 relative">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                       with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ url('dashboard') }}" class="nav-link @yield('dashboard-active')">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('users') }}" class="nav-link @yield('users-acitve')">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('admin-users') }}" class="nav-link @yield('admin-active')">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Admins</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('contents') }}" class="nav-link @yield('contents-acitve')">
                        <i class="nav-icon fa-solid fa-paper-plane"></i>
                        <p>Contents</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin-history') }}" class="nav-link @yield('admin-history')">
                        <i class="nav-icon fa-solid fa-clock-rotate-left"></i>
                        <p>History</p>
                    </a>
                </li>

                <li class="nav-item"" onclick="logout()" style="cursor: pointer;">
                    <form action="logout" method="post" id="logout">
                        @csrf
                        <a class="nav-link">
                            <i class="nav-icon fa-solid fa-right-from-bracket fa-fw"></i>
                            <p>Logout</p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

@section('extra-script')
<script>
    let form = document.getElementById('logout');
    function logout(){
        if (confirm('Are you sure, you want to logout?') == true) {
            form.submit();
        }
    }
</script>
@endsection
