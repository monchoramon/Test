
function mandar_datos( form, params, directorio, method, opc_server, prop_cam_autoC ){

		var method = tipo_metodo( method ), dat_return = null;

		$.ajax({

			url: 'assets/server/'+directorio+'/index.php', //_script.php
			data: tipo_datos_enviar( form, params, opc_server ),
			method: method,

			success:function( data ){

				var request = JSON.parse( data );

			if( request ){
					
					switch(opc_server){
						
						case 1:
							console.log('autocompletado')
						break;

						case 2:
							llenado_select( request, params, 1 )
						break;

						case 3:
							llenado_select( request, params, 2 )
						break;

						case 4:
							arreglo_autocompletado( request, prop_cam_autoC )
						break;

						case 5:
							pintar_data_input_conceptos( request )
						break;

						case 6:
							arreglo_autocompletado( request, prop_cam_autoC )
						break;

						case 7:
							llenado_datos_con_rfc( request, prop_cam_autoC )
						break;

						case 8:
							gurdar_datos_fiscales( request )
						break;

				}

			}

		}

	})

}