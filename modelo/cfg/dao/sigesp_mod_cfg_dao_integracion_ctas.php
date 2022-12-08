<?php
require_once("../../base/librerias/php/general/sigesp_lib_conexion.php");

class integracionctasDao extends ADOdb_Active_Record
{
	var $_table='scg_casa_presu';
	public function Modificar()
	{
		global $db;
		$db->StartTrans();
		$this->Replace();
		$db->CompleteTrans();
		return "1";
		
	}
	public function Incluir()
	{
		try
		{
			global $db;
			$db->StartTrans();
			$this->save();
			$db->CompleteTrans();
			return "1";
		}
		catch (Exception $e) 
		{
			//mandar a un archivo de logs con los eventos fallidos fallidos	
    		return "0";
		}


	}
	public function Eliminar()
	{
		global $db;
		$db->StartTrans();
		$this->delete();
		$db->CompleteTrans();
		return "1";

	}
	
	public function BuscarCodigo()
	{
		global $db;
		$Rs = $db->Execute("select (sig_cuenta) from {$this->_table}"); 
		var_dump($Rs->fields['sig_cuenta']); 
		if($Rs->fields['sig_cuenta']=='')
		{
			return "0001"; 
		}
		else
		{	
			$dato = $Rs->fields['sig_cuenta'];
			return $dato;
		}
	}
	
	public function LeerTodos()
	{
		global $db;
		$Rs = $db->Execute("select * from {$this->_table}");
		return $Rs;
		
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$Rs = $this->Find("{$cr} like  '%{$cad}%' ");
		//var_dump ($Rs);
		return $Rs;
	}
	
	
}
?>