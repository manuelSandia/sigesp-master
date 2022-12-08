<?php
session_start();
require_once('../../base/librerias/php/general/Json.php');
require_once('sigesp_ctr_sst_servicio.php');

if ($_POST['ObjSon']) 	
{
	$submit = str_replace("\\","",$_POST['ObjSon']);
	$json = new Services_JSON;	
	$ArJson = $json->decode($submit);
	$oserviciosst = new ServicioSst('sigesp_empresa');
	$evento = $ArJson->operacion;
	
	switch ($evento)
	{
		case 'verificarrelease' :
			$mensaje="";
			$valido = $oserviciosst->verificarReleaseSst($_SESSION["ls_gestor"],$mensaje);
			$arreglo[0]['mensaje'] = $mensaje; 
			$arreglo[0]['valido']  = $valido;
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta; 
			break;
	}
	unset($oserviciosep);
}

?>