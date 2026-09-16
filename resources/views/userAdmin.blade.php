@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" type="text/css" href="{{ asset( 'Styles/users_admin.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset( 'Styles/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset( 'Styles/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset( 'fonts/font-awesome.min.css') }}" type="text/css" media="screen">
    <script type="text/javascript" language="javascript" src="{{ asset( 'JS/jquery-3.5.1.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset( 'JS/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset( 'JS/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset( 'JS/jszip.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset( 'JS/vfs_fonts.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset( 'JS/buttons.html5.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset( 'JS/buttons.print.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset( 'JS/users_admin.js') }}"></script>
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

    <h2>Lista de administradores</h2>
    <div class="table_container" style="padding-bottom: 70px;" id="admin_list">
        <table id="example" class="display nowrap" style="width:100%">
            <thead>
                
                <tr>
                    <th>Nombre</th>
                    <th>email</th>
                    <th>Borrar</th>
                    <th>Editar</th>
                </tr>
                
            </thead>
            <tbody  id="data_table">
                @for ($i = 1; $i < count($administra); $i++)
                    <tr>
                        @foreach ($administra[$i] as $key => $value )
                            @if ($key != 'id')
                                <td>{{ $value }}</td>
                            @endif
                        @endforeach
                        <td style="text-align: center; color: black;"><i class="fa fa-trash" aria-hidden="true" title="Eliminar admin."
                            aria-label="Eliminar admin."></i></td>
                        <td style="text-align: center; color: black;"><a href="{{ route('formuAddAdmin', ['id' => $administra[$i]['id']]) }}"><i class="fa fa-pencil" aria-hidden="true" title="Editar admin."
                            aria-label="Editar admin."></i></a></td>
                    </tr>
                @endfor
            </tbody>
            <tfoot>
                
                <tr>
                    <th>Nombre</th>
                    <th>email</th>
                    <th>Borrar</th>
                    <th>Editar</th>
                </tr>
                
            </tfoot>
        </table>
    </div>

    <div id="id01" class="modal">
        <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
        <div class="modal-content">
            <div class="container">
                <h1>Eliminar administrador</h1>
                <p style="font-size: 25px; color: black;">Esta acción no puede ser deshecha. ¿Está seguro de que desea borrar el administrador?</p>

                <div class="clearfix">
                    <button type="button" class="btn cancelbtn" onclick="document.getElementById('id01').style.display='none'" style="background-color: #484242;">Cancelar</button>
                    <form action="" method="post">  
                        @csrf
                        <input type="text" name="del_admin" id="del_admin" style="display: none; background-color: red;">
                        <button type="submit" class="btn deletebtn">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <button class="btn" onclick="agregar()">Agregar Administrador</button> 

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
