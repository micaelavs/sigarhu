/**
 * Si la variable '$data_table_init' esta seteada globalmente, se ejecuta automaticamente esta funcion.
 * Recorre todos los elementos TH de la tabla con el attributo class designado. Usando los attributos data-[target,visible,width,etc] construye la configuracion para inicializar el DataTable.
 *
 * Ejemplo disponible en: "historial_evaluacion.php"
 * @param string	tabla_class_name	- atributo class pasado desde PHP
 * @return void
*/
function iniciarDataTable(tabla_class_name){
	if(!(typeof $('.'+tabla_class_name) !== 'undefined' && $('.'+tabla_class_name).length > 0)){
		return;
	}
	if($.fn.dataTable.isDataTable('.'+tabla_class_name) !== false){
		return;
	}
	dataAttributes	= ['width', 'orderable', 'visible', 'type', 'title', 'searchable', 'name', 'className'];
	ths				= $('.'+tabla_class_name).find('th');
	ths_aux			= [];
	Object.keys(ths).forEach(function(v){
		if(!isNaN(parseInt(v))){
			ths_aux[v]	= $(ths[v]);
		}
	});
	ths				= ths_aux;
	delete ths_aux;
	setting			= {
		columnDefs: [],
		order: [],
	};
	columnDefs		= [];

	ths.forEach(function(element, key) {
		aux	= {};
		if(typeof $(element).data('target') !== 'undefined'){
			aux.targets	= $(element).data('target');

			dataAttributes.forEach(function(attr){
				if(typeof $(element).data(attr) !== 'undefined'){
					aux[attr]	= $(element).data(attr);
				}
			});
		} else {
			aux.targets	= key;
			aux.visible	= false;
		}
		if(typeof aux.orderable !== 'undefined' && aux.orderable == true){
			setting.order.push([key, 'desc']);
		}
		setting.columnDefs.push(aux);
	});
	$('.'+tabla_class_name).DataTable(setting);
	$.fn.dataTable.moment('DD/MM/YYYY');
}

$(document).ready(function () {
	$(document).delegate('[data-toggle="tooltip"]', 'mouseover', function(){
		$(this).tooltip({ html : true });
	});
	$(document).delegate('[data-toggle="popover"]', 'mouseover', function(){
		$(this).popover({ html : true });
	});

	$("a[href='?c=base&a=manual']").attr('target', '_blank');

	$(".fecha.libre").datetimepicker({
	  format: 'DD/MM/YYYY'
	})
	$(".fecha").datetimepicker({
      maxDate: 'now',
      format: 'DD/MM/YYYY'
    })
	if($(".filestyle").lenght != 'undefined' && typeof $.fn.fileinput != 'undefined'){
		$(".filestyle").fileinput({
			language: 'es',
			browseLabel: '',
			showRemove: false,
			showUpload: false,
			previewFileIcon: '<i class="glyphicon glyphicon-eye"></i>',
			previewFileIconClass: 'file-icon-4x'
		});
	}
/**
 * Opciones por defecto para todas las implementaciones de DataTable()
*/
	if (typeof($.fn.dataTable) !== 'undefined') {
		$.extend( $.fn.dataTable.defaults, {
			language: {
				url: $endpoint_cdn+'/datatables/1.10.12/Spanish_sym.json',
				decimal: ',',
				thousands: '.',
				search: '_INPUT_',
				searchPlaceholder: 'Ingrese búsqueda'
			},
			info: true,
			buttons: [],
			order: [[0, 'desc']],
			ordering:  true,
			searching: true,
			columnDefs: [
				{targets: 3, searchable: false, orderable: false}
			]
		});
		if(typeof $data_table_init !== 'undefined'){
			iniciarDataTable($data_table_init);
		}
	}
	if(typeof $data_table_init !== 'undefined' && typeof($.fn.dataTable) !== 'undefined'){
		$('.'+$data_table_init).DataTable();
	}
	
	$('.container').delegate('select.activarSelect2', 'mouseover', function (e) {
		if($(this).hasClass('select2-hidden-accessible') || $(this).is(':disabled')){
			return;
		}
		$(this).select2();	
	});
});

