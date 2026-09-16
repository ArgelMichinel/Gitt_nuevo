@props(['packets', 'clients'])

<div>
    <table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th># envio</th>
                <th>fec. ingreso</th>
                <th>status</th>
                <th># venta</th>
                <th>calle</th>
                <th>comentario</th>
                <th>Cod. postal</th>
                <th>ciudad</th>
                <th style="display: none;">delivery_preference</th>
                <th>prov.</th>
                <th>país</th>
                <th>nom. recep.</th>
                <th>fec. 1ra visit</th>
                <th>fec. entreg.</th>
                <th>fec. no entr.</th>
                <th>Status logis.</th>
                <th>coment. logist.</th>
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
                            ($key != 'time_cad1')  && ($key != 'time_cad2')  && ($key != 'time_cad3') && ($key != 'description') && ($key != 'admin_ingre') &&
                            ($key != 'TN') && ($key != 'admin_status'))
                            <td class='{{ $key }}'> {{ $value }}</td>
                        @endif

                        @if ($key === 'id_ship')
                            <td class="id_ship"> {{ $value }} </td>
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
                                        <td style="background-color: blue; color: white; text-align: center;"> Asignado</td>
                                    @else
                                        <td  style="background-color: yellow; color: white; text-align: center;"> 1era visita</td>
                                    @endif
                                    @break
                                
                                @case('Pendiente')
                                    <td style="background-color: blue; color: white; text-align: center;"> Pendiente</td>
                                    @break
                            
                                @default
                                    <td style="background-color: white; color: black; text-align: center;"> {{ $value }}</td>
                            @endswitch  
                        @endif

                        @if ($key === 'delivery_preference')
                            <td style="display: none;"> {{ $value }}</td>
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
                <th># envio</th>
                <th>fec. ingreso</th>
                <th>status</th>
                <th># venta</th>
                <th>calle</th>
                <th>comentario</th>
                <th>Cod. postal</th>
                <th>ciudad</th>
                <th style="display: none;">delivery_preference</th>
                <th>prov.</th>
                <th>país</th>
                <th>nom. recep.</th>
                <th>fec. 1ra visit</th>
                <th>fec. entreg.</th>
                <th>fec. no entr.</th>
                <th>Status logis.</th>
                <th>coment. logist.</th>
            </tr>
        </tfoot>
    </table>
</div>