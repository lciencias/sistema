var url;
var token;

$(document).ready(function() {	
	token = $('meta[name="csrf-token"]').attr('content');
    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });
	var formCompetencia = $('validateFormCompetencia');
	formCompetencia.on('submit', function(e){
	    e.preventDefault();
	});

	$('#validateFormCompetencia').bootstrapValidator({
	    message: I18n._("nombreInvalido"),        
	    feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	    },
	    fields: {
		nombreCompetencia: {
        	    message: I18n._("nombreInvalido"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("nombreObligatorio")
        		},	
        		regexp: {
        		    regexp: letras,
        		    message: I18n._("nombreCaracteres")
        		},
        		stringLength: {
        		    min: 3,
        		    max: 50,
        		    message: I18n._("minimoCompetenciaCaracteres")
        		}, 
        	    }
        	},
        	definicionCompetencia:{
        	    message: I18n._("nombreInvalido"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("nombreObligatorio")
        		},
        		regexp: {
        		    regexp: alfanum,
        		    message: I18n._("nombreCaracteres")
        		},
        		stringLength: {
        		    min: 6,
        		    max: 50,
        		    message: I18n._("minimoRazonCaracteres")
        		}
        	    }            
        	},
        	tipoCompetencia:{
        	    message: I18n._("nombreInvalido"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("nombreObligatorio")
        		},
        	    }            
        	},
	    }
	});	
	
	

	
	//evento boton guardar
