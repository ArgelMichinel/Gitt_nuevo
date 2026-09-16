@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset( 'Styles/add_admin.css') }}">
    <script type="text/javascript" language="javascript" src="{{ asset( 'JS/add_admin.js') }}"></script>
@endsection

@section('output')

    <h2>Registrar Administrador</h2>
    <div class="formu">
    <form action="" method="post" class="container-reg">
        @csrf
        <h1>Nuevo Administrador</h1>

        <input type="text" name="new_admin[id]" value="{{ $datos->id ?? '' }}" style="display: none">

        <label for="new_admin[nombre]"><b>Nombre</b></label>
        <input type="text" placeholder="Introduzca el nombre" name="new_admin[nombre]" value="{{ $datos->name ?? '' }}" required>
        
        <label for="new_admin[email]"><b>email</b></label>
        <input type="text" placeholder="Introduzca el email" name="new_admin[email]" value="{{ $datos->email ?? '' }}" required>

        <label for="new_admin[password]"><b>Contraseña</b></label>
        <input type="password" placeholder="Introduzca su contraseña" name="new_admin[password]" required>
        
        <label for="new_admin[master]"><b>Usuario Master</b></label>
        <input type="checkbox" name="new_admin[master]" {{ $datos->master ?? '' }}>

        <button type="submit" class="btn">Registrar</button>
    </form>
        
        <input type="password" id="errores" name="errores" value="<?=$num_errores?>" style="display: none;">
        
        
        <div id="id01" class="modal">
        <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
        <div class="modal-content">
            <div class="container">
                <h1>Errores</h1>
                
                @for ($i = 0; $i < count($errores); $i++)
                    <p style="font-size: 25px; color: red;">- {{ $errores[$i] }}</p>
                @endfor

            <div class="clearfix">
                <button type="button" class="btn3 cancelbtn" onclick="document.getElementById('id01').style.display='none'" style="background-color: #484242;">Aceptar</button>
            </div>
            </div>
            </div>
        </div>
        
    </div> 

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
