<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link {{ Request::is('/dashboard') ? 'active':''}}" href="{{url('/dashboard')}}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                
                <div class="sb-sidenav-menu-heading">Management</div>
                
                <!-- Users Section -->
                <a class="nav-link {{ Request::is('/users') ? 'active':''}}" href="{{url('/users')}}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Users
                </a>
                
                <a class="nav-link {{ Request::is('/tasks') ? 'active':''}}" href="{{url('/tasks')}}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    tasks
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            Admin
        </div>
    </nav>
</div>
