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
                <th>cadete</th>
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

                        @if (($key != 'id_num') && ($key != 'status') && ($key != 'sender_id') && ($key != 'city' ) && ($key != 'delivery_preference') && ($key != 'street_name') && ($key != 'street_number') && ($key != 'receiver_phone') && ($key != 'cadete') && ($key != 'sticker') && ($key != 'id_ship') && ($key != 'country')) 
                            <td class='{{ $key }}'> {{ $value }}</td>
                        @endif

                        @if ($key === 'id_ship')
                            <td class="id_ship"> {{ $value }} <i class="fa fa-qrcode" aria-hidden="true"></i></td>
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
                            
                                @default
                                    <td style="background-color: white; color: black; text-align: center;"> {{ $value }}</td>
                            @endswitch  
                        @endif

                        @if ($key === 'sender_id')
                            <td style="display: none;"> {{ $value }}</td>
                            @foreach ($clients as $cl => $variab)
                                @if ($variab['user_id'] === $value)
                                    <td> {{ $variab['Nombre'] }}</td>
                                    @break
                                @endif
                                
                            @endforeach
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

                        @if ($key === 'cadete')
                            @foreach ($cadetes as $ct => $variab)
                                @if ($variab['num_cadete'] == $value)
                                    <td> {{ $variab['nombre'] }} {{ $variab['apellido'] }}</td>
                                    @break
                                @endif
                            @endforeach
                            
                        @endif

                        @if ($key == 'country')
                            @switch($type)
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
                            @switch($type)
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
                <th class="cadete">cadete</th>
                <th class="status_logistica">Status logis.</th>
                <th class="comment_logis">coment. logist.</th>
                <th style="display: none;">sticker</th>
            </tr>
        </tfoot>
    </table>
</div>