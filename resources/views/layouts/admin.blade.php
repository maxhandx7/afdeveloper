<!DOCTYPE html>

<html lang="es">



<head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>@yield('title')</title>

  {!! Html::style('melody/vendors/iconfonts/font-awesome/css/all.min.css') !!}

  {!! Html::style('melody/vendors/css/vendor.bundle.base.css') !!}

  {!! Html::style('melody/vendors/css/vendor.bundle.addons.css') !!}

  {!! Html::style('melody/css/style.css') !!}

  @yield('styles')

  <link rel="shortcut icon" sizes="96x96" href="{{ asset('image/' . $business->logo) }}" />

</head>



<body class="horizontal-menu">

  <div class="container-scroller">

    <nav class="navbar horizontal-layout-navbar fixed-top navbar-expand-lg">

      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">

        <a class="navbar-brand brand-logo" href="/home"><img src="{{asset('melody/images/logo.svg')}}" alt="logo"/></a>

        <a class="navbar-brand brand-logo-mini" href="/home"><img src="{{asset('melody/images/logo-mini.svg')}}" alt="logo"/></a>                    

      </div>

      <div class="navbar-menu-wrapper d-flex flex-grow">

        <ul class="navbar-nav navbar-nav-left collapse navbar-collapse" id="horizontal-top-example">

          <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle active" href="#" id="projects-dropdown" data-toggle="dropdown" aria-expanded="false">

              Administrador

            </a>

            <div class="dropdown-menu navbar-dropdown" aria-labelledby="projects-dropdown">

              <a class="dropdown-item" href="{{ route('links.index') }}">

                <i class="mdi mdi-laptop-mac mr-2 text-primary"></i>

                Proyectos

              </a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="{{ route('clients.index') }}">

                <i class="mdi mdi-scale-balance mr-2 text-primary"></i>

                Clientes

              </a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="{{ route('teams.index') }}">

                <i class="mdi mdi-scale-balance mr-2 text-primary"></i>

                Equipo

              </a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="{{ route('proyects') }}">

                <i class="fa fa-eye mr-2 text-primary"></i>

                Ver proyectos

              </a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="{{ route('clientes') }}">

                <i class="fa fa-eye mr-2 text-primary"></i>

                Ver clientes

              </a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="{{ route('equipo') }}">

                <i class="fa fa-eye mr-2 text-primary"></i>

                Ver equipos

              </a>

            </div>

          </li>

          <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle" href="#" id="employees-dropdown" data-toggle="dropdown" aria-expanded="false">

              Gestionar Cuenta

            </a>

            <div class="dropdown-menu navbar-dropdown" aria-labelledby="employees-dropdown">

              <a class="dropdown-item" href="{{ route('configs.edit', Auth::user()->id) }}">

                <i class="mdi mdi-monitor-multiple mr-2 text-primary"></i>

                Editar perfil

              </a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="{{ route('password.change') }}">

                <i class="mdi mdi-scale-balance mr-2 text-primary"></i>

                Editar Contraseña

              </a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="{{ url('/bandeja') }}">

                <i class="mdi mdi-scale-balance mr-2 text-primary"></i>

                Mensajes

              </a>


              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="{{ route('business.index') }}" data-toggle="tooltip"
                                    data-placement="top" title="" data-original-title="Logout">
                                    <i class="fas fa-briefcase text-primary"></i>
                                    {{ $business->name }}
                                </a>

            </div>

          </li>

        </ul>

        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item nav-profile">

            <a class="nav-link">

              <div class="nav-profile-text">

                {{Auth::user()->name}}

              </div>

              <div class="nav-profile-img">

                <img src="{{ asset('image/' . Auth::user()->image) }}" alt="image" class="img-xs rounded-circle ml-3">

                <span class="availability-status online"></span>             

              </div>

            </a>

          </li>

          <li class="nav-item nav-search">

            <div class="nav-link">

              <div class="search-field d-none d-md-block">

                <form class="d-flex align-items-stretch h-100" action="#">

                  <div class="input-group">

                    <div class="input-group-prepend">

                        <span class="input-group-text">                                       

                        </span>

                    </div>

                  </div>

                </form>

              </div>

            </div>

          </li>

          <li class="nav-item">

            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();

                                 document.getElementById('logout-form').submit();">

              <i class="fas fa-power-off  font-weight-bold icon-sm"></i>

            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

              @csrf

            </form>

          </li>

        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center ml-auto" type="button" data-toggle="collapse" data-target="#horizontal-top-example">

          <span class="fa fa-bars"></span>

        </button>

      </div>

    </nav>

    @yield('preference')

    <div class="container-fluid page-body-wrapper">

      <div class="main-panel">

        @yield('content')

        <footer class="footer">

          <div class="col-lg-12 login-half-bg d-flex flex-row justify-content-center">

            {{-- <div class="d-sm-flex justify-content-center justify-content-sm-between"> --}}

            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024.

              Todos los derechos reservados.&nbsp;</span>

              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><i class="fa fa-code text-dark"></i>&nbsp;<b><a style="text-decoration: none; color:rgb(17, 15, 129);" href="https://afdeveloper.com/" target="_blank">&nbsp;AF</a> </b> </span>

          </div>

        </footer>

      </div>

       

    </div>

  </div>

  {!! Html::script('melody/js/main.js') !!}

  {!! Html::script('melody/vendors/js/vendor.bundle.base.js') !!}

  {!! Html::script('melody/vendors/js/vendor.bundle.addons.js') !!}

  {!! Html::script('melody/js/off-canvas.js') !!}

  {!! Html::script('melody/js/hoverable-collapse.js') !!}

  {!! Html::script('melody/js/misc.js') !!}

  {!! Html::script('melody/js/select2.js') !!}

  {!! Html::script('melody/js/toastDemo.js') !!}

  {!! Html::script('melody/js/settings.js') !!}

  {!! Html::script('melody/js/numeral.min.js') !!}

  {!! Html::script('melody/js/dashboard.js') !!}

  @yield('scripts')

</body>

</html>

