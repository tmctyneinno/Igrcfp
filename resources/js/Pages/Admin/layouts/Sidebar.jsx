import { Link, router } from "@inertiajs/react";
import { Icon } from "@iconify/react";
import React, { useState } from "react";

export default function SideBar() {
    const [openMenu, setOpenMenu] = useState(null);

    const toggleMenu = (menu) => {
        setOpenMenu(openMenu === menu ? null : menu);
    };

    const handleLogout = () => {
        router.post(route('admin.logout'));
    };

    return (
        <aside className="sidebar">
            {/* Close button (mobile) */}
            <button type="button" class="sidebar-close-btn">
                <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
            </button>

            {/* Logo */}
            <div>
                <Link href="/admin/dashboard" className="sidebar-logo">
                    <img
                        src="/assets/images/home-three/logo/logo-main.png"
                        alt="site logo"
                        className="light-logo"
                    />
                    <img
                        src="/assets/images/home-three/logo/logo-main.png"
                        alt="site logo"
                        className="dark-logo"
                    />
                    <img
                        src="/assets/images/home-three/logo/logo-main.png"
                        alt="site logo"
                        className="logo-icon"
                    />
                </Link>
            </div>

            {/* Menu */}
            <div className="sidebar-menu-area">
                <ul className="sidebar-menu" id="sidebar-menu">

                    {/* Dashboard */}
                    <li>
                        <Link href="/admin/dashboard">
                            <Icon
                                icon="solar:home-smile-angle-outline"
                                className="menu-icon"
                            />
                            <span>Dashboard</span>
                        </Link>
                    </li>

                    {/* Users */}
                    <li>
                        <Link href="/admin/users">
                            <Icon
                                icon="flowbite:users-group-outline"
                                className="menu-icon"
                            />
                            <span>Users</span>
                        </Link>
                    </li>
                   
                    
                    {/* Blogs */}
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <Icon icon="hugeicons:invoice-03" className="menu-icon" />
                            <span>Blog</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                            <a href="/admin/blogs">
                                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                            </li>
                            <li>
                            <a href="/admin/blogs/create"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Add New</a>
                            </li>
                        </ul>
                    </li>
                    

                    {/* Events */}
                    <li className='dropdown'>
                        <a href="javascript:void(0)" >
                            <Icon icon="solar:document-text-outline" className="menu-icon"/>
                            <span>Events</span>
                        </a>
                        <ul className="sidebar-submenu">
                            <li>
                                <Link href="/admin/events/index">
                                    <i className="ri-circle-fill circle-icon text-primary-600"></i>
                                    List
                                </Link>
                            </li> 
                            <li>
                                <Link href="/admin/events/create">
                                    <i className="ri-circle-fill circle-icon text-warning-main"></i>
                                    Add New
                                </Link>
                            </li>
                        </ul>
                    </li>

                    {/* Courses */}
                    <li className={`dropdown ${openMenu === "courses" ? "open" : ""}`}>
                         <a href="javascript:void(0)" >
                            <Icon
                                icon="solar:document-text-outline"
                                className="menu-icon"
                            />
                            <span>Courses</span>
                        </a>

                        <ul className="sidebar-submenu">
                            <li>
                                <Link href="/admin/courses">
                                    <i className="ri-circle-fill circle-icon text-primary-600"></i>
                                    List
                                </Link>
                            </li>
                            <li>
                                <Link href="/admin/courses/create">
                                    <i className="ri-circle-fill circle-icon text-warning-main"></i>
                                    Add New
                                </Link>
                            </li>
                        </ul>
                    </li>

                     <li>
                        <button onClick={handleLogout}>
                            <Icon
                                icon="flowbite:users-group-outline"
                                className="menu-icon"
                            />
                            <span>Logout</span>
                        </button>
                    </li>

                </ul>
            </div>
        </aside>
    );
}
