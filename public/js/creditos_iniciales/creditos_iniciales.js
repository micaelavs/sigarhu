
$(document).ready(function () {
    $('#cuit').typeahead({
        onSelect: function (item) {                
            $("#nombre_apellido").val(item.text);
        },
        ajax: {
            url: $base_url+"/CreditosIniciales/buscarAgente",
            timeout: 300,
            displayField: 'full_name',
            valueField: 'dep',
            triggerLength: 11,
            method: "post",
            loadingClass: "loading-circle",
            preDispatch: function (query) {
                return {
                    cuit: query,
                }
            },
            preProcess: function (data) {
                if (data.success === false) {
                    return false;
                }
                var Nombre_apellido = data.data.persona.nombre+" "+data.data.persona.apellido;
                if((data.data.id == null)){
                    $('#aviso').text("Debe ingresar un CUIT de un Agente existente");
                     Nombre_apellido ='';   
                     $("#nombre_apellido").text(Nombre_apellido);
                }else{
                    $("#aviso").text('');
                }
                $("#nombre_apellido").text(Nombre_apellido);
                   //return data;
            }
        }
    });
});	 

