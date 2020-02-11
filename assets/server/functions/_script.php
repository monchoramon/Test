<?php

/**
 * Llenado de datos
 */

include_once '../PDO/pdo.php';

class main{

	public $conexion;

	function __construct(){
		$pdo = new conexion();
		$this->conexion = $pdo->bd();
	}

	public function llenado_estados(){

		$stmt = $this->conexion->prepare("SELECT kcveestado, onombre FROM estados");
		$stmt->execute();

		while ( $row = $stmt->fetch(2) ) {
			$data[] = $row;
		}

			if( @$data ){
				print_r(json_encode( array( 'data' => $data ) ));
			}

	}


}


?>