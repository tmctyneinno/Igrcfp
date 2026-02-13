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
            // Make sure the route exists
            const url = route('cart.add', course.id);
            console.log('Adding to cart:', url); // Debug log
            
            router.post(url, {}, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    console.log('Success response:', page); // Debug log
                    
                    if (page.props.flash?.success) {
                        // Check if already in cart (backend should prevent duplicates)
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
                        
                        alert(page.props.flash.success);
                        resolve(true);
                    } else if (page.props.flash?.info) {
                        alert(page.props.flash.info);
                        resolve(false);
                    }
                },
                onError: (errors) => {
                    console.error('Error adding to cart:', errors);
                    alert('Failed to add course to cart. Please try again.');
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