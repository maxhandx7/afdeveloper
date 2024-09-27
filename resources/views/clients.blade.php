@extends('layouts.web')

@section('title', 'LogisticaSoft')

@section('styles')

@endsection

@section('options')

@endsection

@section('preference')

@endsection

@section('content')



    <style>

        @import url('https://fonts.googleapis.com/css?family=Josefin+Sans:100,300,400,600,700');



        body {

            background: #2d3748;

            font-family: 'Josefin Sans', sans-serif;

        }



        h3 {

            font-family: 'Josefin Sans', sans-serif;

        }



        .box {

            padding: 60px 0px;

            margin: -4em 0;

        }



        .box-part {

            background: #2f6dc1;

            color: #fff;

            height: 400px;

            padding: 60px 10px;

            margin: 50px 0px;

            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);

            margin-bottom: 25px;

        }





        .text {

            margin: 20px 0px;

        }



        .fa {

            color: #2f6dc1;

        }



        .title {

            margin: 10px;

        }



        .icon-img {

            width: 115px;

            height: 150px;

            object-fit: cover;

            margin-bottom: 15px;

        }

        .carousel-indicators {

            bottom: 20px;

        }

        

    </style>



    



    <div id="clientCarousel" class="carousel slide mt-5" data-ride="carousel" data-interval="7000">

        <ol class="carousel-indicators">

            @foreach ($clients as $index => $client)

                <li data-target="#clientCarousel" data-slide-to="{{ $index }}"

                    class="{{ $index == 0 ? 'active' : '' }}"></li>

            @endforeach

        </ol>



        <div class="carousel-inner">

            @foreach ($clients as $index => $client)

                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">

                    <div class="container">

                        <div class="row justify-content-center">

                            <div class="col-lg-12 col-md-12 col-sm-4 col-xs-12">

                                <div class="box-part text-center rounded">

                                    <img src="{{ asset($client->image) }}" class="icon-img rounded-circle" alt="">

                                    <div class="title">

                                        <h3>{{ $client->name }}</h3>

                                    </div>

                                    <div class="text">

                                        <span>{{ $client->description }}</span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>



{{--     <footer class="fixed-bottom ">

        <div class="container text-center py-2">

            <a href="{{ url('/') }}" class="btn btn-outline-info">

                &#8592; Volver Atrás

            </a>

        </div>

    </footer> --}}



@endsection







@section('scripts')

    <script>

        $(document).ready(function() {

            $('body').addClass('sidebar-icon-only');

        });

    </script>

@endsection

