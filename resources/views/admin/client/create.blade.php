@extends('layouts.admin')
@section('title','Nuevo Cliente')
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
            Nuevo Cliente
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item"><a href="{{route('home') }}">Panel administrador</a></li>
                <li class="breadcrumb-item"><a href="{{route('clients.index') }}">Clientes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nuevo cliente</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Registrar nuevo cliente</h4>
                       
                    </div>
                    {!! Form::open(['route'=>'clients.store', 'method'=>'POST', 'files' => true]) !!}
                    @include('admin.client._form')
                    <button type="submit" class="btn btn-primary mr-2">Agregar</button>
                    <a href="{{route('clients.index') }}" class="btn btn-light mr-2">
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