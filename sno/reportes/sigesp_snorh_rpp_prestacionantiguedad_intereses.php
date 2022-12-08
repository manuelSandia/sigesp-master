<?php
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

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 03/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_prestacionantiguedad_intereses.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_anocurper,$as_desmesperdes,$as_desmesperhas,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_anocurper // A�o en curso
		//	    		   as_desmesper // Mes en curso
		//	    		   io_pdf // Instancia de objetso pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 03/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if(trim($as_desmesperdes)==trim($as_desmesperhas))
		{
			$ls_mes=" Mes: ".$as_desmesperdes;
		}
		else
		{
			$ls_mes=" Meses: ".$as_desmesperdes." - ".$as_desmesperhas;
		}
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el t�tulo
		$ls_periodo="A�o: ".$as_anocurper." ".$ls_mes;
		$li_tm=$io_pdf->getTextWidth(11,$ls_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$ls_periodo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,11,$as_desnom); // Agregar el t�tulo
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 27/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSety(700);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(51,679,500,$io_pdf->getFontHeight(17));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('periodo'=>'<b>Periodo</b>',
						  'cedula'=>'<b>C�dula</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'antiguedad'=>'<b>Monto Prestaci�n Antiguedad</b>',
						  'tasa'=>'<b>Tasa de Interes</b>',
						  'monto'=>'<b>Monto Interes</b>');
		$la_columna=array('periodo'=>'',
						  'cedula'=>'',
						  'nombre'=>'',
						  'antiguedad'=>'',
						  'tasa'=>'',
						  'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('periodo'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>170), // Justificaci�n y ancho de la columna
						 			   'antiguedad'=>array('justification'=>'center','width'=>120), // Justificaci�n y ancho de la columna
						 			   'tasa'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>60))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('periodo'=>'',
						  'cedula'=>'',
						  'nombre'=>'',
						  'antiguedad'=>'',
						  'tasa'=>'',
						  'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('periodo'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>170), // Justificaci�n y ancho de la columna
						 			   'antiguedad'=>array('justification'=>'right','width'=>120), // Justificaci�n y ancho de la columna
						 			   'tasa'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>60))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 06/07/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('total'=>'',
						  'antiguedad'=>'',
						  'tasa'=>'',
						  'monto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>260), // Justificaci�n y ancho de la columna
						 			   'antiguedad'=>array('justification'=>'right','width'=>120), // Justificaci�n y ancho de la columna
						 			   'tasa'=>array('justification'=>'right','width'=>60), // Justificaci�n y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>60))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Listado de Intereses Prestaci�n de Antiguedad</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_desnomdes=$io_fun_nomina->uf_obtenervalor_get("desnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_desnomhas=$io_fun_nomina->uf_obtenervalor_get("desnomhas","");
	$ls_anocurperdes=$io_fun_nomina->uf_obtenervalor_get("anocurperdes","");
	$ls_mescurperdes=$io_fun_nomina->uf_obtenervalor_get("mescurperdes","");
	$ls_desmesperdes=$io_fun_nomina->uf_obtenervalor_get("desmesperdes","");
	$ls_anocurperhas=$io_fun_nomina->uf_obtenervalor_get("anocurperhas","");
	$ls_mescurperhas=$io_fun_nomina->uf_obtenervalor_get("mescurperhas","");
	$ls_desmesperhas=$io_fun_nomina->uf_obtenervalor_get("desmesperhas","");
	$ls_tiporeporte=0;
	if ($ls_codnomdes==$ls_codnomhas)
	{
		$ls_desnom=$ls_desnomdes;
	}
	else
	{
		$ls_desnom=$ls_desnomdes." - ".$ls_desnomhas;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_prestacionantiguedadintereses_personal($ls_codnomdes,$ls_codnomhas,$ls_anocurperdes,$ls_mescurperdes,
																		 $ls_anocurperhas,$ls_mescurperhas); // Obtenemos el detalle del reporte
	}
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
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.9,2.5,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_anocurperdes,$ls_desmesperdes,$ls_desmesperhas,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el n�mero de p�gina
		uf_print_cabecera($io_pdf);
		$li_i=0;
		$li_monant_total=0;
		$li_monint_total=0;
		while(!$io_report->rs_data->EOF)
		{
			$li_i++;
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_nomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			$ls_periodo=str_pad($io_report->rs_data->fields["mescurper"],2,"0",0)." - ".$io_report->rs_data->fields["anocurper"];
			$li_monant_total=$li_monant_total+$io_report->rs_data->fields["monant"];
			$li_monint_total=$li_monint_total+$io_report->rs_data->fields["monint"];
			$li_monant=$io_fun_nomina->uf_formatonumerico($io_report->rs_data->fields["monant"]);
			$li_porint=$io_fun_nomina->uf_formatonumerico($io_report->rs_data->fields["porint"]*100);
			$li_monint=$io_fun_nomina->uf_formatonumerico($io_report->rs_data->fields["monint"]);
			$la_data[$li_i]=array('periodo'=>$ls_periodo,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'antiguedad'=>$li_monant,
								  'tasa'=>$li_porint,'monto'=>$li_monint);
			$io_report->rs_data->MoveNext();
		}
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		$li_monant_total=$io_fun_nomina->uf_formatonumerico($li_monant_total);
		$li_monint_total=$io_fun_nomina->uf_formatonumerico($li_monint_total);
		$la_data[1]=array('total'=>'<b>Total BS </b>','antiguedad'=>$li_monant_total,'tasa'=>'','monto'=>$li_monint_total);
		uf_print_totales($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		if($lb_valido) // Si no ocurrio ning�n error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo alg�n error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 