import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { 
    BellIcon, 
    CheckCircleIcon,
    TrashIcon,
    AcademicCapIcon,
    TrophyIcon,
    ClockIcon,
    DocumentTextIcon, 
    XCircleIcon
} from '@heroicons/react/24/outline';
import toast from 'react-hot-toast';

export default function NotificationsIndex({ notifications, unread_count, filters, types }) {
    const [selectedType, setSelectedType] = useState(filters.type || '');
    const [selectedStatus, setSelectedStatus] = useState(filters.status || '');
    
    const getNotificationIcon = (type) => {
        const icons = {
            'certificate_generated': '🎓',
            'quiz_passed': '✅',
            'quiz_failed': '❌',
            'project_submitted': '📤',
            'project_passed': '🎉',
            'project_graded': '📊',
            'course_completed': '🏆',
            'module_completed': '📚',
            'assessment_due': '⏰',
            'all_quizzes_completed': '🎯',
            'all_lessons_completed': '📖',
            'progress_milestone': '📈',
            'project_eligible': '🔓',
        };
        return icons[type] || '📌';
    };
    
    const getNotificationColor = (type) => {
        const colors = {
            'certificate_generated': 'bg-purple-100 text-purple-800 border-purple-200',
            'quiz_passed': 'bg-green-100 text-green-800 border-green-200',
            'quiz_failed': 'bg-red-100 text-red-800 border-red-200',
            'project_submitted': 'bg-blue-100 text-blue-800 border-blue-200',
            'project_passed': 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'project_graded': 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'course_completed': 'bg-amber-100 text-amber-800 border-amber-200',
            'module_completed': 'bg-teal-100 text-teal-800 border-teal-200',
            'assessment_due': 'bg-orange-100 text-orange-800 border-orange-200',
        };
        return colors[type] || 'bg-gray-100 text-gray-800 border-gray-200';
    };
    
    const handleFilter = () => {
        const params = new URLSearchParams();
        if (selectedType) params.set('type', selectedType);
        if (selectedStatus) params.set('status', selectedStatus);
        router.visit(route('dashboard.notifications.index') + '?' + params.toString());
    };
    
    const handleNotificationClick = (notification) => {
    // Mark as read when clicking
    if (!notification.is_read) {
        markAsRead(notification.id, false);
    }
    
    // Handle PDF files - force download
    if (notification.link && notification.link.toLowerCase().endsWith('.pdf')) {
        // Create a temporary anchor to force download
        const link = document.createElement('a');
        link.href = notification.link;
        link.download = notification.title.replace(/[^a-z0-9]/gi, '_') + '.pdf';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } else if (notification.link) {
        // Handle regular Inertia navigation
        router.visit(notification.link);
    }
};
    
    const markAsRead = async (notificationId, showToast = true) => {
        try {
            await fetch(route('dashboard.notifications.mark-read', notificationId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });
            router.reload({ only: ['notifications', 'unread_count'] });
            if (showToast) {
                toast.success('Notification marked as read');
            }
        } catch (error) {
            if (showToast) {
                toast.error('Failed to mark as read');
            }
        }
    };
    
    const markAllAsRead = async () => {
        try {
            await fetch(route('dashboard.notifications.mark-all-read'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });
            router.reload({ only: ['notifications', 'unread_count'] });
            toast.success('All notifications marked as read');
        } catch (error) {
            toast.error('Failed to mark all as read');
        }
    };
    
    const deleteNotification = async (notificationId) => {
        if (!confirm('Delete this notification?')) return;
        
        try {
            await fetch(route('dashboard.notifications.destroy', notificationId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });
            router.reload({ only: ['notifications', 'unread_count'] });
            toast.success('Notification deleted');
        } catch (error) {
            toast.error('Failed to delete notification');
        }
    };
    
    return (
        <AuthenticatedLayout>
            <Head title="Notifications" />
            
            <div className="py-8">
                <div className="max-w-4xl mx-auto px-4">
                    {/* Header */}
                    <div className="mb-6 flex items-center justify-between">
                        <div>
                            <h1 className="text-2xl font-bold text-gray-900">Notifications</h1>
                            <p className="text-gray-600">
                                {unread_count} unread notification{unread_count !== 1 ? 's' : ''}
                            </p>
                        </div>
                        {unread_count > 0 && (
                            <button
                                onClick={markAllAsRead}
                                className="text-sm text-blue-600 hover:text-blue-800"
                            >
                                Mark all as read
                            </button>
                        )}
                    </div>
                    
                    {/* Filters */}
                    <div className="bg-white rounded-xl shadow-sm p-4 mb-6">
                        <div className="flex gap-3">
                            <select
                                value={selectedType}
                                onChange={(e) => setSelectedType(e.target.value)}
                                className="border-gray-300 rounded-lg text-sm"
                            >
                                {types.map((type) => (
                                    <option key={type.value} value={type.value}>
                                        {type.label}
                                    </option>
                                ))}
                            </select>
                            
                            <select
                                value={selectedStatus}
                                onChange={(e) => setSelectedStatus(e.target.value)}
                                className="border-gray-300 rounded-lg text-sm"
                            >
                                <option value="">All Status</option>
                                <option value="unread">Unread</option>
                                <option value="read">Read</option>
                            </select>
                            
                            <button
                                onClick={handleFilter}
                                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm"
                            >
                                Apply Filters
                            </button>
                            
                            {(selectedType || selectedStatus) && (
                                <button
                                    onClick={() => {
                                        setSelectedType('');
                                        setSelectedStatus('');
                                        router.visit(route('dashboard.notifications.index'));
                                    }}
                                    className="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm"
                                >
                                    Clear
                                </button>
                            )}
                        </div>
                    </div>
                    
                    {/* Notifications List */}
                    <div className="space-y-3">
                        {notifications.data.length > 0 ? (
                            notifications.data.map((notification) => (
                                <div
                                    key={notification.id}
                                    className={`border rounded-xl p-4 transition ${
                                        getNotificationColor(notification.type)
                                    } ${!notification.is_read ? 'shadow-md' : ''}`}
                                >
                                    <div className="flex items-start gap-4">
                                        <span className="text-2xl">
                                            {getNotificationIcon(notification.type)}
                                        </span>
                                        
                                        <div className="flex-1">
                                            <div className="flex items-center justify-between mb-1">
                                                <h3 className="font-semibold text-gray-900">
                                                    {notification.title}
                                                </h3>
                                                <span className="text-xs text-gray-500">
                                                    {notification.time_ago}
                                                </span>
                                            </div>
                                            
                                            <p className="text-sm text-gray-700 mb-3">
                                                {notification.message}
                                            </p>
                                            
                                            <div className="flex items-center gap-3">
                                                {notification.link && (
                                                    <button
                                                        onClick={() => handleNotificationClick(notification)}
                                                        className="text-sm font-medium text-blue-600 hover:text-blue-800"
                                                    >
                                                        {notification.link.toLowerCase().endsWith('.pdf') 
                                                            ? 'View Certificate →' 
                                                            : 'View Details →'}
                                                    </button>
                                                )}
                                                
                                                {!notification.is_read && (
                                                    <button
                                                        onClick={() => markAsRead(notification.id)}
                                                        className="text-sm text-gray-500 hover:text-gray-700"
                                                    >
                                                        Mark as read
                                                    </button>
                                                )}
                                                
                                                <button
                                                    onClick={() => deleteNotification(notification.id)}
                                                    className="text-sm text-red-500 hover:text-red-700 ml-auto"
                                                >
                                                    <TrashIcon className="w-4 h-4" />
                                                </button>
                                            </div>
                                        </div>
                                        
                                        {!notification.is_read && (
                                            <span className="w-2 h-2 bg-blue-600 rounded-full"></span>
                                        )}
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="bg-white rounded-xl shadow-sm p-12 text-center">
                                <BellIcon className="w-16 h-16 text-gray-300 mx-auto mb-4" />
                                <h3 className="text-lg font-medium text-gray-900 mb-2">
                                    No notifications
                                </h3>
                                <p className="text-gray-500">
                                    You're all caught up! Check back later for updates.
                                </p>
                            </div>
                        )}
                    </div>
                    
                    {/* Pagination */}
                    {notifications.data.length > 0 && (
                        <div className="mt-6">
                            {notifications.links && (
                                <div className="flex justify-center">
                                    {notifications.links.map((link, i) => (
                                        <button
                                            key={i}
                                            onClick={() => link.url && router.visit(link.url)}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                            className={`px-3 py-1 mx-1 rounded ${
                                                link.active
                                                    ? 'bg-blue-600 text-white'
                                                    : link.url
                                                        ? 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                                        : 'text-gray-400 cursor-not-allowed'
                                            }`}
                                            disabled={!link.url}
                                        />
                                    ))}
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}