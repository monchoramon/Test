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

		while ( $row = $stmt->fetch(PDO::FETCH_NUM) ) {
			$data[] = $row;
		}

			if( @$data ){
				print_r(json_encode( array( 'data' => $data ) ));
			}else{
				print_r(json_encode( array( 'data' => null ) ));
			}

	}

	public function llenado_Clave_CostoUnitario( $params ){


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

	public function autocompletado_rfc( $rfc ){

		$stmt = $this->conexion->prepare("SELECT orfc FROM datosfiscales WHERE orfc LIKE '%$rfc%'");

		$stmt->execute();

		while ( $row = $stmt->fetch(PDO::FETCH_NUM) ) {
			$data[] = $row;
		}

			if( @$data ){
				print_r(json_encode( array( 'data' => $data ) ));
			}else{
				print_r(json_encode( array( 'data' => null ) ));
			}


	}

	public function llenado_datos_con_rfc( $rfc ){

		$stmt = $this->conexion->prepare("SELECT 
											orazonsocial,
											oemail,
											rcveestado, 
											rcvemunicipio,
											odireccion,
											ocolonia,
											ocodigopostal
											FROM datosfiscales WHERE orfc = '$rfc'");

			$stmt->execute();

			while ( $row = $stmt->fetch(2) ) {
				$data[] = $row;
			}

			if( @$data ){
				print_r(json_encode( $data ));
			}else{
				print_r(json_encode( array( 'data' => null ) ));
			}

	}

	public function gurdar_datos_fiscales($rfc, $razon_social, $email, $estado, $municipio, $direccion, $colonia, $codigo_postal){

		$validar_datos = array( $rfc, $razon_social, $email, $estado, $municipio, $direccion, $colonia, $codigo_postal );

		if( main::validar_datos( $validar_datos ) > 0 ){
			print_r(json_encode( array('info' => 'Todos los campos son necesarios') ));
		}else{

			if( main::verificar_existencia( $rfc ) ){

				$stmt = $this->conexion->prepare('UPDATE datosfiscales SET 
												  orfc = :rfc_, 
												  orazonsocial = :orazonsocial, 
												  rcveestado = :rcveestado, 
												  rcvemunicipio = :rcvemunicipio, 
												  odireccion = :odireccion, 
												  ocolonia = :ocolonia, 
												  ocodigopostal = :ocodigopostal, 
												  oemail = :oemail 
												  WHERE orfc = :rfc'
												);

				$stmt->bindParam(':rfc', $rfc);

			}else{

				$stmt = $this->conexion->prepare('INSERT INTO datosfiscales 
								  (	
								  	orfc,
								  	orazonsocial,
								  	rcveestado,
								  	rcvemunicipio,
								  	odireccion,
								  	ocolonia,
								  	ocodigopostal,
								  	oemail
								  ) VALUES( :rfc_
								  		   ,:orazonsocial
								  		   ,:rcveestado
								  		   ,:rcvemunicipio
								  		   ,:odireccion
								  		   ,:ocolonia
								  		   ,:ocodigopostal
								  		   ,:oemail
										   )');

			}

			$stmt->bindParam(':rfc_', $rfc);
			$stmt->bindParam(':orazonsocial', $razon_social);
			$stmt->bindParam(':rcveestado', $estado);
			$stmt->bindParam(':rcvemunicipio', $municipio);
			$stmt->bindParam(':odireccion', $direccion);
			$stmt->bindParam(':ocolonia', $colonia);
			$stmt->bindParam(':ocodigopostal', $codigo_postal);
			$stmt->bindParam(':oemail', $email);

			if( $stmt->execute() ){
				print_r(json_encode( array('tipe' => true) ));
			}else{
				print_r(json_encode( array('tipe' => false) ));
			}

		}
		
	}


	public function verificar_existencia( $rfc ){

		$stmt = $this->conexion->prepare("SELECT orfc FROM datosfiscales WHERE orfc = ?");

		$stmt->bindParam(1, $rfc);

		$stmt->execute();

		$tipe = false;

		while ( $row = $stmt->fetch(2) ) {
			$data[] = $row;
		}

		if( @$data ){
			$tipe = true;
		}


			return $tipe;

	}

	public function validar_datos( $validar_datos ){

		$ctn_vacios = 0;

		foreach ($validar_datos as $key => $value) {
			if( !$value ){
				$ctn_vacios++;
			}
		}

			return $ctn_vacios;

	}


}


?>