$(document).ready(function () {
$(function () {
    $('.fecha').datetimepicker({
      format: 'DD/MM/YYYY'
    });
  });

    var tipo_embargo= $('#tipo_embargo').val();
	   span_monto(tipo_embargo);

    $('#tipo_embargo').on('change', function(){
  			var tipo_embargo= $(this).val();
  			span_monto(tipo_embargo);

  	});

});

function span_monto(tipo_embargo){
	if(tipo_embargo == ''){
		$( "#span_monto" ).removeClass().addClass( "fa fa-minus" );
	}
	if(tipo_embargo == 1){
		$( "#span_monto" ).removeClass().addClass( "fa fa-usd" );
	}
	if(tipo_embargo == 2){
		$( "#span_monto" ).removeClass().addClass( "fa fa-percent" );
	}
}
