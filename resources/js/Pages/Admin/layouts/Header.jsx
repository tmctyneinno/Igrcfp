// resources/js/Pages/Admin/layouts/Header.jsx

import { Head } from "@inertiajs/react";

export default function Header({title }) {
    return (
        <Head>
            <meta charSet="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />

            <title>{title}</title>

            {/* Favicon */}
            <link
                rel="shortcut icon"
                href="/assets/images/favicon.png"
                type="image/png"
            />

            {/* Admin CSS */}
            <link rel="stylesheet" href="/assets/admin/css/remixicon.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/bootstrap.min.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/apexcharts.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/dataTables.min.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/editor-katex.min.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/editor.atom-one-dark.min.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/editor.quill.snow.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/flatpickr.min.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/full-calendar.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/jquery-jvectormap-2.0.5.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/magnific-popup.css" />
            <link rel="stylesheet" href="/assets/admin/css/lib/slick.css" />
            <link rel="stylesheet" href="/assets/admin/css/style.css" />
        </Head>
    );
}
