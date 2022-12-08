<?php
$dirmodcfgdaopur = "";
$dirmodcfgdaopur = dirname(__FILE__);
$dirmodcfgdaopur = str_replace("\\","/",$dirmodcfgdaopur);
$dirmodcfgdaopur = str_replace("/modelo/cfg/dao","",$dirmodcfgdaopur);
require_once ($dirmodcfgdaopur."/base/librerias/php/general/sigesp_lib_daogenerico.php");

class planunicoreDao extends DaoGenerico
{
	private $conexionbd = null;
	
	public function __construct() {
		parent::__construct ( 'sigesp_plan_unico_re' );
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
		$Rs = $this->conexionbd->Execute("select (sig_cuenta) from {$this->_table}"); 
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
		$Rs = $this->conexionbd->Execute("select * from {$this->_table} order by (sig_cuenta)");
		return $Rs;
		
	}
	
	public function LeerPorCadena($cr,$cad)
	{
		
		$Rs = $this->Find("{$cr} like  '%{$cad}%' ");
		return $Rs;
	}
	
	
}
?>