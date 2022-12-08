<?php
class sigesp_scg_class_situacionfinanciera {
	private $la_empresa;
	private $io_include;
	private $io_conexion;
	private $io_sql;
	
	public function sigesp_scg_class_situacionfinanciera() {
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		/*require_once("../../shared/class_folder/class_sigesp_int.php");
		require_once("../../shared/class_folder/class_sigesp_int_scg.php");*/
		
		$this->io_include  = new sigesp_include();
		$this->io_conexion = $this->io_include->uf_conectar();
		$this->io_sql      = new class_sql($this->io_conexion);
		$this->la_empresa  = $_SESSION["la_empresa"];
	}
	
	public function uf_situacion_financiera($ad_fecfin){
		//varibles filtros de la consulta
		$ls_codemp    = $this->la_empresa["codemp"];
		$ls_activo    = trim($this->la_empresa["activo"]);
		$ls_pasivo    = trim($this->la_empresa["pasivo"]);
		$ls_capital   = trim($this->la_empresa["capital"]);
		$ls_resultado = trim($this->la_empresa["resultado"]);
		$ls_orden_d   = trim($this->la_empresa["orden_d"]);
		$ls_orden_h   = trim($this->la_empresa["orden_h"]);
		
		
		$anoact=substr($ad_fecfin,0,4);
		$anoant=abs($anoact-1);
		$anoh=$anoant."-12-31 00:00:00";
		
		$ls_sql=  " SELECT SC.sc_cuenta, SC.denominacion, SC.nivel, coalesce(T_Saldo_anterior,0) as saldo_anterior, coalesce(T_Saldo,0) as saldo,  1 as tiporden 
						FROM scg_cuentas SC 
							LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes)-sum(haber_mes),0)as T_Saldo
												FROM scg_saldos 
												WHERE codemp='{$ls_codemp}' AND fecsal<='{$ad_fecfin}' GROUP BY codemp,sc_cuenta) curSaldo ON SC.sc_cuenta=curSaldo.sc_cuenta
							LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes)-sum(haber_mes),0)as T_Saldo_anterior
												FROM scg_saldos 
												WHERE codemp='{$ls_codemp}' AND fecsal<='{$anoh}' GROUP BY codemp,sc_cuenta) curSaldo_an ON SC.sc_cuenta=curSaldo_an.sc_cuenta
						WHERE SC.codemp=curSaldo.codemp AND curSaldo.codemp='{$ls_codemp}' AND (SC.sc_cuenta like '{$ls_activo}%') AND SC.nivel<=4 AND SC.nivel<>3
					UNION 
					SELECT SC.sc_cuenta, SC.denominacion, SC.nivel, coalesce(T_Saldo_anterior,0) as saldo_anterior, coalesce(T_Saldo,0) as saldo, 2 as tiporden 
						FROM scg_cuentas SC 
							LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes)-sum(haber_mes),0)as T_Saldo
												FROM scg_saldos 
												WHERE codemp='{$ls_codemp}' AND fecsal<='{$ad_fecfin}' GROUP BY codemp,sc_cuenta) curSaldo ON SC.sc_cuenta=curSaldo.sc_cuenta 
							LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes)-sum(haber_mes),0)as T_Saldo_anterior
												FROM scg_saldos 
												WHERE codemp='{$ls_codemp}' AND fecsal<='{$anoh}' GROUP BY codemp,sc_cuenta) curSaldo_an ON SC.sc_cuenta=curSaldo_an.sc_cuenta
						WHERE SC.codemp=curSaldo.codemp AND curSaldo.codemp='{$ls_codemp}' AND (SC.sc_cuenta like '{$ls_pasivo}%' OR SC.sc_cuenta like '{$ls_resultado}%' OR SC.sc_cuenta like '{$ls_capital}%') AND SC.nivel<=4 AND SC.nivel<>3
					 
					ORDER BY 6,1";
	//echo "<br>".$ls_sql;
	
     	return $this->io_sql->select($ls_sql);
    }
    
    public function uf_buscar_ganancia($fecha) {
    	$ld_ganancia = 0;
    	$ld_ingreso  = $this->uf_buscar_ingreso($fecha);
    	$ld_gasto    = $this->uf_buscar_gastos($fecha);
    	$ld_ganancia = abs($ld_ingreso-$ld_gasto);	 
    	
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