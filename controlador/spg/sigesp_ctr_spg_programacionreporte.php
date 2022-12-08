<?php
session_start(); 
$datosempresa=$_SESSION["la_empresa"];
require_once('../../base/librerias/php/general/Json.php');
require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');

//var_dump($_POST);
if ($_POST['ObjSon']){
	$submit = str_replace("\\","",$_POST['ObjSon']);
	$json = new Services_JSON;	
	$ArJson = $json->decode($submit);
	$Evento = $ArJson->operacion;
	
	
	switch ($Evento){
		case 'cargarcuentas':
			require_once("../../spg/sigesp_spg_class_progrep.php");
			$io_class_progrep = new sigesp_spg_class_progrep('../');
			$ls_codestpro1 = str_pad($ArJson->codest1,25,0,0);
			$ls_codestpro2 = str_pad($ArJson->codest2,25,0,0);
			$ls_codestpro3 = str_pad($ArJson->codest3,25,0,0);
			$ls_codestpro4 = str_pad('',25,0,0);
			$ls_codestpro5 = str_pad('',25,0,0);
			$ls_codrep     = $ArJson->codrep;
			//var_dump($io_class_progrep->validarDatosPlantilla($ls_codrep));
			if ($io_class_progrep->validarDatosPlantilla($ls_codrep)) {
				$io_class_progrep->copiarPresupuesto($ls_codrep);
			}
			$rs_load=$io_class_progrep->buscarCuentas($ls_codrep,$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
	                                               $ls_codestpro4,$ls_codestpro5,$ArJson->estcla);
	        echo generarJson($rs_load,false,false);
	        unset($io_class_progrep);                                    
			break;
			
		case 'procesar':
			require_once("../../spg/sigesp_spg_class_progrep.php");
			$io_class_progrep = new sigesp_spg_class_progrep('../');
			$ls_codrep     = $ArJson->codrep;
			$ls_codestpro1 = str_pad($ArJson->codest1,25,0,0);
			$ls_codestpro2 = str_pad($ArJson->codest2,25,0,0);
			$ls_codestpro3 = str_pad($ArJson->codest3,25,0,0);
			$ls_codestpro4 = str_pad('',25,0,0);
			$ls_codestpro5 = str_pad('',25,0,0);
			$ls_estcla     = $ArJson->estcla;
			
			foreach ($ArJson->cuenta as $cuenta) {
				$insertado = $io_class_progrep->actualizarMontos($ls_codrep, $ls_codestpro1, $ls_codestpro2, $ls_codestpro3, $ls_codestpro4, $ls_codestpro5, $ls_estcla, 
														  $cuenta->spg_cuenta, $cuenta->denominacion, $cuenta->status, $cuenta->sc_cuenta, $cuenta->asignado, 
														  $cuenta->distribuir, $cuenta->enero, $cuenta->febrero, $cuenta->marzo, $cuenta->abril, $cuenta->mayo, 
														  $cuenta->junio, $cuenta->julio, $cuenta->agosto, $cuenta->septiembre, $cuenta->octubre, $cuenta->noviembre, 
														  $cuenta->diciembre, $cuenta->nivel, $cuenta->referencia);
				//var_dump($insertado);
			    if (!$insertado) {
			        	break;
			    }
			}
			
			echo $insertado;
			break; 
	}
}
?>