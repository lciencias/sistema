var url;
var idEjercicio;
var id;
var dataString;
var token;
var vacio = '';
var noPermisos =0;
$(document).ready(function() {
    token = $('meta[name="csrf-token"]').attr('content');
    noPermisos = $("#noPermisos").val();
    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });

	var form = $('validateFormEjercicio');
	form.on('submit', function(e){
		e.preventDefault();
	});

	
	  //validador ejercicio
    $('#validateFormEjercicio').bootstrapValidator({
    	message: I18n._("nombreInvalido"),
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            idclienteEjercicio: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("nombreObligatorio")
                    },
                }
            },   
            idTipoEjercicio: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("nombreObligatorio")
                    },
                }
            },   
        }
    });
    
    //Evento click para agregar competencia
    $("#idCompetenciaAgrega").on('change', function(event) {
	 var idCompetencia = event.target.value;
	 if($("#idCompetenciaAgrega").val() != "") {
	     $("#btnAgregaCompetencia").prop('disabled', false);
	 } else {
	     $("#btnAgregaCompetencia").prop('disabled', true);
	 }
	
	
	
    });
    
    //Evento click para agregar competencia
    $("#btnAgregaCompetencia").on('click', function(event) {
	event.preventDefault();
	
	
	
	var nombreCompetencia =$('#idCompetenciaAgrega option:selected').html();
	var idCompetencia = $("#idCompetenciaAgrega").val();
	
	if ( $("#competencia"+idCompetencia).length > 0 ) {
	    alert ("la competencia ya se agrego");
	    $("#idCompetenciaAgrega").val("");
	    $("#btnAgregaCompetencia").prop('disabled', true);
	    return false;
	  }
	
	var msg = "";
	
	url   = baseUrl+'competencia/regresaComportamientos';
	dataString = 'idCompetencia='+idCompetencia;	
    	$.ajax({
    		type: 'POST',
    		url : url,		
    		 headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
        dataType: 'json',
    		data: dataString,
    		beforeSend: function(){
    			$('#procesando').html(procesando);
    		},			
    		success: function(data){
    			$('#procesando').fadeIn(1000).html("");
    			if(parseInt(data.exito) === 1){
    			    
    			    	
    				var comportamientos = data.comportamientos;	
    				if(comportamientos.length > 0){							
    					$.each(comportamientos, function(i, item) {
    					    	msg += "<tr><td class='warning'><input name= 'comportamientos[]' class='test-checkbox comportamientos' data-toggle='tooltip' data-placement='left' id='comportamiento-" + idCompetencia+ "-" + comportamientos[i].idcomportamiento + "' value='comportamiento-" + idCompetencia+ "-" + comportamientos[i].idcomportamiento + "' type='checkbox' checked>&nbsp;&nbsp;<b>" + comportamientos[i].nombre+ "</b></td></tr>";
    					});
    					var nuevaCompetencia = "<div class='panel panel-default'>" +
    					"<div class='panel-heading' role='tab' id='competencia" + idCompetencia +  "'>" +
    		        			"<h4 class='panel-title'>" +
//    		        			"<input class='test-checkbox parents p-5' id='p-5' data-toggle='tooltip' data-placement='left' title='lo que se' name='modulosParents[]' type='checkbox' value='5'>" +
    		        			"<a role='button' data-toggle='collapse' data-parent='#accordion' href='#collapse" +idCompetencia + "' aria-expanded='false' aria-controls='collapse" + idCompetencia + "' >" +
    		        			"<label for='mods'><b>" + nombreCompetencia + "</b>     </label></a> <button class='quitarCompetencia' id='btnCompetencia-" + idCompetencia + "'>Quitar</button> " +
    		        			"</h4>" +
    					"</div>" +
    					"<div id='collapse" + idCompetencia + "' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='competencia"+ idCompetencia+ "'>" +
    					"<div class='panel-body'>" +
    					"<table class='table table-bordered'>" + msg +
    					"</table></div></div>";
    			
                			$("#accordion").append(nuevaCompetencia);
                			
                			$("#idCompetenciaAgrega").val("");
                			$("#btnAgregaCompetencia").prop('disabled', true);
                			//Quita del select los elegidos
                			//$("#idCompetenciaAgrega option[value='" + idCompetencia + "']").remove();
                			
    				}else{
    					msg += "";
    				}
    				
    				$('#procesando').fadeIn(1000).html("");
    			}		
    		},
    		error: function (xhr, ajaxOptions, thrownError) {
    
    			$('#procesando').fadeIn(1000).html("");
    			error(vacio);
    	    },
    		complete: function(){
    			event.stopPropagation();
    		}				
    	});

	
	
    });


   

  
    $(".quitarCompetencia").on('click',function(event){
	event.preventDefault();
	alert("qiotar");
	return false;
});
  
  
 
	
	//evento para restablecer al usuario
	$(".restablecerEjercicio").on('click',function(event){
		event.preventDefault();
		alert("qiotar");
		url   = baseUrl+'catalogos/activaEjercicio';
		var tmp = $(this).attr('id').split('-');
		var id  = tmp[1];
		var div = 'modal-activarEjercicio-'+id; 
		if(parseInt(id) > 0 && String(token) !== ''){
			dataString = 'idEjercicio='+id;	
			$.ajax({
				type: 'POST',
				url : url,		
				 headers: {
	                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	             },
	            dataType: 'json',
				data: dataString,
				beforeSend: function(){
					$('#procesando').html(procesando);
				},			
				success: function(data){
					$('#'+div).modal('hide');
					$('#procesando').fadeIn(1000).html("");
					if(parseInt(data.exito) === 1){
						exito("El ejercicio ha sido activado");
						 setTimeout(function(){
							 location.href = baseUrl+"catalogos/ejercicio";
						},1500);							
					}else{						
						error(vacio);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$('#'+div).modal('hide');
					$('#procesando').fadeIn(1000).html("");
					error(vacio);
			    }				
			});
		}
		return false;
	});
	
	$("#creaEjercicio, #actualizaEjercicio").on('click',function(){
	    var contador = 0;
	    $(".comportamientos").each(function(i,item){
		if($(this).prop('checked')){
		    contador++;
		}
	    });
	    if(contador === 0){
		error(I18n._("SinCompetencia"))
		return false;
	    }
	    return true;
	    
	});
	
});