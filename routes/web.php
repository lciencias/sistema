<?php


use Illuminate\Support\Facades\Route;

//***********************************Generales********************
Route::get('excel/{id}','ExcelController@index');
Route::post('general/validaNombre','EmpresaController@validaNombreElemento');
Route::get('/', 'Auth\LoginController@showLoginForm');
Route::get('home', 'HomeController@index')->name('home');
Route::post('login','Auth\LoginController@login')->name('login');
Route::post('actualizapassword','ResetPasswordController@reset')->name('actualizapassword');
Route::post('logout','Auth\LoginController@logout')->name('logout');
Route::get('logout','Auth\LoginController@logout')->name('logout');
Route::resource('layouts/home', 'MenuController');
Route::resource('auth/reset/reset','ResetPasswordController');
Route::post('auth/reset/update','ResetPasswordController@update');
Route::post('busqueda', 'BusquedasController@index');
Route::auth();
Route::get('/home', 'HomeController@index');
Route::get('/{slug?}', 'HomeController@index');
Route::resource('/jobs/ejecuta/cronReporteErrores','CronReporteErroresController');


//Home
Route::post('seguridad/session','HomeController@session');

//********************Modulo Seguridad********************

//Empresa
Route::resource('seguridad/empresa','EmpresaController');
Route::get('seguridad.empresa.edit/{id}', ['as' => 'seguridad.empresa.edit','uses' => 'EmpresaController@edit']);
Route::post('seguridad.empresa.update/{id}', ['as' => 'seguridad.empresa.update','uses' => 'EmpresaController@update']);
Route::post('seguridad/activaEmpresa', 'EmpresaController@activar');
Route::post('seguridad/desactivaEmpresa', 'EmpresaController@desactivar');
Route::post('seguridad/regresaPerfiles','EmpresaController@buscaPerfiles');
Route::post('empresa/validaNombre/','EmpresaController@validaNombre');
Route::post('empresa/validaMailRepresentante/','EmpresaController@validaMailRepresentante');
Route::post('seguridad/guardaEmpresa', 'EmpresaController@storeAjax');
Route::post('seguridad/editarEmpresa', 'EmpresaController@editAjax');

//Perfil
Route::resource('seguridad/perfil','PerfilController');
Route::get('seguridad/perfil/edit/{id}',  ['as' => 'seguridad.perfil.edit', 'uses' => 'PerfilController@edit']);
Route::post('seguridad.perfil.update/{id}',  ['as' => 'seguridad.perfil.update','uses' => 'PerfilController@update']);
Route::post('seguridad/activaPerfil', 'PerfilController@activar');
Route::post('seguridad/desactivaPerfil', 'PerfilController@desactivar');
Route::resource('seguridad/empleado','EmpleadoController');


//Usuario
Route::resource('seguridad/usuario','UsuarioController');
Route::get('seguridad.usuario.edit/{id}', ['as' => 'seguridad.usuario.edit','uses' => 'UsuarioController@edit']);
Route::post('seguridad.usuario.update/{id}', ['as' => 'seguridad.usuario.update','uses' => 'UsuarioController@update']);
Route::post('seguridad/activaUsuario', 'UsuarioController@activar');
Route::post('seguridad/desactivaUsuario', 'UsuarioController@desactivar');
Route::post('seguridad/resetContrasena', 'UsuarioController@reset');
Route::post('seguridad/buscaModulosPermiso', 'UsuarioController@peticionModuloPermisoId');
Route::post('seguridad/buscaModulosPerfil', 'UsuarioController@peticion');
Route::post('seguridad/peticionModuloPermiso', 'UsuarioController@peticionModuloPermiso');
Route::post('seguridad/peticionDeshabilitaModulo', 'UsuarioController@deshabiltaModulo');
Route::post('seguridad/peticionanadeModulo', 'UsuarioController@habilitaModulo');
Route::post('usuario/validaMail/','UsuarioController@validaMail');

//Permiso
Route::resource('seguridad/permiso','PermisoController');
Route::post('seguridad/store','PermisoController@store');

//Cliente
Route::resource('gestion/cliente','ClienteController');
Route::get('gestion.cliente.edit/{id}', ['as' => 'gestion.cliente.edit','uses' => 'ClienteController@edit']);
Route::post('gestion.cliente.update/{id}', ['as' => 'gestion.cliente.update','uses' => 'ClienteController@update']);
Route::post('gestion/activaCliente', 'ClienteController@activar');
Route::post('gestion/desactivaCliente', 'ClienteController@desactivar');
Route::post('cliente/validaNombre/','ClienteController@validaNombre');
Route::post('cliente/validaMailRepresentante/','ClienteController@validaMailRepresentante');
Route::post('cliente/regresaMunicipios','ClienteController@buscaMunicipios');


//Candidato
Route::resource('gestion/candidato','CandidatoController');
Route::get('gestion.candidato.edit/{id}', ['as' => 'gestion.candidato.edit','uses' => 'CandidatoController@edit']);
Route::post('gestion.candidato.update/{id}', ['as' => 'gestion.candidato.update','uses' => 'CandidatoController@update']);
Route::post('gestion/activaCandidato', 'CandidatoController@activar');
Route::post('gestion/desactivaCandidato', 'CandidatoController@desactivar');

