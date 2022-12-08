<?php
$dirmodsepdaosigcon = "";
$dirmodsepdaosigcon = dirname(__FILE__);
$dirmodsepdaosigcon = str_replace("\\","/",$dirmodsepdaosigcon);
$dirmodsepdaosigcon = str_replace("/modelo/cfg/dao","",$dirmodsepdaosigcon);
require_once($dirmodsepdaosigcon."/base/librerias/php/general/sigesp_lib_daogenerico.php");

class SigespConfigDao extends DaoGenerico{

	function __construct() {
		parent::__construct ( 'sigesp_config' );
	}

	public function getFormato($codemp,$codsis,$seccion,$entry,$type){
		/***********************************************************************************
		 * @Descripcion: metodo que obtiene el nombre fisico del formato almacenado en base de
		 * datos para indicar cual debe ser llamado
		 * @Autor: Ing. Gerardo Cordero
		 ************************************************************************************/

		$cadenasql = "SELECT sigesp_config.value
  					  FROM sigesp_config
  					  WHERE sigesp_config.codemp='".$codemp."' AND trim(sigesp_config.codsis)='".$codsis."' 
  					  AND trim(sigesp_config.seccion)='".$seccion."' AND trim(sigesp_config.entry)='".$entry."' 
  					  AND trim(sigesp_config.type)='".$type."'";
		$dataconfig = $this->buscarSql($cadenasql);
		if($dataconfig->_numOfRows>0){
			if ($dataconfig->fields ['value'] == "") {
				return "";
			} else {
				return $dataconfig->fields ['value'];
			}
		}
		else{
			return 0;
		}
	}

	public function insertarConfigReporte($codemp,$codsis,$seccion,$entry,$type,$value,$formato){
		$this->codemp  = $codemp;
		$this->codsis  = $codsis;
		$this->seccion = $seccion;
		$this->entry   = $entry;
		$this->type    = $type;
		$this->value   = $value;
		
		$resultado = $this->incluir();
		
		if($formato==0){
			return $resultado;
		}
		else{
			if($resultado==1){
				return true;
			}else{
				return false;
			}
		}
	}
}
?>