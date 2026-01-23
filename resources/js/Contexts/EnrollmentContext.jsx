import React, { createContext, useContext, useState } from 'react';
import { router } from '@inertiajs/react';

// Create context
const EnrollmentContext = createContext();

// Provider component
export const EnrollmentProvider = ({ children, user }) => {
  const [selectedCourse, setSelectedCourse] = useState(null);
  const [redirectAfterAuth, setRedirectAfterAuth] = useState(null);

  const startEnrollment = (course) => {
    setSelectedCourse(course);
    
    if (!user) {
      // Store the intended enrollment URL
      setRedirectAfterAuth(`/courses/${course.slug}/enroll`);
      // Redirect to login with return URL
      router.visit('/login', {
        data: { redirect: `/courses/${course.slug}/enroll` }
      });
      return;
    }
    
    // User is logged in, proceed to enrollment page
    router.visit(`/courses/${course.slug}/enroll`);
  };

  const value = {
    startEnrollment,
    selectedCourse,
    redirectAfterAuth,
    user
  };

  return (
    <EnrollmentContext.Provider value={value}>
      {children}
    </EnrollmentContext.Provider>
  );
};

// Custom hook to use enrollment context
export const useEnrollment = () => {
  const context = useContext(EnrollmentContext);
  if (context === undefined) {
    throw new Error('useEnrollment must be used within an EnrollmentProvider');
  }
  return context;
};