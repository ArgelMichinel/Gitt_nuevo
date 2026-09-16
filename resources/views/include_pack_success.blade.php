@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/integracion.css') }}">
    <script type="text/javascript" language="javascript" src="{{ asset('JS/access.js') }}"></script>
@endsection

@section('output')
<div class="extern">
  
    <div class="formu">
        <div id="respuesta">
            <h2> {{ $mensaje }} </h2>
            <a class="underH" href="{{ route('mostrarenvios') }}">Regresar a envíos registrados</a>
        </div>
    </div>
    
</div>
@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')