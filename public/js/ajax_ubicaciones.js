$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    var tabla = $('#ubicaciones').DataTable({
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
            url:$url_base + '/index.php/ubicaciones/ajax_lista_ubicaciones',
            contentType: "application/json",
            data: function (d) {

            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
            { targets: 0, width: '70%' },
            { targets: 1, width: '10%' },
            { targets: 2, width: '10%' },
            { targets: 3, width: '10%'}
        ],
        order: [[0, 'asc']],
        columns: [
            {
                title: 'Edificio',
                className: 'text-left',
                name: 'nombre',
                data: 'nombre',

            },
            {
                title: 'Piso',
                className: 'text-center',
                name: 'piso',
                data: 'piso',
            },      
            {
                title: 'Oficina',
                className: 'text-center',
                name: 'oficina',
                data: 'oficina',
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
                        $html += '<a href="'+$url_base+'/index.php/ubicaciones/modificacion/' +row.id + '"  class="btn btn-link btn-sm" data-toggle="tooltip"    data-id="'+row.id+'" data-bl="'+ row.id_bloque + '" title="Modificar Ubicación" id= "modificar_ubicacion"><i class="fa fa-edit"></i></a>';                    
                        $html += '</div>';
                        $html += '<a href="'+$url_base+'/index.php/ubicaciones/baja/' +row.id + '"  class="btn btn-link btn-sm" data-toggle="tooltip"    data-id="'+row.id+'" data-bl="'+ row.id_bloque + '" title="Eliminar Ubicación" id= "eliminar_ubicacion"><i class="fa fa-trash"></i></a>';                    
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