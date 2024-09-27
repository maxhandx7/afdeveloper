@extends('layouts.admin')
@section('title', 'Editar Cliente')
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
                Editar Cliente
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="{{route('home') }}">Panel administrador</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clientes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar cliente</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title">Editar cliente</h4>

                        </div>
                        {!! Form::model($client, ['route' => ['clients.update', $client], 'method' => 'PUT', 'files' => true]) !!}


                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Nombre del cliente" value="{{ old('name', $client->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>




                        <div class="form-group">
                            <label for="description">Descripcion</label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Descripcion" name="description" rows="3">{{ old('description', $client->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>



                        <div class="row">
                            <div class="col-12 grid-margin">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Imágen</h4>
                                        <div class="form-group">
                                            @if (!$client->image)
                                            <p>No hay imagen actualmente.</p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Actualizar Imagen</label>
                                            <input type="file" name="image" class="dropify" data-default-file="/{{ $client->image }}" />
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
        
    </div>

@endsection
@section('scripts')

    {!! Html::script('melody/js/dropify.js') !!}
    {!! Html::script('melody/vendors/summernote/dist/summernote-bs4.min.js') !!}
    {!! Html::script('melody/js/editorDemo.js') !!}
    {!! Html::script('melody/js/select2.js') !!}
@endsection