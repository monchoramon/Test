	
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

	function tipo_datos_enviar( form, params, opc_server ){

		var data = $(form).serialize()

		if( !form ){
			data = {
						params:params,
						opcion:opc_server
				    }
		}
		

			return data

	}

	function llenado_select( request, params, tipo ){

			var select = opt = data = null
								
			if( params ){
				$("#municipio option").remove()
			}

				switch( tipo ){
					case 1:
						select = $("#estado")	
					break;

					case 2:
						select = $("#municipio")
					break;
				}


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

		console.log( auto_txt, prop_cam_autoC )

		$(document).on('keypress keyup', prop_cam_autoC, function(){

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
		
		input_clave.readOnly = true
		input_cos_unit.readOnly = true

		//console.log( request, inputs )

	}

	function llenado_datos_con_rfc( request ){
		$('#razon_social').val(request[0].orazonsocial)
		$('#email').val(request[0].oemail)
		$('#estado').val(request[0].rcveestado)
		$('#municipio').val(request[0].rcvemunicipio)
		$('#direccion').val(request[0].odireccion)
		$('#colonia').val(request[0].ocolonia)
		$('#codigo_postal').val(request[0].ocodigopostal)
	}

	function gurdar_datos_fiscales( request ){
		alert(request.info);
	}

