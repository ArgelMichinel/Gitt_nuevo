<p align="center">
<img src="https://gittgetittoday.com/system/public/images/97543029_280123276722929_1855283596785352704_n.png" alt="Build Status">
</p>

<h1>GITT - Get It Today</h1>

Aplicación web desarrollada para una empresa de logística que permite el registro de los envíos ingresantes y su asignación a los mensajeros que entregan. Contiene la integración con la API de Mercadolibre y la API de Tienda Nube. 
A continuación, se presenta un desglose de las caracaterísticas:

<li>Posee logins separados para el ingreso de administradores y tiendas.</li>
<li>Permite leer el código QR de los envíos para extraer la identificación del mismo.</li>
<li>Si el envío es registrado por una tienda de Mercadolibre utiliza los parámetros de identificación contenidos en el código QR para solicitarle a la API de mercadolibre toda la información asociada a la encomienda.</li>
<li>Si el envío es registrado por una tienda de Tienda Nube los clientes directamente, desde la administración de su tienda, comparten la información de los envíos (con la aplicación de la logística) a través de la integración de su API.</li>
<li>Separa los envíos por distintas zonas geográficas (CABA, Cordon 1, Cordón 2)</li>
<li>Permite la asignación de los envío a los cadetes para administrar el pago y el cobro a las tiendas.</li>
<li>Filtra por zonas, cadetes, tiendas y Fecha.</li>
