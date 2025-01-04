@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/integracion.css') }}">
@endsection

@section('output')
<div class="extern">
  
    <h2>Seleccione la tienda que desea comenzar a integrar</h2>
  
    <div class="formu row">
      
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <a class="enlace" href="{{ route('integrar_MELI') }}"> Mercado Libre </a>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <a class="enlace" href="{{ route('integrar_MELI') }}"> Tienda Nube</a>
            </div>
        </div>
        
    </div>
</div>
@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')