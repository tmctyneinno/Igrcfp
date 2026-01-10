import { useEffect } from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'student',
        phone: '',
        date_of_birth: '',
    }); 

    const [currentStep, setCurrentStep] = useState(1);
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    
    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route('register'));
    };
    

    const nextStep = () => {
        // Validate current step before proceeding
        if (currentStep === 1 && !data.role) {
            return; // Don't proceed if role is not selected
        }
        if (currentStep === 2 && (!data.name || !data.email)) {
            return; // Don't proceed if required fields are empty
        }
        setCurrentStep(currentStep + 1);
    };

    const prevStep = () => {
        setCurrentStep(currentStep - 1);
    };

    const togglePasswordVisibility = () => {
        setShowPassword(!showPassword);
    };

    const toggleConfirmPasswordVisibility = () => {
        setShowConfirmPassword(!showConfirmPassword);
    };

    // Steps configuration
    const steps = [
        { number: 1, title: 'Select Role', description: 'Choose your account type' },
        { number: 2, title: 'Personal Info', description: 'Enter your details' },
        { number: 3, title: 'Security', description: 'Create your password' },
    ];


    return (
        <GuestLayout>
            <Head title="Register" />
            <div className="min-h-screen flex">
                {/* Left Side - Image */}
                 <div className="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-50 to-indigo-100">
                    <div className="flex items-center justify-center w-full p-12">
                        <div className="text-center">
                            <div className="mb-8">
                                <img 
                                    src="/assets/admin/images/auth/auth-img.png" 
                                    alt="Registration" 
                                    className="mx-auto max-w-full h-auto"
                                    style={{ maxHeight: '500px' }}
                                />
                            </div>
                            <h2 className="text-2xl font-bold text-gray-800 mb-4">
                                Join Our Community
                            </h2>
                            <p className="text-gray-600">
                                Create an account to access all features and start your journey
                            </p>
                        </div>
                    </div>
                </div>
                 {/* Right Side - Form */}
                <div className="w-full lg:w-1/2 flex items-center justify-center p-8">
                    <div class="row justify-content-center">
                        <div className="w-full max-w-md">
                            <div className="col-lg-12">
                                <div className="section-title text-center mb-50">
                                    <div className="site-branding">
                                        <a href="{{ route('index') }}" className="brand-logo">
                                            <img src="/assets/images/home-three/logo/logo-main.png" style={{width:'60px'}} alt="Brand Logo"/>
                                        </a>
                                    </div>
                                    <h2>Join Us!</h2>
                                    <p>Please provide your details</p>
                                </div>
                            </div>
                        </div>
                        {/* Progress Steps */}
                        <div className="mb-8">
                            <div className="flex items-center justify-between">
                                {steps.map((step) => (
                                    <div key={step.number} className="flex flex-col items-center flex-1">
                                        <div className={`w-10 h-10 rounded-full flex items-center justify-center mb-2 ${currentStep >= step.number ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500'}`}>
                                            <span className="font-semibold">{step.number}</span>
                                        </div>
                                        <div className="text-center">
                                            <div className={`text-sm font-medium ${currentStep >= step.number ? 'text-indigo-600' : 'text-gray-500'}`}>
                                                {step.title}
                                            </div>
                                            <div className="text-xs text-gray-500 hidden md:block">
                                                {step.description}
                                            </div>
                                        </div>
                                        {step.number < steps.length && (
                                            <div className={`h-1 flex-1 mt-5 mx-2 ${currentStep > step.number ? 'bg-indigo-600' : 'bg-gray-200'}`}></div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>

                        <form onSubmit={submit} className="space-y-6">
                            {/* Role Field */}
                            <div>
                                <InputLabel htmlFor="role" value="I want to join as a" />
                                <div className="mt-1 relative">
                                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg className="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fillRule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clipRule="evenodd" />
                                        </svg>
                                    </div>
                                    <select
                                        id="role"
                                        name="role"
                                        value={data.role}
                                        className="pl-10 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                        onChange={(e) => setData('role', e.target.value)}
                                        required
                                    >
                                        <option value="student">Student</option>
                                        <option value="instructor">Instructor</option>
                                        <option value="admin">Administrator</option>
                                    </select>
                                </div>
                                <InputError message={errors.role} className="mt-2" />
                            </div>
                            <div>
                                <InputLabel htmlFor="name" value="Name" />
                                <TextInput
                                    id="name"
                                    name="name"
                                    value={data.name}
                                    className="mt-1 block w-full"
                                    autoComplete="name"
                                    isFocused={true}
                                    onChange={(e) => setData('name', e.target.value)}
                                    required
                                />
                                <InputError message={errors.name} className="mt-2" />
                            </div>

                            <div>
                                <InputLabel htmlFor="email" value="Email" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    className="mt-1 block w-full"
                                    autoComplete="username"
                                    onChange={(e) => setData('email', e.target.value)}
                                    required
                                />
                                <InputError message={errors.email} className="mt-2" />
                            </div>

                            <div>
                                <InputLabel htmlFor="phone" value="Phone Number (Optional)" />
                                <TextInput
                                    id="phone"
                                    type="tel"
                                    name="phone"
                                    value={data.phone}
                                    className="mt-1 block w-full"
                                    autoComplete="tel"
                                    onChange={(e) => setData('phone', e.target.value)}
                                />
                                <InputError message={errors.phone} className="mt-2" />
                            </div>

                            <div>
                                <InputLabel htmlFor="date_of_birth" value="Date of Birth (Optional)" />
                                <TextInput
                                    id="date_of_birth"
                                    type="date"
                                    name="date_of_birth"
                                    value={data.date_of_birth}
                                    className="mt-1 block w-full"
                                    onChange={(e) => setData('date_of_birth', e.target.value)}
                                />
                                <InputError message={errors.date_of_birth} className="mt-2" />
                            </div>

                            <div>
                                <InputLabel htmlFor="password" value="Password" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    name="password"
                                    value={data.password}
                                    className="mt-1 block w-full"
                                    autoComplete="new-password"
                                    onChange={(e) => setData('password', e.target.value)}
                                    required
                                />
                                <InputError message={errors.password} className="mt-2" />
                            </div>

                            <div>
                                <InputLabel htmlFor="password_confirmation" value="Confirm Password" />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    value={data.password_confirmation}
                                    className="mt-1 block w-full"
                                    autoComplete="new-password"
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    required
                                />
                                <InputError message={errors.password_confirmation} className="mt-2" />
                            </div>

                            <div className="flex items-center justify-end mt-4">
                                <Link
                                    href={route('login')}
                                    className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Already registered?
                                </Link>

                                <PrimaryButton className="ml-4" disabled={processing}>
                                    Register
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            
        </GuestLayout>
    );
}