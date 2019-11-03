<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use sistema\Repositories\UsuarioRepository;
use sistema\Policies\Constantes;

/**
 * 
 * @author Miguel Molina
 * Clase controladora para cargar los datos del layout principal admin, llena el menu y los datos generales de la sesion
 */
class MenuController extends Controller
{
	private $repository;
    public function __construct(UsuarioRepository $repository)
    {
    	$this->repository = $repository;
    }
    
    /**
     * Se encarga de generar el menu principal
     * @param Request $request
     */
    public function index(Request $request)
    {
    	$items = Session::get('items');
        return view('layouts/home', ["items"=>$items]);
    }
    
    
    /**
     * Envia el menu generado al loguerase el usuario
     */
    public function getMenu()
    {
    	$items = Session::get('items');
    	return $items;
    }
    
    /**
     * Regresa el nombre del usuario logueado
     */
    public function getNombreUsuario()
    {
    	$userName = Session::get('userName');
    	return $userName;
    }
    
    /**
     * Regresa los modulos a los que tiene acceso el usuario
     */
    public function getModulos(){
    	$modulos = Session::get('modulos');
    	return $modulos;
    }
    
    public function getPerfil(){
    	$rol = Session::get('roleName');
    	return $rol;
    }
    
    public function getEmpresa(){
    	return Constantes::TITLE;
    }
    
    public function getIngreso(){
    	$ingreso = substr(Session::get('userIngreso'),0,10);
    	return "Fecha de Ingreso: ".substr($ingreso,8,2).'-'.substr($ingreso,5,2).'-'.substr($ingreso,0,4);    	 
    }
    
    public function getIdUser(){
    	$idUser = Session::get('idUser');
    	return $idUser;
    }

    public function getIdEmpresa(){
    	return Session::get('userEnterprise');
    }
    
    public function getNmEmpresa(){
    	return Session::get('nmEmpresa');
    }
    
    public function getPathWeb(){
    	return Session::get('path_web');
    }
    
    public function getPathSis(){
    	return Session::get('path_sis');
    }
    
    public function getLogotipo(){
    	return  Session::get('logotipo');
    }

    public function setLogotipo($logotipo){
    	Session::put('logotipo', $logotipo);
    }

    public function getIconos(){
    	return array('glyphicon glyphicon-play-circle','glyphicon glyphicon-list-alt','','','','','glyphicon glyphicon-tasks','','','','glyphicon glyphicon-book','','');
    }
    public function getFechaAcceso(){
    	return  Session::get('fechaAcceso');
    }
    
    public function setFechaAcceso($fechaAcceso){
    	Session::put('fechaAcceso', $fechaAcceso);
    }

    public function getMenuCompleto(){
        return Session::get('menu');
    }
    
 	public function getIdentificadorUsuario(){
        return Session::get('user');
    }
}