$(() => {
	/*$("#validateFormMovimiento").on("submit", () => {
		var wrong = 0;
		$(".input").each(function(){
			let id = $(this).attr('id');
			let val = $(this).val();
			if(val == ''){
				error("Todos los campos son necesarios.");
				$("#"+id).focus();
				wrong ++;
				return false;
			}
		});
		if(wrong > 0){
			return false;
		}
	});*/

	$('#validateFormMovimiento').bootstrapValidator({
        message: I18n._("valorInvalido"),
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
     	fields: {
            cta_origen_cat: {
                validators: {
                    notEmpty:{message: 'Cuenta origen categoría es requerido'}
                }
            },
            monto: {
                validators: {
                    notEmpty:{message: 'El campo monto es requerido'}
                }
            },
            descripcion1: {
                validators: {
                    notEmpty:{message: 'El campo descripción 1 es requerido'}
                }
            },
            no_cliente_origen: {
                validators: {
                    notEmpty:{message: 'No. cliente origen es requerido'}
                }
            },
            codigo_tx_origen: {
                validators: {
                    notEmpty:{message: 'El código transacción origen es requerido'}
                }
            },
            producto_origen: {
                validators: {
                    notEmpty:{message: 'El producto origen es requerido'}
                }
            },
            cta_destino_categoria: {
                validators: {
                    notEmpty:{message: 'Cuenta destino categoría es requerido'}
                }
            },
            descripcion2: {
                validators: {
                    notEmpty:{message: 'El campo descripcion 2 es requerido'}
                }
            },
            num_cliente_destino: {
                validators: {
                    notEmpty:{message: 'Número de cliente destino es requerido'}
                }
            },
            producto_destino: {
                validators: {
                    notEmpty:{message: 'El campo producto destino es requerido'}
                }
            },
            codigo_tx_destino: {
                validators: {
                    notEmpty:{message: 'Código transacción destino es requerido'}
                }
            }
        }
    });
});