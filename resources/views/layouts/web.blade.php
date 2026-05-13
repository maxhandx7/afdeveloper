<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="{{ $business->shortDescription }}" />
    <meta name="author" content="Alan Carabali" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('image/LOGO.png') }}" sizes="32x32">
    <title>{{ $business->name }}</title>

    <!-- Fonts: Cormorant Garamond (display serif) + Outfit (body) + DM Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Outfit:wght@300;400;500;600&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap grid + modals (sin tema de color) -->
    {!! Html::style('afdeveloper/css/styles.css') !!}

    <style>
    /* ═══════════════════════════════════════════════════
       AF DEVELOPER — SISTEMA DE DISEÑO v2
       Concepto: "Arquitecto Silencioso"
       Paleta: ink #0e0e0f · ivory #f5f4f0 · slate #5a6478
    ═══════════════════════════════════════════════════ */
    :root {
        --ink:         #0e0e0f;
        --ink-soft:    #1c1c1e;
        --ivory:       #f5f4f0;
        --ivory-dim:   #ece9e2;
        --slate:       #5a6478;
        --slate-lt:    #8a96aa;
        --sand:        #c4beb4;
        --sand-lt:     #dedad3;

        --f-display:   'Cormorant Garamond', Georgia, serif;
        --f-body:      'Outfit', sans-serif;
        --f-mono:      'DM Mono', monospace;

        --nav-h:       72px;
        --pad-x:       clamp(1.5rem, 5vw, 5rem);
        --section-v:   clamp(6rem, 12vw, 11rem);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    body {
        font-family: var(--f-body);
        background: var(--ivory);
        color: var(--ink);
        overflow-x: hidden;
    }

    /* ── Custom cursor ── */
    #af-cursor, #af-ring {
        position: fixed;
        border-radius: 50%;
        pointer-events: none;
        transform: translate(-50%, -50%);
        z-index: 9999;
        will-change: left, top;
    }
    #af-cursor {
        width: 8px; height: 8px;
        background: var(--slate);
        transition: width .15s, height .15s;
        mix-blend-mode: multiply;
    }
    #af-ring {
        width: 32px; height: 32px;
        border: 1px solid var(--slate);
        opacity: .4;
        z-index: 9998;
        transition: width .3s ease, height .3s ease, opacity .3s;
    }
    .cursor-grow #af-cursor { width: 16px; height: 16px; }
    .cursor-grow #af-ring   { width: 52px; height: 52px; opacity: .15; }
    @media (hover: none) { #af-cursor, #af-ring { display: none; } }

    /* ── Navbar ── */
    #mainNav {
        position: fixed;
        inset: 0 0 auto 0;
        height: var(--nav-h);
        z-index: 500;
        display: flex;
        align-items: center;
        padding: 0 var(--pad-x);
        transition: background .5s, border-color .5s;
        background: transparent;
        border-bottom: 1px solid transparent;
    }
    #mainNav.scrolled {
        background: rgba(245,244,240,.94);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-color: var(--sand-lt);
    }
    .nav-inner {
        width: 100%; max-width: 1320px; margin: 0 auto;
        display: flex; align-items: center; justify-content: space-between;
    }
    .nav-logo img { width: 108px; display: block; }

    .nav-links { list-style: none; display: flex; gap: .2rem; }
    .nav-links a {
        font-family: var(--f-mono);
        font-size: .67rem;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: var(--slate);
        text-decoration: none;
        padding: .45rem 1rem;
        border-radius: 3px;
        position: relative;
        transition: color .2s;
    }
    .nav-links a::after {
        content: '';
        position: absolute;
        bottom: 0; left: 1rem; right: 1rem;
        height: 1px; background: var(--ink);
        transform: scaleX(0); transform-origin: left;
        transition: transform .3s ease;
    }
    .nav-links a:hover { color: var(--ink); }
    .nav-links a:hover::after { transform: scaleX(1); }

    /* Hamburger */
    .nav-burger {
        display: none;
        flex-direction: column;
        gap: 6px;
        background: none;
        border: none;
        padding: .3rem;
    }
    .nav-burger span { display: block; width: 22px; height: 1px; background: var(--ink); transition: .3s; }

    /* Mobile overlay */
    .nav-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: var(--ivory);
        z-index: 490;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2.2rem;
    }
    .nav-overlay.open { display: flex; }
    .nav-overlay a {
        font-family: var(--f-display);
        font-size: clamp(2rem, 6vw, 3rem);
        font-weight: 300;
        color: var(--ink);
        text-decoration: none;
        letter-spacing: -.02em;
        transition: color .2s;
    }
    .nav-overlay a:hover { color: var(--slate); }
    .nav-overlay-close {
        position: absolute; top: 1.8rem; right: var(--pad-x);
        font-family: var(--f-mono); font-size: .85rem;
        background: none; border: none; color: var(--slate);
        letter-spacing: .1em;
    }

    @media (max-width: 820px) {
        .nav-links { display: none; }
        .nav-burger { display: flex; }
    }

    /* ── Layout helpers ── */
    .af-container {
        width: 100%; max-width: 1320px;
        margin: 0 auto; padding: 0 var(--pad-x);
    }
    .section-tag {
        font-family: var(--f-mono);
        font-size: .63rem;
        letter-spacing: .25em;
        text-transform: uppercase;
        color: var(--slate-lt);
        display: inline-flex;
        align-items: center;
        gap: .8rem;
        margin-bottom: 2.5rem;
    }
    .section-tag::before {
        content: '';
        display: block;
        width: 28px; height: 1px;
        background: var(--sand);
    }

    .display-h {
        font-family: var(--f-display);
        font-weight: 300;
        line-height: 1.02;
        letter-spacing: -.025em;
    }
    .display-h em { font-style: italic; color: var(--slate); }

    /* Reveal */
    [data-reveal] {
        opacity: 0;
        transform: translateY(28px);
        transition: opacity .9s cubic-bezier(.22,.61,.36,1),
                    transform .9s cubic-bezier(.22,.61,.36,1);
    }
    [data-reveal].on { opacity: 1; transform: none; }
    [data-reveal="fade"] { transform: none; }

    /* Botones */
    .af-btn {
        display: inline-flex; align-items: center; gap: .7rem;
        font-family: var(--f-mono);
        font-size: .68rem;
        letter-spacing: .16em;
        text-transform: uppercase;
        text-decoration: none;
        padding: .85rem 1.9rem;
        border: 1px solid currentColor;
        border-radius: 2px;
        transition: background .25s, color .25s;
        background: transparent;
    }
    .af-btn-dark { color: var(--ink); }
    .af-btn-dark:hover { background: var(--ink); color: var(--ivory); }
    .af-btn-slate { color: var(--slate); }
    .af-btn-slate:hover { background: var(--slate); color: var(--ivory); }
    .af-btn-inv { color: var(--ivory); border-color: rgba(245,244,240,.3); }
    .af-btn-inv:hover { background: var(--ivory); color: var(--ink); }

    /* ── Footer ── */
    .af-footer {
        background: var(--ink);
        color: var(--sand);
        padding: 6rem 0 2.5rem;
    }
    .footer-grid {
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr;
        gap: 4rem;
        padding-bottom: 4rem;
        border-bottom: 1px solid rgba(255,255,255,.07);
        margin-bottom: 2.5rem;
    }
    @media (max-width: 768px) { .footer-grid { grid-template-columns: 1fr; gap: 2.5rem; } }

    .footer-brand-logo { width: 90px; opacity: .35; filter: invert(1); margin-bottom: 1.5rem; }
    .footer-brand p { font-size: .88rem; color: var(--slate-lt); line-height: 1.8; max-width: 260px; }

    .footer-col h5 {
        font-family: var(--f-mono);
        font-size: .6rem;
        letter-spacing: .22em;
        text-transform: uppercase;
        color: var(--slate-lt);
        margin-bottom: 1.4rem;
    }
    .footer-col a, .footer-col span {
        display: block;
        font-size: .85rem;
        color: var(--sand);
        text-decoration: none;
        line-height: 2.1;
        transition: color .2s;
    }
    .footer-col a:hover { color: #fff; }
    .footer-socials { display: flex; gap: .5rem; margin-top: .8rem; }
    .footer-social-icon {
        width: 34px; height: 34px;
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 50%;
        display: flex !important;
        align-items: center;
        justify-content: center;
        font-size: .78rem;
        color: var(--sand) !important;
        transition: border-color .2s, color .2s !important;
        line-height: 1 !important;
    }
    .footer-social-icon:hover { border-color: var(--slate-lt) !important; color: #fff !important; }

    .footer-copy {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: var(--f-mono);
        font-size: .63rem;
        letter-spacing: .1em;
        color: var(--slate-lt);
    }
    .footer-copy a { color: var(--slate-lt); text-decoration: none; transition: color .2s; }
    .footer-copy a:hover { color: #fff; }

    /* ── Modals ── */
    .af-modal .modal-content {
        background: var(--ivory);
        border: none;
        border-radius: 0;
        border-top: 2px solid var(--ink);
    }
    .af-modal .modal-header {
        border-bottom: 1px solid var(--sand-lt) !important;
        padding: 1.5rem 2.5rem;
    }
    .af-modal .btn-close { opacity: .3; }
    .af-modal .modal-body { padding: 3rem 2.5rem; }
    .modal-proj-title {
        font-family: var(--f-display);
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 300;
        letter-spacing: -.025em;
        margin-bottom: .5rem;
    }
    .modal-proj-desc { color: var(--slate); line-height: 1.8; }
    </style>

    @yield('styles')
</head>

<script async src="https://www.googletagmanager.com/gtag/js?id=G-PPN2T320CL"></script>
<script>
    window.dataLayer=window.dataLayer||[];
    function gtag(){dataLayer.push(arguments);}
    gtag('js',new Date()); gtag('config','G-PPN2T320CL');
</script>

<body id="page-top">

<!-- Custom cursor -->
<div id="af-cursor"></div>
<div id="af-ring"></div>

<!-- ══ NAV ══ -->
<nav id="mainNav">
    <div class="nav-inner">
        <div class="nav-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('image/AFDEVELOPER_LOGO.png') }}" alt="AF Developer">
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="{{ url('/#portfolio') }}">Portafolio</a></li>
            <li><a href="{{ url('/#skills') }}">Skills</a></li>
            <li><a href="{{ url('/#about') }}">Sobre mí</a></li>
            <li><a href="{{ url('/#testimonials') }}">Testimonios</a></li>
            <li><a href="{{ url('/#contact') }}">Contacto</a></li>
            @auth <li><a href="{{ route('home') }}">{{ Auth::user()->name }}</a></li> @endauth
        </ul>
        <button class="nav-burger" id="burgerBtn" aria-label="Abrir menú">
            <span></span><span></span>
        </button>
    </div>
</nav>

<!-- Mobile nav -->
<div class="nav-overlay" id="navOverlay">
    <button class="nav-overlay-close" id="overlayClose">cerrar ✕</button>
    <a href="{{ url('/#portfolio') }}" class="overlay-link">Portafolio</a>
    <a href="{{ url('/#skills') }}" class="overlay-link">Skills</a>
    <a href="{{ url('/#about') }}" class="overlay-link">Sobre mí</a>
    <a href="{{ url('/#testimonials') }}" class="overlay-link">Testimonios</a>
    <a href="{{ url('/#contact') }}" class="overlay-link">Contacto</a>
</div>

@yield('content')

<!-- ══ FOOTER ══ -->
<footer class="af-footer">
    <div class="af-container">
        <div class="footer-grid">
            <div class="footer-brand">
                <img src="{{ asset('image/AFDEVELOPER_LOGO.png') }}" class="footer-brand-logo" alt="AF Developer">
                <p>Desarrollador Backend especializado en soluciones escalables e integraciones API. Cali, Colombia.</p>
            </div>
            <div class="footer-col">
                <h5>Redes</h5>
                <div class="footer-socials">
                    <a class="footer-social-icon" href="{{ $business->configurations['facebook'] ?? '#' }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a class="footer-social-icon" href="{{ $business->configurations['twitter'] ?? '#' }}" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a class="footer-social-icon" href="{{ $business->configurations['linkedin'] ?? '#' }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    <a class="footer-social-icon" href="{{ $business->configurations['instagram'] ?? '#' }}" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h5>Contacto</h5>
                <a href="tel:{{ $business->phone }}">{{ $business->phone }}</a>
                <a href="mailto:{{ $business->mail }}">{{ $business->mail }}</a>
                <a href="https://www.google.com/maps?q={{ urlencode($business->address) }}" target="_blank">{{ $business->address }}</a>
            </div>
        </div>
        <div class="footer-copy">
            <span>&copy; {{ date('Y') }} AF Developer — afdeveloper.com</span>
            <a href="{{ route('login') }}">Admin</a>
        </div>
    </div>
</footer>

{!! Html::script('afdeveloper/js/jquery.min.js') !!}
{!! Html::script('afdeveloper/js/bootstrap.bundle.min.js') !!}

<script>
// ── Cursor ──
const afC = document.getElementById('af-cursor');
const afR = document.getElementById('af-ring');
let mx=window.innerWidth/2, my=window.innerHeight/2, rx=mx, ry=my;
document.addEventListener('mousemove', e => { mx=e.clientX; my=e.clientY; });
(function tick() {
    if(afC){ afC.style.left=mx+'px'; afC.style.top=my+'px'; }
    if(afR){ rx+=(mx-rx)*.1; ry+=(my-ry)*.1; afR.style.left=rx+'px'; afR.style.top=ry+'px'; }
    requestAnimationFrame(tick);
})();
document.querySelectorAll('a,button').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('cursor-grow'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('cursor-grow'));
});

// ── Nav scroll ──
const nav = document.getElementById('mainNav');
addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 50));

// ── Mobile nav ──
document.getElementById('burgerBtn').onclick  = () => document.getElementById('navOverlay').classList.add('open');
document.getElementById('overlayClose').onclick = () => document.getElementById('navOverlay').classList.remove('open');
document.querySelectorAll('.overlay-link').forEach(a =>
    a.addEventListener('click', () => document.getElementById('navOverlay').classList.remove('open'))
);

// ── Reveal on scroll ──
const revObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            const d = +(e.target.dataset.delay || 0);
            setTimeout(() => e.target.classList.add('on'), d);
            revObs.unobserve(e.target);
        }
    });
}, { threshold: 0.08 });
document.querySelectorAll('[data-reveal]').forEach(el => revObs.observe(el));
</script>

@yield('scripts')
</body>
</html>