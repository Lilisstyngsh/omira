@extends('layouts.app')

@section('title', 'Login - OMIRA')

@section('guest')

<style>
    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        min-height: 100%;
    }

    body {
        overflow-x: hidden;
    }

    /* =========================================================
       GUEST WRAPPER
    ========================================================== */

    .guest-page {
        width: 100% !important;
        height: 100vh !important;
        min-height: 100vh !important;
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
        overflow: hidden !important;
        background: #f3f7f4 !important;
    }

    /* =========================================================
       LOGIN PAGE
    ========================================================== */

    .login-page {
        width: 100%;
        height: 100vh;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        margin: 0;
        overflow: hidden;
        background:
            radial-gradient(
                circle at 12% 18%,
                rgba(79, 139, 99, .05),
                transparent 22%
            ),
            radial-gradient(
                circle at 88% 82%,
                rgba(79, 139, 99, .04),
                transparent 20%
            ),
            #f3f7f4;
        font-family:
            Inter,
            ui-sans-serif,
            system-ui,
            -apple-system,
            BlinkMacSystemFont,
            "Segoe UI",
            sans-serif;
    }

    .login-container {
        position: relative;
        width: 100%;
        max-width: 1040px;
        height: min(600px, calc(100vh - 48px));
        min-height: 540px;
        display: grid;
        grid-template-columns: 55% 45%;
        background: #ffffff;
        border: 1px solid #e3ebe5;
        border-radius: 26px;
        overflow: hidden;
        box-shadow:
            0 30px 70px rgba(39, 73, 53, .10),
            0 8px 25px rgba(39, 73, 53, .04);
        animation: containerEnter .8s ease both;
    }

    @keyframes containerEnter {
        from {
            opacity: 0;
            transform: translateY(14px) scale(.992);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* =========================================================
       LEFT VISUAL
    ========================================================== */

    .login-visual {
        position: relative;
        overflow: hidden;
        padding: 34px 38px 30px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background:
            radial-gradient(
                circle at 15% 15%,
                rgba(111, 168, 132, .25),
                transparent 29%
            ),
            radial-gradient(
                circle at 88% 82%,
                rgba(165, 203, 179, .20),
                transparent 30%
            ),
            linear-gradient(
                145deg,
                #eef6f0 0%,
                #e5f0e9 52%,
                #f8fbf9 100%
            );
    }

    /* =========================================================
       MOVING GRID
    ========================================================== */

    .industrial-grid {
        position: absolute;
        inset: -40px;
        opacity: .20;
        background-image:
            linear-gradient(
                rgba(72, 107, 84, .08) 1px,
                transparent 1px
            ),
            linear-gradient(
                90deg,
                rgba(72, 107, 84, .08) 1px,
                transparent 1px
            );
        background-size: 30px 30px;
        pointer-events: none;
        animation: gridMove 18s linear infinite;
    }

    @keyframes gridMove {
        from {
            transform: translate3d(0, 0, 0);
        }

        to {
            transform: translate3d(30px, 30px, 0);
        }
    }

    /* =========================================================
       BACKGROUND CIRCLES
    ========================================================== */

    .visual-shape {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .shape-one {
        width: 220px;
        height: 220px;
        top: -110px;
        right: -70px;
        border: 1px solid rgba(64, 120, 83, .11);
        background: rgba(255, 255, 255, .14);
        animation: shapeFloatOne 8s ease-in-out infinite;
    }

    .shape-two {
        width: 135px;
        height: 135px;
        bottom: -65px;
        left: -50px;
        border: 1px solid rgba(64, 120, 83, .09);
        background: rgba(255, 255, 255, .12);
        animation: shapeFloatTwo 10s ease-in-out infinite;
    }

    @keyframes shapeFloatOne {
        0%, 100% {
            transform: translate(0, 0);
        }

        50% {
            transform: translate(-9px, 7px);
        }
    }

    @keyframes shapeFloatTwo {
        0%, 100% {
            transform: translate(0, 0);
        }

        50% {
            transform: translate(8px, -7px);
        }
    }

    /* =========================================================
       PARTICLES
    ========================================================== */

    .data-particles {
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
    }

    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #5f9670;
        opacity: .18;
        animation: particleFloat 7s ease-in-out infinite;
    }

    .particle:nth-child(1) {
        left: 11%;
        top: 23%;
        animation-delay: 0s;
    }

    .particle:nth-child(2) {
        left: 24%;
        top: 67%;
        animation-delay: 1.2s;
    }

    .particle:nth-child(3) {
        left: 42%;
        top: 19%;
        animation-delay: 2.1s;
    }

    .particle:nth-child(4) {
        left: 67%;
        top: 29%;
        animation-delay: .8s;
    }

    .particle:nth-child(5) {
        left: 78%;
        top: 73%;
        animation-delay: 1.7s;
    }

    .particle:nth-child(6) {
        left: 90%;
        top: 42%;
        animation-delay: 3s;
    }

    .particle:nth-child(7) {
        left: 50%;
        top: 80%;
        animation-delay: 2.6s;
    }

    @keyframes particleFloat {
        0%, 100% {
            opacity: .12;
            transform: translate(0, 0) scale(1);
        }

        50% {
            opacity: .30;
            transform: translate(7px, -9px) scale(1.22);
        }
    }

    /* =========================================================
       VISUAL CONTENT
    ========================================================== */

    .visual-top {
        position: relative;
        z-index: 3;
    }

    .visual-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        animation: contentEnter .65s .15s ease forwards;
    }

    .visual-brand-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: linear-gradient(135deg, #4f8b63, #376b4a);
        color: #fff;
        font-size: 13px;
        box-shadow: 0 8px 18px rgba(55, 107, 74, .15);
    }

    .visual-brand-text strong {
        display: block;
        color: #1e3828;
        font-size: 13px;
        font-weight: 850;
    }

    .visual-brand-text span {
        display: block;
        margin-top: 2px;
        color: #7d8f84;
        font-size: 7px;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .visual-heading {
        position: relative;
        z-index: 3;
        margin-top: 30px;
        max-width: 420px;
        opacity: 0;
        animation: contentEnter .7s .28s ease forwards;
    }

    .visual-heading h1 {
        margin: 0;
        color: #1c3525;
        font-size: 34px;
        line-height: 1.06;
        font-weight: 850;
        letter-spacing: -1.5px;
    }

    .visual-heading h1 span {
        color: #4f8b63;
    }

    .visual-heading p {
        max-width: 365px;
        margin: 11px 0 0;
        color: #6f8075;
        font-size: 11px;
        line-height: 1.65;
    }

    @keyframes contentEnter {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =========================================================
       WORKFLOW
    ========================================================== */

    .workflow-area {
        position: relative;
        z-index: 3;
        height: 255px;
        margin-top: 20px;
        opacity: 0;
        animation: contentEnter .75s .42s ease forwards;
    }

    .workflow-card {
        position: absolute;
        left: 4%;
        top: 10px;
        width: 92%;
        height: 205px;
        padding: 16px;
        border-radius: 18px;
        background: rgba(255, 255, 255, .79);
        border: 1px solid rgba(255, 255, 255, .90);
        box-shadow:
            0 20px 45px rgba(44, 82, 58, .09),
            inset 0 1px 0 rgba(255, 255, 255, .95);
        backdrop-filter: blur(12px);
        transform: rotate(-1deg);
    }

    .workflow-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .workflow-title {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .workflow-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #4f8b63;
        box-shadow:
            0 0 0 4px rgba(79, 139, 99, .10);
        animation: indicatorPulse 2s ease-in-out infinite;
    }

    @keyframes indicatorPulse {
        0%, 100% {
            transform: scale(1);
            box-shadow:
                0 0 0 4px rgba(79, 139, 99, .10);
        }

        50% {
            transform: scale(1.18);
            box-shadow:
                0 0 0 7px rgba(79, 139, 99, .03);
        }
    }

    .workflow-title span {
        color: #3e5245;
        font-size: 9px;
        font-weight: 800;
    }

    .workflow-code {
        color: #98a69e;
        font-size: 7px;
        font-weight: 700;
    }

    .workflow-main {
        display: grid;
        grid-template-columns: 1.05fr .95fr;
        gap: 10px;
    }

    /* =========================================================
       MACHINE
    ========================================================== */

    .machine-panel {
        position: relative;
        height: 120px;
        padding: 12px;
        border-radius: 13px;
        background: #f7faf8;
        border: 1px solid #e7efe9;
        overflow: hidden;
    }

    .machine-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .machine-top strong {
        color: #405447;
        font-size: 8px;
        font-weight: 800;
    }

    .machine-indicator {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #4f8b63;
        box-shadow: 0 0 0 4px rgba(79, 139, 99, .08);
        animation: machinePulse 2.2s ease-in-out infinite;
    }

    @keyframes machinePulse {
        0%, 100% {
            opacity: .65;
            transform: scale(1);
        }

        50% {
            opacity: 1;
            transform: scale(1.18);
        }
    }

    .machine-body {
        position: relative;
        height: 76px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .machine-box {
        position: relative;
        width: 92px;
        height: 55px;
        border-radius: 9px;
        background: #dce8df;
        border: 1px solid #bfd1c3;
        box-shadow:
            inset 0 0 0 5px rgba(255,255,255,.30),
            0 7px 15px rgba(55, 107, 74, .07);
        animation: machineFloat 4.8s ease-in-out infinite;
    }

    @keyframes machineFloat {
        0%, 100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-3px);
        }
    }

    .machine-screen {
        position: absolute;
        top: 11px;
        left: 13px;
        width: 38px;
        height: 23px;
        border-radius: 5px;
        background: #ffffff;
        border: 1px solid #d4e1d7;
        overflow: hidden;
    }

    .machine-screen::after {
        content: '';
        position: absolute;
        left: 6px;
        bottom: 5px;
        width: 14px;
        height: 4px;
        border-radius: 4px;
        background: #6a9b78;
        animation: screenSignal 2.8s ease-in-out infinite;
    }

    @keyframes screenSignal {
        0%, 100% {
            width: 14px;
        }

        50% {
            width: 25px;
        }
    }

    .machine-button {
        position: absolute;
        top: 15px;
        right: 11px;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #4f8b63;
        animation: buttonGlow 2s ease-in-out infinite;
    }

    @keyframes buttonGlow {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(79, 139, 99, .20);
        }

        50% {
            box-shadow: 0 0 0 6px rgba(79, 139, 99, 0);
        }
    }

    .machine-base {
        position: absolute;
        bottom: 7px;
        left: 13px;
        right: 13px;
        height: 4px;
        border-radius: 4px;
        background: #aec3b3;
    }

    /* =========================================================
       SCANNER
    ========================================================== */

    .scanner-line {
        position: absolute;
        top: 29px;
        left: -5%;
        width: 2px;
        height: 70px;
        background: linear-gradient(
            to bottom,
            transparent,
            rgba(79, 139, 99, .45),
            transparent
        );
        opacity: .60;
        animation: scannerMove 3.8s linear infinite;
    }

    @keyframes scannerMove {
        from {
            left: -5%;
        }

        to {
            left: 105%;
        }
    }

    /* =========================================================
       PROCESS PANEL
    ========================================================== */

    .status-panel {
        height: 120px;
        padding: 12px;
        border-radius: 13px;
        background: #ffffff;
        border: 1px solid #edf2ee;
    }

    .status-panel-title {
        color: #7b8d81;
        font-size: 7px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .status-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 0;
        border-bottom: 1px solid #eef2ef;
    }

    .status-row:last-child {
        border-bottom: none;
    }

    .status-check {
        width: 19px;
        height: 19px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e4f1e8;
        color: #3f7d58;
        font-size: 8px;
        font-weight: 900;
    }

    .status-row span {
        color: #596c60;
        font-size: 7px;
        font-weight: 700;
    }

    .status-row.active .status-check {
        animation: activeStep 2s ease-in-out infinite;
    }

    .status-row.pending .status-check {
        background: #f1f4f2;
        color: #9aaa9f;
    }

    @keyframes activeStep {
        0%, 100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.10);
        }
    }

    /* =========================================================
       FLOATING CARD
    ========================================================== */

    .floating-order {
        position: absolute;
        z-index: 6;
        right: -3px;
        top: 47px;
        padding: 10px 12px;
        border-radius: 11px;
        background: rgba(255,255,255,.95);
        border: 1px solid rgba(255,255,255,.9);
        box-shadow: 0 13px 28px rgba(44,82,58,.10);
        animation: floatingCard 4.4s ease-in-out infinite;
    }

    @keyframes floatingCard {
        0%, 100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    .floating-label {
        margin-bottom: 3px;
        color: #9aa79f;
        font-size: 6px;
        font-weight: 700;
    }

    .floating-value {
        color: #2d4637;
        font-size: 9px;
        font-weight: 850;
    }

    /* =========================================================
       BOTTOM FEATURES
    ========================================================== */

    .visual-bottom {
        position: relative;
        z-index: 3;
        display: flex;
        gap: 7px;
        opacity: 0;
        animation: contentEnter .65s .62s ease forwards;
    }

    .feature-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 8px;
        background: rgba(255,255,255,.55);
        border: 1px solid rgba(100,139,113,.12);
        color: #688072;
        font-size: 7px;
        font-weight: 750;
        transition: .2s ease;
    }

    .feature-badge:hover {
        transform: translateY(-2px);
        background: rgba(255,255,255,.75);
    }

    .feature-badge i {
        color: #4f8b63;
        font-size: 7px;
    }

    /* =========================================================
       RIGHT - LOGIN
    ========================================================== */

    .login-form-panel {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 44px 50px;
        background: #ffffff;
        opacity: 0;
        animation: formEnter .8s .22s ease forwards;
    }

    @keyframes formEnter {
        from {
            opacity: 0;
            transform: translateX(12px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .form-brand {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 38px;
    }

    .form-brand-mark {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #e7f1ea;
        color: #3f7d58;
        font-size: 12px;
    }

    .form-brand strong {
        color: #26392d;
        font-size: 13px;
        font-weight: 850;
    }

    .form-brand span {
        color: #9aa79f;
        font-size: 7px;
        margin-left: 1px;
    }

    .login-title {
        margin: 0;
        color: #1b2b21;
        font-size: 31px;
        font-weight: 850;
        letter-spacing: -1px;
    }

    .login-subtitle {
        margin: 8px 0 24px;
        color: #7b887f;
        font-size: 11px;
        line-height: 1.6;
    }

    .login-error {
        margin-bottom: 16px;
        padding: 10px 12px;
        border-radius: 9px;
        background: #fff4f3;
        border: 1px solid #ffd9d4;
        color: #b42318;
        font-size: 10px;
    }

    .login-field {
        margin-bottom: 15px;
    }

    .login-field label {
        display: block;
        margin-bottom: 7px;
        color: #3d4e43;
        font-size: 10px;
        font-weight: 700;
    }

    .input-wrap {
        position: relative;
    }

    .login-input {
        width: 100%;
        height: 46px;
        padding: 0 13px;
        border: 1px solid #dce5df;
        border-radius: 10px;
        outline: none;
        background: #fbfdfc;
        color: #24372b;
        font-size: 11px;
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }

    .login-input.password-field {
        padding-right: 42px;
    }

    .login-input::placeholder {
        color: #a5b0a9;
    }

    .login-input:focus {
        border-color: #5d936c;
        background: #ffffff;
        box-shadow:
            0 0 0 4px rgba(93, 147, 108, .09);
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 11px;
        transform: translateY(-50%);
        width: 27px;
        height: 27px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 7px;
        background: transparent;
        color: #91a097;
        cursor: pointer;
        transition: .18s ease;
    }

    .toggle-password:hover {
        background: #eef5f0;
        color: #4f8b63;
    }

    .login-options {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin: 1px 0 20px;
    }

    .remember {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #7e8c83;
        font-size: 9px;
        cursor: pointer;
    }

    .remember input {
        width: 13px;
        height: 13px;
        margin: 0;
        accent-color: #4f8b63;
        cursor: pointer;
    }

    .login-button {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 47px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #4f8b63, #3b7351);
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .01em;
        cursor: pointer;
        box-shadow:
            0 9px 18px rgba(63, 125, 88, .17);
        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }

    .login-button::after {
        content: '';
        position: absolute;
        top: 0;
        left: -75%;
        width: 42%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.22),
            transparent
        );
        transform: skewX(-18deg);
        animation: buttonShine 6s ease-in-out infinite;
    }

    @keyframes buttonShine {
        0%, 72% {
            left: -75%;
        }

        88% {
            left: 125%;
        }

        100% {
            left: 125%;
        }
    }

    .login-button:hover {
        transform: translateY(-1px);
        box-shadow:
            0 12px 24px rgba(63, 125, 88, .22);
    }

    .login-button:active {
        transform: translateY(0);
    }

    .security-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        margin-top: 16px;
        color: #9aa69f;
        font-size: 7px;
    }

    .security-note i {
        color: #5d936c;
    }

    .login-footer {
        margin-top: 15px;
        text-align: center;
        color: #a0aaa4;
        font-size: 7px;
        line-height: 1.7;
    }

    .login-footer strong {
        color: #5f7467;
        font-weight: 750;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 1000px) {

        .guest-page,
        .login-page {
            min-height: 100vh !important;
            height: 100vh !important;
        }

        .login-container {
            grid-template-columns: 1fr;
            width: 100%;
            max-width: 500px;
            height: min(580px, calc(100vh - 32px));
            min-height: 0;
        }

        .login-visual {
            display: none;
        }

        .login-form-panel {
            min-height: 0;
            padding: 42px 40px;
        }
    }

    @media (max-width: 500px) {

        .login-page {
            padding: 12px;
        }

        .login-container {
            height: calc(100vh - 24px);
            min-height: 500px;
            border-radius: 20px;
        }

        .login-form-panel {
            padding: 32px 23px;
        }

        .form-brand {
            margin-bottom: 32px;
        }

        .login-title {
            font-size: 28px;
        }

        .login-subtitle {
            margin-bottom: 22px;
        }
    }

    /* =========================================================
       REDUCED MOTION
    ========================================================== */

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            scroll-behavior: auto !important;
        }
    }
