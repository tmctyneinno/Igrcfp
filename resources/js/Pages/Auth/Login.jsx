import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const [showPassword, setShowPassword] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    const togglePasswordVisibility = () => {
        setShowPassword(!showPassword);
    };

    return (
        <GuestLayout>
            <Head title="Log in" />
            
            {/* Custom Login Section */}
            <section className="bizzen-contact_two pt-100 pb-120">
                <div className="container">
                    <div className="row justify-content-center">
                        {/* Image Section */}
                        <div className="col-xl-6 col-lg-6 d-none d-lg-block">
                            <div className="auth-center">
                                <div className="d-flex align-items-center flex-column h-100 justify-content-center">
                                    <img 
                                        src="/assets/admin/images/auth/auth-img.png" 
                                        alt="Authentication" 
                                        className="img-fluid"
                                    />
                                </div>
                            </div>
                        </div> 
                        
                        {/* Form Section */}
                        <div className="col-xl-6 col-lg-8 col-md-10">
                            <div className="contact-wrapper" data-aos="fade-left" data-aos-duration="1400">
                                
                                {status && (
                                    <div className="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                        {status}
                                        <button type="button" className="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                )}

                                <div className="row justify-content-center">
                                    <div className="col-lg-12">
                                        <div className="section-title text-center mb-50">
                                            <div className="site-branding mb-3">
                                                <Link href={route('home')} className="brand-logo">
                                                    <img 
                                                        src="/assets/images/home-three/logo/logo-main.png" 
                                                        style={{width: '60px'}} 
                                                        alt="Brand Logo" 
                                                    />
                                                </Link>
                                            </div>
                                            <h2 className="text-2xl font-bold text-gray-900">Welcome Back!</h2>
                                            <p className="text-gray-600">Login to your account</p>
                                        </div>
                                    </div>
                                </div>

                                <form onSubmit={submit}>
                                    <div className="space-y-6">
                                        {/* Email Input */}
                                        <div>
                                            <InputLabel htmlFor="email" value="Email Address" className="mb-2" />
                                            <div className="input-group">
                                                <span className="input-group-text">
                                                    <i className="far fa-envelope"></i>
                                                </span>
                                                <TextInput
                                                    id="email"
                                                    type="email"
                                                    name="email"
                                                    value={data.email}
                                                    className="form-control"
                                                    placeholder="Enter your email"
                                                    autoComplete="username"
                                                    isFocused={true}
                                                    onChange={(e) => setData('email', e.target.value)}
                                                />
                                            </div>
                                            <InputError message={errors.email} className="mt-2" />
                                        </div>

                                        {/* Password Input */}
                                        <div>
                                            <InputLabel htmlFor="password" value="Password" className="mb-2" />
                                            <div className="input-group">
                                                <span className="input-group-text">
                                                    <i className="far fa-lock"></i>
                                                </span>
                                                <TextInput
                                                    id="password"
                                                    type={showPassword ? "text" : "password"}
                                                    name="password"
                                                    value={data.password}
                                                    className="form-control"
                                                    placeholder="Enter your password"
                                                    autoComplete="current-password"
                                                    onChange={(e) => setData('password', e.target.value)}
                                                />
                                                <button 
                                                    type="button" 
                                                    className="btn btn-outline-secondary toggle-password"
                                                    onClick={togglePasswordVisibility}
                                                >
                                                    <i className={`far ${showPassword ? 'fa-eye-slash' : 'fa-eye'}`}></i>
                                                </button>
                                            </div>
                                            <InputError message={errors.password} className="mt-2" />
                                        </div>

                                        {/* Remember Me & Forgot Password */}
                                        <div className="flex items-center justify-between">
                                            <label className="flex items-center">
                                                <Checkbox
                                                    name="remember"
                                                    checked={data.remember}
                                                    onChange={(e) => setData('remember', e.target.checked)}
                                                />
                                                <span className="ms-2 text-sm text-gray-600">
                                                    Remember me
                                                </span>
                                            </label>

                                            {canResetPassword && (
                                                <Link
                                                    href={route('password.request')}
                                                    className="text-sm text-primary hover:text-primary/80"
                                                >
                                                    Forgot your password?
                                                </Link>
                                            )}
                                        </div>

                                        {/* Submit Button */}
                                        <div className="text-center">
                                            <PrimaryButton 
                                                className="w-full justify-center py-3 px-4"
                                                disabled={processing}
                                            >
                                                {processing ? (
                                                    <>
                                                        <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                                                        Logging in...
                                                    </>
                                                ) : (
                                                    <>
                                                        <i className="fas fa-sign-in-alt me-2"></i>
                                                        Log in
                                                    </>
                                                )}
                                            </PrimaryButton>
                                        </div>

                                        {/* Register Link */}
                                        {route().has('register') && (
                                            <div className="text-center mt-4">
                                                <p className="text-sm text-gray-600">
                                                    Don't have an account?{' '}
                                                    <Link 
                                                        href={route('register')} 
                                                        className="font-medium text-primary hover:text-primary/80"
                                                    >
                                                        Register here
                                                    </Link>
                                                </p>
                                            </div>
                                        )}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <style>{`
                .toggle-password {
                    cursor: pointer;
                    background-color: #fff;
                    border: 1px solid #ced4da;
                    border-left: none;
                    height: 42px;
                }

                .toggle-password:hover {
                    background-color: #f8f9fa;
                }

                .input-group .form-control {
                    border-right: 0;
                    height: 42px;
                }

                .input-group .toggle-password {
                    border-left: 0;
                }

                .input-group-text {
                    background-color: #fff;
                    height: 42px;
                }
                
                .auth-center {
                    height: 100%;
                    padding: 2rem;
                }
            `}</style>
        </GuestLayout>
    );
}