
	//autocompletado
	$("input[name=rfc]").on({
		  keypress:function(){
			  	if( $("input[name=rfc]").val().length > 1 ){
		  			mandar_datos('#get_rfc', 'main', 1, 1, 1)
			  	}
		  }
	})

	// function get_pintar_autocompletado( request ){
	// 	$("input[name=rfc]").autocomplete({
	// 			source: "dede"
	// 		})
	// 	console.log( request )
	// }


	//Pintar datos al cargar documento
	$(document).ready(function(){
		//form, directorio, method, opc, server
		mandar_datos('#get_rfc', null, 'main', 1, 2, 2)
		mandar_datos('#get_rfc', null, 'main', 1, 3, 3)
	})

	//Mostrar municipios por estado

	$("#estados").on({

		change:function(){
			console.log( this.value )
			mandar_datos('#get_rfc', this.value, 'main', 1, 3, 3)
		}

	})
