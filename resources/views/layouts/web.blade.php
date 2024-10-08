<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="{{ $business->shortDescription }}" />
    <link rel="icon" href="{{ asset('image/LOGO.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('image/LOGO.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('image/LOGO.png') }}">
    <meta name="author" content="Alan Carabali" />
    <title>{{ $business->name }}</title>
    <link rel="shortcut icon" href="{{ asset('image/LOGO.png') }}" />
    <!-- Font Awesome icons (free version)-->
    {!! Html::script('afdeveloper/js/all.js') !!}
    <!-- Google fonts-->
    {!! Html::style('afdeveloper/font/css.css') !!}
    {!! Html::style('afdeveloper/font/css2.css') !!}
    <!-- Core theme CSS (includes Bootstrap)-->
    {!! Html::style('afdeveloper/css/styles.css') !!}
    @yield('styles')
</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
        <div class="container">

            <a href="{{ url('/') }}"><img class="navbar-brand" src="{{ asset('image/AFDEVELOPER_LOGO.png') }}"
                    style="width: 150px;" alt="..." /></a>
            <button class="navbar-toggler text-uppercase font-weight-bold bg-secondary text-white rounded"
                type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded"
                            href="{{ url('/#portfolio') }}">Portafolio</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded"
                            href="{{ url('/#about') }}">Sobre mi</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded"
                            href="{{ url('/#testimonials') }}">Testimonios</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded"
                            href="{{ url('/#contact') }}">Contacto</a></li>

                    @auth
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded"
                                href="{{ route('home') }}">Hola,
                                {{ Auth::user()->name }}</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer-->
    <footer class="footer text-center">
        <div class="container">
            <div class="row">
                <!-- Footer Location-->
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h4 class="text-uppercase mb-4">Ubicación</h4>
                    <p class="lead mb-0">
                        {{ $business->address }}
                    </p>
                </div>
                <!-- Footer Social Icons-->
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h4 class="text-uppercase mb-4">Sígueme en</h4>

                    <a class="btn btn-outline-light btn-social mx-1"
                        href="{{ $business->configurations['facebook'] ?? '#' }}"><i
                            class="fab fa-fw fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social mx-1"
                        href="{{ $business->configurations['twitter'] ?? '#' }}"><i
                            class="fab fa-fw fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social mx-1"
                        href="{{ url('https://www.linkedin.com/in/alancarabali/') }}"><i
                            class="fab fa-fw fa-linkedin-in"></i></a>
                    <a class="btn btn-outline-light btn-social mx-1"
                        href="{{ $business->configurations['instagram'] ?? '#' }}"><i
                            class="fab fa-fw fa-instagram"></i></a>
                </div>
                <!-- Footer About Text-->
                <div class="col-lg-4">
                    <h4 class="text-uppercase mb-4">Contactos</h4>
                    <p class="lead mb-0">
                        {{ $business->phone }} <br>
                        <a href="mailto:{{ $business->mail }}">{{ $business->mail }}</a>

                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Copyright Section-->
    <div class="copyright py-4 text-center text-white">
        <div class="container"> <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
                2024.
                Todos los derechos reservados.&nbsp;</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><b><a
                        style="text-decoration: none; " class="text-secondary" href="https://afdeveloper.com/"
                        target="_blank">&nbsp;AF</a> </b> <i class="far fa-heart text-danger"></i>&nbsp;</span>
        </div>
    </div>
    <!-- Portfolio Modals-->
    <!-- Portfolio Modal 1-->
    @if (!is_null($proyectos) && $proyectos->isNotEmpty())
        @foreach ($proyectos as $proyect)
            <div class="portfolio-modal modal fade" id="portfolioModal{{ $proyect->id }}" tabindex="-1"
                aria-labelledby="portfolioModal1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header border-0"><button class="btn-close" type="button"
                                data-bs-dismiss="modal" aria-label="Close"></button></div>
                        <div class="modal-body text-center pb-5">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8">
                                        <!-- Portfolio Modal - Title-->
                                        <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0">
                                            {{ $proyect->title }}</h2>
                                        <!-- Icon Divider-->
                                        <div class="divider-custom">
                                            <div class="divider-custom-line"></div>
                                            <div class="divider-custom-icon"><i class="fas fa-code"></i></div>
                                            <div class="divider-custom-line"></div>
                                        </div>
                                        <!-- Portfolio Modal - Image-->
                                        <img class="img-fluid rounded mb-5" src="{{ $proyect->image }}"
                                            alt="..." />
                                        <!-- Portfolio Modal - Text-->
                                        <h5 class="mb-4">{{ $proyect->description }}</h5>

                                        <p class="">{!! $proyect->long_description !!}</p>
                                        <a class="btn btn-primary" href="{{ $proyect->link }}">
                                            <i class="fas fa-arrow-right fa-fw"></i>
                                            Ver más
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif








    {!! Html::script('afdeveloper/js/bootstrap.bundle.min.js') !!}

    {!! Html::script('afdeveloper/js/scripts.js') !!}

    {!! Html::script('afdeveloper/js/jquery.min.js') !!}


</body>

</html>
