@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/access.css') }}">
@endsection

@section('output')
<div class="extern">
  
    <div class="table_container" style="padding-bottom: 40px;">
        <x-filtros></x-filtros>
        
        <x-select_columna></x-select_columna>
        
        <x-tabla_query></x-tabla_query>
        
    </div>
    
    <h2>Crear Lista</h2>
    
    <div class="list_container">
        
        <div style="align-self: center;"><label for="selection_check">Seleccionar todos los registros:</label><input type="checkbox" class="Checkbox" onclick="select_all()" name="selection_check" id="selection_check" value="true"></div>
        
        <form action="" method="post">
            <label for="new_list[name]">Nombre de la lista:</label>
            <input type="text" id="name_list" name="new_list[name]" placeholder="Nombre de la lista" required>
            <input type="text" id="values_list" name="new_list[values]" style="display: none;">
            <button type="submit" id="submit_list" style="display: none;">Crear Lista</button>
        </form>
        <button class="btn" onclick="constr_list()">Crear Lista</button>
        
    </div>


</div>
@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
