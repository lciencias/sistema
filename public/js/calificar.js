$(document).ready(function() {	
	$(".calificar").change(function(){
    	var id = $(this).val();
    	if(parseInt(id) === 0){
        	console.log("id:  "+ id);
    		error("Por favor seleccione una calificación");
    	}
    	return false;
    });
	
	
	$("#guardaCalifica").click(function(){
		var noComportamientos =  document.getElementsByClassName("comportamientos").length;
		var contadorRadios    = 0;
		var contadorSelect    = 0;
		var observaciones     = $("#observaciones").val();		
		$(".radios").each(function( index ) {
			if( $(this).is(':checked')){
				contadorRadios++;				
			}
		});
		
		$(".calificar").each(function( index ) {
			if( parseFloat($(this).val()) > 0){
				contadorSelect++;				
			}
		});
		if( parseInt(noComportamientos) === parseInt(contadorRadios) && parseInt(noComportamientos) === parseInt(contadorSelect)  && parseInt(observaciones.length) >0 ){
			$("#estatus").val(2);
		}else{
			$("#estatus").val(1);
		}
		return true;
	});
});