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
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("sigesp_spg_funciones_reportes.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("sigesp_spg_class_reportes_instructivos.php");
	$lo_archivo =  tempnam("/tmp", "Reporte_Estado_de_Resultado_Instructivo_08.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	$io_funciones=new class_funciones();	
	$io_function_report=new sigesp_spg_funciones_reportes();	
	$io_fecha = new class_fecha();
	$io_report = new sigesp_spg_class_reportes_instructivos();
	
	function uf_formato_salida($as_cuenta,$ai_nivel,$as_formato,$as_separador)
	{
	    ///////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_formato_salida
        //         Access: public
        //        Returns: vacio     
        //    Description: Este método da formato según lo que estable los instrucivos de la ONAPRE para la cuentas
        //////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_arreglo = explode('-',$as_formato);
        $ls_cuenta = "";
		$j=0;
		$ls_nvoformato = "";
		do
		{
		 if($j<$ai_nivel-1)
		 {
		  $ls_nvoformato .= $la_arreglo[$j].'-';
		 }
		 else
		 {
		  $ls_nvoformato .= $la_arreglo[$j];
		 }
		 $j++;
		}while($j<$ai_nivel);
		
		$la_arreglo_nvo = explode('-',trim($ls_nvoformato));
		$li_total = count($la_arreglo_nvo);
		$ini = 0;
		foreach($la_arreglo_nvo as $key => $valor)
		{
		  if($key <> 0)
		  {
		   $ini += strlen(trim($la_arreglo_nvo[$key-1]));
		  }
		  $len = strlen(trim($valor));
		  if($key<$li_total-1)
		  {
		   $ls_cuenta .= substr(trim($as_cuenta),$ini,$len).$as_separador;
		  }
		  else
		  {
		   $ls_cuenta .= substr(trim($as_cuenta),$ini,$len);
		  }
		}
		
		return $ls_cuenta;
	
	}
	//-----------------------------------------------------------------------------------------------------------------------------
		
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		
		$ls_trimestre=$_GET["trimestre"];
		$li_mesdes=substr($ls_trimestre,0,2);
		$ldt_fecdes=$li_ano."-".$li_mesdes."-01";
		$li_meshas=substr($ls_trimestre,2,2);
		$ldt_ult_dia=$io_fecha->uf_last_day($li_meshas,$li_ano);
		$fechas=$ldt_ult_dia;
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_mesdes=$io_fecha->uf_load_nombre_mes($li_mesdes);
		$ls_meshas=$io_fecha->uf_load_nombre_mes($li_meshas);
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_nombre_empresa="<b>".$ls_nombre."</b>";
		$ls_titulo=" ESTADO DE RESULTADO";    
		$ls_bs="(En Bolívares)"  ; 
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
	 $lb_valido=$io_report->uf_spg_reportes_estado_de_resultado_inst08($ldt_fecdes,$ldt_fechas,$ls_mesdes,$ls_meshas);
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
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
		$lo_datacenter= &$lo_libro->addformat();
		$lo_datacenter->set_font("Verdana");
		$lo_datacenter->set_align('center');
		$lo_datacenter->set_size('9');
		$lo_dataleft= &$lo_libro->addformat();
		$lo_dataleft->set_text_wrap();
		$lo_dataleft->set_font("Verdana");
		$lo_dataleft->set_align('left');
		$lo_dataleft->set_size('9');
		$lo_dataleft_bold= &$lo_libro->addformat();
		$lo_dataleft_bold->set_text_wrap();
		$lo_dataleft_bold->set_font("Verdana");
		$lo_dataleft_bold->set_align('left');
		$lo_dataleft_bold->set_size('9');
		$lo_dataleft_bold->set_bold();
		$lo_dataright= &$lo_libro->addformat(array(num_format =>'#,##0.00;[Red](#,##0.00)'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		$lo_dataright_bold= &$lo_libro->addformat(array(num_format =>'#,##0.00;[Red](#,##0.00)'));
		$lo_dataright_bold->set_font("Verdana");
		$lo_dataright_bold->set_align('right');
		$lo_dataright_bold->set_size('9');
		$lo_dataright_bold->set_bold();
		$lo_hoja->set_column(0,0,15);
		$lo_hoja->set_column(1,1,20);
		$lo_hoja->set_column(2,2,30);
		$lo_hoja->set_column(3,3,20);
		$lo_hoja->set_column(4,4,13);
		$lo_hoja->set_column(5,7,30);
		$ls_etiqueta = "";
		$ls_periodo = "";
		if($li_mesdes==1)
		{
		  $ls_etiqueta="I";
		  $ls_periodo = "ENERO - MARZO";
		}
		if($li_mesdes==4)
		{
		  $ls_etiqueta="II";
		  $ls_periodo = "ABRIL - JUNIO";
		}
		if($li_mesdes==7)
		{
		  $ls_etiqueta="III";
		  $ls_periodo = "JULIO - SEPTIEMBRE";
		}
		if($li_mesdes==10)
		{
		  $ls_etiqueta="IV";
		  $ls_periodo = "OCTUBRE - DICIEMBRE";
		}

		$li_row=4;
		$lo_hoja->write(0, 0,"CÓDIGO PRESUPUESTARIO DEL ENTE",$lo_dataleft_bold);
		$lo_hoja->write(0, 1, $_SESSION['la_empresa']['codasiona'],$lo_dataleft);
		$lo_hoja->write(1, 0,"DENOMINACION DEL ENTE",$lo_dataleft_bold);
		$lo_hoja->write(1, 1,$_SESSION["la_empresa"]["nombre"],$lo_dataleft);
		$lo_hoja->write(2, 0,"ORGANO DE ADSCRIPCION",$lo_dataleft_bold);
		$lo_hoja->write(2, 1,$_SESSION["la_empresa"]["nomorgads"],$lo_dataleft);
		$lo_hoja->write(3, 0,"PERIODO PRESUPUESTARIO",$lo_dataleft_bold);
		$lo_hoja->write(3, 1,$ls_periodo." ".$li_ano,$lo_dataleft);
	
		$li_row=5;
		$lo_hoja->write($li_row, 3,$ls_nombre,$lo_titulo);
		$li_row++;
		$lo_hoja->write($li_row, 3,$ls_titulo,$lo_titulo);
		$li_row++;
		$li_row++;
		$lo_hoja->write($li_row, 4, "TRIMESTRE Nº ".$ls_etiqueta,$lo_titulo);
		$lo_hoja->write($li_row, 5, "",$lo_titulo);
		$lo_hoja->write($li_row, 6, "VARIACION EJECUTADO PROGRAMADO EN EL TRIMESTRE Nº ".$ls_etiqueta,$lo_titulo);
		$lo_hoja->write($li_row, 7, "",$lo_titulo);
		$lo_hoja->write($li_row, 8, "TOTAL ACUMULADO AL TRIMESTRE Nº".$ls_etiqueta,$lo_titulo);
		$lo_hoja->write($li_row, 9, "",$lo_titulo);
		$li_row++;
		
		$lo_hoja->write($li_row, 0, "CUENTA",$lo_titulo);
		$lo_hoja->write($li_row, 1, "DENOMINACION",$lo_titulo);
		$lo_hoja->write($li_row, 2, "PRESUPUESTO APROBADO",$lo_titulo);
		$lo_hoja->write($li_row, 3, "PRESUPUESTO MODIFICADO",$lo_titulo);
		$lo_hoja->write($li_row, 4, "PROGRAMADO",$lo_titulo);
		$lo_hoja->write($li_row, 5, "EJECUTADO",$lo_titulo);
		$lo_hoja->write($li_row, 6, "ABSOLUTA",$lo_titulo);
		$lo_hoja->write($li_row, 7, "PORCENTAJE (%)",$lo_titulo);
		$lo_hoja->write($li_row, 8, "PROGRAMADO",$lo_titulo);
		$lo_hoja->write($li_row, 9, "EJECUTADO",$lo_titulo);
		$li_row++;
		$li_total=$io_report->dts_reporte->getRowCount("cuenta");
		$li_nivel = count(explode('-',$_SESSION["la_empresa"]["formpre"]));
		$ls_mascara = str_replace("-","",$_SESSION["la_empresa"]["formpre"]);
		$li_len_mas = strlen($ls_mascara);
		$ls_ceros = str_pad("",$li_len_mas - 9,"0"); 
		
        $la_cuentas_saldos[]  = '303000000'.$ls_ceros;
        $la_cuentas_saldos[]  = '408070000'.$ls_ceros;
        $la_cuentas_saldos[]  = '304000000'.$ls_ceros;
        $la_cuentas_saldos[]  = '305000000'.$ls_ceros;
        $la_cuentas_saldos[]  = '407000000'.$ls_ceros;
        $la_cuentas_saldos[]  = '408000000'.$ls_ceros;
		
		$ld_asignado_rs=0;
		$ld_asignado_modificado_rs=0;
		$ld_programado_rs=0;
		$ld_ejecutado_rs=0;
		$ld_variacion_absoluta_rs=0;
		$ld_variacion_porcentual_rs=0;
		$ld_programado_acumulado_rs=0;
		$ld_ejecutado_acumulado_rs=0;
		
		$ld_asignado_rs_i=0;
		$ld_asignado_modificado_rs_i=0;
		$ld_programado_rs_i=0;
		$ld_ejecutado_rs_i=0;
		$ld_variacion_absoluta_rs_i=0;
		$ld_variacion_porcentual_rs_i=0;
		$ld_programado_acumulado_rs_i=0;
		$ld_ejecutado_acumulado_rs_i=0;
		
		for($z=1;$z<=$li_total;$z++)
		{
			$ls_cuenta=trim($io_report->dts_reporte->data["cuenta"][$z]);
			$ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
			$ls_denominacion  = str_replace('<b>','',$ls_denominacion);
			$ls_denominacion  = str_replace('</b>','',$ls_denominacion);
			$ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
			$ld_asignado_modificado=$io_report->dts_reporte->data["asignado_modificado"][$z];
			$ld_programado=$io_report->dts_reporte->data["programado"][$z];
			$ld_ejecutado=$io_report->dts_reporte->data["ejecutado"][$z];
			$ld_variacion_absoluta=$io_report->dts_reporte->data["variacion_absoluta"][$z];
			$ld_variacion_porcentual=$io_report->dts_reporte->data["variacion_porcentual"][$z];
			$ld_programado_acumulado=$io_report->dts_reporte->data["programado_acumulado"][$z];
			$ld_ejecutado_acumulado=$io_report->dts_reporte->data["ejecutado_acumulado"][$z];
			$ls_tipo=$io_report->dts_reporte->data["tipo"][$z];
			$ls_cuenta_nva = "";
			if(!empty($ls_cuenta))
			{
			 $ls_cuenta_nva = uf_formato_salida($ls_cuenta,$li_nivel,$_SESSION["la_empresa"]["formpre"],".");
			}
			
			if(is_int(array_search($ls_cuenta,$la_cuentas_saldos)))
			{
				switch(substr($ls_cuenta,0,1))
				{
				 case '3':
						 $ld_asignado_rs += $ld_asignado;
						 $ld_asignado_modificado_rs += $ld_asignado_modificado;
						 $ld_programado_rs += $ld_programado;
						 $ld_ejecutado_rs += $ld_ejecutado;
						 $ld_programado_acumulado_rs += $ld_programado_acumulado;
						 $ld_ejecutado_acumulado_rs +=$ld_ejecutado_acumulado;
				 break;
				 
				 case '4':
				         $ld_asignado_rs -= $ld_asignado;
						 $ld_asignado_modificado_rs -= $ld_asignado_modificado;
						 $ld_programado_rs -= $ld_programado;
						 $ld_ejecutado_rs -= $ld_ejecutado;
						 $ld_programado_acumulado_rs -= $ld_programado_acumulado;
						 $ld_ejecutado_acumulado_rs -= $ld_ejecutado_acumulado;
				 break;
				}
			}
			
		    if(($ls_tipo == 'IN')||($ls_tipo == 'EG'))
			{
		 	 $ld_asignado=" ";
			 $ld_asignado_modificado=" ";
			 $ld_programado=" ";
			 $ld_ejecutado=" ";
			 $ld_variacion_absoluta=" ";
			 $ld_variacion_porcentual=" ";
			 $ld_programado_acumulado=" ";
			 $ld_ejecutado_acumulado=" ";
			}
			
			if($ld_asignado == $ld_asignado_modificado)
			{
			 $ld_asignado_modificado = '';
			}
		    if(($ls_tipo == 'IN')||($ls_tipo == 'EG')||($ls_tipo == 'VN'))
			{
			 $lo_hoja->write($li_row, 0, $ls_cuenta_nva,$lo_dataleft_bold);
			 $lo_hoja->write($li_row, 1, $ls_denominacion,$lo_dataleft_bold);
			 $lo_hoja->write($li_row, 2,$ld_asignado,$lo_dataright_bold);
			 $lo_hoja->write($li_row, 3, $ld_asignado_modificado,$lo_dataright_bold);
			 $lo_hoja->write($li_row, 4, $ld_programado,$lo_dataright_bold);
			 $lo_hoja->write($li_row, 5, $ld_ejecutado,$lo_dataright_bold);
			 $lo_hoja->write($li_row, 6, $ld_variacion_absoluta,$lo_dataright_bold);
			 $lo_hoja->write($li_row, 7, $ld_variacion_porcentual,$lo_dataright_bold);
			 $lo_hoja->write($li_row, 8, $ld_programado_acumulado,$lo_dataright_bold);
			 $lo_hoja->write($li_row, 9, $ld_ejecutado_acumulado,$lo_dataright_bold);
			}
			else
			{
			 $lo_hoja->write($li_row, 0, $ls_cuenta_nva,$lo_dataleft);
			 $lo_hoja->write($li_row, 1, $ls_denominacion,$lo_dataleft);
			 $lo_hoja->write($li_row, 2,$ld_asignado,$lo_dataright);
			 $lo_hoja->write($li_row, 3, $ld_asignado_modificado,$lo_dataright);
			 $lo_hoja->write($li_row, 4, $ld_programado,$lo_dataright);
			 $lo_hoja->write($li_row, 5, $ld_ejecutado,$lo_dataright);
			 $lo_hoja->write($li_row, 6, $ld_variacion_absoluta,$lo_dataright);
			 $lo_hoja->write($li_row, 7, $ld_variacion_porcentual,$lo_dataright);
			 $lo_hoja->write($li_row, 8, $ld_programado_acumulado,$lo_dataright);
			 $lo_hoja->write($li_row, 9, $ld_ejecutado_acumulado,$lo_dataright);
			}
			$li_row++;
			if($z == $li_total - 1)
			{
			  if(($ld_programado_rs > 0)&&($ld_ejecutado_rs > 0))
			  {
			   $ld_variacion_absoluta_rs = abs($ld_programado_rs - $ld_ejecutado_rs);
			  }		
			  if($ld_programado_rs > 0)
			  {
			   $ld_variacion_porcentual_rs = ($ld_ejecutado_rs/$ld_programado_rs)*100;
			  }
			  
			  $ld_asignado_rs_i=$ld_asignado_rs;
			  $ld_asignado_modificado_rs_i=$ld_asignado_modificado_rs;
			  $ld_programado_rs_i=$ld_programado_rs;
			  $ld_ejecutado_rs_i=$ld_ejecutado_rs;
			  $ld_programado_acumulado_rs_i=$ld_programado_acumulado_rs;
			  $ld_ejecutado_acumulado_rs_i=$ld_ejecutado_acumulado_rs;
			  
			  $lo_hoja->write($li_row, 0, " ",$lo_dataleft);
			  $lo_hoja->write($li_row, 1, "Resultado antes del Impuesto Sobre la Renta",$lo_dataleft_bold);
			  $lo_hoja->write($li_row, 2, $ld_asignado_rs,$lo_dataright_bold);
			  $lo_hoja->write($li_row, 3, $ld_asignado_modificado_rs,$lo_dataright_bold);
			  $lo_hoja->write($li_row, 4, $ld_programado_rs,$lo_dataright_bold);
			  $lo_hoja->write($li_row, 5, $ld_ejecutado_rs,$lo_dataright_bold);
			  $lo_hoja->write($li_row, 6, $ld_variacion_absoluta_rs,$lo_dataright_bold);
			  $lo_hoja->write($li_row, 7, $ld_variacion_porcentual_rs,$lo_dataright_bold);
			  $lo_hoja->write($li_row, 8, $ld_programado_acumulado_rs,$lo_dataright_bold);
			  $lo_hoja->write($li_row, 9, $ld_ejecutado_acumulado_rs,$lo_dataright_bold);
			  $li_row++;
			  if(trim($ls_cuenta) == '408060700'.$ls_ceros)
			  {
				  $ld_asignado_rs_i -= $ld_asignado;
				  $ld_asignado_modificado_rs_i -= $ld_asignado_modificado;
				  $ld_programado_rs_i -= $ld_programado;
				  $ld_ejecutado_rs_i -= $ld_ejecutado;
				  $ld_programado_acumulado_rs_i -= $ld_programado_acumulado;
				  $ld_ejecutado_acumulado_rs_i -= $ld_ejecutado_acumulado;
			  }
		    }   
							   
		}//for
		if(($ld_programado_rs_i > 0)&&($ld_ejecutado_rs_i > 0))
	    {
	     $ld_variacion_absoluta_rs_i = abs($ld_programado_rs_i - $ld_ejecutado_rs_i);
	    }		
	    if($ld_programado_rs_i > 0)
	    {
	     $ld_variacion_porcentual_rs_i = ($ld_ejecutado_rs_i/$ld_programado_rs_i)*100;
	    }
		$lo_hoja->write($li_row, 0, " ",$lo_dataleft);
		$lo_hoja->write($li_row, 1, "Resultado del Ejercicio",$lo_dataleft_bold);
		$lo_hoja->write($li_row, 2, $ld_asignado_rs_i,$lo_dataright_bold);
		$lo_hoja->write($li_row, 3, $ld_asignado_modificado_rs_i,$lo_dataright_bold);
		$lo_hoja->write($li_row, 4, $ld_programado_rs_i,$lo_dataright_bold);
		$lo_hoja->write($li_row, 5, $ld_ejecutado_rs_i,$lo_dataright_bold);
		$lo_hoja->write($li_row, 6, $ld_variacion_absoluta_rs_i,$lo_dataright_bold);
		$lo_hoja->write($li_row, 7, $ld_variacion_porcentual_rs_i,$lo_dataright_bold);
		$lo_hoja->write($li_row, 8, $ld_programado_acumulado_rs_i,$lo_dataright_bold);
		$lo_hoja->write($li_row, 9, $ld_ejecutado_acumulado_rs_i,$lo_dataright_bold);
	}//else
	
	$lo_libro->close();
	header("Content-Type: application/x-msexcel; name=\"Reporte_Estado_de_Resultado_Instructivo_08.xls\"");
	header("Content-Disposition: inline; filename=\"Reporte_Estado_de_Resultado_Instructivo_08.xls\"");
	$fh=fopen($lo_archivo, "rb");
	fpassthru($fh);
	unlink($lo_archivo);
	print("<script language=JavaScript>");
	print(" close();");
	print("</script>");			
	unset($io_report);
	unset($io_funciones);

	unset($io_report);
	unset($io_funciones);
?> 