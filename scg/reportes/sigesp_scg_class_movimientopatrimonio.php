<?php
class sigesp_scg_class_movimientopatrimonio {
	private $la_empresa;
	private $io_include;
	private $io_conexion;
	private $io_sql;
	
	public function sigesp_scg_class_movimientopatrimonio() {
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
				
		$this->io_include  = new sigesp_include();
		$this->io_conexion = $this->io_include->uf_conectar();
		$this->io_sql      = new class_sql($this->io_conexion);
		$this->la_empresa  = $_SESSION["la_empresa"];
	}
	
	public function uf_obtener_saldo($fechasaldo, $grupocuenta, $nivel){
		$ld_saldo = 0;
		
		$ls_sql=  " SELECT coalesce(saldo,0) as saldo
						FROM scg_cuentas SC 
						LEFT OUTER JOIN (SELECT codemp, sc_cuenta, coalesce(sum(debe_mes)-sum(haber_mes),0)as saldo
											FROM scg_saldos 
											WHERE codemp='{$this->la_empresa["codemp"]}' AND fecsal<='{$fechasaldo}' GROUP BY codemp,sc_cuenta) curSaldo ON SC.sc_cuenta=curSaldo.sc_cuenta
						WHERE curSaldo.codemp='{$this->la_empresa["codemp"]}' AND SC.codemp=curSaldo.codemp AND (SC.sc_cuenta like '{$grupocuenta}%') AND SC.nivel={$nivel}";
	
	 	//echo $ls_sql."<br>";
		
     	$data = $this->io_sql->select($ls_sql);
     	if($data != false){
     		if($row=$this->io_sql->fetch_row($data)){
     			$ld_saldo = $row["saldo"];
			}
     	}
     	
     	return $ld_saldo;
    }
    
    public function uf_obtener_movimiento($fecha) {
    	
    	
    	$ls_sql=  "SELECT sc_cuenta,  sum(case  debhab when 'H' then monto else -monto end) as monto FROM scg_dt_cmp 
WHERE sc_cuenta like '3%' and fecha<='{$fecha}' 
group by 1
ORDER BY sc_cuenta";
    	
    	//echo $ls_sql."<br>";
	
     	return $this->io_sql->select($ls_sql);
    }
    
    
 public function uf_buscar_ganancia($fecha) {
    	$ld_ganancia = 0;
    	$ld_ingreso  = $this->uf_buscar_ingreso($fecha);
    	$ld_gasto    = $this->uf_buscar_gastos($fecha);
    	$ld_ganancia = $ld_ingreso-$ld_gasto;	 
    	
    	return $ld_ganancia;
    	
    }
    
    public function uf_buscar_ingreso($fecha) {
    	$ad_saldo   = 0;
    	$ai_ingreso = trim($this->la_empresa['ingreso']);
    	$ls_sql= " SELECT COALESCE(sum(SD.haber_mes-SD.debe_mes),0) as saldo ".
                 " FROM   scg_cuentas SC, scg_saldos SD ".
                 " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND (SC.status='C') AND ".
			     "        fecsal<='".$fecha."' AND (SC.sc_cuenta like '".$ai_ingreso."%') AND SC.sc_cuenta IN (SELECT DISTINCT sc_cuenta FROM scg_dt_cmp WHERE fecha <= '".$fecha."' AND sc_cuenta LIKE '".$ai_ingreso."%')";
    	$rs_data=$this->io_sql->select($ls_sql);
    	
	 	if($rs_data===false){// error interno sql
	 		$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_ingreso_BG ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido = false;
	 	}
	 	else{
	 		if($row=$this->io_sql->fetch_row($rs_data)){
	 			$ad_saldo=$row["saldo"];
			}
			$this->io_sql->free_result($rs_data);
	 	}
	 	
	 	return $ad_saldo;
    }
    
    public function uf_buscar_gastos($fecha) {
    	$ad_saldo = 0;
    	$ai_gasto = trim($this->la_empresa['gasto']);
    	$ls_sql=" SELECT COALESCE(sum(SD.debe_mes-SD.haber_mes),0) as saldo ".
                " FROM   scg_cuentas SC, scg_saldos SD ".
             " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND (SC.status='C') AND ".
			 "        fecsal<='".$fecha."' AND (SC.sc_cuenta like '".$ai_gasto."%') AND SC.sc_cuenta IN (SELECT DISTINCT sc_cuenta FROM scg_dt_cmp WHERE fecha <= '".$fecha."' AND sc_cuenta LIKE '".$ai_gasto."%')";
    	$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false){// error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_gasto_BG ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido = false;
	 	}
	 	else{
			if($row=$this->io_sql->fetch_row($rs_data)){
				$ad_saldo=$row["saldo"];
			}
			$this->io_sql->free_result($rs_data);
	 	}
	 	
	 	return $ad_saldo;
    }
    
    
}