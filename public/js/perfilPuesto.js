var url;
var token;

$(document).ready(function() {	
	token = $('meta[name="csrf-token"]').attr('content');
    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });
	var formPerfilPuesto = $('validateFormPerfilPuesto');
	formPerfilPuesto.on('submit', function(e){
	    e.preventDefault();
	});

	$('#validateFormPerfilPuesto').bootstrapValidator({
	    message: I18n._("nombreInvalido"),        
	    feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	    },
	    fields: {
		nombrePerfilPuesto: {
        	    message: I18n._("nombreInvalido"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("datoObligatorio")
        		},	
        		stringLength: {
        		    min: 3,
        		    max: 50,
        		    message: I18n._("minimoPerfilPuestoCaracteres")
        		}, 
        	    }
        	},
        	idclienteEjercicio:{
        	    message: I18n._("datoObligatorio"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("datoObligatorio")
        		},
        	    }            
        	},
        	nivelPerfilPuesto:{
        	    message: I18n._("datoObligatorio"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("nombreObligatorio")
        		},
        	    }            
        	},
	    }
	});	
	
	
	
	
	 //Evento click para agregar competencia
	    $("#idTalentoAgrega").on('change', function(event) {
		 var idTalento = event.target.value;
		 if($("#idTalentoAgrega").val() != "") {
		     $("#btnAgregaTalento").prop('disabled', false);
		 } else {
		     $("#btnAgregaTalento").prop('disabled', true);
		 }
		
		
		
	    });
	    
	  //Evento click para agregar competencia
	    $("#btnAgregaTalento").on('click', function(event) {
		event.preventDefault();
		var nombreTalento =$('#idTalentoAgrega option:selected').html();
		var idTalento = $("#idTalentoAgrega").val();
		if ( $("#talento"+idTalento).length > 0 ) {
		    alert ("El talento ya se agrego");
		    $("#idTalentoAgrega").val("");
		    $("#btnAgregaTalento").prop('disabled', true);
		    return false;
		  }
		
		var nuevaFila="<tr id='talento" + idTalento +"' >";
		nuevaFila+="<td style='visibility: hidden'>null</td>";
		nuevaFila+="<td style='visibility: hidden' valor='" + idTalento + "' ></td>";
		nuevaFila+="<td>" + nombreTalento + "</td>";
		nuevaFila+="<td class='tdCenter'> " +
		"<a href='#' class='btn btn-default  btn-xs'>" +
		"<span data-toggle='tooltip' data-placement='top' title='Editar' class='glyphicon glyphicon-pencil'></span> </a>"+
		"<a href='#' class='btn btn-default  btn-xs'>" +
		"<span data-toggle='tooltip' data-placement='top' title='Eliminar' class='glyphicon glyphicon-trash'></span> </a>"+
		"</td>";
		
		nuevaFila+="</tr>";
		$("#tablaTalentos").append(nuevaFila);

		$("#idTalentoAgrega").val("");
		$("#btnAgregaTalento").prop('disabled', true);
		
	    });

	    
	    
	  //Evento click para agregar competencia
	    $("#idPruebaAgrega").on('change', function(event) {
		 var idPrueba = event.target.value;
		 if($("#idPruebaAgrega").val() != "") {
		     $("#btnAgregaPrueba").prop('disabled', false);
		 } else {
		     $("#btnAgregaPrueba").prop('disabled', true);
		 }
		
		
		
	    });
	    
	  //Evento click para agregar competencia
	    $("#btnAgregaPrueba").on('click', function(event) {
		event.preventDefault();
		var nombrePrueba =$('#idPruebaAgrega option:selected').html();
		var idPrueba = $("#idPruebaAgrega").val();
		if ( $("#prueba"+idPrueba).length > 0 ) {
		    alert ("La prueba ya se agrego");
		    $("#idPruebaAgrega").val("");
		    $("#btnPruebaTalento").prop('disabled', true);
		    return false;
		  }
		
		var nuevaFila="<tr id='prueba" + idPrueba +"' >";
		nuevaFila+="<td style='visibility: hidden'>null</td>";
		nuevaFila+="<td style='visibility: hidden' valor='" + idPrueba + "' ></td>";
		nuevaFila+="<td>" + nombrePrueba + "</td>";
		nuevaFila+="<td class='tdCenter'> " +
		"<a href='#' class='btn btn-default  btn-xs'>" +
		"<span data-toggle='tooltip' data-placement='top' title='Editar' class='glyphicon glyphicon-pencil'></span> </a>"+
		"<a href='#' class='btn btn-default  btn-xs'>" +
		"<span data-toggle='tooltip' data-placement='top' title='Eliminar' class='glyphicon glyphicon-trash'></span> </a>"+
		"</td>";
		
		nuevaFila+="</tr>";
		$("#tablaPruebas").append(nuevaFila);

		$("#idPruebaAgrega").val("");
		$("#btnAgregaPrueba").prop('disabled', true);
		
	    });
	
	//evento boton guardar
