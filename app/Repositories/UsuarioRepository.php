<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 14/03/2018
 * Time: 11:47 AM
 */
namespace sistema\Repositories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use sistema\Http\Requests\UsuarioFormRequest;
use sistema\Models\User;
use sistema\Models\UsersModulo;
use sistema\Models\UsersModuloPermiso;
use sistema\Policies\Constantes;
use sistema\Repositories\Eloquent\Repository;


/**
 * Clase de servicio de acceso a datos de perfiles
 * @author Miguel Molina 05/04/2017
 *
 */
class UsuarioRepository extends Repository {

	//nombre del campo que se registrara en la bitacora
	public static $campoBase = 'name';
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'sistema\Models\User';
    }


    function modulos($idPerfil){
        $datos = DB::table('perfil_modulo')
            ->join('modulo','perfil_modulo.idmodulo','=','modulo.idmodulo')
            ->where('perfil_modulo.idperfil','=',$idPerfil)->whereNull('modulo.parent')
            ->select('perfil_modulo.idmodulo','modulo.nombre','modulo.parent')
            ->get();
        return $datos;
    }

    function modulosHijos($idPerfil){
        return DB::table('perfil_modulo')
            ->join('modulo','perfil_modulo.idmodulo','=','modulo.idmodulo')
            ->where('perfil_modulo.idperfil','=',$idPerfil)->whereNotNull('modulo.parent')
            ->select('perfil_modulo.idmodulo','modulo.nombre','modulo.parent')
            ->get();
    }


    function modulosPerfilAll($idPerfil){
        return DB::table('perfil_modulo')
            ->join('modulo','perfil_modulo.idmodulo','=','modulo.idmodulo')
            ->where('perfil_modulo.idperfil','=',$idPerfil)
            ->select('perfil_modulo.idmodulo', 'modulo.nombre', 'modulo.parent', 'perfil_modulo.idperfil_modulo')
            ->get();
    }

    function modulosPerfilAllCompleto($idPerfil){
        return DB::table('perfil_modulo')
            ->join('modulo','perfil_modulo.idmodulo','=','modulo.idmodulo')
            ->where('perfil_modulo.idperfil','=',$idPerfil)
            ->select('perfil_modulo.idmodulo', 'modulo.nombre', 'modulo.parent', 'perfil_modulo.idperfil_modulo')
            ->get();
    }

    function modulosUserAll($idUsuario){
        return DB::table('users_modulo')
            ->join('modulo','users_modulo.idmodulo','=','modulo.idmodulo')
            ->where('users_modulo.iduser','=',$idUsuario)
            ->select('users_modulo.idmodulo','modulo.nombre','modulo.parent','users_modulo.permitido')
            ->get();
    }

    function modulosPerfilAllEdit($idPerfil){
        return DB::table('perfil_modulo')
            ->join('modulo','perfil_modulo.idmodulo','=','modulo.idmodulo')
            ->where('perfil_modulo.idperfil','=',$idPerfil)
            ->select('perfil_modulo.idmodulo', 'modulo.nombre', 'modulo.parent', 'perfil_modulo.idperfil_modulo')
            ->get();
    }

    function modulosUserAllEdit($idUsuario){
        return DB::table('users_modulo')
            ->join('modulo','users_modulo.idmodulo','=','modulo.idmodulo')
            ->where('users_modulo.iduser','=',$idUsuario)
            ->select('users_modulo.idmodulo','modulo.nombre','modulo.parent','users_modulo.iduser_modulo')
            ->get();
    }


    function modulosUserPermitidos($idUsuario){
        return DB::table('users_modulo')
            ->join('modulo','users_modulo.idmodulo','=','modulo.idmodulo')
            ->where('users_modulo.iduser','=',$idUsuario)
            ->where('users_modulo.permitido','=',true)
            ->select('users_modulo.idmodulo','users_modulo.permitido','modulo.parent')
            ->get();
    }

    function modulosUserNoPermitidos($idUsuario){
        return DB::table('users_modulo')
            ->join('modulo','users_modulo.idmodulo','=','modulo.idmodulo')
            ->where('users_modulo.iduser','=',$idUsuario)
            ->where('users_modulo.permitido','=',false)
            ->select('users_modulo.idmodulo','users_modulo.permitido','modulo.parent')
            ->get();
    }
    /**
     * Metodo que guarda el nuevo usuario
     * @param UsuarioFormRequest $usuario
     * @throws \Exception
     */
    function saveUsuario(UsuarioFormRequest $request,$password) {
        if ($request) {
            DB::beginTransaction ();
            try{
                $seleccionados         = $request->get('submodulosSeleccionados');
                $noseleccionados 	   = $request->get('submodulosNoSeleccionados');
                $permisosseleccionados = $request->get('submodulosSeleccionadosPermisos');
                $usuario = new User ();
                $usuario->idperfil = $request->get ( 'idperfil' );
                $usuario->name 	   = trim($request->get ( 'name' ));
                $usuario->email    = trim($request->get ( 'email' ));
                
                
                
                if((int) $request->get ('idEmpresaBusca') > 0){
                    $usuario->idempresa = $request->get ('idEmpresaBusca');
                }
                $usuario->password = $password;
                $usuario->dummy    = true;
                $usuario->activo    = true;
                $usuario->save ();
                $permisosPerfil = $this->getAllPerfilPermisos($request->get('idperfil'));
                $permisosPerfil = $this->arrayUnique($permisosPerfil);
                if(	trim($seleccionados)!= '' || trim($noseleccionados)!= '' ){
                    $this->insertaModulosUsuarios($usuario,$seleccionados,$noseleccionados,$permisosseleccionados,$permisosPerfil);
                }
                //Bitacora
                $controller = $this->obtenerNombreController($request);
                $moduloId   = $this->obtenModuloId($controller);
                $perfilesPermisos = $this->comparaPerfilPermisos(Constantes::ACCION_ALTA,$usuario,$usuario);
                $this->insertaBitacora(Constantes::ACCION_ALTA, null, $usuario->getAttributes(), $usuario->id,$controller,$moduloId,Session::get('idUser'),$perfilesPermisos,self::$campoBase);
                DB::commit ();
                return $usuario;
            }
            catch ( \Exception $e ) {
                DB::rollback ();
                throw new \Exception('Error al crear al Usuario '.$e);
            }
        }
    }

    /**
     * Metodo que sirve para activar al usuario
     * @param  $id
     * @throws \Exception
     */
    function activaUsuario($id){
        if((int) $id > 0){
            DB::beginTransaction ();
            try{
                $usuario = User::findOrFail ( $id );
                $usuarioA =clone $usuario;
                $usuario->activo = true;
                $usuario->dummy = true;
                $usuario->update();
                //Bitacora
                $moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_USUARIO);
                $this->insertaBitacora(Constantes::ACCION_ACTIVAR, $usuarioA->getAttributes(), $usuario->getAttributes(), $usuario->id,Constantes::CONTROLLER_USUARIO,$moduloId,Session::get('idUser'),array(),self::$campoBase);
                DB::commit ();
            }
            catch ( \Exception $e ) {
                DB::rollback ();
                throw new \Exception('Error al restablecer al Usuario con id:'.$id.' -> ' . $e);
            }
        }
    }

    /**
     * Metodo que sirve para activar al usuario
     * @param  $id
     * @throws \Exception
     */
    function desactivaUsuario($id){
        if((int) $id > 0){
            DB::beginTransaction ();
            try{
                $usuario = User::findOrFail ( $id );
                $usuarioA =clone $usuario;
                $usuario->activo = false;
                $usuario->update();
                //Bitacora
                $moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_USUARIO);
                 $this->insertaBitacora(Constantes::ACCION_ELIMINAR, $usuarioA->getAttributes(), $usuario->getAttributes(), $usuario->id,Constantes::CONTROLLER_USUARIO,$moduloId,Session::get('idUser'),array(),self::$campoBase);
                DB::commit ();
            }
            catch ( \Exception $e ) {
                DB::rollback ();
                throw new \Exception('Error al restablecer al Usuario con id:'.$id.' -> ' . $e);
            }
        }
    }
    /**
     * Metodo para resetear la contraseña del usuario
     * @param int $id
     * @param string $password
     * @throws \Exception
     */
    function resetContrasenaUsuario($id,$password){
        if((int) $id > 0){
            DB::beginTransaction ();
            try{
                $usuario = User::findOrFail ( $id );
                $usuarioA = clone $usuario;
                $usuario->password = $password;
                $usuario->dummy    = Constantes::ACTIVO;
                $usuario->update();
                //Bitacora
                $moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_USUARIO);
                $this->insertaBitacora(Constantes::ACCION_RESETEO, $usuarioA->getAttributes(), $usuario->getAttributes(), $usuario->id,Constantes::CONTROLLER_USUARIO,$moduloId,Session::get('idUser'),array(),self::$campoBase);
                DB::commit ();
            }
            catch ( \Exception $e ) {
                DB::rollback ();
                throw new \Exception('Error al resetear la contraseña del Usuario con id:'.$id.' -> ' . $e);
            }
        }
    }
    /**
     * Metodo que actualiza la información del usuario
     * {@inheritDoc}
     * @see \sistema\Repositories\Eloquent\Repository::update()
     */
    function updateUsuario(UsuarioFormRequest $request) {
        if ($request) {
            DB::beginTransaction ();
            try{
                $id = (int) $request->get('id');
                $seleccionados   =  $request->get('submodulosSeleccionados');
                $noseleccionados =  $request->get('submodulosNoSeleccionados');
                $permisosseleccionados = $request->get('submodulosSeleccionadosPermisos');
                $usuario  = User::findOrFail ( $id );
                $usuarioA = clone $usuario;
                $usuario->idperfil = $request->get ( 'idperfil' );
                $usuario->name 	   = trim($request->get ( 'name' ));
                $usuario->email    = trim($request->get ( 'email' ));
                $usuario->update();
                if(	trim($seleccionados)!= '' || trim($noseleccionados)!= '' ){
                    $permisosPerfil = $this->getAllPerfilPermisos($request->get('idperfil'));
                    $permisosPerfil = $this->arrayUnique($permisosPerfil);
                    $this->eliminaModulosUsuarios($id);
                    $this->insertaModulosUsuarios($usuario,$seleccionados,$noseleccionados,$permisosseleccionados,$permisosPerfil);
                }
                //Bitacora
                $controller = $this->obtenerNombreController($request);
                $moduloId   = $this->obtenModuloId($controller);
                $perfilesPermisos = $this->comparaPerfilPermisos(Constantes::ACCION_ACTUALIZAR,$usuario,$usuarioA);
                $this->insertaBitacora(Constantes::ACCION_ACTUALIZAR, $usuarioA->getAttributes(), $usuario->getAttributes(), $usuario->id,$controller,$moduloId,Session::get('idUser'),$perfilesPermisos,self::$campoBase);
                DB::commit ();
            }
            catch ( \Exception $e ) {
                DB::rollback ();
                throw new \Exception('Error al guardar Perfil: ' . $e);
            }
        }
    }


    private function comparaPerfilPermisos($tipo,$usuario,$usuarioA){
        $array = array();
        if($tipo == Constantes::ACCION_ALTA){
            $array['perfilModulosPermisosAnt']  = '';
            $array['usuarioModulosPermisosAnt'] = '';
            $array['perfilModulosPermisosDes']  = $this->exportaCadena($this->getPerfilModulosPermisosUser($usuario->idperfil),1);
            $array['usuarioModulosPermisosDes'] = $this->exportaCadena($this->getUserModulosPermisosUser($usuario->id),2);

        }else{
            $array['perfilModulosPermisosAnt']  = $this->exportaCadena($this->getPerfilModulosPermisosUser($usuarioA->idperfil),1);
            $array['usuarioModulosPermisosAnt'] = $this->exportaCadena($this->getUserModulosPermisosUser($usuarioA->id),2);
            $array['perfilModulosPermisosDes']  = $this->exportaCadena($this->getPerfilModulosPermisosUser($usuario->idperfil),1);
            $array['usuarioModulosPermisosDes'] = $this->exportaCadena($this->getUserModulosPermisosUser($usuario->id),2);
        }
        return $array;
    }

    private function exportaCadena($objetos,$opcion){
        $tmp = array();
        $cadena = "";
        if(count($objetos) > 0){
            foreach($objetos as $objeto){
                if( (int) $objeto->idpermiso > 0){
                    if($opcion == 1){
                        $tmp[] = "{Perfil : ".$objeto->idperfil."  Modulo ".$objeto->idmodulo." : Permiso ".$objeto->idpermiso."}";
                    }
                    if($opcion == 2){
                        if($objeto->permitido == 1)
                            $tmp[] = "{User  ".$objeto->iduser."  Modulo ".$objeto->idmodulo." : Permiso".$objeto->idpermiso." permitido }";
                        else
                            $tmp[] = "{User  ".$objeto->iduser."  Modulo ".$objeto->idmodulo." : Permiso".$objeto->idpermiso." No permitido }";
                    }
                }
            }
            $cadena = implode(' ',$tmp);
        }
        return $cadena;
    }

    /**
     * Metodo que sirve para eliminar un usuario y modulos asignados
     * @param int $id
     */
    function eliminaUsuario($id){
        if((int) $id > 0){
            DB::beginTransaction ();
            try{
                $this->eliminaModulosUsuarios($id);
                $usuario = User::findOrFail ( $id );
                $usuarioA= clone $usuario;
                $usuario->activo = 0;
                $usuario->update();
                //Bitacora
                $moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_USUARIO);
                $this->insertaBitacora(Constantes::ACCION_ELIMINAR, $usuarioA->getAttributes(), $usuario->getAttributes(), $usuario->id,Constantes::CONTROLLER_USUARIO,$moduloId,Session::get('idUser'),array(),self::$campoBase);
                DB::commit ();
            }
            catch ( \Exception $e ) {
                DB::rollback ();
                throw new \Exception('Error al guardar Perfil: ' . $e);
                die("error:  ".$e->getMessage());
            }
        }
    }

    function resetPasswordUsuario($email,$password) {
        if ($email) {
            DB::beginTransaction ();
            try{
                $results =  $this->obtenidUsuario($email);
                if(count($results) > 0){
                    foreach($results as $result){
                        if((int) $result->id > 0){
                            $usuario = User::findOrFail ( $result->id );
                            $usuarioA= clone $usuario;
                            $usuario->password = $password;
                            $usuario->activo = Constantes::ACTIVO;
                            $usuario->dummy = null;
                            $usuario->update();
                            //Bitacora
                            $moduloId   = $this->obtenModuloId(Constantes::CONTROLLER_USUARIO);
                            $this->insertaBitacora(Constantes::ACCION_ACTPASSWORD, $usuarioA->getAttributes(), $usuario->getAttributes(), $usuario->id,Constantes::CONTROLLER_USUARIO,$moduloId,Session::get('idUser'),array(),self::$campoBase);
                            DB::commit ();
                        }
                    }
                }
            }
            catch ( \Exception $e ) {
                DB::rollback ();
                throw new \Exception('Error al actualizar el password Perfil: ' . $e);
            }
        }
        //die("final");
    }

    /**
     * Metodo que sirve para regresar el objeto de un usuario
     * @param int $id
     */
    function findModulosUsuario($id){
        return DB::table('users_modulo')
            ->where('users_modulo.iduser',$id)
            ->get();
    }


    function findModulosUsuariosPermisos($arrayUsersModuloPermiso){
        return DB::table('users_modulo_permiso')
            ->whereIn('users_modulo_permiso.iduser_modulo',$arrayUsersModuloPermiso)
            ->get();

    }

    function moduloSeleccionado($permisosseleccionados){
        $registros = explode('|',$permisosseleccionados);
        if(count($registros) > 0){
            foreach($registros as $subcadena){
                $modulos = explode('*',$subcadena);
                $arrayPermisos[$modulos[0]] = explode(',',$modulos[1]);
            }
        }
        return $arrayPermisos;
    }


    /**
     * Metodo que inserta la relacion usuario - modulo
     * @param int $id
     * @param string $seleccionados
     */

    function generaInsertBiz($inserts){
        $data = $arrayModulos = $arrayIds =array();
        $data = $arrayModulos = $arrayIds = array();
        $idUserModulo = 0;
        if(count($inserts)>0){
            foreach($inserts as $key => $permitido){
                $idUserModulo = 0;
                $data = explode('-',$key);
                $id = $data[0];
                $idModulo = $data[1];
                //insertamos users_modulos
                if(!in_array($idModulo,$arrayModulos)){
                    $idUserModulo = $this->insertaModulo($id, $idModulo, $permitido);
                    $arrayModulos[]= $idModulo;
                }
            }
        }
    }

    function generaInsert($inserts){
        $data = $arrayModulos = $arrayIds = array();
        $idUserModulo = 0;
        if(count($inserts)>0){
            foreach($inserts as $key => $permitido){
                $idUserModulo = 0;
                $data = explode('-',$key);
                $id = $data[0];
                $idModulo = $data[1];
                $idPermiso = $data[2];
                //insertamos users_modulos
                if(!in_array($idModulo,$arrayModulos)){
                    $idUserModulo = $this->insertaModulo($id, $idModulo, $permitido);
                    $arrayModulos[]= $idModulo;
                    $arrayIds[$idModulo] = $idUserModulo;
                }else{
                    $idUserModulo = $arrayIds[$idModulo];
                }
                //insertamos users_modulos_permisos
                if($idUserModulo > 0){
                    $this->insertaModuloPermiso($idUserModulo,$idPermiso,$permitido);
                }
            }
        }
    }

	function obtenModulosPermisosPerfil($usuario){
		$modulosPermisos = DB::table('perfil_modulo')->distinct()
							->leftJoin('perfil_modulo_permiso','perfil_modulo_permiso.idperfil_modulo', '=' ,'perfil_modulo.idperfil_modulo')
							->select('perfil_modulo.idmodulo')
							->where('perfil_modulo.idperfil', '=', $usuario->idperfil)
							->orderBy('perfil_modulo.idmodulo')
							->get()->toArray();
		$arrayModPerf = array();
		foreach($modulosPermisos as $moduloP){
			$arrayModPerf[] = $moduloP->idmodulo;
		}
		return $arrayModPerf;
	}
    
	    
	function obtieneIdPadre($idModulo){
		return  DB::table('modulo')->select('parent')->where('idmodulo', '=' ,$idModulo)->get()->first();
	}
	
	function getModulosHijos($idModPadre,$idModHijo){
		$arrayHijos = array();
		$hijos = DB::table('modulo')->where('parent', '=' ,$idModPadre);
		
		if((int) $idModHijo > 0){
			$hijos = $hijos->where('idmodulo', '!=' ,$idModHijo);
		}
		$hijos = $hijos->get();
		if(count($hijos) > 0){
			foreach($hijos as $hijo){
				$arrayHijos[] = $hijo->idmodulo;
			}
		}
		return $arrayHijos;
	}
	function existeEnSeleccion($modHijo,$arraytemporal){
		$y = implode(',' , $arraytemporal);
		$exito = (int) in_array($modHijo,$arraytemporal,true);
		return $exito;
	}
	
    /*******************************/
    function insertaModulosUsuarios(User $usuario,$seleccionados,$noseleccionados,$permisosseleccionados,$permisosPerfil){
        $arrayUnicos = $arrayUnicosPermisos = $arrayIdModulos =$arrayModulosConPermisos = $arrayPermisos = array();
        $id       = $usuario->id;
        $idPerfil = $usuario->idperfil;
        $agregaModulos = array();
        DB::beginTransaction ();
        try{
        	$padres = $this->getModuloParents();
        	$catPadres   = $this->catalogoModulos($padres);

        	/** Permisos seleccionados por el usuario  ***/
            $arrayPermisos = $this->moduloSeleccionado($permisosseleccionados);       
            $arraytemporal = array_keys($arrayPermisos);
            
            /** A�adimos modulos padres al arreglo de permisosseleccionados **/            
            $arrayTSelecc  = explode(',',$seleccionados);
            foreach($arrayTSelecc as  $idMod){
            	if(!array_key_exists($idMod, $arrayPermisos) && array_key_exists($idMod, $catPadres)){
            		$arrayPermisos[$idMod] = array(1);
            	}
            }
            $arrayUnicosModulosSeleccionado = array_keys($arrayPermisos);
            $arrayUnicosModulosPerfil       = array_keys($permisosPerfil);
            
            //Recorrer seleccionado y comparar con perfil
            if(count($arrayPermisos)>0){
                $insertar = array();
                foreach($arrayPermisos as $idModulo => $permisosModulo){//revisa si el modulo existe en los permisos del perfil
                    if(!in_array($idModulo,$arrayUnicosModulosPerfil)){
                        if(!in_array($idModulo,$agregaModulos)){
                            $idUserModulo = $this->insertaModulo($id, $idModulo, true);
                            $agregaModulos[] = $idModulo;
                        }
                        if(count($permisosModulo)>0){
                            foreach($permisosModulo  as $idPermisoSeleccionado){
                                $this->insertaModuloPermiso($idUserModulo, $idPermisoSeleccionado, true);
                            }
                        }
                    }else{ //El modulo existe en el perfil por lo que revisamos los permisos
                        $permisosModuloPerfil = $permisosPerfil[$idModulo];
                        if(count($permisosModulo)>0){
                            foreach($permisosModulo  as $idPermisoSeleccionado){
                                if(!in_array($idPermisoSeleccionado,$permisosModuloPerfil)){
                                    $insertar[] = $idPermisoSeleccionado;
                                }
                            }
                            if(count($insertar) > 0){
                                if(!in_array($idModulo,$agregaModulos)){
                                    $idUserModulo = $this->insertaModulo($id, $idModulo, true);
                                    $agregaModulos[] = $idModulo;
                                }
                                foreach($insertar as $idPermisoNuevo){
                                    $this->insertaModuloPermiso($idUserModulo, $idPermisoNuevo, true);
                                }
                            }
                        }
                    }
                }
            }
            if(count($permisosPerfil)>0){
                $insertar = $noInsertar = array();
                foreach($permisosPerfil as $idModulo => $permisosModulo){ //revisa si el modulo existe en los permisos del perfil
                    $insertar = array();
                    if(in_array($idModulo,$arrayUnicosModulosSeleccionado)){
                        $permisosModulosSel = $arrayPermisos[$idModulo];
                        if(count($permisosModulo)>0){
                            foreach($permisosModulo  as $idPermisoPerfil){
                                if(!in_array($idPermisoPerfil,$permisosModulosSel)){
                                    $insertar[] = $idPermisoPerfil;
                                }else{
                                    $noInsertar[] = $idPermisoPerfil;
                                }
                            }
                            if(count($insertar) > 0){
                                if(count($noInsertar) == 0){
                                    if(!in_array($idModulo,$agregaModulos)){
                                        $idUserModulo = $this->insertaModulo($id, $idModulo, false);
                                    }
                                }
                                else{
                                    if(!in_array($idModulo,$agregaModulos)){
                                        $idUserModulo = $this->insertaModulo($id, $idModulo, true);
                                    }
                                }
                                foreach($insertar as $idPermisoNuevo){
                                	$this->insertaModuloPermiso($idUserModulo, $idPermisoNuevo, false);
                                }
                            }
                        }
                    }
                }
            }
            $array = explode(',',$noseleccionados);
            if(count($array) > 0){
                foreach ($array as $idModulo){
                    if(!in_array($idModulo,$arrayUnicos)){
                        if(in_array($idModulo,$arrayUnicosModulosPerfil) && !in_array($idModulo,$arrayUnicosModulosSeleccionado)){
                            if(!in_array($idModulo,$agregaModulos)){
                                $idUserModulo = $this->insertaModulo($id, $idModulo, false);
                                $arrayUnicos[] = $idModulo;
                            }
                        }
                    }
                }
            }
            DB::commit ();
        }
        catch(\Exception $e){
        	DB::rollback ();
        }
    }


    /**
     * Metodo que sirve para eliminar los modulos asignados al usuario
     * @param int $id
     * @param String $seleccionados
     */
    function eliminaModulosUsuarios($id){
        $modulos = $this->findModulosUsuario($id);
        $arrayIdUserModulo = array();
        if(count($modulos) > 0){
            try{
                foreach($modulos as $modulo){
                    $arrayIdUserModulo[] = $modulo->iduser_modulo;
                }
                $this->eliminaUsersModulosPermisos($arrayIdUserModulo);

                foreach($arrayIdUserModulo as $idUserModulo){
                    UsersModulo::destroy($idUserModulo);
                }
            }
            catch ( \Exception $e ) {
                throw new \Exception('Error al guardar Perfil: ' . $e);
            }
        }
    }

    function eliminaUsersModulosPermisos($arrayIds){
        try{
            if(count($arrayIds) > 0){
                $results = DB::table('users_modulo_permiso')->select('iduser_modulo_permiso')
                    ->whereIn('users_modulo_permiso.iduser_modulo', $arrayIds)
                    ->get();
                if(count($results) > 0){
                    foreach($results as $result){
                        UsersModuloPermiso::destroy($result->iduser_modulo_permiso);
                    }
                }
            }
        }
        catch(\Exception $e){
            throw new \Exception('Error al guardar Perfil: ' . $e);
        }
    }

    /**
     * Metodo qye regresa el objeto user
     * @param int $id
     */
    function getUsuario($id){
        return  User::findOrFail ( $id );
    }


    function getAllModuloPermisosIdPerfilModulo($idperfil_modulo){
        return DB::table('perfil_modulo_permiso')
            ->join('permiso','perfil_modulo_permiso.idpermiso','=','permiso.idpermiso')
            ->where('perfil_modulo_permiso.idperfil_modulo',$idperfil_modulo)
            ->select('perfil_modulo_permiso.idpermiso','permiso.nombre')
            ->get();
    }

    /**
     * Metodo que consulta los permisos asignados al modulo
     * @param int $idModulo
     */
    function getAllModuloPermisosId($idModulo){
        $result = DB::table('modulo_permiso')
            ->leftjoin('permiso', 'modulo_permiso.idpermiso', '=', 'permiso.idpermiso')
            ->select('modulo_permiso.idpermiso','permiso.nombre')
            ->where('modulo_permiso.idmodulo',$idModulo)
            ->orderBy('modulo_permiso.idpermiso')
            ->get();
        return $result;
    }

    function getAllModuloPermisosPerfil($idModulo){
        $result = DB::table('perfil_modulo_permiso')
            ->leftjoin('permiso', 'modulo_permiso.idpermiso', '=', 'permiso.idpermiso')
            ->select('modulo_permiso.idpermiso','permiso.nombre')
            ->where('modulo_permiso.idmodulo',$idModulo)
            ->orderBy('modulo_permiso.idpermiso')
            ->get();
        return $result;

    }

    function getAllPerfilPermisosModulo($idPerfil){
        return  DB::table('perfil_modulo')
            ->join('perfil_modulo_permiso', 'perfil_modulo_permiso.idperfil_modulo', '=', 'perfil_modulo.idperfil_modulo')
            ->select('perfil_modulo.idmodulo','perfil_modulo_permiso.idpermiso')
            ->where('perfil_modulo.idperfil',$idPerfil)
            ->orderBy('perfil_modulo.idmodulo', 'asc','perfil_modulo_permiso.idpermiso')
            ->get();
    }

    function getAllPermisosModulo($idUsuario){
        return  DB::table('users_modulo')
            ->join('users_modulo_permiso', 'users_modulo_permiso.iduser_modulo', '=', 'users_modulo.iduser_modulo')
            ->select('users_modulo.idmodulo','users_modulo_permiso.idpermiso')
            ->where('users_modulo.iduser',$idUsuario)
            ->orderBy('users_modulo.idmodulo', 'asc','users_modulo_permiso.idpermiso','desc')
            ->get();

    }

    function getAllPermisosModulos(){
        return  DB::table('modulo')
            ->join('modulo_permiso', 'modulo_permiso.idmodulo', '=', 'modulo.idmodulo')
            ->join('permiso','permiso.idpermiso','=','modulo_permiso.idpermiso')
            ->select('modulo_permiso.idpermiso','permiso.nombre','modulo.idmodulo')
            ->orderBy('modulo.idmodulo', 'asc','modulo_permiso.idpermiso')
            ->get();

    }

    function getAllPerfilPermisos($idPerfil){
        return  DB::table('perfil_modulo')
            ->join('perfil_modulo_permiso', 'perfil_modulo_permiso.idperfil_modulo', '=', 'perfil_modulo.idperfil_modulo')
            ->select('perfil_modulo.idmodulo','perfil_modulo_permiso.idpermiso')
            ->where('perfil_modulo.idperfil',$idPerfil)
            ->orderBy('perfil_modulo.idperfil', 'asc','perfil_modulo_permiso.idpermiso')
            ->get();
    }

    function getAllUsuarioPermisos($idUsuario){
        return  DB::table('users_modulo')
            ->join('users_modulo_permiso', 'users_modulo_permiso.iduser_modulo', '=', 'users_modulo.iduser_modulo')
            ->select('users_modulo.idmodulo','users_modulo_permiso.idpermiso')
            ->where('users_modulo.iduser',$idUsuario)
            ->where('users_modulo_permiso.permitido',true)
            ->orderBy('users_modulo.idmodulo', 'asc','users_modulo_permiso.idpermiso')
            ->get();
    }

    function getAllUsuarioPermisosNoAsignados($idUsuario){
        return  DB::table('users_modulo')
            ->join('users_modulo_permiso', 'users_modulo_permiso.iduser_modulo', '=', 'users_modulo.iduser_modulo')
            ->select('users_modulo.idmodulo','users_modulo_permiso.idpermiso')
            ->where('users_modulo.iduser',$idUsuario)
            ->where('users_modulo_permiso.permitido',false)
            ->orderBy('users_modulo.idmodulo', 'asc','users_modulo_permiso.idpermiso')
            ->get();
    }

    function getIdUserModulo($idUsuario, $idModulo){
        return DB::table('user_modulo')
            ->where('iduser',$idUsuario)
            ->where('idmodulo',$idModulo)
            ->select('iduser_modulo')->get();

    }

    function obtenidUsuario($email){
        return DB::table('users')
            ->where('email','=',$email)
            ->select('id')->get();
    }

    function toArray($permisos){
        return array_map(function($item){return (array) $item;},$permisos);
    }

    function arrayUnique($permisos){
        $permisosPerfil= array();
        if(count($permisos) > 0){
            foreach($permisos as $permiso){
                $paso[$permiso->idmodulo][$permiso->idpermiso]= $permiso->idpermiso;
                $permisosPerfil[$permiso->idmodulo] = $paso[$permiso->idmodulo];
            }
        }
        return $permisosPerfil;
    }

    function getArrayModulos($modulos){

    }

    function arrayUniqueUserModulo($permisos){
        $permisosUsuario= array();
        if(count($permisos) > 0){
            foreach($permisos as $permiso){
                $paso[$permiso->idmodulo][$permiso->idpermiso]= $permiso->idpermiso;
                $permisosUsuario[$permiso->idmodulo] = $paso[$permiso->idmodulo];
            }
        }
        return $permisosUsuario;
    }

    private function insertaModulo($id,$idModulo,$boolean){
        $userModulo = new UsersModulo();
        $userModulo->iduser = $id;
        $userModulo->idmodulo = $idModulo;
        $userModulo->permitido = $boolean;
        $userModulo->save();
        return $userModulo->iduser_modulo;
    }
    private function insertaModuloPermiso($idUserModulo,$idPermiso,$boolean){
        $userModuloPermiso = new UsersModuloPermiso();
        $userModuloPermiso->iduser_modulo = $idUserModulo;
        $userModuloPermiso->idpermiso = $idPermiso;
        $userModuloPermiso->permitido = $boolean;
        $userModuloPermiso->save();
    }
    
    
    /**
     * Metodo para validar si el mail del usuario ya se encuentra registrado
     * @param String $mail
     * @param String $idusuario
     * @return boolean
     */
    function validaMail($mail, $idusuario){
    	$existe = false;
    	$results = DB::table('users')->where('email','=',$mail);
    	if($idusuario != null && $idusuario != '' && $idusuario != '0')
    		$results = $results->where('id','!=',$idusuario);
    
    		$results = $results->get();
    		if(count($results) > 0){
    			$existe = true;
    		}
    		return $existe;
    }
}