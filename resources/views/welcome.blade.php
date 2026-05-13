@extends('layouts.web')
@section('title', $business->name)
@section('styles')
<style>
/* ══════════════════════════════════════
   HERO
══════════════════════════════════════ */
.hero {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
    padding-top: var(--nav-h);
    overflow: hidden;
}
@media (max-width: 900px) {
    .hero { grid-template-columns: 1fr; }
    .hero-visual { display: none; }
}

.hero-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: var(--section-v) var(--pad-x) var(--section-v) var(--pad-x);
    max-width: 720px;
}
.hero-eyebrow {
    font-family: var(--f-mono);
    font-size: .65rem;
    letter-spacing: .28em;
    text-transform: uppercase;
    color: var(--slate-lt);
    margin-bottom: 2.5rem;
    display: flex;
    align-items: center;
    gap: .8rem;
}
.hero-eyebrow::before {
    content: '';
    display: block;
    width: 28px; height: 1px;
    background: var(--sand);
}
.hero-name {
    font-family: var(--f-display);
    font-size: clamp(4rem, 9vw, 8rem);
    font-weight: 300;
    line-height: .96;
    letter-spacing: -.035em;
    color: var(--ink);
    margin-bottom: 1.2rem;
}
.hero-name em { font-style: italic; color: var(--slate); display: block; }
.hero-role {
    font-family: var(--f-mono);
    font-size: .75rem;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--slate-lt);
    margin-bottom: 3rem;
    padding-left: 2px;
}
.hero-cta { display: flex; gap: 1.2rem; flex-wrap: wrap; }

/* Scroll indicator */
.hero-scroll {
    position: absolute;
    bottom: 2.5rem;
    left: var(--pad-x);
    display: flex;
    align-items: center;
    gap: .8rem;
    font-family: var(--f-mono);
    font-size: .6rem;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--slate-lt);
}
.hero-scroll-line {
    width: 40px; height: 1px;
    background: var(--sand);
    transform-origin: left;
    animation: expandLine 2s 1s ease forwards;
    transform: scaleX(0);
}
@keyframes expandLine { to { transform: scaleX(1); } }

/* Hero right — decorative panel */
.hero-visual {
    background: var(--ink);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hero-visual-logo {
    width: 55%;
    opacity: .06;
    filter: invert(1);
    animation: floatLogo 8s ease-in-out infinite;
}
@keyframes floatLogo {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-16px); }
}
.hero-visual-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(245,244,240,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(245,244,240,.04) 1px, transparent 1px);
    background-size: 60px 60px;
}
.hero-year {
    position: absolute;
    bottom: 2.5rem;
    right: 2.5rem;
    font-family: var(--f-mono);
    font-size: .6rem;
    letter-spacing: .2em;
    color: rgba(245,244,240,.2);
}

/* ══════════════════════════════════════
   TICKER / marquee
══════════════════════════════════════ */
.ticker-wrap {
    overflow: hidden;
    background: var(--ink);
    padding: 1.1rem 0;
    border-top: 1px solid rgba(255,255,255,.05);
    border-bottom: 1px solid rgba(255,255,255,.05);
}
.ticker-track {
    display: flex;
    gap: 3rem;
    width: max-content;
    animation: ticker 24s linear infinite;
}
.ticker-track:hover { animation-play-state: paused; }
.ticker-item {
    font-family: var(--f-mono);
    font-size: .65rem;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: rgba(245,244,240,.25);
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 3rem;
}
.ticker-item::after {
    content: '·';
    color: var(--slate);
    font-size: 1rem;
}
@keyframes ticker { from { transform: translateX(0); } to { transform: translateX(-50%); } }

/* ══════════════════════════════════════
   PORTFOLIO
══════════════════════════════════════ */
#portfolio {
    padding: var(--section-v) 0;
    background: var(--ivory);
}
.portfolio-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 5rem;
    gap: 2rem;
    flex-wrap: wrap;
}
.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5px;
    background: var(--sand-lt);
}
@media (max-width: 860px) { .portfolio-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px) { .portfolio-grid { grid-template-columns: 1fr; } }