</style>


<div class="login-page">

    <div class="login-container">

        {{-- =================================================
             LEFT - INDUSTRIAL VISUAL
        ================================================== --}}

        <div class="login-visual">

            <div class="industrial-grid"></div>

            <div class="visual-shape shape-one"></div>
            <div class="visual-shape shape-two"></div>


            {{-- PARTICLES --}}
            <div class="data-particles">

                <span class="particle"></span>
                <span class="particle"></span>
                <span class="particle"></span>
                <span class="particle"></span>
                <span class="particle"></span>
                <span class="particle"></span>
                <span class="particle"></span>

            </div>


            <div class="visual-top">

                {{-- BRAND --}}
                <div class="visual-brand">

                    <div class="visual-brand-icon">
                        <i class="fa-solid fa-industry"></i>
                    </div>

                    <div class="visual-brand-text">

                        <strong>
                            OMIRA
                        </strong>

                        <span>
                            OMD Integrated Repair Application
                        </span>

                    </div>

                </div>


                {{-- HEADING --}}
                <div class="visual-heading">

                    <h1>
                        Simple control.<br>
                        <span>Better repair.</span>
                    </h1>

                </div>


                {{-- WORKFLOW --}}
                <div class="workflow-area">

                    <div class="workflow-card">

                        <div class="workflow-header">

                            <div class="workflow-title">

                                <div class="workflow-status-dot"></div>

                                <span>
                                    Repair Monitoring
                                </span>

                            </div>
                        </div>


                        <div class="workflow-main">

                            {{-- MACHINE --}}
                            <div class="machine-panel">

                                <div class="scanner-line"></div>

                                <div class="machine-top">

                                    <strong>
                                        Repair Station
                                    </strong>

                                    <div class="machine-indicator"></div>

                                </div>

                                <div class="machine-body">

                                    <div class="machine-box">

                                        <div class="machine-screen"></div>

                                        <div class="machine-button"></div>

                                        <div class="machine-base"></div>

                                    </div>

                                </div>

                            </div>


                            {{-- PROCESS --}}
                            <div class="status-panel">

                                <div class="status-panel-title">
                                    Process
                                </div>


                                <div class="status-row active">

                                    <div class="status-check">
                                        ✓
                                    </div>

                                    <span>
                                        Open
                                    </span>

                                </div>


                                <div class="status-row">

                                    <div class="status-check">
                                        ✓
                                    </div>

                                    <span>
                                        Progress
                                    </span>

                                </div>


                                <div class="status-row active">

                                    <div class="status-check">
                                        ✓
                                    </div>

                                    <span>
                                        Close
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- FLOATING ORDER --}}
                    <div class="floating-order">

                        <div class="floating-label">
                            CURRENT ORDER
                        </div>

                        <div class="floating-value">
                            Repair Box
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             RIGHT - LOGIN FORM
        ================================================== --}}

        <div class="login-form-panel">

            <div class="form-brand">

                <div class="form-brand-mark">
                    <i class="fa-solid fa-gears"></i>
                </div>

                <strong>
                    OMIRA
                </strong>

                <span>
                    Workshop System
                </span>

            </div>


            <div>

                <h1 class="login-title">
                    Login
                </h1>

                <p class="login-subtitle">
                    
                </p>


                {{-- ERROR LOGIN --}}
                @if ($errors->any())

                    <div class="login-error">
                        {{ $errors->first() }}
                    </div>

                @endif


                <form method="POST" action="{{ route('login.store') }}">

                    @csrf


                    {{-- EMAIL --}}
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


                    {{-- PASSWORD --}}
                    <div class="login-field">

                        <label for="password">
                            Password
                        </label>

                        <div class="input-wrap">

                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="login-input password-field"
                                placeholder="Masukkan password"
                                required
                            >

                            <button
                                type="button"
                                class="toggle-password"
                                id="togglePassword"
                                aria-label="Tampilkan password"
                            >
                                <i class="fa-regular fa-eye"></i>
                            </button>

                        </div>

                    </div>


                    {{-- REMEMBER --}}
                    <div class="login-options">

                        <label class="remember">

                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                            >

                            <span>
                                Ingat saya
                            </span>

                        </label>

                    </div>


                    {{-- LOGIN BUTTON --}}
                    <button
                        type="submit"
                        class="login-button"
                    >
                        Masuk
                    </button>

                </form>


                <div class="security-note">

                    <i class="fa-solid fa-shield-halved"></i>

                    <span>
                        Secure access to your workspace
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const passwordInput =
            document.getElementById('password');

        const togglePassword =
            document.getElementById('togglePassword');

        if (!passwordInput || !togglePassword) {
            return;
        }

        togglePassword.addEventListener('click', function () {

            const icon =
                togglePassword.querySelector('i');

            if (passwordInput.type === 'password') {

                passwordInput.type = 'text';

                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');

                togglePassword.setAttribute(
                    'aria-label',
                    'Sembunyikan password'
                );

            } else {

                passwordInput.type = 'password';

                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');

                togglePassword.setAttribute(
                    'aria-label',
                    'Tampilkan password'
                );

            }

        });

    });
</script>

@endsection