<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use sistema\Repositories\UsuarioRepository;

class ResetPasswordController extends Controller
{
	private $usuarioRepository;
	
    public function __construct(UsuarioRepository $usuarioRepository){
//     	parent::__construct($usuarioRepository);
    	$this->usuarioRepository = $usuarioRepository;
    	
    }
    
	public function index(){
		return view ( 'auth.reset.reset', ["email" => Session::get('user')]);
	}
	
	public function update(Request $request){
		$passwordEncript = "";
		try{
			if($request){
				$password = bcrypt ($request->get ('password'));
				$this->usuarioRepository->resetPasswordUsuario($request->get('email'),$password);
				Session()->put('dummy',null);
				Session::flash ( 'message', Lang::get ( 'general.success' ) );
			}
		}
		catch (\Exception $e) {
			$this->log->error ($e);
			Session::flash ( 'message-error', Lang::get ( 'general.error' ) );
		}
		finally {
			return Redirect::to ( 'home/' );
		}
	}	
}