.portfolio-tile {
    background: var(--ivory);
    overflow: hidden;
    position: relative;
    cursor: pointer;
    aspect-ratio: 4/3;
}
.portfolio-tile img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .7s cubic-bezier(.22,.61,.36,1), filter .4s;
    filter: grayscale(20%);
}
.portfolio-tile:hover img { transform: scale(1.06); filter: grayscale(0); }
.portfolio-tile-overlay {
    position: absolute;
    inset: 0;
    background: rgba(14,14,15,.6);
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-end;
    padding: 2rem;
    opacity: 0;
    transition: opacity .35s;
    backdrop-filter: blur(2px);
}
.portfolio-tile:hover .portfolio-tile-overlay { opacity: 1; }
.tile-title {
    font-family: var(--f-display);
    font-size: 1.6rem;
    font-weight: 300;
    color: #fff;
    letter-spacing: -.02em;
    margin-bottom: .4rem;
}
.tile-tag {
    font-family: var(--f-mono);
    font-size: .6rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--sand);
}

.portfolio-more { text-align: right; margin-top: 3rem; }

/* ══════════════════════════════════════
   SKILLS
══════════════════════════════════════ */
#skills {
    padding: var(--section-v) 0;
    background: var(--ink);
    color: var(--ivory);
}
.skills-layout {
    display: grid;
    grid-template-columns: 1fr 1.6fr;
    gap: 6rem;
    align-items: start;
}
@media (max-width: 860px) { .skills-layout { grid-template-columns: 1fr; gap: 3rem; } }

.skills-intro .display-h { font-size: clamp(2.5rem, 5vw, 4rem); color: var(--ivory); }
.skills-intro .display-h em { color: var(--slate-lt); }
.skills-sub {
    font-size: .92rem;
    color: var(--slate-lt);
    line-height: 1.8;
    margin-top: 1.5rem;
}

.skills-groups { display: flex; flex-direction: column; gap: 2.5rem; }
.skill-group-label {
    font-family: var(--f-mono);
    font-size: .6rem;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--slate-lt);
    margin-bottom: 1rem;
    padding-bottom: .7rem;
    border-bottom: 1px solid rgba(255,255,255,.07);
}
.skill-pills { display: flex; flex-wrap: wrap; gap: .6rem; }
.skill-pill {
    font-family: var(--f-mono);
    font-size: .7rem;
    letter-spacing: .1em;
    padding: .5rem 1.1rem;
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 2px;
    color: var(--sand);
    transition: border-color .2s, color .2s;
}
.skill-pill:hover { border-color: var(--slate-lt); color: #fff; }

/* ══════════════════════════════════════
   SOBRE MÍ
══════════════════════════════════════ */
#about {
    padding: var(--section-v) 0;
    background: var(--ivory-dim);
}
.about-layout {
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 8rem;
    align-items: center;
}
@media (max-width: 860px) { .about-layout { grid-template-columns: 1fr; gap: 3rem; } }

.about-img-wrap {
    position: relative;
}
.about-img {
    width: 100%;
    aspect-ratio: 3/4;
    object-fit: cover;
    display: block;
    filter: grayscale(15%);
}
.about-img-frame {
    position: absolute;
    inset: 1.5rem -1.5rem -1.5rem 1.5rem;
    border: 1px solid var(--sand-lt);
    z-index: -1;
}
.about-tag { color: var(--slate-lt); }
.about-title { font-size: clamp(2.5rem, 5vw, 4.2rem); color: var(--ink); margin-bottom: 2rem; }
.about-text {
    font-size: .97rem;
    line-height: 1.85;
    color: var(--slate);
}
.about-data {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin: 3rem 0;
    border-top: 1px solid var(--sand-lt);
    border-bottom: 1px solid var(--sand-lt);
    padding: 2rem 0;
}
.about-data-item span {
    font-family: var(--f-mono);
    font-size: .6rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--slate-lt);
    display: block;
    margin-bottom: .4rem;
}
.about-data-item strong {
    font-family: var(--f-display);
    font-size: 2rem;
    font-weight: 300;
    color: var(--ink);
    letter-spacing: -.02em;
}

