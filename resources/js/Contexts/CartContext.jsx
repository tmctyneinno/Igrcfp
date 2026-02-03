import React, { createContext, useContext, useState, useEffect } from 'react';

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

    const addToCart = (course) => {
        // Check if course already in cart
        const existingItem = cartItems.find(item => item.id === course.id);
        
        if (!existingItem) {
            const newCartItems = [...cartItems, { 
                id: course.id, 
                title: course.title,
                price: course.price,
                discount_price: course.discount_price,
                image_url: course.image_url || course.banner_image
            }];
            setCartItems(newCartItems);
            setCartCount(newCartItems.length);
            return true;
        }
        return false;
    };

    const removeFromCart = (courseId) => {
        const newCartItems = cartItems.filter(item => item.id !== courseId);
        setCartItems(newCartItems);
        setCartCount(newCartItems.length);
    };

    const clearCart = () => {
        setCartItems([]);
        setCartCount(0);
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