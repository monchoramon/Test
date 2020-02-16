<?php

/**
 * 
 */

class conexion{

	public function bd(){		

	$usuario = "root";
	$password = "";

	$mbd = new PDO('mysql:host=localhost;dbname=___test;charset=utf8', $usuario, $password);

		if(!$mbd){
			print_r(json_encode(array(
									"tipe"=>false, 
									"text"=>"Error de conexión a la BD."
									)));

			
		}else{
			return $mbd;
		}

	}

}

?>