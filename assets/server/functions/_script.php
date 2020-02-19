<?php

/**
 * Llenado de datos
 */

include_once '../PDO/pdo.php';
date_default_timezone_set('America/Mexico_City');

class main{

	public $conexion;
	public $sqls_ctn;
	public $id_otros;
	public $seccion_a;
	public $seccion_b;
	public $seccion_c;

	// public $forma_pago;
	// public $uso_cfdi;
	// public $metodo_pago;
	// public $numero_cuenta;
	// public $folio;

	function __construct(){

		$pdo = new conexion();
		$this->conexion = $pdo->bd();

		$this->sqls_ctn = 0;
		$this->id_otros = 0;

		$this->seccion_a = $this->seccion_b = $this->seccion_c = array();

		session_start();

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

	public function gurdar_datos_fiscales($rfc, $razon_social, $estado, $municipio, $direccion, $colonia, $codigo_postal, $email){

		$validar_datos = array( $rfc, $razon_social, $email, $estado, $municipio, $direccion, $colonia, $codigo_postal );

		//print_r(json_encode( array( $validar_datos ) ));

		if( main::validar_datos( $validar_datos ) > 0 ){
			print_r(json_encode( array('info' => 'Todos los campos son necesarios.') ));
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

				$stmt = $this->conexion->prepare("INSERT INTO datosfiscales 
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
										   )");

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
				// print_r(json_encode( array('info'=>'Registro generado correctamente.') ));
				return true;
			}else{
				print_r(json_encode( array('info'=>'Error interno del servidor.') ));
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

	public function guardar_factura($cantidad,
									$descuento,
									$iva,
									$total,
									$rfc,
									$clave,
									$forma_pago,
									$numero_cuenta,
									$uso_cfdi,
									$metodo_pago,
									//datos fiscales
									$_rfc,
									$razon_social,
									$estado,
									$municipio,
									$direccion,
									$colonia,
									$codigo_postal,
									$email
								   ){


		if ( main::gurdar_datos_fiscales( $_rfc, $razon_social, $estado, $municipio, $direccion, $colonia, $codigo_postal, $email ) ){

			$id_rfc = main::insert_otros_datos( $forma_pago, $uso_cfdi, $metodo_pago, $numero_cuenta, $rfc );

			if( $id_rfc ){
				 $this->id_otros = main::get_id_otros_datos( $id_rfc );
			}

			if( $this->id_otros ){		

				$data = array( $cantidad, $descuento, $iva, $total );
				$claves = main::get_id_producto( $clave );

				//generar arreglo unidimensional de ids, clave producto
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

					        //main::insertar_conceptos( $key_a, $data_final );

							//print_r(json_encode( array( $data_final, $this->id_otros ) ));

			}

		}


	}

	public function insertar_conceptos( $index_main, $data_final ){

		$stmt = $this->conexion->prepare('INSERT INTO compras 
				  (	
				  	cantidad,
				  	descuento,
				  	iva,
				  	total,
				  	kcveproducto,
				  	kcvedatosfiscales,
				  	id_otros
	  			  ) VALUES( :cantidad,
					  		:descuento,
					  		:iva,
					  		:total,
					  		:kcveproducto,
					  		:kcvedatosfiscales,
					  		:id_otros
						   )');

			$stmt->bindParam(':cantidad', $data_final[$index_main][0]);
			$stmt->bindParam(':descuento', $data_final[$index_main][1]);
			$stmt->bindParam(':iva', $data_final[$index_main][2]);
			$stmt->bindParam(':total', $data_final[$index_main][3]);
			$stmt->bindParam(':kcveproducto', $data_final[$index_main][4]);
			$stmt->bindParam(':kcvedatosfiscales', $data_final[$index_main][5]);
			$stmt->bindParam(':id_otros', $this->id_otros);


		if( $stmt->execute() ){
			$this->sqls_ctn++;
		}

			if( $this->sqls_ctn == sizeof($data_final) ){
				print_r(json_encode(array('tipe'=>true)));
			}

	}


	public function insert_otros_datos( $forma_pago, $uso_cfdi, $metodo_pago, $numero_cuenta, $rfc ){

			$fecha  = date("Y-m-d H:i:s");
			$id_rfc = (int) main::get_id_datos_fiscales( $rfc );
			$folio  = main::folio();
			
			$stmt = $this->conexion->prepare("INSERT INTO otros_datos 
						  (	fecha_expedicion,
						  	folio,
						  	forma_pago,
						  	cfdi,
						  	metodo_pago,
						  	n_cuenta,
						  	kcvedatosfiscales
			  			  ) VALUES( :fecha_expedicion,
			  			  			:folio,
			  			  			:forma_pago,
							  		:cfdi,
							  		:metodo_pago,
							  		:n_cuenta,
							  		:kcvedatosfiscales
								   )");

			$stmt->bindParam(':fecha_expedicion', $fecha);
			$stmt->bindParam(':folio', $folio);
			$stmt->bindParam(':forma_pago', $forma_pago);
			$stmt->bindParam(':cfdi', $uso_cfdi);
			$stmt->bindParam(':metodo_pago', $metodo_pago);
			$stmt->bindParam(':n_cuenta', $numero_cuenta);
			$stmt->bindParam(':kcvedatosfiscales', $id_rfc); 


			if( $stmt->execute() ){
				return $id_rfc;
			}else{
				print_r(json_encode( array( false ) ));
			}



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


		public function get_max_folio_por_rfc( $rfc ){

			$stmt = $this->conexion->prepare("SELECT MAX(folio) AS folio FROM otros_datos WHERE kcvedatosfiscales = ?");

			$stmt->bindParam(1, $rfc);
			$stmt->execute();

			$folio_max = $stmt->fetch(2)['folio'];

			return $folio_max;

		}

		public function get_id_otros_datos( $id_rfc ){

			$stmt = $this->conexion->prepare("SELECT MAX(id_otros) AS id_otros FROM otros_datos WHERE kcvedatosfiscales = ?");

			$stmt->bindParam(1, $id_rfc);
			$stmt->execute();


			$id_otros = $row = $stmt->fetch(PDO::FETCH_NUM)[0];

				if( $id_otros ){
					return $id_otros;
				}else{
					print_r(json_encode( array('Error interno del Servidor.') ));
				}

		}

		public function get_id_productos_compras_por_id_otros_y_rfc( $id_otros, $id_rfc ){

			$stmt = $this->conexion->prepare("SELECT kcveproducto FROM compras WHERE id_otros = ? AND kcvedatosfiscales = ?");

			$stmt->bindParam(1, $id_otros);
			$stmt->bindParam(2, $id_rfc);

			$stmt->execute();

			while ( $row = $stmt->fetch(PDO::FETCH_NUM) ) {
				$id_productos[] = $row;
			}

				return $id_productos;

		}

		public function get_id_compras_table_compras( $id_otros ){

			$stmt = $this->conexion->prepare("SELECT id_compras FROM compras WHERE id_otros = ?");
			$stmt->bindParam(1, $id_otros);
			$stmt->execute();

			while ( $row = $stmt->fetch(PDO::FETCH_NUM) ) {
				$id_compras[] = $row[0];
			}

				return $id_compras;

		}

		//Inicio facturación

		public function generar_factura( $rfc ){
			$id_rfc    = main::get_id_datos_fiscales( $rfc ); //id principal RFC
			main::seccion_a_factura($id_rfc);
		}

			public function seccion_a_factura( $id_rfc ){

				$stmt = $this->conexion->prepare("SELECT datosfiscales.orazonsocial, datosfiscales.orfc, estados.onombre AS estado_nombre, municipio.onombre AS municipio_nombre FROM( (datosfiscales INNER JOIN estados ON datosfiscales.rcveestado = estados.kcveestado) INNER JOIN municipio ON datosfiscales.rcvemunicipio = municipio.kcvemunicipio) WHERE datosfiscales.kcvedatosfiscales = ? ");

				$stmt->bindParam(1, $id_rfc);
				$stmt->execute();

				while ( $row = $stmt->fetch(2) ) {
					$this->seccion_a[] = $row;
				}


				    $folio_max = main::get_max_folio_por_rfc( $id_rfc );
				    $id_otros  = main::get_id_otros_datos( $id_rfc );

					main::seccion_b_factura( $id_rfc, $folio_max );
				    main::seccion_c_factura( $id_otros, $id_rfc );

					//print_r(json_encode( array( $seccion_a, $seccion_b ) ));

			}


			public function seccion_b_factura( $id_rfc, $folio_max ){	

				$folio = main::folio();

				$stmt = $this->conexion->prepare("SELECT otros_datos.folio, otros_datos.fecha_expedicion, otros_datos.forma_pago, otros_datos.metodo_pago, otros_datos.cfdi FROM otros_datos WHERE otros_datos.kcvedatosfiscales = ? AND otros_datos.folio = ?");

				$stmt->bindParam(1, $id_rfc);
				$stmt->bindParam(2, $folio_max);
				$stmt->execute();

				while ( $row = $stmt->fetch(2) ) {
					$this->seccion_b[] = $row;
				}

					$this->seccion_b[0]['moneda'] = 'MXN';

					// print_r(json_encode( $seccion_b ));
	
			}

			public function seccion_c_factura( $id_otros, $id_rfc ){

				$ids_productos = main::get_id_productos_compras_por_id_otros_y_rfc( $id_otros, $id_rfc );
				$id_compras    = main::get_id_compras_table_compras($id_otros);


				$id_otros = main::get_id_otros_datos( $id_rfc );
				$length_id_productos = sizeof( $ids_productos );

				foreach ($ids_productos as $key => $id_producto) {
					main::unir_datos_tablas_compras_y_producto( $id_producto[0], $key, $id_rfc, $id_otros, $length_id_productos, $id_compras[$key] );
				}

				//print_r(json_encode( array( $ids_productos, $id_rfc, $id_otros, $seccion_c ) ));

			}

			public function unir_datos_tablas_compras_y_producto( $id_producto, $key, $id_rfc, $id_otros, $length_id_productos, $id_compras ){

				//print_r(json_encode( array( $id_producto, $id_rfc, $id_otros ) ));

				$stmt = $this->conexion->prepare("SELECT producto.oclave, producto.onombre, compras.cantidad, producto.ocostounitario, compras.descuento, compras.iva, compras.total, compras.kcveproducto FROM ( (producto INNER JOIN compras ON producto.kcveproducto = compras.kcveproducto) ) WHERE compras.kcveproducto = ? AND compras.kcvedatosfiscales = ? AND compras.id_otros = ? AND compras.id_compras = ?");

					$stmt->bindParam(1, $id_producto);
					$stmt->bindParam(2, $id_rfc);
					$stmt->bindParam(3, $id_otros);
					$stmt->bindParam(4, $id_compras);

					$stmt->execute();

					while ( $row = $stmt->fetch(2) ) {
						$this->seccion_c[$key] = $row;
					}


					if( ($key+1) == $length_id_productos){
						$_SESSION["seccion_a"] = $this->seccion_a;
						$_SESSION["seccion_b"] = $this->seccion_b;
						$_SESSION["seccion_c"] = $this->seccion_c;
						print_r(json_encode( array( $this->seccion_a, $this->seccion_b, $this->seccion_c, 'tipe' => true) ));
					}

			}



}


?>