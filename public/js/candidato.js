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
	var formCandidato = $('validateFormCandidato');
	formCandidato.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormCandidato').bootstrapValidator({
	    message: I18n._("nombreInvalido"),        
	    feedbackIcons: {
		valid: 'glyphicon glyphicon-ok',
		invalid: 'glyphicon glyphicon-remove',
		validating: 'glyphicon glyphicon-refresh'
	    },
	    fields: {
		idclienteCandidato: {
        	    message: I18n._("nombreInvalido"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("datoObligatorio")
        		},	
        	    }
        	},
		nombreCandidato: {
        	    message: I18n._("nombreInvalido"),
        	    validators: {
        		notEmpty: {
        		    message: I18n._("datoObligatorio")
        		},	
        		stringLength: {
        		    min: 3,
        		    max: 50,
        		    message: I18n._("minimoCandidatoCaracteres")
        		}, 
        	    }
        	},
        	paternoCandidato:{
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
        	noEmpleadoeCandidato:{
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
        	generoCandidato:{
        	    message: I18n._("nombreInvalido"),
                    validators: {
                        notEmpty: {
                            message: I18n._("datoObligatorio")
                        },
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
//                            url: baseUrl+'candidato/validaMailRepresentante',
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
	

	
	//evento para restablecer a la candidato
	$(".restablecerEmp").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'seguridad/activaCandidato';
		var tmp = $(this).attr('id').split('-');
		var id  = tmp[1];
		var div = 'modal-restableceEmp-'+id; 
		if(parseInt(id) > 0 && String(token) !== ''){
			dataString = 'idCandidato='+id;	
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
						exito("La Candidato ha sido activado");
						 setTimeout(function(){
							 location.href = baseUrl+"seguridad/candidato";
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

	$("#idCandidatoBusca").on('change',function(event){
		var msg='';
		event.preventDefault();
		url   = baseUrl+'seguridad/regresaPerfiles';
		var idCandidato = $("#idCandidatoBusca").val();
		if(parseInt(idCandidato) > 0 && String(token) !== '-1'){
			dataString = 'idCandidato='+idCandidato;	
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
		url   = baseUrl+'candidato/regresaMunicipios';
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