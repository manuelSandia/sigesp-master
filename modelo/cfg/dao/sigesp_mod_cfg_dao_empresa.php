<?php
$dirmodcfgdaoemp = "";
$dirmodcfgdaoemp = dirname(__FILE__);
$dirmodcfgdaoemp = str_replace("\\","/",$dirmodcfgdaoemp);
$dirmodcfgdaoemp = str_replace("/modelo/cfg/dao","",$dirmodcfgdaoemp);
require_once ($dirmodcfgdaoemp."/base/librerias/php/general/sigesp_lib_daogenerico.php");


class EmpresaDao extends DaoGenerico{

	public function __construct() {
		parent::__construct ( 'sigesp_empresa' );
	}
	
	public function getEmpresas($arrdatcon){
		$conexion = $this->getObjetoConexion($arrdatcon);
		$cadenasql = "SELECT * FROM sigesp_empresa";
		$dataempresa = $conexion->execute($cadenasql);
		unset($conexion);
		return $dataempresa;
	}
}


?>