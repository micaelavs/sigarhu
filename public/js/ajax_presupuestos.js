$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    if($('#presupuestos').length){
        var tabla = $('#presupuestos').DataTable({
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
                url:$url_base + '/index.php/presupuestos/ajax_presupuestos',
                contentType: "application/json",
                data: function (d) {

                }
            },
            info: true,
            bFilter: true,
            columnDefs: [
                { targets: 0, width: '12%' },
                { targets: 1, width: '11%' },
                { targets: 2, width: '12%' },
                { targets: 3, width: '12%' },
                { targets: 4, width: '12%' },
                { targets: 5, width: '12%' },
                { targets: 6, width: '12%' },
                { targets: 7, width: '12%' },
                { targets: 8 },
            ],
            order: [[0, 'asc']],
            columns: [
                {
                    title: 'SAF',
                    className: 'text-left',
                    name: 'saf',
                    data: 'saf',

                },
                {
                    title: 'Jurisdicción',
                    className: 'text-left',
                    name: 'jurisdicciones',
                    data: 'jurisdicciones',
                },
                {
                    title: 'Ubicación Geográfica',
                    className: 'text-left',
                    name: 'ub_geograficas',
                    data: 'ub_geograficas',
                },
                {
                    title: 'Programa',
                    className: 'text-left',
                    name: 'programas',
                    data: 'programas',
                },
                {
                    title: 'Subprograma',
                    className: 'text-left',
                    name: 'subprogramas',
                    data: 'subprogramas',
                },
                {
                    title: 'Proyecto',
                    className: 'text-left',
                    name: 'proyectos',
                    data: 'proyectos',
                },
                {
                    title: 'Actividad',
                    className: 'text-left',
                    name: 'actividades',
                    data: 'actividades',
                },
                {
                    title: 'Obra',
                    className: 'text-left',
                    name: 'obras',
                    data: 'obras',
                },    
                {
                    title: 'Acciones',
                    className: 'text-left,overflow-hidden',
                    name: 'accion',
                    orderable: false,
                    data: 'acciones',
                    render: function (data, type, row) {
                      if(row.id_usuario == row.id_logueado){
                          var  $html = '<span class="acciones">';
                            $html += '<a href="'+$url_base+'/index.php/presupuestos/am_presupuesto/' +row.id + '" data-toggle="tooltip" title="Modificar Presupuesto" id= "modificar_presupuesto"><i class="fa fa-pencil"></i></a>';                    
                            $html += ' ';
                            $html += '<a href="'+$url_base+'/index.php/presupuestos/baja_presupuesto/' +row.id + '" data-toggle="tooltip" title="Eliminar Presupuesto" id= "eliminar_presupuesto"><i class="fa fa-trash"></i></a>';                    
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

    $('#id_subprograma').on('change', function(){
        $.ajax({
            url: $url_base + '/index.php/presupuestos/ajax_get_proyectos',
            data: {
                id_programa: $("#id_programa").val(),
                id_subprograma: $(this).val(),
            },

            method: "POST"
        })
        .done(function (data) {
            if(data.proyectos !== undefined) {
                addOptions(data.proyectos, 'select#id_proyecto', true);
            }
        });
    });

    $('#id_proyecto').on('change', function(){
        $.ajax({
            url: $url_base + '/index.php/presupuestos/ajax_get_obras',
            data: {id_proyecto: $(this).val()},
            method: "POST"
        })
        .done(function (data) {
            if(data.obras !== undefined) {
                addOptions(data.obras, 'select#id_obra', true);
            }
        });
    });

}); 