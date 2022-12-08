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
	
//------------------------------------------------------------------------------------------------------------------------------
		require_once ("sigesp_spg_class_tcpdf.php");
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report = new sigesp_spg_funciones_reportes();
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();			
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		$ls_nombrearchivo="acumulado_por_cuentas.txt";
		$lo_archivo=@fopen("$ls_nombrearchivo","a+");
		require_once("../../shared/graficos/pChart/pData.class");
		require_once("../../shared/graficos/pChart/pChart.class");
//--------------------------------------------------  Par�metros para Filtar el Reporte  --------------------------------------
		$ldt_periodo        = $_SESSION["la_empresa"]["periodo"];
		$li_ano             = substr($ldt_periodo,0,4);
		$li_estmodest       = $_SESSION["la_empresa"]["estmodest"];
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
		$ls_text_periodo 	= $_GET["tperiodo"];
		$ld_periodo      	= $_GET["periodo"];
		$ld_tipper       	= $_GET["tipper"];
		$ld_fecinirep		= "";
		$ld_fecfinrep 		= "";
//-----------------------------------------------------------------------------------------------------------------------------

		switch($ld_tipper)
		{
			 case 1:
			       $ld_per01 		= intval($ld_periodo);
				   $ld_per02 		= "";
				   $ld_per03 		= "";
				   $ls_desper 		= "MENSUAL"; 
			       $ld_fecinirep	= $li_ano."/".$ld_periodo."/"."01";
				   $ld_fecfinrep	= $io_fecha->uf_last_day($ld_periodo,$li_ano); 
				   $ld_fecfinrep	= $li_ano."/".substr($ld_periodo,0,2)."/".substr($ld_fecfinrep,0,2);
			       $ld_anterior 	= 1; 
			       break;

			 case 2:
			      $ld_per01 		= intval(substr($ld_periodo,0,2));
				  $ld_per02 		= intval(substr($ld_periodo,2,2));
				  $ls_desper 		= "BIMENSUAL";
				  $ld_fecinirep		= $li_ano."/".substr($ld_periodo,0,2)."/"."01";
				  $ld_fecfinrep		= $io_fecha->uf_last_day(substr($ld_periodo,2,2),$li_ano);
				  $ld_fecfinrep		= $li_ano."/".substr($ld_periodo,2,2)."/".substr($ld_fecfinrep,0,2);
				  $ld_per03 		= "";
				  $ld_anterior 		= 2;
			      break;

			 case 3:
			      $ld_per01 		= intval(substr($ld_periodo,0,2));
				  $ld_per02 		= intval(substr($ld_periodo,2,2));
				  $ld_per03 		= intval(substr($ld_periodo,4,2));
				  $ls_desper 		= "TRIMESTRAL";
				  $ld_fecinirep		= $li_ano."/".substr($ld_periodo,0,2)."/"."01";
				  $ld_fecfinrep		= $io_fecha->uf_last_day(substr($ld_periodo,4,2),$li_ano);
				  $ld_fecfinrep		= $li_ano."/".substr($ld_periodo,4,2)."/".substr($ld_fecfinrep,0,2);
				  $ld_anterior 		= 3;
			      break;
		}


		global $ls_tipoformato;
		global $la_data_tot_bsf;
		global $la_data_tot;
		require_once("sigesp_spg_reporte.php");
		$io_report = new sigesp_spg_reporte();

		$li_candeccon = $_SESSION["la_empresa"]["candeccon"];
		$li_tipconmon = $_SESSION["la_empresa"]["tipconmon"];
		$li_redconmon = $_SESSION["la_empresa"]["redconmon"];
