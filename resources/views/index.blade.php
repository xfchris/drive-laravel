@extends('layouts.base')

@section('title') Alacenamiento sin limites - PruebaUP! @stop
@section('descripcion') Alacenamiento sin limites - PruebaUP! @stop
@section('keywords') uso compartido de archivos, almacenamiento en la nube, almacenamiento de archivos en línea, aplicaciones, plataforma de aplicaciones, @stop

@section('bodyCss') d-flex h-100 text-center text-white bg-color-b @stop

@section('body')
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="mb-auto">
            <div>
                <h3 class="float-md-left mb-0">PruebaUP!</h3>
                <nav class="nav nav-masthead justify-content-center float-md-right">
                    <a class="nav-link active" aria-current="page" href="{{asset('auth/google')}}" >Entrar</a>
                </nav>
            </div>
        </header>

        <main class="px-3">
            <h1>Almacena tus archivos en cuestiones de segundos!</h1>
            <p class="lead">PruebaUP!, un servicio de almacenamiento de archivos, seguro, confiable y lo mejor, de muy facil manejo!
                <br/>Registrate facil y comienza a usar este novedoso servicio</p>
            <p class="lead">
                <a href="{{asset('auth/google')}}" class="btn btn-lg btn-secondary font-weight-bold border-white bg-white">Entra con google</a>
            </p>
        </main>

        <footer class="mt-auto text-white-50">
            <p>Contactanos por <a target="_blank" href="https://linkedin.com/in/christian-valencia-cuero-49841759/" class="text-white">@linkedin</a>.</p>
        </footer>
    </div>
@stop
