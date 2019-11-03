var tiempo = 1000;
$(function() {
    
	$.tablesorter.themes.bootstrap = {
		table        : 'table table-bordered table-striped table-hover table-condensed',
		caption      : 'caption',
		header       : 'bootstrap-header',
		sortNone     : '',
		sortAsc      : '',
		sortDesc     : '',
		active       : '', 
		hover        : '',
		icons        : '', 
		iconSortNone : 'bootstrap-icon-unsorted', // class name added to icon when column is not sorted
		iconSortAsc  : 'glyphicon glyphicon-chevron-up', // class name added to icon when column has ascending sort
		iconSortDesc : 'glyphicon glyphicon-chevron-down', // class name added to icon when column has descending sort
		filterRow    : '', // filter row class; use widgetOptions.filter_cssFilter for the input/select element
		footerRow    : '',
		footerCells  : '',
		even         : '', // even row zebra striping
		odd          : ''  // odd row zebra striping
	};

	// call the tablesorter plugin and apply the uitheme widget
	$("table").tablesorter({
		theme : "bootstrap",
		widthFixed: true,
		headerTemplate : '{content} {icon}',
		//widgets : [ "uitheme", "filter", "zebra" ],
		widgets : [ "uitheme", "zebra" ],
		widgetOptions : {
			zebra : ["even", "odd"],
			filter_reset : ".reset",
			filter_cssFilter: "form-control",
		}
	});
	
	$(".searchs:input").keyup(function(e) {
	    var noRegs = $("#noRegs").val();
	    var noPage = 1;
	    var dataString = '';
	    if(e.which == 13) {
		if(parseInt($(this).val().length) > 0){
		    if(parseInt($("#idModulo").val()) > 0){
			dataString = 'idModulo='+$("#idModulo").val()+'&page='+noPage+'&noRegs='+noRegs+'&orden=asc';
			dataString += recuperaVariables();
			registrosView(dataString,$("#idModulo").val());	
		    }			
		}else{
			dataString = 'idModulo='+$("#idModulo").val()+'&page='+noPage+'&noRegs='+noRegs+'&orden=asc';
			dataString += recuperaVariables();
			registrosView(dataString,$("#idModulo").val());	
			
		}
	    }
	return false
	});



	$("#buttonacceso").on('click',function(e){
	var noRegs = $("#noRegs").val();
		var noPage = 1;
		var dataString = '';
		if(parseInt($("#idModulo").val()) > 0){
			dataString = 'idModulo='+$("#idModulo").val()+'&page='+noPage+'&noRegs='+noRegs+'&orden=asc';
			dataString += recuperaVariables();
			registrosView(dataString,$("#idModulo").val());	
		}
		return false	

        })

	
	$(".seleccionaIdtarea").click(function(){
	   if(parseInt($(this).attr('id')) >  0){
	       url   = baseUrl+'seguridad/asignaSession';
	       dataString = 'idTarea='+$(this).attr('id');
	       $.ajax({
		   type: 'POST',
		   url : url,		
		   headers: {
		       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		   },
		   dataType: 'json',
		   data: dataString,
		   beforeSend: function(){},			
		   success: function(data){
		       if(parseInt(data.exito) === 1){
			   console.log("se ha guardao en sesion");
		       }		       
		   },
		   error: function (xhr, ajaxOptions, thrownError) {				
		   }
	       });	
	   }
	   
	});
	/*$(".searchs:input").keyup(function(){	
		var noRegs = $("#noRegs").val();
		var noPage = 1;
		var dataString = '';
		var noCaracteres = 3;
		if(String($(this).attr('class'))  === 'form-control searchs numeros'){
			noCaracteres = 0;
		}
		if(String($(this).attr('class'))  === 'form-control searchs ip'){
			noCaracteres = 4;
		}
		if(String($(this).attr('class'))  === 'form-control searchs fecha'){
			noCaracteres = 10;
		}
		if(parseInt($(this).val().length) > 0){
			if(parseInt($("#idModulo").val()) > 0 && $(this).val().length >= noCaracteres){
				dataString = 'idModulo='+$("#idModulo").val()+'&page='+noPage+'&noRegs='+noRegs+'&orden=asc';
				dataString += recuperaVariables();
				registrosView(dataString,$("#idModulo").val());	
			}
			
		}else{
			dataString = 'idModulo='+$("#idModulo").val()+'&page='+noPage+'&noRegs='+noRegs+'&orden=asc';
			dataString += recuperaVariables();
			registrosView(dataString,$("#idModulo").val());	
			
		}
		return false		
	});*/
	
	
	$(".ordena").click(function(){
		var noRegs = $("#noRegs").val();
		var noPage = 1;
		var div = $(this).attr('id')
		var hid = div.replace('data-','');
		var campo = $(this).attr('data-no-column');
		var dataString = '';
		if(String($("#"+hid).val()) === "asc"){
			$("#"+hid).val("desc");
		}else{
			$("#"+hid).val("asc");
		}
		if(parseInt($("#idModulo").val()) > 0){
			dataString = 'idModulo='+$("#idModulo").val()+'&page='+noPage+'&noRegs='+noRegs+'&orden='+$("#"+hid).val()+'&campo='+campo;
			dataString += recuperaVariables();
			registrosView(dataString,$("#idModulo").val());
		}
		return false		
	});
	
	
	$("select.searchs").change(function(){
		var noRegs = $("#noRegs").val();
		var noPage = 1;
		var dataString = '';
		if(parseInt($("#idModulo").val()) > 0){
			dataString = 'idModulo='+$("#idModulo").val()+'&page='+noPage+'&noRegs='+noRegs+'&orden=asc';
			dataString += recuperaVariables();
			registrosView(dataString,$("#idModulo").val());	
		}
		return false		
	});
	
	 $("#noRegs").change(function(){
		 var noRegs = $("#noRegs").val();
		 var noPage = 1;
		 var dataString ="";
		 if(parseInt($("#idModulo").val()) > 0){
			 dataString = 'idModulo='+$("#idModulo").val()+'&page='+noPage+'&noRegs='+noRegs+'&orden=asc';
			 dataString += recuperaVariables();
			 registrosView(dataString,$("#idModulo").val());
		 }
		 return false;
	 });
	  
	  $(".pagina").click(function(){
			var noRegs = $("#noRegs").val();
			var noPage = $(this).attr('id');
			var dataString ="";
			if(parseInt($("#idModulo").val()) > 0){
				dataString = 'idModulo='+$("#idModulo").val()+'&page='+noPage+'&noRegs='+noRegs+'&orden=asc';
				dataString += recuperaVariables();
				registrosView(dataString,$("#idModulo").val());
			}
			return false;
	  });
	 
	  /** Modales **/
	  $(".modaleliminar").on('click',function(){		
		  	tmp = $(this).attr('id').split('-');
			$(".txtId").html(tmp[1]);
	  		$("#idRegistro").val(tmp[1]);
			$("#modal-delete").modal('show');
		});
	 
	  $(".modalresetear").on('click',function(){	
		  	tmp = $(this).attr('id').split('-');
			$(".txtId").html(tmp[1]);
	  		$("#idRegistro").val(tmp[1]);
			$("#modal-reset").modal('show');
	   });
	  
	  $(".modalrestablecer").on('click',function(){	
		  	tmp = $(this).attr('id').split('-');
			$(".txtId").html(tmp[1]);
	  		$("#idRegistro").val(tmp[1]);
			$("#modal-restablece").modal('show');
	  });
	  
	  $(".eliminar").on('click',function(){
		  var buffer = 0;
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'seguridad/desactivaUsuario';
			  dataString = 'idModulo='+$("#idModulo").val()+'&id='+$("#idRegistro").val();
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
						$('#procesando').fadeIn(1000).html("");
						if(parseInt(info.exito) === 1){
							$("#modal-restablece").modal('hide');
							exito(info.msg);
                            setTimeout(function(){location.href = baseUrl+'seguridad/usuario'},tiempo);							
						}else{
							error(info.msg);
						}						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});				 
		    }
	  });


	  $("#actualizaUsuarios").on('click',function(){
	  	var userIds = [];
	  	supervisorId = $("#supervisor").val();
	  	url   = baseUrl+'configuracion/actualizaGrupo';
 	  	$('.userCheck:checkbox:checked').each(function() {
 	  		userIds.push($(this).attr("value"));
 	  	});

		  // var buffer = 0;
			   dataString = 'idGrupo='+$("#grupoId").val()+'&userIds='+userIds+'&supervisorId='+supervisorId;
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
						 $('#procesando').fadeIn(1000).html("");
						 if(parseInt(info.exito) === 1){
						 	$("#modalGrupo").modal('hide');
						 	exito(info.msg);
						 	window.location.reload();
                             // setTimeout(function(){location.href = baseUrl+'seguridad/usuario'},tiempo);							
						 }else{
						 	error(info.msg);
						 	window.location.reload();
						 }						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});				 
		    
	  });


	  $("#actualizaUsuariosDist").on('click',function(){

	  	var userIds = [];
	  	url   = baseUrl+'configuracion/actualizaListaDistribucion';
 	  	$('.userCheck:checkbox:checked').each(function() {
 	  		userIds.push($(this).attr("value"));
 	  	});

		  // var buffer = 0;
			   dataString = 'idLista='+$("#idLista").val()+'&userIds='+userIds;
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
						 $('#procesando').fadeIn(1000).html("");
						 if(parseInt(info.exito) === 1){
						 	$("#modalGrupo").modal('hide');
						 	exito(info.msg);
						 	window.location.reload();
                             // setTimeout(function(){location.href = baseUrl+'seguridad/usuario'},tiempo);							
						 }else{
						 	error(info.msg);
						 }						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});				 
		    
	  });


	  $(".actualizaUsuariosListas").on('click',function(){

	  	 var userIds = [];
	  	 url   = baseUrl+'configuracion/actualizaActividad';
	  	 idModal=$(this).closest(".modalGeneral").attr("id");
	  	 idRetraso=$("#idRetraso").val();
	  	 idCumplimiento=$("#idCumplimiento").val();
	  	 

	  	tipo=$(this).attr("tipo");
	  	$(this).parent().parent().find('.userCheck:checkbox:checked').each(function() {
 	  		userIds.push($(this).attr("value"));
 	  	});	 

		  // // var buffer = 0;
			   dataString = 'idActividad='+$("#actividadId").val()+'&userIds='+userIds+'&tipo='+tipo+'&idRetraso='+idRetraso+'&idCumplimiento='+idCumplimiento;
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
						 $('#procesando').fadeIn(1000).html("");
						 if(parseInt(info.exito) === 1){
						 	$("#"+idModal).modal('hide');
						 	exito(info.msg);
						 	window.location.reload();
                             // setTimeout(function(){location.href = baseUrl+'seguridad/usuario'},tiempo);							
						 }else{
						 	error(info.msg);
						 }						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});				 
		    
	  });

	  $("#supervisor").on('change',function(){
	  	url   = baseUrl+'configuracion/actualizaSupervisor';
 	  	
 	  		supervisorid=$(this).val();
 	  		if(supervisorid !=''){


					dataString = 'idGrupo='+$("#grupoId").val()+'&supervisorId='+supervisorid;
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
						  $('#procesando').fadeIn(1000).html("");
						  if(parseInt(info.exito) === 1){
						  	exito(info.msg);
						  	window.location.reload();
						  }else{
						  	error(info.msg);
						  }						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});


 	  		}
 	  	
			   	return false;			 
		    
	  });


	  $(".usereliminar").on('click',function(){
	  	url   = baseUrl+'configuracion/eliminaUsuario';
 	  	
 	  		idUsuario=$("#idRegistro").val();//$(this).attr("id");
				dataString = 'idGrupo='+$("#grupoId").val()+'&idUsuario='+idUsuario;
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
						  $('#procesando').fadeIn(1000).html("");
						  if(parseInt(info.exito) === 1){
						  	exito(info.msg);
						  	window.location.reload();
						  }else{
						  	error(info.msg);
						  }						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});
 	  	
			   	return false;			 
		    
	  });


	  $(".userdisteliminar").on('click',function(){
	  	url   = baseUrl+'configuracion/eliminaDistribucionUsuario';
 	  	
 	  		idDetalleDist=$("#idRegistro").val();//$(this).attr("id");
				dataString = '&idDetalleDist='+idDetalleDist;
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
						  $('#procesando').fadeIn(1000).html("");
						  if(parseInt(info.exito) === 1){
						  	exito(info.msg);
						  	window.location.reload();
						  }else{
						  	error(info.msg);
						  }						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});
 	  	
			   	return false;			 
		    
	  });

