 <!-- Sidebar Start -->
 <div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="{{route('dashboard')}}" class="navbar-brand mx-4 mb-3">
        <img class="profile_logo" src="{{URL::asset('images/site_logo.svg')}}" alt="site-logo">
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="{{URL::asset('images/user.jpg')}}" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0 admin_name">{{ Auth::user()->name }}</h6>
                <span>Admin</span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="{{route('dashboard')}}" class="nav-item nav-link dashboard-nav-link"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            
            <a href="{{route('admin-profile')}}" class="nav-item nav-link profile-nav-link"><i class="fa fa-th me-2"></i>Profile</a>
            <a href="{{route('users')}}" class="nav-item nav-link categories-nav-link"><i class="fa fa-keyboard me-2"></i>Users</a>
            <a href="{{route('feedback')}}" class="nav-item nav-link add-products-nav-link"><i class="fa fa-table me-2"></i>Feedbacks</a>
            <a href="" class="nav-item nav-link products-nav-link"><i class="fa fa-table me-2"></i>Products</a>
            
        </div>
    </nav>
</div>
        <!-- Sidebar End -->
