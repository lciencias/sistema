var url;
var idPerfil;
var id;
var dataString;
var token;
var vacio = '';
var noPermisos =0;
$(document).ready(function() {
    token = $('meta[name="csrf-token"]').attr('content');
    noPermisos = $("#noPermisos").val();
    $(".alert").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert").slideUp(500);
    });

	var form = $('validateFormPerfil');
	form.on('submit', function(e){
		e.preventDefault();
	});

	
	  //validador perfil
    $('#validateFormPerfil').bootstrapValidator({
    	message: I18n._("nombreInvalido"),
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	nombre: {
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
                        message: I18n._("minimoPerfilCaracteres")
                    },
                    remote: {
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
            empresa: {
                message: I18n._("empresaInvalida"),  
                validators: {
                    notEmpty: {
                        message: I18n._("perfilEmpresaObligatorio")
                    }
                    
                }
            },
            modulos: {
                message: I18n._("moduloObligatorio"),
                validators: {
                    notEmpty: {
                        message: I18n._("moduloObligatorio")
                    }
                    
                }
            }
        }
    });
    
       
  //Evento que detecta el checkbox de modulos
  $(".parents").on('click',function(){	  
	  var tmp;
	  var tmpChildren;
	  var div;
	  var divChildren;
	  div = $(this).attr('id');
	  tmp = div.split('-');
	  if(parseInt(tmp[1]) > 0){		  
		  if( $(this).prop('checked') ) {
			  $(".permisos").each(function(i,item){
				  divChildren = $(item).attr('id');				
				  tmpChildren = divChildren.split('-');
				  if( parseInt(tmp[1]) === parseInt(tmpChildren[1]) ){
					  $(item).prop('checked', true);
				  }			  
			  });
		  }else{
			  $(".permisos").each(function(i,item){
				  divChildren = $(this).attr('id');
				  tmpChildren = divChildren.split('-');
				  if( parseInt(tmp[1]) === parseInt(tmpChildren[1]) ){
					  $(item).prop('checked', false);
				  }			  
			  });
		  }
		  
		  if( $(this).prop('checked') ) {
			  $(".parents_parents").each(function(i,item){
				  divChildren = $(item).attr('id');				
				  tmpChildren = divChildren.split('-');
				  if( parseInt(tmp[1]) === parseInt(tmpChildren[1]) ){
					  $(item).prop('checked', true);
				  }			  
			  });
		  }else{
			  $(".parents_parents").each(function(i,item){
				  divChildren = $(this).attr('id');
				  tmpChildren = divChildren.split('-');
				  if( parseInt(tmp[1]) === parseInt(tmpChildren[1]) ){
					  $(item).prop('checked', false);
				  }			  
			  });
		  }
	  }	  
  });
  
  $(".parents_parents").on('click',function(){	  
	  var tmp;
	  var tmpChildren;
	  var div;
	  var divChildren;
	  var contadoractivos;
	  contadoractivos = 0;
	  div = $(this).attr('id');
	  tmp = div.split('-');
	  if(parseInt(tmp[1]) > 0 && parseInt(tmp[2]) > 0){		  
		  if( $(this).prop('checked') ) {
			  $(".permisos").each(function(i,item){
				  divChildren = $(item).attr('id');				
				  tmpChildren = divChildren.split('-');
				  if( parseInt(tmp[1]) === parseInt(tmpChildren[1]) && parseInt(tmp[2]) === parseInt(tmpChildren[2]) ){
					  $(item).prop('checked', true);
				  }			  
			  });
			  $("#p-"+tmp[1]).prop('checked', true);
		  }else{
			  $(".permisos").each(function(i,item){
				  divChildren = $(this).attr('id');
				  tmpChildren = divChildren.split('-');
				  if( parseInt(tmp[1]) === parseInt(tmpChildren[1]) && parseInt(tmp[2]) === parseInt(tmpChildren[2])){
					  $(item).prop('checked', false);
				  }			  
			  });
			  contadoractivos = 0;
			  $(".permisos").each(function(i,item){
				  divChildren = $(this).attr('id');
				  tmpChildren = divChildren.split('-');
				  if( parseInt(tmp[1]) === parseInt(tmpChildren[1]) ){
					  if( $(item).prop('checked') ) {
						  contadoractivos++;
					  }
				  }				  
			  });			  
			  if(parseInt(contadoractivos) === 0){			  
				  $("#p-"+tmp[1]).prop('checked', false);
			  }else{
				  $("#p-"+tmp[1]).prop('checked', true);
			  }
		  }
	  }	  
  });
  
  
  //Evento que detecta el checkbox de perfil-permisos
  $(".permisos").on('click',function(){
      var tmpChildren;
      var div;
      var divChildren;
      var arreglo = new Array();
      var arregloP= new Array();
      var unir;
      var unirP;
      var divClick;
      var tmpClick;	
      var contadoractivos;
      var  modulosPermisosActivos = 0;
      contadoractivos = 0;
      divClick = $(this).attr('id'); 
      tmpClick = divClick .split('-');
      noPermisos = $("#noPermisos").val();
      
      $(".p-"+tmpClick[1]).each(function(i,item){
	  var nivel1 = $(this).attr('id');
	  $(".sub-"+tmpClick[1]).each(function(i,item){	      
	      var nivel2 = $(this).attr('id');
	      $("."+nivel2+"-c").each(function(j,itemj){
		  var nivel3 = $(this).attr('id');
		  if( $(itemj).prop('checked') ) {
		      modulosPermisosActivos++; 
		  }
	      });	      
	  });
      });
      $("#p-"+tmpClick[1]).prop('checked', false);
      $("#sub-"+tmpClick[1]+"-"+tmpClick[2]).prop('checked', false);
      if(modulosPermisosActivos > 0){
	  $("#sub-"+tmpClick[1]+"-"+tmpClick[2]).prop('checked', true);
	  $("#p-"+tmpClick[1]).prop('checked', true);
      }	  
  });

  
  
  
  
 
	
	//evento para restablecer al usuario
	$(".restablecerPerfil").on('click',function(event){
		event.preventDefault();
		url   = baseUrl+'seguridad/activaPerfil';
		var tmp = $(this).attr('id').split('-');
		var id  = tmp[1];
		var div = 'modal-activarPerfil-'+id; 
		if(parseInt(id) > 0 && String(token) !== ''){
			dataString = 'idPerfil='+id;	
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
						exito("El perfil ha sido activado");
						 setTimeout(function(){
							 location.href = baseUrl+"seguridad/perfil";
						},1500);							
					}else{						
						error(vacio);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$('#'+div).modal('hide');
					$('#procesando').fadeIn(1000).html("");
					error(vacio);
			    }				
			});
		}
		return false;
	});
	
	$("#creaPerfil, #actualizaPerfil").on('click',function(){
	    var contador = 0;
	    $(".parents").each(function(i,item){
		if($(this).prop('checked')){
		    contador++;
		}
	    });
	    if(contador === 0){
		error(I18n._("Noseleccionados"))
		return false;
	    }
	    return true;
	    
	});
	
});