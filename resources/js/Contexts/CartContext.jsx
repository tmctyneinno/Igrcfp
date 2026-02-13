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
            router.post(route('cart.add', course.id), {}, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (page) => {
                    // Check if course was added successfully
                    if (page.props.flash?.success) {
                        // Add to local state
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
                        
                        // Show success message (optional)
                        if (page.props.flash.success) {
                            alert(page.props.flash.success);
                        }
                        
                        resolve(true);
                    } else {
                        // Handle case where course might already be in cart
                        if (page.props.flash?.info) {
                            alert(page.props.flash.info);
                        }
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
        // Find the cart item ID from your items
        const item = cartItems.find(item => item.id === courseId);
        if (!item) return;
        
        // You'll need the cart_item_id - this might need adjustment
        // based on how your backend identifies cart items
        router.delete(route('cart.remove', courseId), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                // Remove from local state
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