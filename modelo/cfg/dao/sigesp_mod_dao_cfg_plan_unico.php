<?php
$dirmodcfgdaoplu = "";
$dirmodcfgdaoplu = dirname(__FILE__);
$dirmodcfgdaoplu = str_replace("\\","/",$dirmodcfgdaoplu);
$dirmodcfgdaoplu = str_replace("/modelo/cfg/dao","",$dirmodcfgdaoplu);
require_once ($dirmodcfgdaoplu."/base/librerias/php/general/sigesp_lib_daogenerico.php");

class planunicoDao extends DaoGenerico
{
	private $conexionbd;
	
	/**
	 * @desc Metodo constructor de la clase, hace uso del constructor de la clase padre
	 * 		 para convertirse en un objeto active record del adodb
	 * @author Ing. Gerardo Cordero
	 */
	public function __construct() {
		parent::__construct ( 'sigesp_plan_unico' );
		$this->conexionbd = $this->obtenerConexionBd(); 
	}
	
	public function LeerTodos()
	{
		$Rs = $this->conexionbd->Execute("select * from {$this->_table}");
		return $Rs;
		
	}
		
}

?>