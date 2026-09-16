@props(['packets', 'clients', 'cadetes', 'admin', 'credencial'])

<div>
    <table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Select</th>
                <th># envio</th>
                <th>fec. ingreso</th>
                <th>status</th>
                <th style="display: none;"># cliente</th>
                <th>nom. cliente</th>
                <th># venta</th>
                <th>calle</th>
                <th>comentario</th>
                <th>Cod. postal</th>
                <th>ciudad</th>
                <th style="display: none;">delivery_preference</th>
                <th>prov.</th>
                <th>país</th>
                <th>nom. recep.</th>
                <th>descripción</th>
                <th>fec. 1ra visit</th>
                <th>fec. entreg.</th>
                <th>fec. no entr.</th>
                <th>admin. ingreso</th>
                <th>cadete1</th>
                <th style="display: none;">admin_cad1</th>
                <th style="display: none;">fec. asig1</th>
                <th style="display: none;">cadete-2</th>
                <th style="display: none;">admin_cad2</th>
                <th style="display: none;">fec. asig2</th>
                <th style="display: none;">cadete-3</th>
                <th style="display: none;">admin_cad3</th>
                <th style="display: none;">fec. asig3</th>
                <th style="display: none;">admin_status</th>
                <th>Status logis.</th>
                <th>coment. logist.</th>
                <th style="display: none;">sticker</th>
            </tr>
        </thead>
        <tbody  id="data_table">

            @foreach ($packets as $pack)
                <tr>
                    <td><input type="checkbox" class="Checkbox"></td>

                    @foreach ($pack as $key => $value )

                        @if (($key != 'id_num') && ($key != 'status') && ($key != 'sender_id') && ($key != 'city' ) && ($key != 'delivery_preference') && 
                            ($key != 'street_name') && ($key != 'street_number') && ($key != 'receiver_phone') && ($key != 'cadete1') && ($key != 'cadete2') && 
                            ($key != 'cadete3') && ($key != 'sticker') && ($key != 'id_ship') && ($key != 'country')  && ($key != 'status_logistica')  && 
                            ($key != 'Latit')  && ($key != 'Longi')  && ($key != 'admin_cad1')  && ($key != 'admin_cad2')  && ($key != 'admin_cad3')  &&
                            ($key != 'time_cad1')  && ($key != 'time_cad2')  && ($key != 'time_cad3') && ($key != 'admin_ingre') && ($key != 'TN') && 
                            ($key != 'order_id') && ($key != 'date_in') && ($key != 'admin_status'))
                            <td class='{{ $key }}'> {{ $value }}</td>
                        @endif

                        @if ($key === 'id_ship')
                            <td class="id_ship"> {{ $value }} <i class="fa fa-qrcode" aria-hidden="true"></i> 
                                @if(Request::is('admin/logged_packets'))
                                    {{-- El contenido aquí se mostrará si la URL comienza con 'admin/' --}}
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                @endif
                            </td>
                        @endif

                        @if ($key === 'date_in')
                            @if ($credencial === 1)
                                <td class='{{ $key }}'> {{ $value }} <button class="btn2" onclick="delete_ship(this)" style="padding: 5px 20px;">Borrar</button></td>
                            @else
                                <td class='{{ $key }}'> {{ $value }}</td>
                            @endif
                        @endif

                        @if ($key == 'status')
                            @switch($value)
                                @case('ready_to_ship')
                                    <td style="background-color: blue; color: white; text-align: center;"> En curso</td>
                                    @break
                            
                                @case('shipped')
                                    @if ($pack['date_first_visit'] === NULL)
                                        <td style="background-color: blue; color: white; text-align: center;"> En curso</td>
                                    @else
                                        <td  style="background-color: yellow; color: white; text-align: center;"> 1era visita</td>
                                    @endif
                                    @break
                            
                                @case('delivered')
                                    <td style="background-color: green; color: white; text-align: center;"> Completado</td>
                                    @break
                        
                                @case('not_delivered')
                                    <td style="background-color: red; color: white; text-align: center;"> No completado</td>
                                    @break
                        
                                @case('cancelled')
                                    <td style="background-color: red; color: white; text-align: center;"> Cancelado</td>
                                    @break
                            
                                @case('cadete_asignado')
                                    @if ($pack['date_first_visit'] === NULL)
                                        <td style="background-color: blue; color: white; text-align: center;"> Asignado<br><button class="btn" onclick="update_TN(this,'first_visit')" style="width: 45%;">1era visit.</button><button class="btn" onclick="update_TN(this,'delivered')" style="width: 45%;">Compl.</button></td>
                                    @else
                                        <td  style="background-color: yellow; color: white; text-align: center;"> 1era visita<br><button class="btn" onclick="update_TN(this,'first_visit')" style="width: 45%;">1era visit.</button><button class="btn" onclick="update_TN(this,'delivered')" style="width: 45%;">Compl.</button></td>
                                    @endif
                                    @break

                                @case('Pendiente')
                                    <td style="background-color: blue; color: white; text-align: center;"> Pendiente</td>
                                    @break

                                @case('Asignado')
                                    <td style="background-color: rgb(97, 97, 100); color: white; text-align: center;"> Asignado</td>
                                    @break

                                @default
                                    <td style="background-color: white; color: black; text-align: center;"> {{ $value }}</td>
                            @endswitch  
                        @endif

                        @if ($key === 'sender_id')          {{-- Selección de la tabla donde se buscará el cliente sender del paquete --}}
                            <td style="display: none;"> {{ $value }}</td>

                            @php
                                            $miBandera = true;
                            @endphp

                            
                            @if (substr($pack['id_ship'],0,1) === "G")   {{-- Si el envío es de Gitt --}}

                                @foreach ($clients as $cl => $variab)
                                    @if ($variab['id_Gitt'] === $value)
                                        {{-- <td> {{ $variab['name'] }}</td> --}}
                                        <td> {{ $variab['name'] }}<i class="fa fa-id-card-o" aria-hidden="true"></i>
                                            @if (($pack['status'] != 'delivered') & ($pack['status'] != 'cancelled'))
                                                <br><button class="btn2" onclick="update_TN(this,'cancelled')">Cancel</button>
                                            @endif
                                        </td>
                                        @php
                                            $miBandera = false;
                                        @endphp
                                        @break
                                    @endif
                                @endforeach

                                
                                @php  // Significa que no se halló el cliente en la tabla de clientes
                                    if (isset($miBandera) && $miBandera) {
                                        echo '<td>'. $value .'</td>';
                                    }
                                @endphp
                                
                            @else 
    
                                @foreach ($clients as $cl => $variab)
                                    @if ($variab['id_MELI'] === $value)   {{-- Si el envío es de ML --}}
                                        <td> {{ $variab['name'] }}</td>
                                        @php
                                            $miBandera = false;
                                        @endphp
                                        @break
                                    @endif

                                    @if ($variab['id_TN'] === $value)     {{-- Si el envío es de TN --}}
                                        <td> {{ $variab['name'] }}<i class="fa fa-id-card-o" aria-hidden="true"></i></td>
                                        @php
                                            $miBandera = false;
                                        @endphp
                                        @break
                                    @endif


                                @endforeach
                                
                                @php  // Significa que no se halló el cliente en la tabla de clientes
                                    if (isset($miBandera) && $miBandera) {
                                        echo '<td>'. $value .'</td>';
                                    }
                                @endphp

                            @endif

                        @endif

                        @if ($key === 'sticker')
                            @if (is_null($value))
                                <td style="display: none;">(vacio)</td>
                            @else
                                <td style="display: none;"> {{ $value }}</td>
                            @endif
                            
                        @endif

                        @if ($key === 'delivery_preference')
                            <td style="display: none;"> {{ $value }}</td>
                        @endif

                        @if ($key === 'order_id')
                            @if (substr($pack['id_ship'],0,1) === "G")   {{-- Si el envío es de Gitt --}}
                                <td class="order:id"> {{ $value }} <a href="{{ route('incluirEnvioGitt', ['id' => $pack['id_ship']]) }}"><i class="fa fa-edit"></i></a></td>
                            @else
                                <td class="order:id"> {{ $value }}</td>
                            @endif
                        @endif
                        
                        @if ($key === 'city')
                            @if ($pack['delivery_preference'] == 1 )
                                <td class="city" style="background-color: magenta; color: white;"> {{ $value }}</td>
                            @else
                                <td class="city"> {{ $value }}</td>
                            @endif
                        @endif

                        @if ($key == 'street_name')
                            <td>{{ $value }} {{ $pack['street_number'] }}</td>
                        @endif

                        @if (($key === 'cadete1') || ($key === 'cadete2') || ($key === 'cadete3'))
                            @foreach ($cadetes as $ct => $variab)
                                @if ($variab['num_cadete'] == $value)
                                    @if ($key === 'cadete1')
                                        <td> {{ $variab['nombre'] }} {{ $variab['apellido'] }}</td>
                                        @break
                                    @else
                                        <td style="display: none;"> {{ $variab['nombre'] }} {{ $variab['apellido'] }}</td>
                                        @break
                                    @endif
                                @endif
                                @if ($variab['num_cadete'] == $cadetes[count($cadetes)-1]['num_cadete'])   {{-- Sólo se usa si no se consigue el cadete --}}
                                    @if ($key === 'cadete1')
                                        <td> {{ $value }}</td>
                                        @break
                                    @else
                                        <td style="display: none;"> {{ $value }}</td>
                                        @break
                                    @endif
                                @endif
                            @endforeach
  
                        @endif

                        @if (($key === 'admin_cad1') || ($key === 'admin_cad2') || ($key === 'admin_cad3'))
                            @foreach ($admin as $id => $variab)
                                @if ($variab['id'] == $value)
                                    <td style="display: none;"> {{ $variab['name'] }}</td>
                                    @break
                                @endif
                                @if ($variab['id'] == $admin[count($admin)-1]['id']) 
                                    <td style="display: none;"> {{ $value }}</td> {{-- Sólo se usa si no se consigue el administrador --}}
                                @endif
                            @endforeach
                            
                        @endif

                        @if ($key === 'admin_ingre')
                            @foreach ($admin as $id => $variab)
                                @if ($variab['id'] == $value)
                                    <td> {{ $variab['name'] }}</td>
                                    @break
                                @endif
                                @if ($variab['id'] == $admin[count($admin)-1]['id']) 
                                    <td> {{ $value }}</td> {{-- Sólo se usa si no se consigue el administrador --}}
                                @endif
                            @endforeach
                            
                        @endif

                        @if ($key === 'admin_status')
                            @foreach ($admin as $id => $variab)
                                @if ($variab['id'] == $value)
                                    <td style="display: none;"> {{ $variab['name'] }}</td>
                                    @break
                                @endif
                                @if ($variab['id'] == $admin[count($admin)-1]['id']) 
                                    <td style="display: none;"> {{ $value == "" ? "-" : $value }}</td> {{-- Sólo se usa si no se consigue el administrador --}}
                                @endif
                            @endforeach
                            
                        @endif

                        @if (($key === 'time_cad1') || ($key === 'time_cad2') || ($key === 'time_cad3'))
                            <td style="display: none;"> {{ $value }}</td>     
                        @endif

                        @if ($key == 'country')
                            @switch($value)
                                @case(1)
                                    <td class="country"> Argentina</td>
                                    @break
                                @case(2)
                                    <td class="country"> Brasil</td>
                                    @break
                                @case(3)
                                    <td class="country"> Chile</td>
                                    @break
                                @case(4)
                                    <td class="country"> Perú</td>
                                    @break
                                @case(5)
                                    <td class="country"> Venezuela</td>
                                    @break
                                @default
                                    <td class="country"> Argentina</td>
                            @endswitch
                        @endif

                        @if ($key == 'status_logistica')
                            @switch($value)
                                @case(0)
                                    <td class="status_logistica"> Ingresado</td>
                                    @break
                                @case(1)
                                    <td class="status_logistica"> Entregado</td>
                                    @break
                                @case(2)
                                    <td class="status_logistica"> 1era visita</td>
                                    @break
                                @case(3)
                                    <td class="status_logistica"> 2da visita</td>
                                    @break
                                @case(4)
                                    <td class="status_logistica"> Devuelto logística</td>
                                    @break
                                @case(5)
                                    <td class="status_logistica"> Devuelto MELI</td>
                                    @break
                                @case(6)
                                    <td class="status_logistica"> Entregado y cobrado</td>
                                    @break
                                @default
                                    <td class="status_logistica"> Ingresado</td>
                            @endswitch
                        @endif

                    @endforeach

                </tr>

            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th>Select</th>
                <th class="id_ship"># envio</th>
                <th class="date_in">fec. ingreso</th>
                <th class="status">status</th>
                <th style="display: none;"># cliente</th>
                <th class="sender_id">nom. cliente</th>
                <th class="order:id"># venta</th>
                <th class="street_name">calle</th>
                <th class="comment">comentario</th>
                <th class="zip_code">Cod. postal</th>
                <th class="city">ciudad</th>
                <th style="display: none;">delivery_preference</th>
                <th class="state">prov.</th>
                <th class="country">país</th>
                <th class="receiver_name">nom. recep.</th>
                <th class="description">descripción</th>
                <th class="date_first_visit">fec. 1ra visit</th>
                <th class="date_delivered">fec. entreg.</th>
                <th class="date_not_delivered">fec. no entr.</th>
                <th class="admin_ingre">admin. ingreso</th>
                <th class="cadete1">cadete-1</th>
                <th style="display: none;">admin_cad1</th>
                <th style="display: none;">fec. asig1</th>
                <th style="display: none;">cadete-2</th>
                <th style="display: none;">admin_cad2</th>
                <th style="display: none;">fec. asig2</th>
                <th style="display: none;">cadete-3</th>
                <th style="display: none;">admin_cad3</th>
                <th style="display: none;">fec. asig3</th>
                <th style="display: none;">admin_status</th>
                <th class="status_logistica">Status logis.</th>
                <th class="comment_logis">coment. logist.</th>
                <th style="display: none;">sticker</th>
            </tr>
        </tfoot>
    </table>
</div>


<div id="id01" class="modal">
  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" action="{{ route('borrar') }}" method="post">
    @csrf
    <div class="container">
        <input type="text" id="id01_id_ship" name="id_ship" value="" style="display: none">
      <h1>Cancelar Envío</h1>
      <p>¿Estás seguro que quieres Borrar el envío?</p>

      <div class="clearfix">
        <button type="button" class="cancelbtn" onclick="document.getElementById('id01').style.display='none'">Salir</button>
        <button type="submit" class="deletebtn">Borrar envío</button>
      </div>
    </div>
  </form>
</div>