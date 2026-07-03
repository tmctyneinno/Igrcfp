import ApplicationLogo from '@/Components/ApplicationLogo';
import Dropdown from '@/Components/Dropdown';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import { Link, usePage, router } from '@inertiajs/react';
import React, { useState, useEffect } from 'react';
import { Toaster } from 'react-hot-toast';
import AuthenticatedFooter from '@/Layouts/AuthenticatedFooter';
import { 
  BellIcon, 
  ShoppingCartIcon, 
  UserCircleIcon,
  CogIcon,
  ArrowRightOnRectangleIcon, 
  ChevronDownIcon
} from '@heroicons/react/24/outline'; 

export default function AuthenticatedLayout({ header, children }) {
    const { props } = usePage();
    const user = props.auth.user; 
    const [showingNavigationDropdown, setShowingNavigationDropdown] = useState(false);
    const [notificationCount, setNotificationCount] = useState(0);
    const [notifications, setNotifications] = useState([]);
    const [openDropdown, setOpenDropdown] = useState(null);
    const [showNotificationDropdown, setShowNotificationDropdown] = useState(false);
    
    const cartCount = props.cart_count || 0;

    // ✅ Fetch notification count and recent notifications
    useEffect(() => {
        fetchNotificationCount();
        fetchRecentNotifications();
        
        // Poll for new notifications every 30 seconds
        const interval = setInterval(() => {
            fetchNotificationCount();
        }, 30000);
        
        return () => clearInterval(interval);
    }, []);
    
    const fetchNotificationCount = async () => {
        try {
            const response = await fetch(route('dashboard.notifications.unread-count'));
            const data = await response.json();
            setNotificationCount(data.count || 0);
        } catch (error) {
            console.error('Failed to fetch notification count:', error);
        }
    };
    
    const fetchRecentNotifications = async () => {
        try {
            const response = await fetch(route('dashboard.notifications.recent'));
            const data = await response.json();
            setNotifications(data.notifications || []);
        } catch (error) {
            console.error('Failed to fetch notifications:', error);
        }
    };
    
    const markAsRead = async (notificationId) => {
        try {
            await fetch(route('dashboard.notifications.mark-read', notificationId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });
            fetchNotificationCount();
            fetchRecentNotifications();
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    };

    return (
        <div className="min-h-screen bg-gray-50">
            <Toaster 
                position="top-right"
                toastOptions={{
                    duration: 4000,
                    style: {
                        background: '#363636',
                        color: '#fff',
                        zIndex: 9999,
                    },
                    success: {
                        duration: 3000,
                        icon: '✅',
                        style: {
                            background: '#10b981',
                        },
                    },
                    error: {
                        duration: 4000,
                        icon: '❌',
                        style: {
                            background: '#ef4444',
                        },
                    },
                }}
            /> 
            <nav className="border-b border-gray-200 bg-white shadow-sm">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex h-16 justify-between">
                        {/* Logo and Navigation */}
                        <div className="flex items-center">
                            <div className="flex shrink-0 items-center">
                                <Link href="/"> 
                                    <img 
                                        src="/assets/images/home-three/logo/logo-main.png" 
                                        alt="Logo" 
                                        className="h-10 w-auto"
                                    />
                                 </Link>
                            </div>

                            <div className="hidden sm:ml-10 sm:flex sm:space-x-8">
                                <NavLink
                                    href={route('dashboard.index')}
                                    active={route().current('dashboard.index')}
                                >
                                   Dashboard
                                </NavLink> 

                                <div className="relative">
                                    <button
                                        onClick={() => setOpenDropdown(openDropdown === 'courses' ? null : 'courses')}
                                        className={`inline-flex items-center px-1 pt-1 text-sm font-medium ${
                                            route().current('dashboard.courses.index') || route().current('igrcfp.*')
                                                ? 'border-b-2 border-indigo-400 text-gray-900'
                                                : 'border-b-2 border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                        }`}
                                    >
                                        🎓 Certifications & Trainings
                                        <ChevronDownIcon className="ml-1 h-4 w-4" />
                                    </button>
                                    
                                    {openDropdown === 'courses' && (
                                        <div className="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                            <div className="py-1">
                                                <Link 
                                                    href={route('dashboard.courses.index')} 
                                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    onClick={() => setOpenDropdown(null)}
                                                >
                                                    L1: Certificate
                                                </Link>
                                                <Link 
                                                    href={route('dashboard.diploma.index')} 
                                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    onClick={() => setOpenDropdown(null)}
                                                >
                                                    L2: Diploma
                                                </Link>
                                                <Link 
                                                    href={route('dashboard.advanced-diploma.index')} 
                                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    onClick={() => setOpenDropdown(null)}
                                                >
                                                    L3: Advanced Diploma
                                                </Link>
                                                <Link 
                                                    href={route('dashboard.certified-grc-financial-crime-specialist.index')} 
                                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    onClick={() => setOpenDropdown(null)}
                                                >
                                                    L4: Certified GRC Specialist
                                                </Link>
                                                <Link 
                                                    href={route('dashboard.postgraduate-diploma.index')} 
                                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    onClick={() => setOpenDropdown(null)}
                                                >
                                                    L5: Postgraduate Diploma
                                                </Link>
                                                <Link 
                                                    href={route('dashboard.fellowship.index')} 
                                                    className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                    onClick={() => setOpenDropdown(null)}
                                                >
                                                    L6: Fellowship
                                                </Link>
                                            </div>
                                        </div>
                                    )}
                                </div>
                                <NavLink
                                    href={route('dashboard.my-courses')}
                                    active={route().current('dashboard.my-courses')}
                                >
                                    My Learning
                                </NavLink> 
                                <NavLink
                                    href={route('dashboard.memebership')}
                                    active={route().current('dashboard.memebership')}
                                >
                                    Membership
                                </NavLink> 
                            </div>
                        </div>

                        {/* Right side icons and user dropdown */}
                        <div className="flex items-center space-x-4">
                            {/* ✅ Notification Icon with Dropdown */}
                            <div className="relative">
                                <button
                                    onClick={() => setShowNotificationDropdown(!showNotificationDropdown)}
                                    className="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 transition relative"
                                >
                                    <BellIcon className="h-6 w-6" /> 
                                    {notificationCount > 0 && (
                                        <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                                            {notificationCount > 9 ? '9+' : notificationCount}
                                        </span>
                                    )}
                                </button>
                                
                                {/* ✅ Notification Dropdown */}
                                {showNotificationDropdown && (
                                    <div className="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                        <div className="p-3 border-b border-gray-100 flex items-center justify-between">
                                            <h3 className="font-semibold text-gray-900">Notifications</h3>
                                            <Link 
                                                href={route('dashboard.notifications.index')}
                                                className="text-xs text-blue-600 hover:text-blue-800"
                                                onClick={() => setShowNotificationDropdown(false)}
                                            >
                                                View All
                                            </Link>
                                        </div>
                                        <div className="max-h-96 overflow-y-auto">
                                            {notifications.length > 0 ? (
                                                notifications.map((notification) => (
                                                    <div 
                                                        key={notification.id}
                                                        className={`p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition ${
                                                            !notification.is_read ? 'bg-blue-50' : ''
                                                        }`}
                                                        onClick={() => {
                                                            markAsRead(notification.id);
                                                            if (notification.link) {
                                                                window.location.href = notification.link;
                                                            }
                                                            setShowNotificationDropdown(false);
                                                        }}
                                                    >
                                                        <div className="flex items-start gap-3">
                                                            <span className="text-xl">{notification.icon}</span>
                                                            <div className="flex-1">
                                                                <p className="text-sm font-medium text-gray-900">
                                                                    {notification.title}
                                                                </p>
                                                                <p className="text-xs text-gray-600 line-clamp-2">
                                                                    {notification.message}
                                                                </p>
                                                                <p className="text-xs text-gray-400 mt-1">
                                                                    {notification.time_ago}
                                                                </p>
                                                            </div>
                                                            {!notification.is_read && (
                                                                <span className="w-2 h-2 bg-blue-600 rounded-full"></span>
                                                            )}
                                                        </div>
                                                    </div>
                                                ))
                                            ) : (
                                                <div className="p-6 text-center text-gray-500">
                                                    <BellIcon className="h-8 w-8 mx-auto mb-2 text-gray-300" />
                                                    <p className="text-sm">No notifications yet</p>
                                                </div>
                                            )}
                                        </div>
                                        {notifications.length > 0 && (
                                            <div className="p-2 border-t border-gray-100">
                                                <button
                                                    onClick={async () => {
                                                        await fetch(route('dashboard.notifications.mark-all-read'), {
                                                            method: 'POST',
                                                            headers: {
                                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                                            },
                                                        });
                                                        fetchNotificationCount();
                                                        fetchRecentNotifications();
                                                    }}
                                                    className="w-full text-center text-xs text-blue-600 hover:text-blue-800 py-1"
                                                >
                                                    Mark all as read
                                                </button>
                                            </div>
                                        )}
                                    </div>
                                )}
                            </div> 

                            {/* Cart Icon */}
                            <div className="relative">
                                <Link
                                    href={route('dashboard.cart.index')}
                                    className="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 transition relative"
                                >
                                    <ShoppingCartIcon className="h-6 w-6" />
                                    {cartCount > 0 && (
                                        <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-xs font-bold shadow-sm">
                                            {cartCount > 99 ? '99+' : cartCount}
                                        </span>
                                    )}
                                </Link>
                            </div>

                            {/* User Dropdown */}
                            <div className="relative ml-3">
                                <Dropdown>
                                    <Dropdown.Trigger>
                                        <div className="flex items-center space-x-2 cursor-pointer p-2 rounded-lg hover:bg-gray-100 transition">
                                            <div className="flex items-center space-x-3">
                                                <div className="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    {user.avatar ? (
                                                        <img 
                                                            src={user.avatar} 
                                                            alt={user.name}
                                                            className="h-8 w-8 rounded-full"
                                                        />
                                                    ) : (
                                                        <UserCircleIcon className="h-8 w-8 text-blue-600" />
                                                    )}
                                                </div>
                                                <div className="hidden md:block text-left">
                                                    <p className="text-sm font-medium text-gray-900">{user.name}</p>
                                                    <p className="text-xs text-gray-500">{user.email}</p>
                                                </div>
                                                <ChevronDownIcon className="h-5 w-5 text-gray-500" />
                                            </div>
                                        </div>
                                    </Dropdown.Trigger>

                                    <Dropdown.Content align="right" width="48">
                                        <div className="px-4 py-2 border-b border-gray-100">
                                            <p className="text-sm font-medium text-gray-900">{user.name}</p>
                                            <p className="text-xs text-gray-500 truncate">{user.email}</p>
                                        </div>
                                        <Dropdown.Link href={route('dashboard.my-courses')} className="flex items-center">
                                            <svg className="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            My Learning Overview
                                        </Dropdown.Link>
                                        <Dropdown.Link href={route('dashboard.my-courses')} className="flex items-center">
                                            <svg className="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            My Learning 
                                        </Dropdown.Link>
                                        
                                        <Dropdown.Link href={route('profile.edit')} className="flex items-center">
                                            <UserCircleIcon className="h-4 w-4 mr-2" />
                                            Profile
                                        </Dropdown.Link>
                                        <div className="border-t border-gray-100"></div>
                                        <Dropdown.Link
                                            href={route('logout')}
                                            method="post"
                                            as="button"
                                            className="flex items-center text-red-600 hover:text-red-700"
                                        >
                                            <ArrowRightOnRectangleIcon className="h-4 w-4 mr-2" />
                                            Log Out
                                        </Dropdown.Link>
                                    </Dropdown.Content>
                                </Dropdown>
                            </div>
                        </div>

                        {/* Mobile menu button */}
                        <div className="flex items-center sm:hidden">
                            <button
                                onClick={() => setShowingNavigationDropdown(!showingNavigationDropdown)}
                                className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none"
                            >
                                <span className="sr-only">Open main menu</span>
                                {!showingNavigationDropdown ? (
                                    <svg className="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                ) : (
                                    <svg className="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                )}
                            </button>
                        </div>
                    </div>
                </div>

                {/* Mobile menu */}
                <div className={`${showingNavigationDropdown ? 'block' : 'hidden'} sm:hidden`}>
                    <div className="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink href={route('dashboard.index')} active={route().current('dashboard.index')}>
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={route('dashboard.courses.index')} active={route().current('dashboard.courses.index')}>
                            Courses
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={route('dashboard.my-courses')} active={route().current('dashboard.my-courses')}>
                            My Learning
                        </ResponsiveNavLink>
                    </div>
                    
                    {/* Mobile notification and cart */}
                    <div className="pt-4 pb-3 border-t border-gray-200">
                        <div className="flex items-center px-4 space-x-4">
                            <Link href={route('dashboard.notifications.index')} className="relative p-2 text-gray-500 hover:text-gray-700">
                                <BellIcon className="h-6 w-6" />
                                {notificationCount > 0 && (
                                    <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                                        {notificationCount > 9 ? '9+' : notificationCount}
                                    </span>
                                )}
                            </Link>
                            <Link href={route('dashboard.cart.index')} className="relative p-2 text-gray-500 hover:text-gray-700">
                                <ShoppingCartIcon className="h-6 w-6" />
                                {cartCount > 0 && (
                                    <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-xs font-bold">
                                        {cartCount > 99 ? '99+' : cartCount}
                                    </span>
                                )}
                            </Link>
                        </div>
                    </div>
                    
                    <div className="pt-4 pb-3 border-t border-gray-200">
                        <div className="px-4">
                            <div className="text-base font-medium text-gray-800">{user.name}</div>
                            <div className="text-sm font-medium text-gray-500">{user.email}</div>
                        </div>
                        <div className="mt-3 space-y-1">
                            <ResponsiveNavLink href={route('dashboard.my-courses')} >
                                My Learning Overview
                            </ResponsiveNavLink>
                            <ResponsiveNavLink href={route('profile.edit')}>
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                method="post"
                                href={route('logout')}
                                as="button"
                                className="text-red-600 hover:text-red-700"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>
 
            {header && (
                <header className="bg-white shadow">
                    <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {header}
                    </div>
                </header>
            )} 

            <main className="py-8">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    {children}
                </div>
            </main>
            <AuthenticatedFooter/>
            
            {/* ✅ Close notification dropdown when clicking outside */}
            {showNotificationDropdown && (
                <div 
                    className="fixed inset-0 z-40" 
                    onClick={() => setShowNotificationDropdown(false)}
                />
            )}
        </div>
    );
}