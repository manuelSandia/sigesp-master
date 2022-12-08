<?php
require_once("../../sigesp_config.php");

class generalDao extends ADOdb_Active_Record
{

	
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
		global $db;
		try
		{
			$db->debug=1;
			$db->StartTrans();
			$this->save();
			if($db->CompleteTrans())
			{
				return "1";
			}
			else
			{
				return "0";
			}
		}
		catch(exception $e)
		{
			//capturar el error y guardarlo en la bd
			return "0";
		}
	}
	
	public function Eliminar()
	{
		global $db;
		try
		{
			$db->StartTrans();
			$this->delete();
			if($db->CompleteTrans())
			{
				return "1";	
			}
			else
			{
				return "0";
			}
			
		}
		catch(exception $e)
		{
			//capturar el error y guardarlo en la bd
			return "0";
		}
	}
	
	public function LeerTodos()
	{
		global $db;
		$Rs = $db->Execute("select * from {$this->_table} where codpai='{$this->codpai}'");
		return $Rs;
	}
	
	public function LeerTodos2()
	{
		global $db;
		//$db->debug=true;
		$Rs = $db->Execute("select * from {$this->_table}");
		return $Rs;
	}
	
	public function LeerEstados()
	{
		global $db;
		//$db->debug=true;
		$sql="select * from {$this->_table} where codpai='{$this->codpai}'";
		$Rs = $db->Execute($sql);
		return $Rs;
	}
	
	public function LeerMunicipios()
	{
		global $db;
		//$db->debug=true;
		$sql="select * from {$this->_table} where codpai='{$this->codpai}' and codest='{$this->codest}'";
		$Rs = $db->Execute($sql);
		return $Rs;
	}
	
	public function LeerParroquias()
	{
		global $db;
		//$db->debug=true;
		$sql="select * from {$this->_table} where codpai='{$this->codpai}' and codest='{$this->codest}' and codmun='{$this->codmun}'";
		$Rs = $db->Execute($sql);
		return $Rs;
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		global $db;
		$Rs = $this->Find("{$cr} like  '%{$cad}%' and {$this->_table}.codemp='{$this->codemp}'");
		return $Rs;
		
	}
	
	
}

?>