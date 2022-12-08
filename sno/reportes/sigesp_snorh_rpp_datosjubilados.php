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
		// Fecha Creaci�n: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadodatosjubilados.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el t�tulo
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_cedper,$as_nomper,$as_carantper,$as_gerantper,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 27/07/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		$la_data[0]=array('cedula'=>'<b>C�dula</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'cargo'=>'<b>Cargo Anterior</b>',
						  'gerencia'=>'<b>Gerencia Anterior</b>');
		$la_columna=array('cedula'=>'',
						  'nombre'=>'',
						  'cargo'=>'',
						  'gerencia'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 6,  // Tama�o de Letras de los t�tulos
						 'showLines'=>2, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center',
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>180), // Justificaci�n y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>125), // Justificaci�n y ancho de la columna
						 			   'gerencia'=>array('justification'=>'center','width'=>125))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[0]=array('cedula'=>$as_cedper,'nombre'=>$as_nomper,'cargo'=>$as_carantper,'gerencia'=>$as_gerantper);
		$la_columna=array('cedula'=>'',
						  'nombre'=>'',
						  'cargo'=>'',
						  'gerencia'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 6,  // Tama�o de Letras de los t�tulos
						 'showLines'=>2, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center',
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>180), // Justificaci�n y ancho de la columna
						 			   'cargo'=>array('justification'=>'left','width'=>125), // Justificaci�n y ancho de la columna
						 			   'gerencia'=>array('justification'=>'left','width'=>125))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
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
		// Fecha Creaci�n: 22/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data1[1]=array('titulo'=>'<b>BENEFICIARIO(S)</b>');
		$la_columna=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 6,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center',
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>500))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		$la_data1[1]=array('cedben'=>'<b>Cedula Beneficiario</b>',
						  'nomben'=>'<b>Apellidos y Nombres Beneficiario</b>',
						  'nexben'=>'<b>Nexo Beneficiario</b>');
		$la_columna=array('cedben'=>'',
						  'nomben'=>'',
						  'nexben'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 6,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center',
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cedben'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'nomben'=>array('justification'=>'center','width'=>200), // Justificaci�n y ancho de la columna
						 			   'nexben'=>array('justification'=>'center','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);
		unset($la_data1);
		unset($la_columna);
		unset($la_config);

		$la_columna=array('cedben'=>'',
						  'nomben'=>'',
						  'nexben'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 6,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center',
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cedben'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'nomben'=>array('justification'=>'left','width'=>200), // Justificaci�n y ancho de la columna
						 			   'nexben'=>array('justification'=>'left','width'=>200))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
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
	$ls_titulo="<b>Listado de Datos del Jubilado</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_activo=$io_fun_nomina->uf_obtenervalor_get("activo","");
	$ls_egresado=$io_fun_nomina->uf_obtenervalor_get("egresado","");
	$ls_causaegreso=$io_fun_nomina->uf_obtenervalor_get("causaegreso","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_datosjubilado_personal($ls_codperdes,$ls_codperhas,$ls_activo,$ls_egresado,$ls_causaegreso,$ls_orden); // Obtenemos el detalle del reporte
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
		$io_pdf->ezSetCmMargins(3,2.5,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el n�mero de p�gina		
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacci�n
			$li_numpag=$io_pdf->ezPageCount; // N�mero de p�gina
			$li_i=0;
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_nomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			$ls_carantper=$io_report->rs_data->fields["carantper"];
			$ls_gerantper=$io_report->rs_data->fields["gerantper"];
			$lb_valido=$io_report->uf_datosjubilado_beneficiario($ls_codper); // Obtenemos el detalle del reporte
			if(($lb_valido)&&(!$io_report->rs_data_dt->EOF))
			{
				while(!$io_report->rs_data_dt->EOF)
				{
					$ls_cedben=$io_report->rs_data_dt->fields["cedben"];
					$ls_nomben=$io_report->rs_data_dt->fields["apeben"].", ".$io_report->rs_data_dt->fields["nomben"];
					$ls_nexben=$io_report->desc_nexo($io_report->rs_data_dt->fields["nexben"]);
					$li_i++;
					$la_data[$li_i]=array('cedben'=>$ls_cedben,'nomben'=>$ls_nomben,'nexben'=>$ls_nexben);
					$io_report->rs_data_dt->MoveNext();
				}
			}
			else
			{
				$li_i++;
				$la_data[$li_i]=array('cedben'=>'','nomben'=>'','nexben'=>'');
			}
			uf_print_cabecera($ls_cedper,$ls_nomper,$ls_carantper,$ls_gerantper,$io_pdf);
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			if ($io_pdf->ezPageCount==$li_numpag)
			{// Hacemos el commit de los registros que se desean imprimir
				$io_pdf->transaction('commit');
			}
			else
			{// Hacemos un rollback de los registros, agregamos una nueva p�gina y volvemos a imprimir
				$io_pdf->transaction('rewind');
				$io_pdf->ezNewPage(); // Insertar una nueva p�gina
				uf_print_cabecera($ls_cedper,$ls_nomper,$ls_carantper,$ls_gerantper,$io_pdf);
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
			}
			unset($la_data);
			$io_report->rs_data->MoveNext();
		}
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