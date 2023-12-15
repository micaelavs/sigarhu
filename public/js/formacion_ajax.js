$(document).delegate('.tiptit','change',function(){
    var name = $(this).attr('id');
    var name_field = name.substr(14); 
    $.ajax({
        url: $base_url + '/titulos/ajax_titulos',
        data: {id_tipo_titulo: $(this).val()},
        method: "POST"
    })
    .done(function (data) {
        if(data !== undefined) {
             addOptions(data, '#id_titulo'+name_field, true);
        }
    }),name_field;
});


