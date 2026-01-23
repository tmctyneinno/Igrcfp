// resources/js/Contexts/EnrollmentContext.jsx
import React, { createContext, useState, useContext } from 'react';
import { router } from '@inertiajs/react';

const EnrollmentContext = createContext();

export const EnrollmentProvider = ({ children, user }) => {
    const [selectedCourse, setSelectedCourse] = useState(null);

    const startEnrollment = (course) => {
        setSelectedCourse(course);
        
        if (!user) {
            // Redirect to login with return URL
            router.visit('/login', {
                data: { 
                    redirect: `/courses/${course.slug}/enroll`,
                    course_id: course.id
                }
            });
            return;
        }
        
        // User is logged in, proceed to enrollment page
        router.visit(`/courses/${course.slug}/enroll`);
    };

    return (
        <EnrollmentContext.Provider value={{
            startEnrollment,
            selectedCourse,
            user
        }}>
            {children}
        </EnrollmentContext.Provider>
    );
};

export const useEnrollment = () => useContext(EnrollmentContext);