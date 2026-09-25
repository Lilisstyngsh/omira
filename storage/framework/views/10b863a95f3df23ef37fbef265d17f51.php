<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $__env->yieldContent('title', 'OMD Order'); ?></title>

    <style>
        :root {
            --primary: #7c3aed;
            --primary-dark: #6d28d9;
            --primary-light: #ede9fe;
            --purple-soft: #f7f5ff;

            --text: #182033;
            --text-soft: #667085;
            --muted: #98a2b3;

            --white: #ffffff;
            --background: #f7f7fb;
            --border: #e8e5f0;

            --success: #059669;
            --warning: #d97706;
            --danger: #dc2626;
            --info: #2563eb;

            --sidebar-width: 255px;

            --shadow-sm: 0 2px 8px rgba(31, 20, 70, .05);
            --shadow: 0 10px 35px rgba(58, 35, 120, .08);
            --shadow-purple: 0 10px 30px rgba(124, 58, 237, .20);

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
                    rgba(139, 92, 246, .07),
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

            box-shadow:
                8px 0 35px rgba(37, 18, 77, .12);

            z-index: 100;
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
                    #a855f7,
                    #7c3aed);

            box-shadow:
                0 8px 22px rgba(139, 92, 246, .35);

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
                    rgba(139, 92, 246, .95),
                    rgba(124, 58, 237, .78));

            box-shadow:
                0 8px 20px rgba(124, 58, 237, .25);
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
                    #a855f7,
                    #6d28d9);

            color: white;

            font-size: 12px;
            font-weight: 800;

            box-shadow:
                0 5px 15px rgba(124, 58, 237, .25);
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
                    #8b5cf6,
                    #6d28d9);

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
                    rgba(124, 58, 237, .12),
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

        .btn-primary {
            color: white;

            background:
                linear-gradient(135deg,
                    #8b5cf6,
                    #6d28d9);

            box-shadow:
                0 8px 20px rgba(124, 58, 237, .20);
        }

        .btn-primary:hover {
            box-shadow:
                0 10px 25px rgba(124, 58, 237, .30);
        }

        .btn-secondary {
            color: #5b21b6;

            background: var(--primary-light);

            border:
                1px solid #ddd6fe;
        }

        .btn-danger {
            color: #b91c1c;
            background: #fee2e2;
        }

        .btn-success {
            color: #047857;
            background: #d1fae5;
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
            border-color: #8b5cf6;

            box-shadow:
                0 0 0 4px rgba(139, 92, 246, .10);
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
            color: #6d28d9;
            background: #ede9fe;
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
                    rgba(139, 92, 246, .17),
                    transparent 30%),
                radial-gradient(circle at 10% 90%,
                    rgba(124, 58, 237, .08),
                    transparent 25%),
                #f8f7ff;
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
            background: rgba(124, 58, 237, 0.15);
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
            background: rgba(124, 58, 237, 0.22);
            color: white;
            font-weight: 600;
        }

        .nav-sub-icon {
            width: 12px;
            text-align: center;
        }
    </style>
</head>

