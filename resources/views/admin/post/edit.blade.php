@extends('layouts.admin')
@section('title', 'Editar Publicación')
@section('styles')
@endsection

@section('options')
@endsection
@section('preference')
@endsection
@section('content')
{!! Html::style('melody/vendors/summernote/dist/summernote-bs4.css') !!}
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Editar Publicación
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="{{route('home') }}">Panel administrador</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Publicaciones</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar publicación</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title">Editar publicación</h4>

                        </div>
                        {!! Form::model($post, ['route' => ['posts.update', $post], 'method' => 'PUT', 'files' => true]) !!}


                        <div class="form-group">
                            <label for="title">Titulo</label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="Titulo del publicación" value="{{ old('title', $post->title) }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>




                        <div class="form-group">
                            <label for="long_description">Descripción larga</label>
                            <textarea class="form-control" name="long_description" id="summernoteExample" rows="10">{{ old('long_description', $post->long_description) }}</textarea>
                        </div>



                        <div class="row">
                            <div class="col-12 grid-margin">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Imágen</h4>
                                        <div class="form-group">
                                            @if (!$post->image)
                                            <p>No hay imagen actualmente.</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Actualizar Imagen</label>
                                            <input type="file" name="image" class="dropify" data-default-file="/{{ $post->image }}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary mr-2">Actualizar</button>
                        <a href="{{ URL::previous() }}" class="btn btn-light mr-2">
                            cancelar
                        </a>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row grid-margin mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Imagen</h4>
                        <div id="lightgallery" class="row lightGallery justify-content-center">
                            <a href="/{{ $post->image }}" class="image-tile">
                                <img src="/{{ $post->image }}" alt="{{ $post->title }}" class="img-fluid">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        
    </div>

@endsection
@section('scripts')

    {!! Html::script('melody/js/dropify.js') !!}
    {!! Html::script('melody/vendors/summernote/dist/summernote-bs4.min.js') !!}
    {!! Html::script('melody/js/editorDemo.js') !!}
    {!! Html::script('melody/js/select2.js') !!}
@endsection