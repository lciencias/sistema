var url;
var idPerfil;
var id;
var dataString;
var baseUrl    = '/sistema/public/';
var procesando = '<div><img src="'+baseUrl+'img/loading.gif"/><br><span style="font-size:16px;text-align:center;color:#585858;">P r o c e s a n d o . . . .</span></div>'
var token;
var vacio = '';

$(document).ready(function() {	
	token = $('meta[name="csrf-token"]').attr('content');

	
	var formCliente = $('validateFormCliente');
	formCliente.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormCliente').bootstrapValidator({
        message: 'El valor es inválido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
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
            email:{
                message: 'El correo electrónico del cliente es inválido',
                validators: {
                    notEmpty: {
                        message: 'El correo electrónico del cliuente debe ser proporcionado'
                    },
                    regexp: {
                        regexp: /^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/,
                        message: 'El correo electrónico del cliente es incorrecto.'
                    },
                    stringLength: {
                        min: 6,
                        max: 50,
                        message: 'El correo electrónico del cliente debe tener mínimo 5 carácteres y máximo 50'
                    }
                }
            }
        }
    });	
	
	//evento para restablecer al cliente
	$(".restablecerCli").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'catalogo/cliente/activaCliente';
		var tmp = $(this).attr('id').split('-');
		var id  = tmp[1];
		var div = 'modal-restableceCli-'+id; 
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
						exito("El Cliente ha sido activado");
						 setTimeout(function(){
							 location.href = baseUrl+"catalogo/cliente";
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
	
	
	$(".recordarCli").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'catalogos/cliente/recuerdaCliente';
		var tmp = $(this).attr('id').split('-');
		var idEncuestaGenerada  = tmp[1];
		var div = 'modal-recuerdaCli-' + idEncuestaGenerada; 
		if(parseInt(idEncuestaGenerada) > 0 && String(token) !== ''){
//			dataString = 'idCliente='+idCliente;	
			$.ajax({
				type: 'POST',
				url : url,		
				 headers: {
	                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	             },
	            dataType: 'json',
	            data : {
					'idEncuestaGenerada' : idEncuestaGenerada,
				},
				beforeSend: function(){
					$('#procesando').html(procesando);
				},			
				success: function(data){
					$('#'+div).modal('hide');
					$('#procesando').fadeIn(1000).html("");
					if(parseInt(data.exito) === 1){						
						exito("El Cliente ha sido recordado");
						 setTimeout(function(){
							 location.href = baseUrl+"cliente/monitor/" + idCliente ;
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