//------------------------------------------------------------------------------------------------------------------------------		
		if($li_estmodest==1)
		{
			$ls_codestpro4_min =  "0000000000000000000000000";
			$ls_codestpro5_min =  "0000000000000000000000000";
			$ls_codestpro4h_max = "0000000000000000000000000";
			$ls_codestpro5h_max = "0000000000000000000000000";
			if(($ls_codestpro1_min=="")&&($ls_codestpro2_min=="")&&($ls_codestpro3_min==""))
			{
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
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
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
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
		elseif($li_estmodest==2)
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
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,
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
			  if($io_function_report->uf_spg_reporte_select_max_programatica($ls_codestpro1h_max,$ls_codestpro2h_max,
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
		
		$ls_cmbmesdes = $_GET["cmbmesdes"];
		$ldt_fecini=$li_ano."-".$ls_cmbmesdes."-01";
		$ldt_fecini_rep="01/".$ls_cmbmesdes."/".$li_ano;
		$ls_mes=$ls_cmbmesdes;
		$ls_ano=$li_ano;
		$fecfin=$io_fecha->uf_last_day($ls_mes,$ls_ano);
		$ldt_fecfin=$io_funciones->uf_convertirdatetobd($fecfin);
		
		$cmbnivel=$_GET["cmbnivel"];
		if($cmbnivel=="s1")
		{
          $ls_cmbnivel="1";
		}
		else
		{
          $ls_cmbnivel=$cmbnivel;
		}
		$ls_programatica_desde=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
		$ls_programatica_hasta=$ls_codestpro1h.$ls_codestpro2h.$ls_codestpro3h.$ls_codestpro4h.$ls_codestpro5h;
		if($li_estmodest==1)
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
		
	    $ls_cuentades_min=$_GET["txtcuentades"];
	    $ls_cuentahas_max=$_GET["txtcuentahas"];
		if($ls_cuentades_min=="")
		{
		   if($io_function_report->uf_spg_reporte_select_min_cuenta($ls_cuentades_min))
		   {
		     $ls_cuentades=$ls_cuentades_min;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentades=$ls_cuentades_min;
		}
		if($ls_cuentahas_max=="")
		{
		   if($io_function_report->uf_spg_reporte_select_max_cuenta($ls_cuentahas_max))
		   {
		     $ls_cuentahas=$ls_cuentahas_max;
		   } 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay cuentas presupuestraias');"); 
				print(" close();");
				print("</script>");
		   }
		}
		else
		{
		    $ls_cuentahas=$ls_cuentahas_max;
		}
	    $ls_codfuefindes=$_GET["txtcodfuefindes"];
	    $ls_codfuefinhas=$_GET["txtcodfuefinhas"];
		if (($ls_codfuefindes=='')&&($ls_codfuefindes==''))
		{
		   if($io_function_report->uf_spg_select_fuentefinanciamiento(&$ls_minfuefin,&$ls_maxfuefin))
		   {
		     $ls_codfuefindes=$ls_minfuefin;
		     $ls_codfuefinhas=$ls_maxfuefin;
		   } 
		}
		/////////////////////////////////         SEGURIDAD               ///////////////////////////////////
		$ls_desc_event="Solicitud de Reporte Ejecucion Presupuestaria Mensual de Gasto desde la fecha ".$ldt_fecini_rep." hasta ".$fecfin." ,Desde la programatica ".$ls_programatica_desde."  hasta ".$ls_programatica_hasta;
		$io_function_report->uf_load_seguridad_reporte("SPG","sigesp_spg_r_ejecucion_financiera_mensual.php",$ls_desc_event);
		////////////////////////////////         SEGURIDAD               ///////////////////////////////////
	//----------------------------------------------------  Par�metros del encabezado  ---------------------------------------------
		$ls_titulo=" EJECUCION PRESUPUESTARIA MENSUAL DE GASTO DESDE FECHA  ".substr($ld_fecinirep,8,2)."/".substr($ld_fecinirep,5,2)."/".substr($ld_fecinirep,0,4)."  HASTA  ".substr($ld_fecfinrep,8,2)."/".substr($ld_fecfinrep,5,2)."/".substr($ld_fecfinrep,0,4);  
		$ls_titulo1="";
		if($li_estmodest==1)
	    {
		 $ls_titulo1=" DESDE LA ESTRUCTURA PRESUPUESTARIA  ".$ls_programatica_desde1."  HASTA  ".$ls_programatica_hasta1;  
		}
		elseif($li_estmodest==2)
		{
		 $ls_titulo1=" DESDE LA PROGRAMATICA  ".$ls_programatica_desde1."  HASTA  ".$ls_programatica_hasta1;  
		}
    //------------------------------------------------------------------------------------------------------------------------------ $ld_fecinirep,$ld_fecfinrep
    
    
    
    
    
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
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
	$lb_valido=$io_report->uf_spg_reporte_ejecucion_financiera_mensual($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,$ls_codestpro4,
	                                                        $ls_codestpro5,$ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
	                                                        $ls_codestpro4h,$ls_codestpro5h,$ld_fecinirep,$ld_fecfinrep,$ls_cmbnivel,$ls_cuentades,$ls_cuentahas,
															$ls_codfuefindes,$ls_codfuefinhas,$ls_estclades,$ls_estclahas,$rs_data);
	 if($lb_valido==false) // Existe alg�n error � no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		error_reporting(E_ALL);
		$io_tcpdf= new sigesp_spg_class_tcpdf ("L", PDF_UNIT, "legal", true);
		$io_tcpdf->AliasNbPages();		
		$io_tcpdf->SetHeaderData($_SESSION["ls_logo"],30, date("d/m/Y"), date("h:i a").'-'.$_SESSION["ls_database"]);
		$io_tcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$io_tcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$io_tcpdf->SetMargins(3, PDF_MARGIN_TOP,3);
		$io_tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$io_tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$io_tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$io_tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
		$io_tcpdf->AliasNbPages();
		$io_tcpdf->AddPage();
		$io_tcpdf->SetFont("helvetica","B",8);
	 	$io_tcpdf->Cell(0,10,$ls_titulo,0,0,'C');
		$io_tcpdf->Ln(3);
		$io_tcpdf->Cell(0,10,$ls_titulo1,0,0,'C');
		$io_tcpdf->Ln();
		
		$ld_total_asignado=0;
		$ld_total_aumento=0;
		$ld_total_disminucion=0;
		$ld_total_monto_actualizado=0;
		$ld_total_compromiso=0;
		$ld_total_precompromiso=0;
		$ld_total_compromiso=0;
		$ld_total_saldo_comprometer=0;
		$ld_total_causado=0;
		$ld_total_pagado=0;
		$ld_total_por_paga=0;
		$ld_aumento=0;
	    $ld_disminucion=0;
	    $ld_precompromiso=0;
	    $ld_compromiso=0;
	    $ld_causado=0;
	    $ld_pagado=0;
		$ld_monto_actualizado=0;
  	    $ld_saldo_comprometer=0;
		$ld_por_paga=0;
		$lb_valido2=false;		
		$li_tot=$rs_data->RecordCount();
		$z=0;
		
		while(!$rs_data->EOF)
		{
			  $ls_spg_cuenta=$rs_data->fields["spg_cuenta"];
		      $ls_denominacion=utf8_encode(trim($rs_data->fields["denominacion"]));
			  $ls_nivel=$rs_data->fields["nivel"];
			  $ld_asignado=$rs_data->fields["asignado"];//print "$ld_fecinirep   $ld_fecfinrep";
			  
			  $lb_valido2=$io_report->uf_spg_reporte_detalle_ejecucion_financiera_mensual($ls_codestpro1,$ls_codestpro2,$ls_codestpro3,
			                                                                   $ls_codestpro4,$ls_codestpro5,
																			   $ls_codestpro1h,$ls_codestpro2h,$ls_codestpro3h,
	                                                                           $ls_codestpro4h,$ls_codestpro5h,
																			   $ls_estclades,$ls_estclahas,$ls_spg_cuenta,$ld_fecinirep,
															                   $ld_fecfinrep,$rs_data2);
															
					
			  while((!$rs_data2->EOF)&&($lb_valido2))
			  {
				  $ld_aumento=$ld_aumento+$rs_data2->fields["aumento"]; 
				  $ld_disminucion=$ld_disminucion+$rs_data2->fields["disminucion"];
				  $ld_precompromiso=$ld_precompromiso+$rs_data2->fields["precompromiso"];
				  $ld_compromiso=$ld_compromiso+$rs_data2->fields["compromiso"];
				  $ld_causado=$ld_causado+$rs_data2->fields["causado"];
				  $ld_pagado=$ld_pagado+$rs_data2->fields["pagado"];				
				  $ld_monto_actualizado=$ld_asignado+$ld_aumento-$ld_disminucion;				 
				  $ld_saldo_comprometer=$ld_asignado+$ld_aumento-$ld_disminucion-$ld_precompromiso-$ld_compromiso;
				  $ld_por_paga=$ld_causado-$ld_pagado;
			  	  $rs_data2->MoveNext();	
			  }
			  
			  
			  
			   if($ls_nivel==1)
			   {
			  	
				  $ld_total_asignado=$ld_total_asignado+$ld_asignado; 
				  $ld_total_aumento=$ld_total_aumento+$ld_aumento;
				  $ld_total_disminucion=$ld_total_disminucion+$ld_disminucion;
				  $ld_total_monto_actualizado=$ld_total_monto_actualizado+$ld_monto_actualizado;
				  $ld_total_precompromiso=$ld_total_precompromiso+$ld_precompromiso;
				  $ld_total_compromiso=$ld_total_compromiso+$ld_compromiso;
				  $ld_total_saldo_comprometer=$ld_total_saldo_comprometer+$ld_saldo_comprometer;
				  $ld_total_causado=$ld_total_causado+$ld_causado;
				  $ld_total_pagado=$ld_total_pagado+$ld_pagado;
				  $ld_total_por_paga=$ld_total_por_paga+$ld_por_paga;
			  }
			  
			  
			  $ls_spg_cuenta=trim($ls_spg_cuenta);
			  $la_data[$z]=array($ls_spg_cuenta,utf8_encode($ls_denominacion),number_format($ld_asignado,2,",","."),
			                     number_format($ld_aumento,2,",","."),number_format($ld_disminucion,2,",","."),
								 number_format($ld_monto_actualizado,2,",","."),number_format($ld_precompromiso,2,",","."),
								 number_format($ld_compromiso,2,",","."),number_format($ld_saldo_comprometer,2,",","."),
								 number_format($ld_causado,2,",","."),number_format($ld_pagado,2,",","."),
							     number_format($ld_por_paga,2,",","."));
             
			 $ls_cadena=$ls_spg_cuenta."/".$ls_denominacion."/".number_format($ld_asignado,2,",",".")."/".number_format($ld_aumento,2,",",".")."/".number_format($ld_disminucion,2,",",".")."/".number_format($ld_monto_actualizado,2,",",".")."/".number_format($ld_precompromiso,2,",",".")."/".number_format($ld_compromiso,2,",",".")."/".number_format($ld_saldo_comprometer,2,",",".")."/".number_format($ld_saldo_comprometer,2,",",".")."/".number_format($ld_causado,2,",",".")."/".number_format($ld_pagado,2,",",".")."/".number_format($ld_por_paga,2,",",".")."\r\n";
			 if ($lo_archivo)			
			 {
				@fwrite($lo_archivo,$ls_cadena);
			 }
			 
			 $ld_aumento=0;
			 $ld_disminucion=0;
			 $ld_precompromiso=0;
			 $ld_compromiso=0;
			 $ld_causado=0;
			 $ld_pagado=0;
			 $ld_monto_actualizado=0;
			 $ld_saldo_comprometer=0;
			 $ld_por_paga=0;
			 		
			if($z==($li_tot-1))
			{

				 // Dataset definition 
				 $DataSet = new pData;
				 $DataSet->AddPoint(array($ld_total_precompromiso,$ld_total_compromiso,$ld_total_causado,$ld_total_pagado,$ld_total_por_paga),"Serie1");
				 $DataSet->AddPoint(array("Precompromiso","Compromiso","Causado","Pagado","Por Pagar"),"titulos");
				 $DataSet->AddAllSeries();
				 $DataSet->SetAbsciseLabelSerie("titulos");
				

				 // Initialise the graph
				 $Test = new pChart(500,400);
				 $Test->drawFilledRoundedRectangle(7,7,493,393,5,240,240,240);
				 $Test->drawRoundedRectangle(5,5,495,395,5,230,230,230);
				
				 // This will draw a shadow under the pie chart
				 $Test->drawFilledCircle(225,205,170,200,200,200);
				
				 // Draw the pie chart
				 $Test->setFontProperties("../../shared/graficos/Fonts/tahoma.ttf",8);
				 $Test->AntialiasQuality = 0;
				 $Test->drawBasicPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),220,200,170,PIE_PERCENTAGE,255,255,218);
				 $Test->drawPieLegend(380,15,$DataSet->GetData(),$DataSet->GetDataDescription(),150,150,150);
				
				 $Test->Render("ejefinancieramensualtorta.png");
				 

			}//if
			  
			  
			  $rs_data->MoveNext();	
			  $z=$z+1;
		}//fin del while 
		
		
		if(!$lb_valido2)
		   {
				print("<script language=JavaScript>");
				print(" alert('No hay nada que reportar');"); 
				print(" close();");
				print("</script>");
		   }
		
		}
			
		
	   
	unset($io_report);
	unset($io_funciones);
	unset($io_function_report);		
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> Ejecucion Financiera Mensual </title>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/report.css" rel="stylesheet" type="text/css" />
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="498" border="0" align="center">
  <tr>
    <td width="320" class="sin-borde2"><div align="center" class="titulo-celdanew"> Ejecucion Financiera Mensual </div></td>
  </tr>
  <tr>
    <td width="320"><img src="ejefinancieramensualtorta.png" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="right">
<a href="javascript:ue_print();"><img src="../../shared/imagebank/tools20/print.gif" width="35" height="30" border="0"/></div></a>
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

