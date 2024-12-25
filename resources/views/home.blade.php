@extends('layouts.admin')
@section('title', 'Panel administrador')
@section('styles')
@endsection
@section('options')
@endsection
@section('preference')
@endsection
@section('content')
<style>
    #boton{
        text-decoration: none;
    }
</style>
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
                        <div class="h3">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="chart-wrapper mt-3 mx-3" style="height:35px;">
                        <a href="{{ route('configs.edit', Auth::user()->id) }}" class="small-box-footer h4">Editar perfil <i
                                class="fa fa-arrow-circle-right"></i></a>
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
                        <a href="{{ route('links.index') }}" class="small-box-footer h4">Ir <i
                                class="fa fa-arrow-circle-right"></i></a>
                    </div>

                </div>
            </div>
        </div>
        <div class="page-header">
            <h3 class="page-title">
                Resumen Financiero
            </h3>
        </div>
        <div class="row">
            <div class="col-md-6 grid-margin">
                <a href="{{ route('expenses.index') }}" id="boton">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-0">Gastos</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-inline-block pt-3">
                                    <div class="d-md-flex">
                                        <h2 class="mb-0 text-danger">${{ number_format($totalExpenses, 2) }}</h2>
                                        <div class="d-flex align-items-center ml-md-2 mt-2 mt-md-0">
                                            <i class="far fa-clock text-muted"></i>
                                            <small class=" ml-1 mb-0">Actualización: {{ $ExpenselastUpdated }}</small>
                                        </div>
                                    </div>
                                    <small class="text-gray">Basado en los gastos totales.</small>
                                </div>
                                <div class="d-inline-block">
                                    <i class="fas fa-money-bill-wave text-info icon-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 grid-margin">
                <a href="{{ route('incomes.index') }}" id="boton">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-0">Ingresos</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-inline-block pt-3">
                                    <div class="d-md-flex">
                                        <h2 class="mb-0 text-success">${{ number_format($totalIncomes, 2) }}</h2>
                                        <div class="d-flex align-items-center ml-md-2 mt-2 mt-md-0">
                                            <i class="far fa-clock text-muted"></i>
                                            <small class="ml-1 mb-0">actualización: {{ $IncomelastUpdated }}</small>
                                        </div>
                                    </div>
                                    <small class="text-gray">Basado en los ingresos totales del mes</small>
                                </div>
                                <div class="d-inline-block">
                                    <i class="fas fa-hand-holding-usd text-danger icon-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-0">Balance</h4>
                        <div class="d-flex justify-content-around align-items-center">
                            <div class="d-inline-block pt-3">
                                <div class="d-md-flex">
                                    <h2 class="mb-0 {{ $balance <= 0 ? 'text-danger' : 'text-success' }}">
                                        ${{ number_format($balance, 2) }}</h2>
                                    <div class="d-flex align-items-center ml-md-2 mt-2 mt-md-0">
                                    </div>
                                </div>
                            </div>
                            <div class="d-inline-block">
                                <i class="fas fa-balance-scale text-success icon-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
