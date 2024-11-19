@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset( 'Styles/add_admin.css') }}">
@endsection

@section('output')

    <div class="formu">
        <div id="respuesta"><h2>Se agregó el administrador con éxito</h2></div>
    </div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
