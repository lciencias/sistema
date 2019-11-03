<?php
	$titulo     = "";
	$breadcrub  = url()->current();
	$action     = app('request')->route()->getAction();
	$controller = class_basename($action['controller']);
	$arrayContr = explode('@',$controller);
	$controller = str_replace("Controller","",$arrayContr[0]);
	$identifica = Session::get('identificadores');
	$parents    = Session::get('parents');
	$idModulo   = 0;
	$nmModulo   = $nombre = "";
	if($identifica != null && $identifica != '') {
        if(array_key_exists($controller, $identifica)){
	    	$idModulo = $identifica[$controller];
    	}
    }
	
	 if($parents != null && $parents != '') {
        if($idModulo > 0 && array_key_exists($idModulo,$parents)){
	    	$nombre = $parents[$idModulo];
    	}
	 }
	$arrayBread = explode('/',$breadcrub);
    $arrayLeyen = array();
    foreach($arrayBread as $ind => $value){
        if($ind > 4 && trim($value) != '' &&!is_numeric($value) &&  strlen($value)<150){
    		$arrayLeyen[] = $value;
    	}
    }
    
    $titulo  = Lang::get("leyendas.".implode('.',$arrayLeyen));
    if(substr($titulo,0,9)== 'leyendas.')
    	$titulo = Lang::get("leyendas.error");
?>
<div class="row">
	<div class="col-md-6 tdLeft" style="padding-left:40px;padding-top:10px;font-size:18px;">{{$titulo}}</div>
    <div class="col-md-6 tdRight">
		<ol class="breadcrumb encabezado">
			<li><i class="fa fa-dashboard"></i>&nbsp;<b>Usted está en:</b></li>
			@if ($nombre != '')
				<li>&nbsp;{{$nombre}}</li>
			@endif
			<li>&nbsp;{{$titulo}}</li>
		</ol>            		
   	</div>                			
</div>