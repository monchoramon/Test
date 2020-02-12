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

		while ( $row = $stmt->fetch(PDO::FETCH_NUM) ) {
			$data[] = $row;
		}

			if( @$data ){
				print_r(json_encode( array( 'data' => $data ) ));
			}

	}

	public function llenado_municipios( $params ){

		if( @$params ){
			$sql = "SELECT kcvemunicipio, onombre FROM municipio WHERE kcveestado = $params";
		}else{
			$sql = "SELECT kcvemunicipio, onombre FROM municipio";
		}

			$stmt = $this->conexion->prepare( $sql );
			$stmt->execute();

			while ( $row = $stmt->fetch(PDO::FETCH_NUM) ) {
				$data[] = $row;
			}

				if( @$data ){
					print_r(json_encode( array( 'data' => $data ) ));
				}

	}

	public function autocompletado_producto( $nombre ){

		$stmt = $this->conexion->prepare("SELECT onombre FROM producto WHERE onombre LIKE '%$nombre%'");

		$stmt->execute();

		while ( $row = $stmt->fetch(2) ) {
			$data[] = $row;
		}

			if( @$data ){
				print_r(json_encode( array( 'data' => $data ) ));
			}else{
				print_r(json_encode( array( 'data' => null ) ));
			}

	}

	public function llenado_clave_cantidad( $params ){


		$stmt = $this->conexion->prepare("SELECT oclave, ocostounitario FROM producto WHERE onombre = '$params'");

			$stmt->execute();

			while ( $row = $stmt->fetch(2) ) {
				$data[] = $row;
			}

			if( @$data ){
				print_r(json_encode( array( 'data' => $data ) ));
			}else{
				print_r(json_encode( array( 'data' => null ) ));
			}

	}


}


?>