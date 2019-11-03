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

	var formUser = $('validateFormUser');
	formUser.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormUser').bootstrapValidator({
        message: I18n._("valorInvalido"),
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	name: {
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
                        min: 6,
                        max: 50,
                        message: I18n._("minimoUserCaracteres")
                    }
                }
            },
            email:{
//                message:  I18n._("correoInvalido"),
                validators: {
                    notEmpty: {
                    	message: I18n._("correoObligatorio")
                    },
                    regexp: {
                        regexp: /^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/,
                        message: I18n._("correoInvalido"),
                    },
                    stringLength: {
                        min: 6,
                        max: 50,
                        message: I18n._("minimoCorreoCaracteresUser")                        	
                    },  remote: {
                        url: baseUrl+'usuario/validaMail',
                        headers: {
           			 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           			},
           			type: 'POST',
           			data : {
           			   	idusuario: $('#id').val()
        			},
                    	
                	}
                }
            },
            idperfil: {
            	message:  I18n._("perfilInvalido"),
                validators: {
                    notEmpty: {
                    	message: I18n._("perfilUsuarioObligatorio")
                    }                    
                }
            },
            idEmpresaBusca:{
            	message:  I18n._("EmpresaInvalido"),
                validators: {
                    notEmpty: {
                    	message: I18n._("empresaUsuarioObligatorio")
                    }                    
                }
        	
            }
        }
    });
		
	$("#agregaModulos").hide();	
	$("#submodulosSeleccionados").val();
	$("#submodulosNoSeleccionados").val();
	
	if( ($("#id") !== undefined) && (String($("#id").val()) !== '') && (parseInt($("#id").val()) > 0) ){
		url   = baseUrl+'seguridad/buscaModulosPerfil';
		idPerfil = $("#idperfil").val();
		dataString = 'idPerfil='+idPerfil+"&id="+$("#id").val();
		if(parseInt(idPerfil) > 0 && String(token) !== ''){
			$.ajax({
				type: 'POST',
				url : url,		
				 headers: {
	                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	             },
				data: dataString,
				beforeSend: function(){
					$('#procesando').html(procesando);
				},			
				success: function(data){
					$("#agregaModulos").show();
					$('#tableModulos').html(data);
					$('#procesando').fadeIn(1000).html("");
					return false;
				},
				error: function (xhr, ajaxOptions, thrownError) {
					error(vacio);
					return false;
			    },
				complete: function(){
				}				
			});
		}
		return false;
	}
    		
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
						$("#idperfil").empty();
						var perfiles = data.perfiles;												
						if(perfiles.length > 0){							
							msg += "<option value=''>Seleccione</option>";	
							$.each(perfiles, function(i, item) {
								msg += "<option value='"+perfiles[i].idperfil+"'>"+perfiles[i].nombre+"</option>";					
							});
						}else{
							msg += "<option value=''>No existen Perfiles</option>";
						}
						$("#idperfil").html(msg);
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
		    $("#idperfil").empty();
		    msg += "<option value=''>Seleccione</option>";	
		    $("#idperfil").html(msg);
		    $("#idperfil").html(msg);
		    $('#procesando').fadeIn(1000).html("");
		}
		return false;		
	});

	//evento de perfil
	$(".perfiln").on('change',function(evt){	
	    alert("d");
		evt.preventDefault();
		url   = baseUrl+'seguridad/buscaModulosPerfil';
    	$("#errorJs").removeClass("alert-danger alert-dismissible" );
    	$("#errorJs").html("");                    			
		idPerfil = $("#idperfil").val();
		id       = $("#id").val();
		dataString = 'idPerfil='+idPerfil+"&id="+id;
		$('#tableModulos').html('');
		$("#agregaModulos").hide();
		if(parseInt(idPerfil) > 0 && String(token) !== ''){
			$.ajax({
				type: 'POST',
				url : url,		
				 headers: {
	                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	             },
				data: dataString,
				async: true ,
				beforeSend: function(){
					$('#procesando').html(procesando);
				},			
				success: function(data){
					$("#agregaModulos").show();
					$('#tableModulos').html(data);
					$('#procesando').fadeIn(1000).html("");
				},
				error: function (xhr, ajaxOptions, thrownError) {
					error(vacio);
				},
				complete: function(){
					evt.stopPropagation();
				}
			});

		}
		evt.stopPropagation();
	});
	
	
	
	$("#guardaUsuarioss").on('click',function(){
		if(parseInt($("#idperfil").val()) === 0){
			error(I18n._("errorPerfilUsuario"));
			return false;
		}
		
		if(String($("#name").val()) === ''){
			error(I18n._("errorNombreUsuario"));
			return false;
		}		
		if(String($("#email").val()) !== ''){	   
			 if(!valEmail($("#email").val())){
	        	error(I18n._("errorCorreoUsuario"));
	        	return false;
	        }			
		}else{
			error(I18n._("errorCorreoUsuarioOblig"));
			return false;
		}
	});
	
	
	
	//evento para restablecer al usuario
	$(".restablecer").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'seguridad/activaUsuario';
		var tmp = $(this).attr('id').split('-');
		var id  = tmp[1];
		var div = 'modal-restablece-'+id; 
		if(parseInt(id) > 0 && String(token) !== ''){
			dataString = 'idUsuario='+id;	
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
						exito(I18n._("exitoGuardaUsuario"));
						 setTimeout(function(){
							 location.href = baseUrl+"seguridad/usuario";
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

	//evento para eliminar al usuario
	$(".eliminar").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'seguridad/resetContrasena';
		var id  = $(this).attr('id');
		var div = 'modal-delete-'+id; 
		return false;
	});
	
	
	//evento para resetear la contraseÃ±a	
	$(".resetear").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'seguridad/resetContrasena';
		var id  = $(this).attr('id');
		var div = 'modal-reset-'+id; 
		if(parseInt(id) > 0){
			dataString = 'idUsuario='+id;	
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
						exito(I18n._("exitoReseteoUsuario"));
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
});

