@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/regis_cadete.css') }}">
@endsection

@section('output')

    <h2>Agregar Cliente</h2>
    <div class="formu">
      <form action="" method="post" class="container-reg">
        @csrf
        <h1>Agregar Cliente</h1>

        <label for="cliente[name]"><b>Nombre</b></label>
        <input type="text" placeholder="Introduzca el nombre" name="cliente[name]" required>

        <label for="cliente[email]"><b>Email</b></label>
        <input type="email" placeholder="Introduzca el email" name="cliente[email]" required>

        <button type="submit" class="btn">Registrar</button>
      </form>
    </div>


@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')