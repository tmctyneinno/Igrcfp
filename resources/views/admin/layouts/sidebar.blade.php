<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="index.html" class="sidebar-logo">
        <img src="{{ asset('assets/images/home-three/logo/logo-main.png')}}" alt="site logo" class="light-logo">
        <img src="{{ asset('assets/images/home-three/logo/logo-main.png')}}" alt="site logo" class="dark-logo">
        <img src="{{ asset('assets/images/home-three/logo/logo-main.png')}}" alt="site logo" class="logo-icon">
        </a>
    </div> 
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li> 
            
            <!-- Users Management -->
            <li>
                <a href="{{ route('admin.users.index') }}">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Users</span>
                </a>
            </li>

            <!-- Courses Management -->
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                    <span>Courses</span> 
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.courses.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.courses.create') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Add new</a>
                    </li>
                </ul>
            </li>

            <!-- NEW: Enrollments Management -->
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mingcute:user-star-line" class="menu-icon"></iconify-icon>
                    <span>Enrollments</span> 
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.enrollments.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Enrollments</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.enrollments.pending') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Pending</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.enrollments.completed') }}"><i class="ri-circle-fill circle-icon text-success-main w-auto"></i> Completed</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.enrollments.cancelled') }}"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Cancelled</a>
                    </li>
                </ul>
            </li>

            <!-- NEW: Transactions Management -->
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:transaction" class="menu-icon"></iconify-icon>
                    <span>Transactions</span> 
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.transactions.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> All Transactions</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transactions.pending') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Pending</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transactions.completed') }}"><i class="ri-circle-fill circle-icon text-success-main w-auto"></i> Completed</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transactions.failed') }}"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Failed</a>
                    </li>
                </ul>
            </li>

            <!-- NEW: Revenue Reports -->
            <li>
                <a href="{{ route('admin.reports.revenue') }}">
                    <iconify-icon icon="solar:chart-2-outline" class="menu-icon"></iconify-icon>
                    <span>Revenue Reports</span>
                </a>
            </li>

            <!-- Blogs Management -->
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Blogs</span> 
                </a>  
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.blogs.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.blogs.create') }}"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add new</a>
                    </li>
                </ul>
            </li>

            <!-- Events Management -->
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                    <span>Events</span> 
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.events.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.events.create') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Add new</a>
                    </li>
                </ul>
            </li>

            <!-- News/Articles Management -->
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                    <span>News</span> 
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.articles.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.articles.create') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Add new</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>