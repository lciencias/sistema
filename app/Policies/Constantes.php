<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 13/03/2018
 * Time: 05:51 PM
 */

namespace sistema\Policies;

 
class Constantes {
    
    
    //localhost
    const MAIL_FROM         = "enviomails.test2017@gmail.com";
    const MAIL_REMITENTE    = "Notificaciones Sistema";
    const THIS_IP             = "localhost"; // cambiar est ip por la ip del server
    const EMAIL_SOPORTE 	= 'molina_mikea@hotmail.com';
    const PATH_ABSOLUTO       = "c:/xampp7/htdocs"; // cambiar esto por el path donde esté alojado el proyecto
    const PATH_ABSOLUTO2       = "/xampp7/htdocs"; // cambiar esto por el path donde esté alojado el proyecto
    
    //produccion
//     const MAIL_FROM         = "comunicacion@sigese.com";
//     const MAIL_REMITENTE    = "Notificaciones Sistema";
//     const THIS_IP             = "www.sigese.com"; // cambiar est ip por la ip del server
//     const EMAIL_SOPORTE 	= 'molina_mikea@hotmail.com';
//     const PATH_ABSOLUTO       = "/home/sigeseco/public_html"; // cambiar esto por el path donde esté alojado el proyecto
//     const PATH_ABSOLUTO2       = "/home/sigeseco/public_html"; // cambiar esto por el path donde esté alojado el proyecto
    
    
	const PATH_SYSTEM       = self::PATH_ABSOLUTO2."/".self::THIS_SERVER_NAME."/";
	const PATH_SYSTEM_UPLOAD= self::PATH_ABSOLUTO."/".self::THIS_SERVER_NAME."/public/upload/";
	const PATH_WEB          = "http://".self::THIS_IP."/".self::THIS_SERVER_NAME."/public/";
	const PATH_WEB_UPLOAD   = "http://".self::THIS_IP."/".self::THIS_SERVER_NAME."/public/upload/";
	const PATH_MENU         = "./xml/menu.xml";
	const UPLOAD_DIR    	= "/public/imagenes/empresas/";
	const MAX_UPLOAD        = 1580000;
	const URL_ACCESO        = "http://".self::THIS_IP."/".self::THIS_SERVER_NAME."/public/";
	const THIS_SERVER_NAME    = "sistema"; // cambiar est ip por la ip del server
	const PATH                = self::PATH_ABSOLUTO."/".self::THIS_SERVER_NAME."/public/xml/menu.xml";
	const EXPIRASESSION = 60;
	const SINCAMBIOS        = "Sin cambios";
	const UPLOAD_TEMPDIR    = "/public/imagenes/uploads/tmp/";
	const UPLOAD_DIRGRABA 	= "/imagenes/empresas/";
	const REPORTE_ERRORES 	= true;
	
	
		
	const CONTROLLER_USUARIO = "UsuarioController";
	const CONTROLLER_EMPRESA = "EmpresaController";
	const CONTROLLER_PERFIL  = "PerfilController";
	const CONTROLLER_TALENTO = "TalentoController";
	const CONTROLLER_COMPORTAMIENTO = "ComportamientoController";
	const CONTROLLER_COMPETENCIA = "CompetenciaController";
	const CONTROLLER_CLIENTE = "ClienteController";
	const CONTROLLER_EJERCICIO = "EjercicioController";
	const CONTROLLER_TIPO_EJERCICIO = "TipoEjercicioController";
	const CONTROLLER_CANDIDATO = "CandidatoController";
	const CONTROLLER_PRUEBA = "PruebaController";
	const CONTROLLER_PERFIL_PUESTO = "PerfilPuestoController";
	
		
	
	//Paginador
    const PAGINATOR   = 10;
    const NOPAGINA    = 1;
    const ORDEN       = " asc";
    const ACTIVO      = 1;
    const NOACTIVO    = 0;
    
    //Acciones en el sistema
    const ACCION_ALTA        = 1;
    const ACCION_ACTUALIZAR  = 2;
    const ACCION_ELIMINAR    = 3;
    const ACCION_ACTIVAR     = 4;
    const ACCION_RESETEO     = 5;
    const ACCION_ACTPASSWORD = 6;
    
    
    //Perfiles del sistema
    const PERFIL_ADMIN_GENERAL = 1;
    const PERFIL_ADMIN_EMPRESA = 2;
    const PERFIL_ADMIN_CLIENTE = 3;
    const PERFIL_ADMIN_CANDIDATO = 4;
  
    
    //modulos
    const MODULO_ID_PERFIL = 4;
    const MODULO_ID_EMPRESA = 3;
    const MODULO_ID_TALENTO = 13;
    const MODULO_ID_COMPETENCIA = 12;
    const MODULO_ID_CLIENTE = 10;
    const MODULO_ID_EJERCICIO = 14;
    const MODULO_ID_TIPO_EJERCICIO = 27;
    const MODULO_ID_CANDIDATO = 8;
    const MODULO_ID_PRUEBA = 18;
    const MODULO_ID_PERFIL_PUESTO = 17;
    
