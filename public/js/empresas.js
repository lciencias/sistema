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
	var formEmpresa = $('validateFormEmpresa');
	formEmpresa.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormEmpresa').bootstrapValidator({
	    message: I18n._("nombreInvalido"),        
	    feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	    },
	    fields: {
		nombreEmpresa: {
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
        		    message: I18n._("minimoEmpresaCaracteres")
        		}, remote: {
        		    url: baseUrl+'general/validaNombre',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               			},
               			type: 'POST',
               			data : {
               			    idelemento: $('#idelemento').val(),
               			    idempresa: $('#idempresa').val(),
               			    idmodulo : $('#idmodulo').val()
               			},
                        	
                    	}
        	    }
        	},
        	razonSocialEmpresa:{
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
        	nombreRepresentanteEmpresa:{
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
        		    message: I18n._("minimoNombreCaracteres")
        		}
        	    }                        	
        	},
        	paternoRepresentanteEmpresa:{
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
                            message: I18n._("minimoPaternoCaracteres")
                        }
                    }                        	
                },  
                maternoRepresentanteEmpresa:{
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
                            message: I18n._("minimoPaternoCaracteres")
                        }
                    }                        	
                },  
                emailRepresentanteEmpresa:{
                    message: I18n._("nombreInvalido"),
                    validators: {
                        notEmpty: {
                            message: I18n._("nombreObligatorio")
                        },
                        regexp: {
                            regexp: /^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/,
                            message: I18n._("nombreCaracteres")
                        },
                        stringLength: {
                            min: 6,
                            max: 50,
                            message: I18n._("minimoCorreoCaracteres")
                        },  remote: {
                            url: baseUrl+'empresa/validaMailRepresentante',
                            headers: {
               			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               			},
               			type: 'POST',
               			data : {
               			   	idusuario: ''
            			},
                        	
                    	}
                    }
                },
