import { Head } from '@inertiajs/react';

export default function Dashboard({ stats }) {
    return (
        <>
            <Head>
                <title>Admin Dashboard</title>
                {/* Link to external CSS */}
                <link rel="stylesheet" href="/css/admin.css" />
                <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
            </Head>
            
            <div className="admin-dashboard">
                {/* Your dashboard content */}
                <h1 className="text-3xl font-bold">Dashboard</h1>
                {/* ... */}
            </div>
        </>
    );
}