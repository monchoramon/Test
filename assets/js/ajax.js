

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

								var availableTags = []

								for(var x = 0; x < request.data.length; x++){
									availableTags.push( request.data[x]['onombre'] )
								}

									mostrar_autocompletado( availableTags )

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

	//autocompletado

	function mostrar_autocompletado( availableTags ){

		// console.log( availableTags )

		$(document).on('keypress', '#prod_aut', function(){

			$(this).autocomplete({
      			source: availableTags
	   		})	

		})

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