/* ══════════════════════════════════════
   TESTIMONIOS
══════════════════════════════════════ */
#testimonials {
    padding: var(--section-v) 0;
    background: var(--ivory);
}
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2px;
    background: var(--sand-lt);
    margin-top: 4rem;
}
.testi-card {
    background: var(--ivory);
    padding: 3rem;
    position: relative;
    transition: background .3s;
}
.testi-card:hover { background: var(--ivory-dim); }
.testi-quote {
    font-family: var(--f-display);
    font-size: 4rem;
    font-weight: 300;
    color: var(--sand);
    line-height: .8;
    margin-bottom: 1.5rem;
}
.testi-text {
    font-family: var(--f-display);
    font-size: 1.15rem;
    font-weight: 300;
    font-style: italic;
    line-height: 1.7;
    color: var(--ink);
    margin-bottom: 2rem;
}
.testi-author {
    display: flex;
    align-items: center;
    gap: 1rem;
    border-top: 1px solid var(--sand-lt);
    padding-top: 1.5rem;
}
.testi-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    object-fit: cover;
    filter: grayscale(20%);
}
.testi-name {
    font-family: var(--f-mono);
    font-size: .68rem;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--slate);
}

/* ══════════════════════════════════════
   CONTACTO
══════════════════════════════════════ */
#contact {
    padding: var(--section-v) 0;
    background: var(--ink);
    color: var(--ivory);
}
.contact-layout {
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 8rem;
    align-items: start;
}
@media (max-width: 860px) { .contact-layout { grid-template-columns: 1fr; gap: 3rem; } }

.contact-left .display-h { font-size: clamp(2.8rem, 5vw, 4.5rem); color: var(--ivory); }
.contact-left .display-h em { color: var(--slate-lt); }
.contact-info {
    margin-top: 3rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.contact-info a {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: .88rem;
    color: var(--slate-lt);
    text-decoration: none;
    transition: color .2s;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255,255,255,.06);
}
.contact-info a:last-child { border-bottom: none; }
.contact-info a:hover { color: var(--ivory); }
.contact-info i { color: var(--slate); width: 16px; text-align: center; }

