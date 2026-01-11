import Header from "@/Pages/Admin/layouts/Header";
import Footer from "@/Pages/Admin/layouts/Footer";
import NavBar from "@/Pages/Admin/layouts/NavBar";
import SideBar from "@/Pages/Admin/layouts/SideBar";

export default function AdminLayout({ children, title = 'Admin Dashboard', adminName }) {
    return (
        <>
            <Header title="Admin Dashboard" />
            <SideBar />
            <main className="dashboard-main">
                <NavBar adminName={adminName} /> 
                {children}
                <Footer />
            </main>
        </>
    );
}
