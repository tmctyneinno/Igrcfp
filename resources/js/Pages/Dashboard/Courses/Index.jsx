// resources/js/config/toast.js
import toast from 'react-hot-toast';

export const toastConfig = {
    success: (message, options = {}) => {
        toast.success(message, {
            duration: 3000,
            icon: '✅',
            style: {
                background: '#10b981',
                color: '#fff',
                padding: '16px',
                borderRadius: '8px',
                fontWeight: '500',
            },
            ...options
        });
    },
    
    error: (message, options = {}) => {
        toast.error(message, {
            duration: 4000,
            icon: '❌',
            style: {
                background: '#ef4444',
                color: '#fff',
                padding: '16px',
                borderRadius: '8px',
                fontWeight: '500',
            },
            ...options
        });
    },
    
    info: (message, options = {}) => {
        toast(message, {
            duration: 3000,
            icon: 'ℹ️',
            style: {
                background: '#3b82f6',
                color: '#fff',
                padding: '16px',
                borderRadius: '8px',
                fontWeight: '500',
            },
            ...options
        });
    },
    
    warning: (message, options = {}) => {
        toast(message, {
            duration: 3500,
            icon: '⚠️',
            style: {
                background: '#f59e0b',
                color: '#fff',
                padding: '16px',
                borderRadius: '8px',
                fontWeight: '500',
            },
            ...options
        });
    }
};

// Usage: import { toastConfig } from '@/config/toast';
// toastConfig.success('Message');