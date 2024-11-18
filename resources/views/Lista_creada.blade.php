@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/access.css') }}">
    <link rel="stylesheet" href="{{ asset('Styles/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Styles/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Styles/query_packets.css') }}">
@endsection

@section('output')

    <div class="formu">
        <div id="respuesta"><h2>Se creó la lista "{{ $lista_name }}" con éxito</h2></div>
    </div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
