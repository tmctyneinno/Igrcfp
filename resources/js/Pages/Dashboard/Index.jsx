import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import LearningCenter from '@/Pages/Dashboard/Learner/LearningCenter';
import MyLearning from '@/Pages/Dashboard/Learner/MyLearning';
import MostPopular from '@/Pages/Dashboard/Learner/MostPopular';
import CourseCategories from '@/Pages/Dashboard/Learner/CourseCategories';
import { useCartCount } from '@/contexts/CartContext'; 
import toast from 'react-hot-toast';

export default function Dashboard({ auth, courses, enrolledCourses, popularCourses, categories }) {
    const { cartCount, cartItems } = useCartCount(); 
 
    // Handle Direct Scholarship Enrollment
    const handleScholarshipEnroll = async (course) => {
        try {
            router.post(route('courses.enroll', course.slug), {}, {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Scholarship activated! You are now enrolled.', {
                        icon: '🎓',
                        style: { background: '#10b981', color: '#fff' }
                    });
                    window.location.reload();
                },
                onError: (errors) => {
                    console.error("Enrollment failed", errors);
                    toast.error('Failed to activate scholarship.', {
                        style: { background: '#ef4444', color: '#fff' }
                    });
                }
            });
        } catch (error) {
            console.error('Error:', error);
        }
    };

    return (   
        <AuthenticatedLayout auth={auth}>   
            <Head title="Dashboard" /> 
            <LearningCenter enrolledCourses={enrolledCourses} />
            {/* <CourseCategories categories={categories} /> */}
            <MyLearning enrolledCourses={enrolledCourses} />
            <MostPopular 
                initialCourses={popularCourses} 
                onScholarshipEnroll={handleScholarshipEnroll}
                authUser={auth?.user}
            />
        </AuthenticatedLayout>  
    );   
}