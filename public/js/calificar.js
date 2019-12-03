$(document).ready(function() {	
	$(".calificar").change(function(){
    	var id = $(this).val();
    	if(parseInt(id) <= 0){
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
	
	$(".selectorRadios").click(function(){
    	var idCalComp = $(this).val();
    	var idElemento = $(this).attr('id');
    	var tmpElem = idElemento.split('-');    	
    	var idElementoC = "s-"+tmpElem[1]+'-'+tmpElem[2]+'-'+tmpElem[3];
    	console.log(idElemento);
    	console.log(idElementoC);
    	var msg = '';
    	if(parseInt(idCalComp) > 0){
    		var url = baseUrl+ 'evaluacion/calificar/regresaCalificacion';
        	dataString = 'idCalComp =' + idCalComp;
			$.ajax({
				type : 'POST',
				url : url,
				headers : {
					'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
				},
				dataType : 'json',
				data : dataString,
				beforeSend : function() {
					$('#procesando').html(procesando);
				},
				success : function(data) {
					$('#procesando').fadeIn(1000).html("");
					if (parseInt(data.exito) === 1) {
						$("#"+idElementoC).empty();
						var califica = data.calificaciones;
						if (data.total > 0) {
							if(data.total > 1){
								msg += "<option value='-1'>Seleccione</option>";
							}							
							$.each(califica,function(i,item) {
								msg += "<option value='"+ item+ "'>"+ item + "</option>";
							});
						} else {
							msg += "<option value='-1'>Seleccione</option>";
						}
						$("#"+idElementoC).html(msg);
						$('#procesando').fadeIn(1000).html("");
					}
				},
				error : function(xhr,ajaxOptions,thrownError) {
					$('#procesando').fadeIn(1000).html("");
					error(vacio);
				},
				complete : function() {
					event.stopPropagation();
				}
			});
    	}
	});
});