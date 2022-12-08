<?php
$dirmodcfgdaocue = "";
$dirmodcfgdaocue = dirname(__FILE__);
$dirmodcfgdaocue = str_replace("\\","/",$dirmodcfgdaocue);
$dirmodcfgdaocue = str_replace("/modelo/sss/dao","",$dirmodcfgdaocue);
require_once ($dirmodsssdaousu."/base/librerias/php/general/sigesp_lib_daogenerico.php");

class cuentacontableDao extends DaoGenerico
{
	private $conexionbd = null;
	
	public function __construct() {
		parent::__construct ( 'scg_cuentas' );
		$this->conexionbd = $this->obtenerConexionBd();
	}
	
	public function Modificar()
	{
		$this->conexionbd->StartTrans();
		$this->Replace();
		$this->conexionbd->CompleteTrans();
		return "1";
		
	}
	public function Incluir()
	{
		try
		{
			$this->conexionbd->StartTrans();
			$this->save();
			$this->conexionbd->CompleteTrans();
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
		$this->conexionbd->StartTrans();
		$this->delete();
		$this->conexionbd->CompleteTrans();
		return "1";

	}
	
	public function BuscarCodigo()
	{
		$Rs = $this->conexionbd->Execute("select (sc_cuenta) from {$this->_table}"); 
		var_dump($Rs->fields['sc_cuenta']); 
		if($Rs->fields['sc_cuenta']=='')
		{
			return "0001"; 
		}
		else
		{	
			$dato = $Rs->fields['sc_cuenta'];
			return $dato;
		}
	}
	
	public function LeerTodos()
	{
		$Rs = $this->conexionbd->Execute("select * from {$this->_table}");
		return $Rs;
		
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		$Rs = $this->Find("{$cr} like  '%{$cad}%' ");
		return $Rs;
	}
	
	public function buscar()
	{
		$sql =" SELECT TRIM(sc_cuenta) as sc_cuenta,status,denominacion 
	           	FROM scg_cuentas WHERE codemp = '0001' 
	     	    ORDER BY sc_cuenta ASC";
	 	$rs = $this->conexionbd->Execute($sql);
		return $rs;
	}
	
}
?>