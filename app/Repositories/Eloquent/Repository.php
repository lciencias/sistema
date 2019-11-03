<?php
namespace sistema\Repositories\Eloquent;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Logger;
use sistema\Models\Archivo;
use sistema\Models\Bitacora;
use sistema\Models\Cliente;
use sistema\Policies\Constantes;
use sistema\Policies\RegistraBitacora;
use sistema\Repositories\Contracts\RepositoryInterface;
use function sistema\Repositories\Eloquent\Repository\limpiar;



/**
 * Class Repository
 * @package sistema\Repositories\Eloquent
 */
abstract class Repository implements RepositoryInterface {
	use  DispatchesJobs;
   /**
     * @var App
     */
    private $app;
    public $log;
 
    /**
     * @var
     */
    protected $model;
 
    public function __construct(App $app) {
    	
    	$path = public_path();
		Logger::configure ( $path.'/xml/config.xml' );
		$this->log = \Logger::getLogger ( 'main' );
    	
        $this->app = $app;
        $this->makeModel();
    }
 
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract function model();
 
    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*')) {
        return $this->model->get($columns);
    }
 
    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*')) {
        return $this->model->paginate($perPage, $columns);
    }
 
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {
        return $this->model->create($data);
    }
 
    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute="id") {
        return $this->model->where($attribute, '=', $id)->update($data);
    }
 
    /**
     * @param $id
     * @return mixed
     */
    public function delete($id) {
        return $this->model->destroy($id);
    }
 
    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*')) {
        return $this->model->find($id, $columns);
    }
 
    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*')) {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }
    
    
    public function findLikeBy($attribute, $value, $columns = array('*')) {
        return $this->model->where($attribute, 'LIKE', '%' .$value. '%')->paginate (3);
    }
    
    public function findByColumn($array,$opciones){
        $result = $this->model;
        if(count($array) > 0){
            foreach($array as $tmp){
                if(trim($tmp[1]) == 'IN'){
                    $result = $result->whereIn($tmp[0],$tmp[2]);
                }else{
                    $result = $result->where($tmp[0], $tmp[1], $tmp[2]);
                }
            }
        }
        $result = $result->orderBy($opciones['campo'],$opciones['orden'])->paginate ($opciones['noregs']);
        return $result;      
    }
    
    public function findByCount($array){
        $result = $this->model;
        if(count($array) > 0){
            foreach($array as $tmp){
                if(trim($tmp[1]) == 'IN'){
                    $result = $result->whereIn($tmp[0],$tmp[2]);
                }else{
                    $result = $result->where($tmp[0], $tmp[1], $tmp[2]);
                }
            }
        }
        $result = $result->get()->count();        
        return $result;
    
    }
    
    
    public function findByArray($array) {
        $result = $this->model;
        if(count($array) > 0){
            foreach($array as $tmp){
                if(trim($tmp[1]) == 'IN'){
                    $result = $result->whereIn($tmp[0],$tmp[2]);
                }else{
                    $result = $result->where($tmp[0], $tmp[1], $tmp[2]);
                }
            }
        }
        $result = $result->paginate (Constantes::getPaginator());
        return $result;
    }
 
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function makeModel() {
        $model = $this->app->make($this->model());
    
        if (!$model instanceof Model)
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
//             $this->log->debug($model);
        return $this->model = $model->newQuery();
    }
    
    
    public function getArray($object){
        //return array_map(function ($value) {return (array)$value;}, $object);
        return json_decode(json_encode($object),TRUE);
    }

    public function toCombo($array,$key,$value){
        $return = array();
        if(count($array) > 0){
            foreach($array as $tmp){
                $return[$tmp[$key]] = $tmp[$value];
            }
        }
        return $return;
    }

    public function toComboMultiple($array,$key,$keyB,$value){
        $return = array();
        if(count($array) > 0){
            foreach($array as $tmp){
                $clave = $tmp[$key];
                $return[$clave][] = $tmp[$keyB];
            }
        }
        return $return;
    }
    
    public function toComboMultipleNombre($array,$key,$keyB,$value){
        $return = array();
        if(count($array) > 0){
            foreach($array as $tmp){
                $clave = $tmp[$key];
                $return[$clave][] = $tmp[$value];
            }
        }
        return $return;
    }
    /**
     * Metodo que consulta en base de datos todos los modulos
     */
    function getAllModulos(){
        return DB::table ( 'modulo' )->get();
    }

    
    function getModuloParentsSinEmpresa(){
        //return DB::table ( 'modulo' )->whereNull('parent')->whereNull('admin')->get();
        return DB::table ( 'modulo' )->where('parent','=',0)->where('activo','=',1)->whereNull('admin')->get();
    }
    /**
     *  Metodo que consulta en base de datos todos los modulos padres
     */
    function getModuloParents(){
        //return DB::table ( 'modulo' )->whereNull('parent')->get();
        return DB::table ( 'modulo' )->where('parent','=',0)->where('activo','=',1)->get();
    }
    
    function getIdsModuloParents(){
        //return DB::table ( 'modulo' )->whereNull('parent')->select('idmodulo')->get();
        return DB::table ( 'modulo' )->where('parent','=',0)->select('idmodulo')->get();
    }
    
    
    
    function getModuloChildrenSimEmpresa(){
        //return DB::table ( 'modulo' )->whereNotNull('parent')->whereNull('admin')->orderBy('nombre')->get();
        return DB::table ( 'modulo' )->where('parent','>',0)->whereNull('admin')->orderBy('nombre')->get();
    }
    /**
     *  Metodo que consulta en base de datos todos los modulos hijos
     */
    function getModuloChildren(){
        //return DB::table ( 'modulo' )->whereNotNull('parent')->orderBy('nombre')->get();
        return DB::table ( 'modulo' )->where('parent','>',0)->orderBy('nombre')->get();
    }
    
    function getAllEjecutivos(){
    	return DB::table( 'agente' )->where('tipo', '=', Constantes::ACTIVO)->orderBy('nombre')->get();
    }
    function getAllPromotor(){
    	return DB::table( 'agente' )->where('tipo', '=', Constantes::PERFIL_ADMIN_EMPRESA)->orderBy('nombre')->get();
    }
    /**
     *  Metodo que consulta los ids de  todos los Usuarios
     */
    function getIdsUsuarios($filtros){
        $result = DB::table ( 'users' );
        if(count($filtros) > 0){
            foreach($filtros as $tmp){
                $result = $result->where($tmp[0], $tmp[1], $tmp[2]);
            }
        }
        $result = $result->select('id')->orderBy('name')->get();
        return $result;
         
    }
    
    function getMovimientos(){
        return array(1 => 'Alta',2 => 'Actualizar',3 => 'Eliminar',4 => 'Activar',5 => 'Resetear',6 => 'Actualizar Contraseña', 7 => 'Asignar póliza', 
        		8 => 'Asignar renovación', 9 => 'Renovación de contado', 10 => 'Renovación a crédito', 11 => 'Pagar ahora', 12 => 'Reprogramar pago',
        		13 => 'Autorizar póliza', 14 => 'Renovación externa',  15 => 'Cancelación de póliza', 16 => 'Autorizar cancelación de póliza'
        );        
    }
    /**
     *  Metodo que consulta en base de datos todos los Usuarios
     */
    function getAllUsuarios($filtros){
        $result = DB::table ( 'users' );
        if(count($filtros) > 0){
            foreach($filtros as $tmp){
                $result = $result->where($tmp[0], $tmp[1], $tmp[2]);
            }
        }
        $result = $result->orderBy('name')->get();
        return $result;      
    }
    
    /**
     *  Metodo que consulta en base de datos todas las empresas
     */
    function getAllEmpresas(){
        return DB::table ( 'empresa' )->orderBy('nombre')->get();
    }

    function getAllSucursales(){
    	return DB::table ( 'sucursal' )->orderBy('nombre')->get();
    }
    
    function getAllEmpresasArray(){
        $object = DB::table ( 'empresa' )->orderBy('nombre')->get()->toArray();
        return (array) $object;
    }


    /**
     *  Metodo que consulta en base de datos todos los accesos
     */    
    function getAllAccesos($filtros){
        $result = DB::table ( 'acceso' );
        if(count($filtros) > 0){
            foreach($filtros as $tmp){
                $result = $result->where($tmp[0], $tmp[1], $tmp[2]);
            }
        }
        $result = $result->orderBy('idacceso')->get();
        return $result;     
    }

    /**
     *  Metodo que consulta en base de datos todas las biatcoras
     */
    function getAllBitacoras($filtros){
        $result = DB::table ( 'bitacora' );
        if(count($filtros) > 0){
            foreach($filtros as $tmp){
                $result = $result->where($tmp[0], $tmp[1], $tmp[2]);
            }
        }
        $result = $result->orderBy('id')->get();
        return $result;
    }
    
  

    /**
     *  Metodo que consulta en base de datos de todos los accesos para exportar a XLS
     */
    function getAllAccesosXls(){
        $filtros = Session::get('busqueda_Accesos');
        $results = DB::table ( 'acceso' )
                    ->leftjoin('users','users.id', 'acceso.idusuario')
                    ->select('acceso.idacceso as Id','users.name as Nombre del Usuario','acceso.usuario as Email','acceso.fecha as Fecha',
                    'acceso.ip as IP','acceso.explorador as Explorador','acceso.so as Sistema Operativo',
                    DB::raw("(CASE WHEN acceso.status = 1 THEN 'Login' ELSE 'No Logueado' END) AS Estatus")
                    );
        if(count($filtros) > 0){
            foreach($filtros as $tmp){
                if(trim($tmp[1]) == 'IN'){
                    $results = $results->whereIn($tmp[0],$tmp[2]);
                }else{
                    $results = $results->where($tmp[0], $tmp[1], $tmp[2]);
                }
            }
        }
        $results = $results->orderBy('acceso.idacceso', 'desc')->get();
        return $results;
    }

    /**
     *  Metodo que consulta en base de datos de todos los movimientos para exportar a XLS
     */
    
    function getAllMovimientosXls(){
    	
        $filtros = Session::get('busqueda_Movimientos');
        $results = DB::table ( 'bitacora' )
                   ->join('users','users.id', 'bitacora.iduser')
                   ->join('modulo','modulo.idmodulo','bitacora.idmodulo')
                   // ->select('bitacora.id as Id','users.name as Nombre del Usuario','modulo.nombre as Módulo','bitacora.fecha as Fecha',
                   ->select('users.name as Nombre del Usuario','modulo.nombre as Módulo','bitacora.fecha as Fecha',
                   'bitacora.nombre_registro as Registro',              
                   DB::raw("(CASE WHEN bitacora.tipo_movimiento= 1 THEN 'Alta' 
                               WHEN bitacora.tipo_movimiento= 2 THEN 'Actualizar'
                               WHEN bitacora.tipo_movimiento= 3 THEN 'Eliminar'
                               WHEN bitacora.tipo_movimiento= 4 THEN 'Activar'
                               WHEN bitacora.tipo_movimiento= 5 THEN 'Resetear'
                               WHEN bitacora.tipo_movimiento= 6 THEN 'Actualizar_Contraseña'
                        ELSE 'Desconocido' END) AS Movimiento")
                        // 'bitacora.estado_anterior as Estado_Anterior',
                        // 'bitacora.estado_despues as Estado_Actual',
                        // 'bitacora.idregistro as ID_Registro'
                );
        if(count($filtros) > 0){
            foreach($filtros as $tmp){
                if(trim($tmp[1]) == 'IN'){
                    $results = $results->whereIn($tmp[0],$tmp[2]);
                }else{
                    $results = $results->where($tmp[0], $tmp[1], $tmp[2]);
                }
            }
        }        
        $results = $results->orderBy('bitacora.id', 'desc')->get();
        return $results;
    }
    /**
     *  Metodo que consulta en base de datos de todos los usuarios para exportar a XLS
     */
    function getAllUsuariosXls(){       
        $filtros = Session::get('busqueda_Usuarios');
        $results = DB::table ( 'users' )
                ->join('perfil','users.idperfil', 'perfil.idperfil')
                ->select('users.id as Id','users.name as Nombre','users.email as Correo',
                         'users.created_at as Fecha Alta',
                         'perfil.nombre as Perfil',
                         DB::raw("(CASE WHEN users.activo = 1 THEN 'Activo' ELSE 'No Activo' END) AS Estatus")
                        );
        if(count($filtros) > 0){
            foreach($filtros as $tmp){
                if(trim($tmp[1]) == 'IN'){
                    $results = $results->whereIn($tmp[0],$tmp[2]);
                }else{
                    $results = $results->where($tmp[0], $tmp[1], $tmp[2]);
                }
            }
        }
        $results = $results->orderBy('users.id')->get();
        return $results;
    }

      


    /**
     *  Metodo que consulta en base de datos de todos los perfiles para exportar a XLS
     */    
    function getAllPerfilesXls(){
        $filtros = Session::get('busqueda_Perfiles');
        $results = DB::table ( 'perfil' )
        ->join('empresa' , 'perfil.idempresa', 'empresa.idempresa')
        ->select('perfil.idperfil as Id','perfil.nombre as Perfil','perfil.descripcion as Descripcion',
                DB::raw("(CASE WHEN perfil.activo = 1 THEN 'Activo' ELSE 'No Activo' END) AS Estatus")
        );
        if(count($filtros) > 0){
            foreach($filtros as $tmp){
                if(trim($tmp[0]) != '') {
                    if (trim($tmp[1]) == 'IN') {
                        $results = $results->whereIn($tmp[0], $tmp[2]);
                    } else {
                        $results = $results->where($tmp[0], $tmp[1], $tmp[2]);
                    }
                }
            }
        }
        $results = $results->orderBy('perfil.idperfil')->get();
        return $results;
    }

  
    /**
     *  Metodo que consulta en base de datos de todas las empresas para exportar a XLS
     */
    function getAllEmpresasXls(){
        $filtros = Session::get('busqueda_Empresas');
        $results = DB::table ( 'empresa' )
        ->select('empresa.idempresa as Id','empresa.nombre as Empresa',
                 'empresa.razon_social as Razï¿½n Social',
                 'empresa.nombre_representante as Nombre del Representante',                 
                 'empresa.paterno_representante as Apellido Paterno del Representante',
                 'empresa.materno_representante as Apellido Materno del Representante',
                 'empresa.email_representante as Correo Electrï¿½nico del Representante',
                 'empresa.rfc as RFC',
                 'empresa.direccion as Direcciï¿½n',             
                DB::raw("(CASE WHEN empresa.activo = 1 THEN 'Activo' ELSE 'No Activo' END) AS estatus")
        );
        if(count($filtros) > 0){
            foreach($filtros as $tmp){
                if(trim($tmp[1]) == 'IN'){
                    $results = $results->whereIn($tmp[0],$tmp[2]);
                }else{
                    $results = $results->where($tmp[0], $tmp[1], $tmp[2]);
                }
            }
        }
        $results = $results->orderBy('empresa.idempresa')->get();
        return $results;
    }
    
    /**
     *  Metodo que consulta en base de datos de todos los talentos para exportar a XLS
     */
    
    function getAllTalentosXls(){
        
        $filtros = Session::get('busqueda_Talentos');
        $results = DB::table ( 'talento' )
        // ->select('bitacora.id as Id','users.name as Nombre del Usuario','modulo.nombre as Módulo','bitacora.fecha as Fecha',
        ->select('talento.nombre as Nombre','talento.definicion as Definición',
            DB::raw("(CASE WHEN talento.activo= 1 THEN 'Activo'
                        ELSE 'Inactivo' END) AS Estatus")
            );
        if(count($filtros) > 0){
            foreach($filtros as $tmp){
                if(trim($tmp[1]) == 'IN'){
                    $results = $results->whereIn($tmp[0],$tmp[2]);
                }else{
                    $results = $results->where($tmp[0], $tmp[1], $tmp[2]);
                }
            }
        }
        $results = $results->orderBy('talento.idtalento', 'desc')->get();
        return $results;
    }
    
    /**
     *  Metodo que consulta en base de datos todos los perfiles
     */    
    function getAllPerfil(){
        return DB::table ( 'perfil' )->where('activo',true) ->get();
    }

    function getAllPerfilFiltros($array){
        $result = DB::table ( 'perfil' );
        if(count($array) > 0){
            foreach($array as $tmp){
                if(trim($tmp[1]) == 'IN'){
                    $result = $result->whereIn($tmp[0],$tmp[2]);
                }else{
                    $result = $result->where($tmp[0], $tmp[1], $tmp[2]);
                }
            }
        }
        $result = $result->get();
        return $result;
    }

    function getAllPerfilFiltrosArray($array){
        $result = DB::table ( 'perfil' );
        if(count($array) > 0){
            foreach($array as $tmp){
                if(trim($tmp[1]) == 'IN'){
                    $result = $result->whereIn($tmp[0],$tmp[2]);
                }else{
                    $result = $result->where($tmp[0], $tmp[1], $tmp[2]);
                }
            }
        }
        $result = $result->get()->toArray();
        return $result;
    }
    /**
     *  Metodo que consulta en base de datos todos los permisos
     */
    function getAllPermisos(){
        return DB::table ( 'permiso' )->get();
    }

    /**
     *  Metodo que consulta en base de datos los nombres de los ids pasados como parametros
     */
    function getAllPermisosIds($ids){
        return DB::table ( 'permiso' )->whereIn('idpermiso',$ids)->get();
    }
    
    /**
     * Metodo que consulta en base de datos todos los perfil ligados a modulos
     */
    function getAllPerfilModulos($id){      
        $result = DB::table('perfil_modulo')->distinct()->select('idmodulo')->where('idperfil',$id)->get(); 
        return self::eliminaIndice(json_decode(json_encode($result), true));
    }

    function getAllPerfilModulosXIdModulo($idModulo,$idPerfil){
        return DB::table('perfil_modulo')->distinct()->where('idModulo',$idModulo)
                    ->where('idperfil',$idPerfil)->get();
    }
    
    
    function getPerfilModulosPermisosUser($idPerfil){
        return  DB::table('perfil_modulo')
        ->leftjoin('perfil_modulo_permiso', 'perfil_modulo_permiso.idperfil_modulo', '=', 'perfil_modulo.idperfil_modulo')
        ->where('perfil_modulo.idperfil',$idPerfil)
        ->select('perfil_modulo.idperfil','perfil_modulo.idmodulo','perfil_modulo_permiso.idpermiso')
        ->orderBy('perfil_modulo.idperfil','perfil_modulo_permiso.idpermiso')
        ->get();
        
    }
    
    function getUserModulosPermisosUser($idUser){
        return  DB::table('users_modulo')
        ->leftjoin('users_modulo_permiso', 'users_modulo_permiso.iduser_modulo', '=', 'users_modulo.iduser_modulo')
        ->where('users_modulo.iduser',$idUser)
        ->select('users_modulo.iduser','users_modulo.idmodulo','users_modulo_permiso.idpermiso','users_modulo.permitido')
        ->orderBy('users_modulo.iduser','users_modulo_permiso.idpermiso')
        ->get();         
    }
    
    /**
     * Metodo que regrea los modulos ligados al perfil seleccionado
     * @param  $id
     */
    function getAllPerfilModulosMenus($id){
        return  DB::table('perfil_modulo')
        ->leftjoin('modulo', 'perfil_modulo.idmodulo', '=', 'modulo.idmodulo')
        ->where('perfil_modulo.idperfil',$id)
        ->whereNuLL('modulo.parent')
        ->orderBy('perfil_modulo.idperfil','_modulo.idmodulo')
        ->get();
        //toSql
    }
    
    /**
     * Metodo qye regresa todos los modulos del perfil pasado como parametro
     * @param int $id
     */
    function getAllPerfilModulosSubmenus($id){
        return  DB::table('perfil_modulo')
        ->leftjoin('modulo', 'perfil_modulo.idmodulo', '=', 'modulo.idmodulo')
        ->where('perfil_modulo.idperfil',$id)
        ->whereNotNuLL('modulo.parent')
        ->orderBy('perfil_modulo.idperfil','_modulo.idmodulo')
        ->get();
        //toSql        
    }
    
    /**
     * Metodo que consulta en base de datos todos los perfil ligados a modulos y permisos
     */
    function getAllPerfilModuloPermisos($id){
        $result = DB::table('perfil_modulo')
        ->leftjoin('perfil_modulo_permiso', 'perfil_modulo.idperfil_modulo', '=', 'perfil_modulo_permiso.idperfil_modulo')
        ->select('perfil_modulo.idmodulo','perfil_modulo_permiso.idpermiso')
        ->where('perfil_modulo.idperfil',$id)
        ->whereNotNull('perfil_modulo_permiso.idpermiso')
        ->orderBy('perfil_modulo.idperfil','perfil_modulo_permiso.idpermiso')
        ->get();
        return self::arryaKeys(json_decode(json_encode($result), true),'idmodulo','idpermiso');
    }

    /**
     * Metodo que consulta los modulos parent igual a null del usuario que tiene asignado
     */
    function getAllUsuarioModulosMenus($idUsuario){
        return DB::table('users_modulo')
        ->leftjoin('modulo', 'users_modulo.idmodulo', '=', 'modulo.idmodulo')
        ->where('users_modulo.iduser',$idUsuario)
        ->whereNuLL('modulo.parent')
        ->orderBy('users_modulo.iduser','_modulo.idmodulo')
        ->get();
        //toSql
    }
    
    /**
     * Metodo que consulta los modulos parent distinto  a null del usuario que tiene asignado
     */    
    function getAllUsuarioModulosSubmenus($idUsuario){
        return  DB::table('users_modulo')
        ->leftjoin('modulo', 'users_modulo.idmodulo', '=', 'modulo.idmodulo')
        ->where('users_modulo.iduser',$idUsuario)
        ->whereNotNuLL('modulo.parent')
        ->orderBy('users_modulo.iduser','_modulo.idmodulo')
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
    /**
     *  Metodo que consulta en base de datos todos los modulos - permisos
     */
    function getAllModuloPermisos(){        
        $results = DB::table('modulo_permiso')
        ->join('permiso','modulo_permiso.idpermiso','=','permiso.idpermiso')        
        ->select('modulo_permiso.idmodulo','modulo_permiso.idpermiso','permiso.nombre')
        ->orderBy('modulo_permiso.idmodulo','desc','modulo_permiso.idpermiso')
        ->get();
        return $results;
    }
 
    /**
     * Metodo para validar si un correo electronico ya se encuentra registrado en users
     * @param String $email
     * @return boolean
     */
    function validaEmail($email){
        $existe = false;
        $results = DB::table('users')
                    ->where('email','=',$email)
                    ->get();
        if(count($results) > 0){
            $existe = true;
        }
        return $existe;
    }
    
    function eliminaIndice($datos){
        $regreso = array();
        if(count($datos)  > 0){
            foreach($datos as $ind => $tmp){
                foreach($tmp as $key => $value){
                    $regreso[] = $value;
                }
            }
        }
        return $regreso;
    }
    
    function arryaKeys($datos,$key,$value){
        $regreso = array();
        if(count($datos)  > 0){
            foreach($datos as $ind => $tmp){
                $regreso[$tmp[$key]][] = $tmp[$value];
            } 
        }
        return $regreso;         
    }
 
    function eliminaVacios($data){
        $tmp = array();
        if(count($data) > 0){
            foreach($data as $value){
                if(trim($value) != ''){
                    $tmp[] = $value;
                }
            }
        }
        return $tmp;
    }
    
    function obtenerNombreController($request){
        preg_match('/([a-z]*)@/i', $request->route()->getActionName(), $matches);
        return $matches[1];
    }
    
    function obtenModuloId($controller){
        $id = 0;
        $controller = str_replace('Controller','',$controller).'%';
        $results = DB::table('modulo')->where('identificador','like',$controller)->select('idmodulo')->get();
        foreach($results as $result ){
            $id = $result->idmodulo;
        }
        return $id;
    }
    
    function obtenerNombreModulo($idModulo){
        $nm = "";
        $results = DB::table('modulo')->where('idmodulo',$idModulo)->select('nombre')->get();
        foreach($results as $result ){
            $nm = $result->nombre;
        }
        return $nm;
    }
    
    function verificaPasswordRummy($emailUser){
        $idRummy = 0;
        $results = DB::table('users')->where('email','=',$emailUser)->select('dummy')->get();
        foreach($results as $result ){
            $idRummy = $result->dummy;
        }
        return (int) $idRummy;       
    }
 
    function getIdEmpresa($idperfil){
        $idEmpresa = 0;
        $results = DB::table('perfil')->where('idperfil','=',$idperfil)->select('idempresa')->get();
        foreach($results as $result ){
            $idEmpresa = $result->idempresa;
        }
        return (int) $idEmpresa;
         
    }
    
    function insertaBitacora($tipoMovimiento,$objetoAnterior,$objetoDespues,$idRegistro,$modulo,$moduloId,$userId,$array,$campoBase){
        $carbon = new \Carbon\Carbon();
//         DB::beginTransaction ();
        try{
            $registraBitacora          = new RegistraBitacora($tipoMovimiento, $objetoAnterior, $objetoDespues,$campoBase);  
            $bitacora                  = new Bitacora();
            $bitacora->iduser          = $userId;
            $bitacora->idmodulo        = $moduloId;
            $bitacora->nombre_modulo   = $modulo;
            $bitacora->fecha           = $carbon->now()->toDateTimeString();  //new \DateTime();; //date('Y-m-d H:i:s');
            $bitacora->idregistro      = $idRegistro;
            $bitacora->nombre_registro = $registraBitacora->getNombreRegistro();
           
            
            
            $bitacora->tipo_movimiento = $tipoMovimiento;
            $leyendaAntes              = $registraBitacora->getAntes();
            $leyendaDespues            = $registraBitacora->getDespues();
            if(trim($modulo) == Constantes::CONTROLLER_USUARIO && $tipoMovimiento <= Constantes::ACCION_ACTUALIZAR){
                $leyendaAntes  .= " ".$array['perfilModulosPermisosAnt'];
                $leyendaAntes  .= " ".$array['usuarioModulosPermisosAnt'];
                $leyendaDespues.= " ".$array['perfilModulosPermisosDes'];
                $leyendaDespues.= " ".$array['usuarioModulosPermisosDes'];
            }
            if(trim($modulo) == Constantes::CONTROLLER_PERFIL && $tipoMovimiento <= Constantes::ACCION_ACTUALIZAR){
                $leyendaAntes  .= " ".$array['perfilModulosPermisosAnt'];
                $leyendaDespues.= " ".$array['perfilModulosPermisosDes'];            
            }
            $bitacora->estado_anterior = $leyendaAntes;
            $bitacora->estado_despues  = $leyendaDespues;           
            $bitacora->ip              = $registraBitacora->getIp();
            $bitacora->save();
//             $this->log->debug($bitacora);
//             var_dump($bitacora);
//             DB::commit ();
        }catch (\Exception $e){
//             DB::rollback ();
            throw new \Exception('Error al crear la Bitacora '.$e);         
        }
    }
 
    
    
    public  function generaLetrero($total,$totalPaginador,$opciones){
        $datos= array();
        if($total > 0){
            if($totalPaginador < $opciones['noregs']){
                $to = $total;
            }else{
                $to = ($opciones['nopage'] * $opciones['noregs']);
            }
            if((int) $opciones['nopage'] == 1){
                if($totalPaginador < (int) $opciones['noregs']){
                	if($totalPaginador > 0)
                    	$opciones['noregs'] = $totalPaginador;
                	else 
                		$opciones['noregs'] = $total;
                }                
                $datos = array('from' => $opciones['nopage'],'to' => $opciones['noregs'], 'total' =>$total); 
            }else{
                $datos = array('from' => ((($opciones['nopage']-1) * $opciones['noregs']) + 1),
                               'to'   => $to, 'total' =>$total);
            }
        }
        return $datos;
    }


    
    
    
    public function regresaControlador($idModulo){
    	return DB::table('modulo')->select('identificador')->where('idmodulo', '=' ,$idModulo)->get()->first();
    }
    
   

    public function catalogoUsuarios($usuarios){
        $catalogo = array();
        if(count($usuarios) > 0){
            foreach($usuarios as $usu){
                $catalogo[$usu->id] = $usu->name;
            }
        }
        return $catalogo;
    }

    
    public function catalogoEmpresas($empresas){
        $catalogo = array();
        if(count($empresas) > 0){
            foreach($empresas as $emp){
                $catalogo[$emp->idempresa] = $emp->nombre;
            }
        }
        return $catalogo;
    }


    public function catalogoPerfiles($perfiles){
        $catalogo = array();
        if(count($perfiles) > 0){
            foreach($perfiles as $per){
                $catalogo[$per->idperfil] = $per->nombre;
            }
        }
        return $catalogo;
    }

    public function catalogoPermisos($permisos){
        $catalogo = array();
        if(count($permisos) > 0){
            foreach($permisos as $per){
                $catalogo[$per->idpermiso] = $per->nombre;
            }
        }
        return $catalogo;
    }

    public function catalogoModulos($modulos){
        $catalogo = array();
        if(count($modulos) > 0){
            foreach($modulos as $mod){
                $catalogo[$mod->idmodulo] = $mod->nombre;
            }
        }
        return $catalogo;
    }

    public function formatoYMD($fecha){
    	if(trim($fecha) != '')
        	return  substr($fecha,6,4).'-'.substr($fecha,3,2).'-'.substr($fecha,0,2);
    	else
    		return '';
    }
    
    public function formatoDMY($fecha){
    	if(trim($fecha) != '')
        	return  substr($fecha,8,2).'-'.substr($fecha,5,2).'-'.substr($fecha,0,4);
    	else 
    		'';
    }
    
    function debug($data){
        echo"<pre>";
        print_r($data);
        die();
    }
    public function generaPassword() {
        // Se define una cadena de caractares. Te recomiendo que uses esta.
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        // Obtenemos la longitud de la cadena de caracteres
        $longitudCadena = strlen ( $cadena );
    
        // Se define la variable que va a contener la contraseï¿½a
        $pass = "";
        // Se define la longitud de la contraseï¿½a, en mi caso 10, pero puedes poner la longitud que quieras
        $longitudPass = 10;
    
        // Creamos la contraseï¿½a
        for($i = 1; $i <= $longitudPass; $i ++) {
            // Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
            $pos = rand ( 0, $longitudCadena - 1 );
    
            // Vamos formando la contraseï¿½a en cada iteraccion del bucle, aï¿½adiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
            $pass .= substr ( $cadena, $pos, 1 );
        }
        return $pass;
    }  
    
    
    


   
    public function calculaPeriodo($opcion){
    	$dia = $mes = $ano = "";
    	$mesActual  = date('m');
    	$anoActual  = date('Y');
    	$fechaActual= date('Y-m-d');
    	if($opcion == 1){
    		if($mesActual == 12){
    			$mes = Constantes::PERFIL_ADMIN_EMPRESA;
    			$ano = $anoActual + 1;
    		}
    		elseif($mesActual == 11){
    			$mes = Constantes::ACTIVO;
    			$ano = $anoActual + 1;
    		}
    		else{
    			$mes = $mesActual + 2;
    			$ano = $anoActual;
    		}
    	}
    	else{
    		$dia = date ( 'Y-m-d' , strtotime ( '+1 month' , strtotime ($fechaActual) ) );
    		//$dia = "2018-08-01";
    	}
    	return array('mes' => $mes,'ano' => $ano, 'dia' => $dia);
    }
    
    
    
    
    
    
    public function reemplazaTags($input) {
    	$search = array(
    			'@<script[^>]*?>.*?</script>@si',   // Elimina javascript
    			'@<[\/\!]*?[^<>]*?>@si',            // Elimina las etiquetas HTML
    			'@<style[^>]*?>.*?</style>@siU',    // Elimina las etiquetas de estilo
    			'@<![\s\S]*?--[ \t\n\r]*>@'         // Elimina los comentarios multi-lï¿½nea
    	);
    	$output = preg_replace($search, '', $input);
    	return $output;
    }
    
    public function limpiar($input) {
    	if (is_array($input)) {
    		foreach($input as $var=>$val) {
    			$output[$var] = limpiar($val);
    		}
    	}
    	else {
    		if (get_magic_quotes_gpc()) {
    			$input = stripslashes($input);
    		}
    		$input  = $this->reemplazaTags($input);
    		$output = trim(($input));
    	}
    	return $output;
    }
    
    
    
    public function guardaArchivo($file,$string) {
    	if ($file) {
    		DB::beginTransaction ();
    		try{
    			$archivo = new Archivo();
    			$archivo->nombre    = $file->getClientOriginalName();
    			$archivo->tipo      = trim(strtolower($file->getClientOriginalExtension()));
    			$archivo->contenido = $string;
    			$archivo->save ();
    			DB::commit ();
    			return $archivo;
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw new \Exception('Error al guardar el archivo '.$e);
    		}
    	}
    }

    public function guardaArchivoEnRuta($nombre,$string) {
    	if ($nombre != '') {
    		DB::beginTransaction ();
    		try{
    			$archivo = new Archivo();
    			$archivo->nombre    = $nombre;
    			$archivo->tipo      = 'pdf';
    			$archivo->contenido = $string;
    			$archivo->save ();
    			DB::commit ();
    			return $archivo;
    		}
    		catch ( \Exception $e ) {
    			DB::rollback ();
    			throw $e;
    		}
    	}
    }
        
    public function getArchivos(){
    	return DB::table('archivo')->get();
    }
    
    public function getArchivo($id){
    	return  Archivo::findOrFail ( $id );
    }
    /**
     * Metodo que realiza el insert a la base de datos
     * @param  $file_content
     */
    public function codificaBase64($file_content){
    	try{
    		$binary = base64_encode(file_get_contents($file_content));
    	}catch(\Exception $ex){
    		$this->log->error ($ex);
    		$binary = $ex->getMessage();
    	}
    	return $binary;
    }
    
    /**
     * Metodo de decodifica la cadena y la convierte en archivo
     * @param string codificada
     * @return string nombre del archivo
     */
    public function decodificaBase64($file_content,$filename){
    	$array = array();
    	$array['exito'] = 0;
    	try{
    		$pdf_decoded = base64_decode ($file_content);
    		$f = fopen (Constantes::PATH_SYSTEM_UPLOAD.$filename,'w');
    		fwrite ($f,$pdf_decoded);
    		fclose ($f);
    		$array['exito'] = 1;
    		$array['sis'] = Constantes::PATH_SYSTEM_UPLOAD.$filename;
    		$array['web'] = Constantes::PATH_WEB_UPLOAD.$filename;
    		$array['file'] = $filename;
    	}
    	catch(\Exception $ex){
    		$this->log->error ($e);
    	}
    	return $array;
    }
 
    
    public function limpiaArchivo($nombre){
    	$nombre = str_replace(' ','_',$nombre);
    	$nombre = str_replace('Á','A',$nombre);
    	$nombre = str_replace('É','E',$nombre);
    	$nombre = str_replace('Í','I',$nombre);
    	$nombre = str_replace('Ó','O',$nombre);
    	$nombre = str_replace('Ú','U',$nombre);
    	$nombre = str_replace('Ñ‘','N',$nombre);
    	$nombre = str_replace('á','a',$nombre);
    	$nombre = str_replace('é','e',$nombre);
    	$nombre = str_replace('í','i',$nombre);
    	$nombre = str_replace('ó','o',$nombre);
    	$nombre = str_replace('ú','u',$nombre);
    	$nombre = str_replace('ñ','n',$nombre);
    	return $nombre;
    	 
    }
    
    
    public function procesoArchivos($request, $filename){
        $file    = $request->file($filename);
        if ($file) {
            try{
                $file->move (  Constantes::PATH_SYSTEM_UPLOAD, $file->getClientOriginalName () );
                $string = base64_encode ( file_get_contents ( Constantes::PATH_SYSTEM_UPLOAD. $file->getClientOriginalName () ) );
                $archivo = new Archivo();
                $archivo->nombre    = $file->getClientOriginalName();
                $archivo->tipo      = trim(strtolower($file->getClientOriginalExtension()));
                $archivo->contenido = $string;
                $archivo->save ();
                @unlink(Constantes::PATH_SYSTEM_UPLOAD. $file->getClientOriginalName ());
                return $archivo;
            }
            catch ( \Exception $e ) {
                throw new \Exception('Error al guardar el archivo '.$e);
            }
        }

    }
    
    
    /**
     * Metodo para validar si el mail del usuario representante ya se encuentra registrado
     * @param String $mail
     * @param String $idusuario
     * @return boolean
     */
    function validaNombreElemento(Request $request){
    	$idModulo = $request->get('idmodulo');
    	$idElemento = '';
    	$tabla = '';
		switch($idModulo){
	    	
	    	case Constantes::MODULO_ID_PERFIL:
	    		$tabla = 'perfil';
	    		$idElemento = 'idperfil';
	    		break;
    		case Constantes::MODULO_ID_EMPRESA:
    			$tabla = 'empresa';
    			$idElemento = 'idempresa';
    			break;
    		case Constantes::MODULO_ID_TALENTO:
    		    $tabla = 'talento';
    		    $idElemento = 'idtalento';
    		    break;
		 }
	    	
		$nombre = $request->get('nombre');
	    $id = $request->get('idelemento');
	    
	    $idempresa = $request->get('idempresa');
    	$existe = false;
    	$results = DB::table($tabla)->where('nombre','=',$nombre);
    		 
    	if($id != null && $id != '')
    		$results = $results->where($idElemento,'!=',$id);
    			 
//     	if($idempresa != null && $idempresa != '')
//     		$results = $results->where('idempresa','=',$idempresa);
    		
    	$results = $results->get();
    		if(count($results) > 0){
    			$existe = true;
    		}
    	return $existe;
    }
    
    function getCliente($id){
    	return  Cliente::findOrFail ( $id );
    }
    

    function encriptar($string, $key) {
    	//     	$key = "MICLAVE_123456789";
    	$result = '';
    	$test = array();
    	for($i=0; $i<strlen($string); $i++) {
    		$char = substr($string, $i, 1);
    		$keychar = substr($key, ($i % strlen($key))-1, 1);
    		$char = chr(ord($char)+ord($keychar));
    
    		$test[$char]= ord($char)+ord($keychar);
    		$result.=$char;
    	}
    	return urlencode(base64_encode($result));
    }
    function desencriptar($string, $key) {
    	//     	$key = "MICLAVE_123456789";
    	$result = '';
    	$string = base64_decode(urldecode($string));
    	for($i=0; $i<strlen($string); $i++) {
    		$char = substr($string, $i, 1);
    		$keychar = substr($key, ($i % strlen($key))-1, 1);
    		$char = chr(ord($char)-ord($keychar));
    		$result.=$char;
    	}
    	return $result;
    }
    
    

    
    public function detect()
    {
    	$this->log->debug("Navegador: " . $_SERVER['HTTP_USER_AGENT']);
    	$browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
    	$os=array("WIN","MAC","LINUX");
    
    	# definimos unos valores por defecto para el navegador y el sistema operativo
    	$info['browser'] = "OTHER";
    	$info['os'] = "OTHER";
    	$this->log->debug("val: " . strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'TRIDENT') );
    	//Verifica si es mobil
    	 
    	if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'MOBILE') !== false) {
    		if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'OPIOS') !== false) {
    			$info['browser'] = "OPERA MOBIL";
    		}
    		else if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'CRIOS') !== false) {
    			$info['browser'] = "CHROME MOBIL";
    		}
    		else if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'FXIOS') !== false) {
    			$info['browser'] = "FIREFOX MOBIL";
    		}
    		else if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'SAFARI') !== false) {
    			$info['browser'] = "SAFARI MOBIL";
    		}
    
    	} else {
    		if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'MSIE') !== false || strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), 'TRIDENT') !== false) {
    			$info['browser'] = "IE";
    		} else {
    			# buscamos el navegador con su sistema operativo
    			foreach($browser as $parent)
    			{
    				$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
    				$f = $s + strlen($parent);
    				$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
    				$version = preg_replace('/[^0-9,.]/','',$version);
    				if ($s)
    				{
    					$info['browser'] = $parent;
    					$info['version'] = $version;
    				}
    			}
    		}
    	}
    
    	 
    	 
    	 
    
    	 
    
    	# obtenemos el sistema operativo
    	foreach($os as $val)
    	{
    		if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
    			$info['os'] = $val;
    	}
    
    	# devolvemos el array de valores
    	return $info;
    }
    
    
    function getAllErrores(){
    	$result = DB::table ( 'reporte_error' )
    	->where('reportado','=',false)
    	->get();
    	return $result;
    }
    
    function getAllEstados(){
        return DB::table( 'estado' )->orderBy('nombre')->get();
    }

    function getComportamientosCompetencia($idCompetencia, $estatus){
        $qry = DB::table( 'comportamiento' )->where('idcompetencia','=',$idCompetencia);
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
        
        $qry = $qry->orderBy('nombre')->get();
        return $qry;
    }
    
    function getCompetencias($estatus){
        $qry = DB::table( 'competencia' );
        if($estatus != '')
        $qry = $qry->where('activo','=',$estatus);
        
        $qry = $qry->orderBy('nombre')->get();
        return $qry;
    }
    
    
    function getTiposCompetencia($estatus){
        $qry = DB::table( 'tipo_competencia' );
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('nombre')->get();
            return $qry;
    }
    
    
    function getEjerciciosPorTipo($idTipoEjercicio, $estatus){
        $qry = DB::table( 'ejercicio' )->where('idtipo_ejercicio','=',$idTipoEjercicio);
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('nombre')->get();
            return $qry;
    }
    
    function getClientes($estatus){
        $qry = DB::table( 'cliente' );
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('nombre_comercial')->get();
            return $qry;
    }
    
    function getTiposEjercicios($estatus){
        $qry = DB::table( 'tipo_ejercicio' );
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('nombre')->get();
            return $qry;
    }
    
    
    function getTalentos($estatus){
        $qry = DB::table( 'talento' );
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('nombre')->get();
            return $qry;
    }
    
    function getPruebas($estatus){
        $qry = DB::table( 'prueba' );
        if($estatus != '')
            $qry = $qry->where('activo','=',$estatus);
            
            $qry = $qry->orderBy('nombre')->get();
            return $qry;
    }
}