@extends('layouts.base')

@section('descripcion') Alacenamiento sin limites - PruebaUP! @stop
@section('keywords') uso compartido de archivos, almacenamiento en la nube, almacenamiento de archivos en línea, aplicaciones, plataforma de aplicaciones, @stop
@section('head')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@stop

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script> @stop


@section('body')
    <header class="navbar navbar-dark sticky-top bg-marron flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3 text-bold" href="/">Prueba UP!</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse"
                data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <input id="buscarArchivo" class="form-control form-control-dark w-100 d-none-ni" type="text"
               placeholder="Buscar un archivo..." aria-label="Search">
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="{{asset('/salir')}}">Salir</a>
            </li>
        </ul>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">
                                <span data-feather="file"></span>
                                Archivos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cuenta">
                                <span data-feather="user"></span>
                                Mi cuenta
                            </a>
                        </li>
                    </ul>
                    <br/>
                    <br/>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Mi plan</span>
                    </h6>


                    <p class="mb-2 px-3">
                        @if($user->getTiempoPlan())
                        Tus archivos estarán hasta el
                        <br/><b class="text-bold tiempoPlan">{{$user->getTiempoPlan()}}</b>.
                        <br/>
                        @endif
                        Para evitar que se borren tus archivos automáticamente, añádele mas tiempo a tu plan de almacenamiento.
                    </p>


                    <a class="btn btn-sm btn-outline-primary mx-3"
                       href="{{asset('/cuenta/planes')}}"
                    >
                        <span data-feather="shopping-cart"></span>
                        Aumentar mi plan</a>

                </div>
            </nav>

            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <input class="d-none" type="hidden" id="i_token" name="_token" value="{{ csrf_token() }}">
                @yield("content")
            </main>
        </div>
    </div>
@stop
