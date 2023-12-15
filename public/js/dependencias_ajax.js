$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    var tabla = $('#dependencias').DataTable({
        language: {
            url: $endpoint_cdn+'/datatables/1.10.12/Spanish_sym.json',
            decimal: ',',
            thousands: '.'
        },
        processing: true,
        serverSide: true,
        responsive: true,
        searchDelay: 1200,

        ajax: {
            url:$url_base + '/index.php/dependencias/ajax_lista_dependencias',
            contentType: "application/json",
            data: function (d) {

            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
            { targets: 0, width: '50%' },
            { targets: 1, width: '15%' },
            { targets: 2, width: '20%' },
            { targets: 3, width: '15%'}
        ],
        order: [[0, 'asc']],
        columns: [
            {
                title: 'Dependencia',
                className: 'text-left',
                name: 'nombre',
                data: 'nombre',

            },
            {
                title: 'Fecha Desde',
                className: 'text-center',
                name: 'fecha_desde',
                data: 'fecha_desde',
            },      
            {
                title: 'Nivel',
                className: 'text-left',
                name: 'nombre_nivel',
                data: 'nombre_nivel',
            },
            {
                title: 'Acciones',
                className: 'text-center',
                name: 'accion',
                orderable: false,
                data: 'acciones',
                render: function (data, type, row) {
                  if(row.id_usuario == row.id_logueado){
                      var  $html = '<div class="btn-group btn-group-sm">';
                        $html += '<a href="'+$url_base+'/index.php/dependencias/modificacion/' +row.id + '"  class="btn btn-link btn-sm" data-toggle="tooltip"    data-id="'+row.id+'" data-bl="'+ row.id_bloque + '" title="Modificar Dependencia" id= "modificar_dependencia"><i class="fa fa-edit"></i></a>';                    
                        $html += '</div>';
                        $html += '<a href="'+$url_base+'/index.php/dependencias/baja/' +row.id + '"  class="btn btn-link btn-sm" data-toggle="tooltip"    data-id="'+row.id+'" data-bl="'+ row.id_bloque + '" title="Eliminar Dependencia" id= "eliminar_dependencia"><i class="fa fa-trash"></i></a>';                    
                        $html += '</div>';
                        return $html;
                    }
                }

            }
        ]
    });
    $.fn.dataTable.moment('DD/MM/YYYY');
    /**
     * Consulta al servidor los datos y redibuja la tabla
     * @return {Void}
    */
    function update() {
        tabla.draw();
    }


}); 