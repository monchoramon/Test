
	//autocompletado
	$("input[name=rfc]").on({
		  keypress:function(){
			  	if( $("input[name=rfc]").val().length > 1 ){
		  			mandar_datos('', this.value, 'main', 1, 3)
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
		mandar_datos('', null, 'main', 1, 2)
		mandar_datos('', null, 'main', 1, 3)
	})

	//Mostrar municipios por estado

	$("#estados").on({

		change:function(){
			console.log( this.value )
			mandar_datos('', this.value, 'main', 1, 3)
		}

	})

	//autocompletado 

	$(document).on( 'keydown', '#prod_aut', function(){

		var value = $(this).val()
		mandar_datos('#get_rfc', value, 'main', 1, 4)

			console.log( value )

	})

	// $('.ui-menu-item-wrapper').on('click', function(){
	// 	console.log( input.val() )
	// })
