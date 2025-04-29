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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    {!! Html::script('afdeveloper/js/all.js') !!}
    <!-- Google fonts-->
    {!! Html::style('afdeveloper/font/css.css') !!}
    {!! Html::style('afdeveloper/font/css2.css') !!}
    <!-- Core theme CSS (includes Bootstrap)-->
    {!! Html::style('afdeveloper/css/styles.css') !!}
    @yield('styles')
</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-PPN2T320CL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-PPN2T320CL');
</script>

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
                        <a href="https://www.google.com/maps?q={{ urlencode($business->address) }}"  target="_blank"
                            class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" 
                            style="text-decoration: none" 
                            target="_blank">
                            <i class="fas fa-map-marker-alt"></i> {{ $business->address }}
                         </a>                         
                    </p>
                </div>
                <!-- Footer Social Icons-->
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h4 class="text-uppercase mb-4">Sígueme en</h4>

                    <a class="btn btn-outline-light btn-social mx-1" target="_blank"
                        href="{{ $business->configurations['facebook'] ?? '#' }}"><i
                            class="fab fa-fw fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social mx-1"
                        href="{{ $business->configurations['twitter'] ?? '#' }}" target="_blank"
                        ><i
                            class="fab fa-fw fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social mx-1" target="_blank"
                        href="{{ $business->configurations['linkedin'] ?? '#' }}"><i
                            class="fab fa-fw fa-linkedin-in"></i></a>
                    <a class="btn btn-outline-light btn-social mx-1" target="_blank"
                        href="{{ $business->configurations['instagram'] ?? '#' }}"><i
                            class="fab fa-fw fa-instagram"></i></a>
                </div>
                <!-- Footer About Text-->
                <div class="col-lg-4">
                    <h4 class="text-uppercase mb-4">Contactos</h4>
                    <p class="lead mb-0">
                        <a href="tel: {{ $business->phone }}" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" style="text-decoration: none" target="_blank"><i
                            class="fas fa-phone"></i>  {{ $business->phone }}</a>
                         <br>
                        <a href="mailto:{{ $business->mail }}" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" style="text-decoration: none" target="_blank"><i
                            class="fas fa-envelope"></i> {{ $business->mail }}</a>

                    </p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Copyright Section-->
    <div class="copyright py-4 text-center text-white">
        <div class="container"> <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright ©
                {{date("Y")}}.
                Todos los derechos reservados.&nbsp;</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><b><a
                        style="text-decoration: none; " class="text-secondary" href="{{route('login')}}"
                        target="_blank">&nbsp;AF</a> </b> <i class="far fa-heart text-danger"></i>&nbsp;</span>
        </div>
    </div>

    {!! Html::script('afdeveloper/js/bootstrap.bundle.min.js') !!}

    {!! Html::script('afdeveloper/js/scripts.js') !!}

    {!! Html::script('afdeveloper/js/jquery.min.js') !!}

    @yield('scripts')
</body>

</html>
