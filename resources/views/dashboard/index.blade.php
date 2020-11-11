@extends('layouts.baseLogin')

@section('title') Dashboard - PruebaUP! @stop
@section('cssFile') {{asset('css/dashboard.css')}} @stop


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">

        <div class="btn-toolbar mb-2 mb-md-0">
                <input class="d-none" multiple type="file" id="iSubirArchivo"  name="iSubirArchivo" />

            <div class="btn-group mr-2">
                <button type="button" class="btn btn-sm btn-outline-primary" id="btnSubirArchivo">
                    <span data-feather="upload-cloud"></span>
                    Subir archivo</button>
            </div>
        </div>

        <h3 class="h4">Mis archivos</h3>

    </div>

    <div id="progressFiles" class="progress d-none-ni">
        <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Subiendo en 25%</div>
    </div>

    <div class=" mb-3 border-bottom">

    </div>

    <div class="table-responsive">
        <table id="listaDeArchivos" class="table table-striped table-sm table-hover">

        </table>
    </div>
@stop
