@extends('layouts.web')
@section('title', $business->name)
@section('styles')
<style>
.teams-page { padding-top: calc(var(--nav-h) + var(--section-v)); padding-bottom: var(--section-v); }
.teams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2px;
    background: var(--sand-lt);
    margin-top: 4rem;
}
.team-card {
    background: var(--ivory);
    padding: 3rem 2.5rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 1.5rem;
    transition: background .3s;
}
.team-card:hover { background: var(--ivory-dim); }
.team-avatar {
    width: 72px; height: 72px;
    border-radius: 50%; object-fit: cover;
    filter: grayscale(20%);
    transition: filter .3s;
    border: 1px solid var(--sand-lt);
}
.team-card:hover .team-avatar { filter: grayscale(0); }
.team-info { border-top: 1px solid var(--sand-lt); width: 100%; padding-top: 1.5rem; }
.team-name {
    font-family: var(--f-display);
    font-size: 1.4rem; font-weight: 300;
    letter-spacing: -.02em; color: var(--ink); margin-bottom: .3rem;
}
.team-email {
    font-family: var(--f-mono); font-size: .65rem;
    letter-spacing: .1em; color: var(--slate-lt); margin-bottom: .8rem;
}
.team-role {
    display: inline-block;
    font-family: var(--f-mono); font-size: .6rem;
    letter-spacing: .16em; text-transform: uppercase;
    color: var(--slate); border: 1px solid var(--sand-lt);
    padding: .3rem .8rem; border-radius: 2px;
}
</style>
@endsection
@section('content')
<div class="teams-page">
    <div class="af-container">
        <p class="section-tag" data-reveal="fade">Equipo</p>
        <h1 class="display-h" data-reveal style="font-size:clamp(3rem,6vw,5rem)">
            Las personas<br><em>detrás</em>
        </h1>
    </div>
    <div class="af-container" style="padding-left:0;padding-right:0;max-width:100%">
        <div class="teams-grid">
            @foreach ($teams as $team)
            <div class="team-card" data-reveal data-delay="{{ $loop->index * 80 }}">
                <img class="team-avatar" src="{{ asset($team->image) }}" alt="{{ $team->name }}">
                <div class="team-info">
                    <h3 class="team-name">{{ $team->name }}</h3>
                    <p class="team-email">{{ $team->email }}</p>
                    <span class="team-role">{{ $team->rol }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="af-container" style="margin-top:4rem">
        <a href="{{ url('/') }}" class="af-btn af-btn-dark">← Volver</a>
    </div>
</div>
@endsection
@section('scripts')
<script>$(document).ready(function(){$('body').addClass('sidebar-icon-only');});</script>
@endsection