<body>

    

    <?php if(View::hasSection('guest')): ?>

        <main class="guest-page">
            <?php echo $__env->yieldContent('guest'); ?>
        </main>
    <?php else: ?>
        

        <div class="app">

            

            <aside class="sidebar">

                
                <div class="brand">

                    <div class="brand-logo">
                        O
                    </div>

                    <div class="brand-text">

                        <div class="brand-title">
                            OMIRA
                        </div>

                        <div class="brand-subtitle">
                            OMD Integrated Repair Application
                        </div>

                    </div>

                </div>


                
                <div class="nav-section">
                    Main Menu
                </div>

                <nav class="nav">

                    
                    <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">

                        <span class="nav-icon">⌂</span>

                        <span>
                            Dashboard
                        </span>

                    </a>


                    
                    <?php if(auth()->user()->role === 'user'): ?>
                        <a href="<?php echo e(route('user.orders.index')); ?>"
                            class="<?php echo e(request()->routeIs('user.orders.*') ? 'active' : ''); ?>">

                            <span class="nav-icon">▣</span>

                            <span>
                                Order Repair Box
                            </span>

                        </a>


                        <a href="<?php echo e(route('user.tps.index')); ?>"
                            class="<?php echo e(request()->routeIs('user.tps.*') ? 'active' : ''); ?>">

                            <span class="nav-icon">⚙</span>

                            <span>
                                Order TPS Tools
                            </span>

                        </a>
                    <?php else: ?>
                        

                        <a href="<?php echo e(route('omd.orders.index')); ?>"
                            class="<?php echo e(request()->routeIs('omd.orders.*') ? 'active' : ''); ?>">

                            <span class="nav-icon">▣</span>

                            <span>
                                Order Repair Box
                            </span>

                        </a>


                        <a href="<?php echo e(route('omd.tps.index')); ?>"
                            class="<?php echo e(request()->routeIs('omd.tps.*') ? 'active' : ''); ?>">

                            <span class="nav-icon">⚙</span>

                            <span>
                                Order TPS Tools
                            </span>

                        </a>
                    <?php endif; ?>

                </nav>


                

                <?php if(auth()->user()->role !== 'user'): ?>

                    <div class="nav-section">
                        Management
                    </div>

                    <nav class="nav">

                        
                        <?php if(auth()->user()->role === 'omd_leader'): ?>
                            <div class="nav-dropdown <?php echo e(request()->routeIs('omd.master.*') ? 'open' : ''); ?>">


                                
                                <div class="nav-dropdown-toggle <?php echo e(request()->routeIs('omd.master.*') ? 'active' : ''); ?>"
                                    onclick="this.parentElement.classList.toggle('open')">


                                    <span class="nav-icon">
                                        ▤
                                    </span>


                                    <span class="nav-dropdown-title">
                                        Data Master
                                    </span>


                                    <span class="nav-arrow">
                                        ▾
                                    </span>


                                </div>



                                
                                <div class="nav-dropdown-menu">


                                    
                                    <a href="<?php echo e(route('omd.master.plant')); ?>"
                                        class="<?php echo e(request()->routeIs('omd.master.plant') ? 'active' : ''); ?>">

                                        <span>
                                            Plant
                                        </span>

                                    </a>



                                    
                                    <a href="<?php echo e(route('omd.master.line')); ?>"
                                        class="<?php echo e(request()->routeIs('omd.master.line') ? 'active' : ''); ?>">

                                        <span>
                                            Line
                                        </span>

                                    </a>

                                    
                                    <a href="<?php echo e(route('omd.master.model-product')); ?>"
                                        class="<?php echo e(request()->routeIs('omd.master.model-product') ? 'active' : ''); ?>">

                                        <span>
                                            Model & Produk
                                        </span>

                                    </a>

                                    
                                    <a href="<?php echo e(route('omd.master.ng-type')); ?>"
                                        class="<?php echo e(request()->routeIs('omd.master.ng-type') ? 'active' : ''); ?>">

                                        <span>
                                            Jenis NG
                                        </span>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        
                        <?php if(auth()->user()->role === 'omd_leader'): ?>
                            <a href="<?php echo e(route('omd.users.index')); ?>"
                                class="<?php echo e(request()->routeIs('omd.users.*') ? 'active' : ''); ?>">

                                <span class="nav-icon">
                                    👥
                                </span>

                                <span>
                                    Manajemen Akun
                                </span>

                            </a>
                        <?php endif; ?>

                    </nav>

                <?php endif; ?>


                

                <div class="sidebar-bottom">

                    <div class="sidebar-user">

                        <div class="sidebar-user-info">

                            <div class="sidebar-avatar">
                                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                            </div>

                            <div style="min-width:0;">

                                <div class="sidebar-user-name">
                                    <?php echo e(auth()->user()->name); ?>

                                </div>

                                <div class="sidebar-user-role">
                                    <?php echo e(strtoupper(str_replace('_', ' ', auth()->user()->role))); ?>

                                </div>

                            </div>

                        </div>


                        
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>

                            <button type="submit" class="logout-btn">

                                <span>
                                    Logout
                                </span>

                            </button>

                        </form>

                    </div>

                </div>

            </aside>


            

            <main class="main">

                
                <header class="topbar">

                    <div class="topbar-title">
                        <?php echo $__env->yieldContent('header', 'Dashboard'); ?>
                    </div>


                    <div class="topbar-right">

                        <div class="profile">

                            <div class="profile-avatar">
                                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                            </div>

                            <div class="profile-name">
                                <?php echo e(auth()->user()->name); ?>

                            </div>

                        </div>

                    </div>

                </header>


                
                <section class="content">

                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>


                    
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <?php echo e($errors->first()); ?>

                        </div>
                    <?php endif; ?>


                    
                    <?php echo $__env->yieldContent('content'); ?>

                </section>

            </main>

        </div>

    <?php endif; ?>

</body>

</html>
<?php /**PATH C:\laragon\www\omira\resources\views/layouts/app.blade.php ENDPATH**/ ?>