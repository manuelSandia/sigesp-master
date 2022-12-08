<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}
//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("sigesp_spi_reporte.php");
		$io_report = new sigesp_spi_reporte();
		require_once("sigesp_spi_funciones_reportes.php");
		$io_function_report = new sigesp_spi_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();
		require_once("../class_funciones_ingreso.php");
		$io_fun_ingreso=new class_funciones_ingreso();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("../../shared/graficos/pChart/pData.class");
		require_once("../../shared/graficos/pChart/pChart.class");
//--------------------------------------------------  Par�metros para Filtar el Reporte  ---------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$ls_cmbmesdes = $_GET["cmbmesdes"];
		$ldt_fecini=$li_ano."-01-01";
		$ldt_fecini_rep="01/01/".$li_ano;
		$ls_cmbmeshas = $_GET["cmbmeshas"];
		$ls_mes=$ls_cmbmeshas;
		$ls_ano=$li_ano;
		$fecfin=$io_fecha->uf_last_day($ls_mes,$ls_ano);
		$ldt_fecfin=$io_funciones->uf_convertirdatetobd($fecfin);
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ls_estpreing=$_SESSION["la_empresa"]["estpreing"];
		
		if($ls_estpreing==1)
		{
			$ls_codestpro1_min  = $_GET["codestpro1"];
			$ls_codestpro2_min  = $_GET["codestpro2"];
			$ls_codestpro3_min  = $_GET["codestpro3"];
			$ls_codestpro1h_max = $_GET["codestpro1h"];
			$ls_codestpro2h_max = $_GET["codestpro2h"];
			$ls_codestpro3h_max = $_GET["codestpro3h"];
			$ls_estclades       = $_GET["estclades"];
			$ls_estclahas       = $_GET["estclahas"];
			$ls_loncodestpro1   = $_SESSION["la_empresa"]["loncodestpro1"];
			$ls_loncodestpro2   = $_SESSION["la_empresa"]["loncodestpro2"];
			$ls_loncodestpro3   = $_SESSION["la_empresa"]["loncodestpro3"];
			$ls_loncodestpro4   = $_SESSION["la_empresa"]["loncodestpro4"];
			$ls_loncodestpro5   = $_SESSION["la_empresa"]["loncodestpro5"];
	
			if($ls_modalidad==1)
			{
				$ls_codestpro4_min =  "0000000000000000000000000";
				$ls_codestpro5_min =  "0000000000000000000000000";
				$ls_codestpro4h_max = "0000000000000000000000000";
				$ls_codestpro5h_max = "0000000000000000000000000";
				if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
				{
				  if($io_function_report->uf_spi_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
																				 $ls_codestpro3_min,$ls_codestpro4_min,
																				 $ls_codestpro5_min,$ls_estclades))
				  {
						$ls_codestpro1  = $ls_codestpro1_min;
						$ls_codestpro2  = $ls_codestpro2_min;
						$ls_codestpro3  = $ls_codestpro3_min;
						$ls_codestpro4  = $ls_codestpro4_min;
						$ls_codestpro5  = $ls_codestpro5_min;
				  }
				}
				else
				{
						$ls_codestpro1  = $ls_codestpro1_min;
						$ls_codestpro2  = $ls_codestpro2_min;
						$ls_codestpro3  = $ls_codestpro3_min;
						$ls_codestpro4  = $ls_codestpro4_min;
						$ls_codestpro5  = $ls_codestpro5_min;
				}
				if(($ls_codestpro1h_max=="")&&($ls_codestpro2h_max=="")&&($ls_codestpro3h_max==""))
				{
				  if($io_function_report->uf_spi_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																				 $ls_codestpro3h_max,$ls_codestpro4h_max,
																				 $ls_codestpro5h_max,$ls_estclahas))
				  {
						$ls_codestpro1h  = $ls_codestpro1h_max;
						$ls_codestpro2h  = $ls_codestpro2h_max;
						$ls_codestpro3h  = $ls_codestpro3h_max;
						$ls_codestpro4h  = $ls_codestpro4h_max;
						$ls_codestpro5h  = $ls_codestpro5h_max;
				  }
				}
				else
				{
						$ls_codestpro1h  = $ls_codestpro1h_max;
						$ls_codestpro2h  = $ls_codestpro2h_max;
						$ls_codestpro3h  = $ls_codestpro3h_max;
						$ls_codestpro4h  = $ls_codestpro4h_max;
						$ls_codestpro5h  = $ls_codestpro5h_max;
				}
			}
			elseif($ls_modalidad==2)
			{
				$ls_codestpro4_min  = $_GET["codestpro4"];
				$ls_codestpro5_min  = $_GET["codestpro5"];
				$ls_codestpro4h_max = $_GET["codestpro4h"];
				$ls_codestpro5h_max = $_GET["codestpro5h"];
				
				if(($ls_codestpro1_min=='**') ||($ls_codestpro1_min==''))
				{
					$ls_codestpro1_min='';
				}
				else
				{
					$ls_codestpro1_min  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
				}
				if(($ls_codestpro2_min=='**') ||($ls_codestpro2_min==''))
				{
					$ls_codestpro2_min='';
				}
				else
				{
					$ls_codestpro2_min  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
				
				}
				if(($ls_codestpro3_min=='**')||($ls_codestpro3_min==''))
				{
					$ls_codestpro3_min='';
				}
				else
				{
				
					$ls_codestpro3_min  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
				}
				if(($ls_codestpro4_min=='**') ||($ls_codestpro4_min==''))
				{
					$ls_codestpro4_min='';
				}
				else
				{
					$ls_codestpro4_min  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
		
				
				}
				if(($ls_codestpro5_min=='**') ||($ls_codestpro5_min==''))
				{
					$ls_codestpro5_min='';
				}
				else
				{
					$ls_codestpro5_min  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);
				}
				
				
				if(($ls_codestpro1h_max=='**')||($ls_codestpro1h_max==''))
				{
					$ls_codestpro1h_max='';
				}
				else
				{
					$ls_codestpro1h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
				}
				if(($ls_codestpro2h_max=='**') ||($ls_codestpro2h_max==''))
				{
					$ls_codestpro2h_max='';
				}else
				{
					$ls_codestpro2h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
				}
				if(($ls_codestpro3h_max=='**') ||($ls_codestpro3h_max==''))
				{
					$ls_codestpro3h_max='';
				}else
				{
					$ls_codestpro3h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
				}
				if(($ls_codestpro4h_max=='**')  ||($ls_codestpro4h_max==''))
				{
					$ls_codestpro4h_max='';
				}else
				{
					$ls_codestpro4h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
				}
				if(($ls_codestpro5h_max=='**')  || ($ls_codestpro5h_max==''))
				{
					$ls_codestpro5h_max='';
				}else
				{
					$ls_codestpro5h_max  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);
				}
				
				if(($ls_codestpro1_min=="")||($ls_codestpro2_min=="")||($ls_codestpro3_min=="")||($ls_codestpro4_min=="")||($ls_codestpro5_min==""))
				{
				  if($io_function_report->uf_spi_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
																				 $ls_codestpro3_min,$ls_codestpro4_min,
																				 $ls_codestpro5_min,$ls_estclades))
				  {
						$ls_codestpro1  = $ls_codestpro1_min;
						$ls_codestpro2  = $ls_codestpro2_min;
						$ls_codestpro3  = $ls_codestpro3_min;
						$ls_codestpro4  = $ls_codestpro4_min;
						$ls_codestpro5  = $ls_codestpro5_min;
				  }
				}
				else
				{
						$ls_codestpro1  = $ls_codestpro1_min;
						$ls_codestpro2  = $ls_codestpro2_min;
						$ls_codestpro3  = $ls_codestpro3_min;
						$ls_codestpro4  = $ls_codestpro4_min;
						$ls_codestpro5  = $ls_codestpro5_min;
				}
				if(($ls_codestpro1h_max=="")||($ls_codestpro2h_max=="")||($ls_codestpro3h_max=="")||($ls_codestpro4h_max=="")||($ls_codestpro5h_max==""))
				{
				  if($io_function_report->uf_spi_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
																				 $ls_codestpro3h_max,$ls_codestpro4h_max,
																				 $ls_codestpro5h_max,$ls_estclahas))
				  {
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
				  }
				}
				else
				{
					$ls_codestpro1h  = $ls_codestpro1h_max;
					$ls_codestpro2h  = $ls_codestpro2h_max;
					$ls_codestpro3h  = $ls_codestpro3h_max;
					$ls_codestpro4h  = $ls_codestpro4h_max;
					$ls_codestpro5h  = $ls_codestpro5h_max;
				}
			}
			
			$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
			$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
			if($ls_modalidad==1)
			{
				if (($ls_codestpro1<>"")&&($ls_codestpro2=="")&&($ls_codestpro3==""))
				{
				 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1);
				 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1);
				}
				elseif(($ls_codestpro1<>"")&&($ls_codestpro2<>"")&&($ls_codestpro3==""))
				{
				 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2);
				 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2);
				}
				elseif(($ls_codestpro1<>"")&&($ls_codestpro2<>"")&&($ls_codestpro3<>""))
				{
				 $ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3);
				 $ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2)."-".substr($ls_codestpro3h,-$ls_loncodestpro3);
				}
				else
				{
				 $ls_programatica_desde1="";
				 $ls_programatica_hasta1="";
				}
			}
			else
			{
				$ls_programatica_desde1=substr($ls_codestpro1,-$ls_loncodestpro1)."-".substr($ls_codestpro2,-$ls_loncodestpro2)."-".substr($ls_codestpro3,-$ls_loncodestpro3)."-".substr($ls_codestpro4,-$ls_loncodestpro4)."-".substr($ls_codestpro5,-$ls_loncodestpro5)."-".$ls_estclades;
				$ls_programatica_hasta1=substr($ls_codestpro1h,-$ls_loncodestpro1)."-".substr($ls_codestpro2h,-$ls_loncodestpro2)."-".substr($ls_codestpro3h,-$ls_loncodestpro3)."-".substr($ls_codestpro4h,-$ls_loncodestpro4)."-".substr($ls_codestpro5h,-$ls_loncodestpro5)."-".$ls_estclahas;
			}
		}
		$cmbnivel=$_GET["cmbnivel"];
		if($cmbnivel=="s1")
		{
			$ls_cmbnivel="1";
		}
		else
		{
			$ls_cmbnivel=$cmbnivel;
		}
		$ls_subniv=$_GET["checksubniv"];
		if($ls_subniv==1)
		{
			$lb_subniv=true;
		}
		else
		{
			$lb_subniv=false;
		}
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		
		
		
		$ls_desc_event="Solicitud de Reporte Acumulado por Cuentas desde la fecha ".$ldt_fecini_rep." hasta ".$fecfin;
		$io_fun_ingreso->uf_load_seguridad_reporte("SPI","sigesp_spi_r_acum_x_cuentas.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
	//----------------------------------------------------  Par�metros del encabezado  --------------------------------------------
		$ls_estpreing=$_SESSION["la_empresa"]["estpreing"];
		$ls_titulo="ACUMULADO POR CUENTAS  DESDE LA FECHA ".$ldt_fecini_rep."  HASTA  ".$fecfin;
		$ls_tiporeporte=$_GET["tiporeporte"];
		global $ls_tiporeporte;
		require_once("../../shared/ezpdf/class.ezpdf.php");
		
		if($ls_tiporeporte==1)
		{
			require_once("sigesp_spi_reportebsf.php");
			$io_report=new sigesp_spi_reportebsf();
		}              
	//--------------------------------------------------------------------------------------------------------------------------------
	// Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	$ls_estpreing=$_SESSION["la_empresa"]["estpreing"];
	if($ls_estpreing==1)
	{
		$ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
		$ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
		$ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
		$ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
		$ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);
			
		$ls_codestpro1h  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
		$ls_codestpro2h  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
		$ls_codestpro3h  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
		$ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
		$ls_codestpro5h  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);
		
		$li_loncodestpro1 = $_SESSION["la_empresa"]["loncodestpro1"];
		$li_loncodestpro2 = $_SESSION["la_empresa"]["loncodestpro2"];
		$li_loncodestpro3 = $_SESSION["la_empresa"]["loncodestpro3"];
		$li_loncodestpro4 = $_SESSION["la_empresa"]["loncodestpro4"];
		$li_loncodestpro5 = $_SESSION["la_empresa"]["loncodestpro5"];
	}
	error_reporting(E_ALL);
	$ld_total_previsto=0;
	$ld_total_aumento=0;
	$ld_total_disminucion=0;
	$ld_total_devengado=0;
	$ld_total_cobrado=0;
	$ld_total_cobrado_anticipado=0;
	$ld_total_monto_actualizado=0;
	$ld_total_por_cobrar=0;
	$cuentamin = $_GET["cuentadesde"];
	$cuentamax = $_GET["cuentahasta"];
	if(empty($cuentamin))
	{
	 $io_function_report->uf_spi_reporte_select_max_cuenta($cuentamax);
	}
	if(empty($cuentamax))
	{
	 $io_function_report->uf_spi_reporte_select_min_cuenta($cuentamin);
	}
	$vacio = "";
	
	
	if ($ls_estpreing==1)
	{
		$io_report->uf_spi_reporte_acum_cuentas2($cuentamin,$cuentamax,
							$ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
							$ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
							$ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas,$ls_cmbnivel);


		/*
		$lb_valido=$io_report->select_estructuras_spi($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
											        $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
											        $ls_codestpro4h,$ls_codestpro5h,$ls_estclades,$ls_estclahas);*/
		
		//var_dump($io_report->dts_reporte);
		$li_totfila=$io_report->dts_reporte->getRowCount("spi_cuenta");
		
		$ld_total_previsto=0;
		$ld_total_aumento=0;
		$ld_total_disminucion=0;
		$ld_total_devengado=0;
		$ld_total_cobrado=0;
		$ld_total_cobrado_anticipado=0;
		$ld_total_monto_actualizado=0;
		$ld_total_por_cobrar=0;
		$la_data1 = NULL;
		$la_data_tot =  NULL;
		for($j=1;($j<=$li_totfila);$j++)
		{
		      
			$as_spg_cuenta=trim($io_report->dts_reporte->data["spi_cuenta"][$j]);
			$lb_valido=$io_report->uf_spi_reporte_detalle_acumulado_cuentas($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
							$ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,$ls_codestpro4h,
							$ls_codestpro5h,$ls_estclades,$ls_estclahas,
							$as_spg_cuenta,$ldt_fecfin,$j);												

			$li_tot=0;
			$li_tot=$io_report->dts_reporte->getRowCount("spi_cuenta");
				$ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$j];
				$ls_denominacion=$io_report->dts_reporte->data["denominacion"][$j];
				$ls_nivel=$io_report->dts_reporte->data["nivel"][$j];
				$ls_status=$io_report->dts_reporte->data["status"][$j];
				$ld_previsto=$io_report->dts_reporte->data["previsto"][$j];
				$ld_aumento=$io_report->dts_reporte->data["aumento"][$j];
				$ld_disminucion=$io_report->dts_reporte->data["disminucion"][$j];
				$ld_devengado=$io_report->dts_reporte->data["devengado"][$j];
				$ld_cobrado=$io_report->dts_reporte->data["cobrado"][$j];
				//$ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$j];
				$ld_cobrado_anticipado=0;
				//$ls_status=$io_report->dts_reporte->data["status"][$z];
				$ld_monto_actualizado=$ld_previsto+$ld_aumento-$ld_disminucion-$ld_devengado;
				$ld_por_cobrar=$ld_devengado-$ld_cobrado;
				 
				  if($ls_nivel==1)
				  {
					$ld_total_previsto=$ld_total_previsto+$ld_previsto;
					$ld_total_aumento=$ld_total_aumento+$ld_aumento;
					$ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
					$ld_total_devengado=$ld_total_devengado+$ld_devengado;
					$ld_total_cobrado=$ld_total_cobrado+$ld_cobrado;
					$ld_total_cobrado_anticipado=$ld_total_cobrado_anticipado+$ld_cobrado_anticipado;
					$ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
					$ld_total_por_cobrar=$ld_total_por_cobrar+$ld_por_cobrar;
				  } 
				  

				$ld_previsto=number_format($ld_previsto,2,",",".");
				$ld_aumento=number_format($ld_aumento,2,",",".");
				$ld_disminucion=number_format($ld_disminucion,2,",",".");
				$ld_devengado=number_format($ld_devengado,2,",",".");
				$ld_cobrado=number_format($ld_cobrado,2,",",".");
				$ld_cobrado_anticipado=number_format($ld_cobrado_anticipado,2,",",".");
				$ld_monto_actualizado=number_format($ld_monto_actualizado,2,",",".");
				$ld_por_cobrar=number_format($ld_por_cobrar,2,",",".");
				
				$la_data1[$j]=array('cuenta'=>$ls_spi_cuenta,'denominacion'=>$ls_denominacion,'previsto'=>$ld_previsto,
							'aumento'=>$ld_aumento,'disminuci�n'=>$ld_disminucion,'devengado'=>$ld_devengado,
							'cobrado'=>$ld_cobrado,'cobrado_anticipado'=>$ld_cobrado_anticipado,
							'montoactualizado'=>$ld_monto_actualizado,'porcobrar'=>$ld_por_cobrar);
		  
				  
				
				  
				$ld_previsto=str_replace('.','',$ld_previsto);
				$ld_previsto=str_replace(',','.',$ld_previsto);		
				$ld_aumento=str_replace('.','',$ld_aumento);
				$ld_aumento=str_replace(',','.',$ld_aumento);		
				$ld_disminucion=str_replace('.','',$ld_disminucion);
				$ld_disminucion=str_replace(',','.',$ld_disminucion);		
				$ld_monto_actualizado=str_replace('.','',$ld_monto_actualizado);
				$ld_monto_actualizado=str_replace(',','.',$ld_monto_actualizado);
				$ld_devengado=str_replace('.','',$ld_devengado);
				$ld_devengado=str_replace(',','.',$ld_devengado);		
				$ld_cobrado=str_replace('.','',$ld_cobrado);
				$ld_cobrado=str_replace(',','.',$ld_cobrado);		
				$ld_cobrado_anticipado=str_replace('.','',$ld_cobrado_anticipado);
				$ld_cobrado_anticipado=str_replace(',','.',$ld_cobrado_anticipado);		
				$ld_por_cobrar=str_replace('.','',$ld_por_cobrar);
				$ld_por_cobrar=str_replace(',','.',$ld_por_cobrar);		
		}// fin del for
		 $DataSet = new pData;
		 $DataSet->AddPoint(array($ld_total_monto_actualizado),"Serie0");
		 $DataSet->AddPoint(array($ld_total_devengado),"Serie1");
		 $DataSet->AddPoint(array($ld_total_cobrado),"Serie2");
		 $DataSet->AddPoint(array($ld_total_por_cobrar),"Serie3");
		 $DataSet->AddPoint(array(""),"titulos");
		 $DataSet->AddSerie("Serie0");
		 $DataSet->AddSerie("Serie1");
		 $DataSet->AddSerie("Serie2");
		 $DataSet->AddSerie("Serie3");
		 $DataSet->SetSerieName("Actualizado","Serie0");
		 $DataSet->SetSerieName("Devengado","Serie1");
		 $DataSet->SetSerieName("Cobrado","Serie2");
		 $DataSet->SetSerieName("Por Cobrar","Serie3");
		 $DataSet->SetAbsciseLabelSerie("titulos");
		
		 // Initialise the graph
		 $Test = new pChart(700,230);
		 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",8);
		 $Test->setGraphArea(90,30,580,200);
		 $Test->drawFilledRoundedRectangle(7,7,593,223,5,240,240,240);
		 $Test->drawRoundedRectangle(5,5,595,225,5,230,230,230);
		 $Test->drawGraphArea(255,255,255,TRUE);
		 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);
		 $Test->drawGrid(4,TRUE,230,230,230,50);
		
		 // Draw the 0 line
		 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",6);
		 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);
		
		 // Draw the bar graph
		 $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE,80);
		
		
		 // Finish the graph
		 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",8);
		 $Test->drawLegend(596,50,$DataSet->GetDataDescription(),255,255,255);
		 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",10);
		 $Test->drawTitle(50,22,$ls_titulo,50,50,50,585);
		
		 $Test->Render("acumuladoxcuentasbarra.png");
		
		unset($la_data1);
		unset($la_data_tot);		 
	 }
	 else // Imprimimos el reporte
	 {
   	    $lb_valido=$io_report->uf_spi_reporte_acumulado_cuentas($ldt_fecini,$ldt_fecfin,$ls_cmbnivel,$lb_subniv,$ai_MenorNivel);
		$li_tot=$io_report->dts_reporte->getRowCount("spi_cuenta");// print $li_tot;
		for($z=1;$z<=$li_tot;$z++)
		{ 
			$ls_spi_cuenta=$io_report->dts_reporte->data["spi_cuenta"][$z];
			$ls_denominacion=$io_report->dts_reporte->data["denominacion"][$z];
			$ls_nivel=$io_report->dts_reporte->data["nivel"][$z];
			$ld_previsto=$io_report->dts_reporte->data["previsto"][$z]; 
			$ld_aumento=$io_report->dts_reporte->data["aumento"][$z];
			$ld_disminucion=$io_report->dts_reporte->data["disminucion"][$z];
			$ld_devengado=$io_report->dts_reporte->data["devengado"][$z];
			$ld_cobrado=$io_report->dts_reporte->data["cobrado"][$z];
			$ld_cobrado_anticipado=$io_report->dts_reporte->data["cobrado_anticipado"][$z];
			$ls_status=$io_report->dts_reporte->data["status"][$z];
			$ld_monto_actualizado=$ld_previsto+$ld_aumento-$ld_disminucion-$ld_devengado;
			$ld_por_cobrar=$ld_devengado-$ld_cobrado;
			 
			if($ls_status=='C')
			{
				$ld_total_previsto=$ld_total_previsto+$ld_previsto;
				$ld_total_aumento=$ld_total_aumento+$ld_aumento;
				$ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
				$ld_total_devengado=$ld_total_devengado+$ld_devengado;
				$ld_total_cobrado=$ld_total_cobrado+$ld_cobrado;
				$ld_total_cobrado_anticipado=$ld_total_cobrado_anticipado+$ld_cobrado_anticipado;
				$ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
				$ld_total_por_cobrar=$ld_total_por_cobrar+$ld_por_cobrar;
			} 
			$ld_previsto=number_format($ld_previsto,2,",",".");
			$ld_aumento=number_format($ld_aumento,2,",",".");
			$ld_disminucion=number_format($ld_disminucion,2,",",".");
			$ld_devengado=number_format($ld_devengado,2,",",".");
			$ld_cobrado=number_format($ld_cobrado,2,",",".");
			$ld_cobrado_anticipado=number_format($ld_cobrado_anticipado,2,",",".");
			$ld_monto_actualizado=number_format($ld_monto_actualizado,2,",",".");
			$ld_por_cobrar=number_format($ld_por_cobrar,2,",",".");
		      
			$la_data[$z]=array('cuenta'=>$ls_spi_cuenta,'denominacion'=>$ls_denominacion,'previsto'=>$ld_previsto,
						'aumento'=>$ld_aumento,'disminuci�n'=>$ld_disminucion,'devengado'=>$ld_devengado,
						'cobrado'=>$ld_cobrado,'cobrado_anticipado'=>$ld_cobrado_anticipado,
						'montoactualizado'=>$ld_monto_actualizado,'porcobrar'=>$ld_por_cobrar);
		      
		       $ld_previsto=str_replace('.','',$ld_previsto);
		       $ld_previsto=str_replace(',','.',$ld_previsto);		
		       $ld_aumento=str_replace('.','',$ld_aumento);
		       $ld_aumento=str_replace(',','.',$ld_aumento);		
		       $ld_disminucion=str_replace('.','',$ld_disminucion);
		       $ld_disminucion=str_replace(',','.',$ld_disminucion);		
		       $ld_monto_actualizado=str_replace('.','',$ld_monto_actualizado);
		       $ld_monto_actualizado=str_replace(',','.',$ld_monto_actualizado);
		       $ld_devengado=str_replace('.','',$ld_devengado);
		       $ld_devengado=str_replace(',','.',$ld_devengado);		
		       $ld_cobrado=str_replace('.','',$ld_cobrado);
		       $ld_cobrado=str_replace(',','.',$ld_cobrado);		
		       $ld_cobrado_anticipado=str_replace('.','',$ld_cobrado_anticipado);
		       $ld_cobrado_anticipado=str_replace(',','.',$ld_cobrado_anticipado);		
		       $ld_por_cobrar=str_replace('.','',$ld_por_cobrar);
		       $ld_por_cobrar=str_replace(',','.',$ld_por_cobrar);

			if($z==$li_tot)
			{
				 $DataSet = new pData;
				 $DataSet->AddPoint(array($ld_total_monto_actualizado),"Serie0");
				 $DataSet->AddPoint(array($ld_total_devengado),"Serie1");
				 $DataSet->AddPoint(array($ld_total_cobrado),"Serie2");
				 $DataSet->AddPoint(array($ld_total_por_cobrar),"Serie3");
				 $DataSet->AddPoint(array(""),"titulos");
				 $DataSet->AddSerie("Serie0");
				 $DataSet->AddSerie("Serie1");
				 $DataSet->AddSerie("Serie2");
				 $DataSet->AddSerie("Serie3");
				 $DataSet->SetSerieName("Actualizado","Serie0");
				 $DataSet->SetSerieName("Devengado","Serie1");
				 $DataSet->SetSerieName("Cobrado","Serie2");
				 $DataSet->SetSerieName("Por Cobrar","Serie3");
				 $DataSet->SetAbsciseLabelSerie("titulos");
				
				 // Initialise the graph
				 $Test = new pChart(700,230);
				 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",8);
				 $Test->setGraphArea(90,30,580,200);
				 $Test->drawFilledRoundedRectangle(7,7,593,223,5,240,240,240);
				 $Test->drawRoundedRectangle(5,5,595,225,5,230,230,230);
				 $Test->drawGraphArea(255,255,255,TRUE);
				 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);
				 $Test->drawGrid(4,TRUE,230,230,230,50);
				
				 // Draw the 0 line
				 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",6);
				 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);
				
				 // Draw the bar graph
				 $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE,80);
				
				
				 // Finish the graph
				 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",8);
				 $Test->drawLegend(596,50,$DataSet->GetDataDescription(),255,255,255);
				 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",10);
				 $Test->drawTitle(50,22,$ls_titulo,50,50,50,585);
				
				 $Test->Render("acumuladoxcuentasbarra.png");
			}//if
		}//for
		unset($la_data);
		unset($la_data_tot);			
	}
	unset($io_report);
	unset($io_funciones);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Acumulado por Cuentas</title>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/report.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="498" border="0" align="center">
  <tr>
    <td width="320" class="sin-borde2"><div align="center" class="titulo-celdanew"> Acumulado por Cuentas </div></td>
  </tr>
  <tr>
    <td width="320"><img src="acumuladoxcuentasbarra.png" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">
<a href="javascript:ue_print();"> <img src="../../shared/imagebank/tools20/print.gif" width="35" height="30" border="0"/></a></div>
	</td>
  </tr>
  <tr>
    <td width="320"></td>
  </tr>
</table>


</body>
<script language="JavaScript">
function ue_print()
{
	window.print();
}
</script>
</html>

