@extends('layouts.baseLogin')

@section('title') Mi cuenta - PruebaUP! @stop
@section('cssFile') {{asset('css/dashboard.css')}} @stop

@section('script')
    <script src="{{asset('js/user.js')}}"></script> @stop


@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">
        <div class="btn-toolbar mb-2 mb-md-0"> </div>
        <h3 class="h4">Mi cuenta</h3>
    </div>
    <div class=" mb-3 border-bottom">
    </div>

    @yield('contentUser')

@stop
