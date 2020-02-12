

	function mandar_datos( form, params, directorio, method, opc_server ){

			var method = tipo_metodo( method ), dat_return = null;

			$.ajax({

				url: 'assets/server/'+directorio+'/index.php', //_script.php
				data: {
					params:params,
					opcion:opc_server,
					data:$(form).serialize()
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

								if( request.data ){

									var availableTags = []

									for(var x = 0; x < request.data.length; x++){
										availableTags.push( request.data[x]['onombre'] )
									}

									mostrar_autocompletado( availableTags )

								}
								
							break;

							case 5:
								pintar_data_input( request )
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

	//mostar opciones para el autocompletado

	var _this = null

	function mostrar_autocompletado( availableTags ){

		// console.log( availableTags )

		$(document).on('keypress', '#prod_aut', function(){

			_this = this

			$(this).autocomplete({
				source: availableTags
	   		})	

		})

	}

	//pintat los datos correspondientes según la selección
	function pintar_data_input( request ){

		var inputs = $(_this).parent().parent().children(),
			input_clave   = inputs[0].firstChild,
			input_cos_unit =inputs[3].firstChild 

		input_clave.value = request.data[0]['oclave']
		input_cos_unit.value = request.data[0]['ocostounitario']

		input_clave.disabled = true
		input_cos_unit.disabled = true

		//console.log( inputs )

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
