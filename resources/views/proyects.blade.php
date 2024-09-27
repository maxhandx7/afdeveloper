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

         #proyects{
            margin-top: 7%;
        }
    </style>


        <div class="container">
            <div class="row" id="proyects">
                @foreach ($proyects as $proyect)
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="box-part text-center rounded">
                            <img src="{{ asset($proyect->image) }}" class="icon-img rounded-circle"
                                alt="">
                            <div class="title">
                                <h3><a href="{{ $proyect->link }}" target="_blank" rel="noopener noreferrer" style="text-decoration: none"> {{ $proyect->title }} </a></h3>
                            </div>
                            <div class="text">
                                <span>{{ $proyect->description }}</span>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal-{{ $proyect->id }}">Ver detalles<i class="fa fa-play-circle ml-1"></i></button>
                        </div>
                    </div>

                    <div class="modal fade" id="exampleModal-{{ $proyect->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">{{ $proyect->title }}</h5>
                              
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              {!! $proyect->long_description !!}
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light" data-dismiss="modal">Cerrar</button>
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



@section('scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-icon-only');
        });
    </script>
@endsection
