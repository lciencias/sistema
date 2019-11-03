var url;
var idPerfil;
var dataString;
var token;
var modulos;
var seleccionados;
var noSeleccionados;
var vacio = '';
$(document).ready(function() {
	token = $('meta[name="csrf-token"]').attr('content');	
	$("#submodulosSeleccionados").val();
	$("#submodulosNoSeleccionados").val();
	//Evento para mostrar un modal con los permisos de cada modulo
	
	
	$(".verPermisos").on('click',function(event){
		event.preventDefault();
		var permisosselecc  = $("#submodulosSeleccionadosPermisos").val();
		var noPerfil = $("#idperfil").val();
		var noModulo = $(this).attr('id');
		var tmp = noModulo.split('-');
		var idUsuario = $("#id").val();
		if(String( $(this).attr('id') ) !== '' && String(token) !== ''){
			url   = baseUrl+'seguridad/peticionModuloPermiso';
			dataString ='idPerfil='+$("#idperfil").val()+'&modulo='+$(this).attr('id')+"&listapermisos="+permisosselecc+"&idUsuario="+idUsuario;
			$.ajax({
				type: 'POST',
				url : url,		
				dataType: 'json',
				 headers: {
	                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	             },
				data: dataString,
				beforeSend: function(){
					$('#procesando').html(procesando);
				},			
				success: function(data){
					var cadena = "";
					var inputCheck="";
					var permisoId = 0;
					var msg = "";
					var permisosDef = data.permisosDefault;
					var permisos = data.permisos;
					$('#procesando').fadeIn(1000).html("");
					$.each(permisosDef, function(i, item) {						
						inputCheck="";
						permisoId = permisosDef[i].idpermiso;
						$.each(permisos, function(j,jtem){
							if(parseInt(permisoId) === parseInt(permisos[j].idpermiso)){
								inputCheck=" checked ";
								cadena += permisoId+"-";
							}
						})						
						msg += "<input type='checkbox' "+inputCheck+" name='g-"+tmp[1]+'-'+tmp[2]+'-'+permisosDef[i].idpermiso+"' " +
								"id='g-"+tmp[2]+'-'+tmp[2]+'-'+permisosDef[i].idpermiso+"' value='"+permisosDef[i].idpermiso+"' class='permisosModulos-"+tmp[2]+"' style='border:1px solid #e5e5e5;'> "+" "+permisosDef[i].nombre+"<br>";						
					});
					$("#numeroModulos").val(tmp[1]+"*"+tmp[2]);
					$("#modalAjaxLabel").html("Permisos asignados al m&oacute;dulo")
					$("#modalAjaxbuffer").html(msg);
					$("#modalAjax").modal('show');
				},
				error: function (xhr, ajaxOptions, thrownError) {
					error(vacio);
			    }				
			});			
		}
	});

	
	//Evento que elimina el módulo padre del perfil del usuario
	$(".eliminaModuloPadre").on('click',function(){		
		if(parseInt($(this).attr('id')) > 0 && String(token) !== ''){
			$("#idModulo").val($(this).attr('id'));
			$("#idParent").val(0);
			$("#eliminaModuloUsuario").modal('show');
			return false;
		}
	});

		
	//Evento que elimina el módulo del perfil del usuario
	$(".eliminaModulo").on('click',function(){ 
		if(String( $(this).attr('id') ) !== '' && String(token) !== ''){
			var tmp = $(this).attr('id').split('-');
			$("#idModulo").val(tmp[2]);
			$("#idParent").val(tmp[1]);						
			$("#eliminaModuloUsuario").modal('show');
			return false;
		}
	});

	
	// Evento para eliminar un modulo al usuario
	$("#btnEliminaModuloUsuario").on('click',function(event){
		event.preventDefault();
		var seleccionados   = $("#submodulosSeleccionados").val(); 
		var noseleccionados = $("#submodulosNoSeleccionados").val();
		var permisosselecc  = $("#submodulosSeleccionadosPermisos").val();		
		if( parseInt($("#idperfil").val()) > 0 && parseInt($("#idModulo").val()) > 0 && parseInt($("#idParent").val()) >= 0 && String(token) !== '' && seleccionados !== undefined && noseleccionados != undefined){
			$("#tableModulos").html("");
			$('#procesando').html(procesando);
			url   = baseUrl+'seguridad/peticionDeshabilitaModulo';
			dataString = 'idPerfil='+$("#idperfil").val()+'&idModulo='+$("#idModulo").val()
						 +"&seleccionados="+seleccionados+"&noseleccionados="+noseleccionados
						 +"&listapermisos="+permisosselecc;
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
					$("#eliminaModuloUsuario").modal('hide');
					$('#tableModulos').html(data);
					$('#procesando').fadeIn(1000).html("");
					return false;
				},
				error: function (xhr, ajaxOptions, thrownError) {
					error(vacio);
				},
				complete: function(){
					event.stopPropagation();
					return false;
				}					
			});			
			return false;
		}
	});
	
	$("#agregaModulos").on('click',function(){
		$("#myModalAgregar").modal('show');
	});
	
	
	$(".anadeModulo").on('click',function(event){
		event.preventDefault();
		var div = $(this).attr('id');
		var tmp = div.split('-');
		var seleccionados   = $("#submodulosSeleccionados").val(); 
		var noseleccionados = $("#submodulosNoSeleccionados").val();
		var permisosselecc  = $("#submodulosSeleccionadosPermisos").val();
		var clase = "permisosModulos-"+tmp[2];
		var idUsuario = $("#id").val();
		var permisos = "";
		$(".permisosModulos-"+tmp[2]).each(function(){
			if($(this).prop('checked') ){
				var tmp2 = $(this).attr('id').split('-');
				permisos += tmp2[2]+"|"; 
			}
		});

		if( parseInt($("#idperfil").val()) > 0 && parseInt(tmp[1]) > 0 && parseInt(tmp[2]) >= 0 && String(token) !== '' && seleccionados !== undefined && noseleccionados != undefined){
		    if(String(permisos) !== ''){
			$("#tableModulos").html("");
			$('#procesando').html(procesando);
			url   = baseUrl+'seguridad/peticionanadeModulo';
			dataString = 'idPerfil='+$("#idperfil").val()+'&idModulo='+tmp[2]+"&seleccionados="+seleccionados+"&noseleccionados="+noseleccionados+"&idparent="+tmp[1]+"&permisos="+permisos+"&listapermisos="+permisosselecc+"&idUsuario="+idUsuario;
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
				success: function(msg){
					$("#myModalAgregar").modal('hide');
					$('#tableModulos').html(msg);
					$('#procesando').fadeIn(1000).html("");
					$(".modal-backdrop").remove();
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$('#procesando').fadeIn(1000).html("");
					$(".modal-backdrop").remove();
					error(vacio);
			      },
				complete: function(){
					event.stopPropagation();
				}				
			});		
		    }else{
			error(I18n._("permisosModulos"));			
		    }		    		 
		}		
		return false;
	});
	
	
	$(".perfilnAjax").on('change',function(evt){	
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
					return false;
				},
				error: function (xhr, ajaxOptions, thrownError) {
					error(vacio);
					return false;
				},
				complete: function(){
					evt.stopPropagation();
					return false;
				}
			});
			return false;
		}
		evt.stopPropagation();
		return false;
	});
	
	$("#asignaPermisosPerfil").on('click',function(evt){	
		evt.preventDefault();
		var div = $("#numeroModulos").val();
		var tmp = div.split('*');
		var seleccionados   = $("#submodulosSeleccionados").val(); 
		var noseleccionados = $("#submodulosNoSeleccionados").val();
		var permisosselecc  = $("#submodulosSeleccionadosPermisos").val();
		var clase = "permisosModulos-"+tmp[2];
		var idUsuario = $("#id").val();
		var permisos = "";
		$(".permisosModulos-"+tmp[1]).each(function(){
			if($(this).prop('checked') ){
				var tmp2 = $(this).attr('id').split('-');
				permisos += tmp2[3]+"|"; 
			}
		});
		if( parseInt($("#idperfil").val()) > 0 && parseInt(tmp[0]) > 0 && parseInt(tmp[1]) >= 0 && String(token) !== '' && seleccionados !== undefined && noseleccionados != undefined){
			$("#tableModulos").html("");
			$('#procesando').html(procesando);
			url   = baseUrl+'seguridad/peticionanadeModulo';
			dataString = 'idPerfil='+$("#idperfil").val()+'&idModulo='+tmp[1]+"&seleccionados="+seleccionados+"&noseleccionados="+noseleccionados+"&idparent="+tmp[0]+"&permisos="+permisos+"&listapermisos="+permisosselecc+"&idUsuario="+idUsuario;
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
				success: function(msg){
					$("#modalAjax").modal('hide');
					$('#tableModulos').html(msg);
					$('#procesando').fadeIn(1000).html("");
					$(".modal-backdrop").remove();
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$('#procesando').fadeIn(1000).html("");
					$(".modal-backdrop").remove();
					error(vacio);
			      },
				complete: function(){
					event.stopPropagation();
				}				
			});			
			return false;
		}		
	})
	//fin de document ready
});


function exito(msg){
	$('#procesando').fadeIn(1000).html("");
	$("#errorJs").addClass("alert alert-success alert-dismissible" );
	$("#errorJs").html(msg);
    setTimeout(function(){
    	$("#errorJs").removeClass("alert-success alert-dismissible" );
    	$("#errorJs").html("");                    	
    },2500);	
	
}

function error(msg){
	$('#procesando').fadeIn(1000).html("");
	$("#errorJs").addClass("alert alert-danger alert-dismissible" );
	if(String(msg) === '')		
		$("#errorJs").html("Error inesperado favor de notificar al administrador.");
	else
		$("#errorJs").html(msg);
    setTimeout(function(){
    	$("#errorJs").removeClass("alert-danger alert-dismissible" );
    	$("#errorJs").html("");                    	
    },2500);	
}


function moduloPermiso(idModulo,idPermiso){
	this.idModulo = idModulo;
	this.idPermiso = idPermiso;
}