

	function mandar_datos( form, params, directorio, method, opc_server, prop_cam_autoC ){

			var method = tipo_metodo( method ), dat_return = null;

			$.ajax({

				url: 'assets/server/'+directorio+'/index.php', //_script.php
				data: {
					params:params,
					opcion:opc_server
					// data:$(form).serialize()
				},
				method: method,

				success:function( data ){

					var request = JSON.parse( data );

				if( request ){
						
						switch(opc_server){
							
							case 1:
								console.log('autocompletado')
							break;

							case 2:
								var select = $("#estados")
								llenado_select( select, request )
							break;

							case 3:
								var select = $("#municipios")
									if( params ){
										$("#municipios option").remove()
									}
								llenado_select( select, request )
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

					}

				}

			}

		})

	}

	function llenado_select( select, request, index ){

		var opt = data = null

		for(var x = 0; x < request.data.length; x++){
			data = request.data[x]
			opt = $('<option>', {value:data[0], text:data[1]})
			select.append( opt )
		}

	}

	//generar arreglo para las opciones de autocompletado

	function arreglo_autocompletado( request, prop_cam_autoC ){

		if( request.data ){

			var auto_txt = []

			for(var x = 0; x < request.data.length; x++){
				auto_txt.push( request.data[x][0] )
			}

			mostrar_autocompletado( auto_txt, prop_cam_autoC )

		}

	}

	//mostar opciones para el autocompletado

	var _this = null

	function mostrar_autocompletado( auto_txt, prop_cam_autoC ){

		//console.log( auto_txt, prop_cam_autoC )

		$(document).on('keypress', prop_cam_autoC, function(){

			_this = this

			$(this).autocomplete({
				source: auto_txt
	   		})	

		})

	}

	//pintat los datos correspondientes según la selección
	function pintar_data_input_conceptos( request ){

		var inputs = $(_this).parent().parent().children(),
			input_clave   = inputs[0].firstElementChild,
			input_cos_unit =inputs[3].firstElementChild 

		input_clave.value    = request.data[0]['oclave']
		input_cos_unit.value = request.data[0]['ocostounitario']

		input_clave.disabled = true
		input_cos_unit.disabled = true

		//console.log( request, inputs )

	}

	function llenado_datos_con_rfc( request ){
		$('#razon_social').val(request[0].orazonsocial)
		$('#email').val(request[0].oemail)
		$('#estados').val(request[0].rcveestado)
		$('#municipios').val(request[0].rcvemunicipio)
		$('#direccion').val(request[0].odireccion)
		$('#colonia').val(request[0].ocolonia)
		$('#codigo_postal').val(request[0].ocodigopostal)
	}

	function tipo_metodo( tipe ){

		var method = null;

			switch( tipe ){

				case 1:
					method = "POST";
				break;

				case 2:
					method = "GET";
				break;


			}


		return method;

	}
