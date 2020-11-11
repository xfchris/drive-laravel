@extends('layouts.baseLogin')

@section('title') Mi cuenta - PruebaUP! @stop
@section('cssFile') {{asset('css/dashboard.css')}} @stop

@section('script') <script src="{{asset('js/user.js')}}"></script> @stop


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">

        <div class="btn-toolbar mb-2 mb-md-0">

        </div>
        <h3 class="h4">Mi cuenta</h3>

    </div>
    <div class=" mb-3 border-bottom">
    </div>







        <fieldset class="mb-3 col-sm-8">
            <legend>Datos personales</legend>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" readonly="" class="form-control-plaintext"
                           value="{{$user->email}}">
                </div>
            </div>
            <div class="form-group">
                <label for="nombres">Nombres y apellidos</label>
                <input type="text" class="form-control" name="iNombres"  id="iNombres"
                value="{{$user->name}}" required>
            </div>
        </fieldset>


        <fieldset class="form-group">
            <legend>Planes de almacenamientos</legend>
            <p>Selecciona el plan que mas se asemeje a tus necesidades, tus archivos permanecerán por el tiempo que selecciones</p>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" id="iPlan" name="iPlan"
                           value="1" checked="">
                    Una dia
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" id="iPlan" name="iPlan"
                           value="2" checked="">
                    Una semana
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" id="iPlan" name="iPlan"
                           value="3">
                    3 Semanas
                </label>
            </div>
            <div class="form-check disabled">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" id="iPlan" name="iPlan"
                           value="option3">
                    1 mes
                </label>
            </div>
        </fieldset>


        <button id="btnActualizarCuenta" type="button" class="btn btn-success mt-3">Actualizar </button>



@stop
