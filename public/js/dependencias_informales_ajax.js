
var filtros_dataTable = null;

  

$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    var $url_base = $('#url-base').attr('data_url');    

    var tabla = $('#dependencias_informales').DataTable({
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
           url:$url_base + '/index.php/dependencias/ajax_lista_dependencias_informales',
            contentType: "application/json",
            data: function (d) {
                 filtros_dataTable = $.extend({}, d, {
                    dependencia             :   $('#id_dependencia').val(),

                });
                return filtros_dataTable;

            }
        },
        info: true,
        bFilter: true,
        columnDefs: [
            { targets: 0, width: '30%' },
            { targets: 1, width: '40%' },
            { targets: 2, width: '15%' },
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
                title: 'Dependencia Padre',
                className: 'text-left',
                name: 'nombre_padre',
                data: 'nombre_padre',

            },
            {
                title: 'Fecha Desde',
                className: 'text-center',
                name: 'fecha_desde',
                data: 'fecha_desde',
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
                        $html += '<a href="'+$url_base+'/index.php/dependencias/modificacion_informales/' +row.id + '"  class="btn btn-link btn-sm" data-toggle="tooltip"  data-id="'+row.id+'" data-bl="'+ row.id_bloque + '" title="Modificar Dependencia Informal" id= "modificar_dep_informal"><i class="fa fa-edit"></i></a>';                    
                        $html += '</div>';
                        $html += '<a href="'+$url_base+'/index.php/dependencias/baja_informales/' +row.id + '"  class="btn btn-link btn-sm" data-toggle="tooltip"    data-id="'+row.id+'" data-bl="'+ row.id_bloque + '" title="Eliminar Dependencia Informal" id= "eliminar_dep_informal"><i class="fa fa-trash"></i></a>';                    
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


    $('#id_dependencia').on('change', update);
    $("#id_dependencia").select2();
    $("#boton_nuevo").attr('disabled', true);
    $('#id_dependencia').on('change', function(){ 
        var aux = $("#boton_nuevo").data('href');          
        aux = aux +'/'+ $('#id_dependencia').val();
        $("#boton_nuevo").data('href',aux);
    
         $.ajax({
         url: $url_base+"/index.php/dependencias/ajax_lista_dependencias_informales",
            data: {
                dependencia:   $('select#id_dependencia').val(),
            },
              success: function(data) {
                 $("#boton_nuevo").attr('disabled', false);
            },
           method: "GET"
        })

    });  

$("#boton_nuevo").click(function () {
    window.location.replace($(this).data('href'));
});


}); 