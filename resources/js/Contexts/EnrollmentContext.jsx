import React, { createContext, useContext, useState, useEffect } from 'react';
import { router } from '@inertiajs/react';

// Create context
const EnrollmentContext = createContext();

// Provider component
export const EnrollmentProvider = ({ children, user, enrollmentRedirect }) => {
  const [selectedCourse, setSelectedCourse] = useState(null);
  const [redirectAfterAuth, setRedirectAfterAuth] = useState(null);

  // Handle enrollment redirect after login
  useEffect(() => {
    if (user && enrollmentRedirect) {
      // User is now logged in and we have a pending enrollment redirect
      router.visit(enrollmentRedirect);
    }
  }, [user, enrollmentRedirect]);

  const startEnrollment = (course) => {
    setSelectedCourse(course);
    
    if (!user) {
      // Store the intended enrollment in session storage
      sessionStorage.setItem('pendingEnrollment', JSON.stringify({
        courseId: course.id,
        courseSlug: course.slug,
        timestamp: Date.now()
      }));
      
      // Pass redirect as query parameter
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