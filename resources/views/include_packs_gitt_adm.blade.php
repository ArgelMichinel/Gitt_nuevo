@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/include_packs_gitt_adm.css') }}">
@endsection

@section('output')

    <h2>Registrar Envío</h2>
    <div class="formu">
      <form action="" method="post" class="container-reg">
        @csrf
        <h1>Nuevo Envío</h1>

        <input type="text" name="new_packet[id_ship]" value="{{ $packet->id_ship ?? '' }}" style="display: none">

        <label for="new_packet[sender_id]"><b>Seleccione el código del vendedor:</b></label>
        <select name="new_packet[sender_id]" required>
            @for ($i=0; $i < count($clients); $i++)
              @if (isset($packet->sender_id))
                <option value="{{ $clients[$i]->id_Gitt }}" {{ $clients[$i]->id_Gitt == $packet->sender_id ? 'selected' : '' }}>{{ $clients[$i]->name }}</option>
              @else
                <option value="{{ $clients[$i]->id_Gitt }}">{{ $clients[$i]->name }}</option>
              @endif
            @endfor
        </select>

        <label for="new_packet[receiver_name]"><b>Nombre del receptor</b></label>
        <input type="text" placeholder="Introduzca el nombre y apellido" name="new_packet[receiver_name]" value="{{ $packet->receiver_name ?? '' }}" required>

        <label for="new_packet[receiver_phone]"><b>Teléfono</b></label>
        <input type="text" placeholder="Introduzca el teléfono" name="new_packet[receiver_phone]" value="{{ $packet->receiver_phone ?? '' }}" required>

        <label for="new_packet[city]"><b>Ciudad</b></label>
        <input type="text" placeholder="Introduzca el nombre de la ciudad" name="new_packet[city]" value="{{ $packet->city ?? '' }}" required>
          
        <label for="new_packet[street_name]"><b>Nombre de la calle</b></label>
        <input type="text" placeholder="Introduzca el nombre de la calle" name="new_packet[street_name]" value="{{ $packet->street_name ?? '' }}" required>
          
        <label for="new_packet[street_number]"><b>Altura de la calle</b></label>
        <input type="text" placeholder="Introduzca el número" name="new_packet[street_number]" value="{{ $packet->street_number ?? '' }}" required>
          
        <label for="new_packet[comment]"><b>Comentario</b></label>
        <input type="text" placeholder="Introduzca un comentario" name="new_packet[comment]" value="{{ $packet->comment ?? '' }}">

        <label for="new_packet[zip_code]"><b>Código postal</b></label>
        <input type="text" placeholder="Introduzca el código postal" name="new_packet[zip_code]" value="{{ $packet->zip_code ?? '' }}" required>

        <label for="new_packet[state]"><b>Provincia</b></label>
          <select name="new_packet[state]" required>
            <option value="Capital Federal">Capital Federal</option>
            <option value="Buenos Aires">Buenos Aires</option>
          </select>

        <label for="new_packet[delivery_preference]"><b>¿Es un domicilio laboral?</b></label>
          <select name="new_packet[delivery_preference]">
            @if (isset($packet->delivery_preference))
              <option value="0" {{ $packet->delivery_preference == 0 ? 'selected' : '' }}>No</option>
              <option value="1" {{ $packet->delivery_preference == 1 ? 'selected' : '' }}>Sí</option>
            @else
              <option value="0">No</option>
              <option value="1">Sí</option>
            @endif
          </select>

        <label for="new_packet[description]"><b>Descripción</b></label>
        <input type="text" placeholder="Introduzca la descripción del envío" name="new_packet[description]" value="{{ $packet->description ?? '' }}"   required>

        <button type="submit" class="btn">Registrar Envío</button>
      </form>
    </div>


@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')