import GuestLayout from '@/Layouts/GuestLayout';
import { Head, usePage } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import axios from 'axios';

export default function VerifyOTP({ email, phone }) {
    const { auth } = usePage().props;
    const [otpCode, setOtpCode] = useState('');
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);
    const [resendDisabled, setResendDisabled] = useState(false);
    const [countdown, setCountdown] = useState(0);
    const [message, setMessage] = useState('');

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
            setError(error.response?.data?.error || 'Invalid verification code');
        } finally {
            setLoading(false);
        }
    };

    const resendOTP = async () => {
        setError('');
        setMessage('');
        setResendDisabled(true);
        setCountdown(60);

        try {
            const response = await axios.post('/verify-otp/resend');
            setMessage('New verification code sent!');
        } catch (error) {
            setError(error.response?.data?.error || 'Failed to resend code');
            setResendDisabled(false);
            setCountdown(0);
        }
    }; 

    return (
        <GuestLayout auth={auth}>
            <Head title="Verify OTP" />
            
            <div className="min-h-screen pt-20 flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
                <div className="w-full max-w-md">
                    <div className="bg-white rounded-2xl shadow-xl p-8">
                        <div className="text-center mb-8">
                            <div className="mx-auto w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                                <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6-4h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2zm10-4V6a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h2 className="text-2xl font-bold text-gray-900">Verify Your Identity</h2>
                            <p className="mt-2 text-gray-600">
                                Enter the 6-digit code sent to:
                            </p>
                            <p className="text-sm font-medium text-gray-900 mt-1">{email}</p>
                            {phone && <p className="text-sm font-medium text-gray-900">{phone}</p>}
                        </div>

                        {message && (
                            <div className="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p className="text-sm text-green-800">{message}</p>
                            </div>
                        )}

                        {error && (
                            <div className="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p className="text-sm text-red-800">{error}</p>
                            </div>
                        )}

                        <form onSubmit={verifyOTP} className="space-y-6">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Verification Code
                                </label>
                                <input
                                    type="text"
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

                        <div className="mt-6 text-center">
                            <button
                                onClick={resendOTP}
                                disabled={resendDisabled}
                                className="text-sm text-indigo-600 hover:text-indigo-500 disabled:text-gray-400"
                            >
                                {resendDisabled ? `Resend code in ${countdown}s` : 'Resend Code'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}