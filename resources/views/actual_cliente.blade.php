@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/regis_cadete.css') }}">
@endsection

@section('output')

    <h2>Actualizar Cliente</h2>
    <div class="formu">
      <form action="" method="post" class="container-reg">
        @csrf
        <h1>Actualizar Cliente</h1>

        <label for="cliente[name]"><b>Nombre</b></label>
        <input type="text" placeholder="Introduzca el nombre" name="cliente[name]" value="{{ $cliente['name'] ?? '' }}" required>

        <label for="cliente[id]" style="display: none;"><b>id</b></label>
        <input type="text" name="cliente[id]"  style="display: none;" value="{{ $cliente['id'] ?? '' }}">

        <button type="submit" class="btn">Registrar</button>
      </form>
    </div>


@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')