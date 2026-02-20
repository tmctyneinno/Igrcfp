import React from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from "@/Pages/Admin/layouts/AdminLayout";

export default function Dashboard({ stats, auth }) {
    return (
        <>
           <AdminLayout title="Admin Dashboard" adminName={stats.admin_name}>
            <div className="admin-dashboard">
                {/* Your dashboard content */}
                <h1 className="text-3xl font-bold mb-6">Welcome back, {stats.admin_name}!</h1>
                <h1 className="text-3xl font-bold">Dashboard</h1>
                {/* ... */}
            </div>
            </AdminLayout>
        </> 
    ); 
}