
<?php

	include_once '../functions/_script.php';

	$main = new main();

	$opc = $_POST['opcion'];

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
			$main->llenado_clave_cantidad( @$_POST['params'] );
		break;

	}

?>