var fechaActual =  moment().format("DD-MM-YYYY"); // new Date().toJSON().slice(0,10);
var max=10;
var numericos = /[0-9]/;
var decimales = /[0-9\.]/;
var camPoliza = /^[a-zA-Z0-9]+$/; 
var tmpIdAseguradora = 0;
var tmpDividendos    = 0;
var tmpUdis          = 0;
var tmpPoliza        = '';
var tmpContrato      = '';
var tmpVigInicio     = '';	    
var tmpVigFin        = '';
var tmpFactores      = '';

$(document).ready(function() {	
    token = $('meta[name="csrf-token"]').attr('content');
    $("input[type=text],input[type=email],select,textarea").focus(function(){
	 $(this).css({ color: "#000000", background: "#E5E5E5" });
    });
    $("input[type=text],input[type=email],select,textarea").focusout(function(){
	$(this).css({ color: "#000000", background: "#ffffff" });
    });
    //	Entrada Caracteres
    $( document ).delegate(".numeros", "keypress", function(e) {  
	  tecla = (document.all) ? e.keyCode : e.which;
	  //Tecla de retroceso para borrar o tabulador , siempre la permite
	  if (tecla == 0 || tecla == 8){
	        return true;
	   }        
	    // Patron aceptado
	    tecla_final = String.fromCharCode(tecla);
	    return numericos.test(tecla_final);		 
    });
    
    $( document ).delegate(".decimales", "keypress", function(e) {  
	  tecla = (document.all) ? e.keyCode : e.which;
	  //Tecla de retroceso para borrar o tabulador , siempre la permite
	  if (tecla == 0 || tecla == 8){
	        return true;
	   }        
	    // Patron aceptado
	    tecla_final = String.fromCharCode(tecla);
	    return decimales.test(tecla_final);		 
    });
    
    $( document ).delegate(".poliza", "keypress", function(e) {  
	  tecla = (document.all) ? e.keyCode : e.which;
	  //Tecla de retroceso para borrar o tabulador , siempre la permite
	  if (tecla == 0 || tecla == 8){
	        return true;
	   }        
	    // Patron aceptado
	    tecla_final = String.fromCharCode(tecla);
	    return camPoliza.test(tecla_final);		 
  });

    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });

    $( ".datetimepicker").datetimepicker({
    	format: 'DD-MM-YYYY',
    	inline: false,
        sideBySide: false,
        locale: 'es'
    });
        
    //Empieza la validacion de validateFormAsignacionPolizaRenovada
    var form = $('validateFormAsignacionPolizaRenovada');
    form.on('submit', function(e){
        e.preventDefault();
    });
    
    $('#validateFormAsignacionPolizaRenovada').bootstrapValidator({
    	message: I18n._("nombreInvalido"),
        feedbackIcons: {
            valid: 	'glyphicon glyphicon-ok',
            invalid: 	'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            idejecutivo: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    }
                }
            }
        }
    });  
    //Termina la validacion de validateFormAsignacionPolizaRenovada


    //Empieza la validacion de validateFormAsignacionPolizaRenovada
    var form = $('validateFormReAsignacionSucursal');
    form.on('submit', function(e){
        e.preventDefault();
    });
    
    $('#validateFormReAsignacionSucursal').bootstrapValidator({
    	message: I18n._("nombreInvalido"),
        feedbackIcons: {
            valid: 	'glyphicon glyphicon-ok',
            invalid: 	'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            idsucursal: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    }
                }
            }
        }
    });  
    //Termina la validacion de validateFormAsignacionPolizaRenovada

    //Empieza la validacion de validateFormCargaRenovacion
    var form = $('validateFormCargaRenovacion');
    form.on('submit', function(e){
        e.preventDefault();
    });
    
    $('#validateFormCargaRenovacion').bootstrapValidator({
    	message: I18n._("nombreInvalido"),
        feedbackIcons: {
            valid: 	'glyphicon glyphicon-ok',
            invalid: 	'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            file: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    }
                }
            }
        }
    });  
    //Termina la validacion de validateFormCargaRenovacion

    //Empieza la validacion de validateFormCargaEmision
    var form = $('validateFormCargaEmision');
    form.on('submit', function(e){
        e.preventDefault();
    });
    
    $('#validateFormCargaEmision').bootstrapValidator({
    	message: I18n._("nombreInvalido"),
        feedbackIcons: {
            valid: 	'glyphicon glyphicon-ok',
            invalid: 	'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            file: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    }
                }
            }
        }
    });  
    //Termina la validacion de validateFormCargaEmision
    
        
    //Empieza la validacion del formulario validateFormConfiguracionGeneral    
    var form = $('validateFormConfiguracionGeneral');
    form.on('submit', function(e){
        e.preventDefault();
    });
    
    $('#validateFormConfiguracionGeneral').bootstrapValidator({
    	message: I18n._("nombreInvalido"),
        feedbackIcons: {
            valid: 	'glyphicon glyphicon-ok',
            invalid: 	'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            meses_previos: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 2,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    },
                    between:{
                        min: 1,
                        max: 100,
                        message: I18n._("minimomaximoDias")
                    }
                }
            }, 
            dias_prev_pago: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 2,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    },
                    between:{
                        min: 1,
                        max: 100,
                        message: I18n._("minimomaximoDias")
                    }
                }
            },
            dias_cancelacion: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 2,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    },
                    between:{
                        min: 1,
                        max: 100,
                        message: I18n._("minimomaximoDias")
                    }
                }
            },
            porc_pago_udi_agencias: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                        regexp: /^\d*(\.\d{1})?\d{0,1}$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 5,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    },
                    between:{
                        min: 0,
                        max: 100,
                        message: I18n._("minimomaximoPorcentaje")
                    }
                }
            },
            porc_pago_udi_ejecutivos: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                	regexp: /^\d*(\.\d{1})?\d{0,1}$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 5,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    },
                    between:{
                        min: 0,
                        max: 100,
                        message: I18n._("minimomaximoPorcentaje")
                    }
                }
            },porc_pago_udi_promotores: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                	regexp: /^\d*(\.\d{1})?\d{0,1}$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 5,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    },
                    between:{
                        min: 1,
                        max: 100,
                        message: I18n._("minimomaximoPorcentaje")
                    }
                }
            },cta_aux: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    stringLength: {
                        min: 6,
                        max: 15,
                        message: I18n._("minimoconfiguracionGenCaracteresCta")
                    },
                }
            },cta_aux2: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    stringLength: {
                        min: 6,
                        max: 15,
                        message: I18n._("minimoconfiguracionGenCaracteresCta")
                    },
                }
            }
            ,no_cliente_aux: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                }
            }
            ,no_cliente_aux2: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                }
            }
        }
    });  //Termina la validacion de validateFormConfiguracionGeneral
    

