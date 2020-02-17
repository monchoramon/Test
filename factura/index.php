<?php

include_once '../assets/server/functions/_scripts.php';
include_once '../vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

session_start();

$rfc = $_SESSION["seccion_a"][0]['orfc'];
$razon_social = $_SESSION["seccion_a"][0]['orazonsocial'];
$estado_nombre = $_SESSION["seccion_a"][0]['estado_nombre'];
$municipio_nombre = $_SESSION["seccion_a"][0]['municipio_nombre'];

//sección B
$folio = $_SESSION["seccion_b"][0]['folio'];
$fecha = $_SESSION["seccion_b"][0]['fecha_expedicion'];
$forma_pago = $_SESSION["seccion_b"][0]['forma_pago'];
$metodo_pago = $_SESSION["seccion_b"][0]['metodo_pago'];
$cfdi = $_SESSION["seccion_b"][0]['cfdi'];
$moneda = $_SESSION["seccion_b"][0]['moneda'];

/////////////////////////////////////////////////////////
$bold      = "style='font-weight:bold;'";
$normal    = "style='font-weight: initial;'";
$font_size = "style='font-size:14px;'";

$mpdf->WriteHTML("
		<table border='1' style='border-collapse: collapse; margin-bottom:3px; margin-left:80%; text-align:center;' width='120%'>
				<tbody>
					<tr>
						<td $bold>FACTURA NO.</td>
					</tr>
					<tr>
						<td>$folio</td>
					</tr>
					<tr>
						<td $bold>Fecha:</td>
					</tr>
					<tr>
						<td>$fecha</td>
					</tr>
				</tbody>
			</table>
	");

$mpdf->WriteHTML("
			<table border='1' style='border-collapse: collapse' width='100%'>
				<thead>
					<tr>
						<th width='60%'>FACTURADO A:</th>
						<th>INFORMACIÓN GENERAL:</th>
					</tr>
				</thead>
				<tbody>
					<tr>

						<td>
							<h5>RAZÓN SOCIAL:</h5>
							<div $font_size>$razon_social</div>
							<h5>RFC:</h5>
							<div $font_size>$rfc</div>
							<h5>Estado:</h5>
							<div $font_size>$estado_nombre</div>
							<h5>Municipio:</h5>
							<div $font_size>$municipio_nombre</div>
						</td>

						<td>
							<h5>Forma de pago:</h5>
							<div $font_size>$forma_pago</div>
							<h5>Método de pago:</h5>
							<div $font_size>$metodo_pago</div>
							<h5>Uso de CFDI:</h5>
							<div $font_size>$cfdi</div>
							<h5>Moneda:</h5>
							<div $font_size>$moneda</div>
						</td>

					</tr>
				</tbody>
			</table>
	");

//sección C

for($x = 0; $x < sizeof( $_SESSION['seccion_c'] ); $x++ ){	

	$session_c = $_SESSION['seccion_c'][$x];

	$oclave         = $session_c['oclave'];
	$onombre        = $session_c['onombre'];
	$cantidad       = $session_c['cantidad'];
	$ocostounitario = $session_c['ocostounitario'];
	$descuento      = $session_c['descuento'];
	$iva            = $session_c['iva'];
	$total          = $session_c['total'];

	@$data .=  "<tr>
		        <td>$oclave</td>
		        <td>$onombre</td>
		        <td>$cantidad</td>
		        <td>$ocostounitario</td>
		        <td>$descuento</td>
		        <td>$iva</td>
		        <td>$total</td>
	          </tr>";

}	

$mpdf->WriteHTML("<table border='1' style='border-collapse: collapse; margin-top:25px;' width='100%'>
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Costo Unitario</th>
                        <th>Descuento</th>
                        <th>IVA</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
					@$data
                </tbody>
            </table>");

$mpdf->Output();