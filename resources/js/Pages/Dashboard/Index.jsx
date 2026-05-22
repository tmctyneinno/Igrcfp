import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import LearningCenter from '@/Pages/Dashboard/Learner/LearningCenter';
import MyLearning from '@/Pages/Dashboard/Learner/MyLearning';
import MostPopular from '@/Pages/Dashboard/Learner/MostPopular';
import CourseCategories from '@/Pages/Dashboard/Learner/CourseCategories';
import { useCartCount } from '@/contexts/CartContext'; 

export default function Dashboard({ auth, courses, enrolledCourses, popularCourses, categories }) {
    const { cartCount, cartItems } = useCartCount(); 
    return (   
        <AuthenticatedLayout>   
            <Head title="Dashboard" />
            <LearningCenter enrolledCourses={enrolledCourses} />
            {/* <CourseCategories categories={categories} /> */}
            <MyLearning enrolledCourses={enrolledCourses} />
            <MostPopular initialCourses={popularCourses} />
        </AuthenticatedLayout>  
    );   
}