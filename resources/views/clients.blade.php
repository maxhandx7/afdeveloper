@extends('layouts.web')
@section('title', $business->name)
@section('styles')
<style>
.clients-page { padding-top: calc(var(--nav-h) + var(--section-v)); padding-bottom: var(--section-v); }
.clients-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2px;
    background: var(--sand-lt);
    margin-top: 4rem;
}
.client-card {
    background: var(--ivory);
    padding: 3rem 2.5rem;
    position: relative;
    transition: background .3s;
}
.client-card:hover { background: var(--ivory-dim); }
.client-q {
    font-family: var(--f-display);
    font-size: 4.5rem; font-weight: 300;
    color: var(--sand); line-height: .75; margin-bottom: 1.5rem;
}
.client-text {
    font-family: var(--f-display);
    font-size: 1.1rem; font-weight: 300;
    font-style: italic; line-height: 1.75;
    color: var(--ink); margin-bottom: 2.5rem;
}
.client-author {
    display: flex; align-items: center; gap: 1rem;
    border-top: 1px solid var(--sand-lt); padding-top: 1.5rem;
}
.client-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; filter: grayscale(15%); }
.client-name {
    font-family: var(--f-mono); font-size: .64rem;
    letter-spacing: .14em; text-transform: uppercase; color: var(--slate);
}
</style>
@endsection
@section('content')
<div class="clients-page">
    <div class="af-container">
        <p class="section-tag" data-reveal="fade">Testimonios</p>
        <h1 class="display-h" data-reveal style="font-size:clamp(3rem,6vw,5rem)">
            Lo que dicen<br><em>de mí</em>
        </h1>
    </div>

    <div class="af-container" style="padding-left:0;padding-right:0;max-width:100%">
        <div class="clients-grid">
            @foreach ($clients as $client)
            <div class="client-card" data-reveal data-delay="{{ $loop->index * 80 }}">
                <div class="client-q">&ldquo;</div>
                <p class="client-text">{{ $client->description }}</p>
                <div class="client-author">
                    <img class="client-avatar" src="{{ asset($client->image) }}" alt="{{ $client->name }}">
                    <span class="client-name">{{ $client->name }}</span>
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