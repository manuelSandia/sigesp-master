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
		// Fecha Creación: 20/05/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sob;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_sob->uf_load_seguridad_reporte("SOB","sigesp_sob_d_obra.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_codobr // Còdigo de Obra
		//	    		   ad_feccreobr // Fecha de Registro de Obra
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 20/05/2009
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
		$ls_sigemp=$_SESSION["la_empresa"]["sigemp"];
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,713,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,670,11,$as_titulo); // Agregar el título
		$io_pdf->addText(50,700,11,$ls_nomemp); // Agregar el título
		$io_pdf->addText(540,690,9,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codobr,$as_desobr,$ai_monto,$as_ubicacion,$as_feciniobr,$as_fecfinobr,$li_porcobrar,&$io_pdf)

	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_desobr    // Descripciòn de la Obra
		//	   			   as_nompro // Organismo Ejecutor
		//	   			   as_resobr // Responsable de la Obra
		//	   			   as_nomsiscon // Sistema Constructivo
		//	   			   as_nomtob    // tipo de Obra
		//	   			   as_nomtipest    // Tipo Estructura
		//	   			   as_consol    // Concepto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 22/05/2009
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data[1]=array('titulo'=>'<b> Obra: </b>'.$as_desobr);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('1'=>'<b>Fecha Inicio:</b> '.$as_feciniobr,'2'=>'<b>Fecha Fin:</b> '.$as_fecfinobr,'3'=>'<b>Monto:</b>','4'=>$ai_monto);
		$la_columnas=array('1'=>'','2'=>'','3'=>'','4'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>2, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('1'=>array('justification'=>'left','width'=>135),
						 			   '2'=>array('justification'=>'left','width'=>145),
									   '3'=>array('justification'=>'left','width'=>135),
									   '4'=>array('justification'=>'left','width'=>135))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b> Ubicacion: </b>'.$as_ubicacion);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('titulo'=>'<b> Por Cobrar: </b>'.$li_porcobrar);
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);


	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_contratos($la_data,$li_totalcontratos,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columnas=array('codcon'=>'<b>No. Contrato</b>',
						   'feccon'=>'<b>Fecha</b>',
						   'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codcon'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'feccon'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);

		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('codval'=>'TOTAL CONTRATOS.....',
						   'montotval'=>$li_totalcontratos);
		$la_columnas=array('codval'=>'<b></b>',
						   'montotval'=>'<b></b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codval'=>array('justification'=>'right','width'=>400), // Justificación y ancho de la columna
						 			   'montotval'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_anticipos($la_data,$li_totalanticipos,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columnas=array('codcon'=>'<b>No. Anticipo</b>',
						   'feccon'=>'<b>Fecha</b>',
						   'monto'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codcon'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'feccon'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$la_data[1]=array('codval'=>'TOTAL ANTICIPOS.....',
						   'montotval'=>$li_totalanticipos);
		$la_columnas=array('codval'=>'<b></b>',
						   'montotval'=>'<b></b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codval'=>array('justification'=>'right','width'=>400), // Justificación y ancho de la columna
						 			   'montotval'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_valuacion($la_data,$li_totmontotval,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columnas=array('codval'=>'<b>No. Valuacion</b>',
						   'fecval'=>'<b>Fecha</b>',
						   'montotval'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codval'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'fecval'=>array('justification'=>'center','width'=>150), // Justificación y ancho de la columna
						 			   'montotval'=>array('justification'=>'right','width'=>150))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		/*$la_data[1]=array('codval'=>'TOTAL VALUACIONES.....',
						   'montotval'=>$li_totmontotval);
		$la_columnas=array('codval'=>'<b></b>',
						   'montotval'=>'<b></b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codval'=>array('justification'=>'right','width'=>400), // Justificación y ancho de la columna
						 			   'montotval'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);*/
	}// end function uf_print_detalle_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_sob.php");
	$io_fun_sob=new class_funciones_sob();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	if($ls_estmodest==1)
	{
		$ls_titcuentas="Estructura Presupuestaria";
	}
	else
	{
		$ls_titcuentas="Estructura Programatica";
	}
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	 $ls_coddes=$io_fun_sob->uf_obtenervalor_get("coddes","");
	 $ls_codhas=$io_fun_sob->uf_obtenervalor_get("codhas","");
	 $ls_fecregdes=$io_fun_sob->uf_obtenervalor_get("fecregdes","");
	 $ls_fecreghas=$io_fun_sob->uf_obtenervalor_get("fecreghas","");
	 $ls_codorgeje=$io_fun_sob->uf_obtenervalor_get("codorgeje","");
	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("sigesp_sob_class_report.php");
	$io_report=new sigesp_sob_class_report();
	 //Instancio a la clase de conversión de numeros a letras.
	 include("../../shared/class_folder/class_numero_a_letra.php");
	 $numalet= new class_numero_a_letra();
	 //imprime numero con los valore por defecto
	 //cambia a minusculas
	 $numalet->setMayusculas(1);
	 //cambia a femenino
	 $numalet->setGenero(1);
	 //cambia moneda
	 $numalet->setMoneda("Bolivares");
	 $ls_moneda="EN Bs.";
	 //cambia prefijo
	 $numalet->setPrefijo("***");
	 //cambia sufijo
	 $numalet->setSufijo("***");
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	 $ls_titulo='<b>SEGUIMIENTO DE OBRAS</b>';
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_listadoobras($ls_coddes,$ls_codhas,$ls_fecregdes,$ls_fecreghas,$ls_codorgeje); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe algún error ó no hay registros
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		else  // Imprimimos el reporte
		{
			error_reporting(E_ALL);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(5,6,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			while(!$io_report->rs_data->EOF)
			{			
				$ls_codobr=$io_report->rs_data->fields["codobr"];
				$ls_desobr=$io_report->rs_data->fields["desobr"];
				$li_monto=$io_report->rs_data->fields["monto"];
				$ls_despai=$io_report->rs_data->fields["despai"];
				$ls_desest=$io_report->rs_data->fields["desest"];
				$ls_desmun=$io_report->rs_data->fields["desmun"];
				$ls_despar=$io_report->rs_data->fields["despar"];
				$ls_descom=$io_report->rs_data->fields["descom"];
				$li_anticipos=$io_report->rs_data->fields["anticipo"];
				$li_valuaciones=$io_report->rs_data->fields["valuacion"];
				$ls_feciniobr=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["feciniobr"]);
				$ls_fecfinobr=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecfinobr"]);
				$li_porcobrar=$li_monto-$li_valuaciones;  //REVISAR FORMULA
				$li_monto=number_format($li_monto,2,",",".");
				$li_anticipos=number_format($li_anticipos,2,",",".");
				$li_valuaciones=number_format($li_valuaciones,2,",",".");
				$li_porcobrar=number_format($li_porcobrar,2,",",".");
				$ls_ubicacion=$ls_despai." - ".$ls_desest." - ".$ls_desmun." - ".$ls_despar." - ".$ls_descom;
				uf_print_cabecera($ls_codobr,$ls_desobr,$li_monto,$ls_ubicacion,$ls_feciniobr,$ls_fecfinobr,$li_porcobrar,&$io_pdf);
				$lb_valido=$io_report->uf_select_contratos_obras($ls_codobr); 
				$li_s=0;
				$li_totalcontrato=0;
				while(!$io_report->rs_contratos->EOF)
				{
					$li_s++;
					$ls_codcon=$io_report->rs_contratos->fields["codcon"];
					$ls_feccon=$io_funciones->uf_convertirfecmostrar($io_report->rs_contratos->fields["feccon"]);
					$ls_montocontrato=$io_report->rs_contratos->fields["monto"];
					$li_totalcontrato=$li_totalcontrato+$ls_montocontrato;
					$ls_montocontrato=number_format($ls_montocontrato,2,",",".");

					$la_data[$li_s]=array('codcon'=>$ls_codcon,'feccon'=>$ls_feccon,'monto'=>$ls_montocontrato);
					$io_report->rs_contratos->MoveNext();
				}
				if($li_s>0)
				{
					$li_totalcontrato=number_format($li_totalcontrato,2,",",".");
					uf_print_detalle_contratos($la_data,$li_totalcontrato,&$io_pdf);	
				}
				unset($la_data);
				$lb_valido=$io_report->uf_select_anticipos_obras($ls_codobr); 
				$li_s=0;
				$li_totalanticipo=0;
				while(!$io_report->rs_anticipos->EOF)
				{
					$li_s++;
					$ls_codant=$io_report->rs_anticipos->fields["codant"];
					$ls_fecant=$io_funciones->uf_convertirfecmostrar($io_report->rs_anticipos->fields["fecant"]);
					$li_montoanticipo=$io_report->rs_anticipos->fields["monto"];
					$li_totalanticipo=$li_totalanticipo+$li_montoanticipo;
					$li_montoanticipo=number_format($li_montoanticipo,2,",",".");

					$la_data[$li_s]=array('codcon'=>$ls_codant,'feccon'=>$ls_fecant,'monto'=>$li_montoanticipo);
					$io_report->rs_anticipos->MoveNext();
				}
				if($li_s>0)
				{
					$li_totalanticipo=number_format($li_totalanticipo,2,",",".");
					uf_print_detalle_anticipos($la_data,$li_totalanticipo,&$io_pdf);
				}
				unset($la_data);
				$lb_valido=$io_report->uf_select_valuaciones_obras($ls_codobr); 
				$li_x=0;
				$li_totmontotval=0;
				while(!$io_report->rs_valuaciones->EOF)
				{
					$li_x++;
					$ls_codval=$io_report->rs_datavaluaciones->fields["codval"];
					$li_montotval=$io_report->rs_datavaluaciones->fields["subtot"];
					$ls_fecval=$io_funciones->uf_convertirfecmostrar($io_report->rs_contratos->fields["fecha"]);
					$li_totmontotval=$li_totmontotval+$li_montotval;
					$li_montotval=number_format($li_montotval,2,",",".");
					$la_data[$li_x]=array('codval'=>$ls_codval,'fecval'=>$ls_fecval,'montotval'=>$li_montotval);
					
					$io_report->rs_valuaciones->MoveNext();
				}
				if($li_x>0)
				{
					$li_totmontotval=number_format($li_totmontotval,2,",",".");
					uf_print_detalle_valuacion($la_data,$li_totmontotval,&$io_pdf);
				}
				unset($la_data);
				$io_report->rs_data->MoveNext();
				if(!$io_report->rs_data->EOF)
				$io_pdf->ezNewPage();
			}
		}
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		
	}

?>
