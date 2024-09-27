@extends('layouts.admin')
@section('title', 'Gestión de mensajes')
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
                Administrar mensajes 
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Panel administrador</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mensajes</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body ">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title">Mensajes </h4>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table id="order-listing" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Telefono</th>
                                        <th>Mensaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bandejas as $bandeja)
                                        <tr>
                                            <td> {{ $bandeja->name }} </td>

                                            <td> {{ $bandeja->email }} </td>

                                            <td> {{ $bandeja->phone }} </td>

                                            <td>
                                                <p class="text-justify">{{ $bandeja->message }} </p>
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
    
    {!! Html::script('melody/responsive/js/responsive.bootstrap4.min.js') !!}
    {!! Html::script('melody/responsive/js/responsive.min.js') !!}
@endsection
