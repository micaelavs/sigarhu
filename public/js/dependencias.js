$(document).ready(function () {
	
  $("#id_padre").select2();
  $(".fecha").datetimepicker({
			format: 'DD/MM/YYYY'
		});

    if(typeof $.fn.dataTable !== 'undefined'){
      $.fn.dataTable.moment('DD/MM/YYYY');	
    }
});

$('select#nivel').on('change', function($e){
  var nivelSelect;
  if($('select#nivel').val()==""){
    nivelSelect = 0;
  }else{
      nivelSelect = $('select#nivel').val();
  }
  $.ajax({
    url: $base_url+"/Dependencias/alta",
    data: {
      nivel:	nivelSelect,
      
    },
    method: "POST"
  })
  .done(function (data) {
    addOptionsMulti(data.data, '#id_padre',data.data.nombre);
  })
  .fail(function(data){
    addOptionsMulti([], '#id_padre');
  });

function addOptionsMulti($options, $dom_select, $selected ){
    $obj				= $($dom_select);
    if($obj[0].nodeName	!= 'SELECT') return $obj;

// Limpiar etiquetas <Select> antes de llenarlas
    $obj.html('');
    $obj.append($('<option>', {
      value: '',
      text : 'Seleccione'
    }));
// Llenar etiquetas <Select>
    $.each($options, function (i, item) {
      $_options	= {
        value: i,
        text : item.nombre,
      };
      if(item.borrado != '0'){
        $_options.disabled	= 'disabled';
      }
      
      if($.inArray( i, $selected) != -1){
        $_options.selected	= 'selected';
      }
      $obj.append($('<option>', $_options));
    });
    return $obj;
  }
});