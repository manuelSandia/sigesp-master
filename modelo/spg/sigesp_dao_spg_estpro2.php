<?php
/***************************************************************************
* @Modelo para las funciones de estructura presupuestaria 2.
* @fecha de creación: 03/10/2008.
* @autor: Ing.Gusmary Balza
***************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registrofallas.php');

class EstPro2 extends ADOdb_Active_Record
{
	var $_table = 'spg_ep2';
	public $valido = true;
	public $mensaje;
	public $seguridad = true;
	public $codsis;
	public $nomfisico;
	public $criterio;
	
	
/***********************************************************************************
* @Función para insertar una estructura presupuestaria.
* @parametros: 
* @retorno:
* @fecha de creación: 03/10/2008.
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function incluir()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->mensaje = 'Incluyo la Estructura Presupuestaria '.$this->codestpro1.$this->codestpro2.$this->estcla;
		try 
		{
			$this->save();
		}		
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje = 'Error al Incluir la Estructura Presupuestaria '.$this->codestpro1.$this->codestpro2.$this->estcla.' '.$conexionbd->ErrorMsg();
		} 
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}
	
	
/***********************************************************************************
* @Función que Busca uno o todas las estructuras presupuestarias de nivel 2
* @parametros: 
* @retorno:
* @fecha de creación: 26/11/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************************/		
	public function leer() 
 	{	
 		global $conexionbd;
 		$conexionbdorigen = conectarBD($_SESSION['sigesp_servidor'], $_SESSION['sigesp_usuario'], $_SESSION['sigesp_clave'],
												 $_SESSION['sigesp_basedatos'], $_SESSION['sigesp_gestor'], $_SESSION['sigesp_puerto']);
 		try
		{	
			$consulta = " SELECT DISTINCT codestpro1,codestpro2,denestpro2,1 as valido ".
						" FROM {$this->_table} ".
						" INNER JOIN sss_permisos_internos ".
						"	ON {$this->_table}.codestpro1=substr(sss_permisos_internos.codintper,1,25) ".
						" 	AND {$this->_table}.codestpro2=substr(sss_permisos_internos.codintper,26,25) ".
						"	AND {$this->_table}.estcla=substr(sss_permisos_internos.codintper,126,1) ".
						" WHERE {$this->_table}.codemp='$this->codemp' AND codusu='$this->codusu' ";
			
			$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;
		 	$result = $conexionbdorigen->Execute($consulta);
		 	return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar la Estructura Presupuestaria '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
	   	} 
 	}
			
	
/***********************************************************************************
* @Función que Incluye el registro de la transacción exitosa
* @parametros: $evento
* @retorno:
* @fecha de creación: 10/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function incluirSeguridad($evento,$tipotransaccion)
	{
		if($tipotransaccion) // Transacción Exitosa
		{
			$objEvento = new RegistroEventos();
		}
		else // Transacción fallida
		{
			$objEvento = new RegistroFallas();
		}
		// Registro del Evento
		$objEvento->codemp = $this->codemp;
		$objEvento->codsis = $this->codsis;
		$objEvento->nomfisico = $this->nomfisico;
		$objEvento->evento = $evento;
		$objEvento->desevetra = $this->mensaje;
		$objEvento->incluir();
		unset($objEvento);
	}	
}	
?>