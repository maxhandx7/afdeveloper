<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $pageTitle = trim($__env->yieldContent('title')) ?: $business->name.' — Desarrollo backend e integraciones';
        $pageDescription = trim($__env->yieldContent('description')) ?: $business->shortDescription();
        $pageImage = trim($__env->yieldContent('og_image')) ?: asset('image/AFDEVELOPER_LOGO.png');
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $business->name }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:locale" content="es_CO">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#f5f7fa">
    <link rel="icon" href="{{ asset('image/LOGO.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,400..800&family=JetBrains+Mono:wght@500;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/brands.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}">
    @stack('head')
</head>
<body>
<a class="skip" href="#main">Saltar al contenido</a>

<header class="nav" data-nav>
    <div class="wrap nav-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="{{ $business->name }}, inicio">
            <span class="brand-mark" aria-hidden="true"></span>
            AF Developer
        </a>

        <ul class="nav-links" id="nav-links" data-nav-links>
            <li><a href="{{ route('projects') }}" @if (request()->routeIs('projects*')) aria-current="page" @endif>Proyectos</a></li>
            <li><a href="{{ route('home') }}#proceso">Cómo trabajo</a></li>
            <li><a href="{{ route('blog.index') }}" @if (request()->routeIs('blog.*')) aria-current="page" @endif>Blog</a></li>
            <li><a href="{{ route('home') }}#sobre-mi">Sobre mí</a></li>
        </ul>

        <a class="btn btn-primary nav-cta" href="{{ route('home') }}#contacto">Cuéntame tu proyecto</a>

        <button class="nav-toggle" type="button" aria-controls="nav-links" aria-expanded="false" aria-label="Abrir menú" data-nav-toggle>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
        </button>
    </div>
</header>

<main id="main">
    @yield('content')
</main>

<footer class="footer">
    <div class="wrap footer-inner">
        <p style="margin:0">© {{ date('Y') }} {{ $business->name }}. Hecho en Cali con Laravel.</p>

        <ul class="socials">
            @foreach (['linkedin' => 'fa-linkedin-in', 'github' => 'fa-github', 'instagram' => 'fa-instagram', 'facebook' => 'fa-facebook-f', 'twitter' => 'fa-x-twitter'] as $network => $icon)
                @if ($url = $business->setting($network))
                    <li><a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}"><i class="fa-brands {{ $icon }}"></i></a></li>
                @endif
            @endforeach
        </ul>

        <a class="quiet" href="{{ url('/admin') }}" rel="nofollow">Panel</a>
    </div>
</footer>

@include('partials.whatsapp')

<script src="{{ asset('js/site.js') }}?v={{ filemtime(public_path('js/site.js')) }}" defer></script>
@stack('scripts')
</body>
</html>
