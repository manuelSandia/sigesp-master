<?php
    session_start();   

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_personal_rac_rec.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/tmp", "listado_personal_rac.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo="Listado de Personal";
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_anio=$io_fun_nomina->uf_obtenervalor_get("anio","");	
	$ls_mes=$io_fun_nomina->uf_obtenervalor_get("mes","");	
	$ls_peri=$io_fun_nomina->uf_obtenervalor_get("codperi","");	
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","");	
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data 
	$lb_valido=uf_insert_seguridad("<b>Listado de Personal RAP DE OBREROS</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadopersonal_personal_rap($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,$ls_anio,$ls_mes,$ls_peri); // Obtenemos el detalle del reporte
	}
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
		$lo_titulo->set_text_wrap();
		$lo_titulo->set_bold();
		$lo_titulo->set_font("Verdana");
		$lo_titulo->set_align('left');
		$lo_titulo->set_size('9');	
		$lo_titulo->set_merge(); # This is the key feature	
		$lo_titulo2= &$lo_libro->addformat();
		$lo_titulo2->set_text_wrap();
		$lo_titulo2->set_bold();
		$lo_titulo2->set_font("Verdana");
		$lo_titulo2->set_align('left');
		$lo_titulo2->set_size('9');	
		$lo_titulo2->set_merge(); # This is the key feature	
		$lo_titulo3= &$lo_libro->addformat();
		$lo_titulo3->set_text_wrap();
		$lo_titulo3->set_bold();
		$lo_titulo3->set_font("Verdana");
		$lo_titulo3->set_align('left');
		$lo_titulo3->set_size('9');	
		$lo_titulo3->set_merge(); # This is the key feature	
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
		$lo_hoja->set_column(1,1,15);
		$lo_hoja->set_column(2,2,70);
		$lo_hoja->set_column(3,3,70);
		$lo_hoja->set_column(4,4,15);
		$lo_hoja->set_column(5,5,10);
		$lo_hoja->set_column(6,6,15);
		$lo_hoja->set_column(7,7,15);
		$lo_hoja->set_column(8,8,15);
		$lo_hoja->set_column(9,9,20);
		
		$li_row=0;
		$ls_codubiadmant = ""; 
		while(!$io_report->rs_data->EOF)
		{
			$ls_minorguniadm=$io_report->rs_data->fields["minorguniadm"];
			$ls_ofiuniadm=$io_report->rs_data->fields["ofiuniadm"];
			$ls_uniuniadm=$io_report->rs_data->fields["uniuniadm"];
			$ls_depuniadm=$io_report->rs_data->fields["depuniadm"];
			$ls_prouniadm=$io_report->rs_data->fields["prouniadm"];
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$ls_codubiadm=$ls_minorguniadm.$ls_ofiuniadm.$ls_uniuniadm.$ls_depuniadm.$ls_prouniadm;
			if ($ls_codubiadm != $ls_codubiadmant)
			{
				$li_row++;
				$li_row++;
				$li_row++;
				$lo_hoja->write($li_row, 0, "UBIC. ADMIN: ".$ls_codubiadm."   ".$ls_desuniadm, $lo_titulo);
				$lo_hoja->write($li_row, 1, "", $lo_titulo2);
				$lo_hoja->write($li_row, 2, "", $lo_titulo3);
				$li_row++;
				$li_row++;
				$li_row++;
				$lo_hoja->write($li_row, 0, "COD.NOM.", $lo_titulo);
				$lo_hoja->write($li_row, 1, "CEDULA.", $lo_titulo);
				$lo_hoja->write($li_row, 2, "APELLIDOS Y NOMBRES", $lo_titulo);
				$lo_hoja->write($li_row, 3, "DENOMINACIÓN DEL CARGO", $lo_titulo);
				$lo_hoja->write($li_row, 4, "CLASE", $lo_titulo);
				$lo_hoja->write($li_row, 5, "GDO", $lo_titulo);
				$lo_hoja->write($li_row, 6, "SALARIO", $lo_titulo);
				$lo_hoja->write($li_row, 7, "COMP", $lo_titulo);
				$lo_hoja->write($li_row, 8, "TOTAL", $lo_titulo);
				$lo_hoja->write($li_row, 9, "FECHA ING.", $lo_titulo);
				$ls_codubiadmant = $ls_codubiadm;
			}
			$ls_codnom=$io_report->rs_data->fields["codunirac"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_cedper=str_pad(trim($ls_cedper),8,"0",0);
			$ls_nomper=$io_report->rs_data->fields["nomper"];
			$ls_denasicar=$io_report->rs_data->fields["denasicar"];
			$ls_claasicar=$io_report->rs_data->fields["claasicar"];
			$ls_grado=$io_report->rs_data->fields["grado"];
			$li_suemin=$io_report->rs_data->fields["suemin"];
			$li_suemin=number_format(trim($li_suemin),2,".","");
			$li_comp=($io_report->rs_data->fields["sueper"]-$io_report->rs_data->fields["suemin"]);
			if($li_comp<0)
			{
				$li_comp=0;
			}
			$li_comp=number_format(trim($li_comp),2,".","");
			$li_sueper=$io_report->rs_data->fields["sueper"];
			$li_sueper=number_format(trim($li_sueper),2,".","");
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecingper"]);
			$li_row++;
			$lo_hoja->write($li_row, 0, " ".$ls_codnom, $lo_datacenter);
			$lo_hoja->write($li_row, 1, " ".$ls_cedper, $lo_datacenter);
			$lo_hoja->write($li_row, 2, " ".$ls_nomper, $lo_dataleft);
			$lo_hoja->write($li_row, 3, " ".$ls_denasicar, $lo_dataleft);
			$lo_hoja->write($li_row, 4, " ".$ls_claasicar, $lo_datacenter);
			$lo_hoja->write($li_row, 5, " ".$ls_grado, $lo_datacenter);
			$lo_hoja->write($li_row, 6, $li_suemin, $lo_dataright);
			$lo_hoja->write($li_row, 7, $li_comp, $lo_dataright);
			$lo_hoja->write($li_row, 8, $li_sueper, $lo_dataright);
			$lo_hoja->write($li_row, 9, " ".$ld_fecingper, $lo_datacenter);
			$io_report->rs_data->MoveNext();
		}
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"listado_personal_rap.xls\"");
		header("Content-Disposition: inline; filename=\"listado_personal_rap.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 