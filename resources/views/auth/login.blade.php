@extends('layouts.app')

@section('title', 'Login - OMIRA')

@section('guest')
<style>
    /* Semua style dibatasi untuk halaman login, tanpa mengubah layout aplikasi lain. */
    .guest-page {
        display: block !important;
        width: 100% !important;
        min-height: 100vh !important;
        min-height: 100svh !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow-x: hidden !important;
        background: #f5f8f2 !important;
    }

    .omira-glass-page,
    .omira-glass-page * {
        box-sizing: border-box;
    }

    .omira-glass-page {
        --omira-ink: #173c32;
        --omira-muted: #62766e;
        position: relative;
        isolation: isolate;
        display: grid;
        place-items: center;
        width: 100%;
        min-height: 100vh;
        min-height: 100svh;
        padding: 42px 22px;
        overflow: hidden;
        color: var(--omira-ink);
        background:
            radial-gradient(ellipse 56% 55% at 68% 19%, rgba(158, 196, 169, .32), transparent 76%),
            radial-gradient(ellipse 50% 65% at 14% 72%, rgba(182, 215, 192, .36), transparent 79%),
            linear-gradient(132deg, #fafbf7 0%, #eef6ed 42%, #f7faf5 76%, #fdfdf9 100%);
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .omira-glass-decor {
        position: absolute;
        inset: 0;
        z-index: -1;
        overflow: hidden;
        pointer-events: none;
    }

    .omira-glass-orb,
    .omira-glass-arc,
    .omira-glass-wave {
        position: absolute;
        display: block;
        pointer-events: none;
    }

    .omira-glass-orb {
        border-radius: 50%;
        background: radial-gradient(circle at 30% 27%, rgba(245, 252, 244, .78), rgba(112, 165, 131, .34) 70%, rgba(112, 165, 131, .14));
    }

    .omira-glass-orb--top {
        width: 150px;
        height: 150px;
        top: 5%;
        left: 18%;
        opacity: .58;
    }

    .omira-glass-orb--bottom {
        width: min(42vw, 610px);
        height: min(42vw, 610px);
        left: 6%;
        bottom: -26%;
        opacity: .42;
        filter: blur(2px);
    }

    .omira-glass-orb--right {
        width: 94px;
        height: 94px;
        right: 10%;
        top: 46%;
        opacity: .62;
    }

    .omira-glass-wave {
        border-radius: 50%;
        background: linear-gradient(120deg, rgba(255,255,255,.65), rgba(226,241,226,.12));
        box-shadow: 0 0 90px rgba(255,255,255,.2);
    }

    .omira-glass-wave--one {
        width: 90vw;
        height: 52vw;
        left: -22vw;
        top: 22%;
        transform: rotate(-21deg);
    }

    .omira-glass-wave--two {
        width: 84vw;
        height: 47vw;
        right: -22vw;
        top: -18%;
        transform: rotate(-22deg);
        opacity: .76;
    }

    .omira-glass-arc {
        width: min(60vw, 760px);
        height: min(60vw, 760px);
        border: 1.5px solid rgba(92, 156, 116, .32);
        border-radius: 50%;
    }

    .omira-glass-arc--left {
        left: -32%;
        top: 14%;
    }

    .omira-glass-arc--top {
        right: -9%;
        top: -47%;
    }

    .omira-glass-arc--bottom {
        right: -13%;
        bottom: -52%;
    }

    .omira-glass-card {
        position: relative;
        width: 100%;
        max-width: 540px;
        min-width: 0;
        padding: 58px 52px 50px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, .88);
        border-radius: 28px;
        background: linear-gradient(144deg, rgba(255,255,255,.49), rgba(245,251,245,.27) 57%, rgba(255,255,255,.40));
        box-shadow:
            0 25px 75px rgba(37, 80, 52, .09),
            inset 0 1px 0 rgba(255,255,255,.86);
        -webkit-backdrop-filter: blur(27px) saturate(135%);
        backdrop-filter: blur(27px) saturate(135%);
    }

    .omira-glass-card::before {
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: radial-gradient(circle at 86% 5%, rgba(190, 222, 199, .22), transparent 45%);
        content: '';
        pointer-events: none;
    }

    .omira-glass-brand,
    .omira-glass-form {
        position: relative;
        z-index: 1;
    }

    .omira-glass-brand {
        margin-bottom: 42px;
        text-align: center;
    }

    .omira-glass-title {
        margin: 0;
        color: #103d32;
        font-size: clamp(39px, 5vw, 51px);
        font-weight: 800;
        letter-spacing: .15em;
        line-height: 1.15;
        text-indent: .15em;
    }

    .omira-glass-subtitle {
        margin: 12px 0 0;
        color: var(--omira-muted);
        font-size: 14px;
        font-weight: 500;
        line-height: 1.5;
    }

    .omira-glass-accent {
        display: block;
        width: 56px;
        height: 4px;
        margin: 17px auto 0;
        border-radius: 20px;
        background: linear-gradient(90deg, rgba(151, 211, 159, .3), #68b881 50%, #27684c);
        box-shadow: 0 2px 7px rgba(59, 135, 84, .12);
    }

    .omira-glass-error {
        margin: 0 0 18px;
        padding: 11px 14px;
        border: 1px solid #efc9c4;
        border-radius: 12px;
        background: rgba(255, 245, 244, .88);
        color: #a22b21;
        font-size: 13px;
        line-height: 1.5;
    }

    .omira-glass-sr-only {
        position: absolute !important;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .omira-glass-field {
        position: relative;
        margin-bottom: 14px;
    }

    .omira-glass-icon {
        position: absolute;
        z-index: 2;
        top: 50%;
        left: 17px;
        width: 21px;
        height: 21px;
        transform: translateY(-50%);
        color: #285c4e;
        pointer-events: none;
    }

    .omira-glass-input {
        display: block;
        width: 100%;
        height: 57px;
        padding: 0 50px 0 55px;
        border: 1px solid rgba(255, 255, 255, .90);
        border-radius: 16px;
        outline: 0;
        background: rgba(255, 255, 255, .57);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.72), 0 4px 12px rgba(57,92,60,.035);
        color: #173c32;
        font-size: 15px;
        transition: background .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    .omira-glass-input::placeholder {
        color: #74867e;
        opacity: 1;
    }

    .omira-glass-input:focus {
        border-color: rgba(71, 157, 107, .65);
        background: rgba(255,255,255,.83);
        box-shadow: 0 0 0 4px rgba(73, 153, 106, .12);
    }

    .omira-glass-eye {
        position: absolute;
        top: 50%;
        right: 12px;
        display: grid;
        place-items: center;
        width: 35px;
        height: 35px;
        padding: 0;
        transform: translateY(-50%);
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: #46675c;
        cursor: pointer;
    }

    .omira-glass-eye:hover,
    .omira-glass-eye:focus-visible {
        background: rgba(76, 141, 102, .10);
        outline: 0;
    }

    .omira-glass-eye svg {
        width: 21px;
        height: 21px;
    }

    .omira-glass-eye .omira-glass-eye-off {
        display: none;
    }

    .omira-glass-eye[aria-pressed="true"] .omira-glass-eye-on {
        display: none;
    }

    .omira-glass-eye[aria-pressed="true"] .omira-glass-eye-off {
        display: block;
    }

    .omira-glass-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin: 23px 0 22px;
        min-height: 22px;
    }

    .omira-glass-remember {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        color: #3b6153;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
    }

    .omira-glass-remember input {
        width: 18px;
        height: 18px;
        flex: 0 0 auto;
        margin: 0;
        accent-color: #28835e;
        cursor: pointer;
    }

    .omira-glass-forgot {
        color: #237b55;
        font-size: 13px;
        font-weight: 650;
        white-space: nowrap;
        text-decoration: none;
    }

    .omira-glass-forgot:hover,
    .omira-glass-forgot:focus-visible {
        color: #14533a;
        text-decoration: underline;
    }

    .omira-glass-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        min-height: 56px;
        padding: 12px 19px;
        border: 1px solid rgba(31, 107, 73, .27);
        border-radius: 15px;
        background: linear-gradient(104deg, #246e55 0%, #378b65 52%, #79b888 100%);
        box-shadow: 0 12px 26px rgba(39, 115, 75, .17), inset 0 1px 0 rgba(255,255,255,.24);
        color: #fff;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: .01em;
        cursor: pointer;
        transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
    }

    .omira-glass-submit svg {
        width: 20px;
        height: 20px;
        transition: transform .18s ease;
    }

    .omira-glass-submit:hover {
        transform: translateY(-1px);
        filter: brightness(1.045);
        box-shadow: 0 16px 30px rgba(39, 115, 75, .22), inset 0 1px 0 rgba(255,255,255,.24);
    }

    .omira-glass-submit:hover svg {
        transform: translateX(3px);
    }

    .omira-glass-submit:active {
        transform: translateY(0);
    }

    .omira-glass-submit:focus-visible {
        outline: 3px solid rgba(23, 99, 63, .4);
        outline-offset: 3px;
    }

    .omira-glass-submit:disabled {
        cursor: wait;
        opacity: .73;
        transform: none;
    }

    @media (max-width: 600px) {
        .omira-glass-page {
            padding: 28px 16px;
        }

        .omira-glass-card {
            max-width: 450px;
            padding: 46px 30px 40px;
            border-radius: 25px;
            -webkit-backdrop-filter: blur(20px) saturate(130%);
            backdrop-filter: blur(20px) saturate(130%);
        }

        .omira-glass-brand {
            margin-bottom: 34px;
        }

        .omira-glass-title {
            font-size: 39px;
        }

        .omira-glass-subtitle {
            font-size: 12.5px;
        }

        .omira-glass-orb--top {
            left: -45px;
            top: 5%;
        }

        .omira-glass-orb--bottom {
            width: 350px;
            height: 350px;
            left: -160px;
            bottom: -90px;
        }

        .omira-glass-arc--left {
            left: -510px;
        }

        .omira-glass-arc--top {
            right: -450px;
        }
    }

    @media (max-width: 390px) {
        .omira-glass-page {
            padding: 18px 13px;
        }

        .omira-glass-card {
            padding: 34px 23px 31px;
            border-radius: 22px;
        }

        .omira-glass-brand {
            margin-bottom: 29px;
        }

        .omira-glass-options {
            gap: 8px;
        }

        .omira-glass-remember,
        .omira-glass-forgot {
            font-size: 12px;
        }
    }

    @media (max-height: 680px) {
        .omira-glass-page {
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .omira-glass-card {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .omira-glass-brand {
            margin-bottom: 24px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .omira-glass-page .omira-glass-input,
        .omira-glass-page .omira-glass-submit,
        .omira-glass-page .omira-glass-submit svg {
            transition: none;
        }
    }
</style>

<div class="omira-glass-page">
    <div class="omira-glass-decor" aria-hidden="true">
        <span class="omira-glass-wave omira-glass-wave--one"></span>
        <span class="omira-glass-wave omira-glass-wave--two"></span>
        <span class="omira-glass-orb omira-glass-orb--top"></span>
        <span class="omira-glass-orb omira-glass-orb--bottom"></span>
        <span class="omira-glass-orb omira-glass-orb--right"></span>
        <span class="omira-glass-arc omira-glass-arc--left"></span>
        <span class="omira-glass-arc omira-glass-arc--top"></span>
        <span class="omira-glass-arc omira-glass-arc--bottom"></span>
    </div>

    <section class="omira-glass-card" aria-labelledby="omira-glass-title">
        <header class="omira-glass-brand">
            <h1 class="omira-glass-title" id="omira-glass-title">OMIRA</h1>
            <p class="omira-glass-subtitle">OMD Integrated Repair Application</p>
            <span class="omira-glass-accent" aria-hidden="true"></span>
        </header>

        @if ($errors->any())
            <div class="omira-glass-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form class="omira-glass-form" method="POST" action="{{ route('login.store') }}" id="omira-login-form">
            @csrf
            <div class="omira-glass-field">
                <label class="omira-glass-sr-only" for="email">Email</label>
                <svg class="omira-glass-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 7 9-7"></path>
                </svg>
                <input class="omira-glass-input" id="email" type="email" name="email"
                    value="{{ old('email') }}" placeholder="Email" autocomplete="email"
                    required autofocus>
            </div>

            <div class="omira-glass-field">
                <label class="omira-glass-sr-only" for="password">Password</label>
                <svg class="omira-glass-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="5" y="10" width="14" height="11" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                </svg>
                <input class="omira-glass-input" id="password" type="password" name="password"
                    placeholder="Password" autocomplete="current-password" required>
                <button class="omira-glass-eye" id="togglePassword" type="button"
                    aria-label="Tampilkan password" aria-pressed="false">
                    <svg class="omira-glass-eye-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"></path><circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <svg class="omira-glass-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m3 3 18 18M10.6 5.1A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a15 15 0 0 1-3.5 4.2M6.1 6.2C3.5 8.1 2 12 2 12s3.5 7 10 7c1 0 2-.2 2.9-.5"></path><path d="M10.3 10.3a3 3 0 0 0 3.4 3.4"></path>
                    </svg>
                </button>
            </div>

            <div class="omira-glass-options">
                <label class="omira-glass-remember">
                    <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat saya</span>
                </label>
                @if (\Illuminate\Support\Facades\Route::has('password.request'))
                    <a class="omira-glass-forgot" href="{{ route('password.request') }}">Lupa password?</a>
                @endif
            </div>

            <button class="omira-glass-submit" type="submit" id="omira-login-submit">
                <span>Login</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 12h15M13 6l6 6-6 6"></path>
                </svg>
            </button>
        </form>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const password = document.getElementById('password');
        const toggle = document.getElementById('togglePassword');
        const form = document.getElementById('omira-login-form');
        const submit = document.getElementById('omira-login-submit');

        if (password && toggle) {
            toggle.addEventListener('click', function () {
                const visible = password.type === 'password';
                password.type = visible ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', String(visible));
                toggle.setAttribute('aria-label', visible ? 'Sembunyikan password' : 'Tampilkan password');
            });
        }

        if (form && submit) {
            form.addEventListener('submit', function () {
                if (form.checkValidity()) {
                    submit.disabled = true;
                    submit.setAttribute('aria-busy', 'true');
                }
            });
        }
    });
</script>
@endsection
