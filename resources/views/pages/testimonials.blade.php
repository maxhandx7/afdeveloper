@extends('layouts.web')
@section('title', 'Testimonios — '.$business->name)
@section('description', 'Lo que dicen los clientes que han trabajado con AF Developer.')

@section('content')
<section class="page-head">
    <div class="wrap">
        <h1 class="display">Testimonios</h1>
        <p class="lead">Lo que dicen las personas y empresas con las que he trabajado.</p>
    </div>
</section>
<section class="section">
    <div class="wrap">
        @if ($clients->isEmpty())
            <p class="empty">Todavía no hay testimonios publicados.</p>
        @else
            <div class="quotes">
                @foreach ($clients as $testimonial)
                    <figure class="quote">
                        <blockquote>{{ $testimonial->description }}</blockquote>
                        <figcaption>
                            @if ($testimonial->image)<img src="{{ asset($testimonial->image) }}" alt="" loading="lazy">@endif
                            <div>
                                <strong>{{ $testimonial->name }}</strong>
                                @if ($testimonial->role)<span>{{ $testimonial->role }}</span>@endif
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
