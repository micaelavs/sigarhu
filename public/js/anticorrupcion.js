$(document).ready(function () {
$(function () {
        $('#periodo').datetimepicker({
            viewMode: 'years',
            format: 'YYYY',
 
        });
    });
       $(".filestyle").fileinput({
          language: 'es',
          browseLabel: '',
          showRemove: false,
          showUpload: false,
          previewFileIcon: '<i class="glyphicon glyphicon-eye"></i>',
          previewFileIconClass: 'file-icon-4x'
        });

    $('#obligado_dj').on('click',function () {
        if ($('#obligado_dj').is(':checked')) {
          $("#fecha_designacion").attr('disabled', false);
          $("#fecha_publicacion_designacion").attr('disabled', false);
          $("#boton_anticorrupcion").attr('disabled', false);
        } else {
          $("#fecha_designacion").val('').attr('disabled', true);
          $("#fecha_publicacion_designacion").val('').attr('disabled', true);
          $("#boton_anticorrupcion").attr('disabled', true);
        }
    });
 
});