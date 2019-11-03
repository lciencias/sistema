
$(document).ready(function() {	
	token = $('meta[name="csrf-token"]').attr('content');

	
	var formPromocion = $('validateFormPromocion');
	formPromocion.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormPromocion').bootstrapValidator({
        message: 'El valor es inválido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	nombre: {
                message: 'El nombre de la promoción es inválido',
                validators: {
                    notEmpty: {
                        message: 'El nombrede la promoción debe ser proporcionado'
                    },
//                    regexp: {
//                        regexp: /^[a-zA-Z0-9_\.\s]+$/,
//                        message: 'El nombre de la empresa solo puede consistir de carácteres alfabeticos'
//                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'El nombre de la promoción debe tener mínimo 3 carácteres y máximo 50'
                    }
                }
            },
        }
    });	
	
  
	//evento para restablecer a la sucursal
	$(".restablecerPro").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'catalogo/promocion/activaPromocion';
		var tmp = $(this).attr('id').split('-');
		var id  = tmp[1];
		var div = 'modal-restablecePro-'+id; 
		if(parseInt(id) > 0 && String(token) !== ''){
			dataString = 'idPromocion='+id;	
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
						exito("La Promoción ha sido activada");
						 setTimeout(function(){
							 location.href = baseUrl+"catalogo/promocion";
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



