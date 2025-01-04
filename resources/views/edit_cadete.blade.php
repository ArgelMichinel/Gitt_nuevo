@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/regis_cadete.css') }}">
@endsection

@section('output')

<h2>Editar Cadete</h2>
<div class="formu">
  <form action="regis_cadete.php" method="get" class="container-reg">
    <h1>Seleccione el cadete a editar</h1>

    <label for="num_cadete"><b>Cadete:</b></label>
    <select name="num_cadete">

        @for ($i=0; $i < count($cadetes); $i++)
                <option value="{{ $cadetes[$i]['num_cadete'] }}">{{ $cadetes[$i]['nombre'] }} {{ $cadetes[$i]['apellido'] }}</option>
        @endfor
        
    </select>


    <button type="submit" class="btn">Editar</button>
  </form>
</div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')