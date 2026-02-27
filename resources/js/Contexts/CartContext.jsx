import React, { createContext, useContext, useState, useEffect } from 'react';
import { router } from '@inertiajs/react';

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

    // Remove from cart
    const removeFromCart = (courseId) => {
        const item = cartItems.find(item => item.id === courseId);
        if (!item) return;
        
        router.delete(route('cart.remove', courseId), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                const newCartItems = cartItems.filter(item => item.id !== courseId);
                setCartItems(newCartItems);
                setCartCount(newCartItems.length);
                
                if (page.props.flash?.success) {
                    alert(page.props.flash.success);
                }
            },
            onError: (errors) => {
                console.error('Error removing from cart:', errors);
            }
        });
    };

    // Clear cart
    const clearCart = () => {
        router.post(route('cart.clear'), {}, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                setCartItems([]);
                setCartCount(0);
                
                if (page.props.flash?.success) {
                    alert(page.props.flash.success);
                }
            },
            onError: (errors) => {
                console.error('Error clearing cart:', errors);
            }
        });
    };

    const value = {
        cartCount,
        cartItems,
        addToCart,
        removeFromCart,
        clearCart
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