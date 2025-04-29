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
            background: #2d3748;
            font-family: 'Josefin Sans', sans-serif;
        }

        h3 {
            font-family: 'Josefin Sans', sans-serif;
        }
    </style>



    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-2 sm:pt-0">
        <div class="container ">
            <div class="row">
                @foreach ($teams as $index => $team)
                <div class="col-md-4 grid-margin stretch-card ">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-row">
                                <img src="{{ asset($team->image) }}" class="img-lg rounded" alt="profile image" />
                                
                                <div class="ml-3">
                                    <h6>{{ $team->name }}</h6>
                                    <p class="text-muted">{{ $team->email }}</p>
                                    <p class="mt-2 text-primary font-weight-bold">{{ $team->rol }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <footer class="fixed-bottom ">
        <div class="container text-center py-2">
            <a href="{{ url('/') }}" class="btn btn-outline-info">
                &#8592; Volver Atrás
            </a>
        </div>
    </footer>
@endsection



@section('scripts')
    <script>
        $(document).ready(function() {
            $('body').addClass('sidebar-icon-only');
        });
    </script>
@endsection
