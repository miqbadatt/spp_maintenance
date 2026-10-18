<div class="login-page" x-data="forgotPage()" x-init="init()">

    {{-- ══════════════════════════════════════════
         CANVAS — Particle / Geometric Background
    ══════════════════════════════════════════ --}}
    <canvas id="bgCanvas" class="login-canvas"></canvas>

    {{-- ══════════════════════════════════════════
         MESH GRADIENT BLOBS
    ══════════════════════════════════════════ --}}
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    {{-- ══════════════════════════════════════════
         GEOMETRIC GRID OVERLAY
    ══════════════════════════════════════════ --}}
    <div class="geo-grid"></div>

    {{-- ══════════════════════════════════════════
         FLOATING ORNAMENTS
    ══════════════════════════════════════════ --}}
    <div class="ornament ornament-1">﷽</div>
    <div class="ornament ornament-2">◈</div>
    <div class="ornament ornament-3">✦</div>
    <div class="ornament ornament-4">◈</div>

    {{-- ══════════════════════════════════════════
         MAIN CARD
    ══════════════════════════════════════════ --}}
    <div class="login-wrapper">

        {{-- Glowing border ring --}}
        <div class="card-ring"></div>

        <div class="login-card" :class="cardVisible ? 'card-in' : ''">

            {{-- ── LOGO ── --}}
            <div class="logo-area">
                <div class="logo-ring-outer">
                    <div class="logo-ring-inner">
                        <div class="logo-icon">
                            <span class="logo-text">LT</span>
                        </div>
                    </div>
                </div>
                <div class="logo-glow"></div>
            </div>

            {{-- ── HEADING ── --}}
            <div class="heading-area">
                @if (!$isVerified)
                    <h1 class="heading-main">
                        <span class="char-reveal" style="--d:0ms">L</span><span class="char-reveal"
                            style="--d:40ms">u</span><span class="char-reveal" style="--d:80ms">p</span><span
                            class="char-reveal" style="--d:120ms">a</span><span class="char-reveal"
                            style="--d:160ms">&nbsp;</span><span class="char-reveal"
                            style="--d:200ms">P</span><span class="char-reveal"
                            style="--d:240ms">a</span><span class="char-reveal"
                            style="--d:280ms">s</span><span class="char-reveal"
                            style="--d:320ms">s</span><span class="char-reveal"
                            style="--d:360ms">w</span><span class="char-reveal"
                            style="--d:400ms">o</span><span class="char-reveal"
                            style="--d:440ms">r</span><span class="char-reveal"
                            style="--d:480ms">d</span>
                    </h1>
                    <p class="heading-sub">Verifikasi akun Wali Murid menggunakan NIS santri</p>
                @else
                    <h1 class="heading-main">
                        <span class="char-reveal" style="--d:0ms">P</span><span class="char-reveal"
                            style="--d:40ms">a</span><span class="char-reveal" style="--d:80ms">s</span><span
                            class="char-reveal" style="--d:120ms">s</span><span class="char-reveal"
                            style="--d:160ms">w</span><span class="char-reveal"
                            style="--d:200ms">o</span><span class="char-reveal"
                            style="--d:240ms">r</span><span class="char-reveal"
                            style="--d:280ms">d</span><span class="char-reveal"
                            style="--d:320ms">&nbsp;</span><span class="char-reveal"
                            style="--d:360ms">B</span><span class="char-reveal"
                            style="--d:400ms">a</span><span class="char-reveal"
                            style="--d:440ms">r</span><span class="char-reveal"
                            style="--d:480ms">u</span>
                    </h1>
                    <p class="heading-sub">Buat kata sandi baru untuk akun Anda</p>
                @endif
                <div class="heading-line"></div>
            </div>

            {{-- ── SUCCESS MESSAGE ── --}}
            @if (session()->has('success'))
                <div class="success-box" x-data="{ show: true }" x-show="show" x-transition>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button @click="show=false"
                        style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:16px;line-height:1;">×</button>
                </div>
            @endif

            {{-- ── ERROR ── --}}
            @if (session()->has('error'))
                <div class="error-box" x-data="{ show: true }" x-show="show" x-transition>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 8v4m0 4h.01" />
                    </svg>
                    <span>{{ session('error') }}</span>
                    <button @click="show=false"
                        style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:16px;line-height:1;">×</button>
                </div>
            @endif

            {{-- ══════════════════════════════════════════
                 TAHAP 1: VERIFIKASI DATA SANTRI
            ══════════════════════════════════════════ --}}
            @if (!$isVerified)
                <form wire:submit.prevent="verifyData" class="login-form">

                    {{-- Email --}}
                    <div class="field-group">
                        <label class="field-label">
                            <span class="field-label-icon">✉</span>
                            Alamat Email Akun
                        </label>
                        <div class="field-wrap">
                            <input type="email" wire:model="email" class="field-input"
                                placeholder="nama@email.com" autocomplete="email">
                            <div class="field-border"></div>
                        </div>
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- NIS Santri --}}
                    <div class="field-group">
                        <label class="field-label">
                            <span class="field-label-icon">🎓</span>
                            NIS Santri (Nomor Induk Santri)
                        </label>
                        <div class="field-wrap">
                            <input type="text" wire:model="nis" class="field-input"
                                placeholder="Contoh: 2024001" autocomplete="off">
                            <div class="field-border"></div>
                        </div>
                        <div style="font-size:11px; color:var(--em-300); margin-top:2px;">
                            *Masukkan NIS santri yang terdaftar di bawah akun wali murid Anda.
                        </div>
                        @error('nis')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Submit Verifikasi --}}
                    <button type="submit" wire:loading.attr="disabled" class="submit-btn"
                        @mouseenter="$el.style.setProperty('--mx', event.offsetX+'px'); $el.style.setProperty('--my', event.offsetY+'px')"
                        @mousemove="$el.style.setProperty('--mx', event.offsetX+'px'); $el.style.setProperty('--my', event.offsetY+'px')">
                        <span class="submit-ripple"></span>
                        <span wire:loading.remove wire:target="verifyData" class="submit-text">
                            <span>Verifikasi Data Santri</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <span wire:loading wire:target="verifyData" class="submit-text *:animate-pulse">
                            <div class="flex items-center gap-2">
                                <svg class="spin-icon" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" opacity=".25" />
                                    <path d="M21 12a9 9 0 00-9-9" />
                                </svg>
                                Memeriksa Data...
                            </div>
                        </span>
                    </button>

                </form>
            @else
            {{-- ══════════════════════════════════════════
                 TAHAP 2: INPUT PASSWORD BARU
            ══════════════════════════════════════════ --}}
                <div style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 12px; padding: 14px 16px; margin-bottom: 20px; text-align: left;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #6ee7b7; display: flex; align-items: center; gap: 5px;">
                            ✓ Santri Terverifikasi
                        </span>
                        <button type="button" wire:click="resetVerification"
                            style="background: none; border: none; font-size: 11px; color: #fca5a5; cursor: pointer; text-decoration: underline;">
                            Ganti Data
                        </button>
                    </div>
                    <div style="font-size: 14.5px; font-weight: 600; color: white;">
                        {{ $siswaNama }}
                    </div>
                    <div style="font-size: 12px; color: var(--em-200); margin-top: 2px;">
                        NIS: <strong>{{ $nis }}</strong> • Kelas: <strong>{{ $siswaKelas }}</strong>
                    </div>
                    <div style="font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 4px;">
                        Wali Murid: {{ $waliNama }} ({{ $email }})
                    </div>
                </div>

                <form wire:submit.prevent="resetPassword" class="login-form">

                    {{-- Password Baru --}}
                    <div class="field-group">
                        <label class="field-label">
                            <span class="field-label-icon">🔒</span>
                            Password Baru
                        </label>
                        <div class="field-wrap">
                            <input :type="showPassword ? 'text' : 'password'" wire:model="password"
                                class="field-input" placeholder="Minimal 8 karakter" autocomplete="new-password">
                            <div class="field-border"></div>
                            <button type="button" @click="showPassword = !showPassword" class="eye-btn">
                                <svg x-show="!showPassword" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg x-show="showPassword" x-cloak width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="field-group">
                        <label class="field-label">
                            <span class="field-label-icon">🔒</span>
                            Konfirmasi Password Baru
                        </label>
                        <div class="field-wrap">
                            <input :type="showConfirm ? 'text' : 'password'" wire:model="password_confirmation"
                                class="field-input" placeholder="Ulangi password baru" autocomplete="new-password">
                            <div class="field-border"></div>
                            <button type="button" @click="showConfirm = !showConfirm" class="eye-btn">
                                <svg x-show="!showConfirm" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg x-show="showConfirm" x-cloak width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Reset --}}
                    <button type="submit" wire:loading.attr="disabled" class="submit-btn"
                        @mouseenter="$el.style.setProperty('--mx', event.offsetX+'px'); $el.style.setProperty('--my', event.offsetY+'px')"
                        @mousemove="$el.style.setProperty('--mx', event.offsetX+'px'); $el.style.setProperty('--my', event.offsetY+'px')">
                        <span class="submit-ripple"></span>
                        <span wire:loading.remove wire:target="resetPassword" class="submit-text">
                            <span>Simpan Password Baru</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5">
                                <path d="M5 13l4 4L19 7"></path>
                            </svg>
                        </span>
                        <span wire:loading wire:target="resetPassword" class="submit-text *:animate-pulse">
                            <div class="flex items-center gap-2">
                                <svg class="spin-icon" width="18" height="18" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" opacity=".25" />
                                    <path d="M21 12a9 9 0 00-9-9" />
                                </svg>
                                Menyimpan Password...
                            </div>
                        </span>
                    </button>

                </form>
            @endif

            {{-- ── FOOTER ── --}}
            <div class="card-footer">
                <a href="/login" wire:navigate class="back-link">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M19 12H5M12 5l-7 7 7 7" />
                    </svg>
                    Kembali ke Login
                </a>
            </div>

        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════
     STYLES
