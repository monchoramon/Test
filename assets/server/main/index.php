
<?php

	include_once '../functions/_script.php';

	$main = new main();

	$opc = @$_POST['opcion'];

	switch ( $opc ) {

		case 1:
			print_r(json_encode( array(100) ));
		break;

		case 2:
			$main->llenado_estados();
		break;

		case 3:
			$main->llenado_municipios( @$_POST['params'] );
		break;

		case 4:
			$main->autocompletado_producto( @$_POST['params'] );
		break;

		case 5:
			$main->llenado_Clave_CostoUnitario( @$_POST['params'] );
		break;

		case 6:
			$main->autocompletado_rfc( @$_POST['params'] );
		break;

		case 7:
			$main->llenado_datos_con_rfc( @$_POST['params'] );
		break;

		case 8:
			$main->gurdar_datos_fiscales( @$_POST['rfc'], 
									      @$_POST['razon_social'],
									      @$_POST['estado'], 
									      @$_POST['municipio'], 
									      @$_POST['direccion'], 
									      @$_POST['colonia'], 
									      @$_POST['codigo_postal'],
									      @$_POST['email']
									       );		
		break;

		case 9:

			$main->guardar_factura( @$_POST['cantidad'], 
								    @$_POST['descuento'], 
								    @$_POST['iva'], 
								    @$_POST['total'], 
								    @$_POST['rfc'], 
								    @$_POST['clave'],
								    @$_POST['forma_pago'],
								    @$_POST['numero_cuenta'],
								    @$_POST['uso_cfdi'],
								    @$_POST['metodo_pago'] );
		break;

		case 10:
			$main->generar_factura( @$_POST['params'] );
		break;

	}

?>