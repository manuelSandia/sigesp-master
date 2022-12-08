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
	  // para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo =  tempnam("/tmp", "EJECUCION TRIMESTRAL DE GASTOS Y APLICACIONES FINANCIERAS.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();	
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report=new sigesp_spg_funciones_reportes();	
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//-----------------------------------------------------------------------------------------------------------------------------
		global $la_data_tot;
		require_once("sigesp_spg_class_reportes_instructivos.php");
		$io_report = new sigesp_spg_class_reportes_instructivos();
		 
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_codestpro1_min  = $_GET["codestpro1"];
		$ls_codestpro1_aux=$ls_codestpro1_min;		
		$ls_codestpro2_min  = $_GET["codestpro2"];
		$ls_codestpro3_min  = $_GET["codestpro3"];
		$ls_codestpro4_min  = $_GET["codestpro4"];
		$ls_codestpro5_min  = $_GET["codestpro5"];
		$ls_codestpro1h_max = $_GET["codestpro1h"];
		$ls_codestpro2h_max = $_GET["codestpro2h"];
		$ls_codestpro3h_max = $_GET["codestpro3h"];
		$ls_codestpro4h_max = $_GET["codestpro4h"];
		$ls_codestpro5h_max = $_GET["codestpro5h"];
		$ls_estclades       = $_GET["estclades"];
	    $ls_estclahas       = $_GET["estclahas"];
		$ls_tipoformato=1;
		if($li_estmodest==1)
		{
			$ls_codestpro4_min = "0000000000000000000000000";
			$ls_codestpro5_min = "0000000000000000000000000";
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
																			 $ls_codestpro4h_max,$ls_estclahas))
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
		    $ls_codestpro4_min = $_GET["codestpro4"];
			$ls_codestpro5_min = $_GET["codestpro5"];
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
			}else
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
			  if($io_function_report->uf_spg_reporte_select_min_programatica($ls_codestpro1_min,$ls_codestpro2_min,$ls_codestpro3_min,
			                                                                 $ls_codestpro4_min,$ls_codestpro5_min,$ls_estclades))
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
		$ls_codestpro1  = $io_funciones->uf_cerosizquierda($ls_codestpro1_min,25);
		$ls_codestpro2  = $io_funciones->uf_cerosizquierda($ls_codestpro2_min,25);
		$ls_codestpro3  = $io_funciones->uf_cerosizquierda($ls_codestpro3_min,25);
		$ls_codestpro4  = $io_funciones->uf_cerosizquierda($ls_codestpro4_min,25);
		$ls_codestpro5  = $io_funciones->uf_cerosizquierda($ls_codestpro5_min,25);
		$ls_codestpro1h  = $io_funciones->uf_cerosizquierda($ls_codestpro1h_max,25);
		$ls_codestpro2h  = $io_funciones->uf_cerosizquierda($ls_codestpro2h_max,25);
		$ls_codestpro3h  = $io_funciones->uf_cerosizquierda($ls_codestpro3h_max,25);
		$ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro4h_max,25);
		$ls_codestpro4h  = $io_funciones->uf_cerosizquierda($ls_codestpro5h_max,25);
		
		$ls_cmbmes    = $_GET["cmbmes"];
		$ls_periodo   = "";
		$li_trimestre = "";
		switch($ls_cmbmes)
		{
		 case '0103' : $ls_periodo   = 'ENERO - MARZO';
		               $li_trimestre = 'I';
		 break;
		 
		 case '0406' : $ls_periodo   = 'ABRIL - JUNIO';
		               $li_trimestre = 'II';
		 break;
		 
		 case '0709' : $ls_periodo   = 'JULIO - SEPTIEMBRE';
		               $li_trimestre = 'III';
		 break;
		 
		 case '1012' : $ls_periodo   = 'OCTUBRE - DICIEMBRE';
		               $li_trimestre = 'IV';
		 break;
		}
		$li_mesdes=substr($ls_cmbmes,0,2);
		$ldt_fecdes=$li_ano."-".$li_mesdes."-01";
		$li_meshas=substr($ls_cmbmes,2,2);
		$ldt_ult_dia=$io_fecha->uf_last_day($li_meshas,$li_ano);
		$fechas=$ldt_ult_dia;
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_mesdes=$io_fecha->uf_load_nombre_mes($li_mesdes);
		$ls_meshas=$io_fecha->uf_load_nombre_mes($li_meshas);
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
		
