@extends('layouts.admin')
@section('title', 'Editar Gasto')
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
                Editar Gasto
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="{{route('home') }}">Panel administrador</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">Gastos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar gasto</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title">Editar gasto</h4>

                        </div>
                        {!! Form::model($expense, ['route' => ['expenses.update', $expense], 'method' => 'PUT', 'files' => true]) !!}

                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Descripción" value="{{ old('description', $expense->description) }}" required>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="amount">Monto</label>
                                <input class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $expense->amount) }}" name="amount" id="amount" data-inputmask="'alias': 'currency'" />
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="date">Fecha</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $expense->date) }}" id="date" name="date" />
                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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