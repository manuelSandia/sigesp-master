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
	function uf_insert_seguridad($as_titulo,$as_titulo2,$as_desnom,$as_periodo,$ai_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_periodo // Descripci�n del per�odo
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Gener� el Reporte ".$as_titulo." ".$as_titulo2.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_contableconceptos.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hcontableconceptos.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_titulo2,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_titulo2 // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_periodo // Descripci�n del per�odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo2);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$as_titulo2); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,11,$as_periodo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,700,10,$as_desnom); // Agregar el t�tulo
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_presupuesto(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_presupuesto
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera para el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Afectaci�n Presupuestaria</b>'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera_presupuesto
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_contable(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_contable
		//		   Access: private 
		//	    Arguments: io_pdf //Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera para el detalle contable
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Afectaci�n Contable</b>'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera_contable
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_presupuesto($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_presupuesto
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-3);
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ls_titulo="";
		switch($ls_modalidad)
		{
			case "1": // Modalidad por Proyecto
				$ls_titulo="Estructura Presupuestaria";
				break;
				
			case "2": // Modalidad por Presupuesto
				$ls_titulo="Estructura Program�tica  ";
				break;
		}
		$la_columna=array('programatica'=>'<b>'.$ls_titulo.'</b>',
						  'estadisticos'=>'<b>Partida Presupuestaria</b>',
						  'denominacion'=>'<b>                             Descripci�n</b>',
						  'total'=>'<b>Total                 </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('programatica'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'estadisticos'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>200), // Justificaci�n y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle_presupuesto
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_contable($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_contable
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle contable
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-3);
		$la_columna=array('cuenta'=>'<b>Cuenta</b>',
						  'denominacion'=>'<b>                                Descripci�n</b>',
						  'debe'=>'<b>Debe               </b>',
						  'haber'=>'<b>Haber               </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>200), // Justificaci�n y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>100), // Justificaci�n y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle_contable
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_contable_cestatik($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_contable
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle contable
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetDy(-3);
		$la_columna=array('cuenta'=>'',
						  'denominacion'=>'',
						  'debe'=>'',
						  'haber'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>200), // Justificaci�n y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>100), // Justificaci�n y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle_contable

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera_presupuesto($ai_total,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera_presupuesto
		//		   Access: private 
		//	    Arguments: ai_total // Total del presupuesto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera para el detalle presupuestario
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 11/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>'<b>Totales '.$ls_bolivares.'</b>','total'=>$ai_total));
		$la_columna=array('name'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				 		 'cols'=>array('name'=>array('justification'=>'right','width'=>400), // Justificaci�n y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera_presupuesto
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera_contable($ai_debe,$ai_haber,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera_contable
		//		   Access: private 
		//	    Arguments: ai_debe // Total por el Debe
		//	               ai_haber // Total por el Haber
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera para los detalles contables
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 22/05/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>'<b>Totales '.$ls_bolivares.'</b>','debe'=>$ai_debe,'haber'=>$ai_haber));
		$la_columna=array('name'=>'','debe'=>'','haber'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				 		 'cols'=>array('name'=>array('justification'=>'right','width'=>300), // Justificaci�n y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>100),
						 			   'haber'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera_contable
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_firmas(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_numsol // numero de la solicitud
		//	    		   ad_fecregsol // fecha de registro de la solicitud
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		// cuadro inferior
        $io_pdf->Rectangle(15,60,570,70);
		$io_pdf->line(15,60,15,140);//vertical
		$io_pdf->line(585,60,585,140);//vertical
		
		$io_pdf->line(15,71,585,71);//horizontal
		$io_pdf->line(15,80,585,80);//horizontal		
		$io_pdf->line(15,120,585,120);//horizontal
		$io_pdf->line(15,140,585,140);//horizontal		
		$io_pdf->line(130,60,130,130);//vertical		
		$io_pdf->line(240,60,240,130);//vertical		
		$io_pdf->line(380,60,380,130);//vertical		
		$io_pdf->addText(300,132,7,"FIRMAS"); // Agregar el t�tulo
		$io_pdf->addText(40,122,7,"Elaborado Por:"); // Agregar el t�tulo
		$io_pdf->addText(50,73,7,"Escarle Torres"); // Agregar el t�tulo
		$io_pdf->addText(32,63,7,"Es. De Recursos Humanos"); // Agregar el t�tulo
		
		$io_pdf->addText(157,122,7,"Revisado Por:"); // Agregar el t�tulo
		$io_pdf->addText(165,73,7,"Rahiza Hurtado"); // Agregar el t�tulo
		$io_pdf->addText(145,63,7,"Gte. De Recursos Humanos"); // Agregar el t�tulo
		
		$io_pdf->addText(275,122,7,"Verificaci�n Presupuestaria"); // Agregar el t�tulo
		$io_pdf->addText(277,73,7,"Musooline Cevallos"); // Agregar el t�tulo
		$io_pdf->addText(257,63,7,"Gte. De Planif. y Control de Gesti�n"); // Agregar el t�tulo
		
		$io_pdf->addText(440,122,7,"Autorizaci�n Administrativa"); // Agregar el t�tulo
		$io_pdf->addText(450,73,7,"Maryoris Lurua"); // Agregar el t�tulo
		$io_pdf->addText(430,63,7,"Gte. de Administraci�n y Finanzas"); // Agregar el t�tulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$li_tipo=0;
	$ls_bolivares="";
	if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
	{
		require_once("sigesp_sno_class_report_contables.php");
		$io_report=new sigesp_sno_class_report_contables();
		$li_tipo=1;
	}
	if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
	{
		require_once("sigesp_sno_class_report_historico_contables.php");
		$io_report=new sigesp_sno_class_report_historico_contables();
		$li_tipo=2;
	}	
	$ls_bolivares ="Bs.";
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ls_conpronom=$_SESSION["la_nomina"]["conpronom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Resumen Contable</b>";
	$ls_titulo2="<b>Presupuestario de Conceptos</b>";
	$ls_periodo="<b>Per�odo Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_titulo2,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido) // Buscamos la informaci�n que afecta el presupuesto
	{
		switch($ls_conpronom)
		{
			case "1":
				$lb_valido=$io_report->uf_contableconceptos_presupuesto_proyecto();
				break;
				
			default:
				$lb_valido=$io_report->uf_contableconceptos_presupuesto();
				break;
		}
	}
	if($lb_valido) // Buscamos la informaci�n que afecta contabilidad por el debe
	{
		switch($ls_conpronom)
		{
			case "1":
				$lb_valido=$io_report->uf_contableconceptos_contable_proyecto();
				break;
				
			default:
				$lb_valido=$io_report->uf_contableconceptos_contable();
				break;
		}
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
		$io_pdf->ezSetCmMargins(3.3,2.5,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$ls_titulo2,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el n�mero de p�gina
		
  	    //--------------------------------------------- Imprimir el detalle Presupuestario------------------------------------------------	
		$li_totrow=$io_report->DS->getRowCount("cueprecon");
		$li_totalpresupuesto=0;
		$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
		$ls_espnom=$_SESSION["la_nomina"]["espnom"];
		$ls_ctnom=$_SESSION["la_nomina"]["ctnom"];
		$ls_codpronom=$_SESSION["la_nomina"]["codpronom"];
		$ls_codbennom=$_SESSION["la_nomina"]["codbennom"];
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_programatica=$io_report->DS->data["codestpro1"][$li_i].$io_report->DS->data["codestpro2"][$li_i].
							 $io_report->DS->data["codestpro3"][$li_i].$io_report->DS->data["codestpro4"][$li_i].
							 $io_report->DS->data["codestpro5"][$li_i];
			$io_fun_nomina->uf_formato_estructura($ls_programatica,$ls_codest1,$ls_codest2,$ls_codest3,$ls_codest4,$ls_codest5);
			$ls_programatica=$ls_codest1.'-'.$ls_codest2.'-'.$ls_codest3;
			switch($ls_modalidad)
			{
				case "2": // Modalidad por Programa
					
					$ls_programatica=$ls_codest5.'-'.$ls_codest2.'-'.$ls_codest3.'-'.$ls_codest4.'-'.$ls_codest5;
					break;
			}
			$ls_cueprecon=$io_report->DS->data["cueprecon"][$li_i];
			$ls_denominacion=$io_report->DS->data["denominacion"][$li_i];
			$li_total=$io_report->DS->data["total"][$li_i];
			$li_totalpresupuesto=$li_totalpresupuesto+$li_total;
			$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			$la_data[$li_i]=array('programatica'=>$ls_programatica,'estadisticos'=>$ls_cueprecon,
								  'denominacion'=>$ls_denominacion,'total'=>$li_total);
		}
		$io_report->DS->resetds("cueprecon");
		if($li_totrow>0)
		{
			uf_print_cabecera_presupuesto($io_pdf); // Imprimimos la cabecera de presupuesto
			uf_print_detalle_presupuesto($la_data,$io_pdf); // Imprimimos el detalle presupuestario
			$li_totalpresupuesto=$io_fun_nomina->uf_formatonumerico($li_totalpresupuesto);
			uf_print_pie_cabecera_presupuesto($li_totalpresupuesto,$io_pdf); // imprimimos los totales presupuestario
			unset($la_data);			
		}
		//-------------------------------------------------------------------------------------------------------------------------------	
		
		//--------------------------------------------- Imprimir el detalle Contable------------------------------------------------	
		$li_i=0;
		$li_totrow=$io_report->DS_detalle->getRowCount("cuenta");
		$li_totalcontadebe=0;
		$li_totalcontahaber=0;
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_cueconpatcon=trim($io_report->DS_detalle->data["cuenta"][$li_i]);
			$ls_denominacion=$io_report->DS_detalle->data["denominacion"][$li_i];
			$ls_operacion=$io_report->DS_detalle->data["operacion"][$li_i];
			if($ls_operacion=="D")
			{
				$li_debe=abs($io_report->DS_detalle->data["total"][$li_i]);
				$li_haber=0;
				$li_totalcontadebe=$li_totalcontadebe+$li_debe;
				$li_totalcontahaber=$li_totalcontahaber+$li_haber;
				$li_debe=$io_fun_nomina->uf_formatonumerico($li_debe);
				$li_haber=$io_fun_nomina->uf_formatonumerico($li_haber);
				$la_data[$li_i]=array('cuenta'=>$ls_cueconpatcon,'denominacion'=>$ls_denominacion,'debe'=>$li_debe,'haber'=>$li_haber);
			}
		}
		$li_acuhab=0;
		if (($ls_espnom==1)&&($ls_ctnom==1))
		{
			if($ls_codpronom=='----------')
			{
				$ls_codprovben_cest=$ls_codbennom;
				$ls_benprov='B';
			}
			else
			{
				$ls_codprovben_cest=$ls_codpronom;
				$ls_benprov='P';
			}
			$ls_ctaprovben_cest=$io_report->uf_select_provbene_ctacestatik($ls_codprovben_cest,$ls_benprov);
			$ls_denctaprovben_cest=$io_report->uf_select_provbene_denctacestatik($ls_ctaprovben_cest);
			$lb_chkbean=0;
			for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
			{
				$ls_cueconpatcon=trim($io_report->DS_detalle->data["cuenta"][$li_i]);
				$ls_denominacion=$io_report->DS_detalle->data["denominacion"][$li_i];
				$ls_operacion=$io_report->DS_detalle->data["operacion"][$li_i];
				if($ls_operacion=="H")
				{
					$lb_chkbean=1;
					$li_debe=0;
					$li_haber=abs($io_report->DS_detalle->data["total"][$li_i]);
					$li_totalcontadebe=$li_totalcontadebe+$li_debe;
					$li_totalcontahaber=$li_totalcontahaber+$li_haber;
					$li_debe=$io_fun_nomina->uf_formatonumerico($li_debe);
					$li_haber=$io_fun_nomina->uf_formatonumerico($li_haber);
					$li_haberdet=$io_fun_nomina->uf_formatonumerico($li_totalcontahaber);
					$la_data_haber[$li_totrow]=array('cuenta'=>$ls_ctaprovben_cest,'denominacion'=>$ls_denctaprovben_cest,'debe'=>$li_debe,'haber'=>$li_haberdet);
				}
			}
		}
		else
		{
			for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
			{
				$ls_cueconpatcon=trim($io_report->DS_detalle->data["cuenta"][$li_i]);
				$ls_denominacion=$io_report->DS_detalle->data["denominacion"][$li_i];
				$ls_operacion=$io_report->DS_detalle->data["operacion"][$li_i];
				if($ls_operacion=="H")
				{
					$li_debe=0;
					$li_haber=abs($io_report->DS_detalle->data["total"][$li_i]);
					$li_totalcontadebe=$li_totalcontadebe+$li_debe;
					$li_totalcontahaber=$li_totalcontahaber+$li_haber;
					$li_debe=$io_fun_nomina->uf_formatonumerico($li_debe);
					$li_haber=$io_fun_nomina->uf_formatonumerico($li_haber);
					$la_data[$li_i]=array('cuenta'=>$ls_cueconpatcon,'denominacion'=>$ls_denominacion,'debe'=>$li_debe,'haber'=>$li_haber);
				}
			}
		}
		$io_report->DS_detalle->resetds("cuenta");
		if($li_totrow>0)
		{
			uf_print_cabecera_contable($io_pdf);// Imprimimos la cabecera contable
			uf_print_detalle_contable($la_data,$io_pdf); // Imprimimos el detalle contable
			if (($ls_espnom==1)&&($ls_ctnom==1)&&($lb_chkbean==1))
			{
				uf_print_detalle_contable_cestatik($la_data_haber,$io_pdf); // Imprimimos el detalle contable
			}
			$li_totalcontadebe=$io_fun_nomina->uf_formatonumerico($li_totalcontadebe);
			$li_totalcontahaber=$io_fun_nomina->uf_formatonumerico($li_totalcontahaber);
			uf_print_pie_cabecera_contable($li_totalcontadebe,$li_totalcontahaber,$io_pdf); // imprimimos los totales contable			
			uf_firmas($io_pdf);
			unset($la_data);
		}		
		//-------------------------------------------------------------------------------------------------------------------------------	
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