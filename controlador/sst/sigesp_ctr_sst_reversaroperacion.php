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
		case 'buscar':
			$oservicio = new ServicioSst ();
			switch ($arrjson->codtipope) {
				case "R":
					$datos= $oservicio->buscarRecepciones($datosempresa["codemp"],$codusu,$arrjson);;
					break;
				case "A":
					$datos= $oservicio->buscarAsignaciones($datosempresa["codemp"],$codusu,$arrjson);;
					break;
				case "C":
					$datos= $oservicio->buscarCierres($datosempresa["codemp"],$codusu,$arrjson);
					break;
			}
			//var_dump($datos);
			$objSon = generarJson ( $datos );
			echo $objSon;
			unset($oservicio);
			break;
		
		case 'recepcion':
			$oservicio = new ServicioSst ();
			$respuesta = $oservicio->reversarRecepcion($datosempresa["codemp"],$arrjson);
			echo "|".$respuesta;
			unset($oservicio);
			break;
		
		case 'asignacion':
			$oservicio = new ServicioSst ();
			$respuesta = $oservicio->reversarAsignacion($datosempresa["codemp"],$arrjson);
			echo "|".$respuesta;
			unset($oservicio);
			break;
		
		case 'cierre':
			$oservicio = new ServicioSst ();
			$respuesta = $oservicio->reversarCierre($datosempresa["codemp"],$arrjson);
			echo "|".$respuesta;
			unset($oservicio);
			break;
		
	}
}
?>
