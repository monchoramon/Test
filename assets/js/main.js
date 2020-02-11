
$("input[name=rfc]").on({

	  keypress:function(){
		  	if( $("input[name=rfc]").val().length > 1 ){
	  			mandar_datos('#get_rfc', 'autocompletados', 1, 1)
		  	}
	  }

})

	function get_pintar_autocompletado( request ){
		$("input[name=rfc]").autocomplete({
				source: "dede"
			})
		console.log( request )
	}


		$(document).ready(function(){
			mandar_datos('#get_rfc', 'llenado', 1, 2)
		})