══════════════════════════════════════════ --}}
<style>
    html, body {
        background-color: var(--em-950) !important;
        overflow-x: hidden;
    }

    /* ── Page Shell ───────────────────────────────────── */
    .login-page {
        position: relative;
        min-height: calc(100vh - 66px);
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--em-950);
        padding: 40px 16px;
        overflow: hidden;
        font-family: var(--font-body);
    }

    /* ── Canvas ───────────────────────────────────────── */
    .login-canvas {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0.35;
        pointer-events: none;
    }

    /* ── Animated mesh blobs ──────────────────────────── */
    .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        pointer-events: none;
        will-change: transform;
    }

    .blob-1 {
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, transparent 70%);
        top: -200px;
        left: -200px;
        animation: blobFloat1 14s ease-in-out infinite;
    }

    .blob-2 {
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.18) 0%, transparent 70%);
        bottom: -150px;
        right: -100px;
        animation: blobFloat2 18s ease-in-out infinite;
    }

    .blob-3 {
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(6, 95, 70, 0.3) 0%, transparent 70%);
        top: 40%;
        left: 55%;
        animation: blobFloat3 10s ease-in-out infinite;
    }

    @keyframes blobFloat1 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(60px, 80px) scale(1.08); }
        66% { transform: translate(-40px, 40px) scale(0.95); }
    }

    @keyframes blobFloat2 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-80px, -60px) scale(1.1); }
    }

    @keyframes blobFloat3 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        40% { transform: translate(40px, -50px) scale(1.15); }
        80% { transform: translate(-30px, 20px) scale(0.9); }
    }

    /* ── Geometric grid ───────────────────────────────── */
    .geo-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(16, 185, 129, 0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(16, 185, 129, 0.04) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 20%, transparent 100%);
        pointer-events: none;
    }

    /* ── Floating ornaments ───────────────────────────── */
    .ornament {
        position: absolute;
        font-family: var(--font-arabic, serif);
        pointer-events: none;
        user-select: none;
        opacity: 0.06;
        color: var(--gd-300);
    }
    .ornament-1 { font-size: 56px; top: 8%; left: 8%; animation: floatSlow 20s ease-in-out infinite; }
    .ornament-2 { font-size: 28px; top: 75%; left: 12%; animation: floatSlow 15s ease-in-out infinite 3s; }
    .ornament-3 { font-size: 20px; top: 15%; right: 10%; animation: floatSlow 12s ease-in-out infinite 1.5s; }
    .ornament-4 { font-size: 32px; bottom: 12%; right: 8%; animation: floatSlow 17s ease-in-out infinite 4.5s; }

    @keyframes floatSlow {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-16px) rotate(4deg); }
    }

    /* ── Wrapper & Card ───────────────────────────────── */
    .login-wrapper {
        position: relative;
        width: 100%;
        max-width: 440px;
        margin: 0 16px;
        z-index: 10;
    }

    .card-ring {
        position: absolute;
        inset: -2px;
        border-radius: calc(var(--r-xl) + 2px);
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.4), rgba(245, 158, 11, 0.2), rgba(16, 185, 129, 0.05), transparent 60%);
        pointer-events: none;
        opacity: 0.7;
    }

    .login-card {
        position: relative;
        background: rgba(4, 26, 16, 0.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(16, 185, 129, 0.18);
        border-radius: var(--r-xl);
        padding: 36px 32px;
        box-shadow: 0 32px 80px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.04) inset;
        opacity: 0;
        transform: translateY(24px) scale(0.98);
        transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .login-card.card-in { opacity: 1; transform: translateY(0) scale(1); }

    /* ── Logo ─────────────────────────────────────────── */
    .logo-area { display: flex; justify-content: center; margin-bottom: 24px; position: relative; }
    .logo-ring-outer { width: 68px; height: 68px; border-radius: 50%; background: linear-gradient(135deg, var(--em-600), var(--gd-500)); padding: 2px; display: flex; align-items: center; justify-content: center; }
    .logo-ring-inner { width: 100%; height: 100%; border-radius: 50%; background: var(--em-950); display: flex; align-items: center; justify-content: center; }
    .logo-icon { width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, var(--em-700), var(--em-900)); display: flex; align-items: center; justify-content: center; }
    .logo-text { font-family: var(--font-display); font-size: 16px; font-weight: 700; color: var(--gd-300); }
    .logo-glow { position: absolute; width: 68px; height: 68px; border-radius: 50%; background: var(--em-500); filter: blur(20px); opacity: 0.3; pointer-events: none; }

    /* ── Heading ──────────────────────────────────────── */
    .heading-area { text-align: center; margin-bottom: 24px; }
    .heading-main { font-family: var(--font-display); font-size: 24px; font-weight: 700; color: white; display: flex; justify-content: center; flex-wrap: wrap; margin-bottom: 6px; }
    .char-reveal { display: inline-block; opacity: 0; transform: translateY(10px); animation: charIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; animation-delay: var(--d, 0ms); }
    @keyframes charIn { to { opacity: 1; transform: translateY(0); } }
    .heading-sub { font-size: 12.5px; color: var(--em-300); letter-spacing: 0.3px; opacity: 0; animation: fadeUp 0.6s ease forwards 0.8s; }
    .heading-line { width: 40px; height: 2px; background: linear-gradient(90deg, var(--gd-400), var(--em-500)); border-radius: 2px; margin: 12px auto 0; opacity: 0; animation: lineExpand 0.5s ease forwards 1s; transform-origin: center; transform: scaleX(0); }
    @keyframes lineExpand { to { opacity: 1; transform: scaleX(1); } }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    /* ── Success box ──────────────────────────────────── */
    .success-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.35);
        border-radius: var(--r-md);
        padding: 12px 14px;
        font-size: 13px;
        color: #6ee7b7;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    /* ── Error box ────────────────────────────────────── */
    .error-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(220, 38, 38, 0.1);
        border: 1px solid rgba(220, 38, 38, 0.3);
        border-radius: var(--r-md);
        padding: 12px 14px;
        font-size: 13px;
        color: #fca5a5;
        margin-bottom: 20px;
    }

    /* ── Form ─────────────────────────────────────────── */
    .login-form { display: flex; flex-direction: column; gap: 18px; }
    .field-group { display: flex; flex-direction: column; gap: 6px; }
    .field-label { font-size: 11px; font-weight: 600; letter-spacing: 1.2px; text-transform: uppercase; color: var(--em-300); display: flex; align-items: center; gap: 6px; }
    .field-label-icon { font-size: 13px; }
    .field-wrap { position: relative; }
    .field-input { width: 100%; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: var(--r-sm); padding: 12px 16px; color: white; font-family: var(--font-body); font-size: 14px; outline: none; transition: background 0.2s, border-color 0.2s, box-shadow 0.2s; position: relative; z-index: 1; }
    .field-input:focus { background: rgba(255, 255, 255, 0.09); border-color: var(--em-500); box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15); }
    .field-input::placeholder { color: rgba(255, 255, 255, 0.22); }
    .field-border { position: absolute; inset: 0; border-radius: var(--r-sm); pointer-events: none; }
    .field-error { font-size: 11.5px; color: #fca5a5; }

    /* Eye toggle */
    .eye-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: rgba(255, 255, 255, 0.35); cursor: pointer; padding: 4px; border-radius: 6px; transition: color 0.15s, background 0.15s; z-index: 2; display: flex; align-items: center; justify-content: center; }
    .eye-btn:hover { color: var(--em-300); background: rgba(255, 255, 255, 0.06); }

    /* ── Submit button ────────────────────────────────── */
    .submit-btn {
        position: relative;
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, var(--em-600), var(--em-500));
        border: none;
        border-radius: var(--r-sm);
        color: white;
        font-family: var(--font-body);
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        overflow: hidden;
        margin-top: 6px;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .submit-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4); }
    .submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }
    .submit-ripple { position: absolute; inset: 0; background: radial-gradient(circle at var(--mx, 50%) var(--my, 50%), rgba(255, 255, 255, 0.2) 0%, transparent 60%); opacity: 0; transition: opacity 0.3s; pointer-events: none; }
    .submit-btn:hover .submit-ripple { opacity: 1; }
    .submit-text { display: flex; align-items: center; justify-content: center; gap: 8px; }

    /* ── Footer ───────────────────────────────────────── */
    .card-footer { margin-top: 24px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.06); padding-top: 18px; }
    .back-link { display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; color: rgba(255, 255, 255, 0.4); text-decoration: none; transition: color 0.2s; font-weight: 500; }
    .back-link:hover { color: var(--em-300); }

    /* ── Responsive ───────────────────────────────────── */
    @media (max-width: 480px) {
        .login-card { padding: 28px 20px; }
        .heading-main { font-size: 22px; }
        .ornament-1, .ornament-4 { display: none; }
    }
