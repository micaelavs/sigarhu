  $(document).ready(function () {

    $(document).delegate('.fechaAntiguedad', 'focus', function(){
		$(".fechaAntiguedad").datetimepicker({
	      maxDate: 'now',
	      format: 'DD/MM/YYYY'
	    }).on('dp.change',function ($e) {
	    	if ($(this).attr("id") == "fecha_ingreso_mtr" || $(this).attr("id") == "fecha_grado"){
				Date.getFormattedDateDiff = function(date1, date2) {
		  			var b = moment(date1,"DD-MM-YYYY"),
		      		a = moment(date2),
					intervals = ['years','months','days'],
					out = [],
					esp = ['años','meses','días'];
					for(var i=0; i<intervals.length; i++){
					    var diff = a.diff(b, intervals[i]);
					    b.add(diff, intervals[i]);
					    out.push(diff + ' ' + esp[i]);
					}
					return out.join(', ');
				};
				var date = moment();
				ee = Date.getFormattedDateDiff(this.value, date);
				if ($(this).attr("id") == "fecha_ingreso_mtr"){
					$('#cont_ant').text(ee);
				}else{
					$('#cont_grado').text(ee);
				}
			}
  		});
	});

    $(".horario").datetimepicker({
      maxDate: 'now',
      format: 'HH:ss'
	});
	
	
	$(".fecha2").datetimepicker({
      format: 'DD/MM/YYYY'
    });

	$(document).delegate('.fechaFormat', 'focus', function(){ // para que funcione con el nuevo elemento que se agrega, después que cargue el documento
	    $(".fechaFormat").datetimepicker({
	      	format: 'DD/MM/YYYY',
	   		maxDate: moment()
	    });
	});    

	
    $("#dep_id_dependencia").select2();
    $("#id_titulo").select2();
    $("#denominacion_funcion").select2();
	$("#denominacion_puesto").select2();

/**
 * Presupuesto - Limpia selects para evitar que la seleccion elegida sea incorrecta antes de modificar
*/	
	

    $('#programa').on('change', function(){
		$('#subprograma').val('');
		$('#proyecto').val('');
		$('#obra').val('');
		$('#buscar_presupuesto').click();
	})
	$('#subprograma').on('change', function(){
		$('#proyecto').val('');
		$('#obra').val('');
		$('#buscar_presupuesto').click();
	})
	$('#proyecto').on('change', function(){
		$('#obra').val('');
		$('#buscar_presupuesto').click();

	})

	$("#saf").select2();
    $("#jurisdiccion").select2();
    $("#ubicacion_geografica").select2();
    $("#programa").select2();
    $("#subprograma").select2();
    $("#proyecto").select2();
    $("#actividad").select2();
    $("#obra").select2();

/**
 * Limpia el contenido html de las etiquetas <select>
 *
 * @param string	$dom_select	- valor usado para seleccionar el elemento dom. E.j.: 'select#id_situacion_revista'
*/
    function cleanSelect($dom_select){
		if(typeof $($dom_select)[0] === 'undefined') return;
    	if($($dom_select)[0].nodeName	!= 'SELECT') return $($dom_select);

		$($dom_select).html('');
		$($dom_select).append($('<option>', {
			value: '',
			text : 'Seleccione'
		}));
		return $($dom_select);
	}

    var $collapseObs = $('#collapseObservaciones');
    var $collapseObsCaret = $("#collapseObservaciones_caret");
    $collapseObs.on('hide.bs.collapse', function () {
      $collapseObsCaret.removeClass('fa-caret-down').addClass('fa-caret-right')
    });

    $collapseObs.on('show.bs.collapse', function () {
      $collapseObsCaret.removeClass('fa-caret-right').addClass('fa-caret-down')
    });

    $("#dep_id_dependencia").select2();
    $("#nacionalidad").select2();
    $("#id_provincia").select2();
    $("#id_localidad").select2();



/**
 * Datos Personales - Alta/Modificacion de Domicilio
*/
	(function tab_datos_personales_domicilio() {
		if(!$empleado_creado || !$empleado_domicilio){
			$('#domicilio_accion').val('alta');
			$('#domicilio_accion_alta').hide();
			$('#domicilio_accion_modificar').hide();
		} else {
			$('#datos_domicilio_personal input').attr('disabled', true);
			$('#datos_domicilio_personal select').attr('disabled', true);

			var campos_domicilio	= {
				'id_provincia'	: $('#id_provincia').val(),
				'id_localidad'	: $('#id_localidad').val(),
				'calle'			: $('#calle').val(),
				'numero'		: $('#numero').val(),
				'piso'			: $('#piso').val(),
				'depto'			: $('#depto').val(),
				'cod_postal'	: $('#cod_postal').val(),
			};

			$('#domicilio_accion_alta').on('click', function($e){
				$('#domicilio_accion').val('alta');
				$('#datos_domicilio_personal input').attr('disabled', false);
				$('#datos_domicilio_personal select').attr('disabled', false);

				$.each(campos_domicilio, function(campo, valor){
					$('#'+campo).val('');
				});
				$('select#id_provincia').trigger('ajax_recargar');
			});

			$('#domicilio_accion_modificar').on('click', function($e){
				$('#domicilio_accion').val('modificacion');
				$('#datos_domicilio_personal input').attr('disabled', false);
				$('#datos_domicilio_personal select').attr('disabled', false);

				$.each(campos_domicilio, function(campo, valor){
					$('#'+campo).val(valor);	
				});
				$("select#id_provincia").val(campos_domicilio['id_provincia']).trigger('change');
				$("select#id_localidad").on('ajax_rollback', function(){
					$(this).val(campos_domicilio['id_localidad']).trigger('change');
				});
			});
		}
	})();
/**
 * Datos Personales - Agregar Telefonos
*/
	(function tab_datos_personales_telefonos(){
		$(document).on('click', '#datos_telefono .btn-add', function(e){
			e.preventDefault();
			var controlForm		= $(this).parents().parents('.combo-input'),
				currentEntry	= $(this).parents('.entry:first'),
				newEntry		= $(currentEntry.clone()).appendTo(controlForm);
			newEntry.find('.form-control').val('');
			controlForm.find('.entry:not(:last) .btn-add')
				.removeClass('btn-add').addClass('btn-remove')
				.removeClass('btn-info').addClass('btn-default')
				.attr('title', 'Eliminar Número')
				.html('<span class="glyphicon glyphicon-minus"></span>');
		}).on('click', '#datos_telefono .btn-remove', function(e){
			$(this).parents('.entry:first').remove();
			e.preventDefault();
			return false;
		});
	})();

/**
 * Datos Personales - Ubicaciones, <select> para  provincia, localidad
 */
	(function tab_datos_personales_ubicaciones(){

// Carga la informacion de Provincia y Localidad para los Datos de Domicilio en funcion de la Nacionalidad
		$('select#id_provincia').on('ajax_recargar', function($e){
			new ApiUbicaciones()
			.done(function (data) {
				cleanSelect('select#id_localidad');
				if(data.ubicacion_regiones !== undefined) {
					addOptions(data.ubicacion_regiones, 'select#id_provincia');
				}
			});
		});
		$('select#id_provincia').on('change', function($e){
			new ApiUbicaciones($('select#id_provincia').val())
			.done(function (data) {
				if(data.ubicacion_localidades !== undefined) {
					addOptions(data.ubicacion_localidades, 'select#id_localidad', true);
				}
				$("select#id_localidad").trigger('ajax_rollback');
			});
		});
	})();


/**
 * Situacion Escalafonaria
 */
	(function tab_situacion_escalafonaria(){
		if(!$no_bloquear) {
			if(!$is_admin) {
				$('#escalafon_accion_alta').hide();
			}
			if(!$empleado_creado || !$empleado_escalafonaria){
				$('#escalafon_accion').val('alta');
				$('#escalafon_accion_alta').hide();
				$('#escalafon_accion_modificar').hide();
			} else {
				$('#escalafonaria input').attr('disabled', true);
				$('#escalafonaria select').attr('disabled', true);
			}
		}	
			var campos_escalafon	= {
				'id_modalidad_vinculacion'	: $('#id_modalidad_vinculacion').val(),
				'id_situacion_revista'		: $('#id_situacion_revista').val(),
				'id_funcion_ejecutiva'		: $('#id_funcion_ejecutiva').val(),
				'id_agrupamiento'			: $('#id_agrupamiento').val(),
				'id_nivel'					: $('#id_nivel').val(),
				'compensacion_geografica'	: $('#compensacion_geografica').val(),
				'id_tramo'					: $('#id_tramo').val(),
				'id_grado'					: $('#id_grado').val(),
				'compensacion_transitoria'	: $('#compensacion_transitoria').val(),
				'cambio_nivel'				: $('#cambio_nivel').val(),
				'cambio_grado'				: $('#cambio_grado').val(),
				'unidad_retributiva'		: $('#unidad_retributiva').val(),
			};
			
			
			var campos_escalafon_mod	= {
				'id_funcion_ejecutiva'		: $('#id_funcion_ejecutiva').val(),
				'id_agrupamiento'			: $('#id_agrupamiento').val(),
				'id_nivel'					: $('#id_nivel').val(),
				'compensacion_geografica'	: $('#compensacion_geografica').val(),
				'id_tramo'					: $('#id_tramo').val(),
				'id_grado'					: $('#id_grado').val(),
				'compensacion_transitoria'	: $('#compensacion_transitoria').val(),
				'cambio_nivel'				: $('#cambio_nivel').val(),
				'cambio_grado'				: $('#cambio_grado').val(),
				'unidad_retributiva'		: $('#unidad_retributiva').val(),
			};

			var span_escalafon	= {
			
				'id_func_ejecutiva'	 : $('#id_func_ejecutiva').text(),
				'id_agrup'			 : $('#id_agrup').text(),
				'nivel_spn'				 : $('#nivel_spn').text(),
				'comp_geografica'	 : $('#comp_geografica').text(),
				'tramo'				 : $('#tramo').text(),
				'grado'  			 : $('#grado').text(),
				'comp_transitoria'	 : $('#comp_transitoria').text(),
				'cambio_nivel_spn'	 : $('#cambio_nivel_spn').text(),
				'cambio_grado_spn'	 : $('#cambio_grado_spn').text(),
				'unidad_retributiva' : $('#unidad_retributiva').text(),

			};

// Carga la situacion de revista al cambiar la modalidad de vinculacion
		$(document).delegate('select#id_modalidad_vinculacion', 'change', function(){
		//#$('select#id_modalidad_vinculacion').on('change', function($e){
			$.ajax({
				url: $base_url+"/Legajos/ajax_convenios_parametricos",
				data: {
					id_modalidad_vinculacion:	$('select#id_modalidad_vinculacion').val(),
				},
				method: "GET"
			})
			.done(function (data) {
				if ($("#tipo").data("tipo") != data.formulario.tipo){
					$("#escalafonaria").html(data.formulario.html);
				}else {
					if(data.situacion_revista !== undefined) {
						addOptions(data.situacion_revista, 'select#id_situacion_revista');
						$("#escalafonaria").find("span").empty();
							$.each(campos_escalafon_mod, function(campo, valor){
									$('#'+campo).val('');
							});
					}
				}
			});
		});
		
		
		
// Actualiza todos los datos parametricos al cambiar la situacion de revista
		$(document).delegate('select#id_situacion_revista', 'change', function () {
//		$('select#id_situacion_revista').on('change', function($e){
			$("#escalafonaria").find("span").empty();
			$.each(campos_escalafon_mod, function(campo, valor){
					$('#'+campo).val('');
			});
			$.ajax({
				url: $base_url+"/Legajos/ajax_convenios_parametricos/",
				data: {
					id_modalidad_vinculacion:	$('select#id_modalidad_vinculacion').val(),
					id_situacion_revista:		$('select#id_situacion_revista').val()
				},
				method: "GET"
			})
			.done(function (data) {
				loadConveniosParametricos(data);
			});
		});
// Si la modalidad de vincualacion y la situacion de revista vienen precargadas (modificacion) trae los datos parametricos.
		function update_modalidad_vinculacion(){
			if($('#id_modalidad_vinculacion').val() != "" && $('#id_situacion_revista').val() != ""){
				$.ajax({
					url: $base_url+"/Legajos/ajax_convenios_parametricos/",
					data: {
						id_modalidad_vinculacion:	$('#id_modalidad_vinculacion').val(),
						id_situacion_revista:		$('#id_situacion_revista').val()
					},
					method: "GET"
				})
				.done(function (data) {
					loadConveniosParametricos(data, true);
				});
			}
		}
		update_modalidad_vinculacion();

		$('#escalafon_accion_alta').on('click', function($e){
			$('#escalafon_accion').val('alta');
			$('#escalafonaria input').attr('disabled', false);
			$('#escalafonaria select').attr('disabled', false);
			$("#escalafonaria").find("span").empty();

			$.each(campos_escalafon, function(campo, valor){
				$('#'+campo).val('');
			});

		});

		$('#escalafon_accion_modificar').on('click', function($e){
			$('#escalafon_accion').val('modificacion');
			$('#escalafonaria input').attr('disabled', false);
			$('#escalafonaria select').attr('disabled', false);
			$.each(campos_escalafon, function(campo, valor){
				$('#'+campo).val(valor);
			});
			setTimeout(function(){
				if ($('#id_tramo').prop('tagName') == 'SELECT'){
					update_modalidad_vinculacion();
				}
			},1000);
			$.each(span_escalafon, function(campo, valor){
				$('#'+campo).text(valor);
			});
		});
/**
 *  Carga los datos de convenio parametricos aplicando los eventos pertinentes.
 *  Si los select tienen valores precargados intenta mantenerlos.
 *
 *  @param array	$data		- coleecion de datos
 *  @param bool		$not_clean	- Fuerza mantener los valores selccionados por PHP
*/
		function loadConveniosParametricos($data, $not_clean=false){
			if($not_clean === false){
				cleanSelect('select#id_nivel');
				cleanSelect('select#id_grado');
			}
			if(typeof $data.agrupamientos === 'object' && Object.keys($data.agrupamientos).length > 0){
				if ($('#id_agrupamiento').prop('tagName') == 'SELECT') {
					$agr	= addOptions($data.agrupamientos, '#id_agrupamiento', $not_clean);
					$agr.off('change');
					$agr.on('change', function($_e){
						addOptions($data.agrupamientos[$agr.val()].niveles, '#id_nivel', false);
					});
				}else{
					//Se obtiene el primer elemento de los tramos porque las reglas de negocio dicen que la prestacion de servicios es sin tramo
					$agr = $('#id_agrupamiento');					
					$id_nivel	= $data.agrupamientos[Object.keys($data.agrupamientos)[0]].id;
					$agr.val($id_nivel);
					addOptions($data.agrupamientos[$id_nivel].niveles, '#id_nivel', $not_clean);
				}
			}
			if (typeof $data.funciones_ejecutivas === 'object' && Object.keys($data.funciones_ejecutivas).length > 0 && $('#id_funcion_ejecutiva').prop('tagName') == 'SELECT'){
			 	addOptions($data.funciones_ejecutivas, '#id_funcion_ejecutiva', $not_clean);
			}
			
			if(typeof $data.tramos === 'object' && Object.keys($data.tramos).length > 0){
				if ($('#id_tramo').prop('tagName') == 'SELECT') {
					$tra	= addOptions($data.tramos, 'select#id_tramo', $not_clean);

					$tra.off('change');
					$tra.on('change', function($_e){
						addOptions($data.tramos[$tra.val()].grados, 'select#id_grado', false);
					});
				}else{					
					$tra = $('#id_tramo');
					//Se obtiene el primer elemento de los tramos porque las reglas de negocio dicen que la prestacion de servicios es sin tramo
					$id_tramo	= $data.tramos[Object.keys($data.tramos)[0]].id;
					$tra.val($id_tramo);
					addOptions($data.tramos[$id_tramo].grados, '#id_grado', $not_clean);
				}
			}
		}
		$(document).delegate('select#id_nivel', 'change', function(){
			ChangeURText();
		});
		$(document).delegate('select#id_grado', 'change', function(){
			ChangeURText();
		});
		//ChangeURText();

		function ChangeURText(){
			var id_nivel = $('select#id_nivel').val();
			var id_grado = $('select#id_grado').val();
			if (typeof $unidad_retributiva === 'undefined') {
				return false;
			}
			if(id_nivel != '' && id_grado !=''){
				$('#ur_minimo').text($unidad_retributiva[id_nivel][id_grado]['min']);
				$('#ur_maximo').text($unidad_retributiva[id_nivel][id_grado]['max']);
			}
		};
	})();



	if ($('#exc_art_14').is(':checked')) {
						     	
		        	$("#formacion").select2({multiple:true}).next().show();
				   	$('#label_formacion').show();
		        	//addOptions(data, 'select#formacion');
		        } else {
		          	$("#formacion").select2({multiple:true}).next().hide();
			    	$('#label_formacion').hide();
		          
		        }
	if ($('#delegado_gremial').is(':checked')) {

		        	$('#fecha_vigencia').show();
		        	$('#id_sindicato').show();
		        	$('#label_fecha_vigencia').show();
				   	$('#label_sindicato').show();

		        } else {
		          	$('#fecha_vigencia').hide();
		          	$('#id_sindicato').hide();
		          	$('#label_fecha_vigencia').hide();
		          	$('#label_sindicato').hide();
		          
		        }

	$('#exc_art_14').on('click',function () {
			$.ajax({
				url: $base_url+"/Legajos/ajax_art_14_parametricos",
				
				method: "GET"
			})

			.done(function (data) {
				if ($('#exc_art_14').is(':checked')) {
		        	$("#formacion").select2({multiple:true}).next().show();
		        	$('#label_formacion').show();
		        	addOptions(data, 'select#formacion');
		        } else {
		          	$('#label_formacion').hide();
		          	$("#formacion").select2({multiple:true}).next().hide();
		          
		        }
 			});
    });

    $('#delegado_gremial').on('click',function () {
			$.ajax({
				url: $base_url+"/Legajos/ajax_sindicato",
				
				method: "GET"
			})
			
			.done(function (data) {
				if ($('#delegado_gremial').is(':checked')) {
		        	$('#fecha_vigencia').show();
		        	$('#id_sindicato').show();
		        	$('#label_fecha_vigencia').show();
		        	$('#label_sindicato').show();
		        	addOptions(data, 'select#id_sindicato');
		        } else {
		          	$('#fecha_vigencia').hide();
		          	$('#id_sindicato').hide();
		          	$('#label_fecha_vigencia').hide();
		          	$('#label_sindicato').hide();
		          
		        }
 			});
    });
 

/**
 * Formacion - Titulos
 *
 * Se encarga de gestionar los agregar, quitar nuevos items para "Titulo", "Otros Estudios Realizados" y "Otros Conocimientos Especificos"
 * En el caso de "Titulo", como usa un <input type="radio"> es necesario hacer un arreglo para manejar "IDs" independientes en durante la clonacion.
*/
	(function tab_formacion(){
/** @var object $secciones_ids - Los "id" contenedores (padres) de cada seccion, y su respectivo titulo para los botones Agregar/Eliminar */
		$secciones_ids	= {
			'#formacion_titulos'				: 'Titulo',
			'#formacion_otros_estudios'			: 'Estudio',
			'#formacion_otros_conocimientos'	: 'Conocimiento',
			'#formacion_cursos' 				: 'Cursos',
		};

		$.each($secciones_ids, function(_id, _titulo){
			$(document).on('click', _id + ' .btn-add', function(e){
				e.preventDefault();
				var controlForm		= $(this).parents().parents('.combo-input');
				var	currentEntry	= $(this).parents('.entry:first');

				var select2_list	= currentEntry.find('select.activarSelect2');
				Object.keys(select2_list).forEach(function(i){
					if(!Number.isInteger(parseInt(i))){
						return;
					}
					if($(select2_list[i]).hasClass('select2-hidden-accessible')){
						$(select2_list[i]).select2('destroy');
					}
				});
				currentEntry	= currentEntry.clone();

				/**
				 * Setea nuevos "id" a los <input> y su respectivo "for" a los <label>
				 * usando como variable el atributo "data-new-id"
				*/
				if(_id == '#formacion_titulos'){
					var new_id	= parseInt(currentEntry.attr('data-new-id')) + 1;
					currentEntry.attr('data-new-id', new_id);

					controlForm.parents().parents().find('label[for="titulo_checked"]')
						.attr('for', 'titulo_checked_'+new_id)

					controlForm.parents().parents().find('input#titulo_checked')
						.val(new_id)
						.attr('id', 'titulo_checked_'+new_id)
				}

				if(_id == '#formacion_cursos'){
					var new_id	= parseInt(currentEntry.attr('data-new-id')) + 1;
					currentEntry.attr('data-new-id', new_id);

					controlForm.parents().parents().find('label[for="tipo_promocion0"]')
						.attr('for', 'tipo_promocion_' + new_id)

					controlForm.parents().parents().find('input#tipo_promocion0')
						.attr('id', 'tipo_promocion_' + new_id)
						.attr('name', 'empleado_cursos[new][' + new_id + '][tipo_promocion]')
				}
				var newEntry		= $(currentEntry).appendTo(controlForm);
				newEntry.find('.form-control').val('');
				controlForm.find('.entry:not(:last) .btn-add')
					.removeClass('btn-add').addClass('btn-remove')
					.removeClass('btn-info').addClass('btn-default')
					.attr('title', 'Eliminar ' + _titulo)
					.html('<span class="glyphicon glyphicon-minus"></span>');

			 	var block = $('[data-new-id="'+new_id+'"]');
				block.find('select#id_tipo_titulo' + (new_id - 1)).attr('id', ('id_tipo_titulo' + new_id)).attr('name', 'titulo[new][' + new_id+'][id_tipo_titulo]');	
				block.find('select#id_titulo' + (new_id - 1)).attr('id', 'id_titulo' + new_id).attr('name', 'titulo[new][' + new_id + '][id_titulo]');
				block.find('select#id_estado_titulo').attr('name', 'titulo[new][' + new_id + '][id_estado_titulo]');
				block.find('input#fecha').attr('name', 'titulo[new][' + new_id + '][fecha]');	

				block.find('select#nombre_curso' + (new_id - 1)).attr('id', 'nombre_curso' + new_id).attr('name', 'empleado_cursos[new][' + new_id + '][id_curso]');
				block.find('input#fecha').attr('name', 'empleado_cursos[new][' + new_id + '][fecha]');	
				block.find('input#tipo_promocion').attr('value',1);	//que le ponga por default 1 --> grado
				


			}).on('click', _id + ' .btn-remove', function(e){
				$(this).parents('.entry:first').remove();
				e.preventDefault();
				return false;
			});
		});
	})();
	function add_clone(){
		controlFormI = $('#entidades').children().last().parent('.combo-input');
		lastElementI = $('#entidades').children().last();
		currentEntryI = lastElementI.clone();
		new_id = parseInt(lastElementI.attr('data-new-id')) + 1;
		currentEntryI.data('new-id', new_id);
		newEntry = $(currentEntryI).appendTo(controlFormI);
		newEntry.find('.form-control').val('');
		controlFormI.find('.entry:not(:last) .btn-add')
				.removeClass('btn-add').addClass('btn-remove')
				.removeClass('btn-info').addClass('btn-default')
				.attr('title', 'Eliminar Entidad')
				.html('<span class="glyphicon glyphicon-minus"></span>');
		block = $('[data-new-id="' + new_id + '"]');
		block.find('select#id_entidad' + (new_id - 1)).attr('id', ('id_entidad' + new_id));
		block.find('span#tipo_entidad' + (new_id - 1)).attr('id', 'tipo_entidad' + new_id);
		block.find('span#juris' + (new_id - 1)).attr('id', 'juris' + new_id);
		block.find('input#fecha_desde' + (new_id - 1)).attr('id', 'fecha_desde' + new_id);
		block.find('input#fecha_hasta' + (new_id - 1)).attr('id', 'fecha_hasta' + new_id);
		currentEntryI[0]['children'][0]['children'][0]['children'][1]['value'] = '';
		currentEntryI[0]['children'][1]['children'][1]['textContent'] = '';
		currentEntryI[0]['children'][2]['children'][1]['textContent'] = '';
		currentEntryI[0]['children'][3]['children'][0]['children'][1]['children'][0]['value'] = '';
		currentEntryI[0]['children'][4]['children'][0]['children'][1]['children'][0]['value'] = '';
		return currentEntryI;
	}

	(function tab_antiguedad(){
		
		if (sessionStorage.length > 0) {
			$datos = JSON.parse(sessionStorage.getItem("datos"));
			if($datos.length >0) {
				$.each($datos, function (_id, _element){
					if(_element !== null){
						currentEntry 	= $('#entidades').children().last();
						if (currentEntry[0]['children'][0]['children'][0]['children'][1]['value'] !=''){
							currentEntry = add_clone();
						}
						currentEntry[0]['children'][0]['children'][0]['children'][1]['value'] 	= _element[0];
						currentEntry[0]['children'][1]['children'][1]['textContent'] 			= $entidades[_element[0]].nombre_tipo;
						currentEntry[0]['children'][2]['children'][1]['textContent'] 			= $entidades[_element[0]].nombre_juris;
						currentEntry[0]['children'][3]['children'][0]['children'][1]['children'][0]['value'] = _element[1];
						currentEntry[0]['children'][4]['children'][0]['children'][1]['children'][0]['value'] = _element[2];
					}
				});
				sessionStorage.removeItem("datos");
				add_clone();
			}
		} 

		$(document).on('click', '#entidades .btn-add', function(e){
			e.preventDefault();
			var controlForm = $(this).parents().parents('.combo-input'),
				currentEntry = $(this).parents('.entry:first').clone();
			/**
			 * Setea nuevos "id" a los <input> y su respectivo "for" a los <label>
			 * usando como variable el atributo "data-new-id"
			*/
			var new_id = parseInt(currentEntry.attr('data-new-id')) + 1;
			currentEntry.attr('data-new-id', new_id);
			var newEntry = $(currentEntry).appendTo(controlForm);
			newEntry.find('.form-control').val('');
			controlForm.find('.entry:not(:last) .btn-add')
				.removeClass('btn-add').addClass('btn-remove')
				.removeClass('btn-info').addClass('btn-default')
				.attr('title', 'Eliminar Entidad')
				.html('<span class="glyphicon glyphicon-minus"></span>');
			var block = $('[data-new-id="' + new_id + '"]');
			block.find('select#id_entidad' + (new_id - 1)).attr('id', ('id_entidad' + new_id));
			block.find('span#tipo_entidad' + (new_id - 1)).attr('id', 'tipo_entidad' + new_id);
			block.find('span#juris' + (new_id - 1)).attr('id', 'juris' + new_id);
			block.find('input#fecha_desde' + (new_id - 1)).attr('id', 'fecha_desde' + new_id);
			block.find('input#fecha_hasta' + (new_id - 1)).attr('id', 'fecha_hasta' + new_id);

		}).on('click', '#entidades .btn-remove', function(e){
			e.preventDefault();
			$this.parents('.entry:first').remove();
			return false;
		});

		function url_redirect(options) {
			var $form = $("<form />");
			$form.attr("action",options.url);
			$form.attr("method",options.method);

		for (var data in options.data)
			$form.append('<input type="hidden" name="'+data+'" id="'+data+'" value="'+options.data[data]+'" />');
			$("body").append($form);
			$form.submit();
		}

		$(document).delegate('.entidad', 'change', function(){
			var id_entidad	= $(this).val();
			if (id_entidad === '0') {
				sessionStorage.removeItem("datos");
				url_alta = $base_url + "/otros_organismos/alta";
				bloque = $('.nav-item.active>a');
				id_bloque = bloque.data('id');
				urlr = $base_url + "/legajos/gestionar";
				cuit = $('#div_agente>strong.ct')[id_bloque].innerText;
				select_tab = 'tab_' + (bloque.attr('aria-controls')).substring(6);
				var _elements = $('[data-tipo="new"]');
				var data = new Array();
				for (var index = 0; index < _elements.length; index++) {
					var _element = _elements[index]; 
					if (_element['children'][0]['children'][0]['children'][1]['value'] != 0) {						
						data.push([ _element['children'][0]['children'][0]['children'][1]['value'],
									_element['children'][3]['children'][0]['children'][1]['children'][0]['value'], 
									_element['children'][4]['children'][0]['children'][1]['children'][0]['value']
								  ]);  
						
					}
				}
				sessionStorage.setItem('datos', JSON.stringify(data));
				url_redirect({ url: url_alta, method: "post", data: { "id_bloque": id_bloque, "urlr": urlr, "cuit": cuit, "select_tab": select_tab } });
			}
			if(isNaN(parseInt(id_entidad))){
			return;
			}
			var entidad = $entidades[id_entidad];
			var new_id = $(this).parent().parent().parent().data('new-id');
			if (entidad !== undefined) { 
				$('#tipo_entidad' + new_id).html(entidad.nombre_tipo);
				if (isNaN(entidad.nombre_juris)) {
				$('#juris'+new_id).html(entidad.nombre_juris);
				}else{
				$('#juris'+new_id).html('');
				}
			}
		});
	})();


	(function documentos(){
		$(document).on('click', '#btn_documentos_ver', function(e){
			e.preventDefault();
			$('#bloque_documentos_ver').val($('li.active a').data('id'));
			$('#form_documentos_ver').submit();
		});
	})();




	$(function() {
      $('.image-editor').cropit('imageSrc', $("#foto_bd").val());

      $('#gestionar_form').click(function(){
		var imageData = $('.image-editor').cropit('export', {type: 'image/jpeg', originalSize: true});
        $('#foto').val(imageData);
      });

    });

    $("#familia_puestos").select2();
	$("#nombre_puesto").select2();
	$('.container').delegate('select.activarSelect2', 'mouseover', function (e) {
		if($(this).hasClass('select2-hidden-accessible') || $(this).is(':disabled')){
			return;
		}
		$(this).select2();	
	});
    

	$('#familia_puestos').on('change', function($e){
		$.ajax({
			url: $base_url+"/Puestos/ajax_get_puesto",
			data: {
				familia_puestos:	$('select#familia_puestos').val(),
			},
			method: "GET"
		})
		.done(function (data) {
			if(data.subfamilias !== undefined) {
			$select= $('select#nombre_puesto');
			$select.html('<option value="">Seleccione</option>');

			$.each(data.subfamilias, function(key, value){
				$obj = $('<optgroup>', {
					label: value.nombre,
					id: 'group'+key

					});
				$select.append($obj);

				$select.append(addOptions(value.puestos, '#group'+key ,true));

				});
			}
		});
	});
});
