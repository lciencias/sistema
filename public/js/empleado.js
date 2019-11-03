
$(document).ready(function() {	
	token = $('meta[name="csrf-token"]').attr('content');

	
	var formCliente = $('validateFormEmpleado');
	formCliente.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormEmpleado').bootstrapValidator({
        message: 'El valor es inválido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	sucursal: {
                message: 'Sucursal inválida',
                validators: {
                    notEmpty: {
                        message: 'Se debe especificar la sucursal para el empleado'
                    }
                    
                }
            },
        	nombre: {
                message: 'El nombre del cliente es inválido',
                validators: {
                    notEmpty: {
                        message: 'El nombre del cliente debe ser proporcionado'
                    },
//                    regexp: {
//                        regexp: /^[a-zA-Z0-9_\.\s]+$/,
//                        message: 'El nombre de la empresa solo puede consistir de carácteres alfabeticos'
//                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'El nombre del cliente debe tener mínimo 3 carácteres y máximo 50'
                    }
                }
            },
            paterno:{
                message: 'El apellido paterno es inválido',
                validators: {
                    notEmpty: {
                        message: 'El apellido paterno  debe ser proporcionado'
                    },
//                    regexp: {
//                        regexp: /^[a-zA-Z0-9_\.\s]+$/,
//                        message: 'La razón social solo puede consistir de carácteres alfabeticos'
//                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'El apellido paterno debe tener mínimo 3 carácteres y máximo 50'
                    }
                }            
            },
//            email:{
//            	  validators: {
////            		  notEmpty: {
////                          message: 'The password is required and cannot be empty'
////                      },
//                	   callback: {
//                		   message: 'El email es incorrecto',
//                		   callback: function (value, validator, $field) {
//                			   if( $("#esgerente").is(':checked') ) {
////                				   alert("entra: " + value);
//                				   if (value == "") {
//                					   return false;
//                				   } 
//                				   
//                				   emailRegex = /^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/;
//                				   if (emailRegex.test(value)) {
//                					      return true;
//                					} else {
//                						false;
//                					}
//                				   
//                			   } else {
//                				   return  true;
//                			   }
//                               
//                           }
//                		   
//                		   
//                           
//                       }
//            	  }
//               
//            }
            email:{
                validators: {
                    notEmpty: {
                        message: 'El correo electrónico del empleado debe ser proporcionado'
                    },
                    regexp: {
                	  regexp: /^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/,
                          message: I18n._("correoInvalido"),
                    },
                    stringLength: {
                        min: 5,
                        max: 50,
                        message: 'El correo electrónico del empleado debe tener mínimo 5 carácteres y máximo 50'
                    }, 
                    remote: {
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
            }
        }
    });	
	
	//evento para restablecer al empleado
	$(".restablecerEmpl").on('click',function(event){
//		alert("entra");
		event.preventDefault();
		url   = baseUrl+'catalogo/empleado/activaEmpleado';
		var tmp = $(this).attr('id').split('-');
		var id  = tmp[1];
		var div = 'modal-restableceEmp-'+id; 
//		alert("entra id: " + id);
		if(parseInt(id) > 0 && String(token) !== ''){
			dataString = 'idEmpleado='+id;	
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
						exito("El empelado ha sido activado");
						 setTimeout(function(){
							 location.href = baseUrl+"catalogo/empleado";
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

	
});

function getSucursalesEmpresa(){
    var msg='';
   var idEmpresa = document.getElementById("empresa").value;
   var sucursales = $("#sucursal");
   var url = baseUrl + "catalogos/empleado/getSucursalesEmpresa/" + idEmpresa;
   if(parseInt(idEmpresa) > 0 && idEmpresa !== ''){
       $.ajax({
	       type: 'post',
	       url: url,
	       headers: {
				 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
	       dataType: 'json',
	       success: function (data) {
//	    	   sucursales.find('option').remove();
//	    	   sucursales.append('<option value="" selected>Seleccione</option>');
//	    	   $(data).each(function(i, v){ // indice, valor
//	    		   sucursales.append('<option value="' + v.idsucursal + '">' + v.nombre + '</option>');
//	           })
	           
		if(parseInt(data.exito) === 1){
		    $("#sucursal").empty();
			var sucursales = data.sucursales;												
			if(sucursales.length > 0){							
			    msg += "<option value=''>Seleccione</option>";	
			    $.each(sucursales, function(i, item) {
				msg += "<option value='"+sucursales[i].idsucursal+"'>"+sucursales[i].nombre+"</option>";					
			});
			}else{
				msg += "<option value=''>No existen sucursales</option>";
			}
			$("#sucursal").html(msg);
			$("#sucursal").html(msg);
			$('#procesando').fadeIn(1000).html("");
		}
	   	
	   
	           
//	    	   alert("exito: " + data);
	       },
	       error: function (data) {
//	    	   alert("error");
	           var errors = data.responseJSON;
	           if (errors) {
	               $.each(errors, function (i) {
	                   console.log(errors[i]);
	               });
	           }
	       }
	   });
   } else {
       $("#sucursal").empty();
       msg += "<option value=''>Seleccione</option>";	
       $("#sucursal").html(msg);
       $("#sucursal").html(msg);
       $('#procesando').fadeIn(1000).html("");
       
   }
  
   
   
   
   
}


function muestraMail() {
	   
	if( $("#esgerente").is(':checked') ) {
		$("#divMail").prop('hidden', false);
	} else {
		$("#divMail").prop('hidden', true);
	}
}

