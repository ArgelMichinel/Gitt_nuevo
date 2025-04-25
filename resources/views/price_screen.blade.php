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
    <script type="text/javascript" language="javascript" src="{{ asset('JS/update_packets7.js') }}"></script>
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
    

    <h2>Actualizar datos internos</h2>

    <div class="filter_container" id="contenedor_update2">
        
        <form action="" method="post">
            @csrf
            
            <label for="new_list[precio1]">Precio {{ $precios[0]['Zona']}}:</label>
            <input type="number" name="new_list[precio][1]" id="new_list[precio1]" value="{{ isset($precios[0]['precio']) ? $precios[0]['precio'] : '' }}"> <br>
            <label for="new_list[precio2]">Precio {{ $precios[1]['Zona']}}:</label>
            <input type="number" name="new_list[precio][2]" id="new_list[precio2]" value="{{ isset($precios[1]['precio']) ? $precios[1]['precio'] : '' }}"> <br>
            <label for="new_list[precio2]">Precio {{ $precios[2]['Zona']}}:</label>
            <input type="number" name="new_list[precio][3]" id="new_list[precio3]" value="{{ isset($precios[2]['precio']) ? $precios[2]['precio'] : '' }}"> <br>
            <label for="new_list[precio2]">Precio {{ $precios[3]['Zona']}}:</label>
            <input type="number" name="new_list[precio][4]" id="new_list[precio4]" value="{{ isset($precios[3]['precio']) ? $precios[3]['precio'] : '' }}"> <br>
            <button class="btn" type="submit" id="submit_list">Actualizar</button>
        </form>
        
    </div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