//	$("#guardaCompetencia").on("click", function (evt) {
//	    evt.preventDefault();
//	    $('#validateFormCompetencia').bootstrapValidator('validate');
//	    if ($(".has-error").length == 0 && ($(".btn-has-error").length == 0)) {
//		 url   = baseUrl+'general/validaNombre';
//		 var form_data;
//		      
//		 form_data = new FormData();
//		 form_data.append('idelemento', $("#idelemento").val());
//		 form_data.append('idcompetencia', $("#idcompetencia").val());
//		 form_data.append('idmodulo', $("#idmodulo").val());
//		 form_data.append('nombre', $("#nombreCompetencia").val());
//			    
//		 $.ajax({
//			type: 'POST',
//			url : url,
//			contentType: false,
//			cache: false,
//			headers: {
//			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//			},
//			 processData: false,
//			data: form_data,
//			dataType: 'json',
//			beforeSend: function(){
//			$('#procesando').html(procesando);
//			},			
//			success: function(info){
//			    $('#procesando').fadeIn(1000).html("");
//			    if(!info.valid){
//				alert("a");
//				error(info.message);
//				evt.preventDefault();
//			    }else{
//				alert("b");
//				return true;
//			    }						
//			},
//			error: function (xhr, ajaxOptions, thrownError) {
//			    alert("c");
//			    $('#procesando').fadeIn(1000).html("");
//			    return false;
//			}
//			
//		      });
//		 alert("d");
//	    } else {
//		 alert("e");
//	    }
//	    
////	    alert("f");
////	    return false;
//	    
//	    
//	    
//	});
	
	
	  $(".eliminarCompetencia").on('click',function(){
		  var buffer = 0;
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'catalogos/desactivaCompetencia';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idCompetencia='+$("#idRegistro").val();
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
					success: function(info){
						$('#procesando').fadeIn(1000).html("");
						if(parseInt(info.exito) === 1){
							$("#modal-restablece").modal('hide');
							exito(info.msg);
                        setTimeout(function(){location.href = baseUrl+'catalogos/competencia'},tiempo);							
						}else{
							error(info.msg);
						}						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});				 
		    }
	  });
	
	//evento para restablecer a la competencia
	  $(".restablecerCompetencia").on('click',function(){
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'catalogos/activaCompetencia';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idCompetencia='+$("#idRegistro").val();
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
					success: function(info){
						$('#procesando').fadeIn(1000).html("");
						if(parseInt(info.exito) === 1){
							$("#modal-restablece").modal('hide');
							exito(info.msg);
                        setTimeout(function(){location.href = baseUrl+'catalogos/competencia'},tiempo);							
						}else{
							error(info.msg);
						}						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});		  
		  }
	  });

	$("#idCompetenciaBusca").on('change',function(event){
		var msg='';
		event.preventDefault();
		url   = baseUrl+'seguridad/regresaPerfiles';
		var idCompetencia = $("#idCompetenciaBusca").val();
		if(parseInt(idCompetencia) > 0 && String(token) !== '-1'){
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
						$("#idPerfilBusca").empty();
						var perfiles = data.perfiles;												
						if(perfiles.length > 0){							
							msg += "<option value=''>Seleccione</option>";	
							$.each(perfiles, function(i, item) {
								msg += "<option value='"+perfiles[i].idperfil+"'>"+perfiles[i].nombre+"</option>";					
							});
						}else{
							msg += "<option value=''>No existen Perfiles</option>";
						}
						$("#idPerfilBusca").html(msg);
						$("#idperfil").html(msg);
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
		} else {
		    $("#idPerfilBusca").empty();
		    msg += "<option value=''>Seleccione</option>";	
		    $("#idPerfilBusca").html(msg);
		    $("#idperfil").html(msg);
		    $('#procesando').fadeIn(1000).html("");
		}
		return false;		
	});
	
	$("#nombre").on('focus',function(){
		return false;
	});
	
	
	
	$("#confirmaAgregaComportamiento").click(function(){
	    	var orden = null;
		var comportamiento= $("#nombreComportamiento").val();
		
		var idNivelDestacado = $("#idNivelDestacado").val();
		var nivelDestacadoT = $("#nivelDestacadoT").val();
//		var nivelDestacadoN = $("#nivelDestacadoN").val();
		
		var idNivelSuperior = $("#idNivelSuperior").val();
		var nivelSuperiorT = $("#nivelSuperiorT").val();
//		var nivelSuperiorN = $("#nivelSuperiorN").val();
		
		var idNivelSatisfactorio = $("#idNivelSatisfactorio").val();
		var nivelSatisfactorioT = $("#nivelSatisfactorioT").val();
//		var nivelSatisfactorioN = $("#nivelSatisfactorioN").val();
		
		var idNivelEnDesarrollo = $("#idNivelEnDesarrollo").val();
		var nivelEnDesarrolloT = $("#nivelEnDesarrolloT").val();
//		var nivelEnDesarrolloN = $("#nivelEnDesarrolloN").val();
		
		var idNivelLimitado = $("#idNivelLimitado").val();
		var nivelLimitadoT = $("#nivelLimitadoT").val();
//		var nivelLimitadoN = $("#nivelLimitadoN").val();
		
		var idNivelNoObservado= $("#idNivelNoObservado").val();
		var nivelNoObservadoT = $("#nivelNoObservadoT").val();
//		var nivelNoObservadoN = $("#nivelNoObservadoN").val();
		
		
//		var opciones = idNivelDestacado + "@/@" +  nivelDestacadoT + "@/@" + nivelDestacadoN + "@/@" + idNivelSuperior + "@/@" + nivelSuperiorT + "@/@" + nivelSuperiorN +
//		"@/@" +  idNivelSatisfactorio   + "@/@" + nivelSatisfactorioT + "@/@" + nivelSatisfactorioN + "@/@" + idNivelEnDesarrollo + "@/@" + nivelEnDesarrolloT  + "@/@" + nivelEnDesarrolloN +
//		"@/@" +  idNivelLimitado + "@/@" +  nivelLimitadoT + "@/@" + nivelLimitadoN + "@/@" +  idNivelNoObservado + "@/@" +nivelNoObservadoT + "@/@" + nivelNoObservadoN;
		
		var opciones = idNivelDestacado + "@/@" +  nivelDestacadoT + "@/@" + idNivelSuperior + "@/@" + nivelSuperiorT + "@/@" +  idNivelSatisfactorio   + "@/@" + nivelSatisfactorioT  
		+ "@/@" + idNivelEnDesarrollo + "@/@" + nivelEnDesarrolloT  + "@/@" +  idNivelLimitado + "@/@" +  nivelLimitadoT + "@/@" +  idNivelNoObservado + "@/@" +nivelNoObservadoT ;
		
		alert("opciones: " + opciones);
		
		if( $("#idComportamiento").val() == "null") {
			var nuevaFila="<tr id='null' >";
			nuevaFila+="<td style='visibility: hidden'>null</td>";
			nuevaFila+="<td>" + comportamiento + "</td>";
			nuevaFila+="<td class='tdCenter'> " +
			"<a href='#' class='btn btn-default  btn-xs'>" +
			"<span data-toggle='tooltip' data-placement='top' title='{{Lang::get('leyendas.editarRegistro')}}' class='glyphicon glyphicon-pencil'></span>"
			"</td>" +
			"</td>";
			nuevaFila+="<td style='visibility: hidden' valor='" + opciones + "' ></td>";
			nuevaFila+="</tr>";
			$("#tablaComportamientos").append(nuevaFila);
		} else {
		    $("#fila-"+$("#idComportamiento").val()).children("td").each(function (index2) 
			{
				switch (index2) 
				{
				case 0:
					break;
				case 1: 
				    	$(this).text(comportamiento);
					break;
				case 2: 
					break;
				case 3: 
				    $(this).attr("valor", opciones);
					break;

				}
					});
			
		}

		$("#modalComportamiento").modal('hide');

	});
	
	
	

	$(".editaComportamiento").on('click',function(event){
	    var idComportamiento = event.target.id;
	    $("#idComportamiento").val(idComportamiento);
	    
	    
	    var nombre;
	     $("#fila-"+$("#idComportamiento").val()).children("td").each(function (index2) 
			{
				switch (index2) 
				{
				case 0:
					break;
				case 1: 
				    nombre = $(this).text();
					break;
				case 2: 
					break;
				case 3: 
					break;

				}
					});
	     
	     $("#nombreComportamiento").val(nombre);
	     
	    
		var msg='';
		event.preventDefault();
		url   = baseUrl+'competencia/regresaNivelesComportamiento';
		if(parseInt(idComportamiento) > 0 && String(token) !== '-1'){
			dataString = 'idComportamiento='+idComportamiento;	
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
						var nivelesCalificacion = data.nivelesCalificacion;
						var indice = 0;
						if(nivelesCalificacion.length > 0){	
						    $.each(nivelesCalificacion, function(i, item) {
        						    if(indice == 0) {
        							$("#idNivelDestacado").val(nivelesCalificacion[i].idcalificacion_comportamiento);
        							$("#nivelDestacadoT").val(nivelesCalificacion[i].calificacion_texto);
        							$("#nivelDestacadoN").val(nivelesCalificacion[i].calificacion_numero);
        							    
        						    }
        						    else if (indice == 1) {
        							$("#idNivelSuperior").val(nivelesCalificacion[i].idcalificacion_comportamiento);
        							$("#nivelSuperiorT").val(nivelesCalificacion[i].calificacion_texto);
        							$("#nivelSuperiorN").val(nivelesCalificacion[i].calificacion_numero);
        							   
        						    }
        						    else if (indice == 2) {
        							$("#idNivelSatisfactorio").val(nivelesCalificacion[i].idcalificacion_comportamiento);
        							$("#nivelSatisfactorioT").val(nivelesCalificacion[i].calificacion_texto);
     							    	$("#nivelSatisfactorioN").val(nivelesCalificacion[i].calificacion_numero);
     							    
        						    }
        						    else if (indice == 3) {
        							$("#idNivelEnDesarrollo").val(nivelesCalificacion[i].idcalificacion_comportamiento);
        							$("#nivelEnDesarrolloT").val(nivelesCalificacion[i].calificacion_texto);
        							$("#nivelEnDesarrolloN").val(nivelesCalificacion[i].calificacion_numero);
         							   
        						    } 
        						    else if (indice == 4) {
        							$("#idNivelLimitado").val(nivelesCalificacion[i].idcalificacion_comportamiento);
        							 $("#nivelLimitadoT").val(nivelesCalificacion[i].calificacion_texto);
        							 $("#nivelLimitadoN").val(nivelesCalificacion[i].calificacion_numero);
      							  
        						    } 
        						    else if (indice == 5) {
        							$("#idNivelNoObservado").val(nivelesCalificacion[i].idcalificacion_comportamiento);
        							$("#nivelNoObservadoT").val(nivelesCalificacion[i].calificacion_texto);
        							$("#nivelNoObservadoN").val(nivelesCalificacion[i].calificacion_numero);
        						    }
        						    indice = indice + 1;
						    
						    });
						    
						    $("#modalComportamiento").modal('show');
						}else{
							msg += "<option value=''>No existen Perfiles</option>";
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
		} 
		return false;		
	});
});



