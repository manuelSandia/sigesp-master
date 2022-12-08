<?php

require_once("../../base/librerias/php/general/sigesp_lib_conexion.php");

class sistemaDao extends ADOdb_Active_Record
{
	var $_table='sss_sistemas';
	
	
	public function LeerTodos()
	{
		global $db;
		$Rs = $db->Execute("select * from {$this->_table}");
		return $Rs;
		
	}
		
}

?>