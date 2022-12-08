<?php
require_once ("../../base/librerias/php/general/sigesp_lib_daogenericoplus.php");

class sepconceptoDao extends DaoGenericoPlus {
	private $arraydetalles = null;
	
	public function __construct() {
		$this->arraydetalles[0] = 'esp_sep_conceptocargos';
        parent::__construct('sep_conceptos',$this->arraydetalles);
    }
    
    function grabarConcepto($arrjson,$codemp){
		$this->setData($arrjson,$codemp);
		$resultado = $this->incluirDto();
		return $resultado;
	}
	
	function eliminarConcepto($arrjson,$codemp){
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