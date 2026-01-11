// resources/js/Pages/Admin/Events/Index.jsx
import AdminLayout from '@/Pages/Admin/layouts/AdminLayout';
import { Link, useForm, usePage } from '@inertiajs/react';
import { Icon } from '@iconify/react';
import { useState, useEffect } from 'react';

export default function Index({ events, filters, title }) {
    const { props } = usePage();
    const [selectedEvents, setSelectedEvents] = useState([]);
    const [selectAll, setSelectAll] = useState(false);
    
    const { data, setData, post, processing } = useForm({
        event_ids: [],
        action: ''
    });

    // Handle select all
    const handleSelectAll = (e) => {
        const checked = e.target.checked;
        setSelectAll(checked);
        if (checked) {
            const allIds = events.data.map(event => event.id);
            setSelectedEvents(allIds);
            setData('event_ids', allIds);
        } else {
            setSelectedEvents([]);
            setData('event_ids', []);
        }
    };

    // Handle individual checkbox
    const handleCheckboxChange = (eventId, isChecked) => {
        if (isChecked) {
            setSelectedEvents(prev => [...prev, eventId]);
            setData('event_ids', [...data.event_ids, eventId]);
        } else {
            setSelectedEvents(prev => prev.filter(id => id !== eventId));
            setData('event_ids', data.event_ids.filter(id => id !== eventId));
        }
    };

    // Handle bulk action
    const handleBulkAction = (e) => {
        e.preventDefault();
        if (selectedEvents.length === 0) {
            alert('Please select at least one event.');
            return;
        }
        if (!data.action) {
            alert('Please select an action.');
            return;
        }
        
        post(route('admin.events.bulk-action'));
    };

    // Update checkboxes when selectedEvents changes
    useEffect(() => {
        if (events.data.length > 0 && selectedEvents.length === events.data.length) {
            setSelectAll(true);
        } else {
            setSelectAll(false);
        }
    }, [selectedEvents, events.data]);

    return (
        <AdminLayout 
            title={title || "Event Management"} 
            adminName={props.auth?.user?.name || "Admin"}
        >
            <div className="dashboard-main-body">
                <div className="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                    <h6 className="fw-semibold mb-0">Event Management</h6>
                    <ul className="d-flex align-items-center gap-2">
                        <li className="fw-medium">
                            <Link 
                                href={route('admin.dashboard')} 
                                className="d-flex align-items-center gap-1 hover-text-primary text-decoration-none"
                            >
                                <Icon icon="solar:home-smile-angle-outline" className="icon text-lg" />
                                Dashboard
                            </Link>
                        </li>
                        <li>-</li>
                        <li className="fw-medium">Events</li>
                    </ul>
                </div>

                {/* Success/Error Messages */}
                {props.flash?.success && (
                    <div className="alert alert-success alert-dismissible fade show" role="alert">
                        {props.flash?.success}
                        <button type="button" className="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                )}

                {props.flash?.error && (
                    <div className="alert alert-danger alert-dismissible fade show" role="alert">
                        {props.flash?.error}
                        <button type="button" className="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                )}

                <div className="card h-100 p-0 radius-12">
                    <div className="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                        <div className="d-flex align-items-center flex-wrap gap-3">
                            <span className="text-md fw-medium text-secondary-light mb-0">Show</span>
                            <form method="GET" className="d-inline">
                                <select 
                                    name="per_page" 
                                    className="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px" 
                                    defaultValue={filters.per_page}
                                    onChange={(e) => e.target.form.submit()}
                                >
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                            </form>
                            
                            <form className="navbar-search" method="GET">
                                <input 
                                    type="text" 
                                    className="bg-base h-40-px w-auto" 
                                    name="search" 
                                    placeholder="Search events..." 
                                    defaultValue={filters.search}
                                />
                                <Icon icon="ion:search-outline" className="icon" />
                            </form>
                            
                            <form method="GET" className="d-inline d-flex">
                                <select 
                                    name="status" 
                                    className="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px"
                                    defaultValue={filters.status}
                                    onChange={(e) => e.target.form.submit()}
                                >
                                    <option value="">All Status</option>
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                {(filters.search || filters.status || filters.per_page != 10) && (
                                    <Link 
                                        href={route('admin.events.index')} 
                                        className="btn btn-sm btn-outline-secondary ms-2"
                                    >
                                        Clear
                                    </Link>
                                )}
                            </form>
                        </div>
                        <Link 
                            href={route('admin.events.create')} 
                            className="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
                        >
                            <Icon icon="ic:baseline-plus" className="icon text-xl line-height-1" />
                            Add New Event
                        </Link>
                    </div>

                    <form onSubmit={handleBulkAction}>
                        <input type="hidden" name="_token" value={props.csrf_token} />
                        <div className="card-body p-24">
                            <div className="d-flex align-items-center gap-3 mb-3">
                                <select 
                                    name="action" 
                                    className="form-select form-select-sm w-auto"
                                    value={data.action}
                                    onChange={(e) => setData('action', e.target.value)}
                                    required
                                >
                                    <option value="">Bulk Actions</option>
                                    <option value="publish">Publish</option>
                                    <option value="draft">Move to Draft</option>
                                    <option value="cancel">Cancel</option>
                                    <option value="delete">Delete</option>
                                </select>
                                <button 
                                    type="submit" 
                                    className="btn btn-sm btn-outline-primary"
                                    disabled={processing}
                                >
                                    {processing ? 'Processing...' : 'Apply'}
                                </button>
                            </div>

                            <div className="table-responsive scroll-sm">
                                <table className="table bordered-table sm-table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="50">
                                                <div className="d-flex align-items-center gap-10">
                                                    <div className="form-check style-check d-flex align-items-center">
                                                        <input 
                                                            className="form-check-input radius-4 border input-form-dark" 
                                                            type="checkbox" 
                                                            id="selectAll"
                                                            checked={selectAll}
                                                            onChange={handleSelectAll}
                                                        />
                                                    </div>
                                                    S.L
                                                </div>
                                            </th>
                                            <th scope="col">Event Image</th>
                                            <th scope="col">Event Title</th>
                                            <th scope="col">Date & Time</th>
                                            <th scope="col">Location</th>
                                            <th scope="col" className="text-center">Price</th>
                                            <th scope="col" className="text-center">Seats</th>
                                            <th scope="col" className="text-center">Status</th>
                                            <th scope="col" className="text-center">Featured</th>
                                            <th scope="col" className="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {events.data.length > 0 ? (
                                            events.data.map((event, index) => (
                                                <tr key={event.id}>
                                                    <td>
                                                        <div className="d-flex align-items-center gap-10">
                                                            <div className="form-check style-check d-flex align-items-center">
                                                                <input 
                                                                    className="form-check-input radius-4 border border-neutral-400 event-checkbox" 
                                                                    type="checkbox" 
                                                                    name="event_ids[]" 
                                                                    value={event.id}
                                                                    checked={selectedEvents.includes(event.id)}
                                                                    onChange={(e) => handleCheckboxChange(event.id, e.target.checked)}
                                                                />
                                                            </div>
                                                            {index + 1 + (events.current_page - 1) * events.per_page}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="featured-image-container"> 
                                                            <img 
                                                                src={event.image ? `/storage/${event.image}` : '/images/default-event.jpg'} 
                                                                alt={event.title}
                                                                style={{ maxHeight: '20px' }}
                                                                className="featured-image rounded-8"
                                                                onError={(e) => e.target.src = '/images/default-event.jpg'}
                                                            />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="d-flex flex-column">
                                                            <span className="text-md fw-medium text-secondary-light mb-1">
                                                                {event.title.length > 40 ? `${event.title.substring(0, 40)}...` : event.title}
                                                            </span>
                                                            <small className="text-muted">
                                                                {event.short_description && event.short_description.length > 60 
                                                                    ? `${event.short_description.substring(0, 60)}...`
                                                                    : event.short_description}
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div className="d-flex flex-column">
                                                            <small className="fw-medium">{event.event_date}</small>
                                                            <small className="text-muted">{event.event_time}</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span className="text-sm fw-normal text-secondary-light">
                                                            {event.location && event.location.length > 30 
                                                                ? `${event.location.substring(0, 30)}...`
                                                                : event.location}
                                                        </span>
                                                    </td>
                                                    <td className="text-center">
                                                        {event.price > 0 ? (
                                                            <span className="badge bg-success">${Number(event.price).toFixed(2)}</span>
                                                        ) : (
                                                            <span className="badge bg-info">Free</span>
                                                        )}
                                                    </td>
                                                    <td className="text-center">
                                                        <span className={`badge bg-${event.registration_status === 'sold_out' ? 'danger' : (event.registration_status === 'few_seats' ? 'warning' : 'primary')}`}>
                                                            {event.available_seats}/{event.capacity}
                                                        </span>
                                                    </td>
                                                    <td className="text-center">
                                                        <span className={`badge bg-${event.status === 'published' ? 'success' : (event.status === 'cancelled' ? 'danger' : 'warning')}`}>
                                                            {event.status.charAt(0).toUpperCase() + event.status.slice(1)}
                                                        </span>
                                                    </td>
                                                    <td className="text-center">
                                                        {event.is_featured ? (
                                                            <Icon icon="mdi:star" className="icon text-warning" />
                                                        ) : (
                                                            <Icon icon="mdi:star-outline" className="icon text-muted" />
                                                        )}
                                                    </td>
                                                    <td className="text-center"> 
                                                        <div className="d-flex align-items-center gap-10 justify-content-center">
                                                            <Link 
                                                                href={route('admin.events.show', event.id)}
                                                                className="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none"
                                                                title="View"
                                                            >
                                                                <Icon icon="majesticons:eye-line" className="icon text-xl" />
                                                            </Link>
                                                            <Link 
                                                                href={route('admin.events.edit', event.id)}
                                                                className="bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle text-decoration-none"
                                                                title="Edit"
                                                            >
                                                                <Icon icon="lucide:edit" className="menu-icon" />
                                                            </Link>
                                                            
                                                            {/* Toggle Featured Form */}
                                                            <form 
                                                                action={route('admin.events.toggle-featured', event.id)} 
                                                                method="POST" 
                                                                className="d-inline"
                                                                onSubmit={(e) => {
                                                                    e.preventDefault();
                                                                    // Implement toggle featured functionality
                                                                }}
                                                            >
                                                                <button 
                                                                    type="submit"
                                                                    className={`bg-${event.is_featured ? 'warning' : 'secondary'}-focus bg-hover-${event.is_featured ? 'warning' : 'secondary'}-200 text-${event.is_featured ? 'warning' : 'secondary'}-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0`}
                                                                    title={event.is_featured ? 'Remove Featured' : 'Mark as Featured'}
                                                                >
                                                                    <Icon icon="mdi:star" className="menu-icon" />
                                                                </button>
                                                            </form>
                                                            
                                                            {/* Delete Form */}
                                                            <form 
                                                                action={route('admin.events.destroy', event.id)} 
                                                                method="POST" 
                                                                className="d-inline"
                                                                onSubmit={(e) => {
                                                                    if (!confirm('Are you sure you want to delete this event?')) {
                                                                        e.preventDefault();
                                                                    }
                                                                }}
                                                            >
                                                                <button 
                                                                    type="submit"
                                                                    className="bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle border-0"
                                                                    title="Delete"
                                                                >
                                                                    <Icon icon="fluent:delete-24-regular" className="menu-icon" />
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr>
                                                <td colSpan="10" className="text-center py-4">
                                                    <div className="text-muted">
                                                        <Icon icon="mdi:calendar-blank-outline" className="icon-3x mb-2" />
                                                        <p>No events found.</p>
                                                        {(filters.search || filters.status) ? (
                                                            <Link href={route('admin.events.index')} className="btn btn-sm btn-primary">
                                                                Clear Filters
                                                            </Link>
                                                        ) : (
                                                            <Link href={route('admin.events.create')} className="btn btn-sm btn-primary">
                                                                Create Your First Event
                                                            </Link>
                                                        )}
                                                    </div>
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                            </div>

                            {events.data.length > 0 && (
                                <div className="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                                    <span>
                                        Showing {events.from} to {events.to} of {events.total} entries
                                    </span>
                                    <nav>
                                        <ul className="pagination mb-0">
                                            {events.links.map((link, index) => (
                                                <li 
                                                    key={index}
                                                    className={`page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}`}
                                                >
                                                    <Link 
                                                        href={link.url || '#'}
                                                        className="page-link"
                                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                                    />
                                                </li>
                                            ))}
                                        </ul>
                                    </nav>
                                </div>
                            )}
                        </div>
                    </form>
                </div>
            </div>

            <style jsx>{`
                .featured-image-container {
                    width: 60px;
                    height: 40px;
                    border-radius: 8px;
                    overflow: hidden;
                }
                .featured-image {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
            `}</style>
        </AdminLayout>
    );
}