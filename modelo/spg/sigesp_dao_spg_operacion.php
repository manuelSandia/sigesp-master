<?php
/****************************************************************************
* @Modelo para las funciones de operaciones.
* @fecha de creaci?n: 01/10/2008.
* @autor: Ing.Gusmary Balza
*****************************************************************************
* @fecha modificaci?n:
* @descripci?n:
* @autor:
****************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registrofallas.php');

class Operacion extends ADOdb_Active_Record
{
	var $_table = 'spg_operaciones';
	public $valido = true;
	public $mensaje;
	public $seguridad = true;
	public $codsis;
	public $nomfisico;
	
	
/***********************************************************************************
* @Funci?n para insertar una operacion.
* @parametros: 
* @retorno:
* @fecha de creaci?n: 01/10/2008.
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificaci?n:
* @descripci?n:
* @autor:
***********************************************************************************/			
	function incluir()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->mensaje='Incluyo la Operaci?n '.$this->operacion;
		try
		{
			$this->save();
		}		
		catch (exception $e)
	   	{
			$this->valido  = false;				
			$this->mensaje='Error al Incluir la Operaci?n '.$this->operacion.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido); 
	}
	
	
/***********************************************************************************
* @Funci?n que Incluye el registro de la transacci?n exitosa
* @parametros: $evento
* @retorno:
* @fecha de creaci?n: 10/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificaci?n:
* @descripci?n:
* @autor:
***********************************************************************************/
	function incluirSeguridad($evento,$tipotransaccion)
	{
		if($tipotransaccion) // Transacci?n Exitosa
		{
			$objEvento = new RegistroEventos();
		}
		else // Transacci?n fallida
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