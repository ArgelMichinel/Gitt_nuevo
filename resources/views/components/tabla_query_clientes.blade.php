@props(['packets', 'clients', 'cadetes', 'admin'])

<div>
    <table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th># envio</th>
                <th>fec. ingreso</th>
                <th>status</th>
                <th># orden</th>
                <th>calle</th>
                <th>comentario</th>
                <th>Cod. postal</th>
                <th>ciudad</th>
                <th>prov.</th>
                <th>país</th>
                <th>nom. recep.</th>
                <th>fec. 1ra visit</th>
                <th>fec. entreg.</th>
                <th>fec. no entr.</th>
            </tr>
        </thead>
        <tbody  id="data_table">

            @foreach ($packets as $pack)
                <tr>

                    @foreach ($pack as $key => $value )

                        @if (($key != 'id_num') && ($key != 'status') && ($key != 'sender_id') && ($key != 'city' ) && ($key != 'delivery_preference') && 
                            ($key != 'street_name') && ($key != 'street_number') && ($key != 'receiver_phone') && ($key != 'cadete1') && ($key != 'cadete2') && 
                            ($key != 'cadete3') && ($key != 'sticker') && ($key != 'id_ship') && ($key != 'country')  && ($key != 'status_logistica')  && 
                            ($key != 'Latit')  && ($key != 'Longi')  && ($key != 'admin_cad1')  && ($key != 'admin_cad2')  && ($key != 'admin_cad3')  &&
                            ($key != 'time_cad1')  && ($key != 'time_cad2')  && ($key != 'time_cad3') && ($key !='description') && ($key !='comment_logis')  &&
                            ($key != 'order_id') && ($key != 'TN') && ($key != 'admin_ingre') && ($key != 'admin_status')) 
                            <td class='{{ $key }}'> {{ $value }}</td>
                        @endif

                        @if ($key === 'id_ship')
                            <td class="id_ship">{{ $value }}</td>
                        @endif

                        @if ($key === 'order_id')
                            @if ($pack['TN'] === 1)
                                <td class="order_id"> {{ $value }} <i class="fa fa-id-card-o" aria-hidden="true"></i>

                                    @if ($key === 'order_id')
                                        @if (substr($pack['id_ship'],0,1) === "G")   {{-- Si el envío es de Gitt --}}
                                            <a href="{{ route('incluirEnvioGittCli', ['id' => $pack['id_ship']]) }}"><i class="fa fa-edit"></i></a>
                                        @else
                                            // Si el envío no es de Gitt, no se muestra el ícono de edición
                                        @endif
                                    @endif
                                    
                                </td>
                            @else
                                <td class="order_id"> {{ $value }}</td>
                            @endif
                        @endif

                        @if ($key == 'status')
                            @switch($value)
                                @case('ready_to_ship')
                                    <td style="background-color: blue; color: white; text-align: center;"> En curso</td>
                                    @break
                            
                                @case('shipped')
                                    @if ($pack['date_first_visit'] === NULL)
                                        <td style="background-color: blue; color: white; text-align: center;"> En curso
                                    @else
                                        <td  style="background-color: yellow; color: white; text-align: center;"> 1era visita
                                    @endif
                                    @break
                            
                                @case('delivered')
                                    <td style="background-color: green; color: white; text-align: center;"> Completado
                                    @break
                        
                                @case('not_delivered')
                                    <td style="background-color: red; color: white; text-align: center;"> No completado
                                    @break
                        
                                @case('cancelled')
                                    <td style="background-color: red; color: white; text-align: center;"> Cancelado
                                    @break

                                @case('cadete_asignado')
                                    <td style="background-color: blue; color: white; text-align: center;"> En curso
                                    @break

                                @case('Pendiente')
                                    <td style="background-color: blue; color: white; text-align: center;"> Pendiente</td>
                                    @break

                                @case('Asignado')
                                    <td style="background-color: rgb(97, 97, 100); color: white; text-align: center;"> Asignado
                                    @break
                            
                                @default
                                    <td style="background-color: white; color: black; text-align: center;"> {{ $value }}
                            @endswitch  

                            @if (($pack['status'] != 'delivered') & ($pack['status'] != 'cancelled'))
                                <br><button class="btn2" onclick="cancel_ship(this)" style="padding: 5px 20px;">Cancel</button>
                            @endif

                            </td>

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

                    @endforeach

                </tr>

            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <th># envio</th>
                <th>fec. ingreso</th>
                <th>status</th>
                <th># orden</th>
                <th>calle</th>
                <th>comentario</th>
                <th>Cod. postal</th>
                <th>ciudad</th>
                <th>prov.</th>
                <th>país</th>
                <th>nom. recep.</th>
                <th>fec. 1ra visit</th>
                <th>fec. entreg.</th>
                <th>fec. no entr.</th>
            </tr>
        </tfoot>
    </table>
</div>

<div id="id01" class="modal">
  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" action="{{ route('cancel_gitt') }}" method="post">
    @csrf
    <div class="container">
        <input type="text" id="id01_id_ship" name="id_ship" value="" style="display: none">
      <h1>Cancelar Envío</h1>
      <p>¿Estás seguro que quieres canacelar el envío?</p>

      <div class="clearfix">
        <button type="button" class="cancelbtn" onclick="document.getElementById('id01').style.display='none'">Salir</button>
        <button type="submit" class="deletebtn">Cancelar envío</button>
      </div>
    </div>
  </form>
</div>