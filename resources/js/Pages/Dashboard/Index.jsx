import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import LearningCenter from '@/Pages/Dashboard/Learner/LearningCenter';
import MyLearning from '@/Pages/Dashboard/Learner/MyLearning';
import MostPopular from '@/Pages/Dashboard/Learner/MostPopular';
import { useCartCount } from '@/contexts/CartContext'; 

export default function Dashboard({ auth, courses, enrolledCourses, popularCourses }) {
    const { cartCount, cartItems } = useCartCount(); 
    return (  
        <AuthenticatedLayout> 
            <Head title="Dashboard" />
            <LearningCenter enrolledCourses={enrolledCourses} />
            <MyLearning enrolledCourses={enrolledCourses} />
            <MostPopular initialCourses={popularCourses} />
        </AuthenticatedLayout>
    );
}