function abreCompetencia(){
    $("#nombreComportamiento").val("");
    $("#nivelDestacadoT").val("");
//    $("#nivelDestacadoN").val("");
    $("#nivelSuperiorT").val("");
//    $("#nivelSuperiorN").val("");
    $("#nivelSatisfactorioT").val("");
//    $("#nivelSatisfactorioN").val("");
    $("#nivelEnDesarrolloT").val("");
//    $("#nivelEnDesarrolloN").val("");
    $("#nivelLimitadoT").val("");
//    $("#nivelLimitadoN").val("");
    $("#nivelNoObservadoT").val("");
//    $("#nivelNoObservadoN").val("");
    $("#idComportamiento").val("null");
    $("#idNivelDestacado").val("null");
    $("#idNivelSuperior").val("null");
    $("#idNivelSatisfactorio").val("null");
    $("#idNivelEnDesarrollo").val("null");
    $("#idNivelLimitado").val("null");
    $("#idNivelNoObservado").val("null");
    return true;
}




function guardaCompetencia(){
	var id = "";
	var comportamiento = "";
	var comportamientos ="";
	var opciones = "";
	var contador = 0;
	var cuantos = $('#tablaComportamientos >tbody >tr').length ;

	
	if (cuantos == 0) {
	    alert ("Se debe agregar por lo meno un comportamiento");
	    return false;
	}

	$("#tablaComportamientos tbody tr").each(function (index) {
		$(this).children("td").each(function (index2) 
				{
			switch (index2) 
			{
			case 0: 
			    id = $(this).text();
			    break;
			case 1: 
			    comportamiento = $(this).text();
			    break;
			case 2: 
			    break;
			case 3: 
			    opciones = $(this).attr("valor");
			    break;

			}
				});

		if(id == "null" || opciones !="") {
		    comportamientos = comportamientos + id + "--"+ comportamiento + "--" + opciones;
		    if(contador < cuantos - 1  )
			    comportamientos = comportamientos + "@@";
		}
		
		contador = contador +1;

	});
	
	$("#comportamientos").val(comportamientos);
	return true;
}

