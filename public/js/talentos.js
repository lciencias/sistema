var url;
var idPerfil;
var id;
var dataString;
var token;
var vacio = '';

$(document).ready(function() {	
	token = $('meta[name="csrf-token"]').attr('content');
    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });
	var formTalento = $('validateFormTalento');
	formTalento.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormTalento').bootstrapValidator({
	    message: I18n._("nombreInvalido"),        
	    feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	    },
	    fields: {
		nombreTalento: {
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
        		    message: I18n._("minimoTalentoCaracteres")
        		}, 
//        		remote: {
//        		    url: baseUrl+'general/validaNombre',
//                            headers: {
//                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//               			},
//               			type: 'POST',
//               			data : {
//               			    idelemento: $('#idelemento').val(),
//               			    idtalento: $('#idtalento').val(),
//               			    idmodulo : $('#idmodulo').val(),
//               			},
//                        	
//                    	}
        	    }
        	},
        	definicionTalento:{
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
	    }
	});	
	
	

	
	//evento boton guardar
//	$("#guardaTalento").on("click", function (evt) {
//	    evt.preventDefault();
//	    $('#validateFormTalento').bootstrapValidator('validate');
//	    if ($(".has-error").length == 0 && ($(".btn-has-error").length == 0)) {
//		 url   = baseUrl+'general/validaNombre';
//		 var form_data;
//		      
//		 form_data = new FormData();
//		 form_data.append('idelemento', $("#idelemento").val());
//		 form_data.append('idtalento', $("#idtalento").val());
//		 form_data.append('idmodulo', $("#idmodulo").val());
//		 form_data.append('nombre', $("#nombreTalento").val());
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
	
	
	  $(".eliminarTalento").on('click',function(){
	      alert("s");
		  var buffer = 0;
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'catalogos/desactivaTalento';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idTalento='+$("#idRegistro").val();
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
                        setTimeout(function(){location.href = baseUrl+'catalogos/talento'},tiempo);							
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
	
	//evento para restablecer a la talento
	  $(".restablecerTalento").on('click',function(){
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'catalogos/activaTalento';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idTalento='+$("#idRegistro").val();
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
                        setTimeout(function(){location.href = baseUrl+'catalogos/talento'},tiempo);							
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

	$("#idTalentoBusca").on('change',function(event){
		var msg='';
		event.preventDefault();
		url   = baseUrl+'seguridad/regresaPerfiles';
		var idTalento = $("#idTalentoBusca").val();
		if(parseInt(idTalento) > 0 && String(token) !== '-1'){
			dataString = 'idTalento='+idTalento;	
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
});