/**
 * Llena los elementos de una etiqueta <select> pasandole un array.
 * Se encarga de limpiar el contenido antes del llenado o mantener los ids previamente seleccionados.
 *
 * @param boolen	$not_clean	- Si esta en true, mantiene el "value" preseleecionado, ideal para articular con PHP.
 * @param string	$dom_select	- valor usado para seleccionar el elemento dom. E.j.: 'select#id_situacion_revista'
 * @param array		$options	- Opciones para el las etiquetas select con formato ['id' => '', nombre => '', borrado => '']
 * @return JQuery
*/
	function addOptions($options, $dom_select, $not_clean=false){
		$obj				= $($dom_select);
		if(typeof $obj[0] == 'undefined' || typeof $obj[0].nodeName == 'undefined') return $obj;
		if(! ($obj[0].nodeName	== 'SELECT'  || $obj[0].nodeName  == 'OPTGROUP')) return $obj;
		$value_pre_selected	= false;
		if($obj.val() != '' && $not_clean){
			$value_pre_selected = $obj.val();
		}
// Limpiar etiquetas <Select> antes de llenarlas
		$obj.html('');
		if($obj[0].nodeName  == 'SELECT'){
			$obj.append($('<option>', {
				value: '',
				text : 'Seleccione'
			}));
		}
// Llenar etiquetas <Select>
		$.each($options, function (i, item) {
			$_options	= {
				value: item.id,
				text : item.nombre,
			};
			if(item.borrado != '0'){
				$_options.disabled	= 'disabled';
			}
			if(Array.isArray($value_pre_selected)) {
				if($.inArray(item.id, $value_pre_selected) != -1){
					$_options.selected	= 'selected';
				}
			}else{
				if(item.id	== $value_pre_selected){
					$_options.selected	= 'selected';
				}
			}
			$obj.append($('<option>', $_options));
		});
		return $obj;
	}


// Boton Volver
	$(document).delegate('.volver_legajo', 'click', function(e){
		e.preventDefault();
		var bloque_id	= $(this).data('bloque');
		var data_ref	= $(this).data('ref');

		var formulario			= $('<form/>', {id:'form_legajo_'+bloque_id , action : data_ref, method : 'POST'});
		var input_hidden_bloque = $('<input />', { name: 'id_bloque', type: 'hidden', value: bloque_id });
		formulario.append(input_hidden_bloque);
		$(this).after(formulario);
		$('form#form_legajo_'+bloque_id).submit();
	});


/**
 * Si el ID de provincia es `false` o `null` devuelve el listado de Provincias. Si es un ID devuelve el listado de Localidades correspondiente a la provincia.
 *
 * Uso normal `new ApiUbicaciones().done(function(data){});`
 *
 * @param {int|null|false}	id_provincia	- ID de la Provincia
 * @returns {JQuery.ajax}
 */
var ApiUbicaciones = function (id_provincia) {
	$data_param	= {};
	if(typeof id_provincia === 'undefined' || id_provincia == null || id_provincia == false ){
		$data_param.id_pais	= this.id_pais;
	} else {
		$data_param.id_region = id_provincia;
	}
	return this.consulta($data_param);
};
/**
 * @var {String} - ID de Pais por Defecto. Argentina.
 * @private
 */
ApiUbicaciones.prototype.id_pais	= 'AR';
/**
 * Realiza la consulta
 * @param {Object} $data_param 
 * @private
 * @returns 
 */
ApiUbicaciones.prototype.consulta = function ($data_param) {
    return $.ajax({
		url: $base_url+"/Legajos/ajax_ubicaciones",
		data: $data_param,
		method: "GET"
	});
};