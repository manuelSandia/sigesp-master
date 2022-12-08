<?php
class sigesp_scg_class_bal_general
{
	var $la_empresa;
	var $io_fun;
	var $io_sql;
	var $io_sql_aux;
	var $io_msg;
	var $int_scg;
	var $ds_reporte;
	var $ds_prebalance;
	var $ds_balance1;
	var $ds_cuentas;
	var $ds_cuentas_acreedoras;
	var $ia_niveles;
	var $io_fecha;
	var $ls_gestor;
	var $int_spi;
	var $int_spg;
	var $ls_activo;
	var $ls_pasivo;
	var $ls_resultado;
	var $ls_cta_resultado;
	var $ls_capital;
	var $ls_ingreso;
	var $ls_gastos;
	var $ls_orden_d;
	var $ls_orden_h;
	var $ds_ctas_temp;
	var $ls_cuedepamo;

	function sigesp_scg_class_bal_general()
	{
		require_once("../../shared/class_folder/class_sql.php");
		require_once("../../shared/class_folder/sigesp_include.php");
		require_once("../../shared/class_folder/class_sigesp_int.php");
		require_once("../../shared/class_folder/class_sigesp_int_scg.php");
		$this->io_fun = new class_funciones();
		$this->siginc=new sigesp_include();
		$this->con=$this->siginc->uf_conectar();
		$this->io_sql= new class_sql($this->con);
		$this->io_sql_aux= new class_sql($this->con);
		$this->io_msg= new class_mensajes();
		$this->io_fecha=new class_fecha();
		$this->la_empresa=$_SESSION["la_empresa"];
		$this->ds_reporte=new class_datastore();
		$this->ds_Prebalance=new class_datastore();
		$this->ds_Prebalance1=new class_datastore();
		$this->ds_Prebalance2=new class_datastore();
		$this->ds_Prebalance3=new class_datastore();
		$this->ds_Prebalance4=new class_datastore();
		$this->ds_Prebalance5=new class_datastore();
		$this->ds_Prebalance6=new class_datastore();
		$this->ds_reg_niveles=new class_datastore();
		$this->ds_Balance1=new class_datastore();
		$this->ds_cuentas=new class_datastore();
		$this->ds_reporte=new class_datastore();
		$this->ds_reportef=new class_datastore();
		$this->ds_cuentas_acreedoras=new class_datastore();
		$this->ds_ctas_temp=new class_datastore();
		$this->int_scg=new class_sigesp_int_scg();
		$this->ls_gestor = $_SESSION["ls_gestor"];
		$this->ia_niveles=array();
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////************************************BALANCE GENERAL*************************************************////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function uf_escalera_cuenta($cuenta)
	{
		$formato = $this->la_empresa["formcont"];
		$auxArr = explode("-",$formato);
		$auxtam  =0;
		$j = 0;
		$li_total = count($auxArr);
		for($i=0;$i<$li_total;$i++)
		{
			if(strlen($auxArr[$i])>0)
			{
				$auxtam+=strlen($auxArr[$i]);
				$arrEsc[$j] = str_pad(substr($cuenta,0,$auxtam),strlen($cuenta),"0");
				$j++;
			}
		}
		return $arrEsc;
	}

	function uf_balance_general_fonpyme($ld_fecsal) {
		$ls_sql="SELECT SC.sc_cuenta,SC.denominacion,SC.nivel,coalesce(curSaldo.debe_mes,0) as debe,coalesce(curSaldo.haber_mes,0) as haber,coalesce(curSaldo.saldo,0) as saldo
					FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta,sum(debe_mes)as debe_mes,sum(haber_mes)as haber_mes, sum(debe_mes-haber_mes) as saldo
															FROM   scg_saldos 
															WHERE  codemp='".$this->la_empresa["codemp"]."' AND fecsal <= '".$ld_fecsal."' 
															GROUP BY codemp,sc_cuenta) curSaldo
					ON SC.sc_cuenta=curSaldo.sc_cuenta AND SC.codemp=curSaldo.codemp
					WHERE  SC.codemp='".$this->la_empresa["codemp"]."' AND SC.nivel<=3
					ORDER BY sc_cuenta";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false){
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		}
		return $rs_data;
	}

	function uf_obtener_capital($ld_fecsal,$ls_codigo) {
		$ls_sql="SELECT SC.sc_cuenta,SC.denominacion,SC.nivel,coalesce(curSaldo.saldo,0) as saldo
					FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, sum(haber_mes-debe_mes) as saldo
															FROM   scg_saldos 
															WHERE  codemp='".$this->la_empresa["codemp"]."' AND fecsal <= '".$ld_fecsal."' 
															GROUP BY codemp,sc_cuenta) curSaldo
					ON SC.sc_cuenta=curSaldo.sc_cuenta AND SC.codemp=curSaldo.codemp
					WHERE  SC.codemp='".$this->la_empresa["codemp"]."' AND (SC.sc_cuenta like '3110101%' AND  SC.sc_cuenta like '%".$ls_codigo."') AND SC.nivel=9
					ORDER BY sc_cuenta";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false){
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		}
			
		$ld_total=0;
			
		while(!$rs_data->EOF){
			$ld_total=$ld_total+$rs_data->fields["saldo"];
			$rs_data->MoveNext();
		}
		$rs_data->Close();
		return $ld_total;
	}

	function regularDecimal($numero) {
		$inicio      = strpos($numero,'.');
		if($inicio>0){
			$decimales   = substr($numero,$inicio+1,strlen($numero));
			$entero      = substr($numero,0,$inicio);
			$nuevonumero = $entero.'.'.substr($decimales,0,2);
		}else{
			$nuevonumero=$numero;
		}		if(settype($nuevonumero,"float")){
			return $nuevonumero;
		}
		else{
			return 0;
		}
	}

	// Funcion modificada por Ofimatica de Venezuela el 10-08-2011, a nivel de la consulta para la emision del Balance General, ya que al momento
	// de crear una cuenta contable y casarla con una cuenta de provision o reserva tecnica o depreciacion acum o amortizacion acumulada, al guardar
	// las cuentas totalizadoras heredan este casamiento y al momento de emitir el reporte reflejaba dicho casamiento para las cuentas totalizadoras,    // siendo esto incorrecto, ya que ese casamiento solo se debe reflejar para las cuentas de movimientos.
	//
	// Para ello se agrego un CASE que soluciona el problema de asignacion de valor del campo cueproacu, si la cuenta es totalizadora se le asigna el
	// valor '' y si es de movimiento conserva el valor asignado por el usuario.
	//
	function uf_balance_general($ad_fecfin,$ai_nivel)
	{
		$lb_valido=true;
		$ds_Balance2=new class_datastore();
		$ldec_resultado=0;
		$ld_saldo_ganancia=0;
		$this->ls_activo=trim($this->la_empresa["activo"]);
		$this->ls_pasivo=trim($this->la_empresa["pasivo"]);
		$this->cuentaresultado = trim($this->la_empresa["c_resultad"]);
		$this->criteriores = substr($this->cuentaresultado,0,5);
		//	$this->criteriores = array(substr($this->cuentaresultado,0,5));
		$arrCuenta = $this->uf_escalera_cuenta($this->cuentaresultado);
		$this->ls_resultado=trim($this->la_empresa["resultado"]);
		$this->ls_capital=trim($this->la_empresa["capital"]);
		$this->ls_orden_d=trim($this->la_empresa["orden_d"]);
		$this->ls_orden_h=trim($this->la_empresa["orden_h"]);
		$this->ls_ingreso=trim($this->la_empresa["ingreso"]);
		$this->ls_gastos =trim($this->la_empresa["gasto"]);
		$this->ls_cta_resultado = trim($this->la_empresa["c_resultad"]);
		$this->ls_cuedepamo = trim($this->la_empresa["cuedepamo"]);
		$ad_fecfin=$this->io_fun->uf_convertirdatetobd($ad_fecfin);
		$ls_codemp=$this->la_empresa["codemp"];
		$as_sc_cuenta='';
		$as_denominacion='';
		$as_status='';
		$as_rnivel='';
		$ad_total_debe='';
		$ad_total_haber='';
		$ls_cadena_filtro = "";
		if(!empty($this->ls_cuedepamo))
		{
			$la_totcuedepamo = array();
			$la_hijcuedepamo = array();
			$la_famcuedepamo = array();
			$this->uf_obtener_total_cuedepamo($la_totcuedepamo,$la_hijcuedepamo,$ad_fecfin);
			$this->uf_obtener_familia_cuedepamo($la_famcuedepamo);
			$ls_cadena_pasivo = $this->uf_obtener_cuentas_pasivo_omitir($la_totcuedepamo,$la_hijcuedepamo,$la_famcuedepamo);
			if(!empty($ls_cadena_pasivo))
			{
				$ls_cadena_filtro = "AND SC.sc_cuenta NOT IN (".$ls_cadena_pasivo.")";
			}
		}

		if($_SESSION["ls_gestor"]=='INFORMIX')
		{
			$ls_sql="SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel,
                             (select case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' AND sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe,
                             (select case sum(haber_mes) when null then 0 else sum(haber_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' AND sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber,
                             0 as nivel,SC.cueproacu  
                      FROM scg_cuentas SC 
                      where  (SC.sc_cuenta like '".$this->ls_activo."%' OR SC.sc_cuenta like '".$this->ls_pasivo."%' 
                           OR SC.sc_cuenta like '".$this->ls_resultado."%' OR SC.sc_cuenta like '".$this->ls_capital."%' 
                           OR SC.sc_cuenta like '".$this->ls_orden_d."%' OR SC.sc_cuenta like '".$this->ls_orden_h."%') 
                      ORDER BY SC.sc_cuenta ";
		}
		else
		{
			/* $ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
			 "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
			 "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,trim(SC.cueproacu) as cueproacu ".
			 " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
			 "                                             coalesce(sum(haber_mes),0) as T_Haber ".
			 "                                      FROM   scg_saldos ".
			 "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
			 "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
			 " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
			 " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
			 "       (SC.sc_cuenta like '".$this->ls_activo."%' OR SC.sc_cuenta like '".$this->ls_pasivo."%' OR ".
			 "        SC.sc_cuenta like '".$this->ls_resultado."%' OR  SC.sc_cuenta like '".$this->ls_capital."%' OR ".
			 "        SC.sc_cuenta like '".$this->ls_orden_d."%' OR SC.sc_cuenta like '".$this->ls_orden_h."%') ".
			 " ORDER BY trim(SC.sc_cuenta) "; */

			// Consulta modificada por Ofimatica de Venezuela el 10-08-2011, para agregar el CASE para el manejo del valor del campo
			// cueproacu
			$ls_sql=  " SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
					  "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
					  "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,(CASE SC.status WHEN 'S' THEN '' WHEN 'C' THEN (SC.cueproacu) END) as cueproacu, 1 as tiporden ".
					  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
					  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
					  "                                      FROM   scg_saldos ".
					  "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
					  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
					  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
					  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
					  "       (SC.sc_cuenta like '".$this->ls_activo."%' OR SC.sc_cuenta like '".$this->ls_orden_d."%') ".
					  "UNION".
					  " SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
					  "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
					  "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,(CASE SC.status WHEN 'S' THEN '' WHEN 'C' THEN (SC.cueproacu) END) as cueproacu, 2 as tiporden ".
					  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
					  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
					  "                                      FROM   scg_saldos ".
					  "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
					  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
					  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
					  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
					  "       (SC.sc_cuenta like '".$this->ls_pasivo."%' OR SC.sc_cuenta like '".$this->ls_resultado."%' OR  SC.sc_cuenta like '".$this->ls_capital."%' )".
			//"        SC.sc_cuenta like '".$this->ls_orden_h."%') ".
					  " AND SC.sc_cuenta NOT IN (SELECT TRIM(cueproacu) FROM scg_cuentas WHERE cueproacu <> '') ".$ls_cadena_filtro.
					  " AND SC.sc_cuenta NOT IN(SELECT cueproacu FROM scg_CUENTAS        SC                  WHERE codemp='".$ls_codemp."'  AND   cueproacu!=''   AND  status='C' )	AND SC.sc_cuenta not like '225%'			  ".
					  " ORDER BY 9,1";
			// Fin de la modificacion de la consulta.
		}
		// echo $ls_sql."<br>";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{// error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
			//print $this->io_sql->message;
			$lb_valido = false;
		}
	 else
	 {
	 	$ld_saldo_ganancia=0;
	 	$restodepre=0;
	 	$saldo6=0;
	 	$saldo5=0;
	 	$saldo4=0;
	 	$saldo3=0;
	 	$saldo2=0;
	 	$saldo1=0;
	 	$previosaldo6=0;
	 	$previosaldo5=0;
	 	$previosaldo4=0;
	 	$previosaldo3=0;
	 	$previosaldo2=0;
	 	$previosaldo1=0;

	 	//while($row=$this->io_sql->fetch_row($rs_data))
	 	while(!$rs_data->EOF)
	 	{
		  $ls_sc_cuenta=$rs_data->fields["sc_cuenta"];
		  $ls_denominacion=$rs_data->fields["denominacion"];
		  $ls_status=$rs_data->fields["status"];
		  $ls_rnivel=$rs_data->fields["rnivel"];
		  $ld_total_debe=$rs_data->fields["total_debe"];
		  $ld_total_haber=$rs_data->fields["total_haber"];
		  $ls_cueproacu = trim($rs_data->fields["cueproacu"]);


		  $ls_nivel=$ls_rnivel;

		  // echo "<br>aaaa".$ai_nivel;
		  //else
		  //  if($ls_nivel<=$ai_nivel)
		  //{
		  $this->ds_Prebalance->insertRow("sc_cuenta",$ls_sc_cuenta);          // print "ls_sc_cuenta  :  $ls_sc_cuenta <br> " ;
		  $this->ds_Prebalance->insertRow("denominacion",$ls_denominacion);
		  $this->ds_Prebalance->insertRow("status",$ls_status);
		  $this->ds_Prebalance->insertRow("nivel",$ls_nivel);
		  $this->ds_Prebalance->insertRow("rnivel",$ls_rnivel);
		  $this->ds_Prebalance->insertRow("total_debe",$ld_total_debe);
		  $this->ds_Prebalance->insertRow("total_haber",$ld_total_haber);
		  $this->ds_Prebalance->insertRow("saldodepre",'');
		  //echo "<br>".$ls_sc_cuenta."   ".$ls_rnivel."   ".$ls_nivel;
		  //echo "<br>saldo2 ".$saldo2."    ".$sssaldo;
		  $sssaldo=$ld_total_debe-$ld_total_haber;

		  $sssaldo2=0;
		  if($sssaldo<0)
		  {
		  	$sssaldo2 = abs($sssaldo);
		  	$this->ds_Prebalance->insertRow("saldo",-$sssaldo2);
		  }else{
		  	$sssaldo2=$sssaldo;
		  	$this->ds_Prebalance->insertRow("saldo",$sssaldo2);
		  }

		  if($ls_rnivel=="7"){
		  	if ($ls_cueproacu!=='')
		  	{
		  		$this->ds_Prebalance->insertRow("posicion","8");
		  		$restodepre=$sssaldo;
		  	}else{
		  		$this->ds_Prebalance->insertRow("posicion",$ls_rnivel);
		  	}
		  	$saldo6=$saldo6+$sssaldo;
	 	  $saldo5=$saldo5+$sssaldo;
	 	  $saldo4=$saldo4+$sssaldo;
	 	  $saldo3=$saldo3+$sssaldo;
	 	  $saldo2=$saldo2+$sssaldo;
	 	  $saldo1=$saldo1+$sssaldo;
	 	  	
		  }else{
		  	$this->ds_reporte->insertRow("posicion",$ls_rnivel);
		  	if($ls_rnivel=="6"){
		  		if($previosaldo6>0){
			  		$presaldo6=0;
			  		if($saldo6<0)
			  		{
			  			$presaldo6 = abs($saldo6);
			  			$this->ds_Prebalance6->insertRow("saldo6",-$presaldo6);


			  		}else{
			  			$presaldo6=$saldo6;
			  			$this->ds_Prebalance6->insertRow("saldo6",$presaldo6);

			  		}
			  		// echo "<br>".$this->ds_Prebalance6->getRowCount("saldo6")."hh ".$presaldo6." 66  ".$sssaldo ;;
			  		 
			  		$saldo6=0;
			  	}else{
			  		$previosaldo6=1;
			  	}}
			  	if($ls_rnivel=="5"){
			  		if($previosaldo5>0){
			  			$presaldo5=0;
			  			if($saldo5<0)
			  			{
			  				$presaldo5 = abs($saldo5);
			  				$this->ds_Prebalance5->insertRow("saldo5",-$presaldo5);
			  			}else{
			  				$presaldo5=$saldo5;
			  				$this->ds_Prebalance5->insertRow("saldo5",$presaldo5);
			  			}
			  			// echo "<br>".$this->ds_Prebalance5->getRowCount("saldo5")."aa ".$presaldo5." 55  ".$sssaldo ;;

			  			$saldo5=0;
			  			//$saldo6=0;
			  		}else{
			  			$previosaldo5=1;
			  		}}
			  		if($ls_rnivel=="4"){
			  			if($previosaldo4>0){
			  				$presaldo4=0;
			  				if($saldo4<0)
			  				{
			  					$presaldo4 = abs($saldo4);
			  					$this->ds_Prebalance4->insertRow("saldo4",-$presaldo4);
			  				}else{
			  					$presaldo4=$saldo4;
			  					$this->ds_Prebalance4->insertRow("saldo4",$presaldo4);
			  				}
			  				// echo "<br>".$this->ds_Prebalance4->getRowCount("saldo4")."bb ".$presaldo4." 44  ".$sssaldo ;;
			  				 
			  				$saldo4=0;
			  				//$saldo5=0;
			  				//$saldo6=0;
			  			}else{
			  				$previosaldo4=1;
			  			}}
			  			if($ls_rnivel=="3"){
			  				if($previosaldo3>0){
			  					$presaldo3=0;
			  					if($saldo3<0)
			  					{
			  						$presaldo3 = abs($saldo3);
			  						$this->ds_Prebalance3->insertRow("saldo3",-$presaldo3);
			  					}else{
			  						$presaldo3=$saldo3;
			  						$this->ds_Prebalance3->insertRow("saldo3",$presaldo3);
			  					}

			  					// echo "<br>".$this->ds_Prebalance3->getRowCount("saldo3")."cc ".$presaldo3." 33  ".$sssaldo ;;

			  					$saldo3=0;
			  					//$saldo4=0;
			  					//$saldo5=0;
			  					//$saldo6=0;
			  				}else{
			  					$previosaldo3=1;
			  				}}
			  				if($ls_rnivel=="2"){

			  					if($previosaldo2>0){
			  						$presaldo2=0;
			  						if($saldo2<0)
			  						{
			  							$presaldo2 = abs($saldo2);
			  							$this->ds_Prebalance2->insertRow("saldo2",-$presaldo2);
			  						}else{
			  							$presaldo2=$saldo2;
			  							$this->ds_Prebalance2->insertRow("saldo2",$presaldo2);
			  						}
			  						//echo "<br>".$this->ds_Prebalance2->getRowCount("saldo2")." dd ".$presaldo2." 22  ".$sssaldo ;;
			  						 
			  						$saldo2=0;
			  						//	$saldo3=0;
			  						//	$saldo4=0;
			  						//	$saldo5=0;
			  						//$saldo6=0;
			  					}else{
			  						$previosaldo2=1;
			  					}}
			  					if($ls_rnivel=="1"){
			  						//echo "<br>saldo1".$saldo1."    ".$sssaldo;
			  						if($previosaldo1>0){
			  							$presaldo1=0;
			  							if($saldo1<0)
			  							{
			  								$presaldo1 = abs($saldo1);
			  								$this->ds_Prebalance1->insertRow("saldo1",-$presaldo1);
			  							}else{
			  								$presaldo1=$saldo1;
			  								$this->ds_Prebalance1->insertRow("saldo1",$presaldo1);
			  							}

			  							//echo "<br>".$this->ds_Prebalance1->getRowCount("saldo1")."ee ".$presaldo1." 11  ".$sssaldo ;;
			  							 
			  							$saldo1=0;
			  							//$saldo2=0;
			  							//$saldo3=0;
			  							//$saldo4=0;
			  							//$saldo5=0;
			  							//$saldo6=0;
			  						}
			  						else{
			  							$previosaldo1=1;
			  						}}
		  }
		  $this->ds_Prebalance->insertRow("cueproacu",0);
		  $lb_valido = true;
		  //  }//if
		  if ($ls_cueproacu!=='')
		  {
		  	$lb_valido2 = $this->uf_scg_cueproacu_saldo($ls_cueproacu,$ad_fecfin,&$as_sc_cuenta,&$as_denominacion,&$as_status ,&$as_rnivel,&$ad_total_debe,&$ad_total_haber );
		  	if($lb_valido2)
		  	{
		  		if($as_status=="C")
		  		{
		  			$ls_nivel=$ls_rnivel;

		  		}//if
		  		else
		  		{
		  			$ls_nivel=$ls_rnivel;
		  		}//else
		  		//if($ls_nivel<=$ai_nivel)
		  		//	{
		  		$this->ds_Prebalance->insertRow("sc_cuenta",$as_sc_cuenta);
		  		$this->ds_Prebalance->insertRow("denominacion",$as_denominacion);
		  		$this->ds_Prebalance->insertRow("status",$as_status);
		  		$this->ds_Prebalance->insertRow("nivel",$ls_nivel);
		  		$this->ds_Prebalance->insertRow("rnivel",$as_rnivel);
		  		$this->ds_Prebalance->insertRow("total_debe",$ad_total_debe);
		  		$this->ds_Prebalance->insertRow("total_haber",$ad_total_haber);
		  		$this->ds_Prebalance->insertRow("posicion","8");
		  		/*  $this->ds_reporte->insertRow("saldo6",'');
					  $this->ds_reporte->insertRow("saldo5",'');
			  		$this->ds_reporte->insertRow("saldo4",'');
			  		$this->ds_reporte->insertRow("saldo3",'');
			  		$this->ds_reporte->insertRow("saldo2",'');
			  		$this->ds_reporte->insertRow("saldo1",'');*/

		  		$sssaldo=$ad_total_debe-$ad_total_haber;
		  		$saldo6=$saldo6+$sssaldo;
		  		$saldo5=$saldo5+$sssaldo;
		  		$saldo4=$saldo4+$sssaldo;
		  		$saldo3=$saldo3+$sssaldo;
		  		$saldo2=$saldo2+$sssaldo;
		  		$saldo1=$saldo1+$sssaldo;
		  		$restodepre=$restodepre+$sssaldo;
		  		$sssaldo2=0;
		  		if($sssaldo<0)
		  		{
		  			$sssaldo2 = abs($sssaldo);
		  			$this->ds_Prebalance->insertRow("saldo",-$sssaldo2);
		  		}else{
		  			$sssaldo2=$sssaldo;
		  			$this->ds_Prebalance->insertRow("saldo",$sssaldo2);
		  		}
		  		$sssaldo3=0;
		  		if($restodepre<0)
		  		{
		  			$sssaldo3 = abs($restodepre);
		  			$this->ds_Prebalance->insertRow("saldodepre",-$sssaldo3);
		  		}else{
		  			$sssaldo3=$restodepre;
		  			$this->ds_Prebalance->insertRow("saldodepre",$sssaldo3);
		  		}

		  		$this->ds_Prebalance->insertRow("cueproacu",1);
		  		$lb_valido2 = true;          //print "incluy�  $as_sc_cuenta <br>";
		  		//}//if
		  		}
		  	}

		  	$rs_data->MoveNext();
		  }//while
		  
		  
		  
		  
		  	   $ld_saldo_i=0;
	// if($lb_valido)
	 //{
	 	$lb_valido=$this->uf_scg_reporte_select_saldo_ingreso_BG($ad_fecfin,$this->ls_ingreso,$ld_saldo_i);
	// }
	 //if($lb_valido)
	 //{
	 	$ld_saldo_g=0;
	 	$lb_valido=$this->uf_scg_reporte_select_saldo_gasto_BG($ad_fecfin,$this->ls_gastos,$ld_saldo_g);
	 //}//if
		  
		  	 	$ld_saldo_ganancia=($ld_saldo_ganancia+($ld_saldo_i-$ld_saldo_g))*-1;
	 	
		
		  	 	
		  	 	
		  	 	
		  $presaldo6=0;
		  if($saldo6<0)
		  {
		  	$presaldo6 = abs($saldo6);
		  	$this->ds_Prebalance6->insertRow("saldo6",-$presaldo6);


		  }else{
		  	$presaldo6=$saldo6;
		  	$this->ds_Prebalance6->insertRow("saldo6",$presaldo6);

		  }
		  $presaldo5=0;
		  if($saldo5<0)
		  {
		  	$presaldo5 = abs($saldo5);
		  	$this->ds_Prebalance5->insertRow("saldo5",-$presaldo5);
		  }else{
		  	$presaldo5=$saldo5;
		  	$this->ds_Prebalance5->insertRow("saldo5",$presaldo5);
		  }
		  $presaldo4=0;
		  if($saldo4<0)
		  {
		  	$presaldo4 = abs($saldo4);
		  	$this->ds_Prebalance4->insertRow("saldo4",-$presaldo4);
		  }else{
		  	$presaldo4=$saldo4;
		  	$this->ds_Prebalance4->insertRow("saldo4",$presaldo4);
		  }
		  $presaldo3=0;
		  if($saldo3<0)
		  {
		  	$presaldo3 = abs($saldo3);
		  	$this->ds_Prebalance3->insertRow("saldo3",-$presaldo3);
		  }else{
		  	$presaldo3=$saldo3;
		  	$this->ds_Prebalance3->insertRow("saldo3",$presaldo3);
		  }
		  $presaldo2=0;
		  if($saldo2<0)
		  {
		  	$presaldo2 = abs($saldo2);
		  	$this->ds_Prebalance2->insertRow("saldo2",-$presaldo2);
		  }else{
		  	$presaldo2=$saldo2;
		  	$this->ds_Prebalance2->insertRow("saldo2",$presaldo2);
		  }
		  $presaldo1=0;
		  //echo "<br>saldo1".$saldo1."    ".$sssaldo;
		  if($saldo1<0)
		  {
		  	$presaldo1 = abs($saldo1);
		  	$this->ds_Prebalance1->insertRow("saldo1",-$presaldo1);
		  }else{
		  	$presaldo1=$saldo1;
		  	$this->ds_Prebalance1->insertRow("saldo1",$presaldo1);
		  }

		  $li_tot =$this->ds_Prebalance2->getRowCount("saldo2");
		  for ($li_i=1;$li_i<=$li_tot;$li_i++)
		  {
		  	//echo "<br>".$li_i." de  ".$li_tot."  ".$this->ds_Prebalance2->getValue("saldo2",$li_i);
		  }

		  $li_tot =$this->ds_Prebalance->getRowCount("sc_cuenta");



		  $total6=1;
		  $total5=1;
		  $total4=1;
		  $total3=1;
		  $total2=1;
		  $total1=1;

		  for ($li_i=1;$li_i<=$li_tot;$li_i++)
		  {
		  	$ls_sc_cuenta       =$this->ds_Prebalance->getValue("sc_cuenta",$li_i);
		  	$ls_denominacion    =$this->ds_Prebalance->getValue("denominacion",$li_i);
		  	$ls_nivel           =$this->ds_Prebalance->getValue("nivel",$li_i);
		  	$ls_rnivel          =$this->ds_Prebalance->getValue("rnivel",$li_i);
		  	$ls_status          =$this->ds_Prebalance->getValue("status",$li_i);
		  	$ld_saldo           =$this->ds_Prebalance->getValue("saldo",$li_i);
		  	$ls_rnivel          =$this->ds_Prebalance->getValue("rnivel",$li_i);
		  	$total_debe	    =$this->ds_Prebalance->getValue("total_debe",$li_i);
		  	$total_haber	    =$this->ds_Prebalance->getValue("total_haber",$li_i);
		  	$posicion	    =$this->ds_Prebalance->getValue("posicion",$li_i);
		  	$saldodepre	    =$this->ds_Prebalance->getValue("saldodepre",$li_i);
		  	if($ls_rnivel==6){
		  		$saldo6=$this->ds_Prebalance6->getValue("saldo6",$total6);
		  		$total6++;;

		  	}else{
		  		$saldo6="";

		  	}
		  	if($ls_rnivel==5){
		  		$saldo5=$this->ds_Prebalance5->getValue("saldo5",$total5);
		  		$total5++;;
		  	}else{
		  		$saldo5="";
		  	}
		  	if($ls_rnivel==4){
		  		$saldo4=$this->ds_Prebalance4->getValue("saldo4",$total4);
		  		$total4++;;
		  	}else{
		  		$saldo4="";
		  	}
		  	if($ls_rnivel==3){
		  		$saldo3=$this->ds_Prebalance3->getValue("saldo3",$total3);
		  		$total3++;
		  	}else{
		  		$saldo3="";
		  	}
		  	if($ls_rnivel==2){
		  		$saldo2=$this->ds_Prebalance2->getValue("saldo2",$total2);
		  		$total2++;
		  	}else{
		  		$saldo2="";
		  	}
		  	if($ls_rnivel==1){
		  		$saldo1=$this->ds_Prebalance1->getValue("saldo1",$total1);
		  		//$ld_saldo=$saldo1;
		  		$total1++;
		  		//echo "<br>entro".$li_i." de  ".$li_tot."  ".$ls_sc_cuenta."  ".$ls_nivel."   ".$ls_rnivel."   ".$saldo1;
		  	}else{
		  		$saldo1="";

		  	}
		  	//echo $saldo1." ". $saldo2." ".$saldo3." ".$saldo4." ".$saldo5." ".$saldo6."<br>";
		  	//if ($ls_rnivel<=$ai_nivel)
		  	//{
		  	//$this->ds_reporte->insertRow("orden",$ls_orden);
		  	//$this->ds_reporte->insertRow("num_reg",$li_nro_reg);
		  	//$this->ds_reporte->insertRow("total",$ld_saldo_resultado);
		  	//$this->ds_reporte->insertRow("referencia",$ls_referencia);
		  	//	 		$totalpasivomaspatrimonio=0;

		  	if($ls_nivel<=$ai_nivel)
		  	{
		  	$this->ds_reporte->insertRow("cuenta_salida",$ls_cuenta_salida);
		  		$this->ds_reporte->insertRow("cerrado",'');
		  		$this->ds_reporte->insertRow("sc_cuenta",$ls_sc_cuenta);
		  		$this->ds_reporte->insertRow("denominacion",$ls_denominacion);
		  		$this->ds_reporte->insertRow("status",$ls_status);
		  		$this->ds_reporte->insertRow("nivel",$ls_nivel);
		  		
		  		$this->ds_reporte->insertRow("rnivel",$ls_rnivel);
		  		$this->ds_reporte->insertRow("posicion",$posicion);
		  		$this->ds_reporte->insertRow("saldodepre",$saldodepre);
		  		
		  		
		  		$this->ds_reporte->insertRow("saldo6",$saldo6);
		  		
		  		if($ls_sc_cuenta=="3000000000000"){
		  			$totalpasivomaspatrimonio=$totalpasivomaspatrimonio+$saldo1+$ld_saldo_ganancia;
		  			$totalpatrimonio=$saldo1+$ld_saldo_ganancia;
		  			$this->ds_reporte->insertRow("saldo",$totalpatrimonio);
		  			$this->ds_reporte->insertRow("saldo1",$totalpatrimonio);
		  			$this->ds_reporte->insertRow("saldo2",$saldo2);
		  			$this->ds_reporte->insertRow("saldo3",$saldo3);
		  			$this->ds_reporte->insertRow("saldo4",$saldo4);
		  			$this->ds_reporte->insertRow("saldo5",$saldo5);
		  			
		  		}else{
		  		if($ls_sc_cuenta=="3200000000000"){
		  			$totalpatrimonio2=$saldo2+$ld_saldo_ganancia;
		  			$this->ds_reporte->insertRow("saldo",$saldo1);
		  			$this->ds_reporte->insertRow("saldo1",$saldo1);
		  			$this->ds_reporte->insertRow("saldo2",$totalpatrimonio2);
		  			$this->ds_reporte->insertRow("saldo3",$saldo3);
		  			$this->ds_reporte->insertRow("saldo4",$saldo4);
		  			$this->ds_reporte->insertRow("saldo5",$saldo5);
		  			
		  		}else{
		  			if($ls_sc_cuenta=="3250000000000"||$ls_sc_cuenta=="3250100000000"||$ls_sc_cuenta=="3250101000000"){
		  				$totalresultado=$saldo3+$ld_saldo_ganancia;
		  				$totalresultado4=$saldo4+$ld_saldo_ganancia;
		  				$totalresultado5=$saldo5+$ld_saldo_ganancia;
		  				$this->ds_reporte->insertRow("saldo",$ld_saldo);
		  				$this->ds_reporte->insertRow("saldo1",$saldo1);
		  				$this->ds_reporte->insertRow("saldo2",$saldo2);
		  				$this->ds_reporte->insertRow("saldo3",$totalresultado);
		  				$this->ds_reporte->insertRow("saldo4",$totalresultado4);
		  				$this->ds_reporte->insertRow("saldo5",$totalresultado5);
		  				
		  			}else{
		  				$this->ds_reporte->insertRow("saldo",$ld_saldo);
		  				$this->ds_reporte->insertRow("saldo1",$saldo1);
		  				$this->ds_reporte->insertRow("saldo2",$saldo2);
		  				$this->ds_reporte->insertRow("saldo3",$saldo3);
		  				$this->ds_reporte->insertRow("saldo4",$saldo4);
		  				$this->ds_reporte->insertRow("saldo5",$saldo5);
		  				
		  			}
		  			if($ls_sc_cuenta=="2000000000000"){
		  				$totalpasivomaspatrimonio=$totalpasivomaspatrimonio+$saldo1;
		  			}
		  		}
		  		}
		  	}



		  	//}
		  }


		  $li=$this->ds_reporte->getRowCount("sc_cuenta");

		  if($li==0)
		  {
		  	$lb_valido = false;
		  	return false;
		  }//if
	 } //else
		$total_total=($ld_saldo_ganancia+$totalpasivomaspatrimonio)*1;
	 



	 // imprimir valroes
	if($ai_nivel>5){

	 $this->ds_reporte->insertRow("cuenta_salida","");
	 $this->ds_reporte->insertRow("cerrado","");
	 $this->ds_reporte->insertRow("sc_cuenta","");
	 $this->ds_reporte->insertRow("denominacion","RESULTADO DEL EJERCICICIO");
	 $this->ds_reporte->insertRow("status","");
	 $this->ds_reporte->insertRow("nivel",6);
	 $this->ds_reporte->insertRow("saldo","");
	 $this->ds_reporte->insertRow("rnivel",6);
	 $this->ds_reporte->insertRow("posicion",6);
	 $this->ds_reporte->insertRow("saldodepre","");
	 $this->ds_reporte->insertRow("saldo1","");
	 $this->ds_reporte->insertRow("saldo2","");
	 $this->ds_reporte->insertRow("saldo3","");
	 $this->ds_reporte->insertRow("saldo4","");
	 $this->ds_reporte->insertRow("saldo5","");
	 $this->ds_reporte->insertRow("saldo6",$ld_saldo_ganancia);
	 	
	 	
	
	}
	 	
	 $this->ds_reporte->insertRow("cuenta_salida","");
	 $this->ds_reporte->insertRow("cerrado","");
	 $this->ds_reporte->insertRow("sc_cuenta","");
	 $this->ds_reporte->insertRow("denominacion","PASIVO + PATRIMONIO");
	 $this->ds_reporte->insertRow("status","");
	 $this->ds_reporte->insertRow("nivel",1);
	 $this->ds_reporte->insertRow("saldo",$totalpasivomaspatrimonio);
	 $this->ds_reporte->insertRow("rnivel",1);
	 $this->ds_reporte->insertRow("posicion",1);
	 $this->ds_reporte->insertRow("saldodepre","");
	 $this->ds_reporte->insertRow("saldo1",$totalpasivomaspatrimonio);
	 $this->ds_reporte->insertRow("saldo2","");
	 $this->ds_reporte->insertRow("saldo3","");
	 $this->ds_reporte->insertRow("saldo4","");
	 $this->ds_reporte->insertRow("saldo5","");
	 $this->ds_reporte->insertRow("saldo6","");
	 	
	 	
	 	

	 //print_r($this->ds_reporte);
	 //	 	 die();
	 unset($this->ds_Prebalance,$this->ds_Prebalance1,$this->ds_Prebalance2,$this->ds_Prebalance3,$this->ds_Prebalance4,$this->ds_Prebalance5,$this->ds_Prebalance6,$this->ds_Balance1,$ds_Balance2);
	 //var_dump($this->ds_reporte->data);
	 return $lb_valido;
	}


	function uf_balance_general_formatoresumen($ad_fecfin,$ai_nivel)
	{
		$lb_valido=true;
		$ds_Balance2=new class_datastore();
		$ldec_resultado=0;
		$ld_saldo_ganancia=0;
		$this->ls_activo=trim($this->la_empresa["activo"]);
		$this->ls_pasivo=trim($this->la_empresa["pasivo"]);
		$this->cuentaresultado = trim($this->la_empresa["c_resultad"]);
		$this->criteriores = substr($this->cuentaresultado,0,5);
		//	$this->criteriores = array(substr($this->cuentaresultado,0,5));
		$arrCuenta = $this->uf_escalera_cuenta($this->cuentaresultado);
		$this->ls_resultado=trim($this->la_empresa["resultado"]);
		$this->ls_capital=trim($this->la_empresa["capital"]);
		$this->ls_orden_d=trim($this->la_empresa["orden_d"]);
		$this->ls_orden_h=trim($this->la_empresa["orden_h"]);
		$this->ls_ingreso=trim($this->la_empresa["ingreso"]);
		$this->ls_gastos =trim($this->la_empresa["gasto"]);
		$this->ls_cta_resultado = trim($this->la_empresa["c_resultad"]);
		$this->ls_cuedepamo = trim($this->la_empresa["cuedepamo"]);
		$ad_fecfin=$this->io_fun->uf_convertirdatetobd($ad_fecfin);
		$ls_codemp=$this->la_empresa["codemp"];
		$as_sc_cuenta='';
		$as_denominacion='';
		$as_status='';
		$as_rnivel='';
		$ad_total_debe='';
		$ad_total_haber='';
		$ls_cadena_filtro = "";
		if(!empty($this->ls_cuedepamo))
		{
			$la_totcuedepamo = array();
			$la_hijcuedepamo = array();
			$la_famcuedepamo = array();
			$this->uf_obtener_total_cuedepamo($la_totcuedepamo,$la_hijcuedepamo,$ad_fecfin);
			$this->uf_obtener_familia_cuedepamo($la_famcuedepamo);
			$ls_cadena_pasivo = $this->uf_obtener_cuentas_pasivo_omitir($la_totcuedepamo,$la_hijcuedepamo,$la_famcuedepamo);
			if(!empty($ls_cadena_pasivo))
			{
				$ls_cadena_filtro = "AND SC.sc_cuenta NOT IN (".$ls_cadena_pasivo.")";
			}
		}

		if($_SESSION["ls_gestor"]=='INFORMIX')
		{
			$ls_sql="SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel,
                             (select case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' AND sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe,
                             (select case sum(haber_mes) when null then 0 else sum(haber_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' AND sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber,
                             0 as nivel,SC.cueproacu  
                      FROM scg_cuentas SC 
                      where  (SC.sc_cuenta like '".$this->ls_activo."%' OR SC.sc_cuenta like '".$this->ls_pasivo."%' 
                           OR SC.sc_cuenta like '".$this->ls_resultado."%' OR SC.sc_cuenta like '".$this->ls_capital."%' 
                           OR SC.sc_cuenta like '".$this->ls_orden_d."%' OR SC.sc_cuenta like '".$this->ls_orden_h."%') 
                      ORDER BY SC.sc_cuenta ";
		}
		else
		{
			/* $ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
			 "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
			 "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,trim(SC.cueproacu) as cueproacu ".
			 " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
			 "                                             coalesce(sum(haber_mes),0) as T_Haber ".
			 "                                      FROM   scg_saldos ".
			 "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
			 "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
			 " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
			 " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
			 "       (SC.sc_cuenta like '".$this->ls_activo."%' OR SC.sc_cuenta like '".$this->ls_pasivo."%' OR ".
			 "        SC.sc_cuenta like '".$this->ls_resultado."%' OR  SC.sc_cuenta like '".$this->ls_capital."%' OR ".
			 "        SC.sc_cuenta like '".$this->ls_orden_d."%' OR SC.sc_cuenta like '".$this->ls_orden_h."%') ".
			 " ORDER BY trim(SC.sc_cuenta) "; */

			// Consulta modificada por Ofimatica de Venezuela el 10-08-2011, para agregar el CASE para el manejo del valor del campo
			// cueproacu
			$ls_sql=  " SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
					  "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
					  "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,(CASE SC.status WHEN 'S' THEN '' WHEN 'C' THEN (SC.cueproacu) END) as cueproacu, 1 as tiporden ".
					  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
					  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
					  "                                      FROM   scg_saldos ".
					  "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
					  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
					  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
					  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
					  "       (SC.sc_cuenta like '".$this->ls_activo."%' OR SC.sc_cuenta like '".$this->ls_orden_d."%') ".
					  "UNION".
					  " SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
					  "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
					  "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,(CASE SC.status WHEN 'S' THEN '' WHEN 'C' THEN (SC.cueproacu) END) as cueproacu, 2 as tiporden ".
					  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
					  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
					  "                                      FROM   scg_saldos ".
					  "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
					  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
					  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
					  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
					  "       (SC.sc_cuenta like '".$this->ls_pasivo."%' OR SC.sc_cuenta like '".$this->ls_resultado."%' OR  SC.sc_cuenta like '".$this->ls_capital."%' )".
			//"        SC.sc_cuenta like '".$this->ls_orden_h."%') ".
					  " AND SC.sc_cuenta NOT IN (SELECT TRIM(cueproacu) FROM scg_cuentas WHERE cueproacu <> '') ".$ls_cadena_filtro.
					  " AND SC.sc_cuenta NOT IN(SELECT cueproacu FROM scg_CUENTAS        SC                  WHERE codemp='".$ls_codemp."'  AND   cueproacu!=''   AND  status='C' )	AND SC.sc_cuenta not like '225%'			  ".
					  " ORDER BY 9,1";
			// Fin de la modificacion de la consulta.
		}
		// echo $ls_sql."<br>";

		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{// error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
			//print $this->io_sql->message;
			$lb_valido = false;
		}
	 else
	 {
	 	$ld_saldo_ganancia=0;
	 	$restodepre=0;
	 	$saldo6=0;
	 	$saldo5=0;
	 	$saldo4=0;
	 	$saldo3=0;
	 	$saldo2=0;
	 	$saldo1=0;
	 	$previosaldo6=0;
	 	$previosaldo5=0;
	 	$previosaldo4=0;
	 	$previosaldo3=0;
	 	$previosaldo2=0;
	 	$previosaldo1=0;

	 	//while($row=$this->io_sql->fetch_row($rs_data))
	 	
	 	
	 	
	 	while(!$rs_data->EOF)
	 	{
		  $ls_sc_cuenta=$rs_data->fields["sc_cuenta"];
		  $ls_denominacion=$rs_data->fields["denominacion"];
		  $ls_status=$rs_data->fields["status"];
		  $ls_rnivel=$rs_data->fields["rnivel"];
		  $ld_total_debe=$rs_data->fields["total_debe"];
		  $ld_total_haber=$rs_data->fields["total_haber"];
		  $ls_cueproacu = trim($rs_data->fields["cueproacu"]);


		  $ls_nivel=$ls_rnivel;

		  // echo "<br>aaaa".$ai_nivel;
		  //else
		  //  if($ls_nivel<=$ai_nivel)
		  //{
		  $this->ds_Prebalance->insertRow("sc_cuenta",$ls_sc_cuenta);          // print "ls_sc_cuenta  :  $ls_sc_cuenta <br> " ;
		  $this->ds_Prebalance->insertRow("denominacion",$ls_denominacion);
		  $this->ds_Prebalance->insertRow("status",$ls_status);
		  $this->ds_Prebalance->insertRow("nivel",$ls_nivel);
		  $this->ds_Prebalance->insertRow("rnivel",$ls_rnivel);
		  $this->ds_Prebalance->insertRow("total_debe",$ld_total_debe);
		  $this->ds_Prebalance->insertRow("total_haber",$ld_total_haber);
		  $this->ds_Prebalance->insertRow("saldodepre",'');
		  		  $this->ds_Prebalance->insertRow("ls_cueproacu",$ls_cueproacu);
		  
		  
		  //echo "<br>".$ls_sc_cuenta."   ".$ls_rnivel."   ".$ls_nivel;
		  //echo "<br>saldo2 ".$saldo2."    ".$sssaldo;
		  $sssaldo=$ld_total_debe-$ld_total_haber;

		  $sssaldo2=0;
		  if($sssaldo<0)
		  {
		  	$sssaldo2 = abs($sssaldo);
		  	$this->ds_Prebalance->insertRow("saldo",-$sssaldo2);
		  }else{
		  	$sssaldo2=$sssaldo;
		  	$this->ds_Prebalance->insertRow("saldo",$sssaldo2);
		  }

		  if($ls_rnivel=="7"){
		  	if ($ls_cueproacu!=='')
		  	{
		  		$this->ds_Prebalance->insertRow("posicion","8");
		  		$restodepre=$sssaldo;
		  	}else{
		  		$this->ds_Prebalance->insertRow("posicion",$ls_rnivel);
		  	}
		  	$saldo6=$saldo6+$sssaldo;
	 	  $saldo5=$saldo5+$sssaldo;
	 	  $saldo4=$saldo4+$sssaldo;
	 	  $saldo3=$saldo3+$sssaldo;
	 	  $saldo2=$saldo2+$sssaldo;
	 	  $saldo1=$saldo1+$sssaldo;
	 	  	
		  }else{
		  	if($ls_rnivel=="6"){
		  		if($previosaldo6>0){
			  		$presaldo6=0;
			  		if($saldo6<0)
			  		{
			  			$presaldo6 = abs($saldo6);
			  			$this->ds_Prebalance6->insertRow("saldo6",-$presaldo6);


			  		}else{
			  			$presaldo6=$saldo6;
			  			$this->ds_Prebalance6->insertRow("saldo6",$presaldo6);

			  		}
			  		// echo "<br>".$this->ds_Prebalance6->getRowCount("saldo6")."hh ".$presaldo6." 66  ".$sssaldo ;;
			  		 
			  		$saldo6=0;
			  	}else{
			  		$previosaldo6=1;
			  	}}
			  	if($ls_rnivel=="5"){
			  		if($previosaldo5>0){
			  			$presaldo5=0;
			  			if($saldo5<0)
			  			{
			  				$presaldo5 = abs($saldo5);
			  				$this->ds_Prebalance5->insertRow("saldo5",-$presaldo5);
			  			}else{
			  				$presaldo5=$saldo5;
			  				$this->ds_Prebalance5->insertRow("saldo5",$presaldo5);
			  			}
			  			// echo "<br>".$this->ds_Prebalance5->getRowCount("saldo5")."aa ".$presaldo5." 55  ".$sssaldo ;;

			  			$saldo5=0;
			  			//$saldo6=0;
			  		}else{
			  			$previosaldo5=1;
			  		}}
			  		if($ls_rnivel=="4"){
			  			if($previosaldo4>0){
			  				$presaldo4=0;
			  				if($saldo4<0)
			  				{
			  					$presaldo4 = abs($saldo4);
			  					$this->ds_Prebalance4->insertRow("saldo4",-$presaldo4);
			  				}else{
			  					$presaldo4=$saldo4;
			  					$this->ds_Prebalance4->insertRow("saldo4",$presaldo4);
			  				}
			  				// echo "<br>".$this->ds_Prebalance4->getRowCount("saldo4")."bb ".$presaldo4." 44  ".$sssaldo ;;
			  				 
			  				$saldo4=0;
			  				//$saldo5=0;
			  				//$saldo6=0;
			  			}else{
			  				$previosaldo4=1;
			  			}}
			  			if($ls_rnivel=="3"){
			  				if($previosaldo3>0){
			  					$presaldo3=0;
			  					if($saldo3<0)
			  					{
			  						$presaldo3 = abs($saldo3);
			  						$this->ds_Prebalance3->insertRow("saldo3",-$presaldo3);
			  					}else{
			  						$presaldo3=$saldo3;
			  						$this->ds_Prebalance3->insertRow("saldo3",$presaldo3);
			  					}

			  					// echo "<br>".$this->ds_Prebalance3->getRowCount("saldo3")."cc ".$presaldo3." 33  ".$sssaldo ;;

			  					$saldo3=0;
			  					//$saldo4=0;
			  					//$saldo5=0;
			  					//$saldo6=0;
			  				}else{
			  					$previosaldo3=1;
			  				}}
			  				if($ls_rnivel=="2"){

			  					if($previosaldo2>0){
			  						$presaldo2=0;
			  						if($saldo2<0)
			  						{
			  							$presaldo2 = abs($saldo2);
			  							$this->ds_Prebalance2->insertRow("saldo2",-$presaldo2);
			  						}else{
			  							$presaldo2=$saldo2;
			  							$this->ds_Prebalance2->insertRow("saldo2",$presaldo2);
			  						}
			  						//echo "<br>".$this->ds_Prebalance2->getRowCount("saldo2")." dd ".$presaldo2." 22  ".$sssaldo ;;
			  						 
			  						$saldo2=0;
			  						//	$saldo3=0;
			  						//	$saldo4=0;
			  						//	$saldo5=0;
			  						//$saldo6=0;
			  					}else{
			  						$previosaldo2=1;
			  					}}
			  					if($ls_rnivel=="1"){
			  						//echo "<br>saldo1".$saldo1."    ".$sssaldo;
			  						if($previosaldo1>0){
			  							$presaldo1=0;
			  							if($saldo1<0)
			  							{
			  								$presaldo1 = abs($saldo1);
			  								$this->ds_Prebalance1->insertRow("saldo1",-$presaldo1);
			  							}else{
			  								$presaldo1=$saldo1;
			  								$this->ds_Prebalance1->insertRow("saldo1",$presaldo1);
			  							}

			  							//echo "<br>".$this->ds_Prebalance1->getRowCount("saldo1")."ee ".$presaldo1." 11  ".$sssaldo ;;
			  							 
			  							$saldo1=0;
			  							//$saldo2=0;
			  							//$saldo3=0;
			  							//$saldo4=0;
			  							//$saldo5=0;
			  							//$saldo6=0;
			  						}
			  						else{
			  							$previosaldo1=1;
			  						}}
		  }
		  $this->ds_Prebalance->insertRow("cueproacu",0);
		  $lb_valido = true;
		  //  }//if
		  if ($ls_cueproacu!=='')
		  {
		  	$vvalorquellama=$ls_cueproacu;
		  	$lb_valido2 = $this->uf_scg_cueproacu_saldo($ls_cueproacu,$ad_fecfin,&$as_sc_cuenta,&$as_denominacion,&$as_status ,&$as_rnivel,&$ad_total_debe,&$ad_total_haber );
		  	if($lb_valido2)
		  	{
		  		if($as_status=="C")
		  		{
		  			$ls_nivel=$ls_rnivel;

		  		}//if
		  		else
		  		{
		  			$ls_nivel=$ls_rnivel;
		  		}//else
		  		//if($ls_nivel<=$ai_nivel)
		  		//	{
		  		$this->ds_Prebalance->insertRow("sc_cuenta",$as_sc_cuenta);
		  		$this->ds_Prebalance->insertRow("denominacion",$as_denominacion);
		  		$this->ds_Prebalance->insertRow("status",$as_status);
		  		$this->ds_Prebalance->insertRow("nivel",$ls_nivel);
		  		$this->ds_Prebalance->insertRow("rnivel",$as_rnivel);
		  		$this->ds_Prebalance->insertRow("total_debe",$ad_total_debe);
		  		$this->ds_Prebalance->insertRow("total_haber",$ad_total_haber);
		  		$this->ds_Prebalance->insertRow("posicion","8");
		  		$this->ds_Prebalance->insertRow("ls_cueproacu",$vvalorquellama);
		  		
		  		/*  $this->ds_reporte->insertRow("saldo6",'');
					  $this->ds_reporte->insertRow("saldo5",'');
			  		$this->ds_reporte->insertRow("saldo4",'');
			  		$this->ds_reporte->insertRow("saldo3",'');
			  		$this->ds_reporte->insertRow("saldo2",'');
			  		$this->ds_reporte->insertRow("saldo1",'');*/

		  		$sssaldo=$ad_total_debe-$ad_total_haber;
		  		$saldo6=$saldo6+$sssaldo;
		  		$saldo5=$saldo5+$sssaldo;
		  		$saldo4=$saldo4+$sssaldo;
		  		$saldo3=$saldo3+$sssaldo;
		  		$saldo2=$saldo2+$sssaldo;
		  		$saldo1=$saldo1+$sssaldo;
		  		$restodepre=$restodepre+$sssaldo;
		  		$sssaldo2=0;
		  		if($sssaldo<0)
		  		{
		  			$sssaldo2 = abs($sssaldo);
		  			$this->ds_Prebalance->insertRow("saldo",-$sssaldo2);
		  		}else{
		  			$sssaldo2=$sssaldo;
		  			$this->ds_Prebalance->insertRow("saldo",$sssaldo2);
		  		}
		  		$sssaldo3=0;
		  		if($restodepre<0)
		  		{
		  			$sssaldo3 = abs($restodepre);
		  			$this->ds_Prebalance->insertRow("saldodepre",-$sssaldo3);
		  		}else{
		  			$sssaldo3=$restodepre;
		  			$this->ds_Prebalance->insertRow("saldodepre",$sssaldo3);
		  		}

		  		$this->ds_Prebalance->insertRow("cueproacu",1);
		  		$lb_valido2 = true;          //print "incluy�  $as_sc_cuenta <br>";
		  		//}//if
		  		}
		  	}

		  	$rs_data->MoveNext();
		  }//while
		  
		  
		  
		  
		   $ld_saldo_i=0;
	// if($lb_valido)
	 //{
	 	$lb_valido=$this->uf_scg_reporte_select_saldo_ingreso_BG($ad_fecfin,$this->ls_ingreso,$ld_saldo_i);
	// }
	 //if($lb_valido)
	 //{
	 	$ld_saldo_g=0;
	 	$lb_valido=$this->uf_scg_reporte_select_saldo_gasto_BG($ad_fecfin,$this->ls_gastos,$ld_saldo_g);
	 //}//if
		  
		  	 	$ld_saldo_ganancia=($ld_saldo_ganancia+($ld_saldo_i-$ld_saldo_g))*-1;
	 	
		
		  $presaldo6=0;
		  if($saldo6<0)
		  {
		  	$presaldo6 = abs($saldo6);
		  	$this->ds_Prebalance6->insertRow("saldo6",-$presaldo6);


		  }else{
		  	$presaldo6=$saldo6;
		  	$this->ds_Prebalance6->insertRow("saldo6",$presaldo6);

		  }
		  $presaldo5=0;
		  if($saldo5<0)
		  {
		  	$presaldo5 = abs($saldo5);
		  	$this->ds_Prebalance5->insertRow("saldo5",-$presaldo5);
		  }else{
		  	$presaldo5=$saldo5;
		  	$this->ds_Prebalance5->insertRow("saldo5",$presaldo5);
		  }
		  $presaldo4=0;
		  if($saldo4<0)
		  {
		  	$presaldo4 = abs($saldo4);
		  	$this->ds_Prebalance4->insertRow("saldo4",-$presaldo4);
		  }else{
		  	$presaldo4=$saldo4;
		  	$this->ds_Prebalance4->insertRow("saldo4",$presaldo4);
		  }
		  $presaldo3=0;
		  if($saldo3<0)
		  {
		  	$presaldo3 = abs($saldo3);
		  	$this->ds_Prebalance3->insertRow("saldo3",-$presaldo3);
		  }else{
		  	$presaldo3=$saldo3;
		  	$this->ds_Prebalance3->insertRow("saldo3",$presaldo3);
		  }
		  $presaldo2=0;
		  if($saldo2<0)
		  {
		  	$presaldo2 = abs($saldo2);
		  	$this->ds_Prebalance2->insertRow("saldo2",-$presaldo2);
		  }else{
		  	$presaldo2=$saldo2;
		  	$this->ds_Prebalance2->insertRow("saldo2",$presaldo2);
		  }
		  $presaldo1=0;
		  //echo "<br>saldo1".$saldo1."    ".$sssaldo;
		  if($saldo1<0)
		  {
		  	$presaldo1 = abs($saldo1);
		  	$this->ds_Prebalance1->insertRow("saldo1",-$presaldo1);
		  }else{
		  	$presaldo1=$saldo1;
		  	$this->ds_Prebalance1->insertRow("saldo1",$presaldo1);
		  }

		  $li_tot =$this->ds_Prebalance2->getRowCount("saldo2");
		  for ($li_i=1;$li_i<=$li_tot;$li_i++)
		  {
		  	//echo "<br>".$li_i." de  ".$li_tot."  ".$this->ds_Prebalance2->getValue("saldo2",$li_i);
		  }

		  $li_tot =$this->ds_Prebalance->getRowCount("sc_cuenta");



		  $total6=1;
		  $total5=1;
		  $total4=1;
		  $total3=1;
		  $total2=1;
		  $total1=1;
		  			$totalpatrimonio=0;
		  
		  for ($li_i=1;$li_i<=$li_tot;$li_i++)
		  {
		  	$ls_sc_cuenta       =$this->ds_Prebalance->getValue("sc_cuenta",$li_i);
		  	$ls_denominacion    =$this->ds_Prebalance->getValue("denominacion",$li_i);
		  	$ls_nivel           =$this->ds_Prebalance->getValue("nivel",$li_i);
		  	$ls_rnivel          =$this->ds_Prebalance->getValue("rnivel",$li_i);
		  	$ls_status          =$this->ds_Prebalance->getValue("status",$li_i);
		  	$ld_saldo           =$this->ds_Prebalance->getValue("saldo",$li_i);
		  	$ls_rnivel          =$this->ds_Prebalance->getValue("rnivel",$li_i);
		  	$total_debe	    =$this->ds_Prebalance->getValue("total_debe",$li_i);
		  	$total_haber	    =$this->ds_Prebalance->getValue("total_haber",$li_i);
		  	$posicion	    =$this->ds_Prebalance->getValue("posicion",$li_i);
		  	$saldodepre	    =$this->ds_Prebalance->getValue("saldodepre",$li_i);
		  	$ls_cueproacu=$this->ds_Prebalance->getValue("ls_cueproacu",$li_i);
		  	if($ls_rnivel==6){
		  		$saldo6=$this->ds_Prebalance6->getValue("saldo6",$total6);
		  		$total6++;;

		  	}else{
		  		$saldo6="";

		  	}
		  	if($ls_rnivel==5){
		  		$saldo5=$this->ds_Prebalance5->getValue("saldo5",$total5);
		  		$total5++;;
		  	}else{
		  		$saldo5="";
		  	}
		  	if($ls_rnivel==4){
		  		$saldo4=$this->ds_Prebalance4->getValue("saldo4",$total4);
		  		$total4++;;
		  	}else{
		  		$saldo4="";
		  	}
		  	if($ls_rnivel==3){
		  		$saldo3=$this->ds_Prebalance3->getValue("saldo3",$total3);
		  		$total3++;
		  	}else{
		  		$saldo3="";
		  	}
		  	if($ls_rnivel==2){
		  		$saldo2=$this->ds_Prebalance2->getValue("saldo2",$total2);
		  		$total2++;
		  	}else{
		  		$saldo2="";
		  	}
		  	if($ls_rnivel==1){
		  		$saldo1=$this->ds_Prebalance1->getValue("saldo1",$total1);
		  		//$ld_saldo=$saldo1;
		  		$total1++;
		  		//echo "<br>entro".$li_i." de  ".$li_tot."  ".$ls_sc_cuenta."  ".$ls_nivel."   ".$ls_rnivel."   ".$saldo1;
		  	}else{
		  		$saldo1="";

		  	}
		  	//echo $saldo1." ". $saldo2." ".$saldo3." ".$saldo4." ".$saldo5." ".$saldo6."<br>";
		  	//if ($ls_rnivel<=$ai_nivel)
		  	//{
		  	//$this->ds_reporte->insertRow("orden",$ls_orden);
		  	//$this->ds_reporte->insertRow("num_reg",$li_nro_reg);
		  	//$this->ds_reporte->insertRow("total",$ld_saldo_resultado);
		  	//$this->ds_reporte->insertRow("referencia",$ls_referencia);
		  	//	 		$totalpasivomaspatrimonio=0;

		  	if($ls_nivel==1||$ls_nivel==2||$ls_nivel==3||$ls_nivel==6||($ls_nivel==7&&$ls_cueproacu!=''))
		  	{
		  		$this->ds_reporte->insertRow("cuenta_salida",$ls_cuenta_salida);
		  		$this->ds_reporte->insertRow("cerrado",'');
		  		$this->ds_reporte->insertRow("sc_cuenta",$ls_sc_cuenta);
		  		$this->ds_reporte->insertRow("denominacion",$ls_denominacion);
		  		$this->ds_reporte->insertRow("status",$ls_status);
		  		$this->ds_reporte->insertRow("nivel",$ls_nivel);
		  		
		  		$this->ds_reporte->insertRow("rnivel",$ls_rnivel);
		  		$this->ds_reporte->insertRow("posicion",$posicion);
		  		$this->ds_reporte->insertRow("saldodepre",$saldodepre);
		  		
		  		
		  		$this->ds_reporte->insertRow("saldo4",$saldo4);
		  		$this->ds_reporte->insertRow("saldo5",$saldo5);
		  		$this->ds_reporte->insertRow("saldo6",$saldo6);
		  		if($ls_sc_cuenta=="3000000000000"||$ls_sc_cuenta=="3200000000000"){
		  			if($ls_sc_cuenta=="3000000000000"){
		  				$totalpasivomaspatrimonio=$totalpasivomaspatrimonio+$saldo1+$ld_saldo_ganancia;
		  				$totalpatrimonio=$saldo1+$ld_saldo_ganancia;
		  			}
		  			$totalpatrimonio2=$saldo2+$ld_saldo_ganancia;
		  			$this->ds_reporte->insertRow("saldo",$totalpatrimonio);
		  			$this->ds_reporte->insertRow("saldo1",$totalpatrimonio);
		  			$this->ds_reporte->insertRow("saldo2",$totalpatrimonio2);
		  			$this->ds_reporte->insertRow("saldo3",$saldo3);
		  			
		  		}else{
		  			if($ls_sc_cuenta=="3250000000000"){
		  				$totalresultado=$saldo3+$ld_saldo_ganancia;
		  				$this->ds_reporte->insertRow("saldo",$ld_saldo);
		  				$this->ds_reporte->insertRow("saldo1",$saldo1);
		  				$this->ds_reporte->insertRow("saldo2",$saldo2);
		  				$this->ds_reporte->insertRow("saldo3",$totalresultado);
		  				
		  				
		  			}else{
		  				$this->ds_reporte->insertRow("saldo",$ld_saldo);
		  				$this->ds_reporte->insertRow("saldo1",$saldo1);
		  				$this->ds_reporte->insertRow("saldo2",$saldo2);
		  				$this->ds_reporte->insertRow("saldo3",$saldo3);
		  				
		  			}
		  			if($ls_sc_cuenta=="2000000000000"){
		  				$totalpasivomaspatrimonio=$totalpasivomaspatrimonio+$saldo1;
		  			}
		  		}
		  	}

  

		  	//}
		  }


		  $li=$this->ds_reporte->getRowCount("sc_cuenta");

		  if($li==0)
		  {
		  	$lb_valido = false;
		  	return false;
		  }//if
	 } //else
	


	 if($lb_valido)
	 {
	 //	$ld_saldo_ganancia=($ld_saldo_ganancia+($ld_saldo_i-$ld_saldo_g))*-1;
	 	$total_total=($ld_saldo_ganancia+$totalpasivomaspatrimonio)*1;
	 



	 // imprimir valroes
	

	 $this->ds_reporte->insertRow("cuenta_salida","");
	 $this->ds_reporte->insertRow("cerrado","");
	 $this->ds_reporte->insertRow("sc_cuenta","");
	 $this->ds_reporte->insertRow("denominacion","RESULTADO DEL EJERCICIO");
	 $this->ds_reporte->insertRow("status","");
	 $this->ds_reporte->insertRow("nivel",6);
	 $this->ds_reporte->insertRow("saldo","");
	 $this->ds_reporte->insertRow("rnivel",6);
	 $this->ds_reporte->insertRow("posicion",6);
	 $this->ds_reporte->insertRow("saldodepre","");
	 $this->ds_reporte->insertRow("saldo1","");
	 $this->ds_reporte->insertRow("saldo2","");
	 $this->ds_reporte->insertRow("saldo3","");
	 $this->ds_reporte->insertRow("saldo4","");
	 $this->ds_reporte->insertRow("saldo5","");
	 $this->ds_reporte->insertRow("saldo6",$ld_saldo_ganancia);
	 	
	 	
	
	 	
	 	
	 $this->ds_reporte->insertRow("cuenta_salida","");
	 $this->ds_reporte->insertRow("cerrado","");
	 $this->ds_reporte->insertRow("sc_cuenta","");
	 $this->ds_reporte->insertRow("denominacion","PASIVO + PATRIMONIO");
	 $this->ds_reporte->insertRow("status","");
	 $this->ds_reporte->insertRow("nivel",1);
	 $this->ds_reporte->insertRow("saldo",$totalpasivomaspatrimonio);
	 $this->ds_reporte->insertRow("rnivel",1);
	 $this->ds_reporte->insertRow("posicion",1);
	 $this->ds_reporte->insertRow("saldodepre","");
	 $this->ds_reporte->insertRow("saldo1",$totalpasivomaspatrimonio);
	 $this->ds_reporte->insertRow("saldo2","");
	 $this->ds_reporte->insertRow("saldo3","");
	 $this->ds_reporte->insertRow("saldo4","");
	 $this->ds_reporte->insertRow("saldo5","");
	 $this->ds_reporte->insertRow("saldo6","");
	 
	 
	
	 	
	 	}//if
	 	

	 //print_r($this->ds_reporte);
	  	 //die();
	 unset($this->ds_Prebalance,$this->ds_Prebalance1,$this->ds_Prebalance2,$this->ds_Prebalance3,$this->ds_Prebalance4,$this->ds_Prebalance5,$this->ds_Prebalance6,$this->ds_Balance1,$ds_Balance2);
	 //var_dump($this->ds_reporte->data);
	 return $lb_valido;
	}



	/****************************************************************************************************************************************/
	function uf_balance_general_formato2($ad_fecfin,$ai_nivel)
	{
		$lb_valido=true;
		$ds_Balance2=new class_datastore();
		$ds_reg_niveles = new class_datastore();
		$ldec_resultado=0;
		$ld_saldo_ganancia=0;
		$this->ls_activo=trim($this->la_empresa["activo"]);
		$this->ls_pasivo=trim($this->la_empresa["pasivo"]);
		$this->cuentaresultado = trim($this->la_empresa["c_resultad"]);
		$this->criteriores = substr($this->cuentaresultado,0,5);
		//    $this->criteriores = array(substr($this->cuentaresultado,0,5));
		$arrCuenta = $this->uf_escalera_cuenta($this->cuentaresultado);
		$this->ls_resultado=trim($this->la_empresa["resultado"]);
		$this->ls_capital=trim($this->la_empresa["capital"]);
		$this->ls_orden_d=trim($this->la_empresa["orden_d"]);
		$this->ls_orden_h=trim($this->la_empresa["orden_h"]);
		$this->ls_ingreso=trim($this->la_empresa["ingreso"]);
		$this->ls_gastos =trim($this->la_empresa["gasto"]);
		$this->ls_cta_resultado = trim($this->la_empresa["c_resultad"]);
		$this->ls_cuedepamo = trim($this->la_empresa["cuedepamo"]);
		$ad_fecfin=$this->io_fun->uf_convertirdatetobd($ad_fecfin);
		$ls_codemp=$this->la_empresa["codemp"];
		$ls_ceros = "";
		$ls_formcont = trim($this->la_empresa["formcont"]);
		$ls_formcont = trim(str_replace("-","",$ls_formcont));
		$ls_ceros = str_pad("",strlen($ls_formcont)-strlen(trim(substr($this->ls_orden_d,0,1))),"0");
		$ls_cuenta_tot_deudora = trim(substr($this->ls_orden_d,0,1));
		if(!empty($ls_cuenta_tot_deudora))
		{
		 $ls_cuenta_tot_deudora .= $ls_ceros;
		}
		else
		{
		 $ls_cuenta_tot_deudora = "";
		}
		$as_sc_cuenta='';
		$as_denominacion='';
		$as_status='';
		$as_rnivel='';
		$ad_total_debe='';
		$ad_total_haber='';
		$aa_cuentas_pa = array();
		$aa_cuentas_pa = $this->uf_scg_crea_array_cueproacu();
		$ls_cadena_filtro = "";
		if(!empty($this->ls_cuedepamo))
		{
			$la_totcuedepamo = array();
			$la_hijcuedepamo = array();
			$la_famcuedepamo = array();
			$this->uf_obtener_total_cuedepamo($la_totcuedepamo,$la_hijcuedepamo,$ad_fecfin);
			$this->uf_obtener_familia_cuedepamo($la_famcuedepamo);
			$ls_cadena_pasivo = $this->uf_obtener_cuentas_pasivo_omitir($la_totcuedepamo,$la_hijcuedepamo,$la_famcuedepamo);
			if(!empty($ls_cadena_pasivo))
			{
				$ls_cadena_filtro = "AND SC.sc_cuenta NOT IN (".$ls_cadena_pasivo.")";
			}
		}
		$ls_cuentas_orden = "";
		if($this->uf_verificar_cuentas_orden_formato2($ls_codemp,$ad_fecfin))
		{
		 $ls_cuentas_orden =  " UNION ".
							  " SELECT DISTINCT '".$ls_cuenta_tot_deudora."' as sc_cuenta, 'CUENTAS DE ORDEN' as denominacion, 'S' as status, 1 as rnivel,'' as referencia,  ".
							  "        coalesce(SUM(curSaldo.T_Debe),0) as total_debe, ".
							  "        coalesce(SUM(curSaldo.T_Haber),0) as total_haber,0 as nivel, (CASE SC.status WHEN 'S' THEN '' WHEN 'C' THEN (SC.cueproacu) END) as cueproacu, 1 as tiporden ".
							  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
							  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
							  "                                      FROM   scg_saldos ".
							  "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
							  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
							  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
							  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
							  "      SC.sc_cuenta like '".$this->ls_orden_d."%' AND SC.status = 'C'".
							  " UNION ".
							  " SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel,SC.referencia as referencia,  ".
							  "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
							  "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,(CASE SC.status WHEN 'S' THEN '' WHEN 'C' THEN (SC.cueproacu) END) as cueproacu, 1 as tiporden ".
							  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
							  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
							  "                                      FROM   scg_saldos ".
							  "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
							  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
							  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
							  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
							  "       (SC.sc_cuenta like '".$this->ls_orden_d."%') ";
		}

		if($_SESSION["ls_gestor"]=='INFORMIX')
		{
			$ls_sql="SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel,sc.referencia as referencia,
                             (select case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' AND sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe,
                             (select case sum(haber_mes) when null then 0 else sum(haber_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' AND sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber,
                             0 as nivel,SC.cueproacu  
                      FROM scg_cuentas SC 
                      where  (SC.sc_cuenta like '".$this->ls_activo."%' OR SC.sc_cuenta like '".$this->ls_pasivo."%' 
                           OR SC.sc_cuenta like '".$this->ls_resultado."%' OR SC.sc_cuenta like '".$this->ls_capital."%' 
                           OR SC.sc_cuenta like '".$this->ls_orden_d."%' OR SC.sc_cuenta like '".$this->ls_orden_h."%') 
                      ORDER BY SC.sc_cuenta ";
		}
		else
		{
			$ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel,SC.referencia as referencia,  ".
				  "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
				  "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,(CASE SC.status WHEN 'S' THEN '' WHEN 'C' THEN (SC.cueproacu) END) as cueproacu, 1 as tiporden ".
				  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
				  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
				  "                                      FROM   scg_saldos ".
				  "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
				  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
				  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
				  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
				  "       (SC.sc_cuenta like '".$this->ls_activo."%') ".$ls_cuentas_orden.
				  " UNION ".
				  " SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel,SC.referencia as referencia,  ".
				  "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
				  "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,(CASE SC.status WHEN 'S' THEN '' WHEN 'C' THEN (SC.cueproacu) END) as cueproacu,2 as tiporden ".
				  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
				  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
				  "                                      FROM   scg_saldos ".
				  "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
				  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
				  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
				  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
				  "       (SC.sc_cuenta like '".$this->ls_pasivo."%' OR ".
				  "        SC.sc_cuenta like '".$this->ls_resultado."%' OR  SC.sc_cuenta like '".$this->ls_capital."%'  ) ".
				   " AND SC.sc_cuenta NOT IN (SELECT TRIM(cueproacu) FROM scg_cuentas WHERE cueproacu <> '') ".$ls_cadena_filtro.
				  " ORDER BY 10,1,4 ";
		}
		//echo $ls_sql;
		//die();
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{// error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general_formato2 ".$this->io_fun->uf_convertirmsg($this->io_sql->message);

			$lb_valido = false;
		}
		else
		{
			$ld_saldo_ganancia=0;
			$ls_nivel_act = "1";
			$ls_status_act = "S";

			while(!$rs_data->EOF)
			{
				$ls_sc_cuenta=$rs_data->fields["sc_cuenta"];
				$ls_denominacion=$rs_data->fields["denominacion"];
				$ls_status=$rs_data->fields["status"];
				$ls_rnivel=$rs_data->fields["rnivel"];
				$ld_total_debe=$rs_data->fields["total_debe"];
				$ld_total_haber=$rs_data->fields["total_haber"];
				$ls_cueproacu = trim($rs_data->fields["cueproacu"]);
				$ls_referencia = $rs_data->fields["referencia"];

				$this->uf_cuenta_por_nivel($ls_sc_cuenta,&$ls_cuentasalida,&$lr_nivel);

				if (trim($ls_sc_cuenta)=='2250204010004')
				{
					$x=0;
				}

				$lb_excluir  =  $this->uf_verificar_cuentaproacu($ls_sc_cuenta,&$aa_cuentas_pa);

				if($ls_status=="C")
				{
					$ls_nivel=$ls_rnivel;        //"4"
				}//if
				else
				{
					$ls_nivel=$ls_rnivel;
				}//else
				if($ls_nivel<=$ai_nivel)
				{
					if (!$lb_excluir)
					{
						$this->ds_Prebalance->insertRow("sc_cuenta",$ls_sc_cuenta);
						$this->ds_Prebalance->insertRow("denominacion",$ls_denominacion);
						$this->ds_Prebalance->insertRow("status",$ls_status);
						$this->ds_Prebalance->insertRow("nivel",$ls_nivel);
						$this->ds_Prebalance->insertRow("rnivel",$ls_rnivel);
						$this->ds_Prebalance->insertRow("total_debe",$ld_total_debe);
						$this->ds_Prebalance->insertRow("total_haber",$ld_total_haber);
						$this->ds_Prebalance->insertRow("cueproacu",0);
						$this->ds_Prebalance->insertRow("referencia",$ls_referencia);
						$this->ds_Prebalance->insertRow("cuenta_salida",$ls_cuentasalida);

						$lb_valido = true;
					}
				}//if

				//if (!empty($ls_cueproacu))
				if ($ls_cueproacu!=="")
				{
					//echo $ls_sc_cuenta." cuerpo".$ls_cueproacu;
					$lb_valido2 = $this->uf_scg_cueproacu_saldo($ls_cueproacu,$ad_fecfin,&$as_sc_cuenta,&$as_denominacion,&$as_status ,&$as_rnivel,&$ad_total_debe,&$ad_total_haber );
					if($lb_valido2)
					{
						if($as_status=="C")
						{
							$ls_nivel=$ls_rnivel;        //"4"
						}//if
						else
						{
							$ls_nivel=$ls_rnivel;
						}//else

						if($ls_nivel<=$ai_nivel)
						{
							$this->ds_Prebalance->insertRow("sc_cuenta",$as_sc_cuenta);
							$this->ds_Prebalance->insertRow("denominacion",$as_denominacion);
							$this->ds_Prebalance->insertRow("status",$as_status);
							$this->ds_Prebalance->insertRow("nivel",$ls_nivel);
							$this->ds_Prebalance->insertRow("rnivel",$as_rnivel);
							$this->ds_Prebalance->insertRow("total_debe",$ad_total_debe);
							$this->ds_Prebalance->insertRow("total_haber",$ad_total_haber);
							$this->ds_Prebalance->insertRow("cueproacu",1);
							$this->ds_Prebalance->insertRow("referencia",$ls_referencia);
							$this->ds_Prebalance->insertRow("cuenta_salida",$ls_cuentasalida);
							//echo $as_sc_cuenta;
							$lb_valido2 = true;
						}//if
					}
				}
				if (($ls_status_act=='C'))
				{
					if (!$lb_excluir)
					{
						$this->ds_Prebalance->insertRow("sc_cuenta",'T'.$as_sc_cuenta);
						$this->ds_Prebalance->insertRow("denominacion",'Total ->'.$as_denominacion);
						$this->ds_Prebalance->insertRow("status",$as_status);
						$this->ds_Prebalance->insertRow("nivel",$ls_nivel);
						$this->ds_Prebalance->insertRow("rnivel",$as_rnivel);
						$this->ds_Prebalance->insertRow("total_debe",$ad_total_debe);
						$this->ds_Prebalance->insertRow("total_haber",$ad_total_haber);
						$this->ds_Prebalance->insertRow("cueproacu",9);
						$this->ds_Prebalance->insertRow("referencia",$ls_referencia);
						$this->ds_Prebalance->insertRow("cuenta_salida",$ls_cuentasalida);
						$ls_nivel_act= $rs_data->fields["rnivel"];

					}

				}

				$rs_data->MoveNext();
			}//while
			$li=$this->ds_Prebalance->getRowCount("sc_cuenta");
			if($li==0)
			{
				$lb_valido = false;
				return false;
			}//if
		} //else
		$ld_saldo_i=0;
		if($lb_valido)
		{
			$lb_valido=$this->uf_scg_reporte_select_saldo_ingreso_BG($ad_fecfin,$this->ls_ingreso,$ld_saldo_i);
		}
		if($lb_valido)
		{
			$ld_saldo_g=0;
			$lb_valido=$this->uf_scg_reporte_select_saldo_gasto_BG($ad_fecfin,$this->ls_gastos,$ld_saldo_g);
		}//if
		if($lb_valido)
		{
			$ld_saldo_ganancia=($ld_saldo_ganancia+($ld_saldo_i-$ld_saldo_g))*-1;
		}//if
			
		$la_sc_cuenta=array();
		$la_denominacion=array();
		$la_saldo=array();
		for($i=1;$i<=$ai_nivel;$i++)
		{
			$la_sc_cuenta[$i]="";
			$la_denominacion[$i]="";
			$la_saldo[$i]=0;
		}//for
		$li_nro_reg=0;
		$ld_saldo_resultado=0;
		$li_row=$this->ds_Prebalance->getRowCount("sc_cuenta");

		for($li_z=1;$li_z<=$li_row;$li_z++)
		{
			$ls_sc_cuenta=$this->ds_Prebalance->getValue("sc_cuenta",$li_z);
			$ls_cueproacu=$this->ds_Prebalance->getValue("cueproacu",$li_z);
			$ldec_debe=$this->ds_Prebalance->getValue("total_debe",$li_z);
			$ldec_haber=$this->ds_Prebalance->getValue("total_haber",$li_z);
			$li_nivel=$this->ds_Prebalance->getValue("nivel",$li_z);
			$li_rnivel=$this->ds_Prebalance->getValue("rnivel",$li_z);
			$li_status=$this->ds_Prebalance->getValue("status",$li_z);
			$ls_denominacion=$this->ds_Prebalance->getValue("denominacion",$li_z);
			$ls_referencia=$this->ds_Prebalance->getValue("referencia",$li_z);
			$ls_cuenta_salida=$this->ds_Prebalance->getValue("cuenta_salida",$li_z);

			$ls_tipo_cuenta=substr($ls_sc_cuenta,0,1);
			if  ($ls_cueproacu==1)
			{
				$ls_tipo_cuenta=$this->ls_activo;
			}
			switch($ls_tipo_cuenta){
				case $this->ls_activo:
					$ls_orden=1;
					break;
				case $this->ls_pasivo:
					$ls_orden=2;
					break;
				case $this->ls_capital:
					$ls_orden=3;
					break;
				case $this->ls_resultado:
					$ls_orden=4;
					break;
				case $this->ls_orden_d:
					$ls_orden=5;
					break;
				case $this->ls_orden_h:
					$ls_orden=6;
					break;
				default:
					$ls_orden=7;
			}

			//echo $ls_sc_cuenta." orden ".$ls_orden." cuerpoacu".$ls_cueproacu."<br>";

			$ldec_saldo=$ldec_debe-$ldec_haber;
			/*if  ($ls_cueproacu==1)
			 {
			 $ldec_saldo=-$ldec_saldo;
			 }*/
			if((($ls_tipo_cuenta==$this->ls_pasivo)||($ls_tipo_cuenta==$this->ls_resultado)||($ls_tipo_cuenta==$this->ls_capital))&&($li_nivel==1))
			{
				if($ldec_saldo<0)
				{
					$ldec_saldoAux = abs($ldec_saldo);
				}
				else
				{
					$ldec_saldoAux = $ldec_saldo;
				}
				$ld_saldo_resultado=$ld_saldo_resultado+$ldec_saldoAux;
			}

			/* if($li_nivel==4)
			 {
			 $li_nro_reg=$li_nro_reg+1;
			 $this->ds_Balance1->insertRow("orden",$ls_orden);
			 $this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
			 $this->ds_Balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
			 $this->ds_Balance1->insertRow("denominacion",$ls_denominacion);
			 $this->ds_Balance1->insertRow("nivel",$li_nivel);
			 $this->ds_Balance1->insertRow("rnivel",$li_rnivel);
			 $this->ds_Balance1->insertRow("status",$ls_status);
			 $this->ds_Balance1->insertRow("saldo",$ldec_saldo);
			 $this->ds_Balance1->insertRow("referencia",$ls_referencia);
			 $this->ds_Balance1->insertRow("cuenta_salida",$ls_cuenta_salida);
			 $this->ds_Balance1->insertRow("cueproacu",$ls_cueproacu);
			 }
			 else
			 {*/
			if(empty($la_sc_cuenta[$li_nivel]))
			{
				$la_sc_cuenta[$ls_nivel]=$ls_sc_cuenta;
				$la_denominacion[$ls_nivel]=$ls_denominacion;
				$la_saldo[$ls_nivel]=$ldec_saldo;
				$li_nro_reg=$li_nro_reg+1;
				$this->ds_Balance1->insertRow("orden",$ls_orden);
				$this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
				$this->ds_Balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
				$this->ds_Balance1->insertRow("denominacion",$ls_denominacion);
				$this->ds_Balance1->insertRow("status",$ls_status);
				$this->ds_Balance1->insertRow("nivel",-$li_nivel);
				$this->ds_Balance1->insertRow("rnivel",$li_rnivel);
				$this->ds_Balance1->insertRow("saldo",$ldec_saldo);
				$this->ds_Balance1->insertRow("referencia",$ls_referencia);
				$this->ds_Balance1->insertRow("cuenta_salida",$ls_cuenta_salida);
				$this->ds_Balance1->insertRow("cueproacu",$ls_cueproacu);
			}
			else
			{
				$this->uf_scg_reporte_calcular_total_BG($li_nro_reg,$ls_prev_nivel,$ls_nivel,$la_sc_cuenta,$la_denominacion,$la_saldo);
				$la_sc_cuenta[$ls_nivel]=$ls_sc_cuenta;
				$la_denominacion[$ls_nivel]=$ls_denominacion;
				$la_saldo[$ls_nivel]=$ldec_saldo;
				$li_nro_reg=$li_nro_reg+1;
				$this->ds_Balance1->insertRow("orden",$ls_orden);
				$this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
				$this->ds_Balance1->insertRow("sc_cuenta",$ls_sc_cuenta);
				$this->ds_Balance1->insertRow("denominacion",$ls_denominacion);
				$this->ds_Balance1->insertRow("nivel",-$li_nivel);
				$this->ds_Balance1->insertRow("status",$ls_status);
				$this->ds_Balance1->insertRow("rnivel",$li_rnivel);
				$this->ds_Balance1->insertRow("saldo",$ldec_saldo);
				$this->ds_Balance1->insertRow("referencia",$ls_referencia);
				$this->ds_Balance1->insertRow("cuenta_salida",$ls_cuenta_salida);
				$this->ds_Balance1->insertRow("cueproacu",$ls_cueproacu);
			}
			// }
			$ls_prev_nivel=$li_nivel;
		}

		$this->uf_scg_reporte_calcular_total_BG(&$li_nro_reg,$ls_prev_nivel,1,$la_sc_cuenta,$la_denominacion,$la_saldo);

		$ld_saldo_resultado=($ld_saldo_resultado+$ld_saldo_ganancia);

		$this->uf_scg_reporte_actualizar_resultado_BG($this->ls_cta_resultado,$ld_saldo_ganancia,$li_nro_reg,$ls_orden,$ai_nivel);

	 $li_total=$this->ds_Balance1->getRowCount("sc_cuenta");

	 for($li_i=1;$li_i<=$li_total;$li_i++)
	 {
	 	$ls_sc_cuenta    = $this->ds_Balance1->data["sc_cuenta"][$li_i];
	 	$li_cueproacu    = $this->ds_Balance1->data["cueproacu"][$li_i];
	 	$ld_saldo        = $this->ds_Balance1->data["saldo"][$li_i];
	 	if($li_cueproacu == 1)
	 	{
	 		$ls_cuenta_activo = $this->ds_Balance1->data["sc_cuenta"][$li_i-1];
	 		$ls_cuenta_pasivo = $this->ds_Balance1->data["sc_cuenta"][$li_i];
	 		$this->uf_actualizar_saldo_activos($ls_cuenta_activo,$ld_saldo,$this->ds_Balance1,"sc_cuenta","saldo");
	 		$this->uf_actualizar_saldo_pasivos($ls_cuenta_pasivo,$ld_saldo,$this->ds_Balance1,"sc_cuenta","saldo");
	 	}
	 }

	 $li_total=$this->ds_Balance1->getRowCount("sc_cuenta");

	 for ($li_i=1;$li_i<=$li_total;$li_i++)
	 {
	 	$ls_sc_cuenta    = $this->ds_Balance1->data["sc_cuenta"][$li_i];
	 	$ls_orden        = $this->ds_Balance1->data["orden"][$li_i];
	 	$li_nro_reg      = $this->ds_Balance1->data["num_reg"][$li_i];
	 	$ls_denominacion = $this->ds_Balance1->data["denominacion"][$li_i];
	 	$ls_nivel        = $this->ds_Balance1->data["nivel"][$li_i];
	 	$ls_status        = $this->ds_Balance1->data["status"][$li_i];
	 	$ls_rnivel        = $this->ds_Balance1->data["rnivel"][$li_i];
	 	$ld_saldo        = $this->ds_Balance1->data["saldo"][$li_i];
	 	$ls_referencia   = $this->ds_Balance1->data["referencia"][$li_i];
	 	$ls_cuenta_salida = $this->ds_Balance1->data["cuenta_salida"][$li_i];
	 	if (!empty($ls_sc_cuenta))
	 	{
	 		$li_pos          = $this->ds_Prebalance->find("sc_cuenta",$ls_sc_cuenta);
	 		if ($li_pos>0)
	 		{
	 			$ls_rnivel=$this->ds_Prebalance->data["rnivel"][$li_pos];
	 		}
	 		/*else
	 		 {
	 		 $ls_rnivel=0;
	 		 }*/
	 		if ($ls_nivel<=$ai_nivel)
	 		{
	 			$ds_Balance2->insertRow("orden",$ls_orden);
	 			$ds_Balance2->insertRow("num_reg",$li_nro_reg);
	 			$ds_Balance2->insertRow("sc_cuenta",$ls_sc_cuenta);
	 			$ds_Balance2->insertRow("denominacion",$ls_denominacion);
	 			$ds_Balance2->insertRow("nivel",$ls_nivel);
	 			$ds_Balance2->insertRow("rnivel",$ls_rnivel);
	 			$ds_Balance2->insertRow("status",$ls_status);
	 			$ds_Balance2->insertRow("saldo",$ld_saldo);
	 			//$ds_Balance2->insertRow("rnivel",$ls_rnivel);
	 			$ds_Balance2->insertRow("total",$ld_saldo_resultado);
	 			$ds_Balance2->insertRow("referencia",$ls_referencia);
	 			$ds_Balance2->insertRow("cuenta_salida",cuenta_salida);
	 		}
	 	}
	 }
	 $li_tot = $ds_Balance2->getRowCount("sc_cuenta");

	 global $arr_cuenta,$arr_denomina;
	 //$arr_cuenta    = array($ai_nivel);
	 //$arr_denomina  = array($ai_nivel);
	 //$arr_saldos    = array($ai_nivel);
	 $arr_cuenta    = array();
	 $arr_denomina  = array();
	 $arr_saldos    = array();

	 for ($li_i=1;$li_i<=$li_tot;$li_i++)
	 {
	 	$ls_sc_cuenta       = $ds_Balance2->data["sc_cuenta"][$li_i];
	 	$ls_orden           = $ds_Balance2->data["orden"][$li_i];
	 	$li_nro_reg         = $ds_Balance2->data["num_reg"][$li_i];
	 	$ls_denominacion    = $ds_Balance2->data["denominacion"][$li_i];
	 	$ls_nivel           = $ds_Balance2->data["nivel"][$li_i];
	 	$ls_status           = $ds_Balance2->data["status"][$li_i];
	 	$ld_saldo           = $ds_Balance2->data["saldo"][$li_i];
	 	$ls_rnivel          = $ds_Balance2->data["rnivel"][$li_i];
	 	$ld_saldo_resultado = $ds_Balance2->data["total"][$li_i];
	 	$ls_referencia      = $ds_Balance2->data["referencia"][$li_i];
	 	$ls_cuenta_salida   = $ds_Balance2->data["cuenta_salida"][$li_i];

	 	if ($ls_rnivel<=$ai_nivel)
	 	{
	 		$this->ds_reporte->insertRow("orden",$ls_orden);
	 		$this->ds_reporte->insertRow("num_reg",$li_nro_reg);
	 		$this->ds_reporte->insertRow("sc_cuenta",$ls_sc_cuenta);
	 		$this->ds_reporte->insertRow("denominacion",$ls_denominacion);
	 		$this->ds_reporte->insertRow("status",$ls_status);
	 		$this->ds_reporte->insertRow("nivel",$ls_nivel);
	 		$this->ds_reporte->insertRow("saldo",$ld_saldo);
	 		$this->ds_reporte->insertRow("rnivel",$ls_rnivel);
	 		$this->ds_reporte->insertRow("total",$ld_saldo_resultado);
	 		$this->ds_reporte->insertRow("referencia",$ls_referencia);
	 		$this->ds_reporte->insertRow("cuenta_salida",$ls_cuenta_salida);
	 		$this->ds_reporte->insertRow("cerrado",'');
	 	}
	 }

	 //chequeo los niveles y ajusto los valores de nivel, status y  referencia
	 $li_row=$this->ds_reporte->getRowCount("sc_cuenta");
	 for($li_z=1;$li_z<=$li_row;$li_z++)
	 {
	 	$ls_sc_cuenta   = $this->ds_reporte->getValue("sc_cuenta",$li_z);
	 	$li_pos         = $this->ds_Prebalance->find("sc_cuenta",$ls_sc_cuenta);
	 	if ($li_pos>0)
	 	{
	 		$ls_rnivel=$this->ds_Prebalance->data["rnivel"][$li_pos];
	 		$ls_status=$this->ds_Prebalance->data["status"][$li_pos];
	 		$ls_referencia=$this->ds_Prebalance->data["referencia"][$li_pos];
	 		$this->ds_reporte->data["rnivel"][$li_z]=$ls_rnivel;
	 		$this->ds_reporte->data["status"][$li_z]=$ls_status;
	 		if ($ls_status=='S')
	 		{
	 			$this->ds_reg_niveles->insertRow("sc_cuenta",$ls_sc_cuenta);
	 			$this->ds_reg_niveles->insertRow("cerrado",'N');
	 			$this->ds_reg_niveles->insertRow("referencia",$ls_referencia);
	 		}
	 	}
	 	else
	 	{
	 		$ls_rnivel=0;
	 	}
	 }

	 // armo el datastore final

	 $an_nivel = intval($ai_nivel);

	 $nPrevNivel = intval($this->ds_reporte->data["rnivel"][$li_row]);

	 $nRegNo = 0;
	 $li_row=$this->ds_reporte->getRowCount("sc_cuenta");
	 for($li_z=1;$li_z<=$li_row;$li_z++)
	 {
	 	$ls_sc_cuenta       = $this->ds_reporte->data["sc_cuenta"][$li_z];
	 	$ls_orden           = $this->ds_reporte->data["orden"][$li_z];
	 	$li_nro_reg         = $this->ds_reporte->data["num_reg"][$li_z];
	 	$ls_denominacion    = $this->ds_reporte->data["denominacion"][$li_z];
	 	$ls_nivel           = $this->ds_reporte->data["nivel"][$li_z];
	 	$ls_status          = $this->ds_reporte->data["status"][$li_z];
	 	$ld_saldo           = $this->ds_reporte->data["saldo"][$li_z];
	 	$ls_rnivel          = $this->ds_reporte->data["rnivel"][$li_z];
	 	$ld_saldo_resultado = $this->ds_reporte->data["total"][$li_z];
	 	$ls_referencia      = $this->ds_reporte->data["referencia"][$li_z];
	 	$ls_cuenta_salida   = $this->ds_reporte->data["cuenta_salida"][$li_z];
	 	$ls_cerrado         = $this->ds_reporte->data["cerrado"][$li_z];
	 	$ln_nivel           = intval($ls_rnivel);
	 	if (empty($ld_saldo))
	 	{
	 		$ld_saldo=0;
	 	}
	 	if ($ln_nivel==$an_nivel)
	 	{
				$nRegNo++;
				//echo "1.".$ls_sc_cuenta."<br>";
				$this->ds_reportef->insertRow("sc_cuenta",$ls_sc_cuenta);
				$this->ds_reportef->insertRow("orden",$ls_orden);
				$this->ds_reportef->insertRow("num_reg",$li_nro_reg);
				$this->ds_reportef->insertRow("denominacion",$ls_denominacion);
				$this->ds_reportef->insertRow("nivel",$ls_nivel);
				$this->ds_reportef->insertRow("status",$ls_status);
				$this->ds_reportef->insertRow("saldo",$ld_saldo);
				$this->ds_reportef->insertRow("rnivel",$ls_rnivel);
				$this->ds_reportef->insertRow("total",$ld_saldo_resultado);
				$this->ds_reportef->insertRow("referencia",$ls_referencia);
				$this->ds_reportef->insertRow("cuenta_salida",$ls_cuenta_salida);
				$this->ds_reportef->insertRow("cerrado",'');
	 	}
	 	else
	 	{
				if (empty($arr_cuenta[intval($ls_rnivel)]))
				{
					$arr_cuenta[$ln_nivel]      = $ls_sc_cuenta;
					$arr_denomina[$ln_nivel]    = $ls_denominacion;
					$arr_saldos[$ln_nivel]      = $ld_saldo;
					$nRegNo++;
					$this->ds_reportef->insertRow("sc_cuenta",$ls_sc_cuenta);
					$this->ds_reportef->insertRow("orden",$ls_orden);
					$this->ds_reportef->insertRow("num_reg",$li_nro_reg);
					$this->ds_reportef->insertRow("denominacion",$ls_denominacion);
					$this->ds_reportef->insertRow("nivel",$ls_nivel);
					$this->ds_reportef->insertRow("status",$ls_status);
					$this->ds_reportef->insertRow("saldo",$ld_saldo);
					$this->ds_reportef->insertRow("rnivel",$ls_rnivel);
					$this->ds_reportef->insertRow("total",$ld_saldo_resultado);
					$this->ds_reportef->insertRow("referencia",$ls_referencia);
					$this->ds_reportef->insertRow("cuenta_salida",$ls_cuenta_salida);
					$this->ds_reportef->insertRow("cerrado",'');
				}
				else
				{
					$a=1;
					$this->uf_downstair($nRegNo,$nPrevNivel,intval($ls_rnivel),&$arr_cuenta,&$arr_denomina,&$arr_saldos);
					$arr_cuenta[$ln_nivel]      = $ls_sc_cuenta;
					$arr_denomina[$ln_nivel]    = $ls_denominacion;
					$arr_saldos[$ln_nivel]      = $ld_saldo;
					$nRegNo++;
					$this->ds_reportef->insertRow("sc_cuenta",$ls_sc_cuenta);
					$this->ds_reportef->insertRow("orden",$ls_orden);
					$this->ds_reportef->insertRow("num_reg",$li_nro_reg);
					$this->ds_reportef->insertRow("denominacion",$ls_denominacion);
					$this->ds_reportef->insertRow("nivel",$ls_nivel);
					$this->ds_reportef->insertRow("status",$ls_status);
					$this->ds_reportef->insertRow("saldo",$ld_saldo);
					$this->ds_reportef->insertRow("rnivel",$ls_rnivel);
					$this->ds_reportef->insertRow("total",$ld_saldo_resultado);
					$this->ds_reportef->insertRow("referencia",$ls_referencia);
					$this->ds_reportef->insertRow("cuenta_salida",$ls_cuenta_salida);
					$this->ds_reportef->insertRow("cerrado",'');
				}
	 	}

	 	$nPrevNivel = intval($ls_rnivel);
	 }//for

	 $this->uf_downstair($nRegNo,$nPrevNivel,1,&$arr_cuenta,&$arr_denomina,&$arr_saldos);
	 $li_registro = 0;
	 unset($this->ds_Prebalance,$this->ds_Balance1,$ds_Balance2);
	 return $lb_valido;
	}



	/****************************************************************************************************************************************/
	function uf_downstair($li_npos,$nHighStair,$nLowerStair,$arr_cuenta,$arr_denomina,&$arr_saldos)
	{
		for($li_z=($nHighStair-1);$li_z>=$nLowerStair;$li_z--)
		{
			if (!empty($arr_cuenta[$li_z]))
			{
				$nRegNo++;
				//inserta en el datastore final
				//echo "3.".$arr_cuenta[$li_z]."<br>";
				$this->ds_reportef->insertRow("sc_cuenta",$arr_cuenta[$li_z]);
				$this->ds_reportef->insertRow("orden",$ls_orden);
				$this->ds_reportef->insertRow("num_reg",$li_nro_reg);
				$this->ds_reportef->insertRow("denominacion",'TOTAL '.$arr_denomina[$li_z]);
				$this->ds_reportef->insertRow("nivel",$ls_nivel);

				$this->ds_reportef->insertRow("saldo",$arr_saldos[$li_z]);

				$ls_sc_cuentat   = $arr_cuenta[$li_z];
				$li_pos         = $this->ds_Prebalance->find("sc_cuenta",$ls_sc_cuentat);
				if ($li_pos>0)
				{
					$ls_rnivel=$this->ds_Prebalance->data["rnivel"][$li_pos];
					$ls_status=$this->ds_Prebalance->data["status"][$li_pos];
				}
				else
				{
					$li_pos          = $this->ds_reportef->find("sc_cuenta",$ls_sc_cuentat);
					if ($li_pos>0)
					{
						$ls_rnivel=$this->ds_reportef->data["rnivel"][$li_pos];
						$ls_status=$this->ds_reportef->data["status"][$li_pos];
					}
					else
					{
						$ls_status = "";
						$ls_rnivel=0;
					}
				}
				$this->ds_reportef->insertRow("status",$ls_status);
				$this->ds_reportef->insertRow("rnivel",$ls_rnivel);
				$this->ds_reportef->insertRow("total",$ld_saldo_resultado);
				$this->ds_reportef->insertRow("referencia",$ls_referencia);
				$this->ds_reportef->insertRow("cuenta_salida",$ls_cuenta_salida);
				$this->ds_reportef->insertRow("cerrado",'S');

				$arr_cuenta[$li_z]      = NULL;
				$arr_denomina[$li_z]    = NULL;
				$arr_saldos[$li_z]      = NULL;
			}//if
		}//for
		//echo "<br><br><br>";

	}

	/****************************************************************************************************************************************/
	//Function uf_cuenta_por_nivel
	//retorna un substring de la cuanta contable segun el nivel que tenga
	//no retorna texto formateado segun la mascara, solo substring
	function uf_cuenta_por_nivel($as_cuenta,&$ls_cuentasalida,&$ls_nivel)
	{
		$ls_nivel=$this->int_scg->uf_scg_obtener_nivel($as_cuenta);
		$ls_mascara= trim($_SESSION["la_empresa"]["formcont"]).'-';
		$li_niveles = substr_count($ls_mascara,'-',1);
		$pos = 0;
		$cant_espacios = 0;
		for ( $li_pos=1;$li_pos<=$li_niveles;$li_pos++)
		{
			$cadena='';
			$lbValido=true;
			while($lbValido)
			{
				$cad = substr($ls_mascara,$pos,1);
				if ($cad=='-')
				{
					$pos++;
					$lbValido=false;
				}
				else
				{
					$cadena = $cadena.$cad;
					$pos++;
				}
			}
			$cant_espacios += strlen($cadena);
			$espacios[$li_pos]=$cant_espacios;
		}
		$ls_cuentasalida = substr($as_cuenta,0,$espacios[$ls_nivel]);
	}



	/****************************************************************************************************************************************/
	function denominacion_cuenta_totalizadora($as_cuenta,&$as_denominacion,&$ls_referencia_ct,&$li_nivel_ct)
	{
		$li_pos  = $this->ds_Prebalance->find("sc_cuenta",$as_cuenta);
		if ($li_pos>0)
		{
			$as_denominacion    = $this->ds_Prebalance->data["denominacion"][$li_pos];
			$ls_referencia_ct   = $this->ds_Prebalance->data["referencia"][$li_pos];
			$li_nivel_ct        = $this->ds_Prebalance->data["rnivel"][$li_pos];
		}
		else
		{
			$as_denominacion    = '';
			$ls_referencia_ct   = '';
			$li_nivel_ct        = '';
		}
	}


	/****************************************************************************************************************************************/
	function uf_scg_cueproacu_saldo($cueproacu,$ad_fecfin,&$as_sc_cuenta,&$as_denominacion,&$as_status ,&$as_rnivel,&$ad_total_debe,&$ad_total_haber)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function :    uf_scg_cueproacu_saldo
		//         Access :    private
		//     Argumentos :    cueproacu  // cuenta de provisiones acumuladas o de depreciacion
		//        Returns :    Retorna datastore con informacion de la cuenta
		//    Description :    Busca en scg_cuentas la informacion de una cuenta, usando la misma consulta del balance general
		//     Creado por :
		// Fecha Creacion :    20/01/2010                          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$cueproacu = trim($cueproacu);
		$ls_codemp=$this->la_empresa["codemp"];
		$ad_fecfin=$this->io_fun->uf_convertirdatetobd($ad_fecfin);

		if($_SESSION["ls_gestor"]=='INFORMIX')
		{
			$ls_sql="SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel,
            (select case sum(debe_mes) when null then 0 else sum(debe_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' AND sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_debe,
            (select case sum(haber_mes) when null then 0 else sum(haber_mes) end FROM scg_saldos WHERE codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' AND sc_cuenta=SC.sc_cuenta GROUP BY codemp,sc_cuenta) as total_haber,
            0 as nivel,SC.cueproacu  
            FROM scg_cuentas SC 
            where  (SC.sc_cuenta like '".$cueproacu."' ) 
            ORDER BY SC.sc_cuenta ";
		}
		else
		{
			$ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
            "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
            "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,SC.cueproacu ".
            " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
            "                                             coalesce(sum(haber_mes),0) as T_Haber ".
            "                                      FROM   scg_saldos ".
            "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
            "                                      GROUP BY codemp,sc_cuenta) as curSaldo ".
            " ON curSaldo.codemp = SC.codemp AND SC.sc_cuenta=curSaldo.sc_cuenta ".
            " WHERE SC.codemp='".$ls_codemp."' AND ".
            "       SC.sc_cuenta like '".$cueproacu."%'  ". 
            " ORDER BY trim(SC.sc_cuenta) "; 
		}

		$lb_valido = true;
		$rs_data_cta=$this->io_sql->select($ls_sql);
		if($rs_data_cta===false)
		{// error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_cueproacu_saldo ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
			//print $this->io_sql->message;
			$lb_valido = false;
		}
		else
		{
			$ld_saldo_ganancia=0;
			while($row=$this->io_sql->fetch_row($rs_data_cta))
			{
				$as_sc_cuenta=$row["sc_cuenta"];
				$as_denominacion=$row["denominacion"];
				$as_status=$row["status"];
				$as_rnivel=$row["rnivel"];
				$ad_total_debe=$row["total_debe"];
				$ad_total_haber=$row["total_haber"];
			}
		}

		return $lb_valido;
			
	}

	/****************************************************************************************************************************************/
	function uf_scg_crea_array_cueproacu()
	{
		$ls_codemp=$this->la_empresa["codemp"];

		if($_SESSION["ls_gestor"]=='INFORMIX')
		{
			$ls_sql= "select cueproacu as sc_cuenta
                  from   scg_cuentas
                  where  codemp='$ls_codemp'        AND 
                         (cueproacu is not null)    AND 
                         (length(cueproacu)<>0)";
		}
		else
		{
			$ls_sql= "select cueproacu as sc_cuenta
                  from   scg_cuentas
                  where  codemp='$ls_codemp'        AND
                         (cueproacu is not null)    AND
                         (length(cueproacu)<>0)";    
		}
		$lb_valido = true;
		$rs_data_ctpa=$this->io_sql->select($ls_sql);
		if($rs_data_ctpa===false)
		{// error interno sql
			$this->is_msg_error="Error en consulta metodo uf_scg_cueproacu_saldo ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
			//print $this->io_sql->message;
			$lb_valido = false;
		}
		else
		{
			$pos=0;
			while($row=$this->io_sql->fetch_row($rs_data_ctpa))
			{
				$pos++;
				$as_cuenta_pa[$pos]=$row["sc_cuenta"];
			}
		}
		return $as_cuenta_pa;
	}

	function uf_verificar_cuentaproacu($as_sc_cuenta,&$aa_cuentas_pa)
	{
		//$as_cuenta_pa
		$lb_encontrado = false;
		$li_tot=count($aa_cuentas_pa);
		for($li_i=1;$li_i<=$li_tot;$li_i++)
		{
			if (trim($as_sc_cuenta)==trim($aa_cuentas_pa[$li_i]))
			{
				$lb_encontrado = true;
			}
		}
		return $lb_encontrado;
	}

	/****************************************************************************************************************************************/


	/****************************************************************************************************************************************/
	function  uf_scg_reporte_select_saldo_ingreso_BG($adt_fecini,$ai_ingreso,&$ad_saldo)
	{
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_ingreso_BG
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde
		//              	    $ai_ingreso  // numero de la cuenta de ingraso
	 //                     $ad_saldo  //  total saldo (referencia)
		//	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado
	 //     Creado por :    Ing. Yozelin Barragan.
	 // Fecha Creacion :    02/05/2006          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->la_empresa["codemp"];
	 $lb_valido=true;
	 if($_SESSION["ls_gestor"]=='INFORMIX')
	 {
	 	$ls_sql=" SELECT case sum(SD.debe_mes-SD.haber_mes) when null then 0 else sum(SD.debe_mes-SD.haber_mes) end saldo ".
                 " FROM   scg_cuentas SC, scg_saldos SD ".
                 " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND (SC.status='C') AND ".
			     "        fecsal<='".$adt_fecini."' AND (SC.sc_cuenta like '".$ai_ingreso."%') AND SC.sc_cuenta IN (SELECT DISTINCT sc_cuenta FROM scg_dt_cmp WHERE fecha <= '".$adt_fecini."' AND sc_cuenta LIKE '".$ai_ingreso."%')";			
		}
		else
		{
			$ls_sql=" SELECT COALESCE(sum(SD.haber_mes-SD.debe_mes),0) as saldo ".
                 " FROM   scg_cuentas SC, scg_saldos SD ".
                 " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND (SC.status='C') AND ".
			     "        fecsal<='".$adt_fecini."' AND (SC.sc_cuenta like '".$ai_ingreso."%') AND SC.sc_cuenta IN (SELECT DISTINCT sc_cuenta FROM scg_dt_cmp WHERE fecha <= '".$adt_fecini."' AND sc_cuenta LIKE '".$ai_ingreso."%')";
		}


	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
	 	$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_ingreso_BG ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
	 	$lb_valido = false;
	 }
	 else
	 {
	 	if($row=$this->io_sql->fetch_row($rs_data))
	 	{
	 		$ad_saldo=$row["saldo"];
	 	}
	 	$this->io_sql->free_result($rs_data);
	 }
	 return $lb_valido;
	}//fin uf_scg_reporte_obtener_saldo_ingreso

	function  uf_scg_reporte_select_saldo_gasto_BG($adt_fecini,$ai_gasto,&$ad_saldo)
	{
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_gasto_BG
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde
		//              	    $ai_gasto  // numero de la cuenta de gasto
	 //                     $ad_saldo  //  total saldo (referencia)
		//	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado
	 //     Creado por :    Ing. Yozelin Barragan.
	 // Fecha Creacion :    02/05/2006          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->la_empresa["codemp"];
	 $lb_valido=true;
	 if($_SESSION["ls_gestor"]=='INFORMIX')
	 {
	 	$ls_sql=" SELECT case sum(SD.debe_mes-SD.haber_mes) when null then 0 else sum(SD.debe_mes-SD.haber_mes) end saldo ".
                 " FROM   scg_cuentas SC, scg_saldos SD ".
                 " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND (SC.status='C') AND ".
			     "        fecsal<='".$adt_fecini."' AND (SC.sc_cuenta like '".$ai_gasto."%') AND SC.sc_cuenta IN (SELECT DISTINCT sc_cuenta FROM scg_dt_cmp WHERE fecha <= '".$adt_fecini."' AND sc_cuenta LIKE '".$ai_gasto."%')";			
		}
	 else
	 {
	 	$ls_sql=" SELECT COALESCE(sum(SD.debe_mes-SD.haber_mes),0) as saldo ".
             " FROM   scg_cuentas SC, scg_saldos SD ".
             " WHERE (SC.sc_cuenta = SD.sc_cuenta) AND (SC.codemp = SD.codemp) AND (SC.status='C') AND ".
			 "        fecsal<='".$adt_fecini."' AND (SC.sc_cuenta like '".$ai_gasto."%') AND SC.sc_cuenta IN (SELECT DISTINCT sc_cuenta FROM scg_dt_cmp WHERE fecha <= '".$adt_fecini."' AND sc_cuenta LIKE '".$ai_gasto."%')";			 
	 }
	 //  var_dump($ls_sql);
	 //	die();
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data===false)
	 {// error interno sql
	 	$this->is_msg_error="Error en consulta metodo uf_scg_reporte_select_saldo_gasto_BG ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
	 	$lb_valido = false;
	 }
	 else
	 {
	 	if($row=$this->io_sql->fetch_row($rs_data))
	 	{
	 		$ad_saldo=$row["saldo"];
	 	}
	 	$this->io_sql->free_result($rs_data);
	 }
	 return $lb_valido;
	}//fin uf_scg_reporte_select_saldo_gasto_BG

	function  uf_scg_reporte_calcular_total_BG(&$ai_nro_regi,$as_prev_nivel,$as_nivel,&$aa_sc_cuenta,$aa_denominacion,$aa_saldo)
	{
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_calcular_total_BG
	 //         Access :	private
	 //     Argumentos :    $as_prev_nivel  // nivel de la cuenta anterior
		//              	    $as_nivel  // nivel de  la cuenta
	 //                     $ai_nro_regi  //  numero de registro (referencia)
	 //                     $aa_sc_cuenta  // arreglo de cuentas (referencia)
	 //                     $aa_denominacion // arreglo de denominacion
	 //                     $aa_saldo // arreglo de saldo
		//	       Returns :	Retorna true o false si se realizo el calculo del total para el reporte
	 //	   Description :	Metodo que genera un monto total para la cuenta del balance general
	 //     Creado por :    Ing. Yozelin Barragan.
	 // Fecha Creacion :    08/05/2006          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $i=$as_prev_nivel-1;
	 $x=$as_nivel-1;
	 if($i>$x)
	 {
	 	$ls_tipo_cuenta=substr($aa_sc_cuenta[$i],0,1);
	 	if($ls_tipo_cuenta==$this->ls_activo) {	$ls_orden="1"; }
	 	if($ls_tipo_cuenta==$this->ls_pasivo) {	$ls_orden="2"; }
	 	if($ls_tipo_cuenta==$this->ls_capital) { $ls_orden="3"; }
	 	if($ls_tipo_cuenta==$this->ls_resultado) { $ls_orden="4"; }
	 	if($ls_tipo_cuenta==$this->ls_orden_d) { $ls_orden="5"; }
	 	if($ls_tipo_cuenta==$this->ls_orden_h){ $ls_orden="6"; }
	 	else{$ls_orden="7";}
	 	if(!empty($aa_sc_cuenta[$i]))
	 	{
	 		$ai_nro_regi=$ai_nro_regi+1;
	 		$this->ds_Balance1->insertRow("orden",$ls_orden);
	 		$this->ds_Balance1->insertRow("num_reg",$ai_nro_regi);
	 		$this->ds_Balance1->insertRow("sc_cuenta",$aa_sc_cuenta[$i]);
	 		$this->ds_Balance1->insertRow("denominacion","Total ".$aa_denominacion[$i]);
	 		$this->ds_Balance1->insertRow("nivel",$i);
	 		$this->ds_Balance1->insertRow("saldo",$aa_saldo[$i]);
	 		$aa_sc_cuenta[$i]="";
	 		$i--;
	 	}//if
	 }//if
	}//uf_scg_reporte_calcular_total_BG

	function  uf_scg_reporte_actualizar_resultado_BG($ai_c_resultad,$ad_saldo_ganancia,$ai_nro_reg,$as_orden,$ai_nivel)
	{
	 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_actualizar_resultado_BG
	 //         Access :	private
	 //     Argumentos :    $ai_c_resultad  // cuenta de resultado
		//              	    $ad_saldo_ganancia  // saldo
		//              	    $as_sc_cuenta  // cuenta
		//	       Returns :	Retorna true o false si se realizo el calculo para el reporte
	 //	   Description :	Metodo que genera un monto actualizado de la cuenta del resultado
	 //     Creado por :    Ing. Yozelin Barragan
	 // Fecha Creacion:     08/05/2006          Fecha ltima Modificacion :      Hora :
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_next_cuenta=$ai_c_resultad;
	 $ld_saldo=0;
	 $this->ds_auxiliar = new class_datastore();
	 $ls_nivel=$this->int_scg->uf_scg_obtener_nivel($ls_next_cuenta);
	 $li_nro_reg=$ai_nro_reg;
	 while($ls_nivel>=1)
	 {
	 	$li_pos=$this->ds_Balance1->find("sc_cuenta",$ls_next_cuenta);
	 	if($li_pos>0)
	 	{
	 		$ld_saldo=$this->ds_Balance1->getValue("saldo",$li_pos);
	 		$ld_saldo=$ld_saldo+$ad_saldo_ganancia;
	 		$this->ds_Balance1->updateRow("saldo",$ld_saldo,$li_pos);
	 		//print "NIVEL->".$ls_nivel."   CUENTA->".$ls_next_cuenta."  SALDO->".$ld_saldo."  GANANCIA->".$ad_saldo_ganancia."<br>";
	 	}
	 	else
	 	{

	 		$lb_valido=$this->uf_select_denominacion($ls_next_cuenta,$ls_denominacion);
	 		if($lb_valido)
				{
					if ($ls_nivel<=$ai_nivel)
					{
						//$li_nro_reg++;
						$ls_status = "";
						$ls_referencia = "";
						$li_nivel = 0;
						$lb_valido = $this->uf_obtener_status_referencia_nivel($ls_next_cuenta,$ls_status,$ls_referencia,$li_nivel);
						if($lb_valido)
						{
							/*$this->ds_Balance1->insertRow("orden",$as_orden);
							 $this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
							 $this->ds_Balance1->insertRow("sc_cuenta",$ls_next_cuenta);
							 $this->ds_Balance1->insertRow("denominacion",$ls_denominacion);
							 $this->ds_Balance1->insertRow("nivel",$ls_nivel);
							 $this->ds_Balance1->insertRow("saldo",$ad_saldo_ganancia); */
							$this->ds_auxiliar->insertRow("status",$ls_status);
							$this->ds_auxiliar->insertRow("orden",$as_orden);
							//$this->ds_auxiliar->insertRow("num_reg",$li_nro_reg);
							$this->ds_auxiliar->insertRow("sc_cuenta",$ls_next_cuenta);
							$this->ds_auxiliar->insertRow("denominacion",$ls_denominacion);
							$this->ds_auxiliar->insertRow("nivel",$ls_nivel);
							$this->ds_auxiliar->insertRow("saldo",$ad_saldo_ganancia);
							$this->ds_auxiliar->insertRow("referencia",$ls_referencia);
						}
					}
				}
	 	}
	 	if($ls_nivel==1)
	 	{
			 $li_totrow = $this->ds_auxiliar->getRowCount("sc_cuenta");
			 if($li_totrow>0)
			 {
			  $this->ds_auxiliar->sortData("sc_cuenta");
			  for($i=1;$i<=$li_totrow;$i++)
			  {
			   $ls_orden   = $this->ds_auxiliar->getValue("orden",$i);
			   //$li_num_reg = $this->ds_auxiliar->getValue("num_reg",$i);
			   $li_nro_reg++;
			   $ls_cuenta  = $this->ds_auxiliar->getValue("sc_cuenta",$i);
			   $ls_dencta  = $this->ds_auxiliar->getValue("denominacion",$i);
			   $li_nivcta  = $this->ds_auxiliar->getValue("nivel",$i);
			   $ld_saldo   = $this->ds_auxiliar->getValue("saldo",$i);
			   $ls_status   = $this->ds_auxiliar->getValue("status",$i);
			   $ls_referencia   = $this->ds_auxiliar->getValue("referencia",$i);
			   $this->ds_Balance1->insertRow("orden",$ls_orden);
			   $this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
			   $this->ds_Balance1->insertRow("sc_cuenta",$ls_cuenta);
			   $this->ds_Balance1->insertRow("status",$ls_status);
			   $this->ds_Balance1->insertRow("denominacion",$ls_dencta);
			   $this->ds_Balance1->insertRow("nivel",-$li_nivcta);
			   $this->ds_Balance1->insertRow("saldo",$ld_saldo);
			   $this->ds_Balance1->insertRow("rnivel",$li_nivcta);
			   $this->ds_Balance1->insertRow("referencia",$ls_referencia);
			   $this->ds_Balance1->insertRow("cuenta_salida",$ls_cuenta);
			   $this->ds_Balance1->insertRow("cueproacu","");
			  }
			 }
			 return;
	 	}//if
	 	$ls_next_cuenta=$this->int_scg->uf_scg_next_cuenta_nivel($ls_next_cuenta);
	 	$ls_nivel=$this->int_scg->uf_scg_obtener_nivel($ls_next_cuenta);

	 }//while

	}//uf_scg_reporte_actualizar_resultado_BG

	function uf_select_denominacion($as_sc_cuenta,&$as_denominacion)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function:  uf_select_denominacion
		//	     Arguments:  $as_sc_cuenta  // codigo de la cuenta
		//                   $as_denominacion  // denominacion de la cuenta (referencia)
		//	       Returns:	 retorna un arreglo con las cuentas inferiores
		//	   Description:  Busca la denominacion de la cuenta
		//     Creado por :  Ing. Yozelin Barragan
		// Fecha Creacion :  14/08/2006                      Fecha ltima Modificacion :
		///////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido=true;
		$ls_codemp = $this->la_empresa["codemp"];
		$ls_sql = "SELECT denominacion FROM scg_cuentas WHERE sc_cuenta='".$as_sc_cuenta."' AND codemp='".$ls_codemp."' ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data===false)
		{
			$lb_valido=false;
			$this->is_msg_error="Error en consulta metodo uf_select_denominacion ".$this->io_fun->uf_convertirmsg($this->io_sql->message);
		}
		else
		{
			if($row=$this->io_sql->fetch_row($rs_data))
			{
				$as_denominacion=$row["denominacion"];
			}
			$this->io_sql->free_result($rs_data);
		}
		return  $lb_valido;
	}//uf_select_denominacion

	function uf_balance_general_consolidado($ad_fecfin)
	{
		$lb_valido=true;
		$ds_Balance2=new class_datastore();
		$ldec_resultado=0;
		$ld_saldo_ganancia=0;
		$this->ls_activo    = trim($this->la_empresa["activo"]);
		$this->ls_pasivo    = trim($this->la_empresa["pasivo"]);
		$this->ls_capital   = trim($this->la_empresa["capital"]);
		$this->ls_orden_d   = trim($this->la_empresa["orden_d"]);
		$this->ls_orden_h   = trim($this->la_empresa["orden_h"]);
		$this->ls_ingreso   = trim($this->la_empresa["ingreso"]);
		$this->ls_gastos    = trim($this->la_empresa["gasto"]);

		$this->ls_cta_resultado = trim($this->la_empresa["c_resultad"]);
		$this->ls_resultado = trim($this->la_empresa["resultado"]);

		$ad_fecfin=$this->io_fun->uf_convertirdatetobd($ad_fecfin);
		$ls_codemp=$this->la_empresa["codemp"];

	 /*$ls_sql = "SELECT TRIM(scg_cuentas_consolida.sc_cuenta) as sc_cuenta, scg_cuentas_consolida.nivel,
	  scg_cuentas_consolida.denominacion,0 as mondeb, 0 as monhab
	  FROM scg_cuentas_consolida
	  WHERE sc_cuenta like '".$this->ls_activo."%'
	  OR sc_cuenta like '".$this->ls_pasivo."%'
	  OR sc_cuenta like '".$this->ls_resultado."%'
	  OR sc_cuenta like '".$this->ls_capital."%'
	  OR sc_cuenta like '".$this->ls_orden_d."%'
	  OR sc_cuenta like '".$this->ls_orden_h."%'
	  UNION
	  SELECT TRIM(sc_cuenta) as sc_cuenta,0 as nivel,'' as denominacion,coalesce(sum(debe_mes),0)as mondeb,
	  coalesce(sum(haber_mes),0) as monhab
	  FROM scg_saldos_consolida
	  WHERE (sc_cuenta like '".$this->ls_activo."%'
	  OR sc_cuenta like '".$this->ls_pasivo."%'
	  OR sc_cuenta like '".$this->ls_resultado."%'
	  OR sc_cuenta like '".$this->ls_capital."%'
	  OR sc_cuenta like '".$this->ls_orden_d."%'
	  OR sc_cuenta like '".$this->ls_orden_h."%')
	  AND fecsal<='".$ad_fecfin."'
	  GROUP BY sc_cuenta
	  ORDER BY sc_cuenta";*/

		$ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, coalesce(curSaldo.mondeb,0) as mondeb,
                 coalesce(curSaldo.monhab,0) as monhab,0 as nivel
            FROM scg_cuentas_consolida SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as mondeb,
																  coalesce(sum(haber_mes),0) as monhab
															 FROM scg_saldos_consolida
															WHERE fecsal<='".$ad_fecfin."'
															GROUP BY codemp,sc_cuenta) curSaldo
              ON SC.sc_cuenta=curSaldo.sc_cuenta
           WHERE SC.sc_cuenta like '".$this->ls_activo."%' 
              OR SC.sc_cuenta like '".$this->ls_pasivo."%'
			  OR SC.sc_cuenta like '".$this->ls_resultado."%'
			  OR SC.sc_cuenta like '".$this->ls_capital."%'
			  OR SC.sc_cuenta like '".$this->ls_orden_d."%'
			  OR SC.sc_cuenta like '".$this->ls_orden_h."%'
           ORDER BY trim(SC.sc_cuenta)";
			
		$rs_data=$this->io_sql->select($ls_sql);
	 if ($rs_data===false)
	 {
	 	$this->is_msg_error="Error en consulta metodo uf_scg_reporte_balance_general_consolidado;".$this->io_fun->uf_convertirmsg($this->io_sql->message);
	 	$lb_valido = false;
	 }
	 else
	 {
	 	$ld_saldo_ganancia=0;
	 	while(!$rs_data->EOF)
	 	{
	 		$ls_scgcta = trim($rs_data->fields["sc_cuenta"]);
	 		$ls_dencta = $rs_data->fields["denominacion"];
	 		$ld_mondeb = number_format($rs_data->fields["mondeb"],2,'.','');
	 		$ld_monhab = number_format($rs_data->fields["monhab"],2,'.','');
	 		$li_nivcta = $rs_data->fields["rnivel"];
	 		$ls_nivcta = $li_nivcta;
	 		$this->ds_Prebalance->insertRow("scgcta",$ls_scgcta);
	 		$this->ds_Prebalance->insertRow("dencta",$ls_dencta);
	 		$this->ds_Prebalance->insertRow("mondeb",$ld_mondeb);
	 		$this->ds_Prebalance->insertRow("monhab",$ld_monhab);
	 		$this->ds_Prebalance->insertRow("nivcta",$ls_nivcta);
	 		$this->ds_Prebalance->insertRow("rnivcta",$li_nivcta);
	 		$rs_data->MoveNext();
	 	}

	 	$this->ds_Prebalance->group_by(array('0'=>'scgcta'),array('0'=>'monhab'),'scgcta');
	 	//$this->ds_Prebalance->sortData('scgcta');
	 	$li_totrows = $this->ds_Prebalance->getRowCount("scgcta");
	 	if ($li_totrows==0)
	 	{
	 		$lb_valido = false;
	 		return false;
	 	}
	 	else
	 	{
	 		$this->ds_Prebalance->sortData('scgcta');
	 	}
	 }
	 $ld_saldo_i=0;
	 if ($lb_valido)
	 {
	 	$lb_valido = $this->uf_scg_reporte_select_saldo_ingreso_consolida($ad_fecfin,$this->ls_ingreso,$ld_saldo_i);
	 }
	 if ($lb_valido)
	 {
	 	$ld_saldo_g=0;
	 	$lb_valido=$this->uf_scg_reporte_select_saldo_gasto_consolida($ad_fecfin,$this->ls_gastos,$ld_saldo_g);
	 }
	 if ($lb_valido)
	 {
	 	$ld_saldo_ganancia=$ld_saldo_ganancia+($ld_saldo_i+$ld_saldo_g);
	 }

	 $la_sc_cuenta	  =	array();
	 $la_denominacion = array();
	 $la_saldo		  = array();
	 for ($i=1;$i<=$li_nivcta;$i++)
	 {
	 	$la_sc_cuenta[$i]="";
	 	$la_denominacion[$i]="";
	 	$la_saldo[$i]=0;
	 }
	  
	 $ld_saldo_resultado=0;
	 for ($li_z=1;$li_z<=$li_totrows;$li_z++)
	 {
	 	$ls_scgcta = trim($this->ds_Prebalance->getValue("scgcta",$li_z));
	 	$ld_mondeb = $this->ds_Prebalance->getValue("mondeb",$li_z);
	 	$ld_monhab = $this->ds_Prebalance->getValue("monhab",$li_z);
	 	$ls_dencta = $this->ds_Prebalance->getValue("dencta",$li_z);
	 	$li_nivcta = $this->ds_Prebalance->getValue("nivcta",$li_z);
	 	$ls_nivcta = $this->ds_Prebalance->getValue("rnivcta",$li_z);
	 	$ls_tipcta = substr($ls_scgcta,0,1);
	 	switch($ls_tipcta){
	 		case $this->ls_activo:
	 			$ls_orden=1;
			  break;
	 		case $this->ls_pasivo:
	 			$ls_orden=2;
			  break;
	 		case $this->ls_capital:
	 			$ls_orden=3;
			  break;
	 		case $this->ls_resultado:
	 			$ls_orden=4;
			  break;
	 		case $this->ls_orden_d:
	 			$ls_orden=5;
			  break;
	 		case $this->ls_orden_h:
	 			$ls_orden=6;
			  break;
	 		default:
	 			$ls_orden=7;
	 	}
	 	$ldec_saldo=$ld_mondeb-$ld_monhab;
	 	if (($ls_tipcta==$this->ls_pasivo || $ls_tipcta==$this->ls_resultado || $ls_tipcta==$this->ls_capital)&&($li_nivcta==1))
	 	{
	 		$ld_saldo_resultado = $ld_saldo_resultado+$ldec_saldo;
	 	}
	 	$li_nro_reg=0;
	 	if ($li_nivcta==4)
	 	{
	 		$li_nro_reg++;
				$la_sc_cuenta[$ls_nivcta]    = $ls_scgcta;
				$la_denominacion[$ls_nivcta] = $ls_dencta;
				$la_saldo[$ls_nivcta]        = $ldec_saldo;
				$this->ds_Balance1->insertRow("orden",$ls_orden);
				$this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
				$this->ds_Balance1->insertRow("sc_cuenta",$ls_scgcta);
				$this->ds_Balance1->insertRow("denominacion",$ls_dencta);
				$this->ds_Balance1->insertRow("nivel",$li_nivcta);
				$this->ds_Balance1->insertRow("saldo",$ldec_saldo);
	 	}
	 	else
	 	{
	 		if (empty($la_sc_cuenta[$li_nivcta]))
	 		{
	 			$li_nro_reg++;
	 			$la_sc_cuenta[$ls_nivcta]    = $ls_scgcta;
	 			$la_denominacion[$ls_nivcta] = $ls_dencta;
	 			$la_saldo[$ls_nivcta]        = $ldec_saldo;
	 			$this->ds_Balance1->insertRow("orden",$ls_orden);
	 			$this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
	 			$this->ds_Balance1->insertRow("sc_cuenta",$ls_scgcta);
	 			$this->ds_Balance1->insertRow("denominacion",$ls_dencta);
	 			$this->ds_Balance1->insertRow("nivel",-$li_nivcta);
	 			$this->ds_Balance1->insertRow("saldo",$ldec_saldo);
	 		}
	 		else
	 		{
	 			$li_nro_reg++;
	 			$this->uf_scg_reporte_calcular_total_BG($li_nro_reg,$ls_prev_nivel,$ls_nivcta,$la_sc_cuenta,$la_denominacion,$la_saldo);
	 			$la_sc_cuenta[$ls_nivcta]    = $ls_scgcta;
	 			$la_denominacion[$ls_nivcta] = $ls_dencta;
	 			$la_saldo[$ls_nivcta]	 	  = $ldec_saldo;
	 			$this->ds_Balance1->insertRow("orden",$ls_orden);
	 			$this->ds_Balance1->insertRow("num_reg",$li_nro_reg);
	 			$this->ds_Balance1->insertRow("sc_cuenta",$ls_scgcta);
	 			$this->ds_Balance1->insertRow("denominacion",$ls_dencta);
	 			$this->ds_Balance1->insertRow("nivel",-$li_nivcta);
	 			$this->ds_Balance1->insertRow("saldo",$ldec_saldo);
	 		}
	 	}
	 	$ls_prev_nivel=$li_nivcta;
	 }
	 $this->uf_scg_reporte_calcular_total_BG(&$li_nro_reg,$ls_prev_nivel,1,$la_sc_cuenta,$la_denominacion,$la_saldo);
	 $ld_saldo_resultado=($ld_saldo_resultado+$ld_saldo_ganancia);

	 $this->uf_scg_reporte_actualizar_resultado_BG($this->ls_cta_resultado,$ld_saldo_ganancia,$li_nro_reg,$ls_orden);
	 $this->ds_Balance1->sortData("sc_cuenta");
	  
	 $li_total=$this->ds_Balance1->getRowCount("sc_cuenta");

	 for ($li_i=1;$li_i<=$li_total;$li_i++)
	 {
	 	$ls_sc_cuenta	= $this->ds_Balance1->data["sc_cuenta"][$li_i];
	 	$ls_orden		= $this->ds_Balance1->data["orden"][$li_i];
	 	$li_nro_reg		= $this->ds_Balance1->data["num_reg"][$li_i];
	 	$ls_denominacion = $this->ds_Balance1->data["denominacion"][$li_i];
	 	$ls_nivel		= $this->ds_Balance1->data["nivel"][$li_i];
	 	$ld_saldo		= $this->ds_Balance1->data["saldo"][$li_i];
	 	$li_pos			= $this->ds_Prebalance->find("scgcta",$ls_sc_cuenta);
	 	if ($li_pos>0)
	 	{
	 		$ls_rnivel = $this->ds_Prebalance->data["rnivcta"][$li_pos];
	 	}
	 	else
	 	{
	 		$ls_rnivel=0;
	 	}
	 	$ds_Balance2->insertRow("orden",$ls_orden);
	 	$ds_Balance2->insertRow("num_reg",$li_nro_reg);
	 	$ds_Balance2->insertRow("sc_cuenta",$ls_sc_cuenta);
	 	$ds_Balance2->insertRow("denominacion",$ls_denominacion);
	 	$ds_Balance2->insertRow("nivel",$ls_nivel);
	 	$ds_Balance2->insertRow("saldo",$ld_saldo);
	 	$ds_Balance2->insertRow("rnivel",$ls_rnivel);
	 	$ds_Balance2->insertRow("total",$ld_saldo_resultado);
	 }//for

	 $li_tot=$ds_Balance2->getRowCount("sc_cuenta");
	 for ($li_i=1;$li_i<=$li_tot;$li_i++)
	 {
	 	$ls_sc_cuenta	   = $ds_Balance2->data["sc_cuenta"][$li_i];
	 	$ls_orden		   = $ds_Balance2->data["orden"][$li_i];
	 	$li_nro_reg		   = $ds_Balance2->data["num_reg"][$li_i];
	 	$ls_denominacion    = $ds_Balance2->data["denominacion"][$li_i];
	 	$ls_nivel		   = $ds_Balance2->data["nivel"][$li_i];
	 	$ld_saldo		   = $ds_Balance2->data["saldo"][$li_i];
	 	$ls_rnivel		   = $ds_Balance2->data["rnivel"][$li_i];
	 	$ld_saldo_resultado = $ds_Balance2->data["total"][$li_i];
	 	$this->ds_reporte->insertRow("orden",$ls_orden);
	 	$this->ds_reporte->insertRow("num_reg",$li_nro_reg);
	 	$this->ds_reporte->insertRow("sc_cuenta",$ls_sc_cuenta);
	 	$this->ds_reporte->insertRow("denominacion",$ls_denominacion);
	 	$this->ds_reporte->insertRow("nivel",$ls_nivel);
	 	$this->ds_reporte->insertRow("saldo",$ld_saldo);
	 	$this->ds_reporte->insertRow("rnivel",$ls_rnivel);
	 	$this->ds_reporte->insertRow("total",$ld_saldo_resultado);
	 }//for
	 unset($this->ds_Prebalance,$this->ds_Balance1,$ds_Balance2);
	 return $lb_valido;
	}

	function uf_scg_reporte_select_saldo_ingreso_consolida($adt_fecini,$ai_ingreso,&$ad_saldo)
	{
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_ingreso_consolida
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde
		//              	    $ai_ingreso  // numero de la cuenta de ingraso
	 //                     $ad_saldo  //  total saldo (referencia)
		//	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado
	 //     Creado por :    Ing. Yozelin Barragan.
	 // Fecha Creacion :    02/05/2006          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->la_empresa["codemp"];
	 $lb_valido=true;
	 if ($_SESSION["ls_gestor"]=='INFORMIX')
	 {
	 	$ls_sql=" SELECT case sum(SD.debe_mes-SD.haber_mes) when null then 0 else sum(SD.debe_mes-SD.haber_mes) end saldo ".
                 "   FROM scg_cuentas_consolida SC, scg_saldos_consolida SD ".
                 "  WHERE SC.status='S'
					  AND fecsal<='".$adt_fecini."' 
					  AND SC.sc_cuenta like '".$ai_ingreso."%'				 
				      AND SC.sc_cuenta = SD.sc_cuenta 
				      AND SC.codemp = SD.codemp";
		}
	 else
		{
			$ls_sql="SELECT COALESCE(sum(scg_saldos_consolida.debe_mes-scg_saldos_consolida.haber_mes),0) as saldo
                     FROM scg_cuentas_consolida, scg_saldos_consolida
                    WHERE scg_cuentas_consolida.status='S' 
					  AND scg_saldos_consolida.fecsal<='".$adt_fecini."' 
					  AND scg_cuentas_consolida.sc_cuenta like '".$ai_ingreso."%'
					  AND scg_cuentas_consolida.codemp=scg_saldos_consolida.codemp
					  AND scg_cuentas_consolida.sc_cuenta=scg_saldos_consolida.sc_cuenta";
		}
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($rs_data===false)
	 {
	 	$this->is_msg_error="CLASS->sigesp_scg_class_bal_general.php;M�todo->uf_scg_reporte_select_saldo_ingreso_consolida();".$this->io_fun->uf_convertirmsg($this->io_sql->message);
	 	$lb_valido = false;
	 }
	 else
	 {
	 	if ($row=$this->io_sql->fetch_row($rs_data))
	 	{
	 		$ad_saldo=$row["saldo"];
	 	}
	 	$this->io_sql->free_result($rs_data);
	 }
	 return $lb_valido;
	}//fin uf_scg_reporte_select_saldo_ingreso_consolida

	function  uf_scg_reporte_select_saldo_gasto_consolida($adt_fecini,$ai_gasto,&$ad_saldo)
	{
	 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_scg_reporte_select_saldo_gasto_consolida
	 //         Access :	private
	 //     Argumentos :    $adt_fecini  // fecha  desde
		//              	    $ai_gasto  // numero de la cuenta de gasto
	 //                     $ad_saldo  //  total saldo (referencia)
		//	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado
	 //     Creado por :    Ing. Yozelin Barragan.
	 // Fecha Creacion :    02/05/2006          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ls_codemp = $this->la_empresa["codemp"];
	 $lb_valido=true;
	 if ($_SESSION["ls_gestor"]=='INFORMIX')
	 {
	 	$ls_sql = "SELECT CASE SUM(scg_saldos_consolida.debe_mes-scg_saldos_consolida.haber_mes)
		                    WHEN NULL THEN 0 ELSE SUM(scg_saldos_consolida.debe_mes-scg_saldos_consolida.haber_mes) end saldo
                       FROM scg_cuentas_consolida, scg_saldos_consolida
                      WHERE scg_cuentas_consolida.status='S'
						AND scg_saldos_consolida.fecsal<='".$adt_fecini."'
						AND scg_cuentas_consolida.sc_cuenta like '".$ai_gasto."%'
					    AND scg_cuentas_consolida.codemp = scg_saldos_consolida.codemp
						AND TRIM(scg_cuentas_consolida.sc_cuenta) = TRIM(scg_saldos_consolida.sc_cuenta)";
		}
	 else
	 {
	 	$ls_sql = "SELECT COALESCE(sum(scg_saldos_consolida.debe_mes-scg_saldos_consolida.haber_mes),0) as saldo
                       FROM scg_cuentas_consolida, scg_saldos_consolida
                      WHERE scg_cuentas_consolida.status='S'
					    AND scg_saldos_consolida.fecsal<='".$adt_fecini."' 
					    AND scg_cuentas_consolida.sc_cuenta like '".$ai_gasto."%'
						AND scg_cuentas_consolida.codemp = scg_saldos_consolida.codemp
						AND TRIM(scg_cuentas_consolida.sc_cuenta) = TRIM(scg_saldos_consolida.sc_cuenta)";
	 }
	 $rs_data=$this->io_sql->select($ls_sql);
	 if ($rs_data===false)
	 {
	 	$this->is_msg_error="CLASS->sigesp_scg_class_bal_general.php;M�todo->uf_scg_reporte_select_saldo_gasto_consolida();".$this->io_fun->uf_convertirmsg($this->io_sql->message);
	 	$lb_valido = false;
	 }
	 else
	 {
	 	if ($row=$this->io_sql->fetch_row($rs_data))
	 	{
	 		$ad_saldo=$row["saldo"];
	 	}
	 	$this->io_sql->free_result($rs_data);
	 }
	 return $lb_valido;
	}//fin uf_scg_reporte_select_saldo_gasto_consolida


	function uf_obtener_cuentas_acreedoras($ad_fecfin,$ai_nivel)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_obtener_cuentas_acreedoras
	 //         Access :	private
	 //     Argumentos :    $adt_fecfin  // fecha  hasta
	 //                     $ai_nivel    // Nivel de las Cuentas
		//	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado para las Cuentas Acreedoras
	 //     Creado por :    Ing. Arnaldo Su�rez
	 // Fecha Creacion :    14/05/2010          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $ad_fecfin=$this->io_fun->uf_convertirdatetobd($ad_fecfin);
	 $ls_codemp=$this->la_empresa["codemp"];
	 $lb_valido = true;
	 $ls_sql=  " SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
			   "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
			   "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,trim(SC.cueproacu) as cueproacu ".
			   " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
			   "                                             coalesce(sum(haber_mes),0) as T_Haber ".
			   "                                      FROM   scg_saldos ".
			   "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
			   "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
			   " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
			   " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
			   "       SC.sc_cuenta like '".$this->ls_orden_h."%'".
			   " ORDER BY 1";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data === false)
	 {
	 	$this->is_msg_error="CLASS->sigesp_scg_class_bal_general.php;M�todo->uf_obtener_cuentas_acreedoras();".$this->io_fun->uf_convertirmsg($this->io_sql->message);
	 	$lb_valido = false;
	 }
	 else
	 {
	 	if($rs_data->EOF)
	 	{
	 		$lb_valido = false;
	 	}
	 }

	 if($lb_valido)
	 {
	  while(!$rs_data->EOF)
	  {
		  $ls_sc_cuenta    = trim($rs_data->fields["sc_cuenta"]);
		  $ls_denominacion = trim($rs_data->fields["denominacion"]);
		  $ld_saldo        = $rs_data->fields["total_debe"] - $rs_data->fields["total_haber"];
		  $ls_rnivel       = $rs_data->fields["rnivel"];
		  $ls_status       = $rs_data->fields["status"];
		  if($ls_status=="C")
		  {
		  	$ls_nivel=$ls_rnivel;
		  }//if
		  else
		  {
		  	$ls_nivel=$ls_rnivel;
		  }//else
		  if($ls_nivel<=$ai_nivel)
		  {
			  $this->ds_cuentas_acreedoras->insertRow("sc_cuenta",$ls_sc_cuenta);
			  $this->ds_cuentas_acreedoras->insertRow("denominacion",$ls_denominacion);
			  $this->ds_cuentas_acreedoras->insertRow("nivel",$ls_nivel);
			  $this->ds_cuentas_acreedoras->insertRow("saldo",$ld_saldo);
			  $this->ds_cuentas_acreedoras->insertRow("rnivel",$ls_rnivel);
			  if($ls_rnivel==$ai_nivel)
			  {
			  	if($ls_rnivel == 1)
			  	{
			  		$this->ds_cuentas_acreedoras->insertRow("estatus",'S');
			  	}
			  	else
			  	{
			  		$this->ds_cuentas_acreedoras->insertRow("estatus",'C');
			  	}
			  }
			  else
			  {
			  	$this->ds_cuentas_acreedoras->insertRow("estatus",'S');
			  }

		  }//if
		  $rs_data->MoveNext();
	  }
	  $this->io_sql->free_result($rs_data);
	 }
	 return $lb_valido;
	}

	function uf_obtener_cuentas_acreedoras_formato2($ad_fecfin,$ai_nivel)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 //	      Function :	uf_obtener_cuentas_acreedoras_formato2
	 //         Access :	private
	 //     Argumentos :    $adt_fecfin  // fecha  hasta
	 //                     $ai_nivel    // Nivel de las Cuentas
		//	       Returns :	Retorna true o false si se realizo la consulta para el reporte
	 //	   Description :	Reporte que genera salida  del Estado de Resultado para las Cuentas Acreedoras - Formato 2
	 //     Creado por :    Ing. Arnaldo Su�rez
	 // Fecha Creacion :    17/05/2010          Fecha �ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $this->ls_orden_h=trim($this->la_empresa["orden_h"]);
	 $ls_codemp=$this->la_empresa["codemp"];
	 $ls_ceros = "";
	 $ls_formcont = trim($this->la_empresa["formcont"]);
	 $ls_formcont = trim(str_replace("-","",$ls_formcont));
	 $ls_ceros = str_pad("",strlen($ls_formcont)-1,"0");
	 $ls_cuenta_tot_acreedora = trim(substr($this->ls_orden_h,0,1));
	 if(!empty($ls_cuenta_tot_acreedora))
	 {
	  $ls_cuenta_tot_acreedora .= $ls_ceros;
	 }
	 else
	 {
	  $ls_cuenta_tot_acreedora = "";
	 }
	 $ad_fecfin=$this->io_fun->uf_convertirdatetobd($ad_fecfin);
	 if(!$this->uf_verificar_cuentas_orden_formato2($ls_codemp,$ad_fecfin))
	 {
	  return false;
	 }

	 $lb_valido = true;
	 $ls_sql=  " SELECT DISTINCT '".$ls_cuenta_tot_acreedora."' as sc_cuenta, 'CUENTAS DE ORDEN' as denominacion, 'S' as status, 1 as rnivel,'' as referencia,  ".
				  "        coalesce(SUM(curSaldo.T_Debe),0) as total_debe, ".
				  "        coalesce(SUM(curSaldo.T_Haber),0) as total_haber,0 as nivel, '' as cueproacu, 1 as tiporden ".
				  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
				  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
				  "                                      FROM   scg_saldos ".
				  "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
				  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
				  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
				  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
				  "      SC.sc_cuenta like '".$this->ls_orden_h."%' AND SC.status = 'C'".
				  " UNION ".
				  " SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel,SC.referencia as referencia,  ".
				  "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
				  "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,SC.cueproacu, 1 as tiporden ".
				  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
				  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
				  "                                      FROM   scg_saldos ".
				  "                                      WHERE  codemp='".$ls_codemp."' AND fecsal<='".$ad_fecfin."' ".
				  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
				  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
				  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$ls_codemp."' AND ".
				  "       (SC.sc_cuenta like '".$this->ls_orden_h."%') ";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data === false)
	 {
	 	$this->is_msg_error="CLASS->sigesp_scg_class_bal_general.php;M�todo->uf_obtener_cuentas_acreedoras();".$this->io_fun->uf_convertirmsg($this->io_sql->message);
	 	$lb_valido = false;
	 }
	 else
	 {
	 	if($rs_data->EOF)
	 	{
	 		$lb_valido = false;
	 	}
	 }

	 if($lb_valido)
	 {


	  $arr_cuenta    = array($ai_nivel);
	  $arr_denomina  = array($ai_nivel);
	  $arr_saldos    = array($ai_nivel);
	  $li_row=0;
	  $an_nivel = 0;
	  $nPrevNivel = 0;
	  $nRegNo = 0;
	  while(!$rs_data->EOF)
	  {
		  $ls_sc_cuenta=trim($rs_data->fields["sc_cuenta"]);
		  $ls_denominacion=$rs_data->fields["denominacion"];
		  $ls_status=$rs_data->fields["status"];
		  $ls_rnivel=$rs_data->fields["rnivel"];
		  $ld_total_debe=$rs_data->fields["total_debe"];
		  $ld_total_haber=$rs_data->fields["total_haber"];
		  $ls_cueproacu = $rs_data->fields["cueproacu"];
		  $ls_referencia = $rs_data->fields["referencia"];
		  $ld_saldo        = $rs_data->fields["total_debe"] - $rs_data->fields["total_haber"];
		  $this->uf_cuenta_por_nivel($ls_sc_cuenta,&$ls_cuentasalida,&$lr_nivel);
		  if($ls_status=="C")
		  {
		  	$ls_nivel=$ls_rnivel;
		  }//if
		  else
		  {
		  	$ls_nivel=$ls_rnivel;
		  }//else
		  if($ls_nivel<=$ai_nivel)
		  {
			  $this->ds_ctas_temp->insertRow("sc_cuenta",$ls_sc_cuenta);
			  $this->ds_ctas_temp->insertRow("denominacion",$ls_denominacion);
			  $this->ds_ctas_temp->insertRow("nivel",$ls_nivel);
			  $this->ds_ctas_temp->insertRow("status",$ls_status);
			  $this->ds_ctas_temp->insertRow("saldo",$ld_saldo);
			  $this->ds_ctas_temp->insertRow("rnivel",$ls_rnivel);
			  $this->ds_ctas_temp->insertRow("cuenta_salida",$ls_cuentasalida);
			  $this->ds_ctas_temp->insertRow("referencia",$ls_referencia);
			  $this->ds_ctas_temp->insertRow("cerrado",'');
		  }//if
		   
		  $rs_data->MoveNext();
	  }

	  $li_row=$this->ds_ctas_temp->getRowCount("sc_cuenta");
	  $an_nivel = intval($ai_nivel);
	  $nPrevNivel = intval($this->ds_ctas_temp->data["rnivel"][$li_row]);
	  $nRegNo = 0;
	  for($li_z=1;$li_z<=$li_row;$li_z++)
	  {
	  	$ls_sc_cuenta       = $this->ds_ctas_temp->data["sc_cuenta"][$li_z];
	  	$ls_denominacion    = $this->ds_ctas_temp->data["denominacion"][$li_z];
	  	$ls_nivel           = $this->ds_ctas_temp->data["nivel"][$li_z];
	  	$ls_status          = $this->ds_ctas_temp->data["status"][$li_z];
	  	$ld_saldo           = $this->ds_ctas_temp->data["saldo"][$li_z];
	  	$ls_rnivel          = $this->ds_ctas_temp->data["rnivel"][$li_z];
	  	$ls_referencia      = $this->ds_ctas_temp->data["referencia"][$li_z];
	  	$ls_cuenta_salida   = $this->ds_ctas_temp->data["cuenta_salida"][$li_z];
	  	$ls_cerrado         = $this->ds_ctas_temp->data["cerrado"][$li_z];
	  	$ln_nivel           = intval($ls_rnivel);
	  	if (empty($ld_saldo))
	  	{
	  		$ld_saldo=0;
	  	}
	  	if ($ln_nivel==$an_nivel)
	  	{
					$nRegNo++;
					$this->ds_cuentas_acreedoras->insertRow("sc_cuenta",$ls_sc_cuenta);
					$this->ds_cuentas_acreedoras->insertRow("denominacion",$ls_denominacion);
					$this->ds_cuentas_acreedoras->insertRow("nivel",$ls_nivel);
					$this->ds_cuentas_acreedoras->insertRow("status",$ls_status);
					$this->ds_cuentas_acreedoras->insertRow("saldo",$ld_saldo);
					$this->ds_cuentas_acreedoras->insertRow("rnivel",$ls_rnivel);
					$this->ds_cuentas_acreedoras->insertRow("referencia",$ls_referencia);
					$this->ds_cuentas_acreedoras->insertRow("cuenta_salida",$ls_cuenta_salida);
					$this->ds_cuentas_acreedoras->insertRow("cerrado",'');
	  	}
	  	else
	  	{
					if (empty($arr_cuenta[intval($ls_rnivel)]))
					{
						$arr_cuenta[$ln_nivel]      = $ls_sc_cuenta;
						$arr_denomina[$ln_nivel]    = $ls_denominacion;
						$arr_saldos[$ln_nivel]      = $ld_saldo;
						$nRegNo++;
						$this->ds_cuentas_acreedoras->insertRow("sc_cuenta",$ls_sc_cuenta);
						$this->ds_cuentas_acreedoras->insertRow("denominacion",$ls_denominacion);
						$this->ds_cuentas_acreedoras->insertRow("nivel",$ls_nivel);
						$this->ds_cuentas_acreedoras->insertRow("status",$ls_status);
						$this->ds_cuentas_acreedoras->insertRow("saldo",$ld_saldo);
						$this->ds_cuentas_acreedoras->insertRow("rnivel",$ls_rnivel);
						$this->ds_cuentas_acreedoras->insertRow("referencia",$ls_referencia);
						$this->ds_cuentas_acreedoras->insertRow("cuenta_salida",$ls_cuenta_salida);
						$this->ds_cuentas_acreedoras->insertRow("cerrado",'');
					}
					else
					{
						$a=1;
						$this->uf_downstair_acreedoras_formato2($nRegNo,$nPrevNivel,intval($ls_rnivel),&$arr_cuenta,&$arr_denomina,&$arr_saldos);
						$arr_cuenta[$ln_nivel]      = $ls_sc_cuenta;
						$arr_denomina[$ln_nivel]    = $ls_denominacion;
						$arr_saldos[$ln_nivel]      = $ld_saldo;
						$nRegNo++;
						$this->ds_cuentas_acreedoras->insertRow("sc_cuenta",$ls_sc_cuenta);
						$this->ds_cuentas_acreedoras->insertRow("denominacion",$ls_denominacion);
						$this->ds_cuentas_acreedoras->insertRow("nivel",$ls_nivel);
						$this->ds_cuentas_acreedoras->insertRow("status",$ls_status);
						$this->ds_cuentas_acreedoras->insertRow("saldo",$ld_saldo);
						$this->ds_cuentas_acreedoras->insertRow("rnivel",$ls_rnivel);
						$this->ds_cuentas_acreedoras->insertRow("referencia",$ls_referencia);
						$this->ds_cuentas_acreedoras->insertRow("cuenta_salida",$ls_cuenta_salida);
						$this->ds_cuentas_acreedoras->insertRow("cerrado",'');
					}
	  	}

	  	$nPrevNivel = intval($ls_rnivel);
	  }//for

	  $this->io_sql->free_result($rs_data);
	 }
	 $this->uf_downstair_acreedoras_formato2($nRegNo,$nPrevNivel,1,&$arr_cuenta,&$arr_denomina,&$arr_saldos);
	 unset($this->ds_ctas_temp);
	 return $lb_valido;
	}

	/****************************************************************************************************************************************/
	function uf_downstair_acreedoras_formato2($li_npos,$nHighStair,$nLowerStair,$arr_cuenta,$arr_denomina,&$arr_saldos)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	    Function :	uf_downstair_acreedoras_formato2
		//         Access :	private
		//     Argumentos :  li_npos         // Posicion de la Cuenta a ajustar
		//                   nHighStair      // Posicion m�s alta de la escalera
		//                   nLowerStair     // Posici�n m�s baja de la escalera
		//                   arr_cuenta      // Arreglo con las Cuentas a ajustar
		//                   arr_denomina    // Arreglo con las Denominacion de las Cuentas a ajustar
		//                   arr_saldos      // Arreglo con los Saldos de las Cuentas a ajustar
		//	     Returns :	sin retorno
		//	 Description :	Funci�n que realiza el ajuste del datastore para la presentaci�n de las Cuentas de Orden Acreedoras en el
		//                   Balance General Formato 2
		//     Creado por :  Ing. Arnaldo Su�rez
		// Fecha Creacion :  14/05/2010          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$nRegNo = 0;
		for($li_z=($nHighStair-1);$li_z>=$nLowerStair;$li_z--)
		{
			if (!empty($arr_cuenta[$li_z]))
			{
				$nRegNo++;
				//inserta en el datastore final
				$this->ds_cuentas_acreedoras->insertRow("sc_cuenta",$arr_cuenta[$li_z]);
				$this->ds_cuentas_acreedoras->insertRow("denominacion",'TOTAL '.$arr_denomina[$li_z]);
				$this->ds_cuentas_acreedoras->insertRow("saldo",$arr_saldos[$li_z]);

				$ls_sc_cuentat   = $arr_cuenta[$li_z];
				$li_pos         = $this->ds_ctas_temp->find("sc_cuenta",$ls_sc_cuentat);
				if ($li_pos>0)
				{
					$ls_rnivel=$this->ds_ctas_temp->data["rnivel"][$li_pos];
					$ls_status=$this->ds_ctas_temp->data["status"][$li_pos];
					$ls_referencia=$this->ds_ctas_temp->data["referencia"][$li_pos];
					$ls_cuenta_salida=$this->ds_ctas_temp->data["cuenta_salida"][$li_pos];
					$ls_nivel=$this->ds_ctas_temp->data["nivel"][$li_pos];
				}
				else
				{
					$ls_rnivel=0;
					$ls_referencia="";
					$ls_cuenta_salida="";
					$ls_status = "";
					$ls_nivel = 0;
				}
				$this->ds_cuentas_acreedoras->insertRow("status",$ls_status);
				$this->ds_cuentas_acreedoras->insertRow("rnivel",$ls_rnivel);
				$this->ds_cuentas_acreedoras->insertRow("referencia",$ls_referencia);
				$this->ds_cuentas_acreedoras->insertRow("cuenta_salida",$ls_cuenta_salida);
				$this->ds_cuentas_acreedoras->insertRow("nivel",$ls_nivel);
				$this->ds_cuentas_acreedoras->insertRow("cerrado",'S');

				$arr_cuenta[$li_z]      = null;
				$arr_denomina[$li_z]    = null;
				$arr_saldos[$li_z]      = null;
			}//if
		}//for

	}

	function uf_actualizar_saldo_activos($as_cuenta,$ad_monto,$aa_datastore,$as_campocuenta,$as_camposaldo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	    Function :	uf_actualizar_saldo_activos
		//         Access :	private
		//     Argumentos :  as_cuenta         // Codigo de la Cuenta
		//                   ad_monto          // Monto de la Cuenta
		//                   aa_datastore      // DataStore de las Cuentas a ajustar Saldo
		//                   as_campocuenta    // Nombre del Campo que tiene la Cuenta del DataStore
		//                   as_camposaldo     // Nombre del Campo que tiene el Saldo de la Cuenta en el DataStore
		//	     Returns :	sin retorno
		//	 Description :	Funci�n que realiza el ajuste del datastore en las Cuentas de Activo, cuando estan asociadas
		//                   las cuentas de depreciaci�n o amortizaci�n acumulada, de forma que el saldo sea net
		//     Creado por :  Ing. Arnaldo Su�rez
		// Fecha Creacion :  07/06/2010          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_nextCuenta=$this->int_scg->uf_scg_next_cuenta_nivel($as_cuenta);
		$li_nivel=$this->int_scg->uf_scg_obtener_nivel( $ls_nextCuenta );
		do
		{
			$li_pos = $aa_datastore->find($as_campocuenta,$ls_nextCuenta);
			$ld_saldo = 0;
			if($li_pos>0)
			{
			 $ld_saldo = $aa_datastore->data[$as_camposaldo][$li_pos];
			 $ld_saldo += $ad_monto;
			 $aa_datastore->updateRow($as_camposaldo,$ld_saldo,$li_pos);
			}
			$lb_valido = true;
			$ls_nextCuenta=$this->int_scg->uf_scg_next_cuenta_nivel($ls_nextCuenta);
			if($ls_nextCuenta!="")
			{
				$li_nivel=($this->int_scg->uf_scg_obtener_nivel($ls_nextCuenta));
			}
		}while(($li_nivel>=1)&&($lb_valido)&&($ls_nextCuenta!=""));

	}

	function uf_actualizar_saldo_pasivos($as_cuenta,$ad_monto,$aa_datastore,$as_campocuenta,$as_camposaldo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	    Function :	uf_actualizar_saldo_pasivos
		//         Access :	private
		//     Argumentos :  as_cuenta         // Codigo de la Cuenta
		//                   ad_monto          // Monto de la Cuenta
		//                   aa_datastore      // DataStore de las Cuentas a ajustar Saldo
		//                   as_campocuenta    // Nombre del Campo que tiene la Cuenta del DataStore
		//                   as_camposaldo     // Nombre del Campo que tiene el Saldo de la Cuenta en el DataStore
		//	     Returns :	sin retorno
		//	 Description :	Funci�n que realiza el ajuste del datastore en las Cuentas de Activo, cuando estan asociadas
		//                   las cuentas de depreciaci�n o amortizaci�n acumulada, de forma que el saldo sea net
		//     Creado por :  Ing. Arnaldo Su�rez
		// Fecha Creacion :  07/06/2010          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		$ls_nextCuenta=$this->int_scg->uf_scg_next_cuenta_nivel($as_cuenta);
		$li_nivel=$this->int_scg->uf_scg_obtener_nivel( $ls_nextCuenta );
		do
		{
			$li_pos = $aa_datastore->find($as_campocuenta,$ls_nextCuenta);
			$ld_saldo = 0;
			if($li_pos>0)
			{
				$ld_saldo = $aa_datastore->data[$as_camposaldo][$li_pos];
				$ld_saldo += abs($ad_monto);
				$aa_datastore->updateRow($as_camposaldo,$ld_saldo,$li_pos);
			}
			$lb_valido = true;
			$ls_nextCuenta=$this->int_scg->uf_scg_next_cuenta_nivel($ls_nextCuenta);
			if($ls_nextCuenta!="")
			{
				$li_nivel=($this->int_scg->uf_scg_obtener_nivel($ls_nextCuenta));
			}
		}while(($li_nivel>=1)&&($lb_valido)&&($ls_nextCuenta!=""));
	}

	function uf_obtener_total_cuedepamo(&$aa_cuedepamo,&$aa_hijcuedepamo,$ad_fecfin)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	    Function :	uf_obtener_total_cuedepamo
		//         Access :	private
		//     Argumentos :  aa_cuedepamo    // Arreglo que contiene el total de las cuentas de las cuentas de Depreciaci�n y Amortizaci�n Acumulada
		//                   aa_hijcuedepamo // Arreglo que contiene por cada cuenta del arreglo aa_cuedepamo aquellas cuentas que lo tienen como referencia
		//                                      Ejemplo: aa_cuedepamo[0] = '2250000000000' y aa_hijcuedepamo[0] = '2250100000000,2250200000000'
		//                   ad_fecfin        // Fecha de Emisi�n del Reporte
		//	     Returns :	aa_cuedepamo y aa_hijcuedepamo
		//	 Description :	Funci�n que crea los arreglos que contienen: 
		//                     1) El Total de Cuentas de Depreciaci�n y Amortizaci�n Acumulada configuradas en el Plan de Cuentas (aa_cuedepamo) y
		//                     2) Las Cuentas Hijas por cada cuenta configurada de aa_cuedepamo.
		//                   Estos arreglos se usan para poder hacer el filtro de las cuentas a omitir en el Balance General y evitar la duplicidad
		//                   de los Montos
		//     Creado por :  Ing. Arnaldo Su�rez
		// Fecha Creacion :  07/06/2010          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $lb_valido = true;
	 $ls_sql=  "SELECT sc_cuenta ".
			   "  FROM scg_cuentas ".
			   "WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."' ".
			   " AND sc_cuenta LIKE '".$this->ls_cuedepamo."%' ".
			   " AND sc_cuenta IN (SELECT DISTINCT sc_cuenta FROM scg_saldos WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."' AND fecsal<='".$ad_fecfin."')".
			   "UNION ".
			   "SELECT DISTINCT referencia ".
			   "   FROM scg_cuentas ".
			   "WHERE codemp = '0001' AND sc_cuenta LIKE '".$this->ls_cuedepamo."%' ".
			   "  AND referencia IN (SELECT DISTINCT sc_cuenta FROM scg_saldos WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."' AND fecsal<='".$ad_fecfin."') ".
			   "ORDER BY 1";
	 $rs_data=$this->io_sql->select($ls_sql);
	 if($rs_data === false)
	 {
	  $this->is_msg_error="CLASS->sigesp_scg_class_bal_general.php;M�todo->uf_obtener_total_cuedepamo();".$this->io_fun->uf_convertirmsg($this->io_sql->message);
	  $lb_valido = false;
	 }
	 else
	 {
	  if($rs_data->EOF)
	  {
	   $lb_valido = false;
	  }
	 }

	 if($lb_valido)
	 {
	  while(!$rs_data->EOF)
	  {
	   array_push($aa_cuedepamo,$rs_data->fields["sc_cuenta"]);
	   $ls_cuenta = $rs_data->fields["sc_cuenta"];
	   $ls_sql_hij=  " SELECT sc_cuenta ".
					 "  FROM scg_cuentas ".
					 " WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."' ".
					 " AND referencia = '".$ls_cuenta."' ".
					 " AND sc_cuenta IN (SELECT DISTINCT sc_cuenta FROM scg_saldos WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."' AND fecsal<='".$ad_fecfin."')".
					 "ORDER BY sc_cuenta";
	   $rs_datahij=$this->io_sql->select($ls_sql_hij);
	   if($rs_datahij === false)
	   {
	   	$this->is_msg_error="CLASS->sigesp_scg_class_bal_general.php;M�todo->uf_obtener_total_cuedepamo();".$this->io_fun->uf_convertirmsg($this->io_sql->message);
	   	$lb_valido = false;
	   }
	   else
	   {
	   	$i=0;
	   	$ls_hijos="";
	   	while(!$rs_datahij->EOF)
	   	{
	   		if($i==0)
	   		{
	   			$ls_hijos .=$rs_datahij->fields["sc_cuenta"];
	   		}
	   		else
	   		{
	   			$ls_hijos .=",".$rs_datahij->fields["sc_cuenta"];
	   		}
	   		$i++;
	   		$rs_datahij->MoveNext();
	   	}
	   	array_push($aa_hijcuedepamo,$ls_hijos);
	   }
	   $rs_data->MoveNext();
	  }
	  $this->io_sql->free_result($rs_datahij);
	  $this->io_sql->free_result($rs_data);
	 }
	}

	function uf_obtener_familia_cuedepamo(&$aa_famcuedepamo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	    Function :	uf_obtener_familia_cuedepamo
		//         Access :	private
		//     Argumentos :  aa_famcuedepamo    // Arreglo que retorna la escalera completa, por cuenta configurada de la
		//                                         Depreciaci�n y Amortizaci�n Acumulada
		//	     Returns :	aa_famcuedepamo
		//	 Description :	Funci�n que crea el arreglo aa_famcuedepamo, que es necesario para poder hacer el filtro de las cuentas a omitir en el Balance General y evitar la duplicidad 
		//                   de los Montos
		//     Creado por :  Ing. Arnaldo Su�rez
		// Fecha Creacion :  07/06/2010          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_sql=  "SELECT DISTINCT cueproacu ".
           "  FROM scg_cuentas ".
		   "WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."' ".
		   " AND cueproacu <> ''".
		   " AND cueproacu IN (SELECT DISTINCT sc_cuenta FROM scg_saldos WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."')".
		   "ORDER BY cueproacu";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data === false)
		{
			$this->is_msg_error="CLASS->sigesp_scg_class_bal_general.php;M�todo->uf_obtener_familia_cuedepamo();".$this->io_fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido = false;
		}
		else
		{
			if($rs_data->EOF)
			{
				$lb_valido = false;
			}
		}
		if($lb_valido)
		{
			while(!$rs_data->EOF)
			{
				$ls_cuenta =$rs_data->fields["cueproacu"];
				array_push($aa_famcuedepamo,$ls_cuenta);
				$ls_nextCuenta=$this->int_scg->uf_scg_next_cuenta_nivel($ls_cuenta);
				$li_nivel=$this->int_scg->uf_scg_obtener_nivel( $ls_nextCuenta );
				do
				{
					array_push($aa_famcuedepamo,$ls_nextCuenta);
					$lb_valido = true;
					$ls_nextCuenta=$this->int_scg->uf_scg_next_cuenta_nivel($ls_nextCuenta);
					if($ls_nextCuenta!="")
					{
						$li_nivel=($this->int_scg->uf_scg_obtener_nivel($ls_nextCuenta));
					}
				}while(($li_nivel>=1)&&($lb_valido)&&($ls_nextCuenta!=""));
				$rs_data->MoveNext();
			}
		}
		$aa_famcuedepamo = array_reverse($aa_famcuedepamo);
		$this->io_sql->free_result($rs_data);
	}

	function uf_obtener_cuentas_pasivo_omitir($aa_cuedepamo,$aa_hijcuedepamo,$aa_famcuedepamo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	    Function :	uf_obtener_cuentas_pasivo_omitir
		//         Access :	private
		//     Argumentos :  aa_cuedepamo     // Arreglo con las Cuentas del Plan de Cuentas de Depreciaci�n y Amortizaci�n Acumulada (Grupo 225
		//                   aa_hijcuedepamo  // Arreglo con las Cuentas Hijas por cada cuenta en el arreglo aa_cuedepamo
		//                   aa_famcuedepamo  // Arreglo con la Escalera por Cuenta de las Configuradas de Depreciaci�n y Amortizaci�n Acumulada
		//	     Returns :	aa_cuedepamo, aa_hijcuedepamo y aa_famcuedepamo
		//	 Description :	Funci�n que genera una cadenas con las cuentas a omitir para el Balance General del Grupo 225 (Cuentas de Amortizaci�n y Depreciaci�n)
		//                   de la secci�n de Pasivos, y no se presente duplicidad de montos  
		//     Creado por :  Ing. Arnaldo Su�rez
		// Fecha Creacion :  07/06/2010          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	 $la_cuentas_omitir = array();
	 $ls_cuentas_omitir = "";
	 $li_total = count($aa_cuedepamo);
	 for($i=0;$i<$li_total;$i++)
	 {
	 	$ls_cuenta = $aa_cuedepamo[$i];
	 	$lb_imprimir = false;
	 	if(!is_bool(array_search($ls_cuenta,$aa_famcuedepamo)))
	 	{
				$ls_cadena = $aa_hijcuedepamo[$i];
				if(!empty($ls_cadena))
				{
				 $la_hijos = explode(',',$ls_cadena);
				 $li_tot = count($la_hijos);
				 for($j=0;$j<$li_tot;$j++)
				 {
				  $ls_hijo = $la_hijos[$j];
				  if(array_search($ls_hijo,$aa_famcuedepamo) === false)
				  {
				   $lb_imprimir = true;
				   break;
				  }
				 }
				 if(!$lb_imprimir)
				 {
				  array_push($la_cuentas_omitir,$ls_cuenta);
				 }
				}
	 	}
	 }
	 $li_totcta = count($la_cuentas_omitir);
	 for($h=0;$h<$li_totcta;$h++)
	 {
	  if($h==0)
	  {
	   $ls_cuentas_omitir .= "'".$la_cuentas_omitir[$h]."'";
	  }
	  else
	  {
	   $ls_cuentas_omitir .= ",'".$la_cuentas_omitir[$h]."'";
	  }
	 }
	 return $ls_cuentas_omitir;
	}

	function uf_verificar_cuentas_orden_formato2($as_codemp,$ad_fecfin)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function :  uf_verificar_cuentas_orden_formato2
		//         Access :  private
		//     Argumentos :
		//	   Description :  Funci�n que valida que haya cuentas de orden para mostrar en el Balance General Formato 2
		//     Creado por :  Ing. Arnaldo Su�rez
		// Fecha Creacion :  08/06/2010          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_sql=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel,SC.referencia as referencia,  ".
				  "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
				  "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel,SC.cueproacu, 1 as tiporden ".
				  " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
				  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
				  "                                      FROM   scg_saldos ".
				  "                                      WHERE  codemp='".$as_codemp."' AND fecsal<='".$ad_fecfin."' ".
				  "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
				  " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
				  " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$as_codemp."' AND ".
				  "       (SC.sc_cuenta like '".$this->ls_orden_h."%') ";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data === false)
		{
			$this->is_msg_error="CLASS->sigesp_scg_class_bal_general.php;M�todo->uf_verificar_cuentas_orden_formato2();".$this->io_fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido = false;
		}
		else
		{
			if($rs_data->EOF)
			{
				$lb_valido = false;
			}
		}
		return $lb_valido;
	}

	function uf_obtener_status_referencia_nivel($as_sc_cuenta,&$as_status,&$as_referencia,&$ai_nivel)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	      Function :  uf_obtener_status_referencia_nivel
		//         Access :  private
		//     Argumentos :  as_sc_cuenta // Cuenta Contable
		//                   as_status    // Estatus de la Cuenta
		//                   as_referencia // Referencia de la Cuenta
		//	   Description :  Funci�n que retorna es estatus de una cuenta contable
		//     Creado por :  Ing. Arnaldo Su�rez
		// Fecha Creacion :  01/07/2010          Fecha ltima Modificacion :      Hora :
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lb_valido = true;
		$ls_sql=" SELECT status, nivel, referencia FROM scg_cuentas WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."' AND sc_cuenta = '".$as_sc_cuenta."'";
		$rs_data=$this->io_sql->select($ls_sql);
		if($rs_data === false)
		{
			$this->is_msg_error="CLASS->sigesp_scg_class_bal_general.php;M�todo->uf_obtener_status();".$this->io_fun->uf_convertirmsg($this->io_sql->message);
			$lb_valido = false;
		}
		else
		{
			if(!$rs_data->EOF)
			{
				$as_status     =  $rs_data->fields["status"];
				$as_referencia =  $rs_data->fields["referencia"];
				$ai_nivel      =  $rs_data->fields["nivel"];
			}
		}
		return $lb_valido;
	}

}
?>
