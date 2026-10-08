@extends('layouts.web')
@section('title', $project->title.' — Proyecto de '.$business->name)
@section('description', \Illuminate\Support\Str::limit($project->description ?: strip_tags((string) $project->long_description), 155))
@if ($project->image)
    @section('og_image', asset($project->image))
@endif

@section('content')
<section class="page-head">
    <div class="wrap">
        <a class="crumb" href="{{ route('projects') }}">← Todos los proyectos</a>
        <h1 class="display">{{ $project->title }}</h1>
        @if ($project->description)
            <p class="lead">{{ $project->description }}</p>
        @endif
    </div>
</section>

<section class="section" style="padding-top:3rem">
    <div class="wrap case">
        <div>
            @if ($project->image)
                <img class="case-cover" src="{{ asset($project->image) }}" alt="Captura de {{ $project->title }}">
            @endif
            <div class="prose">
                {!! $project->long_description ?: '<p>Pronto agregaré el detalle de este proyecto.</p>' !!}
            </div>
        </div>

        <aside class="case-aside">
            <dl>
                @if (! empty($project->tech_stack))
                    <div>
                        <dt>Stack</dt>
                        <dd><ul class="pipeline pipeline-v" style="margin:.4rem 0 0">@foreach ($project->tech_stack as $tech)<li>{{ $tech }}</li>@endforeach</ul></dd>
                    </div>
                @endif
                <div>
                    <dt>Estado</dt>
                    <dd>En producción</dd>
                </div>
            </dl>
            <div class="actions">
                @if ($project->link)
                    <a class="btn btn-primary" href="{{ $project->link }}" target="_blank" rel="noopener">Abrir el proyecto</a>
                @endif
                @if ($project->repo_url)
                    <a class="btn btn-ghost" href="{{ $project->repo_url }}" target="_blank" rel="noopener"><i class="fa-brands fa-github" aria-hidden="true"></i> Ver el código</a>
                @endif
                <a class="btn btn-ghost" href="{{ route('home') }}#contacto">Quiero algo parecido</a>
            </div>
        </aside>
    </div>
</section>

@if ($others->isNotEmpty())
<section class="section">
    <div class="wrap">
        <h2 class="display" style="margin-bottom:2rem;font-size:clamp(1.6rem,3vw,2.2rem)">Otros proyectos</h2>
        <div class="projects">
            @foreach ($others as $project)
                @include('partials.project-row')
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
