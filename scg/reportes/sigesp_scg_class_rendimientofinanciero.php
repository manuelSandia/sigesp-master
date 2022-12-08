<?php
class sigesp_scg_class_rendimientofinanciero {
	private $la_empresa;
	private $io_include;
	private $io_conexion;
	private $io_sql;
	
	public function sigesp_scg_class_rendimientofinanciero() {
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
				
		$this->io_include  = new sigesp_include();
		$this->io_conexion = $this->io_include->uf_conectar();
		$this->io_sql      = new class_sql($this->io_conexion);
		$this->la_empresa  = $_SESSION["la_empresa"];
	}
	
	public function uf_rendimiento_financiero($ad_fecdes, $ad_fechas){
		//varibles filtros de la consulta
		$ls_codemp  = $this->la_empresa["codemp"];
		$ls_ingreso = trim($this->la_empresa['ingreso']);
		$ls_gasto   = trim($this->la_empresa['gasto']);
		
		$anoact=substr($ad_fechas,0,4);
		
				if($anoact>1){
						$anoant=$anoact-1;
						$ad_actualo=$anoant."-12-31 00:00:00";
					}else{
				
							$ad_actualo="2010-12-31 00:00:00";
						}
		$ls_sql=  " SELECT SC.sc_cuenta, SC.denominacion, SC.nivel, coalesce(T_Saldo_anterior,0) as saldo_anterior, coalesce(T_Saldo,0) as saldo,  1 as tiporden 
						FROM scg_cuentas SC 
							LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes)-sum(haber_mes),0)as T_Saldo
												FROM scg_saldos 
												WHERE codemp='{$ls_codemp}' AND (fecsal BETWEEN '{$ad_fecdes}' AND  '{$ad_fechas}') GROUP BY codemp,sc_cuenta) curSaldo ON SC.sc_cuenta=curSaldo.sc_cuenta
							LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes)-sum(haber_mes),0)as T_Saldo_anterior
												FROM scg_saldos 
												WHERE codemp='{$ls_codemp}' AND fecsal<='{$ad_actualo}' GROUP BY codemp,sc_cuenta) curSaldo_an ON SC.sc_cuenta=curSaldo_an.sc_cuenta
						WHERE SC.codemp=curSaldo.codemp AND curSaldo.codemp='{$ls_codemp}' AND (SC.sc_cuenta like '{$ls_ingreso}%') AND SC.nivel<=4 AND SC.nivel<>3
					UNION 
					SELECT SC.sc_cuenta, SC.denominacion, SC.nivel, coalesce(T_Saldo_anterior,0) as saldo_anterior, coalesce(T_Saldo,0) as saldo, 2 as tiporden 
						FROM scg_cuentas SC 
							LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes)-sum(haber_mes),0)as T_Saldo
												FROM scg_saldos 
												WHERE codemp='{$ls_codemp}' AND fecsal BETWEEN '{$ad_fecdes}' AND  '{$ad_fechas}' GROUP BY codemp,sc_cuenta) curSaldo ON SC.sc_cuenta=curSaldo.sc_cuenta 
							LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes)-sum(haber_mes),0)as T_Saldo_anterior
												FROM scg_saldos 
												WHERE codemp='{$ls_codemp}' AND fecsal<='{$ad_actualo}' GROUP BY codemp,sc_cuenta) curSaldo_an ON SC.sc_cuenta=curSaldo_an.sc_cuenta
						WHERE SC.codemp=curSaldo.codemp AND curSaldo.codemp='{$ls_codemp}' AND (SC.sc_cuenta like '{$ls_gasto}%') AND SC.nivel<=4 AND SC.nivel<>3
					ORDER BY 6,1";
	
	 	//echo $ls_sql."<br>";
	
     	return $this->io_sql->select($ls_sql);
    }
    
    
}