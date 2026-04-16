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
    const [notificationCount, setNotificationCount] = useState(props.notification_count || 0);
    
    const cartCount = props.cart_count || 0;

    
 
    // Check for enrollment redirect on dashboard load
    useEffect(() => {
        setNotificationCount(props.notification_count || 0);
    }, [props.notification_count]);

    useEffect(() => {
        const enrollmentRedirect = sessionStorage.getItem('enrollment_redirect');
        const intendedUrl = sessionStorage.getItem('intended_url');
        
        if (enrollmentRedirect && intendedUrl) {
            // Clear the storage
            sessionStorage.removeItem('enrollment_redirect');
            sessionStorage.removeItem('intended_url');
            
            // Redirect to enrollment page
            setTimeout(() => {
                router.visit(enrollmentRedirect);
            }, 100);
        }
        
        const pollNotificationCount = async () => {
            try {
                const response = await window.axios.get(route('notifications.count'));
                setNotificationCount(response?.data?.unread_count ?? 0);
            } catch (error) {
                // Keep existing count if polling fails.
            }
        };

        pollNotificationCount();
        const interval = setInterval(pollNotificationCount, 15000);

        return () => clearInterval(interval);
    }, []);

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Add Toaster component here - it should be at the root level */}
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
                                <NavLink
                                    href={route('dashboard.courses.index')}
                                    active={route().current('dashboard.courses.index')}
                                > 
                                    Courses
                                </NavLink>  
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
                            {/* Notification Icon */}
                            <div className="relative">
                                <Link
                                    href={route('notifications.index')}
                                    className="p-2 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 transition"
                                >
                                    <BellIcon className="h-6 w-6" /> 
                                    {notificationCount > 0 && (
                                        <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                                            {notificationCount > 9 ? '9+' : notificationCount}
                                        </span>
                                    )}
                                </Link>
                            </div>

                            {/* Cart Icon with Dynamic Count from Backend */}
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
                                        
                                        <Dropdown.Link href={route('profile.edit')} className="flex items-center">
                                            <UserCircleIcon className="h-4 w-4 mr-2" />
                                            Profile
                                        </Dropdown.Link>
                                        <Dropdown.Link href={route('settings')} className="flex items-center">
                                            <CogIcon className="h-4 w-4 mr-2" />
                                            Settings
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
                        <ResponsiveNavLink href={route('courses.index')} active={route().current('courses.index')}>
                            Courses
                        </ResponsiveNavLink>
                        <ResponsiveNavLink href={route('dashboard.my-courses')} active={route().current('dashboard.my-courses')}>
                            My Learning
                        </ResponsiveNavLink>
                    </div>
                    
                    {/* Mobile notification and cart with counts */}
                    <div className="pt-4 pb-3 border-t border-gray-200">
                        <div className="flex items-center px-4 space-x-4">
                            <Link href={route('notifications.index')} className="relative p-2 text-gray-500 hover:text-gray-700">
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
                            <ResponsiveNavLink href={route('profile.edit')}>
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink href={route('settings')}>
                                Settings
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
        </div>
    );
}
