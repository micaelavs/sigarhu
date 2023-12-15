$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    var tabla = $('#presupuesto_jurisdicciones').DataTable({
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
            url:$url_base + '/index.php/presupuestos/ajax_presupuesto_jurisdicciones',
            contentType: "application/json",
            data: function (d) {

            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
            { targets: 0, width: '5%' },
            { targets: 1, width: '65%' },
            { targets: 2, width: '5%' },
        ],
        order: [[0, 'asc']],
        columns: [
            {
                title: 'Código',
                className: 'text-left',
                name: 'codigo',
                data: 'codigo',

            },
            {
                title: 'Jurisdicción',
                className: 'text-left',
                name: 'nombre',
                data: 'nombre',
            },     
            {
                title: 'Acciones',
                className: 'text-left',
                name: 'accion',
                orderable: false,
                data: 'acciones',
                render: function (data, type, row) {
                  if(row.id_usuario == row.id_logueado){
                      var  $html = '<span class="acciones">';
                        $html += '<a href="'+$url_base+'/index.php/presupuestos/jurisdicciones/' +row.id + '" data-toggle="tooltip" title="Modificar Presupuesto" id= "modificar_presupuesto"><i class="fa fa-pencil"></i></a>';                    
                        $html += ' ';
                        $html += '<a href="'+$url_base+'/index.php/presupuestos/baja_jurisdicciones/' +row.id + '" data-toggle="tooltip" title="Eliminar Presupuesto" id= "eliminar_presupuesto"><i class="fa fa-trash"></i></a>';                    
                        $html += '</span';
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