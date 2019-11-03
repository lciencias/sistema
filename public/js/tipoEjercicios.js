var url;
var token;

$(document).ready(function() {	
	token = $('meta[name="csrf-token"]').attr('content');
    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });
	var formTipoEjercicio = $('validateFormTipoEjercicio');
	formTipoEjercicio.on('submit', function(e){
	    e.preventDefault();
	});

	$('#validateFormTipoEjercicio').bootstrapValidator({
	    message: I18n._("nombreInvalido"),        
	    feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	    },
	    fields: {
		nombreTipoEjercicio: {
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
        		    message: I18n._("minimoTipoEjercicioCaracteres")
        		}, 
        	    }
        	},
        	definicionTipoEjercicio:{
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
        	tipoTipoEjercicio:{
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
//	$("#guardaTipoEjercicio").on("click", function (evt) {
//	    evt.preventDefault();
//	    $('#validateFormTipoEjercicio').bootstrapValidator('validate');
//	    if ($(".has-error").length == 0 && ($(".btn-has-error").length == 0)) {
//		 url   = baseUrl+'general/validaNombre';
//		 var form_data;
//		      
//		 form_data = new FormData();
//		 form_data.append('idelemento', $("#idelemento").val());
//		 form_data.append('idtipoEjercicio', $("#idtipoEjercicio").val());
//		 form_data.append('idmodulo', $("#idmodulo").val());
//		 form_data.append('nombre', $("#nombreTipoEjercicio").val());
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
	
	
	  $(".eliminarTipoEjercicio").on('click',function(){
		  var buffer = 0;
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'catalogos/desactivaTipoEjercicio';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idTipoEjercicio='+$("#idRegistro").val();
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
                        setTimeout(function(){location.href = baseUrl+'catalogos/tipoEjercicio'},tiempo);							
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
	
	//evento para restablecer a la tipoEjercicio
	  $(".restablecerTipoEjercicio").on('click',function(){
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'catalogos/activaTipoEjercicio';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idTipoEjercicio='+$("#idRegistro").val();
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
                        setTimeout(function(){location.href = baseUrl+'catalogos/tipoEjercicio'},tiempo);							
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

	$("#idTipoEjercicioBusca").on('change',function(event){
		var msg='';
		event.preventDefault();
		url   = baseUrl+'seguridad/regresaPerfiles';
		var idTipoEjercicio = $("#idTipoEjercicioBusca").val();
		if(parseInt(idTipoEjercicio) > 0 && String(token) !== '-1'){
			dataString = 'idTipoEjercicio='+idTipoEjercicio;	
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
	
	
	
	$("#confirmaAgregaEjercicio").click(function(){
	    alert("sdf");
	    	var orden = null;
		var ejercicio= $("#nombreEjercicio").val();
		var descripcion = $("#descripcionEjercicio").val();
		
		
		if( $("#idEjercicio").val() == "null") {
			var nuevaFila="<tr id='null' >";
			nuevaFila+="<td style='visibility: hidden'>null</td>";
			nuevaFila+="<td>" + ejercicio + "</td>";
			nuevaFila+="<td>" + descripcion + "</td>";
			nuevaFila+="<td class='tdCenter'> " +
			"<a href='#' class='btn btn-default  btn-xs'>" +
			"<span data-toggle='tooltip' data-placement='top' title='{{Lang::get('leyendas.editarRegistro')}}' class='glyphicon glyphicon-pencil'></span>"
			"</td>" +
			"</td>";
			nuevaFila+="</tr>";
			$("#tablaEjercicios").append(nuevaFila);
		} else {
		    $("#fila-"+$("#idEjercicio").val()).children("td").each(function (index2) 
			{
				switch (index2) 
				{
				case 0:
					break;
				case 1: 
				    	$(this).text(ejercicio);
					break;
				case 2: 
				    	$(this).text(descripcion);
					break;

				}
					});
			
		}

		$("#modalEjercicio").modal('hide');

	});
	
	
	

	$(".editaEjercicio").on('click',function(event){
	    var idEjercicio = event.target.id;
	    $("#idEjercicio").val(idEjercicio);
	    
	    
	    var nombre;
	    var descripcion;
	     $("#fila-"+$("#idEjercicio").val()).children("td").each(function (index2) 
			{
				switch (index2) 
				{
				case 0:
					break;
				case 1: 
				    	nombre = $(this).text();
					break;
				case 2: 
				    	descripcion = $(this).text();
					break;
				case 3: 
					break;

				}
					});
	     
	     $("#nombreEjercicio").val(nombre);
	     $("#descripcionEjercicio").val(nombre)
	     
	});
});



function abreTipoEjercicio(){
    $("#nombreEjercicio").val("");
    $("#descripcionEjercicio").val("");
    $("#idEjercicio").val("null");
    return true;
}




function guardaTipoEjercicio(){
    alert("dsfdgg");
    throw "error";
    
	var id = "";
	var ejercicio = "";
	var ejercicios ="";
	var descripcion = "";
	var contador = 0;
	var cuantos = $('#tablaEjercicios >tbody >tr').length ;
	
	cuantos = 0;
	if (cuantos == 0) {
		alert ("Se debe agregar por lo menos una pregunta");
		return false;
	}
	

	$("#tablaEjercicios tbody tr").each(function (index) {
		$(this).children("td").each(function (index2) 
				{
			switch (index2) 
			{
			case 0: 
			    id = $(this).text();
			    break;
			case 1: 
			    ejercicio = $(this).text();
			    break;
			case 2: 
			    descripcion = $(this).text();
			    break;
			case 3: 
			    opciones = $(this).attr("valor");
			    break;

			}
				});

		if(id == "null" || opciones !="") {
		    ejercicios = ejercicios + id + "--"+ ejercicio + "--" + descripcion;
		    if(contador < cuantos - 1  )
			ejercicios = ejercicios + "@@";
		}
		
		contador = contador +1;

	});
	
	$("#ejercicios").val(ejercicios);
}

