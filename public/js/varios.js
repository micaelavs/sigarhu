$(document).ready(function(){
	$("#div_otro_sindicato").hide();
	$("#btn_sindicato").click(function(){
	$("#div_otro_sindicato").slideDown();
	});

	var options = $sindicatos;
	var sindi = $sindi;
	var select = $("#sindicato");

	$("#sindicato").select2();
	$("#obra_social").select2();
	$("#seguro_vida").select2();
 });