//Metodo que no se llama por que sirve para llenar la tabla dede jquery
function registrosAjax(dataString){
	url   = baseUrl+'seguridad/busquedas';
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
			var regs     = info.usuarios.data;
			var empresas = info.catEmpresas;
			var perfiles = info.catPerfiles;
			var permisos = info.sessionPermisos;
			$('table').find('tbody').html('');
			for(index in regs){
				detalles = '<tr>';
				detalles+= '<td>'+regs[index].id+'</td>';
				detalles+= '<td>'+regs[index].name+'</td>';
				detalles+= '<td>'+empresas[regs[index].idempresa]+'</td>';
				detalles+= '<td>'+perfiles[regs[index].idperfil]+'</td>';
				detalles+= '<td>'+regs[index].email+'</td>';
				if(regs[index].activo === true){
					detalles+= '<td>'+I18n._("activo")+'</td>';
				}else{
					detalles+= '<td>'+I18n._("noActivo")+'</td>';
				}
				detalles+= '<td>';
				if(regs[index].activo === true){
					if(jQuery.inArray('Editar',permisos)){
						detalles+= '<a href="'+baseUrl+'seguridad/usuario/'+regs[index].id+'/edit" class="btn btn-default btn-xs">';
						detalles+= '<span data-toggle="tooltip" data-placement="top" title="Editar Registro" class="glyphicon glyphicon-pencil"></span></a>';
					}
					if(jQuery.inArray('Eliminar',permisos)){
						detalles+='<a href="" data-target="#modal-delete-'+regs[index].id+'" data-toggle="modal" class="btn btn-default btn-xs" class="btn btn-default btn-sm">';
						detalles+='<span data-toggle="tooltip" data-placement="top" title="Eliminar Registro" class="glyphicon glyphicon-trash"></span></a>';							
					}
					if(jQuery.inArray('Resetear',permisos)){
						detalles+='<a href="#" data-target="#modal-reset-'+regs[index].id+'"  data-toggle="modal" class="btn btn-default btn-xs" class="btn btn-default btn-sm">';
						detalles+='<span data-toggle="tooltip" data-placement="top" title="Resetear contrase&ntilde;a de Usuario" class="glyphicon glyphicon-refresh"></span></a>';
					}
				}else{
					if(jQuery.inArray('Restablecer',permisos)){
						detalles+='<a href="" data-target="#modal-restablece-'+regs[index].id+'" data-toggle="modal" class="btn btn-default btn-xs">';
						detalles+='<span data-toggle="tooltip" data-placement="top" title="Restablecer registro" class="glyphicon glyphicon-repeat"></span></a>';
					}
				}
				detalles+= '</td>';
				detalles+= '</tr>'; 
				$('table').find('tbody').append(detalles);
				consolo.log(info);
			}
			$('#procesando').fadeIn(1000).html("");
		},
		error: function (xhr, ajaxOptions, thrownError) {				
			$('#procesando').fadeIn(1000).html("");
	    }
	});		

}

