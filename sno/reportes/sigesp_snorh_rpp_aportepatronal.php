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
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Tï¿½tulo del reporte
		//	    		   as_desnom // descripciï¿½n de la nï¿½mina
		//	    		   as_periodo // perï¿½odo actual de la nï¿½mina
		//    Description: funciï¿½n que guarda la seguridad de quien generï¿½ el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Genero el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_aportepatronal.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Tï¿½tulo del Reporte
		//	    		   as_desnom // Descripciï¿½n de la nï¿½mina
		//	    		   as_periodo // Descripciï¿½n del perï¿½odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime los encabezados por pï¿½gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 27/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		/*
		 * PORTRAIT
		 * 
		 * */
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		//$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,750,43,43); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,780,11,$as_titulo); // Agregar el tï¿½tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,770,11,$as_desnom); // Agregar el tï¿½tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,760,11,$as_periodo); // Agregar el tï¿½tulo
		$io_pdf->addText(512,760,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,753,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
		
		
		
		
	}// end function uf_print_encabezado_pagina
	
	
	
	function uf_print_encabezado_paginacah($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Tï¿½tulo del Reporte
		//	    		   as_desnom // Descripciï¿½n de la nï¿½mina
		//	    		   as_periodo // Descripciï¿½n del perï¿½odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime los encabezados por pï¿½gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 27/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		
		
		
		
		/*
		 * 
		 * LANDSCAPE
		 * 
		 * */
		
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(0,0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],5,560,43,43); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=506-($li_tm/2);
		$io_pdf->addText($tm,595,11,$as_titulo); // Agregar el tï¿½tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_desnom);
		$tm=506-($li_tm/2);
		$io_pdf->addText($tm,585,11,$as_desnom); // Agregar el tï¿½tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=506-($li_tm/2);
		$io_pdf->addText($tm,575,11,$as_periodo); // Agregar el tï¿½tulo
		$io_pdf->addText(912,580,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(918,570,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
		
	}// end function uf_print_encabezado_pagina
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codconc,$as_nomcon,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codconc // Cï¿½digo de Concepto
		//	   			   as_nomcon // Nombre de Concepto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_cabecera=$io_pdf->openObject();
		$io_pdf->saveState();
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,733,501.5,$io_pdf->getFontHeight(14));
        $io_pdf->setColor(0,0,0);
		$io_pdf->addText(55,738,11,'<b>Concepto</b>  '.$as_nomcon.''); // Agregar el tï¿½tulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');	
		
		
		
		
		
	}// end function uf_print_cabecera
	
	
	
	function uf_print_cabeceracah($as_codconc,$as_nomcon,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codconc // Cï¿½digo de Concepto
		//	   			   as_nomcon // Nombre de Concepto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		
		$io_cabecera=$io_pdf->openObject();
		$io_pdf->saveState();
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(2,555,990,$io_pdf->getFontHeight(12));
        $io_pdf->setColor(0,0,0);
		$io_pdf->addText(55,558,11,'<b>Concepto</b>  '.$as_nomcon.''); // Agregar el tï¿½tulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
		
		
		
		
		
		
	}// end function uf_print_cabecera
	
	
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaciï¿½n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSety(688);
		//$io_pdf->ezSety(688);
		
		$la_columnas=array('nro'=>'<b>Nro</b>',
						   'cedula'=>'<b>Cédula</b>',
						   'nombre'=>'<b>            Apellidos y Nombres</b>',
						   'personal'=>'<b>Empleado     </b>',
						   'patron'=>'<b>Patrón          </b>',
						   'total'=>'<b>Total          </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaï¿½o de Letras
						 'titleFontSize' => 12,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('nro'=>array('justification'=>'center','width'=>30), // Justificaciï¿½n y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>70), // Justificaciï¿½n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>160), // Justificaciï¿½n y ancho de la columna
						 			   'personal'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'patron'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>80))); // Justificaciï¿½n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle




