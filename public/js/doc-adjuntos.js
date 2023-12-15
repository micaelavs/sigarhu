$(document).ready(function(){
	//Para agregar campos input file
    // var max_campo = 5; //maximo campos agregar
    // var campo_file = $(".input_campo_file"); //div a colocar los campos
    // var add_campo_boton = $(".add_campo_boton"); //link agregar
    // var x = 1;
    // $(add_campo_boton).click(function(e){
    //     e.preventDefault();
    //     if(x < max_campo){
    //         x++;
    //         $(campo_file).append('<div><input type="file" name="documento[]" accept=".docx, application/pdf" style="display:inline;"/><a href="#" class="remover_campo" style="display:inline; color: red;"> Eliminar</a></div>'); 
    //     }
    // });
   
    //  $(campo_file).on("click",".remover_campo", function(e){
    //      e.preventDefault(); $(this).parent('div').remove(); x--;
    //  });
    // // $(".uploads").fileinput();
$("#id_tipo").select2();
$(".uploads").fileinput({
          language: 'es',
          browseLabel: 'Seleccione archivo.',
          showRemove: false,
          showUpload: false,
          previewFileIcon: '<i class="glyphicon glyphicon-eye"></i>',
          previewFileIconClass: 'file-icon-4x'
      });



});