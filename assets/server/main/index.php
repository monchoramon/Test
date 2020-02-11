
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

	}

?>