//	$("#guardaPerfilPuesto").on("click", function (evt) {
//	    evt.preventDefault();
//	    $('#validateFormPerfilPuesto').bootstrapValidator('validate');
//	    if ($(".has-error").length == 0 && ($(".btn-has-error").length == 0)) {
//		 url   = baseUrl+'general/validaNombre';
//		 var form_data;
//		      
//		 form_data = new FormData();
//		 form_data.append('idelemento', $("#idelemento").val());
//		 form_data.append('idperfilPuesto', $("#idperfilPuesto").val());
//		 form_data.append('idmodulo', $("#idmodulo").val());
//		 form_data.append('nombre', $("#nombrePerfilPuesto").val());
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
	
	
	  $(".eliminarPerfilPuesto").on('click',function(){
		  var buffer = 0;
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'catalogos/desactivaPerfilPuesto';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idPerfilPuesto='+$("#idRegistro").val();
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
                        setTimeout(function(){location.href = baseUrl+'catalogos/perfilPuesto'},tiempo);							
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
	
	//evento para restablecer a la perfilPuesto
	  $(".restablecerPerfilPuesto").on('click',function(){
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'catalogos/activaPerfilPuesto';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idPerfilPuesto='+$("#idRegistro").val();
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
                        setTimeout(function(){location.href = baseUrl+'catalogos/perfilPuesto'},tiempo);							
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

	$("#idPerfilPuestoBusca").on('change',function(event){
		var msg='';
		event.preventDefault();
		url   = baseUrl+'seguridad/regresaPerfiles';
		var idPerfilPuesto = $("#idPerfilPuestoBusca").val();
		if(parseInt(idPerfilPuesto) > 0 && String(token) !== '-1'){
			dataString = 'idPerfilPuesto='+idPerfilPuesto;	
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
		url   = baseUrl+'perfilPuesto/regresaNivelesComportamiento';
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
	
	$("#tipoInterprestacionPerfilPuesto").on('change',function(event){
		var msg='';
		event.preventDefault();
		var tipoInterpretacion = $("#tipoInterprestacionPerfilPuesto").val();
		if(tipoInterpretacion != '') {
		    $("#divIngresaInterpretacion").show(); 
		    $("#tipoInterprestacionPerfilPuesto").attr("disabled","disabled");
		} else {
		    $("#divIngresaInterpretacion").hide();
		}
		
		return false;		
	});
	
	
	// anadir opcion
	$( document ).delegate("#btnAgregaOpcion", "click", function() {
	    	var idResultado;
	    	var resultado;
		var orden = $('#tablaOpciones >tbody >tr').length + 1;
		var tipo = $("#tipo").val();
		var cadena = "<tr class='renglones' id='opcion-"+ orden +"' >" +
				"<td width='3%' style='visibility: hidden' ides='null'></td>" +
				"<td width='3%'style='visibility: hidden' orden='" + orden + "'></td>" +
				"<td width='8%' class='tdleft'>Opcipión:</td>" +
				"<td width='30%'><input type='text' style='width:100%;' maxlength='50'></td>"+
				"<td width='10%' class='tdright'>Respuesta asociada:</td>" +
				"<td> <select class='form-control searchs combo evento'><option value='-1'>Seleccionar</option> " ;
				
				
				$("#tablaResultados tbody tr").each(function (index) {
				    $(this).children("td").each(function (index2) 
					{
					switch (index2) 
						{
						case 0: 
						    idResultado = $(this).text();
						    break;
						case 1: 
						    resultado = $(this).text();
						    break;

						}
					});


				    cadena = cadena + "<option value='" + idResultado + "' >" + resultado + "</option> " ;


				});
				
				cadena = cadena + "</select> </td>";
				//cadena = cadena + "<td width='15%' valign='middle' ><input type='text' style='width:100%;' onkeypress='return validaEntero(event)' maxlength='2'/></td>";
			cadena = cadena +"<td width='15%' valign='middle' >  <button type='button' class='deleteOpcion'>Quitar</button></td>" +
		"</tr>";
		
		$("#tablaOpciones").append(cadena);    
	});
	
	
	//Se eliminan las preguntas de la encuesta
	$( document ).delegate(".deleteOpcion", "click", function() {

		//Si tiene idpregunta_encuesta entonces se agrega a lista de eliminados
		var id = "";
		id = $(this).parent('td').parent("tr").children("td").first().attr("ides");
//		alert ("opsEliminadas id: " + id);
		var idsOpcionesEliminadas = document.getElementById("opcionesEliminadas");
		if (id != "null") {
			if(idsOpcionesEliminadas.value == "")
				idsOpcionesEliminadas.value = id;
			else
				idsOpcionesEliminadas.value = idsOpcionesEliminadas.value + "-" + id;
		}

//alert ("opsEliminadas: " + idsOpcionesEliminadas.value);
		$(this).parent('td').parent("tr").remove();

		//Se reordenan las preguntas faltantes
		var contador = 1;
		$("#tablaOpciones tbody tr").each(function (index) {

			$(this).attr("id","opcion-"+contador);
			$(this).children("td").eq(1).text("" + contador);
			contador = contador + 1;

		});

		return false;
	});
	
	
	

	$("#confirmaAgregaPregunta").click(function(){
		var pregunta = $("#pregunta").val();
		var ordenPregunta = $("#ordenPregunta").val();
		
		
		//Valida que existe pregunta
		if(pregunta == "") {
			$("#alert").text("Se requiere la pregunta");
			$("#alert").attr("hidden", false);
			return false;
		}
		
		
		//Si es pregunta de opciones, deben existir por lo menos dos 
		//Preguntas selección unica, multiple, empleado y unica de imagen, , Matriz selección única, Matriz con escala de 0 a 10, Matriz con imagenes (predefinidas) y Matriz selección múltiple
		var cuantos = $('#tablaOpciones >tbody >tr').length ;
		if( cuantos < 2) {
			$("#alert").text("Se deben agregar por lo menos dos opciones");
			$("#alert").attr("hidden", false);
				return false;
		}
		
		
		
		var id = "";
		var orden = "";
		var opcion = "";
		var valor = "";
		var archivo = "";
		var opciones = "";
		var contador = 0;
		var cuantos = $('#tablaOpciones >tbody >tr').length ;
		var alertaOpcion = false;
		//Preguntas selección unica
		//Se obtiene los valores de la tabla de opciones
			$("#tablaOpciones tbody tr").each(function (index) {

				$(this).children("td").each(function (index2)  
				{
					switch (index2) 
					{
					case 0: id = $(this).attr("ides");
						break;
					case 1: orden = $(this).attr("orden");
						break;
					case 3: 
						opcion = $(this).children("input").val();
						break;
					case 5: 
						valor = $(this).children("select").val();
						break;
					}
				});

				if(opcion=="")
					alertaOpcion = true;

				opciones = opciones + id + "//"+ orden + "//" + opcion + "//" + valor;

				if(contador < cuantos - 1  )
					opciones = opciones + "&&";
				
				contador = contador +1;

			});
			
		

		//Valida que todas las opciones tengan un valor
		//Preguntas  selección unica, multiple, empleado, unica de imagen, Matriz selección única, Matriz con escala de 0 a 10, Matriz con imagenes (predefinidas) y Matriz selección múltiple
			if(alertaOpcion ) {
				$("#alert").text("Se deben llenar todas las opciones");
				$("#alert").attr("hidden", false);
				return false;
			}
		
		
			
		if( $("#filaNo").val() == "null") {
			var nuevaFila="<tr id=fila-"+ ordenPregunta +" >";
			nuevaFila+="<td style='visibility: hidden'>null</td>";
			nuevaFila+="<td>" + ordenPregunta + "</td>";
			nuevaFila+="<td>" + pregunta + "</td>";
			nuevaFila+="<td><a href='#' class='btn btn-default btn-xs'> <span data-toggle='tooltip' data-placement='top' title='Editar' class='glyphicon glyphicon-pencil'></span></a> " +
			"<a href='#' class='btn btn-default btn-xs modaleliminar'><span data-toggle='tooltip' data-placement='top' title='Eliminar' class='glyphicon glyphicon-trash'></span></a>" +
//			"<a href='#' class='btn'> <button class='btn btn-primary editable  btn-danger' data-toggle='modal' data-target='#confirmaEliminaModal'> Eliminar </button></a></td>" +
			"</td>";
			nuevaFila+="<td style='visibility: hidden' valor='" + opciones + "' ></td>";
			nuevaFila+="</tr>";
			$("#tablaPreguntas").append(nuevaFila);
		} else {
			$("#fila-"+$("#filaNo").val()).children("td").each(function (index2) 
					{
				switch (index2) 
				{
				case 0:
					break;
				case 1: 
					break;
				case 2: 
					$(this).text(pregunta);
					break;
				case 3: 
					$(this).text(tipoVal);
					$(this).attr("tipoPregunta", tipoInt);
					break;
				case 4: 
					$(this).text(obligatoriedadVal);
					$(this).attr("tipoObligada", obligatoriedadInt);
					break;
				case 6: 
					$(this).attr("valor", opciones);
					break;
				case 7: 
					$(this).attr("valor", respuestas);
					break;

				}
					});
			
		}
		
			



		$('#btnCloseModal').click();

	});
	
	
	
	$("#confirmaAgregaResultado").click(function(){
		var resultado = $("#resultado").val();
		var descripcionResultado = $("#descripcionResultado").val();
		
		//Valida que existe pregunta
		if(resultado == "") {
			$("#alert").text("Se requiere el resultado");
			$("#alert").attr("hidden", false);
			return false;
		}
		
			
		if( $("#filaNo").val() == "null") {
			var nuevaFila="<tr>";
			nuevaFila+="<td style='visibility: hidden'>null</td>";
			nuevaFila+="<td>" + resultado + "</td>";
			nuevaFila+="<td>" + descripcionResultado + "</td>";
			nuevaFila+="<td><a href='#' class='btn btn-default btn-xs'> <span data-toggle='tooltip' data-placement='top' title='Editar' class='glyphicon glyphicon-pencil'></span></a> " +
			"<a href='#' class='btn btn-default btn-xs modaleliminar'><span data-toggle='tooltip' data-placement='top' title='Eliminar' class='glyphicon glyphicon-trash'></span></a>" +
//			"<a href='#' class='btn'> <button class='btn btn-primary editable  btn-danger' data-toggle='modal' data-target='#confirmaEliminaModal'> Eliminar </button></a></td>" +
			"</td>";
			nuevaFila+="</tr>";
			$("#tablaResultados").append(nuevaFila);
		} else {
			$("#fila-"+$("#filaNo").val()).children("td").each(function (index2) 
					{
				switch (index2) 
				{
				case 0:
					break;
				case 1: 
					break;
				case 2: 
					$(this).text(pregunta);
					break;
				case 3: 
					$(this).text(tipoVal);
					$(this).attr("tipoPregunta", tipoInt);
					break;
				case 4: 
					$(this).text(obligatoriedadVal);
					$(this).attr("tipoObligada", obligatoriedadInt);
					break;
				case 6: 
					$(this).attr("valor", opciones);
					break;
				case 7: 
					$(this).attr("valor", respuestas);
					break;

				}
					});
			
		}
		
			
		$("#agregaResultadoModal").modal('hide');


	});
	
});



function abrePerfilPuesto(){
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




//function guardaPerfilPuesto(){
//	var id = "";
//	var comportamiento = "";
//	var comportamientos ="";
//	var opciones = "";
//	var contador = 0;
//	var cuantos = $('#tablaComportamientos >tbody >tr').length ;
//
//	
//	if (cuantos == 0) {
//	    alert ("Se debe agregar por lo meno un comportamiento");
//	    return false;
//	}
//
//	$("#tablaComportamientos tbody tr").each(function (index) {
//		$(this).children("td").each(function (index2) 
//				{
//			switch (index2) 
//			{
//			case 0: 
//			    id = $(this).text();
//			    break;
//			case 1: 
//			    comportamiento = $(this).text();
//			    break;
//			case 2: 
//			    break;
//			case 3: 
//			    opciones = $(this).attr("valor");
//			    break;
//
//			}
//				});
//
//		if(id == "null" || opciones !="") {
//		    comportamientos = comportamientos + id + "--"+ comportamiento + "--" + opciones;
//		    if(contador < cuantos - 1  )
//			    comportamientos = comportamientos + "@@";
//		}
//		
//		contador = contador +1;
//
//	});
//	
//	$("#comportamientos").val(comportamientos);
//	return true;
//}



function abreAgregaPregunta(){
//	alert("se abre");
	$("#alert").attr("hidden", true);
	$("#tipo").attr("disabled", false);
	var orden = $('#tablaPreguntas >tbody >tr').length + 1;
	$("#ordenPregunta").val(orden);
	$("#pregunta").val("");
	$("#obligatoriedad").val("1");
	$("#filaNo").val("null");
	$("#tablaOpciones tbody tr").each(function (index) {
		this.remove();
	});

	$("#divOpciones").hide(); 

	return true;
}



function abreAgregaResultado(){
	$("#alert").attr("hidden", true);
	$("#resultado").val("");
	$("#descripcionResultado").val("");
	$("#filaNo").val("null");
	return true;
}


function generaPerfilPuesto(){
    alert("generaPerfilPuesto");
    
  
    
    var etapa =  $("#etapa").val();
    alert("etapa: " + etapa);
    
    if(etapa == 1) {
	
	//por cargar resultados
	var id = "";
	var contador = 0;
	var resultado = "";
	var descripcion = "";
	var resultados = "";
	var cuantos = $('#tablaResultados >tbody >tr').length ;
	alert("cuantos:" + cuantos);
	if (cuantos == 0) {
		alert ("Se debe agregar por lo menos un resultado");
		return false;
	}
	
	$("#tablaResultados tbody tr").each(function (index) {
	    $(this).children("td").each(function (index2) 
		{
		switch (index2) 
			{
			case 0: 
			    id = $(this).text();
			    break;
			case 1: 
			    resultado = $(this).text();
			    break;
			case 2: 
			    descripcion = $(this).text();
			    break;

			}
		});


	    	resultados = resultados + id + "--"+ resultado + "--" + descripcion;

		if(contador < cuantos - 1  )
		    resultados = resultados + "@@";
		
		contador = contador +1;

	});
	
	alert ("resultados: " + resultados);
	
	$("#resultados").val(resultados);
	
    }
    
    if(etapa == 2 || etapa == 3) {
	//por cargar preguntas
	var id = "";
	var orden = "";
	var pregunta = "";
	var obligada = "";
	var preguntas="";
	var opciones = "";
	var contador = 0;
	var cuantos = $('#tablaPreguntas >tbody >tr').length ;
	

	if (cuantos == 0) {
		alert ("Se debe agregar por lo menos una pregunta");
		return false;
	}

	$("#tablaPreguntas tbody tr").each(function (index) {

		$(this).children("td").each(function (index2) 
				{
			switch (index2) 
			{
			case 0: id = $(this).text();
			break;
			case 1: orden = $(this).text();
			break;
			case 2: pregunta = $(this).text();
			break;
			case 4: opciones = $(this).attr("valor");
			break;

			}
				});


		preguntas = preguntas + id + "--"+ orden + "--" + pregunta + "--" + opciones ;

		if(contador < cuantos - 1  )
			preguntas = preguntas + "@@";
		
		contador = contador +1;

	});
	
	alert ("preguntas: " + preguntas);
	
	$("#preguntas").val(preguntas);
    } 
	
	
	return true;
}

