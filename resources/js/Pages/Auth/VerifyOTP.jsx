import GuestLayout from '@/Layouts/GuestLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import axios from 'axios';

export default function VerifyOTP({ email, phone }) {
    const { auth } = usePage().props;
    const [otpCode, setOtpCode] = useState('');
    const [error, setError] = useState('');
    const [success, setSuccess] = useState('');
    const [loading, setLoading] = useState(false);
    const [resendDisabled, setResendDisabled] = useState(false);
    const [countdown, setCountdown] = useState(0);

    useEffect(() => {
        // Auto-send OTP when component mounts
        sendOTP();
    }, []);

    useEffect(() => {
        let timer;
        if (countdown > 0) {
            timer = setInterval(() => {
                setCountdown(prev => prev - 1);
            }, 1000);
        } else if (countdown === 0 && resendDisabled) {
            setResendDisabled(false);
        }
        return () => clearInterval(timer);
    }, [countdown, resendDisabled]);

    const sendOTP = async () => {
        setLoading(true);
        try {
            const response = await axios.post('/verify-otp/send');
            setSuccess('Verification code sent to your email and phone');
            setResendDisabled(true);
            setCountdown(60); // 60 seconds cooldown
        } catch (error) {
            setError('Failed to send verification code. Please try again.');
        } finally {
            setLoading(false);
        }
    };

    const verifyOTP = async (e) => {
        e.preventDefault();
        setError('');
        setLoading(true);

        try {
            const response = await axios.post('/verify-otp/verify', {
                otp_code: otpCode
            });

            if (response.data.success) {
                window.location.href = response.data.redirect;
            }
        } catch (error) {
            setError(error.response?.data?.error || 'Invalid verification code. Please try again.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <GuestLayout auth={auth}>
            <Head title="Verify OTP" />
            
            <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
                <div className="w-full max-w-md">
                    <div className="bg-white rounded-2xl shadow-xl p-8">
                        {/* Header */}
                        <div className="text-center mb-8">
                            <div className="mx-auto w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                                <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6-4h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2zm10-4V6a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h2 className="text-2xl font-bold text-gray-900">Verify Your Identity</h2>
                            <p className="mt-2 text-gray-600">
                                We've sent a verification code to:
                            </p>
                            <div className="mt-2 space-y-1">
                                {email && (
                                    <p className="text-sm text-gray-700">
                                        <span className="font-medium">Email:</span> {email}
                                    </p>
                                )}
                                {phone && (
                                    <p className="text-sm text-gray-700">
                                        <span className="font-medium">Phone:</span> {phone}
                                    </p>
                                )}
                            </div>
                        </div>

                        {/* Success Message */}
                        {success && (
                            <div className="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p className="text-sm text-green-800">{success}</p>
                            </div>
                        )}

                        {/* Error Message */}
                        {error && (
                            <div className="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p className="text-sm text-red-800">{error}</p>
                            </div>
                        )}

                        {/* OTP Form */}
                        <form onSubmit={verifyOTP} className="space-y-6">
                            <div>
                                <label htmlFor="otp" className="block text-sm font-medium text-gray-700 mb-2">
                                    Enter 6-digit verification code
                                </label>
                                <input
                                    type="text"
                                    id="otp"
                                    value={otpCode}
                                    onChange={(e) => setOtpCode(e.target.value.replace(/\D/g, '').slice(0, 6))}
                                    className="w-full px-4 py-3 text-center text-2xl tracking-widest border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="000000"
                                    maxLength="6"
                                    autoFocus
                                />
                            </div>

                            <button
                                type="submit"
                                disabled={loading || otpCode.length !== 6}
                                className="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {loading ? 'Verifying...' : 'Verify Code'}
                            </button>
                        </form>

                        {/* Resend Section */}
                        <div className="mt-6 text-center">
                            <p className="text-sm text-gray-600">
                                Didn't receive the code?{' '}
                                <button
                                    onClick={sendOTP}
                                    disabled={resendDisabled}
                                    className={`font-medium ${resendDisabled ? 'text-gray-400 cursor-not-allowed' : 'text-indigo-600 hover:text-indigo-500'}`}
                                >
                                    Resend Code {resendDisabled && `(${countdown}s)`}
                                </button>
                            </p>
                        </div>

                        {/* Back to Login */}
                        <div className="mt-4 text-center">
                            <a href={route('logout')} 
                               onClick={(e) => {
                                   e.preventDefault();
                                   axios.post('/logout').then(() => window.location.href = '/login');
                               }}
                               className="text-sm text-gray-500 hover:text-gray-700">
                                Back to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}