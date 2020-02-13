
	//Pintar datos al cargar documento
	$(document).ready(function(){
		//form, directorio, method, opc, server
		mandar_datos('', null, 'main', 1, 2, null)
		mandar_datos('', null, 'main', 1, 3, null)
	})

	//Mostrar municipios por estado
	$("#estados").on({

		change:function(){
			console.log( this.value )
			mandar_datos('', this.value, 'main', 1, 3, null)
		}

	})

	//autocompletado, index para buscar con LIKE
	$(document).ready(function(){

		var value = null

		$(document).on( 'keypress', '#prod_aut', function(){

			value = $(this).val()

			//bucar las coincidencias con lo escrito, conceptos
			mandar_datos('', value, 'main', 1, 4, '#prod_aut')
			get_value_input(0)


		})


		$(document).on( 'keypress', 'input[name=rfc]', function(){

			value = $(this).val()

			//bucar las coincidencias con lo escrito, RFCs
			mandar_datos('', value, 'main', 1, 6, 'input[name=rfc]')
			get_value_input(1)

		})


	})


	function get_value_input( _tipo ){

		var tipo = opcion_ajax_consulta_datos_autocomplete( _tipo )

		$(document).on('click', '.ui-menu-item-wrapper', function(){
			var value_f = $("#"+this.id)[0].textContent
			mandar_datos('', value_f, 'main', 1, tipo, null)
		})

	}

		function opcion_ajax_consulta_datos_autocomplete( _tipo ){

			var tipo = null

				switch( _tipo ){

					case 0:
						tipo = 5
					break;

					case 1:
						tipo = 7
					break;
				
				}

					return tipo

		}

	// $("#guardar_compra").click(function(){
	// 	mandar_datos('#data_compra', '', 'main', 1, 6, null)
	// })

		$(document).on('click', '#eliminar_concepto', function(){
			eliminar_fila_tabla(this)
		})

			$(document).on('click', '#botonAgregarConcepto', function(){
				agregar_concepto( this )
			})

		function eliminar_fila_tabla( _this ){

			var padre = $(_this).parent().parent().parent() //padre_element( _this ),
			fila = $(_this).parent().parent() // fila donde esta el botón actual

			if( padre.children().length > 1 ){ //obtener filas (tr) de la tabla
				fila.remove()
			}else{
				alert('Debe de tener al menos una fila')
			}

		}

		//independiente
		function agregar_concepto( _this ){

			var id_tbody = $(_this).parent().parent().children(),
				tag_tr = $("<tr/>", {class:'create_tr'})

			for(var x = 0; x < 8; x++){

				tag_td = $('<td>', {class:'create_td'})

				if( x < 7 ){	

					if( x < 2 ){
						var class_name = 'form-control form-control-sm'
					}else{
						var class_name = 'campoNumerico '+'form-control form-control-sm'
					}

						var tag_input = $('<input>', {type:'text', class: class_name})
					
							if( x == 1 ){
								tag_input[0].id = 'prod_aut'
							}
					
								tag_td.append( tag_input )

				}else{

					var tag_btn = $('<button>', {type:'button', class:'btn btn-danger btn-sm', id: 'eliminar_concepto'}),
						tag_i = $('<i>', {class:'fa fa-trash-o'})

					tag_btn.append( tag_i )
					tag_td.append( tag_btn )

				}

				tag_tr.append( tag_td )

			}

			$("#"+id_tbody[0].id).append( tag_tr )

			//tbody.append(  )

			console.log( id_tbody[0].lastElementChild )

		}

		// function padre_element( _this ){
		// 	return 
		// }




