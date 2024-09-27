@extends('layouts.admin')

@section('title', 'Editar usuario')

@section('styles')

@endsection



@section('options')

@endsection

@section('preference')

@endsection

@section('content')

    <div class="content-wrapper">

        <div class="page-header">

            <h3 class="page-title">

                Editar usuario

            </h3>

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb breadcrumb-custom">

                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Panel administrador</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Editar usuario</li>

                </ol>

            </nav>

        </div>

        <div class="row">

            <div class="col-12 col-sm-6 col-md-6 col-lg-4 mt-2 mb-4 d-flex justify-content-center">

                <div class="border-bottom text-center">

                    <img id="profileImage" src="{{ asset('image/' . Auth::user()->image) }}"

                        alt="profile" class="img-lg rounded-circle mb-3" />

                    <div class="d-flex justify-content-center">

                        <input type="file" id="profile_image" name="profile_image" class="d-none" />

                        <button type="button" class="btn btn-success" id="changeImageButton">Cambiar

                            imagen</button>

                    </div>

                </div>

            </div>

            <div class="col-lg-8 grid-margin stretch-card">

                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <h4 class="card-title">Editar usuario</h4>

                        </div>

                        @include('errors.message') 

                        {!! Form::model($user, ['route' => ['configs.update', $user], 'method' => 'PUT']) !!}

                        <div class="form-group">

                            <label for="name">Nombre</label>

                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"

                                name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus

                                >

                        </div>



   



                        <div class="form-group">

                            <label for="email">Email</label>

                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"

                                name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

                        </div>

                        

        

                        

                        <div class="form-group">

                            <a href="{{ route('password.change') }}" class="btn btn-warning btn-block">

                                Cambiar contraseña

                            </a>

                        </div>



                        

                        <button type="submit" class="btn btn-primary mr-2">Actualizar</button>

                        <a href="{{ route('home') }}" class="btn btn-light mr-2">

                            cancelar

                        </a>

                        



                        {!! Form::close() !!}

                    </div>

                </div>

            </div>

        </div>

    </div>



@endsection

@section('scripts')

    {!! Html::script('melody/js/select2.js') !!}



    <script>

        $('#changeImageButton').click(function() {

            $('#profile_image').click();

        });

    

        $('#profile_image').change(function() {

            var formData = new FormData();

            formData.append('profile_image', $('#profile_image')[0].files[0]);

            formData.append('_token', '{{ csrf_token() }}');

    

            $.ajax({

                url: '{{ route('update_profile_image') }}',

                type: 'POST',

                data: formData,

                contentType: false,

                processData: false,

                success: function(response) {

                    if (response.success) {

                        $('#profileImage').attr('src', response.image_url);

                        swal("carga exitosa!",

                                    "Imagen de perfil actualizada con éxito.",

                                    "success");

                    } else {

                        swal("carga fallida!",

                                    "Error al actualizar la imagen de perfil",

                                    "error");

                    }

                },

                error: function(response) {

                    swal("carga fallida!",

                                    "Error al actualizar la imagen de perfil",

                                    "error");

                }

            });

        });

    </script>

@endsection

