@extends('layouts.admin')
@section('title','Panel administrador')
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
        Dashboard
      </h3>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card text-white bg-dark">

                <div class="card-body pb-0">
                    <div class="float-right">
                        <i class="fas fa-user fa-4x"></i>
                    </div>
                    <div class="text-value h4"><strong>Bienvenido</strong>
                    </div>
                    <div class="h3">{{Auth::user()->name}}</div>
                </div>
                <div class="chart-wrapper mt-3 mx-3" style="height:35px;">
                    <a href="{{ route('configs.edit', Auth::user()->id) }}" class="small-box-footer h4">Editar perfil <i class="fa fa-arrow-circle-right"></i></a>
                </div>

            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card  text-white bg-primary">

                <div class="card-body pb-0">

                    <div class="float-right">
                        <i class="fas fa-lightbulb fa-4x"></i>
                    </div>
                    <div class="text-value h4"><strong>Gestionar proyectos</strong>
                    </div>
                    <div class="h3"></div>
                </div>
                <div class="chart-wrapper mt-3 mx-3" style="height:35px;">
                    <a href="{{ route('links.index') }}" class="small-box-footer h4">Ir <i class="fa fa-arrow-circle-right"></i></a>
                </div>

            </div>
        </div>
    </div>
  </div>
@endsection
