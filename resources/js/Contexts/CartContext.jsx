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
            const url = route('dashboard.cart.add', course.slug);
            
            router.post(url, {}, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
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
                        
                        // toast.success(page.props.flash.success, {
                        //     duration: 4000,
                        //     position: 'top-right',
                        //     icon: '🛒'
                        // });
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

    // Remove from cart - now expects cart item ID
    const removeFromCart = (cartItemId) => {
        if (!cartItemId) return;
        
        // Show loading toast
        router.delete(route('cart.remove', cartItemId), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                if (page.props.flash?.success) {
                    toast.success(page.props.flash.success, {
                        duration: 4000,
                        position: 'top-right'
                    });
                }
                
                // Refresh cart data from server to get updated list
                refreshCart();
                // Check if the response includes updated cart data
            if (page.props.flash?.cart) {
                // Update local cart items based on the returned cart data
                const updatedCart = page.props.flash.cart;
                
                // Convert the cart items to your local format
                const newCartItems = updatedCart.items.map(item => ({
                    id: item.course.id,
                    title: item.course.title,
                    // ... map other fields
                }));
                
                setCartItems(newCartItems);
                setCartCount(newCartItems.length);
            } else {
                // If no cart data, refresh from server
                refreshCart();
            }
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
        router.post(route('cart.clear'), {}, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                
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

export function useCartCount() {
    const { cartCount } = useCart();
    return cartCount;
}