    //Etapas alta de prueba
    const ETAPA_PRUEBA_INICIAL                    = 0;
    const ETAPA_PRUEBA_POR_CARGAR_RESULTADOS      = 1;
    const ETAPA_PRUEBA_POR_CARGAR_PREGUNTAS       = 2;
    const ETAPA_PRUEBA_POR_CARGAR_INTERPRETACION  = 3;
    const ETAPA_PRUEBA_FINAL                      = 4;
    
    
    //Etapas alta de perfil de puesto
    const ETAPA_PERFIL_PUESTO_INICIAL                    = 0;
    const ETAPA_PERFIL_PUESTO_POR_CARGAR_TALENTOS      = 1;
    const ETAPA_PERFIL_PUESTO_POR_CARGAR_PRUEBAS       = 2;
    const ETAPA_PERFIL_PUESTO_POR_CARGAR_COMPETENCIAS  = 3;
    const ETAPA_PERFIL_PUESTO_FINAL                      = 4;
    
    
    //activo
    const ESTATUS_ACTIVO = '1';
    const ESTATUS_INACTIVO = '0';
    const ESTATUS_TODOS = '';
    
   
    const CATALOGO_ESTATUS = array(1 => 'Activo',0=> 'No Activo');
    const CATALOGO_ESTADO_CIVIL = array(1 => 'Soltero(a)',2=> 'Casado(a)', 3=> 'Divorciado(a)', 4=> 'Viudo(a)', 5=> 'Unión libre');
    const CATALOGO_GENERO = array('M' => 'Masculino','F'=> 'Femenino');
    const CATALOGO_TIPO_SANGRE = array(1 => 'O negativo',2=> 'O positivo',3=> 'A negativo',4=> 'A positivo',5=> 'B negativo',6=> 'B positivo',7=> 'AB negativo',8=> 'AB positivo');
    const CATALOGO_PARENTESCOS = array(1 => 'Padre/Madre',2=> 'Esposo(a)',3=> 'Hijo(a)',4=> 'Concubino(a)',5=> 'Hermano(a)',6=> 'Abuelo(a)',7=> 'Tio(a)',8=> 'Suegro(a)',9=> 'Primo(a)',10=> 'Cuñado(a)');
    const CATALOGO_TIPOS_INTERPRETACION = array('C' => 'Combinación', 'D' => 'Dominio', 'P' => 'Porcentaje');
    const CATALOGO_NIVELES_PUESTO = array('AD' => 'Alta Dirección', 'DI' => 'Dirección', 'AD' => 'Alta gerencia', 'GE' => 'Gerencia', 'GJ' => 'Gerencia Junior', 'JE' => 'Alta gerencia', 'AD' => 'Jefatura', 'SU' => 'Supervisión');
    const CATALOGO_NIVELES_IMPORTANCIA= array('IM' => 'Imprescindible ', 'MI' => 'Muy Importante', 'IT' => 'Importante', 'LI' => 'Ligeramente importante', 'BI' => 'No Importante');
    
    //????
    const EMAIL_ALTA_USUARIO = 1;
    const EMAIL_ALTA_EMPRESA = 2;
       
    const COLUMNAS_EXCEL   = 45;
    const SUCCESS  = 0;
    const ERROR_UPLOAD = 1;
    const ERROR_WEIGHT = 2;
    const ERROR_TYPE   = 3;
    const TITLE        = "P-SeDiT";
	const INTENTOS     = 5;
	const MININTENTOS  = 15;  //MINUTOS;
	const SEGINTENTOS  = 900;  //SEGUNDOS;

   
    public static function getPaginator()
    {
        return self::PAGINATOR;
    }

    public static function getActivo()
    {
        return self::ACTIVO;
    }
    public static function getNoActivo()
    {
        return self::NOACTIVO;
    }

    public static function getPath(){
        return self::PATH;
    }

    public static function getMaxUpload(){
        return self::MAX_UPLOAD;
    }
    public static function getEstatusNueva()
    {
    	return self::ESTATUS_NUEVA;
    }
    public static function getEstatusLlena()
    {
    	return self::ESTATUS_LLENA;
    }
    public static function getTipoAnonima()
    {
    	return self::TIPO_ANONIMA;
    }
    public static function getTipoPregenerada()
    {
    	return self::TIPO_PREGENERADA;
    }
    public static function getTipoBajoDemanda()
    {
    	return self::TIPO_BAJO_DEMANDA;
    }

}