</style>

{{-- ══════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════ --}}
<script>
    function forgotPage() {
        return {
            cardVisible: false,
            showPassword: false,
            showConfirm: false,
            init() {
                requestAnimationFrame(() => {
                    setTimeout(() => {
                        this.cardVisible = true;
                    }, 80);
                });
                this.$nextTick(() => initCanvas());
            }
        };
    }

    function initCanvas() {
        const canvas = document.getElementById('bgCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        let W, H, particles, mouse = { x: -999, y: -999 };

        function resize() {
            W = canvas.width = canvas.parentElement ? canvas.parentElement.offsetWidth : window.innerWidth;
            H = canvas.height = canvas.parentElement ? Math.max(canvas.parentElement.offsetHeight, window.innerHeight) : window.innerHeight;
        }

        function makeParticle() {
            const angle = Math.random() * Math.PI * 2;
            return {
                x: Math.random() * W,
                y: Math.random() * H,
                r: Math.random() * 1.8 + 0.4,
                vx: Math.cos(angle) * (Math.random() * 0.3 + 0.05),
                vy: Math.sin(angle) * (Math.random() * 0.3 + 0.05),
                color: Math.random() > 0.6 ?
                    `rgba(245,158,11,${Math.random() * 0.5 + 0.2})` : `rgba(16,185,129,${Math.random() * 0.4 + 0.15})`,
                life: Math.random(),
                lifeSpeed: Math.random() * 0.003 + 0.001,
            };
        }

        function init() {
            resize();
            particles = Array.from({ length: 120 }, makeParticle);
        }

        function draw() {
            ctx.clearRect(0, 0, W, H);
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 110) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(16,185,129,${(1 - dist / 110) * 0.12})`;
                        ctx.lineWidth = 0.5;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
                const mdx = particles[i].x - mouse.x;
                const mdy = particles[i].y - mouse.y;
                const mdist = Math.sqrt(mdx * mdx + mdy * mdy);
                if (mdist < 150) {
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(245,158,11,${(1 - mdist / 150) * 0.3})`;
                    ctx.lineWidth = 0.8;
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(mouse.x, mouse.y);
                    ctx.stroke();
                }
            }
            particles.forEach(p => {
                p.life += p.lifeSpeed;
                if (p.life > 1) p.life = 0;
                const alpha = Math.sin(p.life * Math.PI);
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = p.color.replace(/[\d.]+\)$/, `${alpha})`);
                ctx.fill();
                const dx = p.x - mouse.x;
                const dy = p.y - mouse.y;
                const d = Math.sqrt(dx * dx + dy * dy);
                if (d < 100) {
                    p.vx += (dx / d) * 0.08;
                    p.vy += (dy / d) * 0.08;
                }
                p.vx *= 0.99;
                p.vy *= 0.99;
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0) p.x = W;
                if (p.x > W) p.x = 0;
                if (p.y < 0) p.y = H;
                if (p.y > H) p.y = 0;
            });
            requestAnimationFrame(draw);
        }

        window.addEventListener('resize', () => { resize(); });
        window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });
        window.addEventListener('mouseleave', () => { mouse.x = -999; mouse.y = -999; });

        init();
        draw();
    }
</script>
