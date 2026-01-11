import { Head, Link, useForm } from '@inertiajs/react';
import { useEffect } from 'react';

export default function AdminLogin({ status }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route('admin.login'));
    };

    return (
        <div className="min-h-screen grid grid-cols-1 lg:grid-cols-2">
            <Head title="Admin Login" />
            {/* LEFT SIDE – IMAGE */}
            <div className="hidden lg:flex items-center justify-center bg-blue-10 relative">
                <img
                    src="/assets/admin/images/auth/auth-img.png"
                    alt="Admin Login Visual"
                    className="max-w-lg w-full object-contain"
                />
            </div>
            {/* RIGHT SIDE – FORM */}
            <div className="flex flex-col justify-center px-6 sm:px-12 lg:px-20 bg-gray-50">
                <div className="max-w-md w-full mx-auto">
                    {/* Logo */}
                    <div className="mb-8">
                        <img
                            src="/assets/images/home-three/logo/logo-main.png"
                            alt="IGRCFP Logo"
                            className="h-14 w-auto mx-auto"
                        />
                    </div>

                    <h2 className="text-3xl font-extrabold text-gray-900 w-auto text-center">
                        Admin Portal
                    </h2>
                    <p className="mt-2 text-sm text-gray-600 w-auto mx-auto text-center">
                        Sign in to access the admin dashboard
                    </p>

                    <div className="mt-8 bg-white py-8 px-6 shadow rounded-lg">
                        <form className="space-y-6" onSubmit={submit}>
                            {/* Email */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700">
                                    Email address
                                </label>
                                <input
                                    type="email"
                                    required
                                    className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="admin@example.com"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                />
                                {errors.email && (
                                    <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                                )}
                            </div>

                            {/* Password */}
                            <div>
                                <label className="block text-sm font-medium text-gray-700">
                                    Password
                                </label>
                                <input
                                    type="password"
                                    required
                                    className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="••••••••"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                />
                                {errors.password && (
                                    <p className="mt-1 text-sm text-red-600">{errors.password}</p>
                                )}
                            </div>

                            {/* Remember / Forgot */}
                            <div className="flex items-center justify-between">
                                <label className="flex items-center text-sm text-gray-700">
                                    <input
                                        type="checkbox"
                                        className="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                        checked={data.remember}
                                        onChange={(e) => setData('remember', e.target.checked)}
                                    />
                                    <span className="ml-2">Remember me</span>
                                </label>

                                <Link
                                    href={route('admin.password.request')}
                                    className="text-sm font-medium text-blue-600 hover:text-blue-500"
                                >
                                    Forgot password?
                                </Link>
                            </div>

                            {/* Submit */}
                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full py-3 px-4 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 transition disabled:opacity-50"
                            >
                                {processing ? 'Signing in...' : 'Sign in'}
                            </button>
                        </form>

                        {/* Footer Links */}
                        <div className="mt-6 text-center">
                            <Link href="/" className="text-sm text-blue-600 hover:text-blue-500">
                                ← Back to Home
                            </Link>
                        </div>
                    </div>

                    {/* Status */}
                    {status && (
                        <div className="mt-4 p-4 bg-green-50 rounded-md text-green-800 text-sm">
                            {status}
                        </div>
                    )}
                </div>
            </div>

            
        </div>
    );
}
