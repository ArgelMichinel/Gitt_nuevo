@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Styles/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Styles/desk_client.css') }}">
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jquery-3.5.1.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/vfs_fonts.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/buttons.print.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/query_packets_client.js') }}"></script>
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
        <x-filtros_clientes></x-filtros_clientes>
        
        <x-tabla_query_clientes :packets="$packets"></x-tabla_query_clientes>
        
    </div>
    
    <div id="id01" class="modal">
        <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
        <div class="modal-content">
            <div class="container">
                <h1>Mensaje</h1>
        
                <p>Para visualizar los envíos asignados a la compañía primero debe seleccionar un periodo o una localidad que desee consultar.</p>
        
                <div class="clearfix">
                    <button type="button" class="btn3 cancelbtn" onclick="document.getElementById('id01').style.display='none'" style="background-color: #484242;">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
