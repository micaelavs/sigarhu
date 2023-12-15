$(document).ready(function () {
   
    var _table  = $('#tabla').DataTable({
        language: {
	    url: $endpoint_cdn+'/datatables/1.10.12/Spanish_sym.json',
            search: '_INPUT_',
            searchPlaceholder: 'Ingrese búsqueda'
        },
     processing: true,
        serverSide: true,
        responsive: true,
        searchDelay: 1200,

        ajax: {
            url:$url_base + '/index.php/puestos/ajax_puesto',
            contentType: "application/json",
            data: function (d) {
            }
        },
        info: true,
        bFilter: true,
        autoWidth: false,       
        columnDefs: [
        { targets: 0, width: '20%',className: 'text-left' },
        { targets: 1, width: '30%',className: 'text-left' },
        { targets: 2, width: '10%',orderable: false, },
        ],
        order: [1,'asc'],
        columns: [
            {
                title: 'Puesto',
                className: 'text-left',
                name: 'puesto',
                data: 'nombre',

            },
            {
                title: 'Subfamilia',
                className: 'text-left',
                name: 'subfamilia',
                data: 'subfamilia',

            },
            {
                title: 'Acciones',
                className: 'text-center',
                name: 'accion',
                orderable: false,
                data: 'acciones',
                render: function (data, type, row) { 
                  if(row.id_usuario == row.id_logueado){
                      var   $html = '<div class="acciones">';                  
                            $html += '<a href="'+$url_base+'/index.php/puestos/modificacion/' +row.id + '" title="Modificación de Puesto" data-toggle="modal"" data-id="'+row.id+'" id= "modificar_puesto"><i class="fa fa-pencil"></i></a>'
                            $html += ' ';
                            $html += '<a href="'+$url_base+'/index.php/puestos/baja/' +row.id + '" title="Eliminar Puesto" data-toggle="modal"" data-id="'+row.id+'" id= "eliminar_puesto"><i class="fa fa-trash"></i></a>'
                            $html += '</div>';
                        return $html;
                    }
                }
            }
        ]        
    });

}); 
