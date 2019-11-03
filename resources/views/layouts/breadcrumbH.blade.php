<?php
	$titulo     = "";
	$breadcrub  = url()->current();
    $arrayBread = explode('/',$breadcrub);
    $arrayLeyen = array();
    foreach($arrayBread as $ind => $value){
    	if($ind > 4 && trim($value) != '' &&!is_numeric($value)){
    		$arrayLeyen[] = $value;
    	}
    }
    $titulo  = Lang::get("leyendas.".implode('.',$arrayLeyen));
?>
<section class="content-header">
	<h1>{{$titulo}}</h1>
	<ol class="breadcrumb encabezado">
		<li><i class="fa fa-dashboard"></i>&nbsp;<b>Usted está en:</b></li>
		<li>&nbsp;{{$titulo}}</li>
	</ol>            
</section>         		