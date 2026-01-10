import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }) {
    return (
        <div className="">
            {/* Main Content */}
            <main className="pt-16">
                {children}
            </main>

        </div>
    );
}