$(".userlistaeliminar").on('click',function(){
	  	url   = baseUrl+'configuracion/eliminaActividadUsuario';
 	  	
 	  		idUsuario=$("#idRegistro").val();//$(this).attr("id");
				dataString = 'idActividad='+$("#actividadId").val()+'&idUsuario='+idUsuario;
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
						  $('#procesando').fadeIn(1000).html("");
						  if(parseInt(info.exito) === 1){
						  	exito(info.msg);
						  	window.location.reload();
						  }else{
						  	error(info.msg);
						  }						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});
 	  	
			   	return false;			 
		    
	  });


	  $(".resetear").on('click',function(){
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'seguridad/resetContrasena';
			  dataString = 'idModulo='+$("#idModulo").val()+'&id='+$("#idRegistro").val();
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
						$('#procesando').fadeIn(1000).html("");
						if(parseInt(info.exito) === 1){
							$("#modal-restablece").modal('hide');
							exito(info.msg);
                            setTimeout(function(){location.href = baseUrl+'seguridad/usuario'},tiempo);							
						}else{
							error(info.msg);
						}						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
			  });				 
		   }
	  });

	  $(".restablecer").on('click',function(){
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'seguridad/activaUsuario';
			  dataString = 'idModulo='+$("#idModulo").val()+'&id='+$("#idRegistro").val();
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
						$('#procesando').fadeIn(1000).html("");
						if(parseInt(info.exito) === 1){
							$("#modal-restablece").modal('hide');
							exito(info.msg);
                            setTimeout(function(){location.href = baseUrl+'seguridad/usuario'},tiempo);							
						}else{
							error(info.msg);
						}						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});		  
		  }
	  });
	  
	  
	  /****  Perfil  ****/
	  $(".eliminarPerfil").on('click',function(){
		  var buffer = 0;
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'seguridad/desactivaPerfil';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idPerfil='+$("#idRegistro").val();
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
						$('#procesando').fadeIn(1000).html("");
						if(parseInt(info.exito) === 1){
							$("#modal-restablece").modal('hide');
							exito(info.msg);
                            setTimeout(function(){location.href = baseUrl+'seguridad/perfil'},tiempo);							
						}else{
							error(info.msg);
						}						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});				 
		    }
	  });
	  
	  $(".restablecerPerfil").on('click',function(){
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'seguridad/activaPerfil';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idPerfil='+$("#idRegistro").val();
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
						$('#procesando').fadeIn(1000).html("");
						if(parseInt(info.exito) === 1){
							$("#modal-restablece").modal('hide');
							exito(info.msg);
                            setTimeout(function(){location.href = baseUrl+'seguridad/perfil'},tiempo);							
						}else{
							error(info.msg);
						}						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});		  
		  }
	  });
	  
	  /** Empresa  **/
	  
	  $(".eliminarEmpresa").on('click',function(){
		  var buffer = 0;
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'seguridad/desactivaEmpresa';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idEmpresa='+$("#idRegistro").val();
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
						$('#procesando').fadeIn(1000).html("");
						if(parseInt(info.exito) === 1){
							$("#modal-restablece").modal('hide');
							exito(info.msg);
                            setTimeout(function(){location.href = baseUrl+'seguridad/empresa'},tiempo);							
						}else{
							error(info.msg);
						}						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});				 
		    }
	  });
	  
	  $(".restablecerEmpresa").on('click',function(){
		  if(parseInt($("#idRegistro").val()) > 0 && parseInt($("#idModulo").val()) > 0){
			  url   = baseUrl+'seguridad/activaEmpresa';
			  dataString = 'idModulo='+$("#idModulo").val()+'&idEmpresa='+$("#idRegistro").val();
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
						$('#procesando').fadeIn(1000).html("");
						if(parseInt(info.exito) === 1){
							$("#modal-restablece").modal('hide');
							exito(info.msg);
                            setTimeout(function(){location.href = baseUrl+'seguridad/empresa'},tiempo);							
						}else{
							error(info.msg);
						}						
					},
					error: function (xhr, ajaxOptions, thrownError) {				
						$('#procesando').fadeIn(1000).html("");
					}
				});		  
		  }
	  });
	  
	 
	  /*.tablesorterPager({

		// target the pager markup - see the HTML block below
		container: $(".ts-pager"),

		// target the pager page select dropdown - choose a page
		cssGoto  : ".pagenum",

		// remove rows from the table to speed up the sort of large tables.
		// setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
		removeRows: false,

		// output string - default is '{page}/{totalPages}';
		// possible variables: {page}, {totalPages}, {filteredPages}, {startRow}, {endRow}, {filteredRows} and {totalRows}
		output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'

	});
*/
});
	
