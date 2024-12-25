@extends('layouts.admin')
@section('title', 'Gestión de Gastos')
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
               Gestión de gastos
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="{{route('home') }}">Panel administrador</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Gastos</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title">Gastos</h4>
                            <div class="btn-group">
                                <a href="{{ route('expenses.create') }}" class="btn btn-primary" type="button">
                                    <i class="fa fa-plus"></i>
                                    Agregar</a>
                            </div>
                        </div>
                        <br>
                        @include('errors.message')
                        <div class="table-responsive">
                            <table id="order-listing" class="table table-striped ">
                                <thead>
                                    <tr>
                                        <th>Descripción</th>     
                                        <th>Monto</th>
                                        <th>Fecha</th>     
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $expense)
                                        <tr>

                                            <td scope="row"> {{ $expense->description }}
                                            </td>


                                            <td>{{ number_format($expense->amount) }}</td>
                                            <td>{{ $expense->date }}</td>


                                            <td style="width: 200px;">
                                                {!! Form::open(['route' => ['expenses.destroy', $expense], 'method' => 'DELETE', 'id' => 'delete-form']) !!}

                                                <a class="btn btn-outline-info"
                                                    href="{{ route('expenses.edit', $expense) }}" title="Editar">
                                                    <i class="far fa-edit"></i>
                                                </a>

                                                <button class="btn btn-outline-danger delete-confirm" type="submit" title="Eliminar" onclick="confirmDelete(event)">
                                                    <i class="far fa-trash-alt"></i>
                                                </button> 
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
{!! Html::script('melody/js/data-table.js') !!}
{!! Html::script('melody/js/toastDemo.js') !!}
{!! Html::script('melody/responsive/js/responsive.bootstrap4.min.js') !!}
{!! Html::script('melody/responsive/js/responsive.min.js') !!}
@endsection
