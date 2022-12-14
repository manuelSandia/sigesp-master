<?php
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: Retencion Municipales
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
		//	    Arguments: as_titulo // T?tulo del reporte
		//    Description: funci?n que guarda la seguridad de quien gener? el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci?n: 15/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Gener? el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_libro_islr_timbrefiscal.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T?tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci?n que imprime los encabezados por p?gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci?n: 04/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,12,$as_titulo); // Agregar el t?tulo
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_agenteret,$as_rifagenteret,$as_diragenteret,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_agenteret // agente de Retenci?n
		//	    		   as_rifagenteret // Rif del Agente de Retenci?n
		//       		   as_diragenteret // Direcci?n del agente de retenci?n
		//	    		   as_periodo // Periodo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci?n que imprime los encabezados por p?gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci?n: 17/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>NOMBRE DE LA INSTTUCION:</b>'."  ".$as_agenteret),
					   array('name'=>'<b>RIF:</b>'."  ".$as_rifagenteret),
					   array('name'=>'<b>DIRECCION:</b>'."  ".$as_diragenteret),
					   array('name'=>'<b>PERIODO:</b>'."  ".$as_periodo),
					   array('name'=>'<b>N? PLANILLA (BANCO):</b>'));
		
		 
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama?o de Letras						 
						 'showLines'=>1, // Mostrar L?neas
						 'shaded'=>0, // Sombra entre l?neas						 
						 'xPos'=>405, // Orientaci?n de la tabla
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Orientaci?n de la tabla
				      	 'cols'=>array('name'=>array('justification'=>'lef','width'=>740))); // Justificaci?n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		
		unset($la_data);
		unset($la_columnas);
		unset($la_config);							 
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------			
			
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabeceradetalle
		//		   Access: private 
		//	    Arguments: la_data // Arreglo de datos a imprimir		
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci?n que imprime los encabezados por p?gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci?n: 14/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetY(400);
		$la_data1[1]=array('fecha'=>'<b>Fecha Operaci?n</b>',
						  'nombre'=>'<b>Nombre Contribuyente</b>',
						  'rif'=>'<b>CI / RIF</b>',
						  'monto'=>'<b>Monto Operaci?n</b>',
  						  'monimp'=>'<b>     Monto Impuesto         1 x 1000</b>',		
						  'municipio'=>'<b>Municipio</b>',
						  'comp'=>'<b>N? Comprobante</b>',
						  'obs'=>'<b>Observaciones</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama?o de Letras
						 'titleFontSize' => 9,  // Tama?o de Letras de los t?tulos
						 'showLines'=>1, // Mostrar L?neas
						 'shaded'=>2, // Sombra entre l?neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho M?nimo de la tabla
						 'xPos'=>405, // Orientaci?n de la tabla
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>65), // Justificacion y ancho de la columna
									   'nombre'=>array('justification'=>'center','width'=>135),
						 			   'rif'=>array('justification'=>'center','width'=>70), // Justificacion y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>95), // Justificacion y ancho de la columna
									   'monimp'=>array('justification'=>'center','width'=>95), // Justificacion y ancho de la columna
						 			   'municipio'=>array('justification'=>'center','width'=>100),
						 			   'comp'=>array('justification'=>'center','width'=>80),
   						 			   'obs'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data1,'','',$la_config);
		unset($la_data1);
		unset($la_config);
		
		
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama?o de Letras
						 'titleFontSize' => 9,  // Tama?o de Letras de los t?tulos
						 'showLines'=>1, // Mostrar L?neas
						 'shaded'=>0, // Sombra entre l?neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho M?nimo de la tabla
						 'xPos'=>405, // Orientaci?n de la tabla
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>65), // Justificacion y ancho de la columna
									   'nombre'=>array('justification'=>'left','width'=>135),
						 			   'rif'=>array('justification'=>'center','width'=>70), // Justificacion y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>95), // Justificacion y ancho de la columna
									   'monimp'=>array('justification'=>'right','width'=>95), // Justificacion y ancho de la columna
						 			   'municipio'=>array('justification'=>'center','width'=>100),
						 			   'comp'=>array('justification'=>'center','width'=>80),
   						 			   'obs'=>array('justification'=>'center','width'=>100))); 
		$io_pdf->ezTable($la_data,'','',$la_config);
		unset($la_data);
		unset($la_config);
		
	}// end function uf_print_detalle
	
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total($ai_totbasimp,$ai_totmonimp,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_total
		//		   Access: private 
		//	    Arguments: 
		//	    		   ai_totbasimp // Total de la base imponible
		//	    		   ai_totmonimp // Total monto imponible		
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci?n que imprime los encabezados por p?gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci?n: 14/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data1[1]=array('total'=>'<b>TOTAL</b>',
						  'total1'=>'<b>'.$ai_totbasimp.'</b>',
						  'total2'=>'<b>'.$ai_totmonimp.'</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama?o de Letras
						 'titleFontSize' => 9,  // Tama?o de Letras de los t?tulos
						 'showLines'=>1, // Mostrar L?neas
						 'shaded'=>0, // Sombra entre l?neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>740, // Ancho de la tabla
						 'maxWidth'=>740, // Ancho M?nimo de la tabla
						 'xPos'=>265, // Orientaci?n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>270), // Justificacion y ancho de la columna
									   'total1'=>array('justification'=>'right','width'=>95),
						 			   'total2'=>array('justification'=>'right','width'=>95))); 
		$io_pdf->ezTable($la_data1,'','',$la_config);
		unset($la_data1);
		unset($la_config);
		
	}// end function uf_print_total
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_firmas
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: funci?n que imprime el detalle por recepci?n
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci?n: 05/07/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[0]=array('firma1'=>'','firma2'=>'');
		$la_data[1]=array('firma1'=>'','firma2'=>'');
		$la_data[2]=array('firma1'=>'','firma2'=>'');
		$la_data[3]=array('firma1'=>'','firma2'=>'');
		$la_data[4]=array('firma1'=>'_________________________________','firma2'=>'_________________________________');
		$la_data[5]=array('firma1'=>'TESORERO / AGENTE DE RETENCION','firma2'=>'JEFE UNIDAD DE TRIBUTOS INTERNOS');
		$la_data[6]=array('firma1'=>'','firma2'=>'');
		$la_columna=array('firma1'=>'','firma2'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama?o de Letras
						 'showLines'=>0, // Mostrar L?neas
						 'shaded'=>0, // Sombra entre l?neas
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho M?ximo de la tabla
						 'xOrientation'=>'center', // Orientaci?n de la tabla
				 		 'cols'=>array('firma1'=>array('justification'=>'center','width'=>250), // Justificaci?n y ancho de la columna
						 			   'firma2'=>array('justification'=>'center','width'=>250))); // Justificaci?n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
	}// end function uf_print_firmas
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/tmp", "timbre_fiscal.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//----------------------------------------------------  Par?metros del encabezado  -----------------------------------------------
	   $ls_titulo="DECLARACION DE TIMBRE FISCAL 1 X 1000";
	//--------------------------------------------------  Par?metros para Filtar el Reporte  -----------------------------------------
	$ls_mes=$io_fun_cxp->uf_obtenervalor_get("mes","");
	$ls_anio=$io_fun_cxp->uf_obtenervalor_get("anio","");
	$ls_agenteret=$_SESSION["la_empresa"]["nombre"];
	$ls_rifagenteret=$_SESSION["la_empresa"]["rifemp"];
	$ls_diragenteret=$_SESSION["la_empresa"]["direccion"];
	
	$mes="";
	switch ($ls_mes)
	{
		case '01':
			$mes='ENERO';
		break;
		case '02':
			$mes='FEBRERO';
		break;
		case '03':
			$mes='MARZO';
		break;
		case '04':
			$mes='ABRIL';
		break;
		case '05':
			$mes='MAYO';
		break;
		case '06':
			$mes='JUNIO';
		break;
		case '07':
			$mes='JULIO';
		break;
		case '08':
			$mes='AGOSTO';
		break;
		case '09':
			$mes='SEPTIEMBRE';
		break;
		case '10':
			$mes='OCTUBRE';
		break;
		case '11':
			$mes='NOVIEMBRE';
		break;
		case '12':
			$mes='DICIEMBRE';
		break;
	
	}
	$ls_periodo= $mes.' - '.$ls_anio;	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_select_contribuyentes_libro_timbrefiscal($ls_mes,$ls_anio,$rs_data);
		if(!$lb_valido)
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else
		{
		//-------formato para el reporte----------------------------------------------------------
		$lo_encabezado= &$lo_libro->addformat();
		$lo_encabezado->set_bold();
		$lo_encabezado->set_font("Verdana");
		$lo_encabezado->set_align('center');
		$lo_encabezado->set_size('11');
		$lo_titulo= &$lo_libro->addformat();
		$lo_titulo->set_text_wrap();
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
		
		$lo_dataright2= &$lo_libro->addformat(array(num_format => '#,##'));
		$lo_dataright2->set_font("Verdana");
		$lo_dataright2->set_align('right');
		$lo_dataright2->set_size('9');	

		
		$lo_dataleftcombinado1 =& $lo_libro->addformat();
		$lo_dataleftcombinado1->set_size('9');		
		$lo_dataleftcombinado1->set_font("Verdana");
		$lo_dataleftcombinado1->set_align('left');
		$lo_dataleftcombinado1->set_merge(); # This is the key feature

		$lo_dataleftcombinado2 =& $lo_libro->addformat();
		$lo_dataleftcombinado2->set_size('9');		
		$lo_dataleftcombinado2->set_font("Verdana");
		$lo_dataleftcombinado2->set_align('left');
		$lo_dataleftcombinado2->set_merge(); # This is the key feature

		$lo_hoja->set_column(0,0,50);
		$lo_hoja->set_column(1,2,15);	
		$lo_hoja->set_column(3,3,40);
		$lo_hoja->set_column(4,4,25);
		$lo_hoja->set_column(5,5,15);
		$lo_hoja->set_column(6,6,20);	
		$lo_hoja->set_column(7,7,20);	
		$lo_hoja->set_column(8,8,15);	
		$lo_hoja->set_column(9,9,15);
		$lo_hoja->set_column(10,10,15);	
		$lo_hoja->set_column(12,12,15);	
		$lo_hoja->set_column(13,13,15);	
		$lo_hoja->set_column(14,14,15);	
		$lo_hoja->set_column(15,15,15);
		$lo_hoja->set_column(16,16,15);	
		$lo_hoja->set_column(17,17,10);	
		$lo_hoja->set_column(18,18,15);	
		$lo_hoja->set_column(19,19,15);		
		$lo_hoja->write(0,3,$ls_titulo,$lo_encabezado);
		$lo_hoja->write(4,0, "NOMBRE DE LA INSTITUCION: ".$ls_agenteret,$lo_dataleftcombinado1);	
		$lo_hoja->write_blank(4,1,                 $lo_dataleftcombinado2);	
		$lo_hoja->write_blank(4,2,                 $lo_dataleftcombinado2);	
		
		$lo_hoja->write(5,0, "RIF ".$ls_rifagenteret,$lo_dataleftcombinado1);
		$lo_hoja->write_blank(5,1,                 $lo_dataleftcombinado2);
		$lo_hoja->write_blank(5,2,                 $lo_dataleftcombinado2);
		
		$lo_hoja->write(6,0, "DIRECCION: ".$ls_diragenteret,$lo_dataleftcombinado1);	
		$lo_hoja->write_blank(6,1,                 $lo_dataleftcombinado2);	
		$lo_hoja->write_blank(6,2,                 $lo_dataleftcombinado2);	
		
		$lo_hoja->write(7,0, "PERIODO: ".$ls_periodo,$lo_dataleftcombinado1);	
		$lo_hoja->write_blank(7,1, $lo_dataleftcombinado2);	
		
		$lo_hoja->write(8,0, "N? PLANILLA (BANCO): ",$lo_dataleftcombinado1);	
		$lo_hoja->write_blank(8,1, $lo_dataleftcombinado2);	
		
		$lo_hoja->write(11,0, "Fecha Operaci?n ",$lo_dataleft);	
		$lo_hoja->write(11,1, "Nombre Contribuyente ",$lo_dataleft);	
		$lo_hoja->write(11,2, "CI / RIF ",$lo_dataleft);	
		$lo_hoja->write(11,3, "Monto Operaci?n ",$lo_dataleft);	
		$lo_hoja->write(11,4, "Monto Impuesto 1 x 1000 ",$lo_dataleft);	
		$lo_hoja->write(11,5, "Municipio ",$lo_dataleft);	
		$lo_hoja->write(11,6, "N? Comprobante ",$lo_dataleft);	
		$lo_hoja->write(11,7, "Solicitud ",$lo_dataleft);	
		//------------------------------------------------------------------------------------------------------
			$lb_valido=true;
			$li_totalbaseimp=0;
			$li_totalmontoimp=0;
			$li_i=0;
			$li_row=11;
			while (!$rs_data->EOF)
			{
				$ls_numcon=$rs_data->fields["numcom"];
				$ls_fecrep=$io_funciones->uf_convertirfecmostrar($rs_data->fields["fecfac"]);
				$ls_nomsujret=$rs_data->fields["nomsujret"];	
				$ls_rif=$rs_data->fields["rif"];	
				$li_baseimp=$rs_data->fields["basimp"];
				$li_totimp=$rs_data->fields["iva_ret"];
				$ls_denmun='LIBERTADOR';
				$li_totalbaseimp=$li_totalbaseimp + $li_baseimp ;	
				$li_totalmontoimp=$li_totalmontoimp + $li_totimp;					
				$li_row++;
				$lo_hoja->write($li_row, 0, $ls_fecrep, $lo_datacenter);
				$lo_hoja->write($li_row, 1, $ls_nomsujret, $lo_dataleft);
				$lo_hoja->write($li_row, 2, $ls_rif, $lo_dataleft);
				$lo_hoja->write($li_row, 3, $li_baseimp, $lo_dataright);
				$lo_hoja->write($li_row, 4, $li_totimp, $lo_dataright);
				$lo_hoja->write($li_row, 5, $ls_denmun, $lo_dataleft);
				$lo_hoja->write($li_row, 6, $ls_numcon, $lo_datacenter);
				$lo_hoja->write($li_row, 7, '', $lo_datacenter);
				$rs_data->MoveNext();	

			}
			$lo_hoja->write($li_row+1, 2, "TOTAL", $lo_dataleft);
			$lo_hoja->write($li_row+1, 3, $li_totalbaseimp, $lo_dataright);
			$lo_hoja->write($li_row+1, 4, $li_totalmontoimp, $lo_dataright);
			
			if($lb_valido) // Si no ocurrio ning?n error
			{
				
				$lo_libro->close();
				header("Content-Type: application/x-msexcel; name=\"timbre_fiscal.xls\"");
				header("Content-Disposition: inline; filename=\"timbre_fiscal.xls\"");
				$fh=fopen($lo_archivo, "rb");
				fpassthru($fh);
				unlink($lo_archivo);		
				print("<script language=JavaScript>");
				print(" close();");
				print("</script>");
				unset($la_data);
			}
			else  // Si hubo alg?n error
			{
				print("<script language=JavaScript>");
				print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
				print(" close();");
				print("</script>");		
			}
			unset($io_pdf);
		}
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_cxp);
?> 