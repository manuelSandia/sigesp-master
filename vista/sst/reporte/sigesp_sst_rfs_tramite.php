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

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sep;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_sep->uf_load_seguridad_reporte("SEP","sigesp_sep_p_solicitud.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numtramite,&$io_pdf){
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_numtramite // numero del tramite
		//	    		   ad_fecini // fecha de inicio del tramite
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
		$io_pdf->line(480,700,480,760);
		$io_pdf->line(480,730,585,730);
        $io_pdf->Rectangle(15,700,570,60);
		$io_pdf->addJpegFromFile('../../../shared/imagebank/'.$_SESSION["ls_logo"],30,703,55,55); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$io_pdf->addText(485,740,9,"No. ".$as_numtramite); // Agregar el título
		$io_pdf->addText(485,710,9,"Fecha ".date("d/m/Y")); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codproven,$as_fecini,$as_docini,$as_fecfin,$as_docfin,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_dentipsol // Denominacion del tipo de solicitud
		//	   			   as_denuniadm // Denominacion de la Unidad Ejecutora solicitante
		//	   			   as_denfuefin // Denominacion de la fuente de financiamiento
		//	   			   as_codigo    // Codigo del Proveedor / Beneficiario
		//	   			   as_nombre    // Nombre del Proveedor / Beneficiario
		//	   			   as_consol    // Concepto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 17/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('titulo'=>'<b> Proveedor / Beneficiario</b>','contenido'=>$as_codproven),
					   array('titulo'=>'<b> Fecha Inicio</b>','contenido'=>$as_fecini),
					   array('titulo'=>'<b> Documento Inicio</b>','contenido'=>$as_docini),
					   array('titulo'=>'<b> Fecha Final</b>','contenido'=>$as_fecfin),
					   array('titulo'=>'<b> Documento Final</b>','contenido'=>$as_docfin));
		$la_columnas=array('titulo'=>'',
						   'contenido'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'cols'=>array('titulo'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'contenido'=>array('justification'=>'left','width'=>450))); // Justificación y ancho de la columna
		
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-10);
		$la_columnas=array('uniemi'=>'<b>Unidad Emisora</b>',
						   'emisor'=>'<b>Emisor</b>',
						   'docenv'=>'<b>Documento Enviado</b>',
						   'fecenv'=>'<b>Fecha de Envio</b>',
						   'unirec'=>'<b>Unidad Receptora</b>',
						   'recept'=>'<b>Receptor</b>',
						   'fecrec'=>'<b>Fecha de Recepcion</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>1, // Sombra entre líneas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('uniemi'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la columna
						 			   'emisor'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'docenv'=>array('justification'=>'center','width'=>115), // Justificación y ancho de la columna
						 			   'fecenv'=>array('justification'=>'center','width'=>65), // Justificación y ancho de la columna
						 			   'unirec'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'recept'=>array('justification'=>'center','width'=>60), // Justificación y ancho de la columna
						 			   'fecrec'=>array('justification'=>'center','width'=>65))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	$dirvissstrpptra = "";
	$dirvissstrpptra = dirname(__FILE__);
	$dirvissstrpptra = str_replace("\\","/",$dirvissstrpptra);
	$dirvissstrpptra = str_replace("/vista/sst/reporte","",$dirvissstrpptra);
	require_once($dirvissstrpptra."/shared/ezpdf/class.ezpdf.php");
	require_once($dirvissstrpptra."/shared/class_folder/class_funciones.php");
	require_once($dirvissstrpptra."/controlador/sst/sigesp_ctr_sst_servicio.php");
	$io_funciones = new class_funciones();
	$io_report    = new ServicioSst();
	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	 $numtramite=$_GET["numtramite"];
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	 $ls_titulo='<b>FLUJO DE TRABAJO</b>';
	//--------------------------------------------------------------------------------------------------------------------------------
	$datacabecera = $io_report->obtenerCaberceraTramite($_SESSION["la_empresa"]["codemp"],$numtramite);
	if($datacabecera->EOF){// Existe algún error ó no hay registros
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else{// Imprimimos el reporte
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.6,6,3,3); // Configuración de los margenes en centímetros
		$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
		$ld_fecini=$io_funciones->uf_convertirfecmostrar($datacabecera->fields["fecini"]);
							
		uf_print_encabezado_pagina($ls_titulo,$numtramite,&$io_pdf);
		uf_print_cabecera($datacabecera->fields["nomprop"],$ld_fecini,$datacabecera->fields["coddocini"],$io_funciones->uf_convertirfecmostrar($datacabecera->fields["fecfin"]),$datacabecera->fields["coddocfin"],&$io_pdf);

		$li_s=0;
		$datadetalle=$io_report->obtenerDetalleTramite($_SESSION["la_empresa"]["codemp"],$numtramite);
		
		while(!$datadetalle->EOF){
			$ls_uniemi = $datadetalle->fields["denunienv"];
			$ls_emisor = $datadetalle->fields["codusuenv"];
			$ls_docenv = $datadetalle->fields["coddocenv"];
			$ls_fecenv = $datadetalle->fields["fecenv"];
			$ls_unirec = $datadetalle->fields["denunirec"];
			$ls_recept = $datadetalle->fields["codusurec"];
			$ls_fecrec = $datadetalle->fields["fecrec"];
			$la_data[$li_s]=array('uniemi'=>$ls_uniemi,'emisor'=>$ls_emisor,'docenv'=>$ls_docenv,
								  'fecenv'=>$ls_fecenv,'unirec'=>$ls_unirec,'recept'=>$ls_recept,'fecrec'=>$ls_fecrec);
			$datadetalle->MoveNext();
			$li_s++;
		}
		uf_print_detalle($la_data,&$io_pdf);
		unset($datadetalle);
		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
		$io_pdf->ezStream(); // Mostramos el reporte
	}
?>
