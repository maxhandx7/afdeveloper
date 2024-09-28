<!DOCTYPE html>

<html lang="es">







<head>

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>{{ $business->name }}</title>



  {!! Html::style('melody/vendors/iconfonts/font-awesome/css/all.min.css') !!}

  {!! Html::style('melody/vendors/css/vendor.bundle.base.css') !!}

  {!! Html::style('melody/vendors/css/vendor.bundle.addons.css') !!}



  {!! Html::style('melody/css/style.css') !!}

  <!-- endinject -->

  <link rel="shortcut icon" href="{{ asset('image/LOGO.png') }}" />

</head>



<body>

  <div class="container-scroller">

    <div class="container-fluid page-body-wrapper full-page-wrapper">

      <div class="content-wrapper d-flex align-items-center auth">

        <div class="row w-100">

          <div class="col-lg-4 mx-auto">

            <div class="auth-form-light text-left p-5">

              <div class="brand-logo">

                <div class="d-flex justify-content-center">

                    <img src="{{asset('melody/images/logo.svg')}}" alt="logo">
                  </div>

              </div>

              @yield('content')

            </div>

          </div>

        </div>

      </div>

      <!-- content-wrapper ends -->

    </div>

    <!-- page-body-wrapper ends -->

  </div>

  <!-- container-scroller -->

  <!-- plugins:js -->

  {!! Html::script('melody/vendors/js/vendor.bundle.base.js') !!}

  {!! Html::script('melody/vendors/js/vendor.bundle.addons.js') !!}

  <!-- endinject -->

  <!-- inject:js -->

  {!! Html::script('melody/js/off-canvas.js') !!}

  {!! Html::script('melody/js/hoverable-collapse.js') !!}

  {!! Html::script('melody/js/misc.js') !!}

  {!! Html::script('melody/js/settings.js') !!}

  {!! Html::script('melody/js/todolist.js') !!}

  <!-- endinject -->

</body>





<!-- Mirrored from www.urbanui.com/melody/template/pages/samples/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 15 Sep 2018 06:08:53 GMT -->

</html>

