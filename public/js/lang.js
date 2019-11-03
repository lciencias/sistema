var language = 'es';
var baseUrl    = '/sistema/public/';
var procesando = '<div><img src="'+baseUrl+'img/loading.gif"/><br><span class="tituloProcesando">P r o c e s a n d o . . . .</span></div>'

var I18n = {
		"_": function(message){
			return this.messages[language][message] || message;
		},    
		messages: {
			'es': {
				"path":baseUrl,
				"valorInvalido" : "El valor es inválido",
				"nombreInvalido": "El nombre es inválido",
				"datoObligatorio":"El dato es obligatorio",
				"nombreObligatorio":"El nombre debe ser proporcionado",
				"nombreCaracteres":"El nombre sólo puede consistir de caracteres alfabéticos",
				"archivoInvalido":"Sólo se permiten archivos de imagenes y pdf's",
				"activo":"Activo",
				"noActivo":"No Activo",
				"renovarContado": "Renovar Contado",
				"renovarCredito": "Renovar Crédito",
				//Usuarios
				"idModuloUsuario":"3",
				"minimoUserCaracteres": "El nombre del usuario debe tener mínimo 5 caracteres y máximo 50",
				"minimoCorreoCaracteres":"El correo electrónico del usuario debe tener mínimo 5 caracteres y máximo 50",
				"correoInvalido" : "El correo electrónico es inválido",
				"correoObligatorio":"El correo electrónico debe ser proporcionado",
				"minimoCorreoCaracteresUser":"El correo electrónico del usuario debe tener mínimo 5 caracteres y máximo 50",
				"perfilUsuarioObligatorio":"Se debe especificar el perfil para dar de alta un usuario.",
				"errorPerfilUsuario":"Favor de proporcionar el perfil del usuario",
				"errorNombreUsuario":"Favor de proporcionar el nombre del usuario",
				"errorCorreoUsuario":"Favor de teclear un correo electrónico válido",
				"errorCorreoUsuarioOblig":"Favor de proporcionar el correo del usuario",
				"exitoGuardaUsuario":"El usuario ha sido activado",
				"exitoReseteoUsuario":"Se ha reseteado la contraseña",
				"empresaUsuarioObligatorio":"Se debe especificar la empresa",
				"Noseleccionados" :"Favor de seleccionar al menos un módulo",
				"permisosModulos":"Favor de seleccionar al menos un permiso al módulo",
				"SinCompetencia" :"Favor de seleccionar al menos una competencia",
				

				//Perfil
				"idModuloPerfil":"2",
				"perfilInvalido":"Perfil inválido",
				"minimoPerfilCaracteres":"El nombre del perfil debe tener mínimo 5 caracteres y máximo 50",
				"perfilEmpresaObligatorio":"Se debe especificar la empresa a la que pertenece el perfil",
				
				//Empresa
				"idModuloEmpresa":"0",
				"empresaInvalida":"Empresa inválida",
				"minimoEmpresaCaracteres":"El nombre de la empresa debe tener mínimo 5 caracteres y máximo 50",
				"minimoRazonCaracteres": "La razón social debe tener mínimo 5 caracteres y máximo 50",
				"minimoNombreCaracteres":"El nombre del representante debe tener mínimo 3 caracteres y máximo 50",
				"minimoPaternoCaracteres":"El apellido paterno del representante debe tener mínimo 3 caracteres y máximo 50",
				"minimoMaternoCaracteres":"El apellido materno del representante debe tener mínimo 3 caracteres y máximo 50",
				"minimoCorreoCaracteres":"El correo electrónico del representante debe tener mínimo 5 caracteres y máximo 50",
				"minimoRFC1Caracteres":"Sólo letras",
				"minimoRFC2Caracteres":"Sólo números",
				"minimoRFC3Caracteres":"Sólo letras y números",
				//Modulos
				"moduloObligatorio":"Se debe especificar por lo menos un módulo",
					
				//Accesos
				"idModuloAcceso":"43",
				//Bitacoras
				"idModuloBitacora":"44",
				
				
				//extras
				"extra1" : "Opciones de diseño",
				"extra2" : "Diseño fijo",
				"extra3" : "Activar el diseño fijo. No puede usar diseños fijos y en caja juntos",
				"extra4" : "Diseño en caja",
				"extra5" : "Activar el diseño en caja",
				"extra6" : "Alternar barra lateral",
				"extra7" : "Alternar el estado de la barra lateral izquierda (abrir o colapsar)",
				"extra8" : "Barra lateral Expandir en el control deslizante",
				"extra9" : "Deje que la barra lateral mini se expanda en el aire estacionario",
				"extra10" : "Alternar la barra lateral derecha Slide",
				"extra11" : "Alternar entre la diapositiva sobre el contenido y los efectos de contenido push",
				"extra12" : "Alternar la barra lateral derecha",
				"extra13" : "Alternar entre máscaras oscuras y claras para la barra lateral derecha",
				"extra14" : "Temas",
				"extra15" : "Azul",
				"extra16" : "Negro",
				"extra17" : "Morado",
				"extra18" : "Verde",
				"extra19" : "Rojo",
				"extra20" : "Amarillo",
				"extra21" : "Azul con blanco",
				"extra22" : "Negro con blanco",
				"extra23" : "Morado con blanco",
				"extra24" : "Verde con blanco",
				"extra25" : "Rojo con blanco",
				"extra26" : "Amarillo con blanco",
				"extra27" : "Configuración general",
				"extra28" : "Reportar el uso del panel",
				"extra29" : "Parte de la información sobre esta opción de configuración general",
				"extra30" : "Permitir redirección de correo",
				"extra31" : "Otros conjuntos de opciones están disponibles",
				"extra32" : "Exponer el nombre del autor en publicaciones",
				"extra33" : "Permitir que el usuario muestre su nombre en publicaciones de blog",
				"extra34" : "configuraciones de chat",
				"extra35" : "Muéstrame como en línea",
				"extra36" : "Desactivar las notificaciones",
				"extra37" : "Eliminar el historial de chat"
			},
			'en': {
				"path" : baseUrl,
				"Spanish": "Spanish",
				"extra1" : "Layout Options",
				"extra2" : "Fixed layout",
				"extra3" : "Activate the fixed layout. You can't use fixed and boxed layouts together",
				"extra4" : "Boxed Layout",
				"extra5" : "Activate the boxed layout",
				"extra6" : "Toggle Sidebar",
				"extra7" : "Toggle the left sidebar's state (open or collapse)",
				"extra8" : "Sidebar Expand on Hover",
				"extra9" : "Let the sidebar mini expand on hover",
				"extra10" : "Toggle Right Sidebar Slide",
				"extra11" : "Toggle between slide over content and push content effects",
				"extra12" : "Toggle Right Sidebar Skin",
				"extra13" : "Toggle between dark and light skins for the right sidebar",
				"extra14" : "Skins",
				"extra15" : "Blue",
				"extra16" : "Black",
				"extra17" : "Purple",
				"extra18" : "Green",
				"extra19" : "Red",
				"extra20" : "Yellow",
				"extra21" : "Blue Light",
				"extra22" : "Black Light",
				"extra23" : "Purple Light",
				"extra24" : "Green Light",
				"extra25" : "Red Light",
				"extra26" : "Yellow Light",
				"extra27" : "General Settings",
				"extra28" : "Report panel usage",
				"extra29" : "Some information about this general settings option",
				"extra30" : "Allow mail redirect",
				"extra31" : "Other sets of options are available",
				"extra32" : "Expose author name in posts",
				"extra33" : "Allow the user to show his name in blog posts",
				"extra34" : "Chat Settings",
				"extra35" : "Show me as online",
				"extra36" : "Turn off notifications",
				"extra37" : "Delete chat history",
				"extra38" : "Tasks Progress",
				"extra39" : "Delete chat history"

				//Extras
	
			}
		}
};