/* Form */
.af-form { display: flex; flex-direction: column; gap: 0; }
.af-field {
    position: relative;
    border-bottom: 1px solid rgba(255,255,255,.1);
    transition: border-color .2s;
}
.af-field:focus-within { border-color: var(--slate-lt); }
.af-field label {
    display: block;
    font-family: var(--f-mono);
    font-size: .58rem;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: var(--slate-lt);
    padding-top: 1.5rem;
    margin-bottom: .4rem;
    transition: color .2s;
}
.af-field:focus-within label { color: var(--ivory); }
.af-field input,
.af-field textarea {
    display: block;
    width: 100%;
    background: transparent;
    border: none;
    outline: none;
    color: var(--ivory);
    font-family: var(--f-body);
    font-size: 1rem;
    padding-bottom: 1.2rem;
    resize: none;
    caret-color: var(--slate-lt);
}
.af-field input::placeholder,
.af-field textarea::placeholder { color: rgba(245,244,240,.15); }
.af-field textarea { min-height: 120px; }
.form-actions { margin-top: 2.5rem; display: flex; align-items: center; gap: 1.5rem; }
.form-feedback {
    font-family: var(--f-mono);
    font-size: .68rem;
    letter-spacing: .1em;
}
.form-feedback.ok { color: #7dcea0; }
.form-feedback.err { color: #f1948a; }
</style>
@endsection

@section('content')

<!-- ══ HERO ══ -->
<section class="hero" style="position:relative;">
    <div class="hero-content">
        <p class="hero-eyebrow" data-reveal data-delay="0">Backend Developer · Cali, Colombia</p>
        <h1 class="hero-name" data-reveal data-delay="80">
            Alan
            <em>Carabali</em>
        </h1>
        <p class="hero-role" data-reveal data-delay="160">af developer · afdeveloper.com</p>
        <div class="hero-cta" data-reveal data-delay="240">
            <a href="#portfolio" class="af-btn af-btn-dark">Ver portafolio</a>
            <a href="#contact" class="af-btn af-btn-slate">Contáctame</a>
        </div>
    </div>
    <div class="hero-visual">
        <div class="hero-visual-grid"></div>
        <img class="hero-visual-logo" src="{{ asset('image/AFDEVELOPER_LOGO.png') }}" alt="">
        <span class="hero-year">© {{ date('Y') }}</span>
    </div>
    <div class="hero-scroll" style="position:absolute">
        <span class="hero-scroll-line"></span>
        <span>scroll</span>
    </div>
</section>

<!-- ══ TICKER ══ -->
<div class="ticker-wrap">
    <div class="ticker-track">
        @foreach (['JavaScript','PHP','Laravel','ReactJS','VueJS','NodeJS','Bootstrap','APIs REST','SOAP','Azure DevOps','Google Cloud','Digital Ocean','Git','Dolibarr','WordPress','jQuery',
                   'JavaScript','PHP','Laravel','ReactJS','VueJS','NodeJS','Bootstrap','APIs REST','SOAP','Azure DevOps','Google Cloud','Digital Ocean','Git','Dolibarr','WordPress','jQuery'] as $tech)
        <span class="ticker-item">{{ $tech }}</span>
        @endforeach
    </div>
</div>

<!-- ══ PORTAFOLIO ══ -->
<section id="portfolio">
    <div class="af-container">
        <div class="portfolio-header">
            <div>
                <p class="section-tag" data-reveal="fade">Trabajos</p>
                <h2 class="display-h" data-reveal style="font-size:clamp(2.5rem,5vw,4rem)">
                    Portafolio<br><em>seleccionado</em>
                </h2>
            </div>
            <a href="{{ route('proyects') }}" class="af-btn af-btn-dark" data-reveal="fade">Ver todo</a>
        </div>
    </div>

    <div class="portfolio-grid">
        @if (!is_null($proyectos) && $proyectos->isNotEmpty())
            @foreach ($proyectos as $proyect)
            <div class="portfolio-tile"
                 data-bs-toggle="modal"
                 data-bs-target="#pm{{ $proyect->id }}"
                 data-reveal data-delay="{{ $loop->index * 80 }}">
                <img src="{{ asset($proyect->image) }}" alt="{{ $proyect->title }}">
                <div class="portfolio-tile-overlay">
                    <p class="tile-tag">Proyecto</p>
                    <h3 class="tile-title">{{ $proyect->title }}</h3>
                </div>
            </div>

            <div class="af-modal modal fade" id="pm{{ $proyect->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <h2 class="modal-proj-title">{{ $proyect->title }}</h2>
                                    <hr class="modal-rule" style="border-color:var(--sand-lt)">
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
        @else
            <div class="af-container" style="padding:4rem 0">
                <p style="color:var(--slate-lt)">No hay proyectos disponibles aún.</p>
            </div>
        @endif
    </div>
</section>

<!-- ══ SKILLS ══ -->
<section id="skills">
    <div class="af-container">
        <div class="skills-layout">
            <div class="skills-intro" data-reveal>
                <p class="section-tag" style="color:rgba(255,255,255,.3)">Tecnologías</p>
                <h2 class="display-h">
                    Stack &<br><em>herramientas</em>
                </h2>
                <p class="skills-sub">Más de 2 años construyendo productos reales con estas tecnologías. Del backend al deploy.</p>
            </div>
            <div class="skills-groups" data-reveal data-delay="150">
                <div>
                    <p class="skill-group-label">Lenguajes & Frameworks</p>
                    <div class="skill-pills">
                        @foreach(['JavaScript','PHP','Laravel','ReactJS','VueJS','NodeJS','jQuery','HTML','Bootstrap','WordPress'] as $s)
                        <span class="skill-pill">{{ $s }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="skill-group-label">Herramientas & Plataformas</p>
                    <div class="skill-pills">
                        @foreach(['Git','APIs REST','SOAP','Azure DevOps','Google Cloud','Digital Ocean','Dolibarr'] as $s)
                        <span class="skill-pill">{{ $s }}</span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="skill-group-label">Idiomas</p>
                    <div class="skill-pills">
                        <span class="skill-pill">Español — Nativo</span>
                        <span class="skill-pill">Inglés — Básico</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ SOBRE MÍ ══ -->
<section id="about">
    <div class="af-container">
        <div class="about-layout">
            <div class="about-img-wrap" data-reveal>
                <img src="{{ asset('image/' . $business->logo) }}" class="about-img" alt="Alan Carabali">
                <div class="about-img-frame"></div>
            </div>
            <div data-reveal data-delay="150">
                <p class="section-tag about-tag">Sobre mí</p>
                <h2 class="display-h about-title">Construyo cosas<br><em>que importan</em></h2>
                <div class="about-text">{!! $business->description !!}</div>
                <div class="about-data">
                    <div class="about-data-item">
                        <span>Experiencia</span>
                        <strong>2+ años</strong>
                    </div>
                    <div class="about-data-item">
                        <span>Educación</span>
                        <strong>Ing. Sistemas</strong>
                    </div>
                </div>
                <a href="#contact" class="af-btn af-btn-dark">Hablemos</a>
            </div>
        </div>
    </div>
</section>

<!-- ══ TESTIMONIOS ══ -->
<section id="testimonials">
    <div class="af-container">
        <p class="section-tag" data-reveal="fade">Clientes</p>
        <h2 class="display-h" data-reveal style="font-size:clamp(2.5rem,5vw,4rem)">
            Lo que dicen<br><em>de mí</em>
        </h2>
    </div>
    <div class="af-container" style="padding-left:0;padding-right:0;max-width:100%">
        <div class="testimonials-grid">
            @if (!is_null($clients) && $clients->isNotEmpty())
                @foreach ($clients as $client)
                <div class="testi-card" data-reveal data-delay="{{ $loop->index * 80 }}">
                    <div class="testi-quote">&ldquo;</div>
                    <p class="testi-text">{{ $client->description }}</p>
                    <div class="testi-author">
                        <img class="testi-avatar" src="{{ asset($client->image) }}" alt="{{ $client->name }}">
                        <span class="testi-name">{{ $client->name }}</span>
                    </div>
                </div>
                @endforeach
            @else
                <div class="af-container" style="padding:4rem 0">
                    <p style="color:var(--slate-lt)">No hay testimonios aún.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="af-container" style="margin-top:3rem;text-align:right" data-reveal="fade">
        <a href="{{ route('clientes') }}" class="af-btn af-btn-dark">Ver todos</a>
    </div>
</section>

<!-- ══ CONTACTO ══ -->
<section id="contact">
    <div class="af-container">
        <div class="contact-layout">
            <div>
                <p class="section-tag" style="color:rgba(255,255,255,.3)" data-reveal="fade">Contacto</p>
                <h2 class="display-h contact-left" data-reveal>
                    <span class="display-h" style="color:var(--ivory);font-size:clamp(2.8rem,5vw,4.5rem)">
                        Trabajemos<br><em style="color:var(--slate-lt)">juntos</em>
                    </span>
                </h2>
                <div class="contact-info" data-reveal data-delay="100">
                    <a href="tel:{{ $business->phone }}">
                        <i class="fas fa-phone"></i> {{ $business->phone }}
                    </a>
                    <a href="mailto:{{ $business->mail }}">
                        <i class="fas fa-envelope"></i> {{ $business->mail }}
                    </a>
                    <a href="https://www.google.com/maps?q={{ urlencode($business->address) }}" target="_blank">
                        <i class="fas fa-map-marker-alt"></i> {{ $business->address }}
                    </a>
                </div>
            </div>

            <div data-reveal data-delay="150">
                <form id="contactForm" class="af-form">
                    @csrf
                    <div class="af-field">
                        <label for="name">Nombre completo</label>
                        <input id="name" name="name" type="text" placeholder="Tu nombre" required>
                    </div>
                    <div class="af-field">
                        <label for="email">Correo electrónico</label>
                        <input id="email" name="email" type="email" placeholder="tu@email.com" required>
                    </div>
                    <div class="af-field">
                        <label for="phone">Teléfono</label>
                        <input id="phone" name="phone" type="tel" placeholder="+57 000 000 0000" required>
                    </div>
                    <div class="af-field">
                        <label for="message">Mensaje</label>
                        <textarea id="message" name="message" placeholder="Cuéntame sobre tu proyecto…" required></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" id="submitButton" class="af-btn af-btn-inv">
                            Enviar mensaje <i class="fas fa-arrow-right"></i>
                        </button>
                        <span class="form-feedback" id="formFeedback"></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@include('__whatsapp')

@endsection

@section('scripts')
{!! Html::script('afdeveloper/js/scripts.js') !!}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    $('#contactForm').on('submit', function (e) {
        e.preventDefault();
        const btn = $('#submitButton');
        const fb  = $('#formFeedback');
        btn.attr('disabled', true).text('Enviando…');
        $.ajax({
            url: "{{ route('bandeja.store') }}",
            type: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (r) {
                if (r.success) {
                    fb.removeClass('err').addClass('ok').text('¡Mensaje enviado!');
                    $('#contactForm')[0].reset();
                } else {
                    fb.removeClass('ok').addClass('err').text('Error al enviar.');
                }
            },
            error: function () { fb.removeClass('ok').addClass('err').text('Error al enviar.'); },
            complete: function () { btn.attr('disabled', false).html('Enviar mensaje <i class="fas fa-arrow-right"></i>'); }
        });
    });
});
</script>
@endsection