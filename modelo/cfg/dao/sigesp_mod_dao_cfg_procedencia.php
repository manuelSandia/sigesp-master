<?php

require_once("../../sigesp_config.php");

class procedenciaDao extends ADOdb_Active_Record

{
	var $_table='sigesp_procedencias';
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
		$Rs = $db->Execute("select (procede) from {$this->_table}"); 
		var_dump($Rs->fields['procede']); 
		if($Rs->fields['procede']=='')
		{
			return "0001"; 
		}
		else
		{	
			$dato = $Rs->fields['procede'];
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
		return $Rs;
	}
	
}

?>
