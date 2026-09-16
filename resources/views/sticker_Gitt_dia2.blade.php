

  <div class="label">

    <!-- REMITENTE -->
    <div class="section remitente">
      <div class="logo"><img src="{{ asset('images/Camion_gitt.jpeg') }}" alt=""></div>
      <div>
        <h4>REMITENTE</h4>
        <p><strong>{{ $info_pack->client_name }}</strong></p>
        <p></p>
        <p></p>
      </div>
    </div>

    <!-- ENVIO PARTICULAR -->
    <div class="envio">ENVÍO PARTICULAR</div>

    <!-- FECHA -->
    <div class="fecha">
      <div>Fecha</div>
      <div>{{ $info_pack->date_in }}</div>
    </div>

    <!-- QR + DESTINO -->
    <div class="qr-section">
      <div class="qr"><img src="data:image/png;base64,{{ $qrBase64 }}"></div>
      <div class="destino">
        {{ $info_pack->zip_code }}
        <span>{{ $info_pack->city }}</span>
        @if ($info_pack->delivery_preference == 1)
          <span>COMERCIAL</span>
        @else
          <span>RESIDENCIAL</span>
        @endif
      </div>
    </div>

    <!-- PAQUETE -->
    <div class="paquete">PAQUETE MANUAL</div>

    <!-- DATOS -->
    <div class="datos">
      <div><strong>Dirección:</strong> {{ $info_pack->street_name }} {{ $info_pack->street_number }}</div>
      <div><strong>Piso:</strong> {{ $info_pack->comment }}</div>
      <div><strong>Descripción:</strong> {{ $info_pack->description }}</div>
      <div><strong>Recibe:</strong> {{ $info_pack->receiver_name }}</div>
      <div><strong>Contacto:</strong> {{ $info_pack->receiver_phone }}</div>
    </div>

  </div>

</body>