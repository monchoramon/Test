

	function mandar_datos( form, params, directorio, method, opc, server ){

			var method = tipo_metodo( method );

			$.ajax({

				url: 'assets/server/'+directorio+'/index.php', //_script.php
				data: {
					params:params,
					opcion:server,
					data:$(form).serialize()
				},
				method: method,

				success:function( data ){

					var request = JSON.parse( data );

				if( request ){
						
						switch(opc){
							
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
