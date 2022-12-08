<?php
session_start();
/***********************************************************************************
* @descripcion:  Controlador para manejar la Seguridad en la session
* @fecha de creaci�n: 15/07/2009
* @autor: Ing. Arnaldo Suarez
* **************************
* @fecha modificacion  
* @autor   
* @descripcion 
* **********************************************************************************/

require_once ('../../base/librerias/php/general/Json.php');
require_once ('../../modelo/sss/dao/sigesp_mod_sss_dao_registroevento.php');
require_once ('sigesp_ctr_sss_servicio.php');

if ($_POST['ObjSon']) {
	$submit = str_replace ( "\\","",$_POST ['ObjSon'] );
	$json = new Services_JSON();
	$arregloJson = $json->decode($submit);
	$evento = $arregloJson->operacion;
	
	switch ($evento){
		case 'ObtenerSesion' :
			if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION))){
				echo "|nosesion";
			}
			else{
				$oservicio = new ServicioSss('sss_derechos_usuarios');
				$oservicio->setCodemp($_SESSION["la_empresa"]["codemp"]);
				$oservicio->obtenerDerechosUsuarios($_SESSION["la_empresa"]["codemp"], $_SESSION["la_logusr"], $arregloJson->codsis, $arregloJson->pantalla, '1');
				echo "|".$ls_permisos;
			}
			break;
		
		case 'ObtenerInicioSesion' :
			if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION))){
				echo "|0";
			}
			else{
				echo "|1";
			}
			break;
		
		case 'DestruirSesion' :
			session_unset();
			break;
		
		case 'informacionusuarioinicio':
			$oservicio = new ServicioSss();
			$datos = $oservicio->obtenerUsuario($_SESSION["la_empresa"]["codemp"],$_SESSION["la_logusr"]);
			if (!$datos->EOF)
			{
				$varJson=generarJson($datos);
				echo $varJson;				
			}
			else 
			{	
				$arreglo[0]['mensaje'] = obtenerMensaje('SESION_EXPIRADA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}
			$datos->close();
			break;
			
		case 'informacionusuario':
			$oservicio = new ServicioSss('sss_derechos_usuarios');
			$oservicio->setCodemp($_SESSION["la_empresa"]["codemp"]);
			$datos = $oservicio->obtenerSistemaUsuario($_SESSION["la_empresa"]["codemp"],$_SESSION["la_logusr"],$arregloJson->codsis);
			if (!$datos->EOF)
			{
				$varJson=generarJson($datos);
				echo $varJson;				
			}
			else 
			{	
				$arreglo[0]['mensaje'] = obtenerMensaje('SESION_EXPIRADA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}
			$datos->close();
			break;
		
		case 'accesosistema':
			$oservicio = new ServicioSss('sss_permisos_internos');
			$oservicio->setCodemp($_SESSION["la_empresa"]["codemp"]);
			
			$restriccion[0][0]= "codemp";
			$restriccion[0][1]= "=";
			$restriccion[0][2]= $_SESSION["la_empresa"]["codemp"];
			$restriccion[0][3]= 0;
			$restriccion[1][0]= "codsis";
			$restriccion[1][1]= "=";
			$restriccion[1][2]= $arregloJson->codsis;
			$restriccion[1][3]= 0;
			$restriccion[2][0]= "codusu";
			$restriccion[2][1]= "=";
			$restriccion[2][2]= $_SESSION["la_logusr"];
			$restriccion[2][3]= 2;
			
			$datos = $oservicio->buscarCampoRestriccion($restriccion);
			if (!$datos->EOF)
			{
				echo '1';				
			}
			else 
			{	
                echo '0';
			}
			$datos->close();
			break;
	}
}
?>