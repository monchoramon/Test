<?php

/**
 * Llenado de datos
 */

include_once '../PDO/pdo.php';

class main{

	public $conexion;
	public $sqls_ctn;

	function __construct(){
		$pdo = new conexion();
		$this->conexion = $pdo->bd();

		$this->sqls_ctn = 0;

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

	public function guardar_factura($cantidad, $descuento, $iva, $total, $rfc, $clave, 
									$forma_pago, $uso_cfdi, $metodo_pago, $numero_cuenta){

		$folio = main::folio();

		$data = array( $cantidad, $descuento, $iva, $total );
		$claves = main::get_id_producto( $clave );

		//generar arreglo unidimensional
		foreach ($claves as $key => $value) {
			foreach ($value as $key => $value) {
				$data[4][$key] = $value;
			}
		}

		foreach ($cantidad as $key_a => $value) {
			foreach ($data as $key_b => $value) {
				$data_final[$key_a][$key_b] = $data[$key_b][$key_a];
				$data_final[$key_a][$key_b+1] = main::get_id_datos_fiscales( $rfc );
			}
		}

		foreach ($data_final as $key_a => $value_a) {
			main::insertar_conceptos( $key_a, $data_final );
		}

		//print_r(json_encode( $data_final ));


	}

		public function get_id_producto( $clave ){
			
			foreach ($clave as $key => $value) {

				$stmt = $this->conexion->prepare("SELECT kcveproducto FROM producto WHERE oclave = ?");

				$stmt->bindParam(1, $value);

				$stmt->execute();

				while ( $row = $stmt->fetch(PDO::FETCH_NUM) ) {
					$id_data[0][$key] = $row[0];
				}

			}

				//print_r(json_encode( $id_data ) );

			return $id_data;

		}

		public function get_id_datos_fiscales( $rfc ){

			$stmt = $this->conexion->prepare("SELECT kcvedatosfiscales FROM datosfiscales WHERE orfc = ?");

			$stmt->bindParam(1, $rfc);

			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_NUM)[0];

		}

		public function insertar_conceptos( $index_main, $data_final ){

				$stmt = $this->conexion->prepare('INSERT INTO compras 
						  (	
						  	cantidad,
						  	descuento,
						  	iva,
						  	total,
						  	kcveproducto,
						  	kcvedatosfiscales
			  			  ) VALUES( :cantidad,
							  		:descuento,
							  		:iva,
							  		:total,
							  		:clave_producto,
							  		:rfc
								   )');

					$stmt->bindParam(':cantidad', $data_final[$index_main][0]);
					$stmt->bindParam(':descuento', $data_final[$index_main][1]);
					$stmt->bindParam(':iva', $data_final[$index_main][2]);
					$stmt->bindParam(':total', $data_final[$index_main][3]);
					$stmt->bindParam(':clave_producto', $data_final[$index_main][4]);
					$stmt->bindParam(':rfc', $data_final[$index_main][5]);

				if( $stmt->execute() ){
					$this->sqls_ctn++;
				}

					if( $this->sqls_ctn == 3 ){
						print_r(json_encode( array( $this->sqls_ctn ) ));
					}

		}


	public function folio(){

		$stmt = $this->conexion->prepare("SELECT MAX(folio) AS folio_otro FROM otros_datos");

		$stmt->execute();

		while ( $row = $stmt->fetch(2) ) {
			$folio[] = $row;
		}

			$folio_query = @$folio[0]['folio_otro'];

			if( @$folio_query ){
				$folio_otro = $folio_query+1;
			}else{
				$folio_otro = 1;
			}

		return $folio_otro;

	}


}


?>