<?php
require_once ("../../base/librerias/php/general/sigesp_lib_daogenericoplus.php");

class modalidadClausulaDao extends DaoGenericoPlus {
	private $arraydetalles = null;
	
	public function __construct() {
		$this->arraydetalles[0] = 'esp_soc_dtm_clausulas';
        parent::__construct('soc_modalidadclausulas',$this->arraydetalles);
    }
    
    public function getCodigo($codemp) {
    	$this->getCabecera()->codemp=$codemp;
    	return $this->getCabecera()->buscarCodigo('codtipmod',true,2);
    }
    
    
	public function getClausulas($codemp) {
		$objdetalle = $this->getInstaciaDetalle('soc_clausulas');
		return $objdetalle->leerTodos('codcla',1,$codemp);
	} 
	
	public function getModclausulas($codemp) {
		return $this->getCabecera()->leerTodos('codtipmod',0,$codemp);
	}
	
	public function getDetalle($codemp,$codtipmod) {
		$cadenasql  = "SELECT dt.codcla, cl.dencla 
  						FROM soc_dtm_clausulas dt
  						INNER JOIN soc_clausulas cl ON dt.codemp=dt.codemp AND dt.codcla=cl.codcla
  						where dt.codemp='".$codemp."' AND codtipmod='".$codtipmod."'";
		$objdetalle = $this->getInstaciaDetalle('soc_clausulas');
		return $objdetalle->buscarSql($cadenasql);
	}
	
	function grabarModclausula($arrjson,$codemp){
		$this->setData($arrjson,$codemp);
		$resultado = $this->incluirDto();
		return $resultado;
	}
	
	function eliminarModclausula($arrjson,$codemp){
		$this->setData($arrjson,$codemp);
		$resultado = $this->eliminarDto(false);
		return $resultado;
	}
	
	/*public function getColocacion($codemp,$codban,$ctaban,$numcol){
		$this->objcabecera = $this->getCabecera();
		$this->objcabecera->codemp=$codemp;
		$this->objcabecera->codban=$codban;
		$this->objcabecera->ctaban=$ctaban;
		$this->objcabecera->numcol=$numcol;
		
		return $this->objcabecera->buscarPk();
	}*/
}
?>