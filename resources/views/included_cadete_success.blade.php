@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/regis_cadete.css') }}">
@endsection

@section('output')
<div class="formu">
    <div id="respuesta"><h2>Se agregó/actualizó el cadete numero <?=$cadete ['num_cadete']?> con éxito</h2></div>
</div>
@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')