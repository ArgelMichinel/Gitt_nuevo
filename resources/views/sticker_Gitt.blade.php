<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Etiqueta de Envío</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <style>
    * {
      box-sizing: border-box;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      background: #f4f4f4;
      padding: 20px;
    }

    .label {
      width: 380px;
      background: #fff;
      border: 2px solid #000;
      margin: auto;
    }

    .section {
      border-bottom: 2px solid #000;
      padding: 8px 10px;
    }

    .row {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .remitente {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .logo {
      width: 60px;
      height: 40px;
      border: 2px solid #000;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 12px;
    }

    .logo img {
      max-width: 100%;
      max-height: 100%;
    }

    .remitente h4 {
      margin: 0;
      font-size: 12px;
      font-weight: bold;
    }

    .remitente p {
      margin: 2px 0;
      font-size: 11px;
    }

    .envio {
      background: #000;
      color: #fff;
      text-align: center;
      font-weight: bold;
      letter-spacing: 3px;
      padding: 6px;
      font-size: 14px;
    }

    .fecha {
      display: flex;
      border-bottom: 2px solid #000;
    }

    .fecha div {
      flex: 1;
      border-right: 2px solid #000;
      padding: 6px;
      font-weight: bold;
      text-align: center;
    }

    .fecha div:last-child {
      border-right: none;
    }

    .qr-section {
      display: flex;
      gap: 10px;
      padding: 10px;
      border-bottom: 2px solid white;
    }

    .qr {
      width: 120px;
      height: 120px;
      border: 2px solid white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
    }

    .destino {
      flex: 1;
      text-align: center;
      font-weight: bold;
      font-size: 14px;
    }

    .destino span {
      display: block;
      font-size: 12px;
      font-weight: normal;
      margin-top: 4px;
    }

    .paquete {
      text-align: center;
      font-weight: bold;
      padding: 6px;
      border-bottom: 2px solid #000;
    }

    .datos {
      padding: 10px;
      font-size: 13px;
      line-height: 1.4;
    }

    .datos strong {
      font-weight: bold;
    }

    @media print {
      body {
        background: none;
        padding: 0;
      }
    }
  </style>
</head>

<body>

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
</html>