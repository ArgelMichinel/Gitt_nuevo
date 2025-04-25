@extends('layouts.app')

@section('inc_head')
    <link rel="stylesheet" href="{{ asset('Styles/assign_packets.css') }}">
    <script type="text/javascript" language="javascript" src="{{ asset('JS/instascan.min.js') }}"></script>
    <script type="text/javascript" language="javascript" src="{{ asset('JS/assign_packets.js') }}"></script>
@endsection

@section('output')

    <div class="presentacion"  id="presentacion">
        
        <div class="container-fluid" id="principal">
            <div><p>Selecciona la forma de asignación</p></div>
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                    <div class="shared" style="margin: auto">

                        <button class="btn" onclick="select_list()">Asignar por lista</button>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                    <div class="shared" style="margin: auto">

                        <button class="btn" onclick="select_scanner()">Asignar por scanner</button>

                    </div>
                </div>

            </div>
        </div>
        
        <!********************************************************>
        
        <div class="container-fluid" id="screem-lista" style="display: none;">
            <div><p>Asignación por lista</p></div>
            
                <form action="" method="post">
                    @csrf
                
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                            <div class="shared" style="margin: auto">

                                <label for="select_list[list]">Selecciona la lista:</label>
                                <select name="select_list[list]">
                                    @for ($i = 0; $i < count($listas); $i++)
                                        <option value="{{$listas[$i]['id_list']}}">{{$listas[$i]['name']}}</option>
                                    @endfor
                                </select>

                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                            <div class="shared" style="margin: auto">

                                <label for="select_list_inutil">Selecciona un cadete:</label>
                                <input id="input_dummy" list="Cadetexy" name="select_listinutil" required>

                                <datalist id="Cadetexy">
                                    @for ($i = 0; $i < count($cadetes); $i++)
                                        <option value="{{$cadetes[$i]['nombre']}} {{$cadetes[$i]['apellido']}} - {{$cadetes[$i]['num_cadete']}}">
                                    @endfor
                                    
                                </datalist>
                                <input type="text" style="display: none;" id="selec_definitivo" name="select_list[cadete]" required>

                            </div>
                        </div>

                    </div>
                
                    <button id="boton_definitivo" type="submit" style="display: none;"></button>
                    <button class="btn" onclick="pulsar()">Asignar envios</button>
                        
                </form>
            
        </div>
    
    </div>


    <!********************************************************>

    <div class="container-fluid" id="screem-scanner" style="display: none;">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

                <div class="shared" style="margin: auto">

                    <!********************************************************>
                            <h2>Cameras</h2>

                    <div class="preview-container">
                        <video id="preview" width="500" height="500"></video>
                    </div>
                    <!********************************************************>
                    <audio id="audio_scan" src="/../include_QR/bip-scanner.mpeg"></audio>
                    <audio id="trompeta" src="/../include_QR/trompeta.mp3"></audio>

                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-right: 0;">

                <div class="shared Lista" style="margin: auto">

                    <!********************************************************>
                    <div id="myDIV" class="header_2">
                        {{-- 
                        <button  type="button" class="boton_tienda_1" id="Tienda_ML" onclick="cambiar_tienda()">MERCADO LIBRE</button>
                        <button  type="button" class="boton_tienda" id="Tienda_TN" onclick="cambiar_tienda()">TIENDA<br>NUBE</button> --}}

                        <h1 style="margin:5px">Envíos para asignar</h1>
                        <label for="select_list[cadete]">Selecciona un cadete:</label>
                        <input id="cadete_dummy" list="Cadetexyz" name="select_list[cadete]" required>

                            <datalist id="Cadetexyz">
                                <option value="">Debe seleccionar una opción</option>
                                @for ($i=0; $i < count($cadetes); $i++)
                                    <option value="{{$cadetes[$i]['nombre']}} {{$cadetes[$i]['apellido']}} - {{$cadetes[$i]['num_cadete']}}">
                                @endfor
                            </datalist>
                        
                        <p style="display: inline-block;">El numero de envios asignado es:</p><p id="num_pack" style="display: inline-block; padding-left: 5px;"></p>
                    </div>

                    <div class="table-container">
                        <table id="my_ship_in">
                        <tr>
                            <th>Id. Shipping</th>
                        </tr>
                        </table>
                    </div>
                    <!********************************************************>

                    <div id="Envi_manual" class="header_2">
                    <p style="margin: auto; font-size: 30px;">Ingreso manual</p>

                        <table width="15%" align="center">
                            <tr>
                            <td># shipping:</td>
                            <td> <input type="text" id="shipnum" name="shipnum"></td>
                            </tr>
                            <tr>
                            <td colspan="2" align="center"><button class="btn2" onclick="prepare_data_man()">Agregar</button></td>
                            </tr>
                        </table>

                    </div>

                    <form action="" method="post">
                        @csrf
                        <input type="text" id="values_scann" name="select_scanner[values]" style="display: none;">
                        <input type="text" id="cadete_scann" name="select_scanner[cadete]" style="display: none;">
                        <button type="submit" id="submit_scann" style="display: none;">someter scanner</button>
                    </form>

                    <button class="btn" onclick="submit_array()">Asignar envios</button>

                </div>

            </div>

        </div>
    </div> 

@endsection

@section('inc_script_end', '{{-- <!*******************************> --}}')
