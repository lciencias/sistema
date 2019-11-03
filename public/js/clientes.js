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
	var formCliente = $('validateFormCliente');
	formCliente.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormCliente').bootstrapValidator({
	    message: I18n._("nombreInvalido"),        
	    feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	    },
	    fields: {
		nombreComercialCliente: {
        	    message: I18n._("nombreInvalido"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("datoObligatorio")
        		},	
        		stringLength: {
        		    min: 3,
        		    max: 50,
        		    message: I18n._("minimoClienteCaracteres")
        		}, 
//        		remote: {
//        		    url: baseUrl+'general/validaNombre',
//                            headers: {
//                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//               			},
//               			type: 'POST',
//               			data : {
//               			    idelemento: $('#idelemento').val(),
//               			    idcliente: $('#idcliente').val(),
//               			    idmodulo : $('#idmodulo').val()
//               			},
//                        	
//                    	}
        	    }
        	},
        	razonSocialCliente:{
        	    message: I18n._("nombreInvalido"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("datoObligatorio")
        		},
        		stringLength: {
        		    min: 6,
        		    max: 50,
        		    message: I18n._("minimoRazonCaracteres")
        		}
        	    }            
        	},
        	rfcCliente:{
        	    message: I18n._("nombreInvalido"),
        	    validators: {
        		stringLength: {
        		    min: 13,
        		    max: 13,
        		    message: 'El RFC debe contener 13 caracteres'
        		}
        	    }            
        	},
        	nombreResponsable:{
        	    message: I18n._("nombreInvalido"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("datoObligatorio")
        		},
        		stringLength: {
        		    min: 3,
        		    max: 50,
        		    message: I18n._("minimoNombreCaracteres")
        		}
        	    }                        	
        	},
        	paternoResponsable:{
        	    message: I18n._("nombreInvalido"),
                    validators: {
                        notEmpty: {
                            message: I18n._("datoObligatorio")
                        },
                        stringLength: {
                            min: 3,
                            max: 50,
                            message: I18n._("minimoPaternoCaracteres")
                        }
                    }                        	
                },  
                maternoResponsable:{
        	    message: I18n._("nombreInvalido"),
                    validators: {
                        notEmpty: {
                            message: I18n._("datoObligatorio")
                        },
                        stringLength: {
                            min: 3,
                            max: 50,
                            message: I18n._("minimoPaternoCaracteres")
                        }
                    }                        	
                },  
                emailResponsable:{
                    message: I18n._("nombreInvalido"),
                    validators: {
                        notEmpty: {
                            message: I18n._("datoObligatorio")
                        },
                        regexp: {
                            regexp: /^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/,
                            message: I18n._("nombreCaracteres")
                        },
                        stringLength: {
                            min: 6,
                            max: 50,
                            message: I18n._("minimoCorreoCaracteres")
                        },  
//                        remote: {
//                            url: baseUrl+'cliente/validaMailRepresentante',
//                            headers: {
//               			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//               			},
//               			type: 'POST',
//               			data : {
//               			   	idusuario: ''
//            			},
//                        	
//                    	}
                    }
                },
	    }
	});	
	

	
	//evento boton guardar
	$("#guardaClienteas").on("click",function(){
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
	
	
	//evento para restablecer a la cliente
	$(".restablecerEmp").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'seguridad/activaCliente';
		var tmp = $(this).attr('id').split('-');
		var id  = tmp[1];
		var div = 'modal-restableceEmp-'+id; 
		if(parseInt(id) > 0 && String(token) !== ''){
			dataString = 'idCliente='+id;	
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
						exito("La Cliente ha sido activado");
						 setTimeout(function(){
							 location.href = baseUrl+"seguridad/cliente";
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

	$("#idClienteBusca").on('change',function(event){
		var msg='';
		event.preventDefault();
		url   = baseUrl+'seguridad/regresaPerfiles';
		var idCliente = $("#idClienteBusca").val();
		if(parseInt(idCliente) > 0 && String(token) !== '-1'){
			dataString = 'idCliente='+idCliente;	
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
	
	
	
	
	$(".estado").on('change',function(event){
		var msg='';
		event.preventDefault();
		url   = baseUrl+'cliente/regresaMunicipios';
		var idCombo = event.target.id;
		var idEstado = null;
		
		if(idCombo == 'estadoFiscal')
		    idEstado =  $("#estadoFiscal").val();
		else
		    idEstado =  $("#estadoComercial").val();
		
		
		if(parseInt(idEstado) > 0 && String(token) !== '-1'){
			dataString = 'idEstado='+idEstado;	
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
					    
					    	if(idCombo == 'estadoFiscal')
					    	    $("#delMunFiscal").empty();
					    	else
					    	    $("#delMunComercial").empty();
					    	
						var municipios = data.municipios;												
						if(municipios.length > 0){							
							msg += "<option value=''>Seleccione</option>";	
							$.each(municipios, function(i, item) {
								msg += "<option value='"+municipios[i].idmunicipio+"'>"+municipios[i].nombre+"</option>";					
							});
						}else{
							msg += "<option value=''>No existen municipios</option>";
						}
						if(idCombo == 'estadoFiscal') {
						    $("#delMunFiscal").html(msg);
						    $("#delMunFiscal").html(msg);
						} else {
						    $("#delMunComercial").html(msg);
						    $("#delMunComercial").html(msg);
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
		} else {
		    msg += "<option value=''>Seleccione</option>";	
		    
		    if(idCombo == 'estadoFiscal') {
			$("#delMunFiscal").empty();
			$("#delMunFiscal").html(msg);
			$("#delMunFiscal").html(msg);
			
		    }
		    else {
			$("#delMunComercial").empty();
			$("#delMunComercial").html(msg);
			$("#delMunComercial").html(msg);
		    }
		    $('#procesando').fadeIn(1000).html("");
		}
		return false;		
	});
	
	
	
	
	
	$("#nombre").on('focus',function(){
		return false;
	});
});