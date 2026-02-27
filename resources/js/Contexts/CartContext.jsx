// resources/js/contexts/CartContext.jsx

import React, { createContext, useContext, useState, useEffect } from 'react';
import { router } from '@inertiajs/react';
import toast from 'react-hot-toast';

const CartContext = createContext();

export function CartProvider({ children, initialCount = 0 }) {
    const [cartCount, setCartCount] = useState(initialCount);
    const [cartItems, setCartItems] = useState([]);

    // Load cart from localStorage on mount
    useEffect(() => {
        const savedCart = localStorage.getItem('cart');
        if (savedCart) {
            const cart = JSON.parse(savedCart);
            setCartItems(cart);
            setCartCount(cart.length);
        }
    }, []);

    // Save cart to localStorage when it changes
    useEffect(() => {
        localStorage.setItem('cart', JSON.stringify(cartItems));
    }, [cartItems]);

    // Add to cart - connects to backend CartController
    const addToCart = (course) => {
        return new Promise((resolve, reject) => {
            // Use course.slug instead of course.id
            const url = route('dashboard.cart.add', course.slug);
            console.log('Adding to cart - URL:', url);
            console.log('Route parameter:', course.slug);
            
            // Show loading toast
            const loadingToast = toast.loading('Adding to cart...');
            
            router.post(url, {}, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    toast.dismiss(loadingToast);
                    console.log('Success response:', page);
                    
                    if (page.props.flash?.success) {
                        const existingItem = cartItems.find(item => item.id === course.id);
                        
                        if (!existingItem) {
                            const newCartItems = [...cartItems, { 
                                id: course.id, 
                                title: course.title,
                                price: course.price,
                                discount_price: course.discount_price,
                                image_url: course.image_url || course.image,
                                slug: course.slug,
                                level: course.level,
                                duration: course.duration
                            }];
                            setCartItems(newCartItems);
                            setCartCount(newCartItems.length);
                        }
                        
                        toast.success(page.props.flash.success, {
                            duration: 4000,
                            position: 'top-right',
                            icon: '🛒'
                        });
                        resolve(true);
                    } else if (page.props.flash?.info) {
                        toast.info(page.props.flash.info, {
                            duration: 4000,
                            position: 'top-right'
                        });
                        resolve(false);
                    }
                    
                    // Refresh cart data from server
                    refreshCart();
                },
                onError: (errors) => {
                    toast.dismiss(loadingToast);
                    console.error('Error adding to cart:', errors);
                    
                    toast.error('Failed to add course to cart. Please try again.', {
                        duration: 5000,
                        position: 'top-right'
                    });
                    reject(errors);
                }
            });
        });
    };

    // Remove from cart - updated to handle both cart item ID and course ID
    const removeFromCart = (identifier, isCartItemId = false) => {
        let itemToRemove;
        let removeId;
        
        if (isCartItemId) {
            // If it's a cart item ID, we need to find the course ID from cart items
            // This is more complex - we'll rely on the backend to handle this
            removeId = identifier;
        } else {
            // It's a course ID
            itemToRemove = cartItems.find(item => item.id === identifier);
            if (!itemToRemove) return;
            removeId = identifier; // This is the course ID
        }
        
        // Show loading toast
        const removeToast = toast.loading('Removing from cart...');
        
        // The backend expects the cart item ID, not the course ID
        // You need to pass the correct ID based on your route
        router.delete(route('cart.remove', removeId), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                toast.dismiss(removeToast);
                
                // Update local state - filter by course ID if we used course ID
                if (!isCartItemId) {
                    const newCartItems = cartItems.filter(item => item.id !== identifier);
                    setCartItems(newCartItems);
                    setCartCount(newCartItems.length);
                }
                
                if (page.props.flash?.success) {
                    toast.success(page.props.flash.success, {
                        duration: 4000,
                        position: 'top-right'
                    });
                }
                
                // Always refresh cart data from server to ensure sync
                refreshCart();
            },
            onError: (errors) => {
                toast.dismiss(removeToast);
                console.error('Error removing from cart:', errors);
                
                toast.error('Failed to remove item from cart.', {
                    duration: 4000,
                    position: 'top-right'
                });
            }
        });
    };

    // Clear cart
    const clearCart = () => {
        // Show loading toast
        const clearToast = toast.loading('Clearing cart...');
        
        router.post(route('cart.clear'), {}, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                toast.dismiss(clearToast);
                
                setCartItems([]);
                setCartCount(0);
                
                if (page.props.flash?.success) {
                    toast.success(page.props.flash.success, {
                        duration: 4000,
                        position: 'top-right',
                        icon: '🗑️'
                    });
                }
                
                // Refresh cart data from server
                refreshCart();
            },
            onError: (errors) => {
                toast.dismiss(clearToast);
                console.error('Error clearing cart:', errors);
                
                toast.error('Failed to clear cart.', {
                    duration: 4000,
                    position: 'top-right'
                });
            }
        });
    };

    // Refresh cart data from server
    const refreshCart = () => {
        router.reload({ only: ['cart'] });
    };

    const value = {
        cartCount,
        cartItems,
        addToCart,
        removeFromCart,
        clearCart,
        refreshCart
    };

    return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

export function useCart() {
    const context = useContext(CartContext);
    if (!context) {
        throw new Error('useCart must be used within CartProvider');
    }
    return context;
}

// ADD THIS - export useCartCount as well
export function useCartCount() {
    const { cartCount } = useCart();
    return cartCount;
}