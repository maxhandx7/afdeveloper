@extends('layouts.web')

@section('content')
{{-- ── Hero: el diagrama ─────────────────────────────────────── --}}
<section class="hero">
    <div class="wrap hero-grid">
        <div>
            @if ($availability = $business->setting('availability'))
                <p class="status">{{ $availability }}</p>
            @endif
            <h1 class="display">Conecto los sistemas de tu negocio para que trabajen juntos.</h1>
            <p class="lead">
                Soy Alan Carabali, desarrollador backend en Cali. Construyo plataformas en Laravel
                e integro lo que ya usas —tiendas, ERP, WhatsApp, la nube— para que la información
                fluya sola y nadie tenga que copiar datos a mano.
            </p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="#contacto">Cuéntame tu proyecto</a>
                <a class="btn btn-ghost" href="{{ route('projects') }}">Ver proyectos</a>
            </div>
        </div>
        <div>
            @include('partials.diagram')
        </div>
    </div>
</section>

{{-- ── Proyectos ─────────────────────────────────────────────── --}}
@if ($proyectos->isNotEmpty())
<section class="section" id="proyectos">
    <div class="wrap">
        <div class="section-head">
            <div>
                <h2 class="display">Proyectos en producción</h2>
                <p>Sistemas reales que usan empresas y personas todos los días.</p>
            </div>
            <a class="link" href="{{ route('projects') }}">Ver los {{ $projectsCount }}</a>
        </div>

        <div class="projects">
            @foreach ($proyectos as $project)
                @include('partials.project-row')
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Cómo trabajo ──────────────────────────────────────────── --}}
<section class="section section-dark" id="proceso">
    <div class="wrap">
        <div class="section-head">
            <div>
                <h2 class="display">Cómo trabajo</h2>
                <p>Antes de escribir código entiendo cómo se mueve la información hoy en tu negocio.</p>
            </div>
        </div>
        <ol class="process">
            <li>
                <h3>Entender el flujo</h3>
                <p>Reviso qué sistemas usas, dónde se repite trabajo y qué datos se pierden en el camino.</p>
            </li>
            <li>
                <h3>Diseñar la conexión</h3>
                <p>Propongo la arquitectura y el alcance por escrito, con tiempos y costo claros.</p>
            </li>
            <li>
                <h3>Construir y probar</h3>
                <p>Desarrollo por entregas que puedes ver funcionando, con pruebas automáticas.</p>
            </li>
            <li>
                <h3>Desplegar y acompañar</h3>
                <p>Lo pongo en producción, te capacito y quedo disponible para soporte y mejoras.</p>
            </li>
        </ol>
    </div>
</section>

{{-- ── Sobre mí ──────────────────────────────────────────────── --}}
<section class="section" id="sobre-mi">
    <div class="wrap about">
        <div class="about-photo">
            <img src="{{ asset($business->logo ?: 'image/Alan.webp') }}" alt="Alan Carabali" loading="lazy">
        </div>
        <div>
            <h2 class="display" style="margin-bottom:1.5rem">Sobre mí</h2>
            <div class="about-text">
                @if ($business->description)
                    {!! $business->description !!}
                @else
                    <p>Desarrollador backend con experiencia en PHP, Laravel e integraciones con APIs de terceros.</p>
                @endif
            </div>
            <dl class="facts">
                <div><dt>Especialidad</dt><dd>Laravel e integraciones</dd></div>
                <div><dt>Proyectos publicados</dt><dd>{{ $projectsCount }}</dd></div>
                <div><dt>Base</dt><dd>Cali, Colombia</dd></div>
            </dl>
        </div>
    </div>
</section>

{{-- ── Testimonios ───────────────────────────────────────────── --}}
@if ($clients->isNotEmpty())
<section class="section" id="testimonios">
    <div class="wrap">
        <div class="section-head">
            <h2 class="display">Lo que dicen los clientes</h2>
            @if ($clients->count() > 3)
                <a class="link" href="{{ route('testimonials') }}">Ver todos</a>
            @endif
        </div>
        <div class="quotes">
            @foreach ($clients->take(3) as $testimonial)
                <figure class="quote">
                    <blockquote>{{ $testimonial->description }}</blockquote>
                    <figcaption>
                        @if ($testimonial->image)
                            <img src="{{ asset($testimonial->image) }}" alt="" loading="lazy">
                        @endif
                        <div>
                            <strong>{{ $testimonial->name }}</strong>
                            @if ($testimonial->role)<span>{{ $testimonial->role }}</span>@endif
                        </div>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Blog ──────────────────────────────────────────────────── --}}
@if ($posts->isNotEmpty())
<section class="section" id="blog">
    <div class="wrap">
        <div class="section-head">
            <div>
                <h2 class="display">Notas recientes</h2>
                <p>Lo que voy aprendiendo en proyectos reales.</p>
            </div>
            <a class="link" href="{{ route('blog.index') }}">Ir al blog</a>
        </div>
        @include('partials.post-list', ['posts' => $posts])
    </div>
</section>
@endif

{{-- ── Contacto ──────────────────────────────────────────────── --}}
<section class="section section-dark" id="contacto">
    <div class="wrap contact">
        <div>
            <h2 class="display">Cuéntame qué necesitas conectar</h2>
            <p class="muted" style="margin-top:1.25rem;max-width:28rem">
                Descríbeme el problema con tus palabras, sin tecnicismos. Te respondo con preguntas
                concretas o una propuesta inicial.
            </p>
            <ul class="channels">
                @if ($business->mail)
                    <li><a href="mailto:{{ $business->mail }}"><i class="fa-solid fa-envelope" aria-hidden="true"></i>{{ $business->mail }}</a></li>
                @endif
                @if ($whatsapp = preg_replace('/\D/', '', (string) $business->setting('whatsapp', '')))
                    <li><a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i>WhatsApp</a></li>
                @endif
                @if ($linkedin = $business->setting('linkedin'))
                    <li><a href="{{ $linkedin }}" target="_blank" rel="noopener"><i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>LinkedIn</a></li>
                @endif
            </ul>
        </div>

        <form class="form" action="{{ route('contact.store') }}" method="post" novalidate data-contact-form>
            @csrf
            <div class="row">
                <div class="field">
                    <label for="name">Nombre</label>
                    <input id="name" name="name" type="text" autocomplete="name" required maxlength="120">
                    <span class="field-error" aria-live="polite"></span>
                </div>
                <div class="field">
                    <label for="email">Correo</label>
                    <input id="email" name="email" type="email" autocomplete="email" required maxlength="180">
                    <span class="field-error" aria-live="polite"></span>
                </div>
            </div>
            <div class="field">
                <label for="phone">WhatsApp o teléfono <small>(opcional)</small></label>
                <input id="phone" name="phone" type="tel" autocomplete="tel" maxlength="30">
                <span class="field-error" aria-live="polite"></span>
            </div>
            <div class="field">
                <label for="message">¿Qué necesitas?</label>
                <textarea id="message" name="message" required minlength="10" maxlength="5000"
                          placeholder="Ej: vendemos por WooCommerce y facturamos en otro sistema; queremos que los pedidos pasen solos."></textarea>
                <span class="field-error" aria-live="polite"></span>
            </div>
            <div class="hp" aria-hidden="true">
                <label for="website">No llenar este campo</label>
                <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
            </div>
            <div>
                <button class="btn btn-primary" type="submit">Enviar mensaje</button>
            </div>
            <p class="form-status" role="status" data-status></p>
        </form>
    </div>
</section>
@endsection

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/solid.min.css">
@endpush
