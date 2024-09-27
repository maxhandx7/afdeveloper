@extends('layouts.admin')
@section('title', 'Gestión de Equipos')
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
               Gestión de equipos
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="{{route('home') }}">Panel administrador</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Equipos</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title">Equipos</h4>
                            <div class="btn-group">
                                <a href="{{ route('teams.create') }}" class="btn btn-primary" type="button">
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
                                        <th>Nombre</th>     
                                        <th>Email</th>
                                        <th>Rol</th>     
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teams as $team)
                                        <tr>

                                            <td scope="row"> {{ $team->name }}
                                            </td>


                                            <td>{{ $team->email }}</td>
                                            <td>{{ $team->rol }}</td>


                                            <td style="width: 200px;">
                                                {!! Form::open(['route' => ['teams.destroy', $team], 'method' => 'DELETE', 'id' => 'delete-form']) !!}

                                                <a class="btn btn-outline-info"
                                                    href="{{ route('teams.edit', $team) }}" title="Editar">
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