//                rfc1:{
//                    message: I18n._("nombreInvalido"),
//                    validators: {
//                	 notEmpty: {
//                             message: I18n._("nombreObligatorio")
//                         },
//                	regexp: {
//                	    regexp: /^[a-zA-Z_\.\s]+$/,
//                	    message: I18n._("nombreCaracteres")
//                        },
//                        stringLength: {
//                            min: 0,
//                            max: 4,
//                            message: I18n._("minimoRFC1Caracteres")
//                        }                	    
//                    }
//                },
//                rfc2:{
//                    message: I18n._("nombreInvalido"),
//                    validators: {
//                	regexp: {
//                	    regexp: /^[0-9]+$/,
//                	    message: I18n._("nombreCaracteres")
//                	},
//                	stringLength: {
//                	    min: 0,
//                	    max: 6,
//                	    message: I18n._("minimoRFC2Caracteres")
//                	}
//                    }
//                },   
//                rfc3:{
//                    message: I18n._("nombreInvalido"),
//                    validators: {
//                	regexp: {
//                	    regexp: /^[a-zA-Z0-9_\.\s]+$/,
//                	    message: I18n._("nombreCaracteres")
//                	},
//                	stringLength: {
//                	    min: 0,
//                	    max: 6,
//                	    message: I18n._("minimoRFC3Caracteres")
//                	}
//                    }
//                }                
	    }
	});	
	
  //revision de fecha de rfc
	$("#rfc2").on("change",function(){
		  var rfc2  = String($("#rfc2").val());
		  if(!revisaFecha(rfc2)){        					  
			  $("#rfc2").val('');
			  $("#rfc2").focus();
			  error("Error en la fecha del rfc");
			  return false;
		  }
	});
	

	
	//evento boton guardar
	$("#guardaEmpresaas").on("click",function(){
		var campo   = "";
		var rfc     = "";
		var rfc1    = "";
		var rfc2    = "";
		var rfc3    = "";
		var email   = "";
		var errores = 0;
		$(".required").each(function(){
			if(String($(this).val().trim()) === ''){
				errores++;
				campo = $(this).attr('data')
				if(parseInt(errores) === 1)
					error("El campo "+campo+" es obligatorio");							
			}
		});
		if(parseInt(errores) > 0){
			return false;
		}
		
		// validacion de rfc
    	rfc1  = String($("#rfc1").val());
    	rfc2  = String($("#rfc2").val());
    	rfc3  = String($("#rfc3").val());
    	if(rfc1 !== '' && rfc2 !== ''){
    		if(revisaFecha(rfc2)){        		
        		rfc = rfc1+rfc2+rfc3;
        		rfc = rfc.toUpperCase();
        		if(!validarRFC(rfc)){
        			$("#rfc1").val('');
      			    $("#rfc2").val('');
      			    $("#rfc3").val('');
    			    $("#rfc1").focus();        			    			
        			error("Error: El RFC es incorrecto");
        			return false;
        		}
    		}
    		else{
  			    $("#rfc2").val('');
			    $("#rfc2").focus();        			
    			error("Error: La fecha del RFC es incorrecta");
    			return false;
    		}
    	}
    	email = $("#email_representante").val().trim();
    	//validacion de email
    	if(String(email) !== ''){
			if(!valEmail(email)){
				$("#email_representante").val('');
				$("#email_representante").focus();
				error("Error: El correo electr&oacute;nico es incorrecto.");
				return false;
			}
		}     	
	});
	
	
	//evento para restablecer a la empresa
	$(".restablecerEmp").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'seguridad/activaEmpresa';
		var tmp = $(this).attr('id').split('-');
		var id  = tmp[1];
		var div = 'modal-restableceEmp-'+id; 
		if(parseInt(id) > 0 && String(token) !== ''){
			dataString = 'idEmpresa='+id;	
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
						exito("La Empresa ha sido activado");
						 setTimeout(function(){
							 location.href = baseUrl+"seguridad/empresa";
						},1500);							
					}else{						
						error(vacio);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$('#'+div).modal('hide');
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

	$("#idEmpresaBusca").on('change',function(event){
		var msg='';
		event.preventDefault();
		url   = baseUrl+'seguridad/regresaPerfiles';
		var idEmpresa = $("#idEmpresaBusca").val();
		if(parseInt(idEmpresa) > 0 && String(token) !== '-1'){
			dataString = 'idEmpresa='+idEmpresa;	
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
	
	$(".modalNuevaEmpresa").on("click", function (evt) {
	    $("#modalDetalleEmpresa").modal('show');
	    
	    
	});
	
	$("#guardaEmpresa").on("click", function (evt) {
	    evt.preventDefault();
	    $('#validateFormEmpresa').bootstrapValidator('validate');
	    if ($(".has-error").length == 0 && ($(".btn-has-error").length == 0)) {
		 url   = baseUrl+'seguridad/guardaEmpresa';
		      var file_data;
		      var form_data;
		      
		      file_data = $('#image').prop('files')[0];
		      form_data = new FormData();
		      form_data.append('id', $("#idelemento").val());
		      form_data.append('image', file_data);
		      form_data.append('nombreEmpresa', $("#nombreEmpresa").val());
		      form_data.append('razonSocialEmpresa', $("#razonSocialEmpresa").val());
		      form_data.append('direccionEmpresa', $("#direccionEmpresa").val());
		      form_data.append('nombreRepresentanteEmpresa', $("#nombreRepresentanteEmpresa").val());
		      form_data.append('paternoRepresentanteEmpresa', $("#paternoRepresentanteEmpresa").val());
		      form_data.append('maternoRepresentanteEmpresa', $("#maternoRepresentanteEmpresa").val());
		      form_data.append('emailRepresentanteEmpresa', $("#emailRepresentanteEmpresa").val());
		      form_data.append('rfcEmpresa', $("#rfcEmpresa").val());
		      
			
		      //dataString = 'idMovimiento='+$("#idRegistro").val();
		      $.ajax({
			type: 'POST',
			url : url,
			contentType: false,
			cache: false,
			headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			//dataType: 'json',
			//data: dataString,
			 processData: false,
			data: form_data,
			dataType: 'json',
			beforeSend: function(){
			$('#procesando').html(procesando);
			},			
			success: function(info){
			    $('#procesando').fadeIn(1000).html("");
			    if(parseInt(info.exito) == 1){
				$("#modalDetalleEmpresa").modal('hide');
				exito(info.msg);
		                setTimeout(function(){location.href = baseUrl+'seguridad/empresa'},tiempo);							
			    }else{
				error(info.msg);
			    }						
			},
			error: function (xhr, ajaxOptions, thrownError) {				
			    $('#procesando').fadeIn(1000).html("");
			}
			
		      });
	    }
	    
	    return false;
	});
	
	
	$(".editarEmpresa").on("click", function (evt) {
	    evt.preventDefault();
	    id = $(this).attr("id");
		 url   = baseUrl+'seguridad/editarEmpresa';
		 var file_data;
		 var form_data;

		 form_data = new FormData();
		 form_data.append('idEmpresa', id);
		 $.ajax({
		     type: 'POST',
		     url : url,
		     contentType: false,
		     cache: false,
		     headers: {
			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		     },
		     processData: false,
		     data: form_data,
		     dataType: 'json',
		     beforeSend: function(){
			 $('#procesando').html(procesando);
		     },			
		     success: function(info){
			 $('#procesando').fadeIn(1000).html("");
			 if(parseInt(info.exito) == 1){
			     $("#idelemento").val(info.empresa.idempresa);
			     $("#nombreEmpresa").val(info.empresa.nombre);
			     $("#razonSocialEmpresa").val(info.empresa.nombre);
			     $("#direccionEmpresa").val(info.empresa.direccion);
			     $("#nombreRepresentanteEmpresa").val(info.empresa.nombre_representante);
			     $("#paternoRepresentanteEmpresa").val(info.empresa.paterno_representante);
			     $("#maternoRepresentanteEmpresa").val(info.empresa.materno_representante);
			     $("#emailRepresentanteEmpresa").val(info.empresa.email_representante);
			     $("#rfcEmpresa").val(info.empresa.rfc);
			     $("#modalDetalleEmpresa").modal('show');
			     
			 }else{
			     error(info.msg);
			 }						
		     },
		     error: function (xhr, ajaxOptions, thrownError) {				
			 $('#procesando').fadeIn(1000).html("");
		     }

		 });
	    
	    return false;
	});
	
	
	
	
});