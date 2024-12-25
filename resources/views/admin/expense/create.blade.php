@extends('layouts.admin')
@section('title','Nuevo Gasto')
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
            Nuevos Gastos
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item"><a href="{{route('home') }}">Panel administrador</a></li>
                <li class="breadcrumb-item"><a href="{{route('expenses.index') }}">Gastos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nuevos gastos</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Registrar nuevo gasto</h4>
                       
                    </div>
                    @include('errors.message')
                    {!! Form::open(['route'=>'expenses.store', 'method'=>'POST', 'files' => true]) !!}
                    @include('admin.expense._form')
                    <button type="submit" class="btn btn-primary mr-2">Agregar</button>
                    <a href="{{route('expenses.index') }}" class="btn btn-light mr-2">
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

    <script>
        $(document).ready(function() {
            // Obtener la fecha actual
            var today = new Date();
            // Formatear la fecha a 'YYYY-MM-DD'
            var formattedDate = today.toISOString().split('T')[0];
            // Asignar la fecha actual al campo de fecha usando jQuery
            $('#date').val(formattedDate);
        });
    </script>
@endsection