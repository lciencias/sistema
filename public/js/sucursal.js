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

	
	var formSusurcal = $('validateFormSucursal');
	formSusurcal.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormSusursal').bootstrapValidator({
        message: 'El valor es inválido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            
            empresa: {
                message: 'El nombre de la empresa es inválido',
                validators: {
                    notEmpty: {
                        message: 'El nombre de la empresa debe ser proporcionado'
                    },
                }
            },
            
        	nombre: {
                message: 'El nombre de la sucursal es inválido',
                validators: {
                    notEmpty: {
                        message: 'El nombre de la sucursal debe ser proporcionado'
                    },
//                    regexp: {
//                        regexp: /^[a-zA-Z0-9_\.\s]+$/,
//                        message: 'El nombre de la empresa solo puede consistir de carácteres alfabeticos'
//                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'El nombre de la sucursal debe tener mínimo 3 carácteres y máximo 50'
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
            identificador: {
                message: 'El identificador de la sucursal es inválido',
                validators: {
                    notEmpty: {
                        message: 'El identificador de la sucursal debe ser proporcionado'
                    },
//                    regexp: {
//                        regexp: /^[a-zA-Z0-9_\.\s]+$/,
//                        message: 'El nombre de la empresa solo puede consistir de carácteres alfabeticos'
//                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'El identificador de la sucursal debe tener mínimo 3 carácteres y máximo 50'
                    }
                }
            },
        }
    });	
	
	//evento para restablecer a la sucursal
	$(".restablecerSuc").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'catalogo/sucursal/activaSucursal';
		var tmp = $(this).attr('id').split('-');
		var id  = tmp[1];
		var div = 'modal-restableceSuc-'+id; 
		if(parseInt(id) > 0 && String(token) !== ''){
			dataString = 'idSucursal='+id;	
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
						exito("La Sucursal ha sido activada");
						 setTimeout(function(){
							 location.href = baseUrl+"catalogo/sucursal";
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