function uf_print_detallecah($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaciï¿½n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$la_columnas=array('nro'=>'<b>Nro</b>',
						   'cedula'=>'<b>Cédula</b>',
						   'nombre'=>'<b>Apellidos y Nombres</b>',
						   'personal'=>'<b>Empleado</b>',
						   'patron'=>'<b>Patrón</b>',
						   'pcp'=>'<b>PCP</b>',	
						   'pmp'=>'<b>PMP</b>',
						   'bic'=>'<b>BIC</b>',
						   'uti'=>'<b>Utiles</b>',
						   'ma'=>'<b>Mutuo Auxilio</b>',
				           'sfu'=>'<b>SFunerario</b>',
				           'pco'=>'<b>PCocomercial</b>',
				           'opt'=>'<b>Optica</b>',
				           'sir'=>'<b>Siragon</b>',
				           'lma'=>'<b>LMarron</b>',
						   'total'=>'<b>Total</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaï¿½o de Letras
						 'titleFontSize' => 12,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>2, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>1000, // Ancho Mï¿½ximo de la tabla
						  'height'=>450, // Ancho de la tabla
						 'maxHeight'=>500, 
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
													 'cols'=>array('nro'=>array('justification'=>'right','width'=>40),
						                'cedula'=>array('justification'=>'center','width'=>60), // Justificaciï¿½n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>100), // Justificaciï¿½n y ancho de la columna
						 			   'personal'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
						 			   'patron'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
									    'pcp'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
								            'pmp'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna		
									    'bic'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
						                           'uti'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
		                                 'ma'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
													 		
									 'sfu'=>array('justification'=>'right','width'=>60),
													 		'pco'=>array('justification'=>'right','width'=>60),
													 		'opt'=>array('justification'=>'right','width'=>60),
													 		'sir'=>array('justification'=>'right','width'=>60),
													 		'lma'=>array('justification'=>'right','width'=>60),
													 		
													 		
						 			   'total'=>array('justification'=>'right','width'=>70)));	// Justificaciï¿½n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle

	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_personal,$ai_patron,$ai_total,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_personal // Total por personal
		//	   			   ai_patron // Total por patrï¿½n
		//	   			   ai_total // Total 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime el fin de la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		$la_data=array(array('name'=>'<b>Total Aporte '.$ls_bolivares.'</b>','personal'=>$ai_personal,'aporte'=>$ai_patron,'total'=>$ai_total));
		$la_columna=array('name'=>'','personal'=>'','aporte'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaï¿½o de Letras
						 'titleFontSize' => 12,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'shaded'=>2, // Sombra entre lï¿½neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'cols'=>array('name'=>array('justification'=>'right','width'=>260), // Justificaciï¿½n y ancho de la columna
						 			   'personal'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>80))); // Justificaciï¿½n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
        $io_pdf->setColor(0,0,0);
	}



