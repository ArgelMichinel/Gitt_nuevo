@extends('layouts.app')

@section('inc_head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('Styles/access.css') }}">
    <link rel="stylesheet" href="{{ asset('Styles/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Styles/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Styles/query_packets.css') }}">
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jquery-3.5.1.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/vfs_fonts.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/buttons.print.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/update_packets8.js') }}"></script>
    <script type="text/javascript" class="init">
        $(document).ready(function() {
            var table = $('#example').DataTable( {
                dom: 'Blfrtip',
                buttons: [
                    'copyHtml5', 'csvHtml5', 'excelHtml5',  'print' //,'pdf'
                ],
                "lengthMenu": [ [5 ,10, 25, 50, 75, 100, 150, 200, -1], [5, 10, 25, 50, 75, 100, 150, 200, "Todos"] ],
                "pageLength": 10,
                "scrollX": true
            } );

            $('.switch-1').on( 'click', function (e) {
                e.preventDefault();

                // Get the column API object
                var column = table.column( $(this).attr('data-column') );
                // Toggle the visibility
                column.visible( ! column.visible() );
            } );

        } );
    </script>
@endsection

@section('output')

    <div class="table_container" style="padding-bottom: 40px;">
        <x-filtros :clients="$clients" :cadetes="$cadetes"></x-filtros>
        
        <x-select_columna></x-select_columna>
        
        <x-tabla_query :packets="$packets" :clients="$clients" :cadetes="$cadetes" :admin="$admin"></x-tabla_query>
        
    </div>
    
    <h2>Actualizar datos de MercadoLibre</h2>

    <div class="filter_container" id="contenedor_update1">
        
        <div style="align-self: center;"><label for="selection_check">Seleccionar todos los registros:</label><input type="checkbox" class="Checkbox" onclick="select_all()" name="selection_check" id="selection_check" value="true"></div>
        
        <button class="btn" onclick="constr_list_update()">Actualizar envíos Seleccionados</button>
        
    </div>

    <h2>Actualizar datos internos</h2>

    <div class="filter_container" id="contenedor_update2">
        
        <form action="" method="post">
            @csrf
            <label for="new_list[status_logistica]">Estatus logística:</label>
            <select name="new_list[status_logistica]">
                <option value=0>Ingresado</option>
                <option value=1>Entregado</option>
                <option value=2>1era visita</option>
                <option value=3>2da visita</option>
                <option value=4>Devuelto logís.</option>
                <option value=5>Devuelto MELI</option>
                <option value=6>Entr. y Cobra.</option>
            </select>
            
            <input type="text" name="new_list[comment_logis]" placeholder="Puede ingresar comentario">
            <input type="text" id="values_list" name="values" style="display: none;">
            <button type="submit" id="submit_list" style="display: none;">Crear Lista</button>
        </form>
        <button class="btn" onclick="constr_list_logis()">Actualizar envíos Seleccionados</button>
        
    </div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
