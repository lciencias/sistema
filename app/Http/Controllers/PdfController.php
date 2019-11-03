<?php

namespace sistema\Http\Controllers;

use Illuminate\Http\Request;

use sistema\Http\Requests;
use sistema\Repositories\UsuarioRepository;
use Barryvdh\DomPDF\Facade as PDF;
use sistema\Repositories\PerfilRepository;
use sistema\Repositories\EmpresaRepository;

class PdfController extends Controller
{
	var $repository;
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Metodo que genera el archivo pdf
	 */
	public function pdf(UsuarioRepository $usuarioRepository){
		$this->repository = $usuarioRepository;
		$usuarios   = $this->repository->findByArray( array());
		$pdf = PDF::loadView('pdfs.usuarios' , compact('usuarios'));
		return $pdf->download('PDFUsuarios.pdf');
	}
	
	public function pdfPerfiles(PerfilRepository $perfilRepository){
		$this->repository = $perfilRepository;
		$perfiles   = $this->repository->findByArray( array());
		$pdf = PDF::loadView('pdfs.perfiles' , compact('perfiles'));
		return $pdf->download('PDFPerfiles.pdf');
	}
	
	public function pdfEmpresas(EmpresaRepository $empresaRepository){
		$this->repository = $empresaRepository;
		$empresas = $this->repository->findByArray( array());
		$pdf = PDF::loadView('pdfs.empresas' , compact('empresas'));
		return $pdf->download('PDFEmpresas.pdf');
	}
	
	
	
	
}
