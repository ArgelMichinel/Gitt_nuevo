@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('Styles/info_client.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Styles/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Styles/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jquery-3.5.1.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/vfs_fonts.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/buttons.print.min.js') }}"></script>
    {{-- <script type="text/javascript" language="javascript" src="{{ asset('JS/list_cadetes.js') }}"></script> --}}
    <script type="text/javascript" class="init">
        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'Blfrtip',
                buttons: [
                    'copy', 'csv', 'excel',  'print' //,'pdf'
                ],
                "lengthMenu": [ [5 ,10, 25, 50, -1], [5, 10, 25, 50, "Todos"] ],
                "pageLength": 5,
                "scrollX": true
            } );
        } );
    </script>
@endsection

@section('output')

<h2>Lista de cadetes</h2>
<div class="table_container" style="padding-bottom: 70px;" id="cadetes-list">
    <table id="example" class="display nowrap" style="width:100%">
        <thead>
            
            <tr>
                <th>Editar</th>
                <th>email</th>
                <th>N° cadete</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Status</th>
            </tr>
            
        </thead>
        <tbody  id="data_table">
            
            @for ($i=0 ; $i < count($cadetes); $i++)
                <tr>
                    <td style="text-align: center;"><a href="{{ route('registrarCadete', ['num_cadete' => $cadetes[$i]['num_cadete']]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true" title="ver cliente"
                        aria-label="ver cliente"></i></a></td>

                    @foreach ($cadetes[$i] as $key => $value)
                        @if (($key != 'id') && ($key != 'email_verified_at') && ($key != 'password') && 
                        ($key != 'remember_token') && ($key != 'created_at') && ($key != 'updated_at'))

                            @if ($key === 'status')
                                @switch($value)
                                    @case(0)
                                        <td>Inactivo</td>
                                        @break
                                    @case(1)
                                        <td>Activo</td>
                                        @break
                                    @default
                                        
                                @endswitch
                                
                            @else
                                <td>{{ $value }}</td>
                            @endif

                        @endif
                        
                    @endforeach

                </tr>
            @endfor
                
        </tbody>
        <tfoot>
            
            <tr>
                <th>Editar</th>
                <th>email</th>
                <th>N° cadete</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Status</th>
            </tr>
            
        </tfoot>
    </table>
</div>

<form action="regis_cadete.php" method="get" style="display: none;">

    <input type="text" name="num_cadete" id="field_cadete">

    <button type="submit" id="cadete_edit">Registrar</button>
</form>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')