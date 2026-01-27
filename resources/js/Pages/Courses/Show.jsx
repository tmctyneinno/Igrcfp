import React, { useState } from 'react';
import { Head } from '@inertiajs/react';

export default function Show({ course, auth, isEnrolled }) {
    console.log('Show component loaded!', { course, auth, isEnrolled });
    
    return (
        <div>
            <Head title={course?.title || 'Course'} />
            <h1>Course Show Page - TEST</h1>
            <p>If you see this, the component loaded successfully!</p>
            <pre>{JSON.stringify({ 
                hasCourse: !!course, 
                courseTitle: course?.title,
                hasAuth: !!auth,
                isEnrolled 
            }, null, 2)}</pre>
        </div>
    );
}
