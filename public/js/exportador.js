$(document).ready(function(){    
	var $base_url = $('#url-base').attr('data_url');
    $('body').focus();
    $('#modalidad_contratacion').on('change', function(){
        $.ajax({
            url: $base_url+"/Legajos/ajax_convenios_parametricos",
            data: {
                id_modalidad_vinculacion:   $('select#modalidad_contratacion').val(),
            },
            method: "GET"
        })
        .done(function (data) {
            if(data.situacion_revista !== undefined) {
                addOptions(data.situacion_revista, 'select#situacion_revista');
            }
        });
    });    

    $('.dep_p,#situacion_revista,#modalidad_contratacion,.nvl,.dep').select2();

    $(document).delegate('.dep_p','change',function(){        
        element = $(this).parent().parent().parent().attr('id'); 
        $.ajax({
            url: $base_url+"/Dependencias/ajax_nivel_hijos/"+$(this).val(),
            method: "GET",
        })         
        .done(function (data) {
            if(data.niveles !== undefined) {
                addOptions(data.niveles, '#'+element+'>div>div>select.nvl');
                addOptions([], '#'+element+'>div>div>select.dep');
            }
        },element);
    });


    $(document).delegate('.nvl','change',function(){
        element = $(this).parent().parent().parent().attr('id');

        $.ajax({
            url: $base_url+"/Dependencias/ajax_dependencias_nivel",
            data: {
                id_dependencia: $('#'+element+'>div>div>select#id_dependencia').val(),
                nivel:          $('#'+element+'>div>div>select.nvl').val()
            },
            method: "POST"
        },element)         
        .done(function (data) {
            if(data.dependencias !== undefined) {
                addOptions(data.dependencias, '#'+element+'>div>div>select.dep',true);
            }
        },element);
    });


    // filtros
    $(document).delegate('.btn-add', 'click', function(e){
            e.preventDefault();
            var controlForm    = $('.entry'),
                currentEntry   = $(this).parent().parent().parent(),
                index = currentEntry.attr('data-item'),
                next = parseInt(index)+1,
                newEntry      = $(currentEntry.clone()).appendTo(controlForm);
            
            newEntry.attr('id','r'+next);    
            newEntry.attr('data-item',next);    
            newEntry.find('label').remove();
            newEntry.find('span').remove();
            dep_p = newEntry.find('.dep_p').attr('name','id_dependencia['+next+']');
            dep_p.find('span').remove();
            dep_p.val('').select2();


            nvl = newEntry.find('.nvl').attr('name','nivel['+next+'][]');
            nvl.html('').select2();

            dep = newEntry.find('.dep').attr('name','dependencias['+next+'][]');
            dep.html('').select2();

            controlForm.find('.col-md-1:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-info').addClass('btn-default')
                .attr('title', 'Eliminar Filtro')
                .html('<i class="glyphicon glyphicon-minus"></i>');

        }).on('click', '.btn-remove', function(e){
            $(this).parent().parent().parent().remove();
            e.preventDefault();
            return false;
        });

    $("[name='select_all']").change(function() {
        var cont = $(this).val();
        cont = cont.replace(/\s/gi, '');
        $('#'+cont).find("input[type='checkbox']").prop('checked', this.checked);

    });       

});