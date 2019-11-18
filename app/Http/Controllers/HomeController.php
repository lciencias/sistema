<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use sistema\Policies\Constantes;
use sistema\Repositories\InterpretacionRepository;

class HomeController extends Controller
{

    public function __construct(InterpretacionRepository $interpretacionRepository){
    	parent::__construct();
    	$this->interpretacionRepository = $interpretacionRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	try{
    	    $interpretaciones = $this->interpretacionRepository->recuperaInterpretacionCandidato();
    	    Session::put('interpretaciones', $interpretaciones);
    	    Session::put('totalInterpretaciones', count($interpretaciones));
			return view('layouts.home');
    	}catch(\Exception $e){
    		$this->log->error(($e->getMessage()));
    	}
    }
    
    public function session(){
    	$regreso = 0;
    	$carbon = new \Carbon\Carbon();
    	$fechaActual = $carbon->now();
    	$fechaUltMov = Session::get ( 'fechaAcceso' );
    	if((int) $fechaActual->diffInMinutes($fechaUltMov) > (int) Constantes::EXPIRASESSION){
    		$this->log->info("Cerrando Sesion:  \nHora actual: ".date('Y-m-d H:i:s')."   \nHora Sesion: ".Session::get( 'fechaAcceso' )."  \nMinutos diferencia:  ".$fechaActual->diffInMinutes($fechaUltMov)."  \nMinutos default:  ".Constantes::EXPIRASESSION);
    		Session::flush();
    		Auth::logout();
    		$regreso = 1;
    	}
    	echo $regreso;
    	die();
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
    }
}