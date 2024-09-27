<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">



<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Logisticasoft</title>

 


    {!! Html::style('melody/vendors/iconfonts/font-awesome/css/all.min.css') !!}

    {!! Html::style('melody/vendors/css/vendor.bundle.base.css') !!}

    {!! Html::style('melody/vendors/css/vendor.bundle.addons.css') !!}

    {!! Html::style('melody/css/style.css') !!}

    @yield('styles')

    <link rel="shortcut icon" href="{{ asset('images/lsoft_mini.png') }}" />

</head>



<body >


        @yield('content')

    

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

    {!! Html::script('js/script.js') !!}

    @yield('scripts')

</body>



</html>

