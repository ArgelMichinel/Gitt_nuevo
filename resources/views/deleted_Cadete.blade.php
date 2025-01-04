@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/delet_list.css') }}">
@endsection

@section('output')
<div class="extern">
  
    <div class="presentacion">
        <div id="respuesta"><h2>Se eliminó el cadete con éxito</h2></div>
    </div>
    
</div>
@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')