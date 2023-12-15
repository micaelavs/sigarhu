$(document).ready(function () {

	$('.tabla_listado_simulacion').ready(function () {
		    var _table  = $('.tabla_listado_simulacion').DataTable({
		        language: {
		            search: '_INPUT_',
		            searchPlaceholder: 'Ingrese búsqueda'
		        },
		        autoWidth: false,
		        info: false,
		        bFilter: true,
		        columnDefs: [
			        { targets: 0, orderable: true, visible: false},
			        { targets: 1, orderable: false, visible: false},
			        { targets: 2, orderable: false, visible: false},
			        { targets: 3, orderable: false, visible: true},
			        { targets: 4, orderable: false, visible: true},
			        { targets: 5, orderable: false, visible: false},
			        { targets: 6, orderable: false, visible: true },
			        { targets: 7, orderable: false, width: '16%', visible: false},
			        { targets: 8, orderable: false },
			        { targets: 9, orderable: true },
			        { targets: 10, orderable: false},
			        { targets: 11, orderable: false},
			        { targets: 12, orderable: false},
			        { targets: 13, orderable: false, visible: false},
			        { targets: 14, orderable: false},
			        { targets: 15, orderable: false},
			        { targets: 16, orderable: false},
			        { targets: 17, orderable: false, visible: false, width: '100px', title: 'Accion', render: function(){return '';}},
			        { targets: 18, orderable: false, visible: false},
			        { targets: 19, orderable: false, visible: false},
		        ],
		        order: [[9,'asc']],
		        rowGroup: {	
	            	dataSrc: function(row){
	            		grupo_incremental	= row[0];
	            		cuit				= row[1];
	            		nombre_apellido		= row[2];
						ultima_promocion	= row[7];
	            		// nivel_actual		= row[3];
	            		// grado_actual		= row[4];
	            		// tramo_actual		= row[6];
	            		aplica_promocion	= row[17];
						id_empleado			= row[19];
						motivo				= row[18];

	            		texto	= '<div class="row"><div class="col-md-10 text-center">';
	            		texto	+= '<span class="control-label"> Ultima Promoción: <strong>'+ultima_promocion+'</strong></span>&nbsp;&nbsp;<strong>|</strong>';
	            		texto	+= '<span class="control-label"> Grupo: <strong>'+grupo_incremental+'</strong></span>&nbsp;&nbsp;<strong>|</strong>';
	            		texto	+= '<span class="control-label"> Cuit: <strong>'+cuit+'</strong></span>&nbsp;&nbsp;<strong>|</strong>';
	            		texto	+= '<span class="control-label"> Nombre: <strong>'+nombre_apellido+'</strong></span>&nbsp;&nbsp;<strong>|</strong>';
	            		// texto	+= '<span class="control-label"> Nivel: <strong>'+nivel_actual+'</strong></span>&nbsp;&nbsp;<strong>|</strong>';
	            		// texto	+= '<span class="control-label"> Grado: <strong>'+grado_actual+'</strong></span>&nbsp;&nbsp;<strong>|</strong>';
						// texto	+= '<span class="control-label"> Tramo: <strong>'+tramo_actual+'</strong></span>&nbsp;&nbsp;<strong>|</strong>';
						texto	+= '<p>'
						texto	+= '<span class="label label-default"> Motivo: <strong>'+motivo+'</strong></span>';
						texto	+= '</p>'
	            		texto	+= '</div>'
	            		if(aplica_promocion == 'true'){
							texto	+= '<div class="col-md-2 text-center"><br/>';
	            			texto	+= '<a class="btn btn-success btn-xs" href="'+$base_url+'/Promocion_grados/alta/'+id_empleado+'/'+grupo_incremental+'" data-toggle="tooltip" data-placement="top" title="Aplica Promocion" data-toggle="modal">Aplica&nbsp; <i class="fa fa-pencil"></i></a>';
	            			texto	+= '</div>';
	            		} else {
							texto	+= '<div class="col-md-2 text-center"><br/>';
							texto	+= '<a class="btn btn-warning btn-xs" href="'+$base_url+'/Promocion_grados/alta/'+id_empleado+'/'+grupo_incremental+'" data-toggle="tooltip" data-placement="top" title="No Aplica Promocion" data-toggle="modal">No Aplica&nbsp; <i class="fa fa-eye"></i></a>';
	            			texto	+= '</div>';
	            		}
	            		texto	+= '</div>';
	            		return texto;
	            	},      	
        		},
		      
	    	});
		});


});