//Talento
Route::resource('catalogos/talento','TalentoController');
Route::get('catalogos/talento/edit/{id}', ['as' => 'catalogos.talento.edit','uses' => 'TalentoController@edit']);
Route::post('catalogos.talento.update/{id}', ['as' => 'catalogos.talento.update','uses' => 'TalentoController@update']);
Route::post('catalogos/activaTalento', 'TalentoController@activar');
Route::post('catalogos/desactivaTalento', 'TalentoController@desactivar');

//Competencia
Route::resource('catalogos/competencia','CompetenciaController');
Route::get('catalogos/competencia/edit/{id}', ['as' => 'catalogos.competencia.edit','uses' => 'CompetenciaController@edit']);
Route::post('catalogos.competencia.update/{id}', ['as' => 'catalogos.competencia.update','uses' => 'CompetenciaController@update']);
Route::post('catalogos/activaCompetencia', 'CompetenciaController@activar');
Route::post('catalogos/desactivaCompetencia', 'CompetenciaController@desactivar');
Route::post('competencia/regresaNivelesComportamiento','CompetenciaController@buscaNivelesCalificacionComportamiento');
Route::post('competencia/regresaComportamientos','CompetenciaController@buscaComportamientos');

//Tipo ejercicio
Route::resource('catalogos/tipoEjercicio','TipoEjercicioController');
Route::get('catalogos/tipoEjercicio/edit/{id}', ['as' => 'catalogos.tipoEjercicio.edit','uses' => 'TipoEjercicioController@edit']);
Route::post('catalogos.tipoEjercicio.update/{id}', ['as' => 'catalogos.tipoEjercicio.update','uses' => 'TipoEjercicioController@update']);
Route::post('catalogos/activaTipoEjercicio', 'TipoEjercicioController@activar');
Route::post('catalogos/desactivaTipoEjercicio', 'TipoEjercicioController@desactivar');
Route::post('competencia/regresaNivelesComportamiento','TipoEjercicioController@buscaNivelesCalificacionComportamiento');
Route::post('competencia/regresaEjercicios','TipoEjercicioController@buscaEjercicios');


//Prueba
Route::resource('catalogos/prueba','PruebaController');
Route::get('catalogos/prueba/edit/{id}', ['as' => 'catalogos.prueba.edit','uses' => 'PruebaController@edit']);
Route::post('catalogos.prueba.update/{id}', ['as' => 'catalogos.prueba.update','uses' => 'PruebaController@update']);
Route::post('catalogos/activaPrueba', 'PruebaController@activar');
Route::post('catalogos/desactivaPrueba', 'PruebaController@desactivar');

//Ejercicio
Route::resource('catalogos/ejercicio','EjercicioController');
Route::get('catalogos/ejercicio/edit/{id}',  ['as' => 'catalogos.ejercicio.edit', 'uses' => 'EjercicioController@edit']);
Route::post('catalogos.ejercicio.update/{id}',  ['as' => 'catalogos.ejercicio.update','uses' => 'EjercicioController@update']);
Route::post('catalogos/activaEjercicio', 'EjercicioController@activar');
Route::post('catalogos/desactivaEjercicio', 'EjercicioController@desactivar');


//Perfil puesto
Route::resource('gestion/perfilPuesto','PerfilPuestoController');
Route::get('gestion/perfilPuesto/edit/{id}', ['as' => 'gestion.perfilPuesto.edit','uses' => 'PerfilPuestoController@edit']);
Route::post('gestion.perfilPuesto.update/{id}', ['as' => 'gestion.perfilPuesto.update','uses' => 'PerfilPuestoController@update']);
Route::post('gestion/activaPerfilPuesto', 'PerfilPuestoController@activar');
Route::post('gestion/desactivaPerfilPuesto', 'PerfilPuestoController@desactivar');


//**********************************Modulo Evaluacion********************
Route::get('evaluacion/evaluacion','EvaluacionController@index');
Route::post('evaluacion/evaluacion','EvaluacionController@index');
Route::post('evaluacion/store','EvaluacionController@store');
Route::get('evaluacion/evalua/{id}', ['as' => 'evaluacion.evalua','uses' => 'EvaluacionController@evalua']);
Route::post('evaluacion/calificar','CalificarController@index');
Route::get('evaluacion/calificar','CalificarController@index');
Route::get('evaluacion.calificar.edit/{id}', ['as' => 'evaluacion.calificar.edit','uses' => 'CalificarController@edit']);

Route::post('evaluacion.calificar.update/{$id}', ['as' => 'evaluacion.calificar.update','uses' => 'CalificarController@update']);

//**********************************Modulo Bitacoras********************
//Accesos
Route::get('bitacoras/acceso','AccesoController@index');
Route::post('bitacoras/acceso','AccesoController@index');


//Movimientos
Route::get('bitacoras/movimientos','MovimientoController@index');
Route::post('bitacoras/movimientos','MovimientoController@index');


Route::get('descargar-usuarios', 'PdfController@pdf')->name('usuarios.pdf');
Route::get('descargar-perfiles', 'PdfController@pdfPerfiles')->name('perfiles.pdf');
Route::get('descargar-empresas', 'PdfController@pdfEmpresas')->name('empresas.pdf');
Route::get('fileUpload', function () {return view('fileUpload');});
Route::post('fileUpload', ['as'=>'fileUpload','uses'=>'ArchivoController@CargaArchivo']);
Route::resource('catalogos/archivos','ArchivoController');


