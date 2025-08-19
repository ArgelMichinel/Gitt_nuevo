@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/regis_cadete.css') }}">
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
    <script type="text/javascript" language="javascript" src="{{ asset('JS/info_client.js') }}"></script>
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

<h2>Lista de clientes</h2>
<div class="table_container" style="padding-bottom: 70px;" id="client-list">
    <table id="example" class="display nowrap" style="width:100%">
        <thead>
            
            <tr>
                <th>Select</th>
                <th># cliente</th>
                <th>id MELI</th>
                <th>id TNube</th>
                <th>nombre</th>
                <th>email</th>
                <th>Borrar</th>
                <th>Editar</th>
            </tr>
            
        </thead>
        <tbody  id="data_table">
            
            @for ($i=0 ; $i < count($clientes); $i++)
                <tr>
                    <td style="text-align: center;"><i class="fa fa-user-circle-o" aria-hidden="true" title="ver cliente"
					aria-label="ver cliente"></i></td>
                    <td>{{ $clientes[$i]['id'] }}</td>
                    <td>{{ $clientes[$i]['id_MELI'] }}</td>
                    <td>{{ $clientes[$i]['id_TN'] }}</td>
                    <td>{{ $clientes[$i]['name'] }}</td>
                    <td>{{ $clientes[$i]['email'] }}</td>
                    <td style="text-align: center; color: black;"><i class="fa fa-trash" aria-hidden="true" title="Eliminar cliente."
					aria-label="Eliminar cliente"></i></td>
                    <td style="text-align: center;"><a href="{{ route('name_client', ['id' => $clientes[$i]['id']]) }}"><i class="fa fa-pencil-square-o" aria-hidden="true" title="Editar cliente"
                        aria-label="Editar cliente"></i></a></td>
                </tr>
            @endfor
                
        </tbody>
        <tfoot>
            
            <tr>
                <th>Select</th>
                <th># cliente</th>
                <th>id MELI</th>
                <th>id TNube</th>
                <th>normbre</th>
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
      <h1>Eliminar cliente</h1>
      <p style="font-size: 25px; color: black;">Esta acción no puede ser deshecha. ¿Está seguro de que desea borrar el cliente?</p>

      <div class="clearfix">
        <button type="button" class="btn cancelbtn" onclick="document.getElementById('id01').style.display='none'" style="background-color: #484242;">Cancelar</button>
        <form action="" method="post"> 
            @csrf 
            <input type="text" name="del_client" id="del_client" style="display: none; background-color: red;">
            <button type="submit" class="btn deletebtn">Eliminar</button>
        </form>
      </div>
    </div>
    </div>
</div>

<div class="data_container" style="display: none;" id="data_container">

    <h2>Datos cliente</h2>
    
    <div style="align-self: center;"><p id="client-selected"></p></div>
    
    <div style="align-self: center;" id="data-client"></div>
    
</div>

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')