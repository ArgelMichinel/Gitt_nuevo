<p align="center">
<img src="https://gittgetittoday.com/system/public/images/97543029_280123276722929_1855283596785352704_n.png" alt="Build Status">
</p>

<h1>GITT - Get It Today</h1>

<section id="app-logistica" class="app-logistica">
  <header>
    <h1>Aplicación web para gestión de envíos de una empresa logística</h1>
    <p class="lead">
      Aplicación web desarrollada para una empresa de logística que gestiona la distribución de envíos provenientes de plataformas de comercio electrónico como <strong>Mercado Libre</strong> y <strong>Tienda Nube</strong>.
      El sistema permite <strong>registrar los envíos ingresantes</strong>, <strong>consultar automáticamente la información de cada encomienda a través de las APIs integradas</strong>, y <strong>asignarlos manualmente a los mensajeros responsables de su entrega</strong>.
      Además, la plataforma posibilita el <strong>seguimiento del estado de los envíos</strong> y la <strong>gestión de cobros y pagos</strong> asociados a las entregas, con filtros que facilitan la administración por zonas, fechas y mensajeros.
    </p>
  </header>

  <article class="seccion flujo-envios">
    <h2>Flujo de los envíos</h2>
    <ol>
      <li>
        <strong>Ingreso de los envíos al sistema</strong><br>
        Los envíos se registran mediante la lectura del <em>código QR</em> de la etiqueta o a través del ingreso manual del código de identificación y del vendedor.
      </li>

      <li>
        <strong>Identificación de la plataforma de origen</strong><br>
        El sistema detecta si el envío proviene de una tienda de <strong>Mercado Libre</strong> o <strong>Tienda Nube</strong>, y realiza el llamado a la <em>API correspondiente</em> para obtener:
        <ul>
          <li>Información del objeto transportado.</li>
          <li>Datos del receptor.</li>
          <li>Dirección y coordenadas geográficas de entrega.</li>
          <li>Estado actual de la encomienda.</li>
        </ul>
      </li>

      <li>
        <strong>Verificación y registro en la base de datos</strong><br>
        Antes de guardar la información, el sistema verifica que el envío no haya sido previamente registrado. En caso de no existir, lo almacena como un nuevo registro en la base de datos.
      </li>

      <li>
        <strong>Asignación a mensajeros</strong><br>
        Los <em>administradores</em> pueden:
        <ul>
          <li>Asignar los envíos de forma <strong>individual</strong> a un mensajero.</li>
          <li><strong>Agrupar varios envíos en una lista</strong> para su asignación por lote.</li>
        </ul>
        Cada mensajero puede ingresar al sistema para <em>consultar los envíos que tiene asignados</em> y el <em>estado de cada uno</em>.
      </li>

      <li>
        <strong>Actualización del estado de los envíos</strong><br>
        Mientras el envío no se encuentre en estado “Entregado” o “Cancelado”, el sistema <strong>consulta automáticamente cada 10 minutos</strong> la API correspondiente para actualizar el estado.
        Las actualizaciones se reflejan en la aplicación al <strong>recargar la página</strong>.
      </li>
    </ol>
  </article>

  <aside class="seccion caracteristicas-relevantes">
    <h2>Características relevantes</h2>
    <ul>
      <li><strong>Lectura de códigos QR</strong> para registrar los envíos de forma ágil y precisa.</li>
      <li><strong>Integración con la API de Mercado Libre</strong>: obtiene toda la información de la encomienda a partir de los parámetros contenidos en el código QR.</li>
      <li><strong>Integración con la API de Tienda Nube</strong>: los clientes (tiendas) comparten directamente la información de sus envíos con la aplicación logística.</li>
      <li><strong>Gestión de zonas geográficas</strong>: los envíos se clasifican por áreas (CABA, Cordón 1, Cordón 2).</li>
      <li><strong>Asignación manual de envíos a mensajeros</strong>, de forma individual o por listas.</li>
      <li><strong>Acceso para mensajeros</strong>: permite visualizar los envíos asignados y sus estados.</li>
      <li><strong>Actualización periódica del estado de las encomiendas</strong> mediante consultas automáticas a las APIs.</li>
      <li><strong>Filtros avanzados</strong> por zona, cadete, tienda o fecha.</li>
      <li><strong>Gestión de cobros y pagos</strong> asociados a las entregas realizadas.</li>
    </ul>
  </aside>

