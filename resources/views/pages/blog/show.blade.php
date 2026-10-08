@extends('layouts.web')
@section('title', $post->title.' — '.$business->name)
@section('description', $post->meta_description ?: $post->summary())
@section('og_type', 'article')
@if ($post->image)
    @section('og_image', asset($post->image))
@endif

@section('content')
<article>
    <header class="page-head">
        <div class="wrap" style="max-width:52rem">
            <a class="crumb" href="{{ route('blog.index') }}">← Blog</a>
            <h1 class="display" style="font-size:clamp(2.2rem,5vw,3.8rem)">{{ $post->title }}</h1>
            <p class="muted" style="margin:1.25rem 0 0">
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->translatedFormat('j \d\e F \d\e Y') }}</time>,
                {{ $post->readingMinutes() }} min de lectura
            </p>
        </div>
    </header>

    <div class="section" style="padding-top:3rem">
        <div class="wrap" style="max-width:52rem">
            @if ($post->image)
                <img class="case-cover" src="{{ asset($post->image) }}" alt="">
            @endif
            <div class="prose">{!! $post->long_description !!}</div>

            <p style="margin-top:3rem">
                <a class="btn btn-primary" href="{{ route('home') }}#contacto">¿Tienes un problema parecido? Hablemos</a>
            </p>
        </div>
    </div>
</article>

@if ($related->isNotEmpty())
<section class="section">
    <div class="wrap" style="max-width:52rem">
        <h2 class="display" style="margin-bottom:1.5rem;font-size:clamp(1.5rem,3vw,2rem)">Sigue leyendo</h2>
        @include('partials.post-list', ['posts' => $related])
    </div>
</section>
@endif

@push('scripts')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => $post->meta_description ?: $post->summary(),
    'datePublished' => $post->published_at?->toAtomString(),
    'dateModified' => $post->updated_at?->toAtomString(),
    'image' => $post->image ? asset($post->image) : null,
    'author' => ['@type' => 'Person', 'name' => 'Alan Carabali'],
    'mainEntityOfPage' => route('blog.show', $post),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
@endsection
