<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'OMD Order')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <script>document.documentElement.dataset.theme = 'light';</script>

    <style>
        :root {
            --primary: #5F9278;
            --primary-dark: #376B52;
            --primary-light: #E7F1E9;
            --purple-soft: #F2F7F2;

            --text: #24352B;
            --text-soft: #718076;
            --muted: #91A097;

            --white: #ffffff;
            --background: #F6F9F6;
            --border: #DCE8DE;

            --success: #4F9568;
            --warning: #D6A24A;
            --danger: #D96C6C;
            --info: #6C9DB5;

            --sidebar-width: 255px;

            --shadow-sm: 0 2px 8px rgba(31, 20, 70, .05);
            --shadow: 0 10px 35px rgba(58, 35, 120, .08);
            --shadow-purple: 0 10px 30px rgba(95,146,120,.20);

            --radius: 16px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background:
                radial-gradient(circle at 85% 5%,
                    rgba(95, 146, 120, .08),
                    transparent 25%),
                var(--background);

            color: var(--text);
            font-size: 14px;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        /* =========================================================
           APP
        ========================================================== */

        .app {
            min-height: 100vh;
        }

        /* =========================================================
           SIDEBAR
        ========================================================== */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;

            width: var(--sidebar-width);

            display: flex;
            flex-direction: column;

            padding: 22px 16px;

            background:
                radial-gradient(circle at 20% 0%,
                    rgba(167, 139, 250, .18),
                    transparent 28%),
                linear-gradient(180deg,
                    #211044 0%,
                    #2d145d 48%,
                    #241046 100%);

            color: white;

            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, .22) transparent;

            box-shadow:
                8px 0 35px rgba(37, 18, 77, .12);

            z-index: 100;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .18);
            border-radius: 999px;
        }

        /* =========================================================
           BRAND
        ========================================================== */

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;

            padding: 5px 10px 25px;
        }

        .brand-logo {
            width: 42px;
            height: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 13px;

            background:
                linear-gradient(135deg,
                    #7FA58C,
                    #5F9278);

            box-shadow:
                0 8px 22px rgba(55,107,82,.22);

            font-size: 17px;
            font-weight: 800;
            color: white;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -.3px;
        }

        .brand-subtitle {
            margin-top: 2px;
            color: rgba(255, 255, 255, .52);
            font-size: 10px;
        }

        /* =========================================================
           NAVIGATION
        ========================================================== */

        .nav-section {
            margin: 20px 10px 8px;

            color: rgba(255, 255, 255, .38);

            font-size: 10px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .nav a {
            position: relative;

            display: flex;
            align-items: center;
            gap: 11px;

            padding: 11px 13px;

            border-radius: 12px;

            color: rgba(255, 255, 255, .70);

            font-size: 13px;
            font-weight: 500;

            transition:
                .2s ease;
        }

        .nav a:hover {
            color: white;

            background:
                rgba(255, 255, 255, .08);

            transform: translateX(2px);
        }

        .nav a.active {
            color: white;

            background:
                linear-gradient(135deg,
                    rgba(95, 146, 120, .96),
                    rgba(55, 107, 82, .88));

            box-shadow:
                0 8px 20px rgba(95, 146, 120, .22);
        }

        .nav-icon {
            width: 19px;
            height: 19px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 14px;
            opacity: .9;
        }

        /* =========================================================
           SIDEBAR BOTTOM
        ========================================================== */

        .sidebar-bottom {
            margin-top: auto;
        }

        .sidebar-user {
            padding: 13px;

            border:
                1px solid rgba(255, 255, 255, .08);

            border-radius: 14px;

            background:
                rgba(255, 255, 255, .055);

            backdrop-filter: blur(10px);

            margin-top: 12px;
        }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 10px;

            margin-bottom: 12px;
        }

        .sidebar-avatar {
            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            border-radius: 50%;

            background:
                linear-gradient(135deg,
                    #7FA58C,
                    #376B52);

            color: white;

            font-size: 12px;
            font-weight: 800;

            box-shadow:
                0 5px 15px rgba(95, 146, 120, .22);
        }

        .sidebar-user-name {
            color: white;
            font-size: 12px;
            font-weight: 700;

            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-user-role {
            margin-top: 3px;

            color: rgba(255, 255, 255, .45);

            font-size: 9px;
            font-weight: 600;
        }

        /* =========================================================
           LOGOUT
        ========================================================== */

        .logout-btn {
            width: 100%;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            min-height: 36px;

            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 9px;

            background: rgba(255, 255, 255, .06);

            color: rgba(255, 255, 255, .72);

            cursor: pointer;

            font-size: 11px;
            font-weight: 650;

            transition:
                .2s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, .14);
            border-color: rgba(248, 113, 113, .25);
            color: #fecaca;

            transform: translateY(-1px);
        }

        .logout-icon {
            font-size: 14px;
        }

        /* =========================================================
           MAIN
        ========================================================== */

        .main {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
        }

        /* =========================================================
           TOPBAR
        ========================================================== */

        .topbar {
            position: sticky;
            top: 0;

            height: 70px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 30px;

            background:
                rgba(255, 255, 255, .86);

            border-bottom:
                1px solid rgba(231, 229, 239, .8);

            backdrop-filter: blur(16px);

            z-index: 50;
        }

        .topbar-title {
            font-size: 17px;
            font-weight: 750;
            letter-spacing: -.3px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* =========================================================
           PROFILE
        ========================================================== */

        .profile {
            display: flex;
            align-items: center;
            gap: 9px;

            padding: 5px 10px 5px 5px;

            border:
                1px solid var(--border);

            border-radius: 30px;

            background: white;

            box-shadow: var(--shadow-sm);
        }

        .profile-avatar {
            width: 32px;
            height: 32px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background:
                linear-gradient(135deg,
                    #7FA58C,
                    #376B52);

            color: white;

            font-size: 11px;
            font-weight: 800;
        }

        .profile-name {
            font-size: 11px;
            font-weight: 700;
        }

        /* =========================================================
           CONTENT
        ========================================================== */

        .content {
            padding: 30px;
            max-width: 1600px;
        }

        /* Equal vertical spacing for standalone cards in OMD and User views.
           Existing grid/flex layouts retain their own gap settings. */
        :where(.content) > :where(.card, .page-card, .repair-card, .history-card, .fy-card, .monitor-card, .dashboard-panel, .order-table-card, .account-table-card, .account-form-card, .profile-card, .form-card) + :where(.card, .page-card, .repair-card, .history-card, .fy-card, .monitor-card, .dashboard-panel, .order-table-card, .account-table-card, .account-form-card, .profile-card, .form-card) {
            margin-top: 18px;
        }
        :where(.content) > :where(.page-head, .page-header) + :where(.card, .page-card, .repair-card, .history-card, .fy-card, .monitor-card, .dashboard-panel, .order-table-card, .account-table-card, .account-form-card, .profile-card, .form-card) {
            margin-top: 18px;
        }

        /* =========================================================
           PAGE HEADER
        ========================================================== */

        .page-head {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            margin-bottom: 24px;
        }

        .page-head h2 {
            margin: 0 0 5px;

            font-size: 24px;
            font-weight: 800;

            letter-spacing: -.6px;
        }

        .page-head h3 {
            margin: 0;

            font-size: 16px;
            font-weight: 750;
        }

        .muted {
            color: var(--text-soft);
            font-size: 12px;
        }

        /* =========================================================
           CARD
        ========================================================== */

        .card {
            background:
                rgba(255, 255, 255, .94);

            border:
                1px solid rgba(231, 229, 239, .9);

            border-radius: var(--radius);

            padding: 20px;

            box-shadow: var(--shadow);

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }

        .card:hover {
            box-shadow:
                0 14px 40px rgba(58, 35, 120, .10);
        }

        /* =========================================================
           STAT CARDS
        ========================================================== */

        .cards {
            display: grid;

            grid-template-columns:
                repeat(4, minmax(0, 1fr));

            gap: 16px;

            margin-bottom: 20px;
        }

        .cards .card {
            position: relative;
            overflow: hidden;
        }

        .cards .card::after {
            content: "";

            position: absolute;

            width: 80px;
            height: 80px;

            right: -25px;
            top: -25px;

            border-radius: 50%;

            background:
                radial-gradient(circle,
                    rgba(95, 146, 120, .12),
                    transparent 70%);
        }

        .stat-label {
            color: var(--text-soft);

            font-size: 11px;
            font-weight: 600;
        }

        .stat-value {
            margin-top: 8px;

            font-size: 27px;
            font-weight: 800;

            letter-spacing: -.8px;
        }

        /* =========================================================
           BUTTON
        ========================================================== */

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            min-height: 38px;

            padding: 0 15px;

            border: 0;
            border-radius: 10px;

            cursor: pointer;

            font-size: 12px;
            font-weight: 700;

            transition:
                transform .18s ease,
                box-shadow .18s ease,
                background .18s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Compact icon-only actions used in table/card action areas. */
        .action-icon-only {
            width: 32px !important;
            min-width: 32px !important;
            height: 32px !important;
            min-height: 32px !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0 !important;
            line-height: 1 !important;
        }

        .action-icon-only i {
            margin: 0 !important;
            font-size: 12px;
            line-height: 1;
            pointer-events: none;
        }

        .btn-primary {
            color: white;

            background:
                linear-gradient(135deg,
                    #7FA58C,
                    #376B52);

            box-shadow:
                0 8px 20px rgba(95,146,120,.20);
        }

        .btn-primary:hover {
            box-shadow:
                0 10px 25px rgba(95, 146, 120, .24);
        }

        .btn-secondary {
            color: #315E49;

            background: var(--primary-light);

            border:
                1px solid #ddd6fe;
        }

        .btn-danger {
            color: #b91c1c;
            background: #fee2e2;
        }

        .btn-success {
            color: #ffffff;
            background: linear-gradient(135deg, #7FA58C, #376B52);
            box-shadow: 0 8px 20px rgba(95,146,120,.18);
        }

        .btn-success:hover {
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(95, 146, 120, .22);
        }

        /* =========================================================
           FORM
        ========================================================== */

        .field {
            margin-bottom: 15px;
        }

        .field label {
            display: block;

            margin-bottom: 7px;

            color: #344054;

            font-size: 12px;
            font-weight: 650;
        }

        .field input,
        .field select,
        .field textarea,
        input,
        select,
        textarea {
            width: 100%;

            min-height: 40px;

            padding: 9px 12px;

            border:
                1px solid #d9d6e5;

            border-radius: 10px;

            outline: none;

            background: white;

            color: var(--text);

            transition:
                border-color .18s ease,
                box-shadow .18s ease;
        }

        .field textarea {
            min-height: 100px;
            resize: vertical;
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus,
        input:focus,
        select:focus,
        textarea:focus {
            border-color: #7FA58C;

            box-shadow:
                0 0 0 4px rgba(95,146,120,.12);
        }

        /* =========================================================
           GRID
        ========================================================== */

        .grid2 {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 18px;
        }

        .grid3 {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 18px;
        }

        /* =========================================================
           TABLE
        ========================================================== */

        .table-wrap {
            width: 100%;

            overflow-x: auto;

            border:
                1px solid var(--border);

            border-radius: 13px;

            background: white;
        }

        .table {
            width: 100%;

            border-collapse: collapse;

            font-size: 12px;
        }

        .table th {
            padding: 12px 13px;

            background:
                #faf9fe;

            color: #667085;

            border-bottom:
                1px solid var(--border);

            font-size: 10px;
            font-weight: 750;

            text-transform: uppercase;
            letter-spacing: .35px;

            text-align: left;
        }

        .table td {
            padding: 12px 13px;

            border-bottom:
                1px solid #eeeef3;

            vertical-align: middle;
        }

        .table tbody tr {
            transition:
                background .15s ease;
        }

        .table tbody tr:hover {
            background:
                #faf9ff;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* =========================================================
           BADGE
        ========================================================== */

        .badge {
            display: inline-flex;
            align-items: center;

            padding: 5px 9px;

            border-radius: 20px;

            font-size: 10px;
            font-weight: 700;
        }

        .badge-submitted {
            color: #376B52;
            background: #E7F1E9;
        }

        .badge-verified {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .badge-in_repair {
            color: #b45309;
            background: #fef3c7;
        }

        .badge-completed {
            color: #047857;
            background: #d1fae5;
        }

        .badge-rejected {
            color: #b91c1c;
            background: #fee2e2;
        }

        /* =========================================================
           EMPTY
        ========================================================== */

        .empty {
            padding: 35px !important;

            color: var(--muted);

            text-align: center;
        }

        /* =========================================================
           ALERT
        ========================================================== */

        .alert {
            padding: 12px 14px;

            margin-bottom: 18px;

            border-radius: 11px;

            font-size: 12px;
        }

        .alert-success {
            color: #047857;

            background: #ecfdf5;

            border:
                1px solid #a7f3d0;
        }

        .alert-danger {
            color: #b91c1c;

            background: #fef2f2;

            border:
                1px solid #fecaca;
        }

        /* =========================================================
           LOGIN / GUEST
           Tidak mengubah struktur login.
        ========================================================== */

        .guest-page {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px;

            background:
                radial-gradient(circle at 85% 15%,
                    rgba(95, 146, 120, .13),
                    transparent 30%),
                radial-gradient(circle at 10% 90%,
                    rgba(95, 146, 120, .08),
                    transparent 25%),
                #F4F8F5;
        }

        .login-page {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;

            padding: 38px;

            background:
                rgba(255, 255, 255, .95);

            border:
                1px solid rgba(255, 255, 255, .9);

            border-radius: 24px;

            box-shadow:
                0 25px 70px rgba(60, 35, 120, .13);

            backdrop-filter: blur(20px);
        }

        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 1100px) {

            .cards {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .grid3 {
                grid-template-columns:
                    repeat(2, 1fr);
            }
        }

        @media (max-width: 800px) {

            :root {
                --sidebar-width: 0px;
            }

            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
            }

            .content {
                padding: 20px;
            }

            .topbar {
                padding: 0 20px;
            }

            .grid2,
            .grid3,
            .cards {
                grid-template-columns: 1fr;
            }

            .page-head {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 500px) {

            .content {
                padding: 15px;
            }

            .login-card {
                padding: 25px 20px;
                border-radius: 18px;
            }

            .profile-name {
                display: none;
            }
        }

        /* =========================
   DATA MASTER DROPDOWN
========================= */

        .nav-dropdown {
            width: 100%;
        }

        .nav-dropdown-toggle {
            width: 100%;
            min-height: 44px;

            display: flex;
            align-items: center;

            gap: 10px;

            padding: 10px 14px;

            box-sizing: border-box;

            color: inherit;
            background: transparent;

            border: none;
            border-radius: 10px;

            cursor: pointer;

            font: inherit;
            text-align: left;
        }

        .nav-dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.07);
        }

        .nav-dropdown-toggle.active {
            background: rgba(95, 146, 120, .15);
        }

        .nav-dropdown-title {
            flex: 1;
        }

        .nav-arrow {
            font-size: 12px;
            transition: transform 0.2s ease;
        }

        .nav-dropdown.open .nav-arrow {
            transform: rotate(180deg);
        }


        /* =========================
   SUB MENU
========================= */

        .nav-dropdown-menu {
            display: none;

            margin-left: 25px;
            padding-left: 15px;

            border-left: 1px solid rgba(255, 255, 255, 0.15);
        }

        .nav-dropdown.open .nav-dropdown-menu {
            display: block;
        }


        /* =========================
   PPIC & PRODUKSI
========================= */

        .nav-dropdown-menu a {
            display: flex;
            align-items: center;

            gap: 9px;

            padding: 9px 12px;

            margin: 3px 0;

            border-radius: 8px;

            color: rgba(255, 255, 255, 0.75);

            text-decoration: none;

            font-size: 13px;

            transition: all 0.2s ease;
        }

        .nav-dropdown-menu a:hover {
            background: rgba(255, 255, 255, 0.07);
            color: white;
        }

        .nav-dropdown-menu a.active {
            background: rgba(95, 146, 120, .20);
            color: white;
            font-weight: 600;
        }

        .nav-sub-icon {
            width: 12px;
            text-align: center;
        }

        .nav-notification {
            margin-left: auto;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #ef4444;
            color: #fff;
            font-size: 9px;
            font-weight: 800;
            line-height: 1;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .10);
        }

        .nav-notification.hidden {
            display: none;
        }

        @keyframes notificationPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, .45);
            }

            70% {
                box-shadow: 0 0 0 7px rgba(239, 68, 68, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        .nav-notification.pulse {
            animation: notificationPulse 1.8s infinite;
        }

        .nav-notification {
            margin-left: auto;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #ef4444;
            color: #fff;
            font-size: 9px;
            font-weight: 800;
            line-height: 1;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .10);
        }

        .nav-notification.hidden {
            display: none;
        }

        @keyframes notificationPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.08);
            }
        }

        .nav-notification.pulse {
            animation: notificationPulse 1.8s infinite;
        }

        /* =========================================================
   PROFILE DROPDOWN
========================================================= */

        .profile-dropdown {
            position: relative;
        }

        .profile-dropdown .profile {
            cursor: pointer;
            border: none;
        }


        /* =========================================================
   POPUP
========================================================= */

        .profile-menu {
            position: absolute;

            top: calc(100% + 10px);
            right: 0;

            width: 255px;

            padding: 8px;

            background: #ffffff;

            border: 1px solid #e8e5f0;
            border-radius: 16px;

            box-shadow:
                0 15px 40px rgba(31, 20, 70, .14);

            display: none;

            z-index: 1000;
        }

        .profile-menu.open {
            display: block;
        }


        /* =========================================================
   PROFILE HEADER
========================================================= */

        .profile-menu-header {
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 8px 8px 12px;
        }

        .profile-menu-avatar {
            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            border-radius: 50%;

            background:
                linear-gradient(135deg,
                    #7FA58C,
                    #376B52);

            color: #fff;

            font-size: 13px;
            font-weight: 800;
        }

        .profile-menu-user {
            min-width: 0;
            flex: 1;
        }

        .profile-menu-name {
            margin-bottom: 3px;

            color: #182033;

            font-size: 12px;
            font-weight: 750;

            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .profile-menu-email {
            color: #667085;

            font-size: 10px;

            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }


        /* =========================================================
   EDIT PROFILE
========================================================= */

        .profile-menu-header .profile-menu-edit {
            width: 30px;
            height: 30px;

            padding: 0;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border: none;
            border-radius: 8px;

            background: transparent;

            color: #3159b7;

            font-size: 15px;

            cursor: pointer;
        }

        .profile-menu-header .profile-menu-edit:hover {
            background: #f3f6ff;
            color: #23479e;
        }


        /* =========================================================
   DIVIDER
========================================================= */

        .profile-menu-divider {
            height: 1px;

            margin: 4px 3px 8px;

            background: #e8e7ed;
        }


        /* =========================================================
   MENU ITEM
========================================================= */

        .profile-menu>a:not(.profile-menu-edit),
        .profile-menu>form button {
            width: 100%;

            min-height: 42px;

            display: flex;
            align-items: center;

            gap: 10px;

            padding: 0 11px;

            border: none;
            border-radius: 10px;

            background: transparent;

            color: #475569;

            font-size: 12px;
            font-weight: 600;

            text-align: left;
            text-decoration: none;

            box-sizing: border-box;

            cursor: pointer;

            transition: .18s ease;
        }

        .profile-menu>a:not(.profile-menu-edit):hover {
            background: #f6f6f8;
            color: #182033;
        }


        /* =========================================================
   ACCOUNT SETTINGS
========================================================= */

        .profile-menu .profile-menu-settings {
            background: #f6f6f8;
            color: #182033;
        }

        .profile-menu .profile-menu-settings:hover {
            background: #f1f1f3;
        }


        /* =========================================================
   MENU ICON
========================================================= */

        .profile-menu-icon {
            width: 20px;
            height: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            color: #3f3f46;

            font-size: 15px;
        }


        /* =========================================================
   LOGOUT
========================================================= */

        .profile-menu form {
            margin: 0;
        }

        .profile-menu>form button {
            color: #4b5563;
        }

        .profile-menu>form button:hover {
            background: #fef2f2;
            color: #dc2626;
        }
    </style>

    <style>
        .theme-toggle {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--white);
            color: var(--text-soft);
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            transition: background .18s ease, color .18s ease, border-color .18s ease, transform .18s ease;
        }

        .theme-toggle:hover {
            color: var(--primary);
            border-color: rgba(95, 146, 120, .22);
            transform: translateY(-1px);
        }

        html[data-theme="dark"] {
            color-scheme: dark;
            --text: #f2f4f8;
            --text-soft: #aab2c2;
            --muted: #7f899a;
            --white: #18231D;
            --background: #101813;
            --border: #304239;
            --primary-light: #294032;
            --purple-soft: #1A2820;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, .18);
            --shadow: 0 12px 34px rgba(0, 0, 0, .24);
            --shadow-purple: 0 10px 30px rgba(95,146,120,.18);
        }

        html[data-theme="dark"] body {
            background:
                radial-gradient(circle at 85% 5%, rgba(95,146,120,.12), transparent 25%),
                var(--background);
            color: var(--text);
        }

        html[data-theme="dark"] .topbar {
            background: rgba(17, 20, 31, .90);
            border-bottom-color: #293B31;
        }

        html[data-theme="dark"] .profile,
        html[data-theme="dark"] .profile-menu,
        html[data-theme="dark"] .card,
        html[data-theme="dark"] .repair-card,
        html[data-theme="dark"] .repair-info-card,
        html[data-theme="dark"] .monitor-card,
        html[data-theme="dark"] .chart-card,
        html[data-theme="dark"] .fy-card,
        html[data-theme="dark"] .history-card,
        html[data-theme="dark"] .account-form-card,
        html[data-theme="dark"] .account-table-card,
        html[data-theme="dark"] .action-card,
        html[data-theme="dark"] .area-card,
        html[data-theme="dark"] .form-card,
        html[data-theme="dark"] .line-card,
        html[data-theme="dark"] .line-table-card,
        html[data-theme="dark"] .master-card,
        html[data-theme="dark"] .model-card,
        html[data-theme="dark"] .ng-card,
        html[data-theme="dark"] .order-table-card,
        html[data-theme="dark"] .page-card,
        html[data-theme="dark"] .plant-card,
        html[data-theme="dark"] .profile-card,
        html[data-theme="dark"] .settings-card,
        html[data-theme="dark"] .sl-card,
        html[data-theme="dark"] .validation-card,
        html[data-theme="dark"] .workflow-card,
        html[data-theme="dark"] .recap-card,
        html[data-theme="dark"] .recap-filter,
        html[data-theme="dark"] .recap-kpi,
        html[data-theme="dark"] .modal-box,
        html[data-theme="dark"] .sl-modal,
        html[data-theme="dark"] .fy-modal,
        html[data-theme="dark"] .account-edit-modal,
        html[data-theme="dark"] .validation-modal {
            background: #18231D !important;
            border-color: #304239 !important;
            box-shadow: 0 12px 34px rgba(0, 0, 0, .22) !important;
        }

        html[data-theme="dark"] .profile-menu-name,
        html[data-theme="dark"] .topbar-title,
        html[data-theme="dark"] .page-head h2,
        html[data-theme="dark"] .page-head h3,
        html[data-theme="dark"] .card-title,
        html[data-theme="dark"] .repair-card-title,
        html[data-theme="dark"] .recap-head h2,
        html[data-theme="dark"] .recap-kpi strong {
            color: #f8fafc !important;
        }

        html[data-theme="dark"] input,
        html[data-theme="dark"] select,
        html[data-theme="dark"] textarea {
            background: #131D17 !important;
            color: #eef2f7 !important;
            border-color: #34483C !important;
        }

        html[data-theme="dark"] input::placeholder,
        html[data-theme="dark"] textarea::placeholder {
            color: #778195 !important;
        }

        html[data-theme="dark"] table {
            color: #dbe1ea;
        }

        html[data-theme="dark"] table th,
        html[data-theme="dark"] .recap-table th {
            background: #202E26 !important;
            color: #cbd5e1 !important;
            border-color: #304239 !important;
        }

        html[data-theme="dark"] table td,
        html[data-theme="dark"] .recap-table td {
            color: #d9e0ea !important;
            border-color: #28382F !important;
        }

        html[data-theme="dark"] .recap-btn-secondary {
            background: #202E26;
            border-color: #34483C;
            color: #dbe1ea;
        }

        html[data-theme="dark"] .modal-overlay,
        html[data-theme="dark"] .sl-modal-backdrop,
        html[data-theme="dark"] .fy-modal-backdrop {
            background: rgba(4, 6, 12, .72) !important;
        }

        html[data-theme="dark"] .theme-toggle {
            background: #18231D;
            border-color: #304239;
            color: #d8deea;
        }
    </style>

    <style>
        /* =========================================================
           OMIRA VISUAL SYSTEM V2 — typography + light/dark surfaces
        ========================================================== */
        :root {
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --surface-hover: #F4F8F5;
            --surface-active: #EAF3EC;
            --border-soft: #edf0f4;
            --input-bg: #ffffff;
            --input-border: #dbe2ea;
            --table-head-bg: #F4F8F5;
            --table-hover-bg: #faf9ff;

            --font-page-title: 20px;
            --font-section-title: 16px;
            --font-card-title: 13px;
            --font-body: 12px;
            --font-label: 11px;
            --font-table-head: 11px;
            --font-table-body: 12px;
        }

        body {
            font-size: 13px;
            line-height: 1.5;
        }

        body :is(
            .page-head h2,
            .repair-head h2,
            .monitor-head h2,
            .fy-header h2,
            .sl-head h2,
            .account-page-title,
            .dashboard-welcome h2,
            .profile-head h2,
            .recap-head h2,
            .plant-dashboard-header h4
        ) {
            font-size: var(--font-page-title) !important;
            font-weight: 800 !important;
            line-height: 1.25 !important;
        }

        body :is(
            .card-title,
            .repair-card-title,
            .dashboard-panel-title,
            .account-form-header h3,
            .account-table-header h3,
            .fy-card-head h3,
            .modal-header h4,
            .sl-modal-title,
            .fy-modal-head h3
        ) {
            font-size: var(--font-section-title) !important;
            font-weight: 800 !important;
            line-height: 1.3 !important;
        }

        body table th {
            font-size: var(--font-table-head);
            font-weight: 700;
        }

        body table td {
            font-size: var(--font-table-body);
            font-weight: 400;
        }

        body :is(.field label, .form-label, .account-field label, .account-edit-field label, .fy-field label, .sl-field label) {
            font-size: var(--font-label);
            font-weight: 700;
        }

        body :is(button, .btn, [class*="-btn"]) {
            font-weight: 700;
        }

        /* icon-only theme switch */
        .theme-toggle {
            width: 34px !important;
            height: 34px !important;
            padding: 0 !important;
            border: 0 !important;
            border-radius: 50% !important;
            background: transparent !important;
            box-shadow: none !important;
            color: var(--text-soft) !important;
            font-size: 18px !important;
            transition: color .18s ease, transform .18s ease, opacity .18s ease !important;
        }

        .theme-toggle:hover {
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            color: var(--primary) !important;
            transform: scale(1.08) !important;
        }

        html[data-theme="dark"] {
            --surface: #18231D;
            --surface-soft: #202E26;
            --surface-hover: #25362C;
            --surface-active: #294032;
            --border-soft: #28382F;
            --input-bg: #131D17;
            --input-border: #34483C;
            --table-head-bg: #202E26;
            --table-hover-bg: #223129;
        }

        html[data-theme="dark"] .theme-toggle {
            color: #f6c453 !important;
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
        }

        html[data-theme="dark"] .sidebar {
            background:
                radial-gradient(circle at 20% 0%, rgba(95, 146, 120, .10), transparent 28%),
                linear-gradient(180deg, #15231B 0%, #18281F 48%, #142119 100%);
        }

        html[data-theme="dark"] :is(
            .page-card,
            .card,
            .create-wrap,
            .dashboard-kpi,
            .dashboard-panel,
            .monitor-card,
            .chart-card,
            .repair-card,
            .repair-info-card,
            .repair-table-scroll,
            .repair-table,
            .review-table,
            .qty-table,
            .summary-table,
            .recent-table,
            .order-table-card,
            .order-table,
            .orders-table,
            .history-card,
            .history-filter-box,
            .calendar-popup,
            .model-card,
            .model-editor,
            .master-card,
            .line-card,
            .line-table-card,
            .plant-card,
            .ng-card,
            .area-card,
            .form-card,
            .sl-card,
            .sl-toolbar,
            .sl-modal,
            .fy-card,
            .fy-modal,
            .account-form-card,
            .account-table-card,
            .account-edit-modal,
            .profile-card,
            .profile-hero,
            .settings-card,
            .validation-card,
            .validation-modal,
            .workflow-card,
            .modal-box,
            .product-list-box,
            .popup-product-item,
            .feedback-popover,
            .chart-abnormality-popover,
            .recap-card,
            .recap-filter,
            .recap-line-menu,
            .login-container,
            .login-form-panel
        ) {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }

        html[data-theme="dark"] :is(
            .history-date-input input,
            .history-date-picker input,
            .history-per-page select,
            .history-per-page-label select,
            .history-search input,
            .history-search-input input,
            .history-line-filter select,
            .history-line-filter-inline select,
            .search-box input,
            .account-filter-field input,
            .account-filter-field select,
            .account-field input,
            .account-field select,
            .account-edit-field input,
            .account-edit-field select,
            .profile-field input,
            .fy-field input,
            .sl-field input,
            .sl-field select,
            .sl-field textarea,
            .monitor-field select,
            .monitor-field input,
            .abnormality-form textarea,
            .qty-input,
            .ng-input,
            .keterangan-input,
            .mismatch-field
        ) {
            background: var(--input-bg) !important;
            color: var(--text) !important;
            border-color: var(--input-border) !important;
        }

        html[data-theme="dark"] :is(
            .history-filter-btn-reset,
            .calendar-nav,
            .calendar-reset,
            .history-pagination-btn,
            .recap-btn-secondary,
            .dialog-cancel,
            .btn-reset,
            .fy-btn.cancel
        ) {
            background: var(--surface-soft) !important;
            border-color: var(--border) !important;
            color: var(--text-soft) !important;
        }

        html[data-theme="dark"] :is(
            .page-head h2,
            .page-head h3,
            .repair-head h2,
            .monitor-head h2,
            .fy-header h2,
            .sl-head h2,
            .account-page-title,
            .dashboard-welcome h2,
            .profile-head h2,
            .recap-head h2,
            .card-title,
            .repair-card-title,
            .dashboard-panel-title,
            .account-form-header h3,
            .account-table-header h3,
            .fy-card-head h3,
            .fy-modal-head h3,
            .sl-modal-title,
            .modal-header h4,
            .order-number,
            .order-model-product strong,
            .repair-model,
            .model-text,
            .ng-name,
            .product-list-header h4,
            .popup-product-name,
            .profile-hero-name,
            .profile-info-value,
            .account-user-name,
            .info-item strong,
            .timeline-item strong
        ) {
            color: var(--text) !important;
        }

        html[data-theme="dark"] :is(
            .page-head p,
            .fy-header p,
            .dashboard-welcome p,
            .profile-head p,
            .account-table-header p,
            .card-subtitle,
            .helper-text,
            .history-info,
            .order-date,
            .repair-info-description
        ) {
            color: var(--text-soft) !important;
        }

        html[data-theme="dark"] :is(
            .orders-table th,
            .order-table th,
            .repair-table th,
            .repair-detail-table th,
            .review-table th,
            .qty-table th,
            .summary-table th,
            .recent-table th,
            .account-table th,
            .fy-table th,
            .recap-table th
        ) {
            background: var(--table-head-bg) !important;
            color: #cbd5e1 !important;
            border-color: var(--border) !important;
        }

        html[data-theme="dark"] :is(
            .orders-table td,
            .order-table td,
            .repair-table td,
            .repair-detail-table td,
            .review-table td,
            .qty-table td,
            .summary-table td,
            .recent-table td,
            .account-table td,
            .fy-table td,
            .recap-table td
        ) {
            color: #dbe1ea !important;
            border-color: var(--border-soft) !important;
        }

        html[data-theme="dark"] :is(.orders-table tbody tr:hover, .order-table tbody tr:hover, .account-table tbody tr:hover) {
            background: var(--table-hover-bg) !important;
        }

        html[data-theme="dark"] .calendar-nav:hover,
        html[data-theme="dark"] .calendar-day:hover:not(:disabled),
        html[data-theme="dark"] .calendar-day.in-range,
        html[data-theme="dark"] .history-search-clear:hover {
            background: var(--surface-active) !important;
            color: #c4b5fd !important;
        }

        html[data-theme="dark"] .calendar-day {
            color: var(--text) !important;
        }

        html[data-theme="dark"] .calendar-footer {
            border-color: var(--border-soft) !important;
        }

        html[data-theme="dark"] .guest-page,
        html[data-theme="dark"] .login-page {
            background: #101813 !important;
        }

        html[data-theme="dark"] .login-visual {
            background:
                radial-gradient(circle at 15% 15%, rgba(95,146,120,.20), transparent 29%),
                radial-gradient(circle at 88% 82%, rgba(95, 146, 120, .10), transparent 30%),
                linear-gradient(145deg, #181a24 0%, #11141d 52%, #18231D 100%) !important;
        }

        html[data-theme="dark"] :is(.login-title, .login-footer strong) {
            color: var(--text) !important;
        }

        html[data-theme="dark"] :is(.login-subtitle, .login-footer) {
            color: var(--text-soft) !important;
        }

        html[data-theme="dark"] .login-input {
            background: var(--input-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--text) !important;
        }

        html[data-theme="dark"] .modal-overlay,
        html[data-theme="dark"] .sl-modal-backdrop,
        html[data-theme="dark"] .fy-modal-backdrop {
            background: rgba(4, 6, 12, .76) !important;
        }
    </style>

    <style>
        /* Additional per-page dark compatibility after full menu audit */
        html[data-theme="dark"] :is(
            .machine-screen,
            .status-panel,
            .stat-box,
            .ng-edit-box,
            .account-pagination,
            .account-empty-icon,
            .monitor-pill,
            .fy-note,
            .repair-dialog,
            .repair-dialog textarea,
            .top-info-item,
            .form-notice
        ) {
            background: var(--surface-soft) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }

        html[data-theme="dark"] :is(.modern-table thead, .modern-table thead th) {
            background: var(--table-head-bg) !important;
            color: #cbd5e1 !important;
        }

        html[data-theme="dark"] :is(.target-reason-card, .alert-card.scrap-alert) {
            background: #2b2414 !important;
            border-color: #6f5517 !important;
            color: #fde68a !important;
        }

        html[data-theme="dark"] .repair-info-slate {
            background: #202E26 !important;
            border-color: #34483C !important;
            color: #dbe1ea !important;
        }

        html[data-theme="dark"] .repair-info-amber {
            background: #2b2414 !important;
            border-color: #6f5517 !important;
            color: #fde68a !important;
        }

        html[data-theme="dark"] .chart-grid {
            opacity: .34;
        }

        html[data-theme="dark"] .chart-grid::before,
        html[data-theme="dark"] .chart-grid::after {
            border-color: #34483C !important;
        }

        html[data-theme="dark"] :is(.chart-bar-value, .legend-item, .chart-month-label) {
            color: #dbe1ea !important;
        }

        html[data-theme="dark"] .chart-abnormality-reason {
            background: var(--surface-soft) !important;
            color: var(--text) !important;
        }

        body :is(
            .orders-table,
            .order-table,
            .repair-table,
            .repair-detail-table,
            .review-table,
            .qty-table,
            .summary-table,
            .recent-table,
            .account-table,
            .fy-table,
            .recap-table
        ) th {
            font-size: var(--font-table-head) !important;
            font-weight: 700 !important;
        }

        body :is(
            .orders-table,
            .order-table,
            .repair-table,
            .repair-detail-table,
            .review-table,
            .qty-table,
            .summary-table,
            .recent-table,
            .account-table,
            .fy-table,
            .recap-table
        ) td {
            font-size: var(--font-table-body) !important;
            font-weight: 400;
        }

        body :is(.model-cell, .repair-model, .account-user-name, .order-number, .popup-product-name) {
            font-weight: 700 !important;
        }
    </style>

    <style>
        html[data-theme="dark"] :is(.ng-form-group input, .row-action, .account-page-btn, .repair-dialog-close) {
            background: var(--surface-soft) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }

        html[data-theme="dark"] .target-marker {
            background: #18243b !important;
            color: #bfdbfe !important;
            border-color: #31527a !important;
        }

        html[data-theme="dark"] .scrap-item:not(.warning):not(.exceeded) {
            background: var(--surface-soft) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }
    </style>

    <style>
        /* =========================================================
           OMIRA SOFT GREEN — authoritative visual theme
           Visual only: no workflow / layout / business-logic changes.
        ========================================================== */
        :root {
            --primary: #5F9278;
            --primary-dark: #376B52;
            --primary-light: #E7F1E9;
            --purple-soft: #F2F7F2;
            --page-bg: #F6F9F6;
            --surface: #FFFFFF;
            --surface-soft: #F2F7F2;
            --surface-hover: #EDF5EF;
            --surface-active: #E7F1E9;
            --text: #24352B;
            --text-soft: #718076;
            --muted: #91A097;
            --border: #DCE8DE;
            --border-soft: #E8F0E9;
            --input-bg: #FFFFFF;
            --input-border: #DCE8DE;
            --table-head-bg: #F2F7F2;
            --table-hover-bg: #F7FBF8;
            --shadow-sm: 0 2px 10px rgba(47, 82, 64, .05);
            --shadow: 0 12px 34px rgba(47, 82, 64, .08);
            --shadow-purple: 0 10px 28px rgba(95, 146, 120, .18);
        }

        body {
            color: var(--text);
            background-color: var(--page-bg);
            background-image:
                radial-gradient(circle at 88% 4%, rgba(95,146,120,.10), transparent 27%),
                radial-gradient(circle at 8% 92%, rgba(231,241,233,.72), transparent 30%),
                linear-gradient(135deg, rgba(255,255,255,.96), rgba(242,247,242,.88)),
                radial-gradient(circle, rgba(55,107,82,.035) 1px, transparent 1px);
            background-size: auto, auto, auto, 22px 22px;
            background-attachment: fixed;
        }

        .sidebar {
            background: linear-gradient(180deg, #EEF6F0 0%, #E7F1E9 48%, #F2F7F2 100%) !important;
            color: #41554A !important;
            border-right: 1px solid #D7E5DA;
            box-shadow: 8px 0 30px rgba(55,107,82,.06) !important;
            scrollbar-color: rgba(55,107,82,.22) transparent !important;
        }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(55,107,82,.18) !important; }

        .brand {
            display: block !important;
            padding: 8px 10px 24px !important;
        }
        .brand-logo { display: none !important; }
        .brand-text { display: block !important; }
        .brand-title {
            color: #2F5E48 !important;
            font-size: 24px !important;
            line-height: 1.05 !important;
            font-weight: 850 !important;
            letter-spacing: -.45px !important;
        }
        .brand-subtitle {
            max-width: 205px;
            margin-top: 7px !important;
            color: #708178 !important;
            font-size: 9.5px !important;
            line-height: 1.45 !important;
            font-weight: 550 !important;
        }
        .nav-section { color: #8A9A91 !important; }
        .nav a {
            color: #52645A !important;
            background: transparent !important;
            box-shadow: none !important;
        }
        .nav a:hover {
            color: #315F49 !important;
            background: rgba(255,255,255,.58) !important;
            transform: none !important;
        }
        .nav a.active {
            color: #315F49 !important;
            background: #FFFFFF !important;
            box-shadow: 0 5px 16px rgba(55,107,82,.09) !important;
        }
        .nav-icon { color: currentColor !important; }
        .sidebar-user {
            background: rgba(255,255,255,.55) !important;
            border-color: rgba(95,146,120,.16) !important;
            backdrop-filter: blur(8px);
        }
        .sidebar-user-name { color: #2C4136 !important; }
        .sidebar-user-role { color: #7B8A82 !important; }
        .sidebar-avatar {
            background: linear-gradient(135deg, #7FAA90, #4F8368) !important;
            box-shadow: 0 5px 15px rgba(55,107,82,.16) !important;
        }
        .logout-btn {
            color: #5F7067 !important;
            border-color: #D7E5DA !important;
            background: rgba(255,255,255,.65) !important;
        }
        .logout-btn:hover {
            color: #B44E4E !important;
            border-color: rgba(217,108,108,.30) !important;
            background: #FFF5F5 !important;
        }

        .topbar {
            background: rgba(255,255,255,.88) !important;
            border-bottom-color: #E1EAE3 !important;
            backdrop-filter: blur(14px);
        }

        .btn-primary,
        button.btn-primary,
        .dialog-confirm,
        .dialog-submit:not(:disabled),
        .sl-btn-success,
        .account-edit-save {
            background: #5F9278 !important;
            border-color: #5F9278 !important;
            color: #FFFFFF !important;
            box-shadow: 0 6px 16px rgba(95,146,120,.17) !important;
        }
        .btn-primary:hover,
        button.btn-primary:hover,
        .dialog-confirm:hover,
        .dialog-submit:not(:disabled):hover,
        .sl-btn-success:hover,
        .account-edit-save:hover {
            background: #376B52 !important;
            border-color: #376B52 !important;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #6D9D82 !important;
            box-shadow: 0 0 0 3px rgba(95,146,120,.12) !important;
            outline: none;
        }
        table th { background-color: var(--table-head-bg); color: #405148; }
        tbody tr:hover td { background-color: var(--table-hover-bg); }

        .theme-toggle {
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            color: #5E6F66 !important;
        }
        .theme-toggle:hover { color: #376B52 !important; transform: scale(1.08) !important; }

        html[data-theme="dark"] {
            color-scheme: dark;
            --primary: #7DA88D;
            --primary-dark: #A4C6AE;
            --primary-light: #24372D;
            --purple-soft: #1A2820;
            --page-bg: #101813;
            --background: #101813;
            --surface: #18231D;
            --surface-soft: #1E2C24;
            --surface-hover: #25362C;
            --surface-active: #294032;
            --text: #E8F1EB;
            --text-soft: #A9B8AF;
            --muted: #819188;
            --white: #18231D;
            --border: #304239;
            --border-soft: #28382F;
            --input-bg: #131D17;
            --input-border: #34483C;
            --table-head-bg: #202E26;
            --table-hover-bg: #223129;
            --shadow-sm: 0 2px 8px rgba(0,0,0,.20);
            --shadow: 0 12px 34px rgba(0,0,0,.28);
            --shadow-purple: 0 10px 26px rgba(0,0,0,.24);
        }
        html[data-theme="dark"] body {
            background-color: #101813 !important;
            background-image:
                radial-gradient(circle at 88% 4%, rgba(125,168,141,.10), transparent 27%),
                linear-gradient(135deg, #101813, #121C16),
                radial-gradient(circle, rgba(180,210,190,.025) 1px, transparent 1px) !important;
            background-size: auto, auto, 22px 22px !important;
        }
        html[data-theme="dark"] .sidebar {
            background: linear-gradient(180deg, #15231B 0%, #18281F 52%, #142119 100%) !important;
            color: #D8E6DC !important;
            border-right-color: #293B31 !important;
        }
        html[data-theme="dark"] .brand-title { color: #E4EFE7 !important; }
        html[data-theme="dark"] .brand-subtitle,
        html[data-theme="dark"] .nav-section { color: #8FA298 !important; }
        html[data-theme="dark"] .nav a { color: #B5C4BB !important; }
        html[data-theme="dark"] .nav a:hover { background: rgba(255,255,255,.055) !important; color: #E2EEE6 !important; }
        html[data-theme="dark"] .nav a.active {
            color: #DDF0E3 !important;
            background: #22352A !important;
            box-shadow: 0 5px 16px rgba(0,0,0,.18) !important;
        }
        html[data-theme="dark"] .sidebar-user { background: rgba(255,255,255,.035) !important; border-color: #31473A !important; }
        html[data-theme="dark"] .sidebar-user-name { color: #E5EEE8 !important; }
        html[data-theme="dark"] .sidebar-user-role { color: #91A49A !important; }
        html[data-theme="dark"] .logout-btn { background: rgba(255,255,255,.03) !important; border-color: #31473A !important; color: #B5C4BB !important; }
        html[data-theme="dark"] .topbar { background: rgba(17,26,21,.90) !important; border-bottom-color: #293B31 !important; }
        html[data-theme="dark"] .theme-toggle { color: #E2C56B !important; }
        html[data-theme="dark"] :is(.card,.repair-card,.repair-info-card,.monitor-card,.chart-card,.fy-card,.history-card,.account-form-card,.account-table-card,.action-card,.area-card,.form-card,.line-card,.line-table-card,.master-card,.model-card,.ng-card,.order-table-card,.page-card,.plant-card,.profile-card,.settings-card,.sl-card,.validation-card,.workflow-card,.recap-card,.recap-filter,.modal-box,.sl-modal,.fy-modal,.account-edit-modal,.validation-modal,.dashboard-kpi,.dashboard-panel) {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            box-shadow: 0 12px 34px rgba(0,0,0,.20) !important;
        }
        html[data-theme="dark"] input,
        html[data-theme="dark"] select,
        html[data-theme="dark"] textarea {
            background: var(--input-bg) !important;
            color: var(--text) !important;
            border-color: var(--input-border) !important;
        }
        html[data-theme="dark"] table th { background: var(--table-head-bg) !important; color: #D4E1D8 !important; border-color: var(--border) !important; }
        html[data-theme="dark"] table td { color: #D7E2DA !important; border-color: var(--border-soft) !important; }
        html[data-theme="dark"] tbody tr:hover td { background: var(--table-hover-bg) !important; }

        /* OMIRA Sidebar — clean white / thin outline / soft-green selection.
           Scoped to sidebar; preserves menu DOM, routes and permissions. */
        html:not([data-theme="dark"]) .sidebar {
            background: #FFFFFF !important;
            color: #111827 !important;
            border-right: 1px solid #E8ECE9 !important;
            box-shadow: 3px 0 18px rgba(17,24,39,.025) !important;
            scrollbar-color: #DDE5DF transparent !important;
        }
        html:not([data-theme="dark"]) .sidebar .brand-title { color: #172A20 !important; }
        html:not([data-theme="dark"]) .sidebar .brand-subtitle { color: #343F38 !important; }
        html:not([data-theme="dark"]) .sidebar .nav-section { color: #5C665F !important; }
        .sidebar .nav a,
        .sidebar .nav-dropdown-toggle {
            min-height: 40px;
            border-radius: 9px;
            font-weight: 500;
            transition: background-color .16s ease, color .16s ease;
        }
        .sidebar .nav-icon { width: 20px; height: 20px; flex: 0 0 20px; opacity: 1; }
        .sidebar .sidebar-outline-icon { display: block; width: 18px; height: 18px; flex-shrink: 0; }
        .sidebar .nav-sub-icon { width: 18px; flex: 0 0 18px; display: flex; align-items: center; justify-content: center; }
        .sidebar .nav-sub-icon .sidebar-outline-icon { width: 16px; height: 16px; }
        .sidebar .nav-arrow .sidebar-outline-icon { width: 16px; height: 16px; }
        html:not([data-theme="dark"]) .sidebar .nav a,
        html:not([data-theme="dark"]) .sidebar .nav-dropdown-toggle,
        html:not([data-theme="dark"]) .sidebar .nav-dropdown-menu a {
            color: #171B19 !important;
            background: transparent !important;
            box-shadow: none !important;
        }
        html:not([data-theme="dark"]) .sidebar .nav a:hover,
        html:not([data-theme="dark"]) .sidebar .nav-dropdown-toggle:hover,
        html:not([data-theme="dark"]) .sidebar .nav-dropdown-menu a:hover {
            color: #102D1D !important;
            background: #F3F7F4 !important;
            transform: none !important;
        }
        html:not([data-theme="dark"]) .sidebar .nav a.active,
        html:not([data-theme="dark"]) .sidebar .nav-dropdown-toggle.active,
        html:not([data-theme="dark"]) .sidebar .nav-dropdown-menu a.active {
            background: #E8F4EC !important;
            color: #17452C !important;
            font-weight: 700 !important;
            box-shadow: none !important;
        }
        html:not([data-theme="dark"]) .sidebar .nav-dropdown-menu {
            border-left: 1px solid #E4EBE6 !important;
        }
        html:not([data-theme="dark"]) .sidebar .sidebar-user {
            background: #FFFFFF !important;
            border: 1px solid #E7ECE8 !important;
            backdrop-filter: none;
            box-shadow: 0 2px 9px rgba(17,24,39,.025) !important;
        }
        html:not([data-theme="dark"]) .sidebar .sidebar-user-name { color: #111827 !important; }
        html:not([data-theme="dark"]) .sidebar .sidebar-user-role { color: #4B5563 !important; }
        /* Maintain existing dark theme, with understated green active state. */
        html[data-theme="dark"] .sidebar .nav-dropdown-menu a { color: #D3E0D6 !important; }
        html[data-theme="dark"] .sidebar .nav-dropdown-toggle { color: #D3E0D6 !important; }
        html[data-theme="dark"] .sidebar .nav-dropdown-menu { border-left-color: #35493C !important; }
        html[data-theme="dark"] .sidebar .nav-dropdown-toggle:hover,
        html[data-theme="dark"] .sidebar .nav-dropdown-menu a:hover { background: #21352A !important; color: #F1F8F3 !important; }
        html[data-theme="dark"] .sidebar .nav-dropdown-toggle.active,
        html[data-theme="dark"] .sidebar .nav-dropdown-menu a.active { background: #294736 !important; color: #E5F5EA !important; font-weight: 700 !important; }
    </style>

</head>

<body>

    {{-- =========================================================
         GUEST / LOGIN
    ========================================================== --}}

    @if (View::hasSection('guest'))

        <main class="guest-page">
            @yield('guest')
        </main>
    @else
        {{-- =========================================================
             APPLICATION
        ========================================================== --}}

        <div class="app">

            {{-- =====================================================
                 SIDEBAR
            ====================================================== --}}

            <aside class="sidebar">

                {{-- BRAND --}}
                <div class="brand">

                    <div class="brand-text">

                        <div class="brand-title">
                            OMIRA
                        </div>

                        <div class="brand-subtitle">
                            OMD Integrated Repair Application
                        </div>

                    </div>

                </div>


                {{-- MAIN MENU --}}
                <div class="nav-section">
                    Main Menu
                </div>

                <nav class="nav">

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

                        <span class="nav-icon"><svg class="sidebar-outline-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 10 9-7 9 7"/><path d="M5 9v12h14V9"/><path d="M9 21v-7h6v7"/></svg></span>

                        <span>
                            Dashboard
                        </span>

                    </a>


                    {{-- USER --}}
                    @if (auth()->user()->role === 'user')
                        <a href="{{ route('user.orders.index') }}"
                            class="{{ (request()->routeIs('user.orders.index') || request()->routeIs('user.orders.show')) ? 'active' : '' }}">
                            <span class="nav-icon"><svg class="sidebar-outline-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 7 9-4 9 4-9 4-9-4Z"/><path d="M3 7v10l9 4 9-4V7"/><path d="M12 11v10"/></svg></span>
                            <span>Order Repair Box</span>
                            <span id="userOrderNotification" class="nav-notification hidden">0</span>
                        </a>

                        <a href="{{ route('user.orders.history') }}"
                            class="{{ (request()->routeIs('user.orders.history') || request()->routeIs('user.orders.history.show')) ? 'active' : '' }}">
                            <span class="nav-icon"><svg class="sidebar-outline-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 3v6h6"/><path d="M12 7v5l3 2"/></svg></span>
                            <span>History Order Repair Box</span>
                        </a>


                        
                    @else
                        {{-- OMD --}}

                        <a href="{{ route('omd.orders.index') }}"
                            class="{{ (request()->routeIs('omd.orders.index') || request()->routeIs('omd.orders.show')) ? 'active' : '' }}">

                            <span class="nav-icon"><svg class="sidebar-outline-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 7 9-4 9 4-9 4-9-4Z"/><path d="M3 7v10l9 4 9-4V7"/><path d="M12 11v10"/></svg></span>

                            <span>
                                Order Repair Box
                            </span>

                            <span id="omdOrderNotification" class="nav-notification hidden">
                                0
                            </span>

                        </a>

                        <a href="{{ route('omd.orders.history') }}"
                            class="{{ (request()->routeIs('omd.orders.history') || request()->routeIs('omd.orders.history.show')) ? 'active' : '' }}">

                            <span class="nav-icon"><svg class="sidebar-outline-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 3v6h6"/><path d="M12 7v5l3 2"/></svg></span>

                            <span>
                                History Order Repair Box
                            </span>

                        </a>

                        
                    @endif

                </nav>


                {{-- =================================================
                     MANAGEMENT
                ================================================== --}}

                @if (auth()->user()->role !== 'user')

                    <div class="nav-section">
                        Management
                    </div>

                    <nav class="nav">
                        {{-- Data Master hanya Leader --}}
                        @if (auth()->user()->role === 'omd')
                            @php
                                $dataMasterActive = request()->routeIs('omd.master.*')
                                    || request()->routeIs('omd.scrap-limits.*')
                                    || request()->routeIs('omd.targets.*');
                            @endphp

                            <div class="nav-dropdown {{ $dataMasterActive ? 'open' : '' }}">
                                <div class="nav-dropdown-toggle {{ $dataMasterActive ? 'active' : '' }}"
                                    onclick="this.parentElement.classList.toggle('open')">
                                    <span class="nav-icon"><svg class="sidebar-outline-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.7 4 3 9 3s9-1.3 9-3V5"/><path d="M3 12c0 1.7 4 3 9 3s9-1.3 9-3"/></svg></span>
                                    <span class="nav-dropdown-title">Data Master</span>
                                    <span class="nav-arrow"><svg class="sidebar-outline-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg></span>
                                </div>

                                <div class="nav-dropdown-menu">
                                    <a href="{{ route('omd.master.line') }}"
                                        class="{{ request()->routeIs('omd.master.line') ? 'active' : '' }}">
                                        <span>Plant & Line</span>
                                    </a>

                                    <a href="{{ route('omd.master.model-product') }}"
                                        class="{{ request()->routeIs('omd.master.model-product') ? 'active' : '' }}">
                                        <span>Model Box</span>
                                    </a>

                                    <a href="{{ route('omd.master.ng-type') }}"
                                        class="{{ request()->routeIs('omd.master.ng-type') ? 'active' : '' }}">
                                        <span>Jenis NG</span>
                                    </a>

                                    <a href="{{ route('omd.scrap-limits.index') }}"
                                        class="{{ request()->routeIs('omd.scrap-limits.*') ? 'active' : '' }}">
                                        <span>Scrap Limit Produk</span>
                                    </a>

                                    <a href="{{ route('omd.targets.index') }}"
                                        class="{{ request()->routeIs('omd.targets.*') ? 'active' : '' }}">
                                        <span>Fiscal Year</span>
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Report Repair Order hanya untuk OMD --}}
                        @if (auth()->user()->role === 'omd')
                            <a href="{{ route('omd.recap') }}"
                                class="{{ request()->routeIs('omd.recap*') ? 'active' : '' }}">
                                <span class="nav-icon"><svg class="sidebar-outline-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="m7 14 4-4 4 3 5-7"/></svg></span>
                                <span>Report</span>
                            </a>
                        @endif

                        {{-- Manajemen Akun hanya Leader --}}
                        @if (auth()->user()->role === 'omd')
                            <a href="{{ route('omd.users.index') }}"
                                class="{{ request()->routeIs('omd.users.*') ? 'active' : '' }}">

                                <span class="nav-icon"><svg class="sidebar-outline-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 20v-2a6 6 0 0 1 9-5"/><circle cx="17" cy="16" r="3"/><path d="M17 10v2m0 8v2m-6-6h2m8 0h2m-10.2-4.2 1.4 1.4m5.6 5.6 1.4 1.4m0-8.4-1.4 1.4m-5.6 5.6-1.4 1.4"/></svg></span>

                                <span>
                                    Manajemen Akun
                                </span>

                            </a>
                        @endif

                    </nav>

                @endif


                {{-- =================================================
                     SIDEBAR BOTTOM
                ================================================== --}}

                <div class="sidebar-bottom">

                    <div class="sidebar-user">

                        <div class="sidebar-user-info">

                            <div class="sidebar-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>

                            <div style="min-width:0;">

                                <div class="sidebar-user-name">
                                    {{ auth()->user()->name }}
                                </div>

                                <div class="sidebar-user-role">

                                    @if (auth()->user()->role === 'user')
                                        {{ auth()->user()->line?->name ?? '-' }}
                                    @else
                                        OMD
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </aside>


            {{-- =====================================================
                 MAIN
            ====================================================== --}}

            <main class="main">

                {{-- TOPBAR --}}
                <header class="topbar">

                    <div class="topbar-title">
                        @yield('header', 'Dashboard')
                    </div>


                    <div class="topbar-right">

                        <div class="profile-dropdown">

                            <button type="button" class="profile" id="profileToggle">

                                <div class="profile-avatar">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>

                                <div class="profile-name">
                                    {{ explode(' ', trim(auth()->user()->name))[0] }}
                                </div>

                            </button>


                            <div class="profile-menu" id="profileMenu">

                                {{-- PROFILE HEADER --}}
                                <div class="profile-menu-header">

                                    <div class="profile-menu-avatar">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>


                                    <div class="profile-menu-user">

                                        <div class="profile-menu-name">
                                            {{ auth()->user()->name }}
                                        </div>

                                        <div class="profile-menu-email">
                                            {{ auth()->user()->email }}
                                        </div>

                                    </div>


                                    {{-- EDIT PROFILE --}}
                                    <a href="{{ route('profile.index') }}" class="profile-menu-edit"
                                        title="Edit Profil">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                </div>


                                <div class="profile-menu-divider"></div>


                                {{-- ACCOUNT SETTINGS --}}
                                <a href="{{ route('profile.index') }}" class="profile-menu-settings">

                                    <span class="profile-menu-icon">
                                        <i class="fa-solid fa-gear"></i>
                                    </span>

                                    <span>
                                        Settings
                                    </span>

                                </a>


                                <div class="profile-menu-divider"></div>


                                {{-- LOGOUT --}}
                                <form method="POST" action="{{ route('logout') }}">

                                    @csrf

                                    <button type="submit">

                                        <span class="profile-menu-icon">
                                            <i class="fa-solid fa-right-from-bracket"></i>
                                        </span>

                                        <span>
                                            Log out
                                        </span>

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </header>


                {{-- CONTENT --}}
                <section class="content">

                    {{-- SUCCESS --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    {{-- ERROR --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif


                    {{-- PAGE CONTENT --}}
                    @yield('content')

                </section>

            </main>

        </div>

    @endif

    @if (auth()->check() && in_array(auth()->user()->role, ['omd']))
        <script>
            function updateOmdOrderNotification() {

                fetch("{{ route('omd.orders.pendingCount') }}", {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Gagal mengambil notifikasi order.');
                        }

                        return response.json();
                    })
                    .then(data => {

                        const badge =
                            document.getElementById('omdOrderNotification');

                        if (!badge) {
                            return;
                        }

                        const count = parseInt(data.count) || 0;

                        if (count > 0) {

                            badge.textContent =
                                count > 99 ? '99+' : count;

                            badge.classList.remove('hidden');
                            badge.classList.add('pulse');

                        } else {

                            badge.textContent = '0';

                            badge.classList.add('hidden');
                            badge.classList.remove('pulse');
                        }

                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            updateOmdOrderNotification();

            setInterval(
                updateOmdOrderNotification,
                5000
            );
        </script>
    @endif

    @if (auth()->check() && auth()->user()->role === 'omd')
        <script>
            function updateOmdOrderNotification() {

                fetch(
                        "{{ route('omd.orders.pendingCount') }}", {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    )
                    .then(response => {

                        if (!response.ok) {
                            throw new Error(
                                'Gagal mengambil notifikasi order.'
                            );
                        }

                        return response.json();
                    })
                    .then(data => {

                        const badge =
                            document.getElementById(
                                'omdOrderNotification'
                            );

                        if (!badge) {
                            return;
                        }

                        const count =
                            parseInt(data.count) || 0;

                        if (count > 0) {

                            badge.textContent =
                                count > 99 ? '99+' : count;

                            badge.classList.remove('hidden');

                            badge.classList.add('pulse');

                        } else {

                            badge.textContent = '0';

                            badge.classList.add('hidden');

                            badge.classList.remove('pulse');
                        }

                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            updateOmdOrderNotification();

            setInterval(
                updateOmdOrderNotification,
                5000
            );
        </script>
    @endif


    @if (auth()->check() && auth()->user()->role === 'user')
        <script>
            function updateUserOrderNotification() {

                fetch(
                        "{{ route('user.orders.pendingConfirmationCount') }}", {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    )
                    .then(response => {

                        if (!response.ok) {
                            throw new Error(
                                'Gagal mengambil notifikasi order user.'
                            );
                        }

                        return response.json();
                    })
                    .then(data => {

                        const badge =
                            document.getElementById(
                                'userOrderNotification'
                            );

                        if (!badge) {
                            return;
                        }

                        const count =
                            parseInt(data.count) || 0;

                        if (count > 0) {

                            badge.textContent =
                                count > 99 ? '99+' : count;

                            badge.classList.remove('hidden');

                            badge.classList.add('pulse');

                        } else {

                            badge.textContent = '0';

                            badge.classList.add('hidden');

                            badge.classList.remove('pulse');
                        }

                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            updateUserOrderNotification();

            setInterval(
                updateUserOrderNotification,
                5000
            );
        </script>
    @endif

    {{-- =========================================================
     PROFILE DROPDOWN
     BERLAKU UNTUK USER DAN OMD
========================================================= --}}

    @if (auth()->check())
        <script>
            document.addEventListener(
                'DOMContentLoaded',
                function() {

                    const profileToggle =
                        document.getElementById(
                            'profileToggle'
                        );

                    const profileMenu =
                        document.getElementById(
                            'profileMenu'
                        );

                    if (
                        !profileToggle ||
                        !profileMenu
                    ) {
                        return;
                    }

                    profileToggle.addEventListener(
                        'click',
                        function(event) {

                            event.stopPropagation();

                            profileMenu.classList.toggle(
                                'open'
                            );

                        }
                    );

                    profileMenu.addEventListener(
                        'click',
                        function(event) {

                            event.stopPropagation();

                        }
                    );

                    document.addEventListener(
                        'click',
                        function() {

                            profileMenu.classList.remove(
                                'open'
                            );

                        }
                    );

                }
            );
        </script>
    @endif


</body>

</html>
