@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('Styles/info_shipping.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('Styles/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Styles/buttons.dataTables.min.css') }}">
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jquery-3.5.1.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/vfs_fonts.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/buttons.print.min.js') }}"></script>
    <script type="text/javascript" class="init">
        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel',  'print' //,'pdf'
                ],
                //"lengthMenu": [ [5 ,10, 25, 50, -1], [5, 10, 25, 50, "Todos"] ],
                "pageLength": 25,
                "ordering": false
            } );
        } );
    </script>
@endsection

@section('output')

<div class="table_container" id="screem-respuesta">
    <table id="example" class="display nowrap" style="width:70%">
        <thead>
            
            <tr>
                <th>Parámetro</th>
                <th>Respuesta</th>
            </tr>
            
        </thead>
        <tbody  id="data_table">
            
            @foreach ($packets as $key => $value)
                <tr>
                    <th>{{ $key }}</th>
                    @if ($value instanceof DateTime)
                        <td>{{ $value->format('Y-m-d H:i:s') }}</td>
                    @else
                        <td>{{ $value }}</td>
                    @endif
                </tr>
            @endforeach
                
        </tbody>
        <tfoot>
            
            <tr>
                <th>Parámetro</th>
                <th>Respuesta</th>
            </tr>
            
        </tfoot>
    </table>
</div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')