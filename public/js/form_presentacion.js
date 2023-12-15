$(document).ready(function () {
$(function () {
    $('#periodo').datetimepicker({
        viewMode: 'years',
        format: 'YYYY',

    });

    var y = moment().get('year');
    $('.fecha').datetimepicker({
      maxDate: moment(y,"YYYY").add(1, 'year').subtract(1, 'days'),
      format: 'DD/MM/YYYY'
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

    var $btn =$('#volver_legajo');
    var f = $('<form/>', {id:'form_l' , action : $btn.data('ref'), method : 'POST'});
    var input = $('<input />', { name: 'id_bloque', type: 'hidden', value: $btn.data('bloque') })
    f.append(input);
    $btn.after(f);
    $btn.click(function(e){
      e.preventDefault();
        $('#form_l').submit();
    });
});