$('#modalFactor').on('hide.bs.modal', function()
{
        // $('#validateFormConfiguracionVida').bootstrapValidator('resetForm', true);
        // $('#validateFormConfiguracionVida').data('bootstrapValidator').resetForm();
        $('#validateFormConfiguracionVida').data('bootstrapValidator').resetField($("#factor"), true);
        $('#validateFormConfiguracionVida').data('bootstrapValidator').resetField($("#cantidad_inicial"), true);
        $('#validateFormConfiguracionVida').data('bootstrapValidator').resetField($("#cantidad_final"), true);
        // $('#validateFormConfiguracionVida').data('bootstrapValidator').resetField($("#tipo"), true);

});


    //Empieza la validacion del formulario validateFormConfiguracionVida
    if($("#factores").val() !== undefined && String($("#factores").val()) !==''){
	tmpIdAseguradora = $("#idaseguradora").val();
	tmpDividendos    = $("#porc_cobro_dividendos").val();
	tmpUdis          = $("#porc_cobro_udis").val();
	tmpPoliza        = $("#numero_poliza").val();
	tmpContrato      = $("#contrato").val();
	tmpVigInicio     = $("#inicio_vigencia").val();	    
	tmpVigFin        = $("#fin_vigencia").val();
	tmpFactores      = $("#factores").val();
	pintaTablas();
    }
    
    var formVida = $('validateFormConfiguracionVida');
    formVida.on('submit', function(e){
        e.preventDefault();
    });
    
    $("#numero_poliza").change(function(){
	$("#numero_poliza").val($("#numero_poliza").val().toUpperCase());
    })
    $("#contrato").change(function(){
	$("#contrato").val($("#contrato").val().toUpperCase());
    })
    
    
    $('#validateFormConfiguracionVida').bootstrapValidator({
    	message: I18n._("nombreInvalido"),
        feedbackIcons: {
            valid: 	'glyphicon glyphicon-ok',
            invalid: 	'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            idaseguradora: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 2,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    },
                    between:{
                        min: 1,
                        max: 100,
                        message: I18n._("minimomaximoDias")
                    }
                }
            }, 
            numero_poliza: {        	
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                	 regexp: /^[a-zA-Z0-9]+$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 10,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    }
                }
            },
            porc_cobro_dividendos: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                	regexp: /^\d*(\.\d{1})?\d{0,1}$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 5,
                        message: I18n._("minimomaximoPorcentaje")
                    },
                    between:{
                        min: 0,
                        max: 100,
                        message: I18n._("minimomaximoPorcentaje")
                    }
                }
            },
            inicio_vigencia: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    }/*,
                    stringLength: {
                        min: 10,
                        max: 10,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    }*/
                }
            },
            fin_vigencia: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    }/*,
                    stringLength: {
                        min: 10,
                        max: 10,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    }*/
                }
            },
            porc_cobro_udis: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                	regexp: /^\d*(\.\d{1})?\d{0,1}$/,                	
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 5,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    },
                    between:{
                        min: 0,
                        max: 100,
                        message: I18n._("minimomaximoPorcentaje")
                    }
                }
            },contrato: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                	 regexp: /^[A-Z,a-z0-9]+$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 10,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    }
                }
            },factores:{
                message: I18n._("configuracionFactores"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionFactores")
                    }
                }        	
            }
        }
    }); 
    
    
    $(".inicio").on("dp.change", function (e) {
        $('.final').data("DateTimePicker").minDate(e.date);
    });
        
    $(".final").on("dp.change", function (e) {
        $('.inicio').data("DateTimePicker").maxDate(e.date);
    });

    $(".inicio2").on("dp.change", function (e) {
        $('.final2').data("DateTimePicker").minDate(e.date);
    });
        
    $(".final2").on("dp.change", function (e) {
        $('.inicio2').data("DateTimePicker").maxDate(e.date);
    });
    $("#inicio_vigencia").focus(function(e){
    	$("#inicio_vigencia").val(fechaActual);
    });
    
    $("#fin_vigencia").focus(function(e){
    	if(String($("#inicio_vigencia").val()) === ""){
    		$("#fin_vigencia").val(fechaActual);	
    	}else{
    		$("#fin_vigencia").val($("#inicio_vigencia").val());	
    	}    	
    });
        
    
    $("#call_center").click(function(){
	if ($('#call_center').prop('checked') ) {
	    bootbox.confirm(I18n._("aviso1"), function(result) {
		if(!result){
		    $('#call_center').prop('checked',false);
		}
	    });
	}else{
	    bootbox.confirm(I18n._("aviso2"), function(result) {
		if(!result){
		    $('#call_center').prop('checked',true);
		}
	    });	    
	}	
    });
    
    $("#idaseguradora").on('change',function(evt){	
	evt.preventDefault();
	var url   = baseUrl+'configuracion/aseguradora';
	if(parseInt($("#idaseguradora").val()) > 0){	
	    if(parseInt(tmpIdAseguradora) === parseInt($("#idaseguradora").val())){
		recuperaCampos();
	    }else{
		limpiaCampos();
		var dataString = 'id='+$("#idaseguradora").val();	
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
    		       $("#porc_cobro_dividendos").val(data.dividendo);
    			$("#porc_cobro_udis").val(data.udis);
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
	}
    });
    //Termina la validacion de validateFormConfiguracionVida

    //Inicia validaciones del formulario de factores
    
    
    var formFactores = $('validateFormFactores');
    formFactores.on('button', function(e){
        e.preventDefault();
    });
    
    
    $('#modalFactor').bootstrapValidator({
    	message: I18n._("nombreInvalido"),
        feedbackIcons: {
            valid: 	'glyphicon glyphicon-ok',
            invalid: 	'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            tipo: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 2,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    },
                    between:{
                        min: 1,
                        max: 100,
                        message: I18n._("minimomaximoDias")
                    }
                }
            }, 
            factor: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                	regexp: /^\d*(\.\d{1})?\d{0,5}$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 8,
                        message: I18n._("minimomaximoPorcentaje")
                    },
                    between:{
                        min: 0,
                        max: 100,
                        message: I18n._("minimomaximoPorcentaje")
                    }
                }
            },cantidad_inicial: {
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                	regexp: /^\d*(\.\d{1})?\d{0,1}$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 10,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    }
                }
            },cantidad_final:{
                message: I18n._("nombreInvalido"),
                validators: {
                    notEmpty: {
                        message: I18n._("configuracionGenObligatorio")
                    },
                    regexp: {
                	regexp: /^\d*(\.\d{1})?\d{0,1}$/,
                        message: I18n._("configuracionGenCaracteres")
                    },
                    stringLength: {
                        min: 1,
                        max: 10,
                        message: I18n._("minimoconfiguracionGenCaracteres")
                    }
                }
            }
        }
    });  //Termina la validacion de validateFormFactores

    //Empieza la validacion del formulario validateFormQuejaCondusef
    var formQuejasCondusef = $('validateFormQuejaCondusef');
    formQuejasCondusef.on('button', function(e){
        e.preventDefault();
    });
    $('#validateFormQuejaCondusef').bootstrapValidator({
       	message: I18n._("nombreInvalido"),
           feedbackIcons: {
               valid: 	   'glyphicon glyphicon-ok',
               invalid:    'glyphicon glyphicon-remove',
               validating: 'glyphicon glyphicon-refresh'
           },
           fields: {                
               comentario_queja: {
                   message: I18n._("nombreInvalido"),
                   validators: {
                       notEmpty: {
                           message: I18n._("configuracionGenObligatorio")
                       },
//                       regexp: {
//                	   regexp: /^[a-zA-Z0-9_\. \s]+$/,
//                           message: I18n._("configuracionGenCaracteresAlfa")
//                       },
                       stringLength: {
                           min: 5,
                           max: 999,
                           message: I18n._("minimoconfiguracionGenCaracteresAlfa")
                       }
                   }
               },
               fecha_cancelacacion: {
                   message: I18n._("nombreInvalido"),
                   validators: {
                       notEmpty: {
                           message: I18n._("configuracionGenObligatorio")
                       }
                   }
   	    	},
   	    	comentario_cancelacion: {
   	    	    message: I18n._("nombreInvalido"),
   	            validators: {
   	        	notEmpty: {
   	        	    message: I18n._("configuracionGenObligatorio")
   	        	},
//   	                regexp: {
//   	                    regexp: /^[a-zA-Z0-9_\. \s]+$/,
//   	                    message: I18n._("configuracionGenCaracteresAlfa")
//   	                },
   	                stringLength: {
                            min: 5,
                            max: 999,
                            message: I18n._("minimoconfiguracionGenCaracteresAlfa")
   	                },
   	            }
   	    	}               
           }
       });  //Termina la validacion de validateFormQuejaCondusef
       

    //Incia evento de guardar factor
    $("#agregafactor").click(function(){
	var error       = 1;
	var numeroFilas = 0;
	var factores 	= "";
	var tupla    	= ""
	$("#e-tipo").hide();
	$("#e-tipo").css({ color: "#ffffff", background: "#ffffff" });
	$("#e-factor").hide();
	$("#e-factor").css({ color: "#ffffff", background: "#ffffff" });
	$("#e-cantidad_inicial").hide();
	$("#e-cantidad_inicial").css({ color: "#ffffff", background: "#ffffff" });
	$("#e-cantidad_final").hide();
	$("#e-cantidad_final").css({ color: "#ffffff", background: "#ffffff" });
	if(String($("#tipo").val()) === ''){
	    $("#e-tipo").css({ color: "#ff0000", background: "#ffffff" });
	    $("#e-tipo").html(I18n._("configuracionGenObligatorio"));
	    $("#e-tipo").show();
	    error = 0;
	}
	if(String($("#factor").val()) === ''){	
	    $("#e-factor").css({ color: "#ff0000", background: "#ffffff" });
	    $("#e-factor").html(I18n._("configuracionGenObligatorio"));
	    $("#e-factor").show();	    
	    error = 0;
	}
	if(String($("#cantidad_inicial").val()) === ''){
	    $("#e-cantidad_inicial").css({ color: "#ff0000", background: "#ffffff" });
	    $("#e-cantidad_inicial").html(I18n._("configuracionGenObligatorio"));
	    $("#e-cantidad_inicial").show();
	    error = 0;
	}
	if(String($("#cantidad_final").val()) === ''){
	    $("#e-cantidad_final").css({ color: "#ff0000", background: "#ffffff" });
	    $("#e-cantidad_final").html(I18n._("configuracionGenObligatorio"));
	    $("#e-cantidad_final").show();
	    error = 0;
	}
	if(parseInt(error) === 0){
	    $("#guardaVida").prop('disabled',false);
	    return false;
	}else{
	    $(".help-block").html('');
	    $("#guardaVida").prop('disabled',false);
	    insertaRegistro();
	    cerrarModal();	    	    
	}
    });
    
    $("#cerrarVentanaFactor").click(function(){
	cerrarModal();
    });
    
    $(".cancelaRegistroEsp").click(function(){
	  url = $(this).attr('id');
	  if(String(url) !== ''){
		  bootbox.confirm("<br><br><b>&iquest;Desea salir del formulario sin guardar?</b>", function(result) {
	          if(result){
	        	  location.href = url;
	          }
		  });
	  }
    });
    
    
    $(".table-hover").on('click', '.eliminaFactor', function () {
	var data          = "";
	var valor         = "";
	var ids           = $(this).attr('id').split('-');
	var idEliminar    = ids[0];
	var tablaId       = ids[1];	
	var factores      = $("#factores").val();	    
	var listaFactores = factores.split('=');	
	bootbox.confirm("<br><br><b>&iquest;Desea eliminar el registro?</b>", function(result) {
	    if(result){
        	$.each(listaFactores,function(indice,valor){
        	    if(String(valor) !== ''){
        		data = valor.split('*');	
        		if(parseInt(idEliminar) === parseInt(data[0])  && parseInt(tablaId) === parseInt(data[1])){
        		    factores = factores.replace("="+valor,'');
        		    $("#row"+idEliminar).remove();
        		}
        	    }
        	 });	
        	$("#factores").val(factores);
	    }
	});
    });   
    //Termina evento de guardar factor
     
}); //fin del document

