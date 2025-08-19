@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/integracion.css') }}">
    <script type="text/javascript" language="javascript" src="{{ asset('JS/access.js') }}"></script>
@endsection

@section('output')
<div class="extern">
  
    <div class="formu">
        <div id="respuesta">
            <h2>Se enviaron {{ $num }} encomiendas exitosamente</h2>
            <p>{{ $detalle }}</p>
            <hr>
            
            @if ($repetidos > 0)
                <h2>Habían {{ $repetidos }} envíos repetidos (ya ingresados)</h2>
            @endif

            <a class="underH" href="{{ route('login') }}">Ingresar al sistema</a>
        </div>
    </div>
    
</div>
@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')