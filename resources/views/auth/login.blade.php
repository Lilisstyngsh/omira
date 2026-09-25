@extends('layouts.app')

@section('title', 'Login - OMD Order')

@section('guest')

<style>
    * {
        box-sizing: border-box;
    }

    .login-page {
        min-height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: #f5f6fa;
        font-family: Inter, ui-sans-serif, system-ui, -apple-system,
            BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .login-container {
        width: 100%;
        max-width: 1180px;
        min-height: 680px;
        display: grid;
        grid-template-columns: 46% 54%;
        background: #ffffff;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.10);
    }

    /* ========================================
       LEFT - LOGIN FORM
    ======================================== */

    .login-left {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px 70px;
        background: #ffffff;
    }

    .login-brand {
        margin-bottom: 50px;
    }

    .brand-logo {
        width: 96px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #5b21b6, #7c3aed);
        color: #ffffff;
        font-size: 21px;
        font-weight: 800;
        box-shadow: 0 8px 20px rgba(124, 58, 237, 0.25);
    }

    .brand-name {
        margin-top: 13px;
        font-size: 21px;
        font-weight: 800;
        letter-spacing: -0.4px;
        color: #111827;
    }

    .brand-description {
        margin-top: 4px;
        color: #98a2b3;
        font-size: 12px;
    }

    .login-title {
        margin: 0;
        color: #111827;
        font-size: 34px;
        font-weight: 750;
        letter-spacing: -1px;
    }

    .login-subtitle {
        margin: 10px 0 30px;
        color: #667085;
        font-size: 14px;
        line-height: 1.6;
    }

    .login-error {
        margin-bottom: 20px;
        padding: 12px 14px;
        border-radius: 10px;
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #be123c;
        font-size: 13px;
    }

    .login-field {
        margin-bottom: 18px;
    }

    .login-field label {
        display: block;
        margin-bottom: 8px;
        color: #344054;
        font-size: 13px;
        font-weight: 600;
    }

    .login-input {
        width: 100%;
        height: 50px;
        padding: 0 15px;
        border: 1px solid #d0d5dd;
        border-radius: 11px;
        outline: none;
        background: #ffffff;
        color: #101828;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .login-input::placeholder {
        color: #98a2b3;
    }

    .login-input:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.10);
    }

    .login-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 2px 0 22px;
    }

    .remember {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #667085;
        font-size: 12px;
        cursor: pointer;
    }

    .remember input {
        width: 15px;
        height: 15px;
        accent-color: #7c3aed;
        cursor: pointer;
    }

    .forgot {
        color: #6d28d9;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .forgot:hover {
        text-decoration: underline;
    }

    .login-button {
        width: 100%;
        height: 50px;
        border: none;
        border-radius: 11px;
        background: linear-gradient(135deg, #6d28d9, #7c3aed);
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 8px 18px rgba(109, 40, 217, 0.20);
    }

    .login-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(109, 40, 217, 0.26);
    }

    .login-button:active {
        transform: translateY(0);
    }

    .login-footer {
        margin-top: 28px;
        text-align: center;
        color: #98a2b3;
        font-size: 11px;
        line-height: 1.6;
    }

    /* ========================================
       RIGHT - VISUAL PANEL
    ======================================== */

    .login-right {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px;
        background:
            radial-gradient(circle at 20% 20%, rgba(196, 181, 253, 0.55), transparent 30%),
            radial-gradient(circle at 85% 80%, rgba(221, 214, 254, 0.70), transparent 35%),
            linear-gradient(135deg, #faf5ff 0%, #f5f3ff 50%, #ffffff 100%);
    }

    .visual-content {
        position: relative;
        width: 100%;
        max-width: 540px;
        z-index: 2;
    }

    .visual-title {
        max-width: 450px;
        margin: 0 auto 12px;
        text-align: center;
        color: #171717;
        font-size: 34px;
        line-height: 1.15;
        letter-spacing: -1.3px;
        font-weight: 800;
    }

    .visual-description {
        max-width: 430px;
        margin: 0 auto 38px;
        text-align: center;
        color: #667085;
        font-size: 13px;
        line-height: 1.7;
    }

    /* Dashboard illustration */

    .dashboard-preview {
        position: relative;
        width: 100%;
        height: 340px;
    }

    .preview-window {
        position: absolute;
        left: 50%;
        top: 20px;
        transform: translateX(-50%);
        width: 92%;
        height: 280px;
        padding: 18px;
        border: 1px solid rgba(255,255,255,0.9);
        border-radius: 22px;
        background: rgba(255,255,255,0.82);
        box-shadow:
            0 25px 60px rgba(76, 29, 149, 0.12),
            inset 0 1px 0 rgba(255,255,255,0.9);
        backdrop-filter: blur(12px);
    }

    .preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .preview-logo {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .preview-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #7c3aed;
    }

    .preview-logo span {
        font-size: 11px;
        font-weight: 700;
        color: #344054;
    }

    .preview-user {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #ede9fe;
    }

    .preview-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 14px;
    }

    .stat-box {
        padding: 12px;
        border-radius: 12px;
        background: #fafafa;
        border: 1px solid #f0f0f0;
    }

    .stat-label {
        color: #98a2b3;
        font-size: 8px;
        margin-bottom: 5px;
    }

    .stat-number {
        color: #1d2939;
        font-size: 17px;
        font-weight: 800;
    }

    .preview-chart {
        position: relative;
        height: 120px;
        padding: 14px;
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid #f2f4f7;
        overflow: hidden;
    }

    .chart-title {
        color: #475467;
        font-size: 9px;
        font-weight: 700;
    }

    .bars {
        position: absolute;
        left: 18px;
        right: 18px;
        bottom: 14px;
        height: 70px;
        display: flex;
        align-items: end;
        justify-content: space-around;
        gap: 10px;
    }

    .bar {
        width: 22px;
        border-radius: 5px 5px 2px 2px;
        background: linear-gradient(to top, #7c3aed, #c4b5fd);
        opacity: 0.9;
    }

    .bar:nth-child(1) {
        height: 35%;
    }

    .bar:nth-child(2) {
        height: 60%;
    }

    .bar:nth-child(3) {
        height: 45%;
    }

    .bar:nth-child(4) {
        height: 80%;
    }

    .bar:nth-child(5) {
        height: 65%;
    }

    .bar:nth-child(6) {
        height: 92%;
    }

    /* Floating cards */

    .floating-card {
        position: absolute;
        z-index: 5;
        padding: 13px 15px;
        border-radius: 14px;
        background: rgba(255,255,255,0.95);
        box-shadow: 0 15px 35px rgba(76, 29, 149, 0.14);
        border: 1px solid rgba(255,255,255,0.9);
    }

    .floating-card.status {
        left: -8px;
        bottom: 34px;
    }

    .floating-card.order {
        right: -8px;
        top: 70px;
    }

    .floating-label {
        color: #98a2b3;
        font-size: 8px;
        margin-bottom: 4px;
    }

    .floating-value {
        color: #1d2939;
        font-size: 13px;
        font-weight: 800;
    }

    .floating-success {
        color: #059669;
    }

    .decor-circle {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .circle-one {
        width: 180px;
        height: 180px;
        right: -70px;
        top: -60px;
        background: rgba(196, 181, 253, 0.25);
    }

    .circle-two {
        width: 120px;
        height: 120px;
        left: -50px;
        bottom: -30px;
        background: rgba(221, 214, 254, 0.35);
    }

    /* ========================================
       RESPONSIVE
    ======================================== */

    @media (max-width: 900px) {
        .login-container {
            grid-template-columns: 1fr;
            max-width: 520px;
        }

        .login-right {
            display: none;
        }

        .login-left {
            padding: 50px 45px;
        }
    }

    @media (max-width: 480px) {
        .login-page {
            padding: 12px;
        }

        .login-container {
            border-radius: 22px;
        }

        .login-left {
            padding: 35px 25px;
        }

        .login-brand {
            margin-bottom: 35px;
        }

        .login-title {
            font-size: 29px;
        }
    }
</style>

<div class="login-page">

    <div class="login-container">

        {{-- ================================
             LEFT - LOGIN FORM
        ================================= --}}

        <div class="login-left">

            <div class="login-brand">

                <div class="brand-logo">
                    OMIRA
                </div>

                <div class="brand-name">
                    OMD Integrated Repair Application
                </div>

            </div>

            <div>

                <h1 class="login-title">
                    Welcome!
                </h1>

                {{-- Error login --}}
                @if ($errors->any())
                    <div class="login-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="login-field">
                        <label for="email">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="login-input"
                            placeholder="Masukkan email"
                            required
                            autofocus
                        >
                    </div>

                    {{-- Password --}}
                    <div class="login-field">
                        <label for="password">
                            Password
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="login-input"
                            placeholder="Masukkan password"
                            required
                        >
                    </div>

                    {{-- Remember --}}
                    <div class="login-options">

                        <label class="remember">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                            >

                            <span>Ingat saya</span>
                        </label>

                        {{-- Kalau belum ada fitur forgot password,
                             bagian ini jangan diarahkan ke route --}}

                    </div>

                    <button type="submit" class="login-button">
                        Masuk
                    </button>

                </form>

            </div>

        </div>


        {{-- ================================
             RIGHT - VISUAL
        ================================= --}}

        <div class="login-right">

            <div class="decor-circle circle-one"></div>
            <div class="decor-circle circle-two"></div>

            <div class="visual-content">

                <h2 class="visual-title">

                <div class="dashboard-preview">

                    {{-- Main dashboard --}}
                    <div class="preview-window">

                        <div class="preview-header">

                            <div class="preview-logo">
                                <div class="preview-dot"></div>
                                <span>OMD ORDER</span>
                            </div>

                            <div class="preview-user"></div>

                        </div>

                        <div class="preview-stats">

                            <div class="stat-box">
                                <div class="stat-label">
                                    TOTAL ORDER
                                </div>

                                <div class="stat-number">
                                    128
                                </div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-label">
                                    ON PROCESS
                                </div>

                                <div class="stat-number">
                                    24
                                </div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-label">
                                    SELESAI
                                </div>

                                <div class="stat-number">
                                    104
                                </div>
                            </div>

                        </div>

                        <div class="preview-chart">

                            <div class="chart-title">
                                Order Repair
                            </div>

                            <div class="bars">
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                                <div class="bar"></div>
                            </div>

                        </div>

                    </div>

                    {{-- Floating card --}}
                    <div class="floating-card status">

                        <div class="floating-label">
                            STATUS ORDER
                        </div>

                        <div class="floating-value floating-success">
                            ● Completed
                        </div>

                    </div>

                    <div class="floating-card order">

                        <div class="floating-label">
                            ORDER TERBARU
                        </div>

                        <div class="floating-value">
                            ORD-2026-001
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection