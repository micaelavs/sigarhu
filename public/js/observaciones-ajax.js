

$(document).ready(function () {

}); 


$('#observacion').ready(function () {
   var $url_base = $('#url-base').attr('data_url');    
    var $cuit = $('#div_agente .ct').eq(1).text();
    $("#alert_observacion").hide();
    $('[data-toggle="tooltip"]').tooltip();

    if (!$.fn.DataTable.isDataTable('#observacion')){
         var tabla = $('#observacion').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searchDelay: 1200,
            language: {
                url: $endpoint_cdn+'/datatables/1.10.12/Spanish_sym.json',
                decimal: ',',
                thousands: '.'
            },
            ajax: {
                url:$url_base + '/index.php/legajos/ajax_lista_observaciones/'+$cuit,
                contentType: "application/json",
                data: function (d) {

                }
            },
            info: true,
            bFilter: true,
            columnDefs: [
                { targets: 0, width: '5%' },
                { targets: 1, width: '50%' },
                { targets: 2, width: '5%' },
                { targets: 3, width: '5%' },
                { targets: 4, width: '5%'}
            ],
            order: [[0, 'desc']],
            columns: [
                {
                    title: 'Fecha',
                    className: 'text-left',
                    name: 'fecha',
                    data: 'fecha',
                    width: '5%',
                },
                {
                    title: 'Observacion',
                    className: 'text-center',
                    name: 'descripcion',
                    data: 'descripcion',
                },      
                {
                    title: 'Bloque',
                    className: 'text-left',
                    name: 'bloque',
                    data: 'bloque',
                },
                {
                    title: 'Usuario',
                    className: 'text-left',
                    name: 'usuario',
                    data: 'usuario',
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
                            $html += '<a class="btn btn-link btn-sm" data-toggle="tooltip" data-id="'+row.id+'" data-bl="'+ row.id_bloque + '" title="Modificar Observacion" id= "modificar_observacion"><i class="fa fa-edit"></i></a>';
                            $html += '<a class="btn btn-link btn-sm" data-toggle="tooltip" data-id="'+row.id+'" data-bl="'+ row.id_bloque + '" title="Eliminar Observacion" id= "eliminar_observacion"><i class="fa fa-trash"></i></a>';
                            $html += '</div>';
                            return $html;
                        }
                          var  $html = '<div class="btn-group btn-group-sm">';
                                $html += '</div>';
                            return $html;
                    }

                }
            ]
        });

        $.fn.dataTable.moment('DD/MM/YYYY');

     /**
     * Consulta al servidor los datos y redibuja la tabla
     * @return {Void}
    */
    $(".span-observacion").click(function(){
        var $bloque;
        var $id;
        var $cuit           =  $('#div_agente .ct').eq(0).text();
        var $observacion    =  $("#legajo_observacion_ajax");
        $("#alert_observacion").hide();
        if ($observacion.data('id') == '') {
            $bloque = $('li.active a').data('id');
            $id = '0';
        }else{
            $bloque = $observacion.data('bloque');
            $id = $observacion.data('id');
        } 
        
        $.ajax({
                url: $url_base + '/index.php/legajos/observaciones',
                data: {
                    id_ob: $id,
                    cuit: $cuit,
                    descripcion: $observacion.val(),
                    bloque: $bloque
                },
                method: "POST"
            })
            .done(function (data) {
                if (!data.error) {
                    $observacion = $("#legajo_observacion_ajax"); 
                    $observacion.val('');
                    $observacion.data('id','');
                    $observacion.data('bloque','');
                    $("#alert_observacion").show();
                    $("#alert_observacion").removeClass().addClass("alert alert-success");
                    $("#alert_observacion i").removeClass().addClass("fa fa-check");
                    $("#alert_observacion span").html(" La nueva Observación se cargo con éxito.");
          
                    update();
                }else{
                    $("#alert_observacion").show();
                    $("#alert_observacion").removeClass().addClass("alert alert-danger")
                    $("#alert_observacion i").removeClass().addClass("fa fa-times-circle");
                    $("#alert_observacion span").html(" No es posible Cargar una Observación vacía");
                }
            });
    });

   $(document).delegate('#modificar_observacion','click',function(){
        var $tr = $(this).parent().parent().parent();
        $("#alert_observacion").hide();
        $('#legajo_observacion_ajax').val($($tr).children('td').eq(1).text());
        $('#legajo_observacion_ajax').data('id', $(this).data('id'));
        $('#legajo_observacion_ajax').data('bloque', $(this).data('bl'));
    });

    $(document).delegate('#eliminar_observacion','click',function(){
        var $observacion    =  $("#legajo_observacion_ajax");
        var $id = $(this).data('id');
        $.ajax({
                url: $url_base + '/index.php/legajos/observaciones',
                data: {
                    id_ob: $id,
                    borrado: 1,
                },
            method: "POST"
        })
        .done(function (data) {
            if (!data.error) {
                $("#alert_observacion").show();
                $("#alert_observacion").removeClass().addClass("alert alert-success");
                $("#alert_observacion i").removeClass().addClass("fa fa-check");
                $("#alert_observacion span").html(" La observación se eliminó con éxito.");
                update();
            }else{
                $("#alert_observacion").show();
                $("#alert_observacion").removeClass().addClass("alert alert-danger")
                $("#alert_observacion i").removeClass().addClass("fa fa-times-circle");
                $("#alert_observacion span").html(" No es posible eliminar");
                }
            });
    });

    function update() {
        tabla.draw();
    }

    }

});    
