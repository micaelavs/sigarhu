$(document).ready(function () {
	
	
	if ($("#tipo").val() == 1) {
		$('.row_jurisdiccion').show();
	}else{
		$('.row_jurisdiccion').hide();
	}
	
	$("#tipo").on('change',function(){
		if ($(this).val() == '1'){
			$('.row_jurisdiccion').show();
		} else {
			$('.row_jurisdiccion').hide();
		}
	});
}); 