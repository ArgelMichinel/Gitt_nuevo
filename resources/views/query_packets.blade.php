@extends('layouts.app')

@section('inc_head')
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
    <script type="text/javascript" language="javascript" src="{{ asset('JS/query_packets.js') }}"></script>
    <script type="text/javascript" class="init">
        $(document).ready(function() {
            var table = $('#example').DataTable( {
                dom: 'Blfrtip',
                buttons: [
                    'copyHtml5', 'csvHtml5', 'excelHtml5',  'print' //,'pdf'
                ],
                "lengthMenu": [ [5 ,10, 25, 50, -1], [5, 10, 25, 50, "Todos"] ],
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
    
    <h2>Crear Lista</h2>
    
    <div class="list_container">
        
        <div style="align-self: center;"><label for="selection_check">Seleccionar todos los registros:</label><input type="checkbox" class="Checkbox" onclick="select_all()" name="selection_check" id="selection_check" value="true"></div>
        
        <form action="" method="post">
            @csrf
            <label for="new_list[name]">Nombre de la lista:</label>
            <input type="text" id="name_list" name="new_list[name]" placeholder="Nombre de la lista" required>
            <input type="text" id="values_list" name="new_list[values]" style="display: none;">
            <button type="submit" id="submit_list" style="display: none;">Crear Lista</button>
        </form>
        <button class="btn" onclick="constr_list()">Crear Lista</button>
        
    </div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