function insertaRegistro(){
    var tupla          = $("#tipo").val()+"*"+$("#factor").val()+"*"+$("#cantidad_inicial").val()+"*"+$("#cantidad_final").val();
    var factores       = $("#factores").val();	    
    var listaFactores  = factores.split('=');
    var data           = "";
    var sig 	       = 0;
    $.each(listaFactores,function(indice,valor){
	if(String(valor) !== ''){sig = indice;}
    });
    sig++;
    factores +="="+sig+"*"+tupla;
    $("#table"+$("#tipo").val()).append('<tr id="row'+sig+'"><td class="tdfLeft">'+$("#cantidad_inicial").val()+' - '+$("#cantidad_final").val()+'</td><td class="tdfLeft">'+$("#factor").val()+'</td><td class="tdCenter"><button type="button" class="btn btn-sm eliminaFactor" id="'+sig+'-'+$("#tipo").val()+'"><span data-toggle="tooltip" data-placement="top" title="Para eliminar el factor" class="glyphicon glyphicon-trash"></span></button></td></tr>');    
    $("#factores").val(factores);
}

function cerrarModal(){
    $(".modales").removeClass('has-success');
    $("#e-tipo").hide();
    $("#tipo").val(1);
    $("#e-tipo").css({ color: "#ffffff", background: "#ffffff" });
    $("#e-factor").hide();
    $("#factor").val('');
    $("#e-factor").css({ color: "#ffffff", background: "#ffffff" });
		
    $("#e-cantidad_inicial").hide();
    $("#cantidad_inicial").val('');
    $("#e-cantidad_inicial").css({ color: "#ffffff", background: "#ffffff" });
	
    $("#e-cantidad_final").hide();
    $("#cantidad_final").val('');
    $("#e-cantidad_final").css({ color: "#ffffff", background: "#ffffff" });
    $("#modalFactor").modal('hide');    
}
function pintaTablas(){
    if( $("#factores").val() !== undefined){
        $(".fila").closest('tr').remove();
        var factores      = $("#factores").val();	  
        var listaFactores = factores.split('=');	
        $.each(listaFactores,function(indice,valor){
    	if(String(valor) !== ''){
    	    data = valor.split('*');		
    	    $("#table"+data[1]).append('<tr class="fila" id="row'+data[0]+'"><td class="tdfLeft">'+data[3]+' - '+data[4]+'</td><td class="tdfLeft">'+data[2]+'</td><td class="tdCenter"><button type="button" class="btn btn-sm eliminaFactor" id="'+data[0]+'-'+data[1]+'"><span data-toggle="tooltip" data-placement="top" title="Para eliminar el factor" class="glyphicon glyphicon-trash"></span></button></td></tr>');
    	}
        });
    }
}
function recuperaCampos(){
	$("#idaseguradora").val(tmpIdAseguradora);
	$("#porc_cobro_dividendos").val(tmpDividendos);
	$("#porc_cobro_udis").val(tmpUdis);
	$("#numero_poliza").val(tmpPoliza);
	$("#contrato").val(tmpContrato);
	$("#inicio_vigencia").val(tmpVigInicio);	    
	$("#fin_vigencia").val(tmpVigFin);
	$("#factores").val(tmpFactores);
	pintaTablas();		
}
function limpiaCampos(){
	$("#porc_cobro_dividendos").val('');
	$("#porc_cobro_udis").val('');
	$("#numero_poliza").val('');
	$("#contrato").val('');	    
	$("#inicio_vigencia").val('');
	$("#fin_vigencia").val('');
	$("#factores").val('');
	pintaTablas();
}