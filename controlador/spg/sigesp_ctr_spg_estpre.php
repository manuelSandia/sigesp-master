<?php
session_start();
/************************************************************************** 	
* @Controlador para las funciones de estructuras presupuestarias.
* @versi?n: 1.0  
* @fecha creaci?n: 09/10/2008
* @autor: Ing. Gusmary Balza
* *************************************************************************
* @fecha modificacion  15/10/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se agrego la opci?n de seguridad
***************************************************************************/
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
$sessionvalida = true;//validarSession();
if (($_POST['objdata']) && ($sessionvalida))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/spg/sigesp_dao_spg_estpro5.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php');

	$_SESSION['session_activa']=time();
	$objdata = str_replace("\\","",$_POST['objdata']);	
	$objdata = json_decode($objdata,false);		
	$objEstPre = new EstPro5();		
	pasarDatos(&$objEstPre,$objdata,&$evento);
	$objEstPre->codemp = $_SESSION['la_empresa']['codemp'];	
	$objEstPre->codsis = $objdata->sistema;
	$objEstPre->nomfisico = $objdata->vista;	
	$objSistemaVentana = new SistemaVentana();		
	$objSistemaVentana->codemp = $_SESSION['la_empresa']['codemp'];	
	$objSistemaVentana->codusu = $_SESSION['la_logusr'];	
	$objSistemaVentana->codsis = $objdata->sistema;
	$objSistemaVentana->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	
	switch ($evento)
	{
		case 'cargarTituloGridCat':		
			$nomestpro1 = $_SESSION['la_empresa']['nomestpro1'];
			$nomestpro2 = $_SESSION['la_empresa']['nomestpro2'];
			$nomestpro3 = $_SESSION['la_empresa']['nomestpro3'];	
			$nomestpro4 = $_SESSION['la_empresa']['nomestpro4'];	
			$nomestpro5 = $_SESSION['la_empresa']['nomestpro5'];				
			$arreglo = array ('nivel1'=>$nomestpro1,'nivel2'=>$nomestpro2,'nivel3'=>$nomestpro3,'nivel4'=>$nomestpro4,'nivel5'=>$nomestpro5);			
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
		break;
		
		case 'catalogo':			
			$objSistemaVentana->campo = 'leer';
			$accionvalida=$objSistemaVentana->verificarUsuario();
			if ($accionvalida)
			{
				/*$i=0;
				$objEstPre->criterio[$i]['operador'] = "AND";
				$objEstPre->criterio[$i]['criterio'] = "codestpro1";
				$objEstPre->criterio[$i]['condicion'] = "=";
				$objEstPre->criterio[$i]['valor'] =	"'".$objdata->codestpro1."'";
				$i++;
				
				$objEstPre->criterio[$i]['operador'] = "AND";
				$objEstPre->criterio[$i]['criterio'] = "codestpro2";
				$objEstPre->criterio[$i]['condicion'] = "=";
				$objEstPre->criterio[$i]['valor'] =	"'".$objdata->codestpro2."'";
				$i++;
				
				$objEstPre->criterio[$i]['operador'] = "AND";
				$objEstPre->criterio[$i]['criterio'] = "codestpro3";
				$objEstPre->criterio[$i]['condicion'] = "=";
				$objEstPre->criterio[$i]['valor'] =	"'".$objdata->codestpro3."'";
				$i++;
				
				$objEstPre->criterio[$i]['operador'] = "AND";
				$objEstPre->criterio[$i]['criterio'] = "codestpro4";
				$objEstPre->criterio[$i]['condicion'] = "=";
				$objEstPre->criterio[$i]['valor'] =	"'".$objdata->codestpro4."'";*/
				
				$datos = $objEstPre->leer();
				if ($objEstPre->valido)
				{
					if (!$datos->EOF)
					{
						$varJson=generarJson($datos);
						echo $varJson;				
					}
				}	
				else 
				{	
					$arreglo[0]['mensaje'] = obtenerMensaje('OPERACION_FALLIDA'); 
					$arreglo[0]['valido']  = false;
					$respuesta  = array('raiz'=>$arreglo);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;
				}
			}
			else
			{
				$arreglo[0]['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);			
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}
		break;	
		
		case 'catalogogeneral':
			//$objSistemaVentana->campo = 'leer';
			//$accionvalida=$objSistemaVentana->verificarUsuario();
			//if ($accionvalida)
		//	{				
				$objEstPre->codusu = $_SESSION['la_logusr'];		
				$i=0;
				$objEstPre->criterio[$i]['operador'] = "AND";
				$objEstPre->criterio[$i]['criterio'] = "codestpro1";
				$objEstPre->criterio[$i]['condicion'] = "=";
				$objEstPre->criterio[$i]['valor'] =	"'".$objdata->codestpro1."'";
				$i++;
				
				$objEstPre->criterio[$i]['operador'] = "AND";
				$objEstPre->criterio[$i]['criterio'] = "codestpro2";
				$objEstPre->criterio[$i]['condicion'] = "=";
				$objEstPre->criterio[$i]['valor'] =	"'".$objdata->codestpro2."'";
				$i++;
				
				$objEstPre->criterio[$i]['operador'] = "AND";
				$objEstPre->criterio[$i]['criterio'] = "codestpro3";
				$objEstPre->criterio[$i]['condicion'] = "=";
				$objEstPre->criterio[$i]['valor'] =	"'".$objdata->codestpro3."'";
				$i++;
				
				$objEstPre->criterio[$i]['operador'] = "AND";
				$objEstPre->criterio[$i]['criterio'] = "codestpro4";
				$objEstPre->criterio[$i]['condicion'] = "=";
				$objEstPre->criterio[$i]['valor'] =	"'".$objdata->codestpro4."'";
				
				$datos = $objEstPre->leerGeneral();
				if ($objEstPre->valido)
				{
					if (!$datos->EOF)
					{
						$varJson=generarJson($datos);
						echo $varJson;				
					}
				}	
				else 
				{	
					$arreglo[0]['mensaje'] = obtenerMensaje('OPERACION_FALLIDA'); 
					$arreglo[0]['valido']  = false;
					$respuesta  = array('raiz'=>$arreglo);
					$respuesta  = json_encode($respuesta);
					echo $respuesta;
				}
			/*}
			else
			{
				$arreglo[0]['mensaje'] = obtenerMensaje('ACCION_NO_VALIDA'); 
				$arreglo[0]['valido']  = false;
				$respuesta  = array('raiz'=>$arreglo);			
				$respuesta  = json_encode($respuesta);
				echo $respuesta;
			}*/
	}
	unset($objSistemaVentana);
	unset($objEstPre);
}		
?>