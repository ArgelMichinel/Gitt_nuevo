@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('Styles/info_shipping.css') }}">
@endsection

@section('output')

<div class="presentacion">

    <form action="" method="post" class="container-reg">
        @csrf
        <h1>Ingrese los datos del envío</h1>

        <label for="shipnum"><b>Escribe el numero de envío:</b></label>
        <input type="text" placeholder="Introduzca el número" name="shipnum" required>

        <label for="sender_id"><b>Seleccione el código del vendedor:</b></label>
        <select name="sender_id" required>
            @for ($i=0; $i < count($clientes); $i++)

                @if ($clientes[$i]->id_MELI)
                    <option value="{{ $clientes[$i]->id_MELI }}">{{ $clientes[$i]->name }}-MELI</option>
                @endif
                @if ($clientes[$i]->id_TN)
                    <option value="{{ $clientes[$i]->id_TN }}">{{ $clientes[$i]->name }}-Tienda Nube</option>
                @endif
                
            @endfor
        </select>

        <button type="submit" class="btn">Consultar</button>
    </form>

</div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')