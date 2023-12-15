var filtros_dataTable = null;

function objectToArray(obj) {
	var result = [];
	var keys = Object.keys(obj);
	keys.forEach(function (key) {
		result.push(obj[key]);
	});
	return result;
}
$(document).ready(function () {
	$('#id_usuario').select2();
	$('#fecha_operacion_desde').data("DateTimePicker").destroy();
	$("#fecha_operacion_desde").datetimepicker({
		maxDate: moment(),
		format: 'DD/MM/YYYY HH:mm:ss'
	})
	$('#fecha_operacion_hasta').data("DateTimePicker").destroy();
	$("#fecha_operacion_hasta").datetimepicker({
		maxDate: moment(),
		format: 'DD/MM/YYYY HH:mm:ss'
	})

	var tabla = $('#dataTable').DataTable({
		processing: true,
		serverSide: true,
		responsive: true,
		searchDelay: 1200,
		ajax: {
			url: $base_url + '/auditorias/index',
			data: function (d) {
				filtros_dataTable = $.extend({}, d, {
					id_usuario: $('#id_usuario').val(),
					fecha_operacion_desde: $('#fecha_operacion_desde').val(),
					fecha_operacion_hasta: $('#fecha_operacion_hasta').val(),
				});
				return filtros_dataTable;
			}
		},
		order: [],
		columns: [
			{
				title: 'Fecha Operacion',
				className: 'text-left',
				width: '150',
				name: 'fecha_operacion',
				data: 'fecha_operacion',
				orderable: false,
			},
			{
				title: 'Operador',
				className: 'text-left',
				width: '180',
				name: 'usuario_nombre',
				data: 'usuario_nombre',
				orderable: false,
			},
			{
				title: 'Tipo',
				className: 'text-center',
				name: 'tipo_registro',
				data: 'tipo_registro',
				orderable: false,
			},
			{
				title: 'CUIT',
				className: 'text-center',
				name: 'empleado_cuit',
				data: 'empleado_cuit',
				orderable: false,
			},
			{
				title: 'Nombre Apellido',
				className: 'text-left',
				width: '250',
				name: 'empleado_nombre',
				data: 'empleado_nombre',
				orderable: false,
			},
			{
				title: 'Accion',
				className: 'text-center',
				name: 'tipo_operacion',
				data: 'tipo_operacion',
				orderable: false,
				render: function(data, type, row, obj){
					return '<span class="capitalize">' + data +'</span>';
				}
			},
			{
				title: 'Acciones',
				data: 'acciones',
				name: 'acciones',
				width: '50',
				className: 'text-center',
				orderable: false,
				render: function (data, type, row, obj) {
					var $html = '';
					$html += '<div class="btn-group btn-group-sm auth">'
					$html += '<a href="" data-pesquisa="' + row.tabla_nombre + '"';
					$html += ' data-id="' + row.id_tabla + '"';
					$html += ' data-usuario_nombre="' + row.usuario_nombre + '"';
					$html += ' data-fecha_operacion="' + row.fecha_operacion+'"';
					$html += ' data-empleado_cuit="' + row.empleado_cuit+'"';
					$html += ' data-empleado_nombre="' + row.empleado_nombre+'"';
					$html += ' data-tipo_operacion="' + row.tipo_operacion+'"';
					$html += ' class="detallepesquisa" data-toggle="tooltip" title="Detalle Pesquisa" ><i class="fa fa-eye"></i></a>';
					$html += '</div>';
					return $html;
				}
			},
		]
	});

	function update() {
        tabla.draw();
	}
	$('.fecha').on('dp.change', function (e) { update(); });
	$('#id_usuario').on('change', function(){ update(); })

	$('div').delegate('.detallepesquisa', 'click', function(e){
		e.preventDefault();
		e.stopImmediatePropagation();
		if ($(this).parent().attr('class') == 'btn-group btn-group-sm auth') {
			$('[role="row"]').removeClass('auth_row');
			$(this).parent().parent().parent().addClass('auth_row');
		}
		var fecha_operacion	= $(this).data('fecha_operacion');
		var empleado_cuit	= $(this).data('empleado_cuit');
		var empleado_nombre	= $(this).data('empleado_nombre');
		var usuario_nombre	= $(this).data('usuario_nombre');
		var tipo_operacion	= $(this).data('tipo_operacion');
		$.ajax({
			url: $base_url + "/auditorias/json_detalle_pesquisa/" + $(this).data('id'),
			data: {
				pesquisa: $(this).data('pesquisa'),
			},
			method: "GET",
		})
		.done(function (data) {
			data = data.data;
			$($('div.alert-warning')[0]).html('');
			if (data.consulta.length == 0){return;}
			$('#descripcion-pesquisa').remove();

			var yourFather = $('<div id="descripcion-pesquisa" class="row alert alert-warning fade in alert-dismissable"><div class="text-right"><button id="volver-arriba" style="background:#8a6d3b;color:#fff;border-radius:5px;" >arriba</button></div></div>');
			var _html = '';
			_html += '<strong>Accion: </strong><span class="label label-warning capitalize">' + tipo_operacion + '</span><br />';
			_html += '<strong>Fecha Operacion: </strong>' + fecha_operacion + ' <b>|</b> ';
			_html += '<strong>Operador: </strong>' + usuario_nombre + ' <b>|</b> ';
			_html += '<strong>Empleado: </strong>' + empleado_nombre + ' <b>|</b> ';
			_html += '<strong>CUIT: </strong>' + empleado_cuit;
			yourFather.append($('<div class="col-xs-12"  style="padding:5px;border:1px solid #8a6d3b;border-radius:5px;">'+_html+'</div>'));

			var consulta		= $('<div class="col-xs-6"></div>');
			var anterior		= $('<div class="col-xs-6"></div>');

			consulta.append('<h3>Valores Auditados: </h3>');
			data.consulta = objectToArray(data.consulta);
			data.consulta.forEach(function (field) {
				var valor = field.valor;
				try {
					param = JSON.parse(field.map);
					valor = param[field.valor];
				} catch(_e) {
					valor = field.valor;
					try {
						valor = $parametricos[field.map][field.valor].nombre;
					} catch(_e) {
						valor = field.valor;
					}
				}
				if (valor == null){
					valor	= '--';
				}
				var _label	= 'info';
				if (field.flag == 'modificado'){
					_label	= 'success';
				}

				if (Number.isNaN(parseInt(field.solapa))){
					var _solapa = field.solapa;
				} else {
					var _solapa = $solapas[field.solapa].nombre;
				}
				var _html	= '';
				_html += '<span class="label label-'+_label+'">' + _solapa + '</span>';
				// _html += '<br />';
				_html += '<strong> ' + field.titulo + ' :</strong>';
				_html += '<br />';
				_html += '<span>'+valor+'</span>';

				consulta.append($('<p>'+_html+'</p>'));
			});
			if (tipo_operacion == 'alta'){
				yourFather.append(consulta);
				yourFather.insertAfter($('#dataTable_wrapper').parents()[1]);
				return;
			}
			anterior.append('<h3>Anterior : </h3>');
			data.anterior = objectToArray(data.anterior);
			var ver_ant = '';
			if(data.anterior.length <= 0){
				anterior.append('<p><i>No se cuenta con datos anteriores para comparar. <br /> O se realizo una baja automatica del registro para dar lugar a un <b>Alta</b></i></p>');
			}
			if (data.ver_anterior.id !== '--' ){

				ver_ant =   '<br>'+
							'<br>'+
							'<div class="auth">'+
								'<button data-pesquisa="' + data.ver_anterior.pesquisa +'" data-id="'+data.ver_anterior.id+'" '+ 
									'data-usuario_nombre="'+data.ver_anterior.usuario+'" '+
									'data-fecha_operacion="'+data.ver_anterior.fecha+'" '+
									'data-empleado_cuit="' +empleado_cuit+'" '+
									'data-empleado_nombre="'+empleado_nombre+'" '+
									'data-tipo_operacion="'+data.ver_anterior.tipo_operacion+'" class="detallepesquisa" '+
									'data-toggle="tooltip" title="" data-original-title="Detalle Pesquisa">'+
									'VER ESTE REGISTRO <i class="fa fa-level-up" aria-hidden="true"></i>'+
								'</button>'+
							'</div>';
			}
			data.anterior.forEach(function (field) {
				var valor = field.valor;
				try {
					param = JSON.parse(field.map);
					valor = param[field.valor];
				} catch(_e) {
					valor = field.valor;
					try {
						valor = $parametricos[field.map][field.valor].nombre;
					} catch(_e) {
						valor = field.valor;
					}
				}
				if (valor == null) {
					valor = '--';
				}

				if (Number.isNaN(parseInt(field.solapa))) {
					var _solapa = field.solapa;
				} else {
					var _solapa = $solapas[field.solapa].nombre;
				}
				var _html = '';
				_html += '<span class="label label-danger">' + _solapa + '</span>';
				// _html += '<br />';
				_html += '<strong> ' + field.titulo + ' :</strong>';
				_html += '<br />';
				_html += '<span>' + valor + '</span>';
				anterior.append($('<p>' + _html + '</p>'));
			});
			yourFather.append(consulta).append(anterior).append(ver_ant);
			yourFather.insertAfter($('#dataTable_wrapper').parents()[1]);
		})
		.fail(function(data){
            var data = data.responseJSON;

            $('#descripcion-pesquisa').remove();
            var yourFather = $('<div id="descripcion-pesquisa" class="row alert alert-warning fade in alert-dismissable">'+data.mensajes[0]+'</div>');
            yourFather.insertAfter($('#dataTable_wrapper').parents()[1]);
		})
		.always(function(){
			$('html, body').animate({
				scrollTop: $("#descripcion-pesquisa").offset().top
			}, 400);
		});
	});
    /**
     * Consulta al servidor los datos y redibuja la tabla
     * @return {Void}
    */
	function update() {
		tabla.draw();
	}

    /**
     * Acciones para los filtros, colapsar/mostrar, actualizar vista
    */
	$('#estado').on('change', update);
	// $('#id_dependencia, #situacion_revista, #modalidad_contratacion').select2();
	var $collapseFiltros		= $('#collapseFiltros');
	var $collapseFiltrosCaret	= $("#collapseFiltros_caret");
	$collapseFiltros.on('hide.bs.collapse', function () {
		$collapseFiltrosCaret.removeClass('fa-caret-down').addClass('fa-caret-right')
	});
	$collapseFiltros.on('show.bs.collapse', function () {
		$collapseFiltrosCaret.removeClass('fa-caret-right').addClass('fa-caret-down')
	});


	$('div').delegate('#volver-arriba', 'click', function (e) {
		e.preventDefault();
		$('html, body').animate({ scrollTop: 200 }, 'slow');
		//$('#descripcion-pesquisa').addClass('animate').display('none');
		setTimeout(() => { $('#descripcion-pesquisa').css('opacity', '-1'); $('#descripcion-pesquisa').remove(); }, 500);
		return false;
	});

});