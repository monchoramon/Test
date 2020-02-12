
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

	//autocompletado, index para buscar con LIKE

	$(document).ready(function(){

		var value = null

		$(document).on( 'keypress', '#prod_aut', function(){

			value = $(this).val()

			//bucar las coincidencias con lo escrito
			mandar_datos('#get_rfc', value, 'main', 1, 4)
			get_value_input( value )


		})

	})


	function get_value_input( value ){

		$(document).on('click', '.ui-menu-item-wrapper', function(){
			var value_f = $("#"+this.id)[0].textContent
			mandar_datos('#get_rfc', value_f, 'main', 1, 5)
		})

	}




