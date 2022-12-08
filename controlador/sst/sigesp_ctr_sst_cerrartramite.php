<?php
session_start(); 
$datosempresa=$_SESSION["la_empresa"];
$codusu=$_SESSION["la_logusr"];
require_once ('../../base/librerias/php/general/Json.php');
require_once ('sigesp_ctr_sst_servicio.php');

$arreve [1] = $datosempresa["codemp"];
$arreve [2] = 'CFG';
$arreve [3] = 'SIGESP';
$arreve [4] = 'sigesp_spg_d_planctas.php';

if ($_POST['ObjSon']) {
    $submit = str_replace("\\", "", $_POST['ObjSon']);
    $json = new Services_JSON;
    $arrjson = $json->decode($submit);
	
	switch ($arrjson->operacion) {
		case 'incluir':
			$oservicio = new ServicioSst ();
			$respuesta = $oservicio->cerrarTramite($datosempresa["codemp"],$codusu,$arrjson);
			echo "|".$respuesta;
			unset($oservicio);
			break;
	}
}
?>
