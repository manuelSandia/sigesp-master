<?php
session_start();
$datosempresa=$_SESSION["la_empresa"];
$codusu=$_SESSION["la_logusr"];
$dirctrsssbhe = "";
$dirctrsssbhe = dirname(__FILE__);
$dirctrsssbhe = str_replace("\\","/",$dirctrsssbhe);
$dirctrsssbhe = str_replace("/controlador/sss","",$dirctrsssbhe);
require_once($dirctrsssbhe.'/base/librerias/php/general/Json.php');
require_once($dirctrsssbhe.'/modelo/sss/dao/sigesp_mod_sss_dao_barraherramientas.php');


if ($_POST['ObjSon']){

	$submit = str_replace("\\","",$_POST['ObjSon']);
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
	$obarraherramientas =  new barraherramientasDao();
	$Evento = $ArJson->oper;
	
	switch ($Evento){
		    			
		case 'barraherramienta':
			$rs = $obarraherramientas->obtenerBarraHerramientaUsuario($codusu,$ArJson->codsis,$ArJson->nomven,$datosempresa["codemp"]);
			if ($rs->RecordCount()>0){
				$ObjSon = generarJson($rs);
				echo $ObjSon;				
			}
			else{
				$arRegistros = array(array('visible'=>0));
				$textJso = array('raiz'=>$arRegistros);
   				$textJson = json_encode($textJso);
				echo $textJson;
			}
			break;
	}
}
?>