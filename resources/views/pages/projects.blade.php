@extends('layouts.web')
@section('title', 'Proyectos — '.$business->name)
@section('description', 'Plataformas en Laravel, integraciones con APIs y sistemas a medida desarrollados por AF Developer en Cali, Colombia.')

@section('content')
<section class="page-head">
    <div class="wrap">
        <h1 class="display">Proyectos</h1>
        <p class="lead">Cada uno resolvió un problema concreto: vender en línea, automatizar avisos, ordenar inventarios o conectar sistemas que no se hablaban.</p>
    </div>
</section>

<section class="section" style="padding-top:2rem">
    <div class="wrap">
        @if ($proyects->isEmpty())
            <p class="empty">Todavía no hay proyectos publicados.</p>
        @else
            <div class="projects">
                @foreach ($proyects as $project)
                    @include('partials.project-row')
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