//----------------------------------------------------  Par�metros del encabezado  ---------------------------------------------
		$ls_titulo=" EJECUCION TRIMESTRAL DE GASTOS Y APLICACIONES FINANCIERAS";       
//--------------------------------------------------------------------------------------------------------------------------------
      //$lb_valido=true;
	  $li_numnivcuenta = count(explode("-",$_SESSION["la_empresa"]["formpre"]));
	  if($li_numnivcuenta == 5)
	  {
	   $lb_valido=$io_report->uf_spg_reportes_ejecucion_trimestral_excel($ls_codestpro1,$ls_codestpro2,
																		 $ls_codestpro3,$ls_codestpro4,
																		 $ls_codestpro5,$ls_codestpro1h,
																		 $ls_codestpro2h,$ls_codestpro3h,
																		 $ls_codestpro4h,$ls_codestpro5h,
																		 $ldt_fecdes,$ldt_fechas,
																		 $ls_codfuefindes,$ls_codfuefinhas,
																		 $ls_estclades,$ls_estclahas);
	  }
	  else
	  {
       $lb_valido=$io_report->uf_spg_reportes_ejecucion_trimestral($ls_codestpro1,$ls_codestpro2,
	                                                             $ls_codestpro3,$ls_codestpro4,
																 $ls_codestpro5,$ls_codestpro1h,
																 $ls_codestpro2h,$ls_codestpro3h,
															     $ls_codestpro4h,$ls_codestpro5h,
																 $ldt_fecdes,$ldt_fechas,
																 $ls_codfuefindes,$ls_codfuefinhas,
																 $ls_estclades,$ls_estclahas);
	  }
	 if($lb_valido==false) // Existe alg�n error � no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		//print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	 	
		
		$li_tot=$io_report->dts_reporte->getRowCount("spg_cuenta");
		
		
		
				
		$lo_encabezado= &$lo_libro->addformat();
		$lo_encabezado->set_bold();
		$lo_encabezado->set_font("Verdana");
		$lo_encabezado->set_align('center');
		$lo_encabezado->set_size('11');
		$lo_titulo= &$lo_libro->addformat();
		$lo_titulo->set_bold();
		$lo_titulo->set_font("Verdana");
		$lo_titulo->set_align('center');
		$lo_titulo->set_size('9');
		
		$lo_dataright_bold= &$lo_libro->addformat();
		$lo_dataright_bold->set_bold();
		$lo_dataright_bold->set_font("Verdana");
		$lo_dataright_bold->set_align('right');
		$lo_dataright_bold->set_size('9');
		
		$lo_datacenter= &$lo_libro->addformat();
		$lo_datacenter->set_font("Verdana");
		$lo_datacenter->set_align('center');
		$lo_datacenter->set_size('9');
		$lo_dataleft= &$lo_libro->addformat();
		$lo_dataleft->set_text_wrap();
		$lo_dataleft->set_font("Verdana");
		$lo_dataleft->set_align('left');
		$lo_dataleft->set_size('9');
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		$lo_hoja->set_column(0,0,15);
		$lo_hoja->set_column(1,1,20);
		$lo_hoja->set_column(2,2,30);
		$lo_hoja->set_column(3,3,20);
		$lo_hoja->set_column(4,4,13);
		$lo_hoja->set_column(5,7,30);
		$lo_hoja->write(0, 3,$ls_titulo,$lo_titulo);
	    $ls_spg_cuenta_ant="";
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
		$li_row=2;
		$contlineas = 2;
	//	$li_tot=$rs_data->RecordCount();
		$z=0;
		$ld_total_asignado=0;
		$ld_total_modificado=0;
		$ld_total_programado=0;
		$ld_total_compromiso=0;
		$ld_total_causado=0;
		$ld_total_pagado=0;
		$ld_total_programado_acum=0;
		$ld_total_compromiso_acum=0;
		$ld_total_causado_acum=0;
		$ld_total_pagado_acum=0;
		$ld_total_disp_fecha=0;	
		$ld_asignado_partida=0;
		$ld_modificado_partida=0;
		$ld_programado_partida=0;
		$ld_compromiso_partida=0;
		$ld_causado_partida=0;
		$ld_pagado_partida=0;
		$ld_programado_acum_partida=0;
		$ld_compromiso_acum_partida=0;
		$ld_causado_acum_partida=0;
		$ld_pagado_acum_partida=0;
		$ld_disp_fecha_partida=0;
		$ls_partida_aux="";	
		
		$lo_hoja->write(0, 0,"C�DIGO PRESUPUESTARIO DEL ENTE",$lo_dataleft);
		$lo_hoja->write(0, 1," ".$_SESSION["la_empresa"]["codemp"],$lo_dataleft);
		$lo_hoja->write(1, 0,"DENOMINACION DEL ENTE",$lo_dataleft);
		$lo_hoja->write(1, 1,$_SESSION["la_empresa"]["nombre"],$lo_dataleft);
		$lo_hoja->write(2, 0,"ORGANO DE ADSCRIPCION",$lo_dataleft);
		$lo_hoja->write(2, 1,$_SESSION["la_empresa"]["nomorgads"],$lo_dataleft);
		$lo_hoja->write(3, 0,"PROYECTO Y/O ACCION CENTRALIZADA",$lo_dataleft);
		$lo_hoja->write(3, 1,"CONSOLIDADO GENERAL",$lo_dataleft);
		$lo_hoja->write(5, 0,"PERIODO PRESUPUESTARIO",$lo_dataleft);
		$lo_hoja->write(5, 1,$ls_periodo." ".substr($_SESSION['la_empresa']['periodo'],0,4),$lo_dataleft);		
		$lo_hoja->write(7, 5,$ls_titulo,$lo_encabezado);
		$li_row=8;
		$lo_hoja->write($li_row, 8, "Ejecutado en el Trimestre N� ".$ls_trimestre,$lo_titulo);
		$lo_hoja->write($li_row, 9, "",$lo_titulo);
		$lo_hoja->write($li_row, 10, "",$lo_titulo);
		$lo_hoja->write($li_row, 14, "Acumulado en el Trimestre N� ".$ls_trimestre,$lo_titulo);
		$lo_hoja->write($li_row, 15, "",$lo_titulo);
		$lo_hoja->write($li_row, 16, "",$lo_titulo);
	

		$li_row=$li_row+1;
		$lo_hoja->write($li_row, 0, "PART",$lo_titulo);
		$lo_hoja->write($li_row, 1, "GEN",$lo_titulo);
		$lo_hoja->write($li_row, 2, "ESP",$lo_titulo);
		$lo_hoja->write($li_row, 3, "SUB-ESP",$lo_titulo);
		$lo_hoja->write($li_row, 5, "DENOMINACI�N",$lo_titulo);
		$lo_hoja->write($li_row, 6, "PRESUPUESTO APROBADO",$lo_titulo);
		$lo_hoja->write($li_row, 7, "PRESUPUESTO MODIFICADO",$lo_titulo);
		$lo_hoja->write($li_row, 8, "PROGRAMADO EN EL TRIMESTRE N� ",$lo_titulo);
		$lo_hoja->write($li_row, 9, "COMPROMISO",$lo_titulo);
		$lo_hoja->write($li_row, 10, "CAUSADO",$lo_titulo);
		$lo_hoja->write($li_row, 11, "PAGADO",$lo_titulo);
		$lo_hoja->write($li_row, 12, "PROGRAMADO",$lo_titulo);
		$lo_hoja->write($li_row, 13, "COMPROMISO",$lo_titulo);
		$lo_hoja->write($li_row, 14, "CAUSADO",$lo_titulo);
		$lo_hoja->write($li_row, 15, "PAGADO",$lo_titulo);
		$lo_hoja->write($li_row, 16, "DISPONIBILIDAD PRESUPUESTARIA",$lo_titulo);
		$li_row++;
		
		for($z=1;$z<=$li_tot;$z++)
		{		
					$ld_asignado=0;
					$ld_modificado=0;
					$ld_programado=0;
					$ld_compromiso=0;
					$ld_causado=0;
					$ld_pagado=0;
					$ld_programado_acum=0;
					$ld_compromiso_acum=0;
					$ld_causado_acum=0;
					$ld_pagado_acum=0;
					$ld_disp_fecha=0;
					$ls_partida="";
					$ls_generica="";
					$ls_especifica="";
					$ls_subesp="";
				  $ls_status="";
				  $ls_spg_cuenta             = trim($io_report->dts_reporte->data["spg_cuenta"][$z]);
				  $io_function_report->uf_get_spg_cuenta($ls_spg_cuenta,$ls_partida,$ls_generica,$ls_especifica,$ls_subesp,$as_spg_int);
				  $ls_denominacion           = trim($io_report->dts_reporte->data["denominacion"][$z]);
				  $ld_asignado               = $io_report->dts_reporte->data["asignado"][$z];
				  $ld_modificado             = $io_report->dts_reporte->data["modificado"][$z];
				  $ld_programado             = $io_report->dts_reporte->data["programado"][$z];
				  $ld_compromiso             = $io_report->dts_reporte->data["compromiso"][$z];
				  $ld_causado                = $io_report->dts_reporte->data["causado"][$z];
				  $ld_pagado                 = $io_report->dts_reporte->data["pagado"][$z];
				  $ld_programado_acum        = $io_report->dts_reporte->data["programado_acum"][$z];
				  $ld_compromiso_acum        = $io_report->dts_reporte->data["compromiso_acum"][$z];
				  $ld_causado_acum           = $io_report->dts_reporte->data["causado_acum"][$z];
				  $ld_pagado_acum            = $io_report->dts_reporte->data["pagado_acum"][$z];
				  $ld_disp_fecha             = $io_report->dts_reporte->data["disponible_fecha"][$z];
				  $ls_status                 = $io_report->dts_reporte->data["status"][$z];
				  
				  if($ls_status == "C")
				  {
				   $ld_total_asignado         = $ld_total_asignado + $ld_asignado;
				   $ld_total_modificado       = $ld_total_modificado + $ld_modificado;
		           $ld_total_programado       = $ld_total_programado + $ld_programado;
		           $ld_total_compromiso       = $ld_total_compromiso + $ld_compromiso;
		           $ld_total_causado          = $ld_total_causado + $ld_causado;
		           $ld_total_pagado           = $ld_total_pagado + $ld_pagado;
		           $ld_total_programado_acum  = $ld_total_programado_acum + $ld_programado_acum;
		           $ld_total_compromiso_acum  = $ld_total_compromiso_acum + $ld_compromiso_acum;
		           $ld_total_causado_acum     = $ld_total_causado_acum + $ld_causado_acum;
		           $ld_total_pagado_acum      = $ld_total_pagado_acum + $ld_pagado_acum;
		           $ld_total_disp_fecha       = $ld_total_disp_fecha + $ld_disp_fecha;
				  } 
				  
				  ///-------------------agrupar por partida---------------------------------------
				  if ($ls_partida_aux=="")
				  {
				 		$ls_partida_aux=$ls_partida; 
				  }
				  elseif ($ls_partida_aux==$ls_partida)
				  {
				  	
					 if($ls_status=="C")
					 {
						 $ls_partida_aux=$ls_partida;
						 $ld_asignado_partida=$ld_asignado_partida+$ld_asignado;
						 $ld_modificado_partida=$ld_modificado_partida+$ld_modificado;
						 $ld_programado_partida=$ld_programado_partida+$ld_programado;
						 $ld_compromiso_partida=$ld_compromiso_partida+$ld_compromiso;
						 $ld_causado_partida=$ld_causado_partida+$ld_causado;
						 $ld_pagado_partida=$ld_pagado_partida+$ld_pagado;
						 $ld_programado_acum_partida=$ld_programado_acum_partida+$ld_programado_acum;
						 $ld_compromiso_acum_partida=$ld_compromiso_acum_partida+$ld_compromiso_acum;
						 $ld_causado_acum_partida=$ld_causado_acum_partida+$ld_causado_acum;
						 $ld_pagado_acum_partida=$ld_pagado_acum_partida+$ld_pagado_acum;
						 $ld_disp_fecha_partida=$ld_disp_fecha_partida+$ld_disp_fecha;	
					}			 
				  }
				  else
				  {
				  	 $ld_asignado_partida       = number_format($ld_asignado_partida,2,",",".");
				     $ld_modificado_partida     = number_format($ld_modificado_partida,2,",",".");
				     $ld_programado_partida     = number_format($ld_programado_partida,2,",",".");
				     $ld_compromiso_partida     = number_format($ld_compromiso_partida,2,",",".");
				     $ld_causado_partida        = number_format($ld_causado_partida,2,",",".");
				     $ld_pagado_partida         = number_format($ld_pagado_partida,2,",",".");
				     $ld_programado_acum_partida  = number_format($ld_programado_acum_partida,2,",",".");
				     $ld_compromiso_acum_partida  = number_format($ld_compromiso_acum_partida,2,",",".");
				     $ld_causado_acum_partida     = number_format($ld_causado_acum_partida,2,",",".");
				     $ld_pagado_acum_partida      = number_format($ld_pagado_acum_partida,2,",",".");
				     $ld_disp_fecha_partida       = number_format($ld_disp_fecha_partida,2,",",".");

/*					 
					 uf_print_total_partidas($ls_partida_aux,$ld_asignado_partida,$ld_modificado_partida,
	                                        $ld_programado_partida, $ld_compromiso_partida,
									        $ld_causado_partida,$ld_pagado_partida,
									        $ld_programado_acum_partida,$ld_compromiso_acum_partida,
									        $ld_causado_acum_partida,$ld_pagado_acum_partida,
											$ld_disp_fecha_partida,&$io_pdf);
*/											
						$li_row=$li_row+1;
						$lo_hoja->write($li_row, 0, "",$lo_dataright);
						$lo_hoja->write($li_row, 1, "",$lo_dataright);
						$lo_hoja->write($li_row, 2, "",$lo_dataright);
						$lo_hoja->write($li_row, 3, "",$lo_dataright);
						$lo_hoja->write($li_row, 4, "TOTALES PARTIDA ".$ls_partida_aux,$lo_titulo);
						$lo_hoja->write($li_row, 6, $ld_asignado_partida,$lo_dataright_bold);
						$lo_hoja->write($li_row, 7, $ld_modificado_partida,$lo_dataright_bold);
						$lo_hoja->write($li_row, 8, $ld_programado_partida,$lo_dataright_bold);
						$lo_hoja->write($li_row, 9, $ld_compromiso_partida,$lo_dataright_bold);
						$lo_hoja->write($li_row, 10, $ld_causado_partida,$lo_dataright_bold);
						$lo_hoja->write($li_row, 11, $ld_pagado_partida,$lo_dataright_bold);
						$lo_hoja->write($li_row, 12,$ld_programado_acum_partida,$lo_dataright_bold);
						$lo_hoja->write($li_row, 13, $ld_compromiso_acum_partida,$lo_dataright_bold);
						$lo_hoja->write($li_row, 14, $ld_causado_acum_partida,$lo_dataright_bold);
						$lo_hoja->write($li_row, 15, $ld_pagado_acum_partida,$lo_dataright_bold);
						$lo_hoja->write($li_row, 16,$ld_disp_fecha_partida,$lo_dataright_bold);
						$li_row++;
						$li_row++;					
						$ld_asignado_partida=0;
						$ld_modificado_partida=0;
						$ld_programado_partida=0;
						$ld_compromiso_partida=0;
						$ld_causado_partida=0;
						$ld_pagado_partida=0;
						$ld_programado_acum_partida=0;
						$ld_compromiso_acum_partida=0;
						$ld_causado_acum_partida=0;
						$ld_pagado_acum_partida=0;
						$ld_disp_fecha_partida=0;		
						$ls_partida_aux=$ls_partida;
				  }
				 
				  //------------------------------------------------------------------------------
				  $ld_asignado               = number_format($ld_asignado,2,",",".");
				  $ld_modificado             = number_format($ld_modificado,2,",",".");
				  $ld_programado             = number_format($ld_programado,2,",",".");
				  $ld_compromiso             = number_format($ld_compromiso,2,",",".");
				  $ld_causado                = number_format($ld_causado,2,",",".");
				  $ld_pagado                 = number_format($ld_pagado,2,",",".");
				  $ld_programado_acum        = number_format($ld_programado_acum,2,",",".");
				  $ld_compromiso_acum        = number_format($ld_compromiso_acum,2,",",".");
				  $ld_causado_acum           = number_format($ld_causado_acum,2,",",".");
				  $ld_pagado_acum            = number_format($ld_pagado_acum,2,",",".");
				  $ld_disp_fecha             = number_format($ld_disp_fecha,2,",",".");
				
	/*			  
				  uf_print_detalle($ls_partida,$ls_generica,$ls_especifica,$ls_subesp,$ls_denominacion,$ld_asignado,
	                               $ld_modificado,$ld_programado,$ld_compromiso,$ld_causado,$ld_pagado,
							       $ld_programado_acum, $ld_compromiso_acum,$ld_causado_acum, $ld_pagado_acum,
								   $ld_disp_fecha,&$io_pdf); // Imprimimos el detalle
								   
	*/							   
								   
				$lo_hoja->write($li_row, 0, $ls_partida,$lo_dataleft);
				$lo_hoja->write($li_row, 1, " ".$ls_generica,$lo_dataleft);
				$lo_hoja->write($li_row, 2, " ".$ls_especifica,$lo_dataleft);
				$lo_hoja->write($li_row, 3, " ".$ls_subesp,$lo_dataleft);
				
				if($as_spg_int!='')
				{
					$lo_hoja->write($li_row,4," ".$as_spg_int,$lo_datacenter);
					$lo_hoja->write(9,4,"INT",$lo_titulo);
				}
				/*if($ld_asignado == $ld_modificado)
				{
				 $ld_modificado = "";
				}*/
				$lo_hoja->write($li_row, 5, $ls_denominacion,$lo_dataleft);
				$lo_hoja->write($li_row, 6, $ld_asignado,$lo_dataright);
				$lo_hoja->write($li_row, 7, $ld_modificado,$lo_dataright);
				$lo_hoja->write($li_row, 8, $ld_programado,$lo_dataright);
				$lo_hoja->write($li_row, 9, $ld_compromiso,$lo_dataright);
				$lo_hoja->write($li_row, 10, $ld_causado,$lo_dataright);
				$lo_hoja->write($li_row, 11, $ld_pagado,$lo_dataright);
				$lo_hoja->write($li_row, 12, $ld_programado_acum,$lo_dataright);
				$lo_hoja->write($li_row, 13, $ld_compromiso_acum,$lo_dataright);
				$lo_hoja->write($li_row, 14, $ld_causado_acum,$lo_dataright);
				$lo_hoja->write($li_row, 15, $ld_pagado_acum,$lo_dataright);
				$lo_hoja->write($li_row, 16,$ld_disp_fecha,$lo_dataright);				  
				$li_row++;	  							 						   
			}//for
		
		
		///-----------------------totales por partidas-------------------------------------------------
		
		             $ld_asignado_partida       = number_format($ld_asignado_partida,2,",",".");
				     $ld_modificado_partida     = number_format($ld_modificado_partida,2,",",".");
				     $ld_programado_partida     = number_format($ld_programado_partida,2,",",".");
				     $ld_compromiso_partida     = number_format($ld_compromiso_partida,2,",",".");
				     $ld_causado_partida        = number_format($ld_causado_partida,2,",",".");
				     $ld_pagado_partida         = number_format($ld_pagado_partida,2,",",".");
				     $ld_programado_acum_partida  = number_format($ld_programado_acum_partida,2,",",".");
				     $ld_compromiso_acum_partida  = number_format($ld_compromiso_acum_partida,2,",",".");
				     $ld_causado_acum_partida     = number_format($ld_causado_acum_partida,2,",",".");
				     $ld_pagado_acum_partida      = number_format($ld_pagado_acum_partida,2,",",".");
				     $ld_disp_fecha_partida       = number_format($ld_disp_fecha_partida,2,",",".");
					 
					/*
					 uf_print_total_partidas($ls_partida_aux,$ld_asignado_partida,$ld_modificado_partida,
	                                        $ld_programado_partida, $ld_compromiso_partida,
									        $ld_causado_partida,$ld_pagado_partida,
									        $ld_programado_acum_partida,$ld_compromiso_acum_partida,
									        $ld_causado_acum_partida,$ld_pagado_acum_partida,
											$ld_disp_fecha_partida,&$io_pdf);
											
					*/						
					$li_row=$li_row+1;
					
					
					$lo_hoja->write($li_row, 0, "",$lo_titulo);
					$lo_hoja->write($li_row, 1, "",$lo_titulo);
					$lo_hoja->write($li_row, 2, "",$lo_titulo);
					$lo_hoja->write($li_row, 3, "",$lo_titulo);
					$lo_hoja->write($li_row, 4, "TOTALES PARTIDA ".$ls_partida_aux,$lo_titulo);
					$lo_hoja->write($li_row, 6, $ld_asignado_partida,$lo_dataright_bold);
					$lo_hoja->write($li_row, 7, $ld_modificado_partida,$lo_dataright_bold);
					$lo_hoja->write($li_row, 8, $ld_programado_partida,$lo_dataright_bold);
					$lo_hoja->write($li_row, 9, $ld_compromiso_partida,$lo_dataright_bold);
					$lo_hoja->write($li_row, 10, $ld_causado_partida,$lo_dataright_bold);
					$lo_hoja->write($li_row, 11, $ld_pagado_partida,$lo_dataright_bold);
					$lo_hoja->write($li_row, 12,$ld_programado_acum_partida,$lo_dataright_bold);
					$lo_hoja->write($li_row, 13, $ld_compromiso_acum_partida,$lo_dataright_bold);
					$lo_hoja->write($li_row, 14, $ld_causado_acum_partida,$lo_dataright_bold);
					$lo_hoja->write($li_row, 15, $ld_pagado_acum_partida,$lo_dataright_bold);
					$lo_hoja->write($li_row, 16,$ld_disp_fecha_partida,$lo_dataright_bold);
						
											
		//----------------------------------------------------------------------------------------------	
		
		$ld_total_asignado         = number_format($ld_total_asignado,2,",",".");
		$ld_total_modificado       = number_format($ld_total_modificado,2,",",".");
		$ld_total_programado       = number_format($ld_total_programado,2,",",".");
		$ld_total_compromiso       = number_format($ld_total_compromiso,2,",",".");
		$ld_total_causado          = number_format($ld_total_causado,2,",",".");
	    $ld_total_pagado           = number_format($ld_total_pagado,2,",",".");
		$ld_total_programado_acum  = number_format($ld_total_programado_acum,2,",",".");
		$ld_total_compromiso_acum  = number_format($ld_total_compromiso_acum,2,",",".");
		$ld_total_causado_acum     = number_format($ld_total_causado_acum,2,",",".");
		$ld_total_pagado_acum      = number_format($ld_total_pagado_acum,2,",",".");
		$ld_total_disp_fecha       = number_format($ld_total_disp_fecha,2,",",".");
			
			
			/*
		$la_data_tot[1]=array('totales'=>"TOTALES",
							  'asignado'=>$ld_total_asignado,
							  'modificado'=>$ld_total_modificado,
							  'programado'=>$ld_total_programado,
							  'compromiso'=>$ld_total_compromiso,
							  'causado'=>$ld_total_causado,
							  'pagado'=>$ld_total_pagado,
							  'programado_acum'=>$ld_total_programado_acum,
							  'compromiso_acum'=>$ld_total_compromiso_acum,
							  'causado_acum'=>$ld_total_causado_acum,
							  'pagado_acum'=>$ld_total_pagado_acum,
							  'disp_fecha'=>$ld_total_disp_fecha);							              	
		    uf_print_pie_cabecera($la_data_tot,$io_pdf);
			*/
			
				$li_row=$li_row+1;
				$li_row++;
				
				$lo_hoja->write($li_row, 0, "",$lo_titulo);
				$lo_hoja->write($li_row, 1, "",$lo_titulo);
				$lo_hoja->write($li_row, 2, "",$lo_titulo);
				$lo_hoja->write($li_row, 3, "",$lo_titulo);
				$lo_hoja->write($li_row, 4, "TOTALES",$lo_titulo);
				$lo_hoja->write($li_row, 6, $ld_total_asignado,$lo_dataright_bold);
				$lo_hoja->write($li_row, 7, $ld_total_modificado,$lo_dataright_bold);
				$lo_hoja->write($li_row, 8, $ld_total_programado,$lo_dataright_bold);
				$lo_hoja->write($li_row, 9, $ld_total_compromiso,$lo_dataright_bold);
				$lo_hoja->write($li_row, 10, $ld_total_causado,$lo_dataright_bold);
				$lo_hoja->write($li_row, 11, $ld_total_pagado,$lo_dataright_bold);
				$lo_hoja->write($li_row, 12, $ld_total_programado_acum,$lo_dataright_bold);
				$lo_hoja->write($li_row, 13, $ld_total_compromiso_acum,$lo_dataright_bold);
				$lo_hoja->write($li_row, 14, $ld_total_causado_acum,$lo_dataright_bold);
				$lo_hoja->write($li_row, 15, $ld_total_pagado_acum,$lo_dataright_bold);
				$lo_hoja->write($li_row, 16,$ld_total_disp_fecha,$lo_dataright_bold);
				
				unset($la_data);
				unset($la_data_tot);
				$lo_libro->close();
				header("Content-Type: application/x-msexcel; name=\"EJECUCION TRIMESTRAL DE GASTOS Y APLICACIONES FINANCIERAS.xls\"");
				header("Content-Disposition: inline; filename=\"EJECUCION TRIMESTRAL DE GASTOS Y APLICACIONES FINANCIERAS.xls\"");
				$fh=fopen($lo_archivo, "rb");
				fpassthru($fh);
				unlink($lo_archivo);
				print("<script language=JavaScript>");
				print(" close();");
				print("</script>");			
			/*	
		$io_pdf->ezStopPageNumbers(1,1);
		if (isset($d) && $d)
		{
			//echo "al final";
			//die();
			$ls_pdfcode = $io_pdf->ezOutput(1);
			$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
			echo '<html><body>';
			echo trim($ls_pdfcode);
			echo '</body></html>';
		}
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);
		
		*/
		
	}//else
	unset($io_report);
	unset($io_funciones);
?> 