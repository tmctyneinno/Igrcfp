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
      // Pass redirect as query parameter, not in data object
      const redirectUrl = `/courses/${course.slug}/enroll`;
      router.visit(`/login?redirect=${encodeURIComponent(redirectUrl)}`);
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