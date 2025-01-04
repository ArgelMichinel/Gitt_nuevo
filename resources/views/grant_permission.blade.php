@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/access.css') }}">
    <link rel="stylesheet" href="{{ asset('Styles/desk_client.css') }}">
@endsection

@section('output')

    <div class="container-fluid" id="principal">
        <div class="row"><p>¿Que desea realizar?</p></div>
        
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center">
                <a class="btn" href="{{ route('integrar_MELI')}}">Mercado Libre</a>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center">
                <a class="btn" href="{{ route('integrar_MELI')}}">Tienda Nube</a>
            </div>

        </div>
    </div>


@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
