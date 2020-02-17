<?php

include_once '../assets/server/functions/_scripts.php';
include_once '../vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

session_start();

//sección A
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
$bold      = "style='font-weight:bold; color:#FFF;'";
$normal    = "style='font-weight: initial;'";
$font_size = "style='font-size:14px;'";

$mpdf->WriteHTML("<img src='https://image.flaticon.com/icons/svg/1486/1486852.svg' width='70' height='70'>");

$mpdf->WriteHTML("
		<table border='1' style='border-collapse: collapse; margin-bottom:3px; margin-left:80%; text-align:center;' width='120%'>
				<tbody>
					<tr style='background-color:#142850;'>
						<td $bold>FACTURA NO.</td>
					</tr>
					<tr>
						<td>$folio</td>
					</tr>
					<tr style='background-color:#142850;'>
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
						<th width='60%' style='background-color:#010a43; color:#FFF;'>FACTURADO A:</th>
						<th style='background-color:#010a43; color:#FFF;'>INFORMACIÓN GENERAL:</th>
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
		        <td style='padding:5px;'>$oclave</td>
		        <td style='padding:5px;'>$onombre</td>
		        <td style='padding:5px;'>$cantidad</td>
		        <td style='padding:5px;'>$ocostounitario</td>
		        <td style='padding:5px;'>$descuento</td>
		        <td style='padding:5px;'>$iva</td>
		        <td style='padding:5px;'>$total</td>
	          </tr>";

	$importe += ($cantidad * $ocostounitario);
	$_descuento += $descuento;
	$_iva += $iva;	

}	

$subtotal = ($importe - $_descuento);
$total = ($subtotal + $_iva);

$mpdf->WriteHTML("<table border='1' style='border-collapse: collapse; margin-top:25px;' width='100%'>
                <thead>
                    <tr>
                        <th style='padding:20px; background-color:#142850; color:#FFF;'>Clave</th>
                        <th style='background-color:#142850; color:#FFF;'>Producto</th>
                        <th style='background-color:#142850; color:#FFF;'>Cantidad</th>
                        <th style='background-color:#142850; color:#FFF;'>Costo Unitario</th>
                        <th style='background-color:#142850; color:#FFF;'>Descuento</th>
                        <th style='background-color:#142850; color:#FFF;'>IVA</th>
                        <th style='background-color:#142850; color:#FFF;'>Total</th>
                    </tr>
                </thead>
                <tbody>
					@$data
                </tbody>
            </table>");


$mpdf->WriteHTML("
			<table border='1' style='border-collapse: collapse; margin-left:50%; margin-top:5%' width='100%'>
				<tbody>
					<tr>

						<td style='padding:10px; background-color:#142850; color:#FFF;'>
							<h4>Importe:</h4>
							<h4>Descuento:</h4>
							<h4>Subtotal:</h4>
							<h4>IVA:</h4>
							<h4>Total:</h4>
						</td>

						<td style='padding:10px;'>
							<h4>$importe</h4>
							<h4>$_descuento</h4>
							<h4>$subtotal</h4>
							<h4>$$_iva</h4>
							<h4>$total</h4>
						</td>

					</tr>
				</tbody>
			</table>
	");

$mpdf->Output();