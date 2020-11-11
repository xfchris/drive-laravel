@extends('layouts.baseUser')
@section('contentUser')
    <fieldset class="mb-3 col-sm-8">
        <legend>Datos personales</legend>
        <div class="form-group row">
            <label for="staticEmail" class="col-sm-4 col-form-label">Email</label>
            <div class="col-sm-8">
                <input type="text" readonly="" class="form-control-plaintext"
                       value="{{$user->email}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="nombres" class="col-sm-4 col-form-label">Nombres y apellidos</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="iNombres" id="iNombres"
                       value="{{$user->name}}" required>
            </div>
        </div>

        <button id="btnActualizarCuenta" type="button" class="btn btn-success mt-3">Actualizar cuenta</button>
    </fieldset>
<br/>
    @include('user/form_plan')

@stop
