$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    if ($('#presupuesto_proyectos').length) {
        var tabla = $('#presupuesto_proyectos').DataTable({
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
                url:$url_base + '/index.php/presupuestos/ajax_presupuesto_proyectos',
                contentType: "application/json",
                data: function (d) {

                }
            },
            info: true,
            bFilter: true,
            columnDefs: [
                { targets: 0, width: '5%' },
                { targets: 1, width: '40%' },
                { targets: 2, width: '25%' },
                { targets: 3, width: '25%' },
                { targets: 4, width: '5%' },
            ],
            order: [[1, 'asc']],
            columns: [
                {
                    title: 'Código',
                    name: 'codigo',
                    data: 'codigo',

                },
                {
                    title: 'Proyecto',
                    className: 'text-left',
                    name: 'nombre',
                    data: 'nombre',
                },
                {
                    title: 'Programa',
                    className: 'text-left',
                    name: 'programa',
                    data: 'programa',

                },
                {
                    title: 'Subprograma',
                    className: 'text-left',
                    name: 'subprograma',
                    data: 'subprograma',

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
                            $html += '<a href="'+$url_base+'/index.php/presupuestos/proyecto/' +row.id + '" data-toggle="tooltip" title="Modificar Presupuesto" id= "modificar_presupuesto"><i class="fa fa-pencil"></i></a>';                    
                            $html += ' ';
                            $html += '<a href="'+$url_base+'/index.php/presupuestos/baja_proyecto/' +row.id + '" data-toggle="tooltip" title="Eliminar Presupuesto" id= "eliminar_presupuesto"><i class="fa fa-trash"></i></a>';                    
                            $html += '</span>';
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
    }

    $('#id_programa').on('change', function(){
        $.ajax({
            url: $url_base + '/index.php/presupuestos/ajax_get_subprogramas',
            data: {id_programa: $(this).val()},
            method: "POST"
        })
        .done(function (data) {
            if(data.subprograma !== undefined) {
                addOptions(data.subprograma, 'select#id_subprograma', true);
            }
        });
    });

}); 