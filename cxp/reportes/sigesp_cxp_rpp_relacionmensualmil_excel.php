<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Recepciones de Documentos
//  ORGANISMO: Ninguno en particular
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_recepciones.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,975,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,535,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,"GOBIERNO BOLIVARIANO DEL ESTADO MIRANDA");
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,570,11,"GOBIERNO BOLIVARIANO DEL ESTADO MIRANDA"); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,"SUPERINTENDENCIA DE ADMINISTRACION TRIBUTARIA DEL ESTADO MIRANDA");
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,560,11,"SUPERINTENDENCIA DE ADMINISTRACION TRIBUTARIA DEL ESTADO MIRANDA"); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,"RELACION MENSUAL");
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,550,11,"RELACION MENSUAL"); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,"IMPUESTO 1 X 1000 - ENTES PUBLICOS");
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,540,11,"IMPUESTO 1 X 1000 - ENTES PUBLICOS"); // Agregar el t�tulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_recepcion($li_totrows,$lo_libro,$lo_hoja,$la_data,$totcmp_con_iva,$totimp,$iva_ret,&$li_fila)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//				   li_totaldoc // acumulado del total
		//				   li_totalcar // acumulado de los cargos
		//				   li_totalded // acumulado de las deducciones
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle de las recepciones de documentos
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 20/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$lo_datadate= &$lo_libro->addformat(array(num_format => 'dd/mm/yyyy'));
		$lo_datadate->set_text_wrap();
		$lo_datadate->set_font("Verdana");
		$lo_datadate->set_align('center');
		$lo_datadate->set_size('8');
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('8');
		$lo_hoja->write($li_fila, 0, 'ORDEN DE PAGO',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 1, 'CHEQUE',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 2, 'TRANSFERENCIA',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 3, 'No. INSTRUMENTO',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 4, 'BANCO',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 5, 'FECHA',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 6, 'No. DEPOSITO',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 7, 'NOMBRE CONTRIBUYENTE',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 8, 'RIF',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 9, 'MONTO BRUTO',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 10, 'MONTO DEL IMPUESTO',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 11, 'MUNICIPIO',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		$lo_hoja->write($li_fila, 12, 'OPERACIONES ANULADAS O REVERSADAS',$lo_libro->addformat(array('bold'=>1,'font'=>'Verdana','align'=>'center','size'=>'9')));
		for($i=1;$i<=$li_totrows;$i++)
		{
			$li_fila++;
			$lo_hoja->write($li_fila, 0, " ".$la_data[$i]['numsop'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'8')));
			$lo_hoja->write($li_fila, 1, " ".$la_data[$i]['numdocpag'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'8')));
			$lo_hoja->write($li_fila, 2, " ".$la_data[$i]['vacio1'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'8')));
			$lo_hoja->write($li_fila, 3, " ".$la_data[$i]['numcom'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'8')));
			$lo_hoja->write($li_fila, 4, " ".$la_data[$i]['nomban'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'8')));
			$lo_hoja->write($li_fila, 5, " ".$la_data[$i]['fecmov'],$lo_datadate);
			$lo_hoja->write($li_fila, 6, " ".$la_data[$i]['vacio2'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'8')));
			$lo_hoja->write($li_fila, 7, " ".$la_data[$i]['nomsujret'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'8')));
			$lo_hoja->write($li_fila, 8, " ".$la_data[$i]['rif'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'8')));
			$lo_hoja->write($li_fila, 9, $la_data[$i]['totcmp_con_iva'],$lo_dataright);
			$lo_hoja->write($li_fila, 10, $la_data[$i]['totimp'],$lo_dataright);
			$lo_hoja->write($li_fila, 11, $la_data[$i]['iva_ret'],$lo_dataright);
			$lo_hoja->write($li_fila, 12, " ".$la_data[$i]['vacio3'],$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'8')));
		}
		$li_fila++;
		$lo_hoja->write($li_fila, 8, " TOTALES....",$lo_libro->addformat(array('bold'=>0,'font'=>'Verdana','align'=>'left','size'=>'8')));
		$lo_hoja->write($li_fila, 9, $totcmp_con_iva,$lo_dataright);
		$lo_hoja->write($li_fila, 10, $totimp,$lo_dataright);
		$lo_hoja->write($li_fila, 11, $iva_ret,$lo_dataright);
	
	}// end function uf_print_detalle
	//--------------------------------------------  Llamada a clases de gneracion de excel  ------------------------------------------
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo =  tempnam("/tmp", "relacion_mensual.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();

	//-----------------------------------------------------------------------------------------------------------------------------------

	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	//Instancio a la clase de conversi�n de numeros a letras.
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ld_fecregdes=$io_fun_cxp->uf_obtenervalor_get("fecregdes","");
	$ld_fecreghas=$io_fun_cxp->uf_obtenervalor_get("fecreghas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_retencionesunoxmil($ld_fecregdes,$ld_fecreghas); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe alg�n error � no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
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
			$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
			$lo_dataright->set_font("Verdana");
			$lo_dataright->set_align('right');
			$lo_dataright->set_size('9');
			$lo_hoja->set_column(0,0,15);
			$lo_hoja->set_column(1,1,20);
			$lo_hoja->set_column(2,2,50);
			$lo_hoja->set_column(3,3,20);
			$lo_hoja->set_column(4,4,30);
			$lo_hoja->set_column(5,5,30);
			$lo_hoja->set_column(6,6,30);
	
			$lo_hoja->write(0, 3, "GOBIERNO BOLIVARIANO DEL ESTADO MIRANDA",$lo_encabezado);
			$lo_hoja->write(1, 3, "SUPERINTENDENCIA DE ADMINISTRACION TRIBUTARIA DEL ESTADO MIRANDA",$lo_encabezado);
			$lo_hoja->write(2, 3, "RELACION MENSUAL",$lo_encabezado);
			$lo_hoja->write(3, 3, "IMPUESTO 1 X 1000 - ENTES PUBLICOS",$lo_encabezado);

			$li_fila=5;
			$li_totrow=$io_report->DS->getRowCount("numcom");
			$totcmp_con_iva= 0;
			$totimp= 0;
			$iva_ret= 0;
			for($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
				$ls_numsop= $io_report->DS->data["numsop"][$li_i];
				$ls_numdocpag= $io_report->DS->data["numdocpag"][$li_i]; 
				$ls_numcom= $io_report->DS->data["numcom"][$li_i];
				$ls_nomban= $io_report->DS->data["nomban"][$li_i];
				$ld_fecmov= $io_report->DS->data["fecmov"][$li_i];
				$ls_nomsujret= $io_report->DS->data["nomsujret"][$li_i];
				$ls_rif= $io_report->DS->data["rif"][$li_i];
				$li_totcmp_con_iva= $io_report->DS->data["totcmp_con_iva"][$li_i];
				$li_totimp= $io_report->DS->data["totimp"][$li_i];
				$li_iva_ret= $io_report->DS->data["iva_ret"][$li_i];
				$ld_fecmov= $io_funciones->uf_convertirfecmostrar($ld_fecmov);

//				$li_totaldoc= $li_totaldoc + $li_montotdoc;
//				$li_totalcar= $li_totalcar + $li_moncardoc;
//				$li_totalded= $li_totalded + $li_mondeddoc;

				$li_totcmp_con_iva= number_format($li_totcmp_con_iva,2,'.','');
				$li_totimp= number_format($li_totimp,2,'.','');
				$li_iva_ret= number_format($li_iva_ret,2,'.','');
				
				$totcmp_con_iva= $totcmp_con_iva + $li_totcmp_con_iva;
				$totimp= $totimp + $li_totimp;
				$iva_ret= $iva_ret + $li_iva_ret;

				$la_data[$li_i]=array('numsop'=>$ls_numsop,'numdocpag'=>$ls_numdocpag,'vacio1'=>"",'numcom'=>$ls_numcom,'nomban'=>$ls_nomban,
									  'fecmov'=>$ld_fecmov,'vacio2'=>"",'nomsujret'=>$ls_nomsujret,'rif'=>$ls_rif,
									  'totcmp_con_iva'=>$li_totcmp_con_iva,'totimp'=>$li_totimp,'iva_ret'=>$li_iva_ret,'vacio3'=>"");
			}
			uf_print_detalle_recepcion($li_i,$lo_libro,$lo_hoja,$la_data,$totcmp_con_iva,$totimp,$iva_ret,&$li_fila);
			if($lb_valido) // Si no ocurrio ning�n error
			{
				unset($io_report);
				$lo_libro->close();
				header("Content-Type: application/x-msexcel; name=\"relacion_mensual.xls\"");
				header("Content-Disposition: inline; filename=\"relacion_mensual.xls\"");
				$fh=fopen($lo_archivo, "rb");
				fpassthru($fh);
				unlink($lo_archivo);
				print("<script language=JavaScript>");
				print(" close();");
				print("</script>");
			}
			else // Si hubo alg�n error
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				print(" close();");
				print("</script>");		
			}
		}
	}

?>
