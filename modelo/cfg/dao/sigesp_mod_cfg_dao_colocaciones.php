<?php
require_once ("../../base/librerias/php/general/sigesp_lib_daogenericoplus.php");

class colocacionDao extends DaoGenericoPlus {
	private $objcabecera;
	
	function __construct($tabcabecera,$arrtabdetalles) {
        parent::__construct($tabcabecera,$arrtabdetalles);
    }
    
	function grabarColocacion($arrjson,$codemp){
		$this->setData($arrjson,$codemp);
		$resultado = $this->incluirDto();
		return $resultado;
	}
	
	function getColocaciones($codemp,$numctaban,$dencol,$nomban){
		$cadenasql ="SELECT 
  						scb_colocacion.codban,scb_banco.nomban,scb_colocacion.ctaban,
						scb_colocacion.numcol,scb_colocacion.dencol,scb_colocacion.codtipcol,
						scb_colocacion.feccol,scb_colocacion.diacol,scb_colocacion.tascol,
						scb_colocacion.monto,scb_colocacion.fecvencol,scb_colocacion.monint,
						scb_colocacion.sc_cuenta,scb_colocacion.spi_cuenta,scb_colocacion.estreicol,
						scb_colocacion.sc_cuentacob,scb_colocacion.codestpro1,scb_colocacion.codestpro2,
						scb_colocacion.codestpro3,scb_colocacion.codestpro4,scb_colocacion.codestpro5,
						scb_colocacion.estcla,scb_tipocolocacion.nomtipcol,scg_cuentas.denominacion as scgctadeno,
						scb_ctabanco.dencta,spi_cuentas.denominacion as spictadeno
					FROM 
  						scb_colocacion, 
  						scb_banco, 
  						scb_tipocolocacion, 
  						scg_cuentas, 
  						scb_ctabanco, 
  						spi_cuentas
					WHERE
						scb_colocacion.codemp = '".$codemp."' AND 
  						scb_colocacion.codemp = scb_banco.codemp AND
  						scb_colocacion.codban = scb_banco.codban AND
  						scb_colocacion.codtipcol = scb_tipocolocacion.codtipcol AND
  						scb_colocacion.codemp = scg_cuentas.codemp AND
  						scb_colocacion.sc_cuenta = scg_cuentas.sc_cuenta AND
  						scb_ctabanco.codemp = scb_colocacion.codemp AND
  						scb_ctabanco.codban = scb_colocacion.codban AND
  						scb_ctabanco.ctaban = scb_colocacion.ctaban AND
  						spi_cuentas.codemp = scb_colocacion.codemp AND
  						spi_cuentas.spi_cuenta = scb_colocacion.spi_cuenta AND
						scb_colocacion.ctaban LIKE '%".$numctaban."%' AND
						scb_colocacion.dencol LIKE '%".$dencol."%' AND
						scb_banco.nomban LIKE '%".$nomban."%'";
		$this->objcabecera = $this->getCabecera();
		return $this->objcabecera->buscarSql($cadenasql);
	}
	
	function getDetalleColocacion($codemp,$codban,$ctaban,$numcol){
		$cadenasql = " SELECT 
  							scb_dt_colocacion.fecreint,scb_dt_colocacion.montoreint
					   FROM 
  							scb_dt_colocacion
					   WHERE 
  							scb_dt_colocacion.codemp = '".$codemp."' AND 
  							scb_dt_colocacion.codban = '".$codban."' AND 
  							scb_dt_colocacion.ctaban = '".$ctaban."' AND 
  							scb_dt_colocacion.numcol = '".$numcol."'";
		$this->objcabecera = $this->getCabecera();
		return $this->objcabecera->buscarSql($cadenasql);
	}
	
	function getColocacion($codemp,$codban,$ctaban,$numcol){
		$this->objcabecera = $this->getCabecera();
		$this->objcabecera->codemp=$codemp;
		$this->objcabecera->codban=$codban;
		$this->objcabecera->ctaban=$ctaban;
		$this->objcabecera->numcol=$numcol;
		
		return $this->objcabecera->buscarPk();
		
	}
}
?>