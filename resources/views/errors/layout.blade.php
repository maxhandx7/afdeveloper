<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('code') · AF Developer</title>
    <link rel="icon" href="{{ asset('image/LOGO.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;1,300&family=Outfit:wght@300;400&family=DM+Mono&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{min-height:100vh;display:grid;place-items:center;background:#f5f4f0;color:#0e0e0f;font-family:'Outfit',sans-serif;padding:2rem}
        main{max-width:34rem}
        .code{font-family:'DM Mono',monospace;font-size:.75rem;letter-spacing:.2em;color:#8a96aa}
        h1{font-family:'Cormorant Garamond',Georgia,serif;font-weight:300;font-size:clamp(2.8rem,8vw,4.5rem);line-height:1;letter-spacing:-.02em;margin:1.25rem 0 1.5rem}
        h1 em{color:#5a6478}
        p{color:#5a6478;line-height:1.7;margin-bottom:2.25rem}
        a{display:inline-block;font-family:'DM Mono',monospace;font-size:.75rem;letter-spacing:.12em;color:#0e0e0f;border:1px solid #0e0e0f;padding:.9rem 1.6rem;text-decoration:none;transition:background .2s,color .2s}
        a:hover{background:#0e0e0f;color:#f5f4f0}
    </style>
</head>
<body>
<main>
    <span class="code">ERROR @yield('code')</span>
    <h1>@yield('heading')</h1>
    <p>@yield('message')</p>
    <a href="{{ url('/') }}">← Volver al inicio</a>
</main>
</body>
</html>
