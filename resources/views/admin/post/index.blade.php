@extends('layouts.admin')
@section('title', 'Gestión de Publicaciones')
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
               Gestión de publicaciones
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom">
                    <li class="breadcrumb-item"><a href="{{route('home') }}">Panel administrador</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Publicaciones</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title">Publicaciones</h4>
                            <div class="btn-group">
                                <a href="{{ route('posts.create') }}" class="btn btn-primary" type="button">
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
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $post)
                                        <tr>

                                            <td scope="row"> {{ $post->title }}
                                            </td>


                                            @if ($post->status=='ACTIVE')
                                    <td>
                                        <a class="badge badge-success" href="{{ route('change.status.blogs', $post)}}" title="Activado">
                                            Activo<i class="fa fa-check"></i>
                                        </a>

                                    </td>

                                    @else
                                    <td>
                                        <a class="badge badge-danger" href="{{ route('change.status.posts', $post)}}" title="Desactivado">
                                            No activo
                                        </a>

                                    </td>

                                    @endif




                                            <td style="width: 200px;">
                                                {!! Form::open(['route' => ['posts.destroy', $post], 'method' => 'DELETE', 'id' => 'delete-form']) !!}

                                                <a class="btn btn-outline-info"
                                                    href="{{ route('posts.edit', $post) }}" title="Editar">
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
