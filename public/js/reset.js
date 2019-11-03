var url;
var idPerfil;
var id;
var dataString;
var token;
var vacio = '';

$(document).ready(function() {	

	token = $('meta[name="csrf-token"]').attr('content');

	/** recupera password desde el correo**/
	var formReset = $('validateFormResetRecupera');
	formReset.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormResetRecupera').bootstrapValidator({
	        message: 'El valor es inválido',
	        feedbackIcons: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
	        },
	        fields: {
	        	password: {
	                message: 'El password es inválido',
	                validators: {
	                    notEmpty: {
	                        message: 'El password debe ser proporcionado'
	                    },
	                    regexp: {
	                    	 regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&_])[A-Za-z\d$@$!%*?&_]{8,15}/,
	                         message: 'El password debe contener mínimo 8 caracteres, máximo 15, al menos una mayúscula, al menos una minuscula, al menos un dígito, sin espacios en blanco y un caracter especial'
	                    },
	                    stringLength: {
	                        min: 6,
	                        max: 50,
	                        message: 'El password debe tener mínimo 6 carácteres y máximo 15'
	                    }
	                }
	            },
	            password_confirmation: {
	                message: 'La confirmaci&oacute;n del password es inválido',
	                validators: {
	                    notEmpty: {
	                        message: 'La confirmaci&oacute;n del password debe ser proporcionado'
	                    },
	                    regexp: {
	                    	 regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&_])[A-Za-z\d$@$!%*?&_]{8,15}/,
	                         message: 'El password debe contener mínimo 8 caracteres, máximo 15, al menos una mayúscula, al menos una minuscula, al menos un dígito, sin espacios en blanco y un caracter especial'
	                    },
	                    stringLength: {
	                        min: 6,
	                        max: 50,
	                        message: 'La confirmaci&oacute;n del password debe tener mínimo 6 carácteres y máximo 15'
	                    }
	                }
	            }
	        }
	    });	

	
	/** recupera password desde el aplicaticon**/
	var formReset = $('validateFormReset');
	formReset.on('submit', function(e){
		e.preventDefault();
	});

	$('#validateFormReset').bootstrapValidator({
        message: 'El valor es inválido',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	password: {
                message: 'El password es inválido',
                validators: {
                    notEmpty: {
                        message: 'El password debe ser proporcionado'
                    },
                    regexp: {
                    	 regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&_])[A-Za-z\d$@$!%*?&_]{8,15}/,
                         message: 'El password debe contener mínimo 8 caracteres, máximo 15, al menos una mayúscula, al menos una minuscula, al menos un dígito, sin espacios en blanco y un caracter especial'
                    },
                    stringLength: {
                        min: 6,
                        max: 50,
                        message: 'El password debe tener mínimo 6 carácteres y máximo 15'
                    }
                }
            },
            passwordC: {
                message: 'La confirmaci&oacute;n del password es inválido',
                validators: {
                    notEmpty: {
                        message: 'La confirmaci&oacute;n del password debe ser proporcionado'
                    },
                    regexp: {
                    	 regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&_])[A-Za-z\d$@$!%*?&_]{8,15}/,
                         message: 'El password debe contener mínimo 8 caracteres, máximo 15, al menos una mayúscula, al menos una minuscula, al menos un dígito, sin espacios en blanco y un caracter especial'
                    },
                    stringLength: {
                        min: 6,
                        max: 50,
                        message: 'La confirmaci&oacute;n del password debe tener mínimo 6 carácteres y máximo 15'
                    }
                }
            }
        }
    });	
	
	
	//evento boton guardar
	$("#guardaReset").on("click",function(){
		var campo   = "";
		var errores = 0;
		$(".required").each(function(){
			if(String($(this).val().trim()) === ''){
				errores++;
				campo = $(this).attr('data')
				if(parseInt(errores) === 1){
					error("El campo "+campo+" es obligatorio");
				}
			}
		});
		if(parseInt(errores) > 0){
			return false;
		}	
		
		if(String($("#password").val().trim()) !== String($("#passwordC").val().trim())){
			error("Lsa claves son diferentes por favor de rectificar");
			return false;
		}		
	});
});