/*$(function(){

		// filter button demo code
		$('button.filter').click(function(){
			var col = $(this).data('column'),			
				txt = $(this).data('filter');
			$('table').find('.tablesorter-filter').val('').eq(col).val(txt);
			$('table').trigger('search', false);
			return false;
		});

		// toggle zebra widget
		$('button.zebra').click(function(){
			var t = $(this).hasClass('btn-success');
//			if (t) {
				// removing classes applied by the zebra widget
				// you shouldn't ever need to use this code, it is only for this demo
//				$('table').find('tr').removeClass('odd even');
//			}
			$('table')
				.toggleClass('table-striped')[0]
				.config.widgets = (t) ? ["uitheme", "filter"] : ["uitheme", "filter", "zebra"];
			$(this)
				.toggleClass('btn-danger btn-success')
				.find('i')
				.toggleClass('glyphicon-ok glyphicon-remove').end()
				.find('span')
				.text(t ? 'disabled' : 'enabled');
			$('table').trigger('refreshWidgets', [false]);
			return false;
		});
	});
*/

function registrosView(dataString,moduloId){
    if(parseInt(moduloId) > 0){
	url   = baseUrl+'busqueda';
	$("#result").html("");
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
		    if(info.indexOf("Sin resultados bajo el criterio buscado") !== -1){
			warning('Sin resultados bajo el criterio buscado');
		    }
		    $('#procesando').fadeIn(1000).html("");
		},
		error: function (xhr, ajaxOptions, thrownError) {				
		    $('#procesando').fadeIn(1000).html("");
		}
	});		
    }
}


function recuperaVariables(){
	var parametros="";
	$(".searchs:input").each(function(){
		if(String($(this).val()) !== ""){			
			parametros += "&"+$(this).attr('id')+"="+$(this).val();
		}			
	})
	$("select.searchs").each(function(){
		if(String($(this).val()) !== ""){
			parametros += "&"+$(this).attr('id')+"="+$(this).val();
		}			
	})		
	return parametros;		
}