function uf_print_piecabeceracah($ai_personal,$ai_patron,$ai_pcp,$ai_pmp,$ai_bic,$ai_uti,$ai_ma,$ai_sfu,$ai_pco,$ai_opt,$ai_sir,$ai_lma,$ai_total,$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_personal // Total por personal
		//	   			   ai_patron // Total por patrï¿½n
		//	   			   ai_total // Total 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime el fin de la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		//$io_pdf->ezSety(65);
		
		
		$la_data=array(array('name'=>'<b>Total Aporte '.$ls_bolivares.'</b>','personal'=>$ai_personal,'aporte'=>$ai_patron,'pcp'=>$ai_pcp,'pmp'=>$ai_pmp,'bic'=>$ai_bic,'utiles'=>$ai_uti,'ma'=>$ai_ma,'sfu'=>$ai_sfu,'pco'=>$ai_pco,'opt'=>$ai_opt,'sir'=>$ai_sir,'lma'=>$ai_lma,'total'=>$ai_total));
		$la_columna=array('name'=>'','personal'=>'','aporte'=>'','pcp'=>'','pmp'=>'','bic'=>'','utiles'=>'','ma'=>'','sfu'=>'','pco'=>'','opt'=>'','sir'=>'','lma'=>'','total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaï¿½o de Letras
						 'titleFontSize' => 12,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'shaded'=>2, // Sombra entre lï¿½neas
						// 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						// 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>1000, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'cols'=>array('name'=>array('justification'=>'right','width'=>200), // Justificaciï¿½n y ancho de la columna
						 			   'personal'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
						 			   'pcp'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
						 			   'pmp'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
						 			   'bic'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
						 			   'utiles'=>array('justification'=>'right','width'=>60),
										'ma'=>array('justification'=>'right','width'=>60), // Justificaciï¿½n y ancho de la columna
						 		'sfu'=>array('justification'=>'right','width'=>60),
						 		'pco'=>array('justification'=>'right','width'=>60),
						 		'opt'=>array('justification'=>'right','width'=>60),
						 		'sir'=>array('justification'=>'right','width'=>60),
						 		'lma'=>array('justification'=>'right','width'=>60),
						 			   'total'=>array('justification'=>'right','width'=>70))); // Justificaciï¿½n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
        $io_pdf->setColor(0,0,0);
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();
//	require_once("../sigesp_sno.php");
//$io_sno3=new sigesp_sno();
	//----------------------------------------------------  Parï¿½metros del encabezado  -----------------------------------------------
	$ls_titulo="<b>Retenciones y Aportes Patronales</b>";
	//--------------------------------------------------  Parï¿½metros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codconc=$io_fun_nomina->uf_obtenervalor_get("codconc","");
	$ls_nomcon=$io_fun_nomina->uf_obtenervalor_get("nomcon","");
	$ls_anodes=$io_fun_nomina->uf_obtenervalor_get("anodes","");
	$ls_mesdes=$io_fun_nomina->uf_obtenervalor_get("mesdes","");
	$ls_anohas=$io_fun_nomina->uf_obtenervalor_get("anohas","");
	$ls_meshas=$io_fun_nomina->uf_obtenervalor_get("meshas","");
	$ls_perdes=$io_fun_nomina->uf_obtenervalor_get("perdes","");
	$ls_perhas=$io_fun_nomina->uf_obtenervalor_get("perhas","");
	//$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tipo=$io_fun_nomina->uf_obtenervalor_get("rdbtipo","");
	
	global $ls_tiporeporte;
	$ls_bolivares="Bs.";
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_snorh_class_reportbsf.php");
		$io_report=new sigesp_snorh_class_reportbsf();
		$ls_bolivares="Bs.F.";
	}
	$ls_rango= "Nóminas: ".$ls_codnomdes;
	if($ls_anodes==$ls_anohas)
	{
		$ls_des_ano=$ls_anodes;
	}
	else
	{
		$ls_des_ano=$ls_anodes." al ".$ls_anohas;
	}
	if($ls_mesdes==$ls_meshas)
	{
		$ls_des_mes=$io_fecha->uf_load_nombre_mes($ls_mesdes);
	}
	else
	{
		$ls_des_mes=$io_fecha->uf_load_nombre_mes($ls_mesdes)." a ".$io_fecha->uf_load_nombre_mes($ls_meshas);
	}
	if($ls_perdes==$ls_perhas)
	{
		$ls_des_periodo=$ls_perdes;
	}
	else
	{
		$ls_des_periodo=$ls_perdes." al ".$ls_perhas;
	}
	
	$ls_periodo= "Año: ".$ls_des_ano." Mes: ".$ls_des_mes." - Período ".$ls_des_periodo;
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_rango,$ls_periodo); // Seguridad de Reporte
	if($lb_valido)
	{
		//$lb_valido=$io_report->uf_aportepatronal_personal($ls_codnomdes,$ls_codnomdes,$ls_anodes,$ls_mesdes,$ls_anohas,$ls_meshas,
			//											  $ls_perdes,$ls_perhas,$ls_codconc,$ls_conceptocero,"","",1); // Cargar el DS con los datos del reporte
			
		
		
		
		$ld_fecpro='01/'.$ls_mesdes.'/'.$ls_anocur;
		$la_fpa=split(",",$ls_concepto_fpa);
		$la_fpj=split(",",$ls_concepto_fpj);
		$la_lph=split(",",$ls_concepto_lph);
		$li_total_fpa=count($la_fpa);
		$li_total_fpj=count($la_fpj);
		$li_total_lph=count($la_lph);
			
		$ls_codconcapo="";
		$ls_codconcded="";
		$ls_codconcpcp="";
		$ls_codconcpmp="";
		$ls_codconcbic="";
		$ls_codconcuti="";

		

		$nomina[]="";
		$conceptos[]="";



		$als_codnom=split("-",$ls_codnomdes);
		$totoalnomina=count($als_codnom);
		$ls_codnom="";
		$ls_sql="";
		$ls_sql2="";

		
	for($li_i=0;$li_i<$totoalnomina;$li_i++)
		{


			$nomina=$als_codnom[$li_i];


		if($ls_tipo=='1'){
				$ls_concepto_lph2=trim($io_report->uf_select_config("SNO","CONFIG","FAOV_".$als_codnom[$li_i],"XXXXXXXX","C"));

				$ls_codconc=$ls_concepto_lph2;
				$als_codconc=split("-",$ls_codconc);
				$totoalconcepto=count($als_codconc);
				$ls_codconc="";
				for($li_m=0;$li_m<$totoalconcepto;$li_m++)
				{

					if($ls_codconc==''){
						$ls_codconc="'".$als_codconc[$li_m]."'";
					}else{
						$ls_codconc=$ls_codconc.",'".$als_codconc[$li_m]."'";
					}
				}

				$conceptofaov[$li_i]=$ls_codconc;
				//$ls_metodo_lph=rtrim($io_metodo->io_sno->uf_select_config("SNO","NOMINA","METODO LPH","SIN METODO","C"));

				$ls_sql1111=$io_report->uf_aportepatronal_personal2($ls_codconc,$nomina,$nomina,$ls_anodes,$ls_mesdes,$ls_meshas,$ls_perdes,$ls_perhas);
			
	
				if($ls_sql2==""){
					$ls_sql2=$ls_sql1111."  ";
				}else{
					$ls_sql2=$ls_sql2." union  ".$ls_sql1111;
				}


					
			}else{
				if($ls_tipo=='2'){
					$ls_concepto_fpj2=trim($io_report->uf_select_config("SNO","CONFIG","FJU_".$als_codnom[$li_i],"XXXXXXXX","C"));
					//	echo "<br>".$ls_concepto_lph2;

					$ls_codconc=$ls_concepto_fpj2;
					$als_codconc=split("-",$ls_codconc);
					$totoalconcepto=count($als_codconc);
					$ls_codconc="";
					for($li_m=0;$li_m<$totoalconcepto;$li_m++)
					{

						if($ls_codconc==''){
							$ls_codconc="'".$als_codconc[$li_m]."'";
						}else{
							$ls_codconc=$ls_codconc.",'".$als_codconc[$li_m]."'";
						}
					}
					$conceptofju[$li_i]=$ls_codconc;

					$ls_sql1111=$io_report->uf_aportepatronal_personal2($ls_codconc,$nomina,$nomina,$ls_anodes,$ls_mesdes,$ls_meshas,$ls_perdes,$ls_perhas);


					if($ls_sql2==""){
						$ls_sql2=$ls_sql1111."  ";
					}else{
						$ls_sql2=$ls_sql2." union  ".$ls_sql1111;
					}
					//echo "<br>".$ls_codconc;

				}else{
					if($ls_tipo=='3'){
							
					/****codigos en la nomina por concepto y tipo de nomina*////
						$ls_codconcapo=trim($io_report->uf_select_config("SNO","CONFIG","CAH_APO_".$als_codnom[$li_i],"XXXXXXXX","C"));//APORTE PATRONAL
						$ls_codconcded=trim($io_report->uf_select_config("SNO","CONFIG","CAH_DED_".$als_codnom[$li_i],"XXXXXXXX","C"));//DEDUCCION EMPLEADO
						$ls_codconcpcp=trim($io_report->uf_select_config("SNO","CONFIG","CAH_PCP_".$als_codnom[$li_i],"XXXXXXXX","C"));//PRESTAMO CORTO PLAZO
						$ls_codconcpmp=trim($io_report->uf_select_config("SNO","CONFIG","CAH_PMP_".$als_codnom[$li_i],"XXXXXXXX","C"));//PRESTAMO MEDIANO PLAZO
						$ls_codconcbic=trim($io_report->uf_select_config("SNO","CONFIG","CAH_BIC_".$als_codnom[$li_i],"XXXXXXXX","C"));//PRESTAMO BICENTENARIO
						$ls_codconcuti=trim($io_report->uf_select_config("SNO","CONFIG","CAH_UTI_".$als_codnom[$li_i],"XXXXXXXX","C"));//UTILES
						$ls_codconcma=trim($io_report->uf_select_config("SNO","CONFIG","CAH_MA_".$als_codnom[$li_i],"XXXXXXXX","C"));//MUTUO AUXILIO
						$ls_codconcsfu=trim($io_report->uf_select_config("SNO","CONFIG","CAH_SFU_".$als_codnom[$li_i],"XXXXXXXX","C"));//SERVICIO FUNERARIO
						$ls_codconcpco=trim($io_report->uf_select_config("SNO","CONFIG","CAH_PCO_".$als_codnom[$li_i],"XXXXXXXX","C"));//PRESTAMO COMERCIAL
						$ls_codconclma=trim($io_report->uf_select_config("SNO","CONFIG","CAH_LMA_".$als_codnom[$li_i],"XXXXXXXX","C"));//LINEA MARRON
						$ls_codconcopt=trim($io_report->uf_select_config("SNO","CONFIG","CAH_OPT_".$als_codnom[$li_i],"XXXXXXXX","C"));//OPTICA
						$ls_codconcsir=trim($io_report->uf_select_config("SNO","CONFIG","CAH_SIR_".$als_codnom[$li_i],"XXXXXXXX","C"));//SIRAGON
						
						
						
						/***codigos ante caja de ahorro******///
						
						
					
						
						$ls_codapo=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_APO","XXXXXXXX","C"));//APORTE PATRONAL
						$ls_codded=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_DED","XXXXXXXX","C"));//DEDUCCION EMPLEADO
						$ls_codpcp=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_PCP","XXXXXXXX","C"));//PRESTAMO CORTO PLAZO
						$ls_codpmp=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_PMP","XXXXXXXX","C"));//PRESTAMO MEDIANO PLAZO
						$ls_codbic=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_BIC","XXXXXXXX","C"));//PRESTAMO BICENTENARIO
						$ls_coduti=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_UTI","XXXXXXXX","C"));//UTILES
						$ls_codma=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_MA","XXXXXXXX","C"));//MUTUO AUXILIO
						$ls_codsfu=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_SFU","XXXXXXXX","C"));//SERVICIO FUNERARIO
						$ls_codpco=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_PCO","XXXXXXXX","C"));//PRESTAMO COMERCIAL
						$ls_codlma=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_LMA","XXXXXXXX","C"));//LINEA MARRON
						$ls_codopt=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_OPT","XXXXXXXX","C"));//OPTICA
						$ls_codsir=trim($io_report->uf_select_config("SNO","CONFIG","CAH_COD_SIR","XXXXXXXX","C"));//SIRAGON
						
						
						
						$todosconcepto=	"";	

						$als_codconcapo=split("-",$ls_codconcapo);
						$totoalconceptoapo=count($als_codconcapo);
						$ls_codconcapo="''";
						for($li_m=0;$li_m<$totoalconceptoapo;$li_m++)
						{

							if($ls_codconcapo==''){
								$ls_codconcapo="'".$als_codconcapo[$li_m]."'";
							}else{
								$ls_codconcapo=$ls_codconcapo.",'".$als_codconcapo[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcapo[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcapo[$li_m]."'";
							}
						}


						$conceptoapo[$li_i]=$ls_codconcapo;

						$als_codconcded=split("-",$ls_codconcded);
						$totoalconceptoded=count($als_codconcded);
						$ls_codconcded="''";
						for($li_m=0;$li_m<$totoalconceptoded;$li_m++)
						{

							if($ls_codconcded==''){
								$ls_codconcded="'".$als_codconcded[$li_m]."'";
							}else{
								$ls_codconcded=$ls_codconcded.",'".$als_codconcded[$li_m]."'";

							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcded[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcded[$li_m]."'";
							}
						}

						$conceptoded[$li_i]=$ls_codconcded;



						$als_codconcpcp=split("-",$ls_codconcpcp);
						$totoalconceptopcp=count($als_codconcpcp);
						$ls_codconcpcp="''";
						for($li_m=0;$li_m<$totoalconceptopcp;$li_m++)
						{

							if($ls_codconcpcp==''){
								$ls_codconcpcp="'".$als_codconcpcp[$li_m]."'";
							}else{
								$ls_codconcpcp=$ls_codconcpcp.",'".$als_codconcpcp[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcpcp[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcpcp[$li_m]."'";
							}
						}


						$conceptopcp[$li_i]=$ls_codconcpcp;

						$als_codconcpmp=split("-",$ls_codconcpmp);
						$totoalconceptopmp=count($als_codconcpmp);
						$ls_codconcpmp="''";
						for($li_m=0;$li_m<$totoalconceptopmp;$li_m++)
						{

							if($ls_codconcpmp==''){
								$ls_codconcpmp="'".$als_codconcpmp[$li_m]."'";
							}else{
								$ls_codconcpmp=$ls_codconcpmp.",'".$als_codconcpmp[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcpmp[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcpmp[$li_m]."'";
							}
						}



						$conceptopmp[$li_i]=$ls_codconcpmp;

						$als_codconcbic=split("-",$ls_codconcbic);
						$totoalconceptobic=count($als_codconcbic);
						$ls_codconcbic="''";
						for($li_m=0;$li_m<$totoalconceptobic;$li_m++)
						{

							if($ls_codconcbic==''){
								$ls_codconcbic="'".$als_codconcbic[$li_m]."'";
							}else{
								$ls_codconcbic=$ls_codconcbic.",'".$als_codconcbic[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcbic[$li_i]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcbic[$li_m]."'";
							}
						}


						$conceptobic[$li_i]=$ls_codconcbic;

						$als_codconcuti=split("-",$ls_codconcuti);
						$totoalconceptouti=count($als_codconcuti);
						$ls_codconcuti="''";
						for($li_m=0;$li_m<$totoalconceptouti;$li_m++)
						{

							if($ls_codconcuti==''){
								$ls_codconcuti="'".$als_codconcuti[$li_m]."'";
							}else{
								$ls_codconcuti=$ls_codconcuti.",'".$als_codconcuti[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcuti[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcuti[$li_m]."'";
							}
						}
						
						$conceptouti[$li_i]=$ls_codconcuti;
						
						
						
						$als_codconcma=split("-",$ls_codconcma);
						$totoalconceptoma=count($als_codconcma);


						$ls_codconcma="''";
						for($li_m=0;$li_m<$totoalconceptoma;$li_m++)
						{



							if($ls_codconcma==''){
								$ls_codconcma="'".$als_codconcma[$li_m]."'";
							}else{
								$ls_codconcma=$ls_codconcma.",'".$als_codconcma[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcma[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcma[$li_m]."'";
							}


						}
						
						$conceptoma[$li_i]=$ls_codconcma;
						
						
						$als_codconcsfu=split("-",$ls_codconcsfu);
						$totoalconceptosfu=count($als_codconcsfu);
						
						$ls_codconcsfu="''";
						for($li_m=0;$li_m<$totoalconceptosfu;$li_m++)
						{



							if($ls_codconcsfu==''){
								$ls_codconcsfu="'".$als_codconcsfu[$li_m]."'";
							}else{
								$ls_codconcsfu=$ls_codconcsfu.",'".$als_codconcsfu[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcsfu[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcsfu[$li_m]."'";
							}


						}
						
						$conceptosfu[$li_i]=$ls_codconcsfu;
						
						
						
						
						
						$als_codconcpco=split("-",$ls_codconcpco);
						$totoalconceptopco=count($als_codconcpco);
						$ls_codconcpco="''";
						for($li_m=0;$li_m<$totoalconceptopco;$li_m++)
						{



							if($ls_codconcpco==''){
								$ls_codconcpco="'".$als_codconcpco[$li_m]."'";
							}else{
								$ls_codconcpco=$ls_codconcpco.",'".$als_codconcpco[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcpco[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcpco[$li_m]."'";
							}


						}
						
						$conceptopco[$li_i]=$ls_codconcpco;
						
						
						
						
						$als_codconclma=split("-",$ls_codconclma);
						$totoalconceptolma=count($als_codconclma);
						
						$ls_codconclma="''";
						for($li_m=0;$li_m<$totoalconceptolma;$li_m++)
						{



							if($ls_codconclma==''){
								$ls_codconclma="'".$als_codconclma[$li_m]."'";
							}else{
								$ls_codconclma=$ls_codconclma.",'".$als_codconclma[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconclma[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconclma[$li_m]."'";
							}


						}
						
						$conceptolma[$li_i]=$ls_codconclma;
						
						
						
						
						
						$als_codconcopt=split("-",$ls_codconcopt);
						$totoalconceptoopt=count($als_codconcopt);
						$ls_codconcopt="''";
						for($li_m=0;$li_m<$totoalconceptoopt;$li_m++)
						{



							if($ls_codconcopt==''){
								$ls_codconcopt="'".$als_codconcopt[$li_m]."'";
							}else{
								$ls_codconcopt=$ls_codconcopt.",'".$als_codconcopt[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcopt[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcopt[$li_m]."'";
							}


						}
						
						$conceptoopt[$li_i]=$ls_codconcopt;
						
						
						
						
						$als_codconcsir=split("-",$ls_codconcsir);
						$totoalconceptosir=count($als_codconcsir);
						$ls_codconcsir="''";
						for($li_m=0;$li_m<$totoalconceptosir;$li_m++)
						{



							if($ls_codconcsir==''){
								$ls_codconcsir="'".$als_codconcsir[$li_m]."'";
							}else{
								$ls_codconcsir=$ls_codconcsir.",'".$als_codconcsir[$li_m]."'";
							}
							if($todosconcepto==''){
								$todosconcepto="'".$als_codconcsir[$li_m]."'";
							}else{
								$todosconcepto=$todosconcepto.",'".$als_codconcsir[$li_m]."'";
							}


						}
						
						$conceptosir[$li_i]=$ls_codconcsir;
						
						
				
$ls_sql1111=$io_report->uf_aportepatronal_personal3($ls_codconcapo,$ls_codconcded,$ls_codconcpcp,$ls_codconcpmp,$ls_codconcbic,$ls_codconcuti,$ls_codconcma,$als_codnom[$li_i],$als_codnom[$li_i],$ls_anodes,$ls_mesdes,$ls_meshas,$ls_perdes,$ls_perhas,$todosconcepto,$ls_codconcsfu,$ls_codconcpco,$ls_codconclma,$ls_codconcopt,$ls_codconcsir);
					


					if($ls_sql2==""){
						$ls_sql2=$ls_sql1111."  ";
					}else{
						$ls_sql2=$ls_sql2." union  ".$ls_sql1111;
					}

					}
				}
			}

			/*$als_codconc=split("-",$ls_codconc);
			 $totoalconcepto=count($als_codconc);
			 $ls_codconc="";
			 for($li_i=0;$li_i<$totoalconcepto;$li_i++)
			 {

				if($ls_codconc==''){
				$ls_codconc="'".$als_codconc[$li_i]."'";
				}else{
				$ls_codconc=$ls_codconc.",'".$als_codconc[$li_i]."'";
				}
				}*/

			$conceptoconc[$li_i]=$ls_codconcconc;


		}
$ls_sql2=$ls_sql2."  order by 3";

//echo "<br>".$ls_sql2;
//die();
$valido=$io_report->uf_generar_reporte($ls_sql2);
		
		
	//	echo "<br>55555".$ls_sql2;
	
		
	}
	if($lb_valido==false) // Existe algï¿½n error ï¿½ no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else  // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		//$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	//	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		//$io_pdf->ezSetCmMargins(3.60,2.5,3,3); // Configuraciï¿½n de los margenes en centï¿½metros
		
		
		
	if($ls_tipo=='3'){
	$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(2.0,2.0,2,2); // Configuraciï¿½n de los margenes en centï¿½metros

		
		uf_print_encabezado_paginacah($ls_titulo,$ls_rango,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la pï¿½gina
}else{
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(2.0,2.0,2,2); // Configuraciï¿½n de los margenes en centï¿½metros

		
		uf_print_encabezado_pagina($ls_titulo,$ls_rango,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la pï¿½gina
}
		
		
		

	//	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el nï¿½mero de pï¿½gina
		
if($ls_tipo=='3'){
					$io_pdf->ezStartPageNumbers(912,10,10,'','',1); // Insertar el nï¿½mero de pï¿½gina
	
}else{
					$io_pdf->ezStartPageNumbers(550,10,10,'','',1); // Insertar el nï¿½mero de pï¿½gina
	
}
		

if($ls_tipo=='3'){
	$ls_nomcon="Caja de Ahorro";
}else{
	if($ls_tipo=='1'){
		$ls_nomcon="Banavih";
	}else{
		if($ls_tipo=='2'){
			$ls_nomcon="FJU";
		}
	}
}



if($ls_tipo=='3'){
		uf_print_cabeceracah($ls_codconc,$ls_nomcon,$io_pdf); // Imprimimos la cabecera del registro
}else{
		uf_print_cabecera($ls_codconc,$ls_nomcon,$io_pdf); // Imprimimos la cabecera del registro
}
	

		$li_totrow=$io_report->DS->getRowCount("cedper");
		$li_totper=0;
		$li_totpat=0;
		$li_totalgeneral=0;
		$li_totper=0;
			$li_totpat=0;
			$li_totpcp=0;
			$li_totpmp=0;
			$li_totbic=0;
			$li_totuti=0;
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			$ls_cedper=$io_report->DS->data["cedper"][$li_i];
			$ls_nomper=$io_report->DS->data["apeper"][$li_i].", ".$io_report->DS->data["nomper"][$li_i];
			$li_personal=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["personal"][$li_i]));
			$li_patron=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["patron"][$li_i]));
if($ls_tipo=='3'){
			$li_pcp=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["pcp"][$li_i]));
			$li_pmp=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["pmp"][$li_i]));
			$li_bic=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["bicentenario"][$li_i]));
			$li_uti=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["utiles"][$li_i]));
			$li_ma=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["ma"][$li_i]));
			
			$li_sfu=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["sfu"][$li_i]));
			$li_pco=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["pco"][$li_i]));
			$li_opt=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["opt"][$li_i]));
			$li_sir=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["sir"][$li_i]));
			$li_lma=$io_fun_nomina->uf_formatonumerico(abs($io_report->DS->data["lma"][$li_i]));
			
			
			
			$li_total=abs($io_report->DS->data["acumulado"][$li_i]);
			$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			$li_totper=$li_totper+abs($io_report->DS->data["personal"][$li_i]);
			$li_totpat=$li_totpat+abs($io_report->DS->data["patron"][$li_i]);
			$li_totpcp=$li_totpcp+abs($io_report->DS->data["pcp"][$li_i]);
			$li_totpmp=$li_totpmp+abs($io_report->DS->data["pmp"][$li_i]);
			$li_totbic=$li_totbic+abs($io_report->DS->data["bicentenario"][$li_i]);
			$li_totuti=$li_totuti+abs($io_report->DS->data["utiles"][$li_i]);
			$li_totma=$li_totma+abs($io_report->DS->data["ma"][$li_i]);
			
			$li_totsfu=$li_totsfu+abs($io_report->DS->data["sfu"][$li_i]);
			$li_totpco=$li_totpco+abs($io_report->DS->data["pco"][$li_i]);
			$li_totopt=$li_totopt+abs($io_report->DS->data["opt"][$li_i]);
			$li_totsir=$li_totsir+abs($io_report->DS->data["sir"][$li_i]);
			$li_totlma=$li_totlma+abs($io_report->DS->data["lma"][$li_i]);
			
			
			
			$li_totalgeneral=$li_totalgeneral+abs($io_report->DS->data["acumulado"][$li_i]);;
			$la_data[$li_i]=array('nro'=>$li_i,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'personal'=>$li_personal,'patron'=>$li_patron,'pcp'=>$li_pcp,'pmp'=>$li_pmp,'bic'=>$li_bic,'uti'=>$li_uti,'ma'=>$li_ma,'sfu'=>$li_sfu,'pco'=>$li_pco,'opt'=>$li_opt,'lma'=>$li_lma,'sir'=>$li_sir,'total'=>$li_total);
}else{
			$li_total=abs($io_report->DS->data["personal"][$li_i]+$io_report->DS->data["patron"][$li_i]);
			$li_total=$io_fun_nomina->uf_formatonumerico($li_total);
			$li_totper=$li_totper+abs($io_report->DS->data["personal"][$li_i]);
			$li_totpat=$li_totpat+abs($io_report->DS->data["patron"][$li_i]);
			$li_totalgeneral=$li_totalgeneral+abs($io_report->DS->data["acumulado"][$li_i]);;
			$la_data[$li_i]=array('nro'=>$li_i,'cedula'=>$ls_cedper,'nombre'=>$ls_nomper,'personal'=>$li_personal,'patron'=>$li_patron,'total'=>$li_total);
}
		}
		$io_report->DS->resetds("cedper");
if($ls_tipo=='3'){
		uf_print_detallecah($la_data,$io_pdf); // Imprimimos el detalle 
}else{
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
}
		$li_totper=$io_fun_nomina->uf_formatonumerico($li_totper);
		$li_totpat=$io_fun_nomina->uf_formatonumerico($li_totpat);
		$li_totalgeneral=$io_fun_nomina->uf_formatonumerico($li_totalgeneral);
if($ls_tipo=='3'){
		uf_print_piecabeceracah($li_totper,$li_totpat,$li_totpcp,$li_totpmp,$li_totbic,$li_totuti,$li_totma,$li_totsfu,$li_totpco,$li_totopt,$li_totsir,$li_totlma,$li_totalgeneral,$io_pdf); // Imprimimos el fin del reporte
}else{
		uf_print_piecabecera($li_totper,$li_totpat,$li_totalgeneral,$io_pdf); // Imprimimos el fin del reporte
}

		if($lb_valido) // Si no ocurrio ningï¿½n error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresiï¿½n de los nï¿½meros de pï¿½gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else // Si hubo algï¿½n error
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
