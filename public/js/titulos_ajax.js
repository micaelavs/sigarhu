$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    var tabla = $('#titulos').DataTable({
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
            url:$url_base + '/index.php/titulos/ajax_lista_titulos',
            contentType: "application/json",
            data: function (d) {

            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
            { targets: 0, width: '15%' },
            { targets: 1, width: '15%' },
            { targets: 2, width: '50%' },
            { targets: 3, width: '15%'}
        ],
        order: [[2, 'asc']],
        columns: [
        	  {
                title: 'Tipo Título',
                className: 'text-left',
                name: 'nombre_tipo',
                data: 'nombre_tipo',

            },
            {
                title: 'Abreviatura',
                className: 'text-center',
                name: 'abreviatura',
                data: 'abreviatura',
            },
            {
                title: 'Título',
                className: 'text-left',
                name: 'nombre',
                data: 'nombre',

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
                        $html += '<a href="'+$url_base+'/index.php/titulos/modificacion/' +row.id + '"  class="btn btn-link btn-sm" data-toggle="tooltip"  data-id="'+row.id+'" data-bl="'+ row.id_bloque + '" title="Modificar Título" id= "modificar_titulo"><i class="fa fa-edit"></i></a>';                    
                        $html += '</div>';
                        $html += '<a href="'+$url_base+'/index.php/titulos/baja/' +row.id + '"  class="btn btn-link btn-sm" data-toggle="tooltip"    data-id="'+row.id+'" data-bl="'+ row.id_bloque + '" title="Eliminar Título" id= "eliminar_titulo"><i class="fa fa-trash"></i></a>';                    
                        $html += '</div>';
                        return $html;
                    }
                
                }

            }
        ]
    });

    /**
     * Consulta al servidor los datos y redibuja la tabla
     * @return {Void}
    */
    function update() {
        tabla.draw();
    }


}); 