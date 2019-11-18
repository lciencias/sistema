var max=10;
var numericos = /[0-9]/;
var letras   = /^[a-zA-Z\.\s\u00C1 \u00E1 \u00C9 \u00E9 \u00CD \u00ED \u00D3 \u00F3 \u00DA \u00FA \u00DC \u00FC \u00D1 \u00F1]+$/;
var alfanum   = /^[a-zA-Z0-9_\u00C1 \u00E1 \u00C9 \u00E9 \u00CD \u00ED \u00D3 \u00F3 \u00DA \u00FA \u00DC \u00FC \u00D1 \u00F1 \.;.s!#$¡¿?,\s]+$/;
var letraNumero   = /^[a-zA-Z0-9_\u00C1 \u00E1 \u00C9 \u00E9 \u00CD \u00ED \u00D3 \u00F3 \u00DA \u00FA \u00DC \u00FC \u00D1 \u00F1 ]+$/;
var correo   = /^[a-zA-Z0-9_@\-\.\s]+$/;
var decimal = /[0-9\.]/;

$(document).ready(function() {
	var formUser = $('#validateFormGrupoUsuarios');
	formUser.on('submit', function(e){
		e.preventDefault();
	});

  setInterval(revisaSesion,300000);
  $(".tablesorter").tablesorter();
  var dataString = 'idModulo='+$("#idModulo").val()+'&page=1&noRegs=0&orden=asc';
  
  if(parseInt($("#idModulo").val()) > 0 && String(token) !== ''){
	registrosIndexView(dataString,$("#idModulo").val());
	return false	
  }
  $("input[type=text],input[type=email],select,textarea").focus(function(){
	  $(this).css({ color: "#000000", background: "#E5E5E5" });
  });
 
  $("input[type=text],input[type=email],select,textarea").focusout(function(){
	  $(this).css({ color: "#000000", background: "#ffffff" });
  });

   //Entrada Caracteres
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
  
  //Entrada Letras
  $( document ).delegate(".letras", "keypress", function(e) {	 
	  tecla = (document.all) ? e.keyCode : e.which;
	  //Tecla de retroceso para borrar o tabulador , siempre la permite
	  if (tecla == 0 || tecla == 8){
	        return true;
	    }	        
	    // Patron aceptado
	    tecla_final = String.fromCharCode(tecla);
	    return letras.test(tecla_final);
  });

  //Entrada alfanumerico
  $( document ).delegate(".alfa", "keypress", function(e) {	  
	  tecla = (document.all) ? e.keyCode : e.which;
	  //Tecla de retroceso para borrar o tabulador , siempre la permite
	  if (tecla == 0 || tecla == 8){
	        return true;
	    }	        
	    // Patron aceptado
	    tecla_final = String.fromCharCode(tecla);
	    return alfanum.test(tecla_final);		  
  });
  
  
  //Entrada letras y numeros
  $( document ).delegate(".letraNumero", "keypress", function(e) {	  
	  tecla = (document.all) ? e.keyCode : e.which;
	  //Tecla de retroceso para borrar o tabulador , siempre la permite
	  if (tecla == 0 || tecla == 8){
	        return true;
	    }	        
	    // Patron aceptado
	    tecla_final = String.fromCharCode(tecla);
	    return letraNumero.test(tecla_final);		  
  });
  
  //Entrada correo
  $( document ).delegate(".correo", "keypress", function(e) {	
	  tecla = (document.all) ? e.keyCode : e.which;
	  //Tecla de retroceso para borrar o tabulador , siempre la permite
	  if (tecla == 0 || tecla == 8){
	        return true;
	    }	        
	    // Patron aceptado
	    tecla_final = String.fromCharCode(tecla);
	    return correo.test(tecla_final);
  });
  
  //Entrada decimal
  $( document ).delegate(".decimal", "keypress", function(e) {	  
	  tecla = (document.all) ? e.keyCode : e.which;
	  //Tecla de retroceso para borrar o tabulador , siempre la permite
	  if (tecla == 0 || tecla == 8){
	        return true;
	    }	        
	    // Patron aceptado
	    tecla_final = String.fromCharCode(tecla);
	    return decimal.test(tecla_final);		  
  });
  
  
  $( ".submit" ).click(function() {
	  var i = 0;
	  $('#procesando').html(procesando);     
	  setTimeout(function(){		 
		  i++;
	  },500);	
  });

  $(".cancelaRegistro").click(function(){
	  url = $(this).attr('id');
	  if(String(url) !== ''){
		  bootbox.confirm("<br><br><b>&iquest;Desea salir del formulario sin guardar?</b>", function(result) {
	          if(result){
	        	  location.href = url;
	          }
		  });
	  }
  });
  
  $(".regresar").click(function(){
	  url = $(this).attr('id');
	  if(String(url) !== ''){
	        	  location.href = url;
	  }
});
  
  $( ".datetimepicker").datetimepicker({
      format: 'DD-MM-YYYY',
      inline: false,
      sideBySide: true,
      locale: 'es',
      useCurrent: false 
  });

  $( ".datetimepickerMax").datetimepicker({
      format: 'DD-MM-YYYY',
      inline: false,
      sideBySide: true,
      maxDate: new Date(),
      locale: 'es',
      useCurrent: false
  });

 
  
  $("#fechaInicio").on("dp.change", function (e) {
      $('#fechaFinal').data("DateTimePicker").minDate(e.date);
  }); 
 



   $('#filter').keyup(function () {
            var rex = new RegExp($(this).val(), 'i');
            $('.searchable tr').hide();
            $('.searchable tr').filter(function () {
                return rex.test($(this).text());
            }).show();

        });
    $('#filter2').keyup(function () {
            var rex = new RegExp($(this).val(), 'i');
            $('.searchable2 tr').hide();
            $('.searchable2 tr').filter(function () {
                return rex.test($(this).text());
            }).show();
        });


    $(".seleccionaIdInterpretacion").click(function(){
    	var id     = $(this).attr('id');
    	var cadena ="";
    	var datos;
    	if(parseInt(id) > 0){
    		cadena = $("#inter"+id).val(); 
    		datos = cadena.split('|');
    		$("#nombreCandidato").html(datos[0]+' '+datos[1]+' '+datos[2]);
    		$("#pruebaCandidato").html(datos[3]);
    		$("#perfilCandidato").html(datos[4]);
    		$("#resultadoCandidato").html(datos[5]);
    		$("#interpretacionCandidato").html(datos[6]);
    		$("#modalInterpretacion").modal('show');
    		return false;
    	}
    })
});  //Fin de jquery


function revisaSesion(){
	var baseUrl    = '/sistema/public/';
	url   = baseUrl+'seguridad/session';
	$.ajax({
		type: 'POST',
		url : url,		
		 headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
		data: dataString,
		beforeSend: function(){},			
		success: function(data){
			if(parseInt(data) !== 0){
				location.href = baseUrl+"logout";
			}			
		},
		error: function (xhr, ajaxOptions, thrownError) {
			return false;
	    },
		complete: function(){
		}				
	});	
	return false;
}
function check_chars(cadena, chars)
{
    var s = "";
    var j = 0;
    for (i = 0; i < cadena.length; i++)
    {
        if (chars.indexOf(cadena.charAt(i)) != -1)
        {
          s = s + cadena.charAt(i);
        }
        else j++;
    }
    cadena = s; 
    return cadena;
}

function valEmail(txt)
{
    var b=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/
    return b.test(txt)
}
function validarRFC(rfc) {
	var regex =  new RegExp("[A-Z]{4}[0-9]{6}[A-Z0-9]{3}");
	 if(String(rfc).length == 12){
		 regex =  new RegExp("[A-Z]{3}[0-9]{6}[A-Z0-9]{3}");	 
	 }
	 return regex.test(rfc);
}

function exito(msg){
	$('#procesando').fadeIn(1000).html("");
	$("#errorJs").addClass("alert alert-success alert-dismissible" );
	//$("#errorJs").css({"position": "absolute", "top": "0px", "right": "0px", "index-z":"9999999999999"});
	$("#errorJs").css({"position": "absolute", "top": "50%", "left": "50%", "width":"200px", "margin-left":"-100px" , "height":"60px" , "margin-top":"30px", "opacity":"0.9"});
	$("#errorJs").html(msg);
    setTimeout(function(){
    	$("#errorJs").removeClass("alert-success alert-dismissible" );
    	$("#errorJs").html("");                    	
    },2500);	
	
}


function error(msg){
	$('#procesando').fadeIn(1000).html("");
	$("#errorJs").addClass("alert alert-danger alert-dismissible" );
	//$("#errorJs").css({"position": "absolute", "top": "0px", "right": "200px", "index-z":"9999999999999"});
	$("#errorJs").css({"position": "absolute", "top": "50%", "left": "50%", "width":"200px", "margin-left":"-100px" , "height":"60px" , "margin-top":"30px", "opacity":"0.9"});
	if(String(msg) === '')
		$("#errorJs").html("Error inesperado favor de notificar al administrador.");
	else
		$("#errorJs").html(msg);
    setTimeout(function(){
    	$("#errorJs").removeClass("alert-danger alert-dismissible" );
    	$("#errorJs").html("");                    	
    },2500);	
}

function errorModal(msg){
    $("#leyendaError").html(msg);
    $("#errorModal").modal('show');
}

function warning(msg){
	$('#procesando').fadeIn(1000).html("");
	$("#errorJs").addClass("alert alert-warning alert-dismissible" );
	//$("#errorJs").css({"position": "absolute", "top": "0px", "right": "0px", "index-z":"9999999999999"});
	$("#errorJs").css({"position": "absolute", "top": "10%", "left": "50%", "width":"200px", "margin-left":"-100px" , "height":"60px" , "margin-top":"30px", "opacity":"0.9"});
	if(String(msg) === '')
		$("#errorJs").html("Error inesperado favor de notificar al administrador.");
	else
		$("#errorJs").html(msg);
    setTimeout(function(){
    	$("#errorJs").removeClass("alert-warning alert-dismissible" );
    	$("#errorJs").html("");                    	
    },2500);	
}


function revisaFecha(fecha){
	var validaciones = 0;
	var ano = fecha.substring(0,2);
	var mes = fecha.substring(2,4);
	var dia = fecha.substring(4,6);
	if( (parseInt(ano) >= 0 && parseInt(ano) <= 99) ){
		validaciones++;
	}
	if( (parseInt(mes) >= 1 && parseInt(mes) <= 12) ){
		validaciones++;
		switch(parseInt(mes)){
			case 1:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 31) ){
					validaciones ++;					
				}
				break;
			case 2:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 29) ){
					validaciones ++;					
				}
				break;
			case 3:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 31) ){
					validaciones ++;					
				}
				break;
			case 4:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 30) ){
					validaciones ++;					
				}
				break;
			case 5:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 31) ){
					validaciones ++;					
				}
				break;
			case 6:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 30) ){
					validaciones ++;					
				}
				break;
			case 7:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 31) ){
					validaciones ++;					
				}
				break;
			case 8:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 31) ){
					validaciones ++;					
				}
				break;
			case 9:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 30) ){
					validaciones ++;					
				}
				break;
			case 10:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 31) ){
					validaciones ++;					
				}
				break;
			case 11:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 30) ){
					validaciones ++;					
				}
				break;
			case 12:
				if( (parseInt(dia) >= 1 && parseInt(dia) <= 31) ){
					validaciones ++;					
				}
				break;
		}		
	}
	if(validaciones === 3){
		return true;
	}else{
		return false;
	}	
}


function registrosIndexView(dataString,moduloId){
	url = "";
	if(parseInt(moduloId) > 0){
		url   = baseUrl+'busqueda';
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
				$("#result").html(info);
				$('#procesando').fadeIn(1000).html("");
				return false;
			},
			error: function (xhr, ajaxOptions, thrownError) {				
				$('#procesando').fadeIn(1000).html("");
		    }
		});				
	}
}



function validaEntero(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}


function validaDecimal(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9-.]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function temporizador(){
	alert("temporiza");
	
}

