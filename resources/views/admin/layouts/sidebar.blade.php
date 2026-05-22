{{-- Update your sidebar to conditionally show menu items based on role --}}
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

            {{-- Admin Management - Only for Super Admin and Origin Admin --}}
            @if(auth()->guard('admin')->user()->isAdmin())
                <li>
                    <a href="{{ route('admin.admins.index') }}">
                        <iconify-icon icon="ic:baseline-admin-panel-settings" class="menu-icon"></iconify-icon>
                        <span>Admin Users</span>
                    </a>
                </li>
            @endif
            
            <!-- Users Management - All admins -->
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
                    @if(auth()->guard('admin')->user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.courses.create') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Add new</a>
                        </li>
                    @endif
                    <li class="sidebar-separator">
                        <hr class="my-2 mx-3 opacity-25">
                    </li>
                    <li>
                        <a href="{{ route('admin.course-categories.index') }}">
                            <i class="ri-circle-fill circle-icon text-info-600 w-auto"></i> 
                            Categories
                        </a>
                    </li>
                    @if(auth()->guard('admin')->user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.course-categories.create') }}">
                                <i class="ri-circle-fill circle-icon text-success-main w-auto"></i> 
                                Add Category
                            </a>
                        </li>
                    @endif
                </ul>
            </li>

            <!-- Assessments - All admins can view, only admins can create -->
            <li class="sidebar-separator">
                <hr class="my-2 mx-3 opacity-25">
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                    <span>Assessments</span> 
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.assessments.all') }}">
                            <i class="ri-circle-fill circle-icon text-green-600 w-auto"></i>
                            All Assessments
                        </a>
                    </li>
                    @if(auth()->guard('admin')->user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.assessments.create.quiz') }}">
                                <i class="ri-circle-fill circle-icon text-green-600 w-auto"></i>
                                Quiz
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.assessments.create.project') }}">
                                <i class="ri-circle-fill circle-icon text-blue-600 w-auto"></i>
                                Project Assessment 
                            </a> 
                        </li>
                    @endif
                </ul>
            </li>
           
            <!-- Enrollments Management -->
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

            <!-- Transactions & Reports - Only for admins -->
            @if(auth()->guard('admin')->user()->isAdmin())
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

                <li>
                    <a href="{{ route('admin.reports.revenue') }}">
                        <iconify-icon icon="solar:chart-2-outline" class="menu-icon"></iconify-icon>
                        <span>Revenue Reports</span>
                    </a>
                </li>
            @endif

            {{-- Add to your admin sidebar/navigation --}}
            @if(auth()->guard('admin')->user()->isAdmin())
                <li class="nav-item {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.activity-logs.index') }}">
                        <i class="fas fa-history"></i>
                        <span>Activity Logs</span>
                    </a>
                </li>
            @endif

            <!-- Memberships & Mentors -->
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:account-badge-outline" class="menu-icon"></iconify-icon>
                    <span>Memberships</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.membership-tiers.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Tiers</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.membership-plans.index') }}"><i class="ri-circle-fill circle-icon text-info-600 w-auto"></i> Plans</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.membership-approvals.index') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Approvals</a>
                    </li>
                </ul>
            </li>
             <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="mdi:account-group-outline" class="menu-icon"></iconify-icon>
                    <span>Mentors</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.mentors.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Mentor List</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.mentor-applications.index') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Applications</a>
                    </li>
                </ul>
            </li>
            
            {{-- Scholarship Applications - NEW --}}
            <li class="sidebar-separator">
                <hr class="my-2 mx-3 opacity-25">
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="ph:student-fill" class="menu-icon"></iconify-icon>
                    <span>Scholarships</span>
                    <span class="badge bg-warning ms-2">{{ \App\Models\ScholarshipApplication::where('status', 'pending')->count() }}</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.scholarships.index') }}">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> 
                            All Applications
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.scholarships.index', ['status' => 'pending']) }}">
                            <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> 
                            Pending
                            <span class="badge bg-warning ms-1">{{ \App\Models\ScholarshipApplication::where('status', 'pending')->count() }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.scholarships.index', ['status' => 'under_review']) }}">
                            <i class="ri-circle-fill circle-icon text-info-main w-auto"></i> 
                            Under Review
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.scholarships.index', ['status' => 'accepted']) }}">
                            <i class="ri-circle-fill circle-icon text-success-main w-auto"></i> 
                            Accepted
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.scholarships.index', ['status' => 'rejected']) }}">
                            <i class="ri-circle-fill circle-icon text-danger-main w-auto"></i> 
                            Rejected
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Blogs Management - All admins -->
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Blogs</span> 
                </a>  
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.blogs.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                    </li>
                    @if(auth()->guard('admin')->user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.blogs.create') }}"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add new</a>
                        </li>
                    @endif
                </ul>
            </li>
            

            <!-- Events Management - All admins -->
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                    <span>Events</span> 
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.events.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                    </li>
                    @if(auth()->guard('admin')->user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.events.create') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Add new</a>
                        </li>
                    @endif
                </ul>
            </li>

            <!-- News Management - All admins -->
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                    <span>News</span> 
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('admin.articles.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                    </li>
                    @if(auth()->guard('admin')->user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.articles.create') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Add new</a>
                        </li>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</aside>