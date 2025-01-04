@extends('layouts.app')

@section('inc_head')
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link rel="stylesheet" href="{{ asset( 'Styles/assign_packets.css') }}">
@endsection

@section('output')

<div class="presentacion">
    <div id="respuesta"><h2>Se asignaron los paquetes con éxito</h2></div>
</div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
