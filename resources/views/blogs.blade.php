@extends('layouts.web')
@section('title', $business->name)
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
            background: #2d3748 ;
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
            background: #F0FFFF;
            height: 330px;
            padding: 60px 10px;
            margin: 30px 0px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            margin-bottom: 25px;
        }

        .box-part:hover {
            background: #2f6dc1;
        }

        .box-part:hover .fa,
        .box-part:hover .title,
        .box-part:hover .text,
        .box-part:hover a {
            color: #FFF;
            -webkit-transition: all 1s ease-out;
            -moz-transition: all 1s ease-out;
            -o-transition: all 1s ease-out;
            transition: all 1s ease-out;
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
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 15px;
        }

         #blogs{
            margin-top: 7%;
        }
    </style>


        <div class="container">
            <div class="row" id="blogs">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="box-part text-center rounded">
                            <img src="{{ asset($blog->image) }}" class="icon-img rounded-circle"
                                alt="">
                            <div class="title">
                                <h3><a href="{{ $blog->link }}" target="_blank" rel="noopener noreferrer" style="text-decoration: none"> {{ $blog->title }} </a></h3>
                            </div>
                            <div class="text">
                                <span>{{ $blog->description }}</span>
                            </div>
                            <div class="portfolio-item mx-auto" data-bs-toggle="modal"
                            data-bs-target="#exampleModal-{{ $blog->id }}"> <button type="button" class="portfolio-item mx-auto btn btn-primary btn-sm" data-toggle="modal" data-bs-target="#exampleModal-{{ $blog->id }}">Ver detalles<i class="fa fa-play-circle ml-1"></i>
                            </button></div>
                        </div>
                    </div>

                    <div class="portfolio-modal modal fade" id="exampleModal-{{ $blog->id }}" tabindex="-1"
                        aria-labelledby="exampleModal-{{ $blog->id }}" aria-hidden="true">
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
                                                    {{ $blog->title }}</h2>
                                                <!-- Icon Divider-->
                                                <div class="divider-custom">
                                                    <div class="divider-custom-line"></div>
                                                    <div class="divider-custom-icon"><i class="fas fa-code"></i></div>
                                                    <div class="divider-custom-line"></div>
                                                </div>
                                                <!-- Portfolio Modal - Text-->
                                                <h5 class="mb-4">{{ $blog->description }}</h5>

                                                <p class="">{!! $blog->long_description !!}</p>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    {{-- <footer class="fixed-bottom ">
        <div class="container text-center py-2">
            <a href="{{ url('/') }}" class="btn btn-outline-info">
                &#8592; Volver Atrás
            </a>
        </div>
    </footer> --}}

@endsection


{!! Html::script('afdeveloper/js/scripts.js') !!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-icon-only');
        });
    </script>

