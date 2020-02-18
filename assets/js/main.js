
	//Pintar datos al cargar documento
	$(document).ready(function(){
		//form, directorio, method, opc, server
		mandar_datos('', null, 'main', 1, 2, null)
		mandar_datos('', null, 'main', 1, 3, null)
	})

	//Mostrar municipios por estado
	$("#estado").on({

		change:function(){
			cambiar_municipio(this)
		},
		click:function(){
			cambiar_municipio(this)
		}

	})

	function cambiar_municipio(_this){
		console.log( _this.value )
		mandar_datos('', _this.value, 'main', 1, 3, null)
	}

	//autocompletado, index para buscar con LIKE
	$(document).ready(function(){

		var value = null

		$(document).on( 'keyup', '#prod_aut', function(){

			value = $(this).val()

			//bucar las coincidencias con lo escrito, conceptos
			mandar_datos('', value, 'main', 1, 4, '#prod_aut')
			get_value_input(0)


		})


		$(document).on( 'keyup', 'input[name=rfc]', function(){

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

		$("#btn_save_dat_fis").click(function(){
			cambiar_opcion( 8 );
			mandar_datos('#data_server', '', 'main', 1, 8, null)
		})

			$("#guardar_compra").click(function(){
				cambiar_opcion( 9 );
				mandar_datos('#data_server', '', 'main', 1, 9, null)
			})

				$("#generar_factura").click(function(){
					mandar_datos('', $("#rfc").val(), 'main', 1, 10, null)
				})

					$(document).on('click', '#eliminar_concepto', function(){
						eliminar_fila_tabla(this)
						campos_cantidad_descuento(this)
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
				tag_tr = $("<tr/>", {class:'create_tr'}),
				prop_name = ['clave[]'
							,'prod_aut[]'
							,'cantidad[]'
							,'costo_unit[]'
							,'descuento[]'
							,'iva[]'
							,'total[]'],
				prop_id = [
							'clave',
							'prod_aut',
							'cantidad',
							'costo_unit',
							'descuento',
							'iva',
							'total'
						  ]

			for(var x = 0; x < 8; x++){

				tag_td = $('<td>', {class:'create_td'})

				if( x < 7 ){	

					if( x < 2 ){
						var class_name = 'form-control form-control-sm'
					}else{
						var class_name = 'campoNumerico '+'form-control form-control-sm'
					}

						var tag_input = $('<input>', {type:'text', class: class_name, name:prop_name[x], id:prop_id[x]})
					
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

		//Calculos facturación

		$(document).on('keyup', '#cantidad', function(){
			campos_cantidad_descuento( this, null )
		})

		$(document).on('keyup', '#descuento', function(){
			campos_cantidad_descuento( this, null )
		})

				$("#cambiar_descuento").click(function(){
					activar_modal("#modal_descuento");
				})

			 	$("#cambiar_des").click(function(){
			 		aplicar_descuentos_productos()
			 		hidden_modal("#modal_descuento")
			 	})

			 	function aplicar_descuentos_productos(){

			 		var rows = get_numero_filas("#conceptos_table", 0).length, ctn = 0

			 		for(var x = 0; x < rows; x++){

			 			var row = tabla("#conceptos_table").rows[x].cells[4]

			 			if( row.firstChild.value = $("#otro_descuento")[0].value ){
		 					ctn++;
			 			}

			 		}


			 			if( ctn == rows ){
			 				get_numero_filas( "#conceptos_table", 1 ) // main
			 			}

			 			console.log( ctn );

			 	}

			 	function tabla(id){
			 		return $(id+" tbody")[0]
			 	}

			 	function get_numero_filas( id, tipe ){

			 		var filas = tabla(id).rows;
		 			
		 			if( tipe ){
		 				get_celdas_por_fila( filas, id );
		 			}else{
		 				return filas;
		 			}

			 	}

			 	function get_celdas_por_fila( filas, id ){

			 		//var length_celdas = tabla(id).rows[0].cells.length;

			 		for (var i = 0; i < filas.length; i++) {

			 			/*for(var x = 0; x < length_celdas; x++){

			 				if( x == 2 ){
									var cantidad = get_val_inputs_por_id_tabla(id, i, x);
			 				}else{
			 					if( x == 3 ){
									var costo_unit = get_val_inputs_por_id_tabla(id, i, x);
			 					}else{
			 						if( x == 4 ){
			 						var descuento = get_val_inputs_por_id_tabla(id, i, x);	
			 						}
			 					}
			 				}

			 			}*/

			 			campos_cantidad_descuento( null, get_val_inputs_por_id_tabla(id, i, x = null) )

			 		}



			 	}

			 	function get_val_inputs_por_id_tabla( id, i, x ){
			 		//var value = tabla(id).rows[i].cells[x].firstChild.value
			 		var row = tabla(id).rows[i]
			 		return row
			 	}

		function campos_cantidad_descuento( _this, row ){

			if( _this ){
				var tr_table = $(_this).parent().parent().children()
			}else{
				var tr_table = row.cells
				 _this = null
			}

			var cantidad    = tr_table[2].firstChild.value,
			    costo_unit  = tr_table[3].firstChild.value,
			    descuento   = tr_table[4].firstChild.value


				if( cantidad && costo_unit && descuento){
					calcular_datos_factura( cantidad, costo_unit, descuento, tr_table, _this )
				}else{
					console.log(false)
				}

			console.log( cantidad, costo_unit, descuento, tr_table, _this );

		}

		function calcular_datos_factura( cantidad, costo_unit, descuento, tr_table, _this ){

			var _total_ini = (cantidad * costo_unit),
				_descuento_aplicado = (_total_ini * descuento)/100,
			    _iva = ( _total_ini - _descuento_aplicado ) * 0.16

				tr_table[5].firstChild.value = _iva
				tr_table[6].firstChild.value = ( _total_ini - _descuento_aplicado) + _iva

			var importe = 0, _descuento = 0, subtotal = 0, iva_ = 0, total = 0, aux_td = 0

				if( _this ){
					rows = $(_this).parent().parent().parent().children()
				}else{
					rows = get_numero_filas( "#conceptos_table", 0 );
				}

				cantidad_aux = 0, costo_unit_aux = 0

				for( var x = 0; x < rows.length; x++){ 

					aux_td = rows[x].children

					for(var y = 0; y < (rows[x].children.length - 1); y++ ){

						if( y == 2 ){
							cantidad_aux = parseFloat( retornar_values( aux_td, y ) )
						}else{
							if( y == 3 ){
								costo_unit_aux = parseFloat( retornar_values( aux_td, y ) )
							}else{
								if( y == 4 ){
									_descuento += parseFloat( retornar_values( aux_td, y ) )
								}else{
									if( y == 5 ){
										console.log( retornar_values( aux_td, y ) )
										iva_ += parseFloat( retornar_values( aux_td, y ) )
									}
								}
							}
						}

					}

					importe += (cantidad_aux * costo_unit_aux)

				}

				subtotal = ( importe - _descuento )
				total = subtotal + iva_

				$('#importe')[0].innerText = '$'+importe
				$('#_descuento')[0].innerText = '$'+_descuento
				$('#subtotal')[0].innerText = '$'+subtotal
				$('#_iva')[0].innerText = '$'+iva_
				$('#_total')[0].innerText = '$'+total

			console.log( importe, _descuento, subtotal, iva_, total, rows )

			//console.log( rows[0].children.length - 1 )

		}

		function retornar_values( aux_td, y ){

			var value = aux_td[y].children[0].value

			if( !value ){
				value = 0
			}

				return value
		}

		function cambiar_opcion( opcion ){
			$("input[name=opcion]")[0].value = opcion
		}

		function activar_modal(id){
			$(id).modal({
				backdrop: 'static',
				keyboard:false
			}).show();
		}

		function hidden_modal(id){
			$(id).modal('hide')
		}



