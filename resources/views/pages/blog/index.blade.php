@extends('layouts.web')
@section('title', 'Blog — '.$business->name)
@section('description', 'Notas sobre Laravel, integraciones con APIs y despliegues, escritas desde proyectos reales.')

@section('content')
<section class="page-head">
    <div class="wrap">
        <h1 class="display">Blog</h1>
        <p class="lead">Lo que voy aprendiendo construyendo, integrando y desplegando software para clientes reales.</p>
    </div>
</section>

<section class="section" style="padding-top:2rem">
    <div class="wrap">
        @if ($posts->isEmpty())
            <p class="empty">Todavía no hay artículos publicados.</p>
        @else
            @include('partials.post-list', ['posts' => $posts])

            @if ($posts->hasPages())
                <nav class="pager" aria-label="Paginación">
                    <span>@if ($posts->previousPageUrl())<a class="link" href="{{ $posts->previousPageUrl() }}">Más recientes</a>@endif</span>
                    <span>@if ($posts->nextPageUrl())<a class="link" href="{{ $posts->nextPageUrl() }}">Anteriores</a>@endif</span>
                </nav>
            @endif
        @endif
    </div>
</section>
@endsection
