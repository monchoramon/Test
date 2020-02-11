

	function mandar_datos( form, directorio, tipe, opc){

			var method = tipo_metodo( tipe );

			$.ajax({

				url: 'assets/server/'+directorio+'/_script.php',
				data: $(form).serialize(),
				method: method,

			success:function( data ){

				var request = JSON.parse( data );

				if( request ){
					
					switch(opc){
						
						case 1:

						break;

						case 2:

							var select = $("#estados"), opt = data = null;

							for(var x = 0; x < request.data.length; x++){
								data = request.data[x];
								opt = $('<option>', {value:data['kcveestado'], text:data['onombre']})
								select.append( opt )
							}

						break;
					}

				}

			}

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
