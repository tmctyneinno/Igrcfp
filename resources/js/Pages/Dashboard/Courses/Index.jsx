import React, { useState, useEffect } from 'react';
import { Head, Link, usePage, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { useCart } from '@/contexts/CartContext'; 
import toast from 'react-hot-toast';
import DashboardCourseCard from '@/components/Courses/DashboardCourseCard';
import FilterSidebar from '@/components/Courses/FilterSidebar';
import SearchBar from '@/components/Courses/SearchBar';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
   
export default function Courses({ auth, courses, filters, filterOptions }) {
    const { addToCart, cartItems, refreshCart } = useCart();
    const { props } = usePage();
    const [addingToCart, setAddingToCart] = useState({});
    const [localCartItems, setLocalCartItems] = useState(cartItems || []);

    const { url } = usePage();
    const [showFilters, setShowFilters] = useState(true);
    const [selectedFilters, setSelectedFilters] = useState(filters);
    const [mobileFiltersOpen, setMobileFiltersOpen] = useState(false);

    // Sync cart items
    useEffect(() => {
        setLocalCartItems(cartItems || []);
    }, [cartItems]);

    useEffect(() => {
        if (props.cart?.items) {
            setLocalCartItems(props.cart.items);
        }
    }, [props.cart]);

    // Show flash messages
    useEffect(() => {
        if (props.flash?.info) {
            toast(props.flash.info, { icon: 'ℹ️', style: { background: '#3b82f6', color: '#fff' } });
        }
        if (props.flash?.error) {
            toast.error(props.flash.error, { style: { background: '#ef4444', color: '#fff' } });
        }
    }, [props.flash]);

    useEffect(() => {
        setSelectedFilters(filters);
    }, [filters]);

    const handleFilterChange = (key, value) => {
        const newFilters = { ...selectedFilters, [key]: value, page: 1 };
        setSelectedFilters(newFilters);
        
        if (key === 'search') {
            const timeout = setTimeout(() => {
                router.get(url.split('?')[0], newFilters, { preserveState: true, replace: true });
            }, 500);
            return () => clearTimeout(timeout);
        }
        
        router.get(url.split('?')[0], newFilters, { preserveState: true, replace: true });
    };

    const handleSortChange = (e) => {
        const [sort_field, sort_direction] = e.target.value.split('_');
        handleFilterChange('sort_field', sort_field);
        handleFilterChange('sort_direction', sort_direction);
    };

    const resetFilters = () => {
        router.get(url.split('?')[0], {}, { preserveState: true, replace: true });
        toast.success('Filters cleared', { icon: '🧹', duration: 2000 });
    };

    const isInCart = (courseId) => {
        return localCartItems?.some(item => {
            if (item.course) return item.course.id === courseId;
            return item.id === courseId;
        }) || props.cart?.items?.some(item => {
            if (item.course) return item.course.id === courseId;
            return item.id === courseId;
        });
    };

    // --- NEW: Handle Direct Scholarship Enrollment ---
    const handleScholarshipEnroll = async (course) => {
        setAddingToCart(prev => ({ ...prev, [course.id]: true }));
        
        try {
            router.post(route('courses.enroll', course.slug), {}, {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Scholarship activated! You are now enrolled.', {
                        icon: '🎓',
                        style: { background: '#10b981', color: '#fff' }
                    });
                    window.location.reload(); // Reload to update UI state
                },
                onError: (errors) => {
                    console.error("Enrollment failed", errors);
                    toast.error('Failed to activate scholarship.', {
                        style: { background: '#ef4444', color: '#fff' }
                    });
                },
                onFinish: () => {
                    setAddingToCart(prev => ({ ...prev, [course.id]: false }));
                }
            }); 
        } catch (error) {
            console.error('Error:', error);
            setAddingToCart(prev => ({ ...prev, [course.id]: false }));
        }
    };
 
    // Handle Add to Cart (Regular Users)
    const handleAddToCart = async (course) => {
        if (!auth.user) {
            toast.error('Please login to add courses to cart', { duration: 4000, icon: '🔒', style: { background: '#ef4444', color: '#fff' } });
            setTimeout(() => router.visit(route('login')), 1500);
            return;
        }

        if (isInCart(course.id)) {
            toast('Course is already in your cart!', { icon: '🛒', style: { background: '#3b82f6', color: '#fff' } });
            return;
        }

        setAddingToCart(prev => ({ ...prev, [course.id]: true }));
        
        try {
            const success = await addToCart(course);
            if (success) {
                await refreshCart();
                if (props.cart?.items) setLocalCartItems(props.cart.items);
            } else {
                toast.error('Failed to add course to cart.', { duration: 4000, icon: '❌', style: { background: '#ef4444', color: '#fff' } });
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            toast.error('Failed to add course to cart.', { duration: 4000, icon: '❌', style: { background: '#ef4444', color: '#fff' } });
        } finally {
            setAddingToCart(prev => ({ ...prev, [course.id]: false }));
        }
    };

    const getActiveFilterCount = () => {
        return Object.entries(selectedFilters).filter(([key, value]) => 
            value && value !== '' && value !== false && key !== 'sort_field' && key !== 'sort_direction' && key !== 'page'
        ).length;
    };

    return (
        <AuthenticatedLayout auth={auth}> 
            <Head title='IGRCFP | Courses' />
            <div className="min-h-screen bg-gray-50">
                <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    {/* Search and Filter Bar */}
                    <div className="mb-8">
                        <div className="flex flex-col lg:flex-row gap-4 items-center justify-between">
                            <div className="w-full lg:w-96">
                                <SearchBar
                                    value={selectedFilters.search || ''}
                                    onChange={(value) => handleFilterChange('search', value)}
                                    placeholder="Search courses by title, description, or tags..."
                                />
                            </div>

                            <div className="flex items-center gap-4 w-full lg:w-auto">
                                <select
                                    value={`${selectedFilters.sort_field || 'created_at'}_${selectedFilters.sort_direction || 'desc'}`}
                                    onChange={handleSortChange}
                                    className="flex-1 lg:flex-none px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                                >
                                    {filterOptions?.sortOptions?.map((option) => (
                                        <option key={option.value} value={option.value}>{option.label}</option>
                                    ))}
                                </select>

                                <button onClick={() => setMobileFiltersOpen(true)} className="lg:hidden flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                                    <span>Filters</span>
                                    {getActiveFilterCount() > 0 && (<span className="bg-blue-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{getActiveFilterCount()}</span>)}
                                </button>

                                <button onClick={() => setShowFilters(!showFilters)} className="hidden lg:flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                                    <span>{showFilters ? 'Hide Filters' : 'Show Filters'}</span>
                                </button>

                                {getActiveFilterCount() > 0 && (
                                    <button onClick={resetFilters} className="text-sm text-gray-600 hover:text-blue-600 transition">Clear All</button>
                                )}
                            </div>
                        </div>
                    </div>

                    <AnimatePresence>
                        {mobileFiltersOpen && (
                            <FilterSidebar
                                isOpen={mobileFiltersOpen}
                                onClose={() => setMobileFiltersOpen(false)}
                                filters={selectedFilters}
                                filterOptions={filterOptions}
                                onFilterChange={handleFilterChange}
                                activeFilterCount={getActiveFilterCount()}
                                onReset={resetFilters}
                            />
                        )}
                    </AnimatePresence>

                    <div className="flex gap-8">
                        {showFilters && (
                            <motion.div initial={{ opacity: 0, x: -20 }} animate={{ opacity: 1, x: 0 }} exit={{ opacity: 0, x: -20 }} className="hidden lg:block w-64 flex-shrink-0">
                                <div className="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                                    <h3 className="font-semibold text-lg mb-4">Filters</h3>
                                    <div className="mb-6">
                                        <label className="block text-sm font-medium text-gray-700 mb-2">Level</label>
                                        <select value={selectedFilters.level || ''} onChange={(e) => handleFilterChange('level', e.target.value)} className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">All Levels</option>
                                            {filterOptions?.levels?.map((level) => (<option key={level} value={level}>{level}</option>))}
                                        </select>
                                    </div>
                                    <div className="mb-6">
                                        <label className="block text-sm font-medium text-gray-700 mb-2">Price</label>
                                        {filterOptions?.priceTypes?.map((type) => (
                                            <label key={type.value} className="flex items-center mb-2">
                                                <input type="radio" name="price_type" value={type.value} checked={selectedFilters.price_type === type.value} onChange={(e) => handleFilterChange('price_type', e.target.value)} className="mr-2" />
                                                <span className="text-sm text-gray-700">{type.label}</span>
                                            </label>
                                        ))}
                                    </div>
                                    <div className="mb-6">
                                        <label className="block text-sm font-medium text-gray-700 mb-2">Special</label>
                                        <label className="flex items-center mb-2">
                                            <input type="checkbox" checked={selectedFilters.featured || false} onChange={(e) => handleFilterChange('featured', e.target.checked)} className="mr-2 rounded" />
                                            <span className="text-sm text-gray-700">Featured Only</span>
                                        </label>
                                        <label className="flex items-center">
                                            <input type="checkbox" checked={selectedFilters.popular || false} onChange={(e) => handleFilterChange('popular', e.target.checked)} className="mr-2 rounded" />
                                            <span className="text-sm text-gray-700">Popular Only</span>
                                        </label>
                                    </div>
                                </div>
                            </motion.div>
                        )}

                        <div className="flex-1">
                            <div className="mb-4 text-sm text-gray-600">
                                Showing {courses.from || 0} - {courses.to || 0} of {courses.total || 0} courses
                            </div>

                            {courses.data && courses.data.length > 0 ? (
                                <div className={`grid gap-6 ${showFilters ? 'grid-cols-1 md:grid-cols-3 xl:grid-cols-3' : 'grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-3'}`}>
                                    {courses.data.map((course, index) => (
                                        <motion.div key={course.id} initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.3, delay: index * 0.05 }}>
                                            <DashboardCourseCard   
                                                course={course}  
                                                onAddToCart={handleAddToCart}
                                                onScholarshipEnroll={handleScholarshipEnroll} // Pass new handler
                                                isInCart={isInCart(course.id)}
                                                isAdding={addingToCart[course.id]}
                                            /> 
                                        </motion.div>
                                    ))} 
                                </div>
                            ) : (
                                <div className="text-center py-16 bg-white rounded-xl">
                                    <svg className="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <h3 className="text-xl font-semibold text-gray-900 mb-2">No courses found</h3>
                                    <p className="text-gray-600 mb-6">Try adjusting your search or filter criteria</p>
                                    <button onClick={resetFilters} className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Clear All Filters</button>
                                </div>
                            )}

                            {courses.links && courses.links.length > 3 && (
                                <div className="mt-8 flex justify-center">
                                    <nav className="flex items-center gap-2">
                                        {courses.links.map((link, index) => (
                                            <Link key={index} href={link.url || '#'} className={`px-4 py-2 rounded-lg transition ${link.active ? 'bg-blue-600 text-white' : link.url ? 'bg-white text-gray-700 hover:bg-gray-50' : 'bg-gray-100 text-gray-400 cursor-not-allowed'}`} dangerouslySetInnerHTML={{ __html: link.label }} preserveState preserveScroll />
                                        ))}
                                    </nav>
                                </div>
                            )}
                        </div>
                    </div>
                </section>
            </div>
        </AuthenticatedLayout>
    );
}