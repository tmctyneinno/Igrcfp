// resources/js/Pages/Admin/layouts/Footer.jsx
import { Head } from '@inertiajs/react';

export default function Footer() {
    const currentYear = new Date().getFullYear();
    return (
        <>
            {/* Scripts in Head */}
            <Head>
               <script src="/assets/admin/js/lib/jquery-3.7.1.min.js"></script>
                <script src="/assets/admin/js/lib/bootstrap.bundle.min.js"></script>
                <script src="/assets/admin/js/lib/apexcharts.min.js"></script>
                <script src="/assets/admin/js/lib/dataTables.min.js"></script>
                <script src="/assets/admin/js/lib/iconify-icon.min.js"></script>
                <script src="/assets/admin/js/lib/jquery-ui.min.js"></script>
                <script src="/assets/admin/js/lib/jquery-jvectormap-2.0.5.min.js"></script>
                <script src="/assets/admin/js/lib/jquery-jvectormap-world-mill-en.js"></script>
                <script src="/assets/admin/js/lib/magnifc-popup.min.js"></script>
                <script src="/assets/admin/js/lib/slick.min.js"></script>
                <script src="/assets/admin/js/app.js"></script>
                <script src="/assets/admin/js/homeOneChart.js"></script>
            </Head>
            
            {/* Visual footer */}

             <footer className="d-footer">
                <div className="row align-items-center justify-content-between">
                    <div className="col-auto">
                    <p className="mb-0">© { currentYear } IGRCFP. All Rights Reserved.</p>
                    </div>
                    <div className="col-auto">
                    <p className="mb-0">Made by <span className="text-primary-600">Tyneside Innovation</span></p>
                    </div>
                </div>
            </footer>
        </>
    );
}