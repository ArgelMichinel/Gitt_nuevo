@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('Styles/query_packets.css') }}">
@endsection

@section('output')
<div class="formu">
    <div id="respuesta"><h2>Se Actualizaron los envíos con éxito</h2></div>
</div>
@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')