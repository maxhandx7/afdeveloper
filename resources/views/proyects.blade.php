@extends('layouts.web')
@section('title', $business->name)
@section('styles')
<style>
.proj-page { padding-top: calc(var(--nav-h) + var(--section-v)); padding-bottom: var(--section-v); }
.proj-page-header { margin-bottom: 5rem; }
.proj-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5px;
    background: var(--sand-lt);
}
@media (max-width: 860px) { .proj-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px) { .proj-grid { grid-template-columns: 1fr; } }

.proj-tile {
    background: var(--ivory);
    overflow: hidden;
    position: relative;
    aspect-ratio: 4/3;
    cursor: pointer;
}
.proj-tile img { width:100%; height:100%; object-fit:cover; display:block;
    transition: transform .7s cubic-bezier(.22,.61,.36,1), filter .4s; filter: grayscale(15%); }
.proj-tile:hover img { transform: scale(1.06); filter: grayscale(0); }
.proj-tile-info {
    position: absolute; inset:0;
    background: rgba(14,14,15,.65);
    display: flex; flex-direction: column;
    justify-content: flex-end; padding: 2rem;
    opacity: 0; transition: opacity .3s;
    backdrop-filter: blur(3px);
}
.proj-tile:hover .proj-tile-info { opacity: 1; }
.proj-tile-title {
    font-family: var(--f-display);
    font-size: 1.5rem; font-weight: 300;
    color: #fff; letter-spacing: -.02em; margin-bottom: .4rem;
}
.proj-tile-desc { font-family: var(--f-mono); font-size: .6rem;
    letter-spacing: .15em; text-transform: uppercase; color: var(--sand); }
.proj-tile-btn {
    margin-top: 1rem;
    font-family: var(--f-mono); font-size: .6rem;
    letter-spacing: .15em; text-transform: uppercase;
    color: var(--ivory); border: 1px solid rgba(255,255,255,.3);
    padding: .5rem 1rem; border-radius: 2px; display: inline-block;
    background: transparent; transition: background .2s;
}
.proj-tile:hover .proj-tile-btn { background: rgba(255,255,255,.1); }
</style>
@endsection
@section('content')
<div class="proj-page">
    <div class="af-container">
        <div class="proj-page-header">
            <p class="section-tag" data-reveal="fade">Proyectos</p>
            <h1 class="display-h" data-reveal style="font-size:clamp(3rem,6vw,5rem)">
                Todo el<br><em>portafolio</em>
            </h1>
        </div>
    </div>

    <div class="proj-grid">
        @foreach ($proyects as $proyect)
        <div class="proj-tile" data-bs-toggle="modal" data-bs-target="#pm{{ $proyect->id }}"
             data-reveal data-delay="{{ $loop->index * 60 }}">
            <img src="{{ asset($proyect->image) }}" alt="{{ $proyect->title }}">
            <div class="proj-tile-info">
                <p class="proj-tile-desc">Proyecto</p>
                <h3 class="proj-tile-title">{{ $proyect->title }}</h3>
                <span class="proj-tile-btn">Ver detalles →</span>
            </div>
        </div>
        <div class="af-modal modal fade" id="pm{{ $proyect->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header border-0"><button class="btn-close" type="button" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <h2 class="modal-proj-title">{{ $proyect->title }}</h2>
                                <hr style="border-color:var(--sand-lt);margin:1.5rem 0">
                                <p class="modal-proj-desc mb-3"><strong>{{ $proyect->description }}</strong></p>
                                <p class="modal-proj-desc">{!! $proyect->long_description !!}</p>
                                <div class="mt-4">
                                    <a class="af-btn af-btn-dark" href="{{ $proyect->link }}" target="_blank">
                                        Ver proyecto <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="af-container" style="margin-top:4rem">
        <a href="{{ url('/') }}" class="af-btn af-btn-dark">← Volver</a>
    </div>
</div>
@endsection
@section('scripts')
<script>$(document).ready(function(){$('body').addClass('sidebar-icon-only');});</script>
@endsection