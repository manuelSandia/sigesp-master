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
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_periodo // Descripci�n del per�odo
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Gener� el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_pagonomina.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hpagonomina.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_periodo // Descripci�n del per�odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,730,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],20,520,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=430-($li_tm/2);
		$io_pdf->addText($tm,510,10,$as_periodo); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=406-($li_tm/2);
		$io_pdf->addText($tm,520,12,"<b>".$as_desnom."</b>"); // Agregar el t�tulo
		
		$io_pdf->addText(702,570,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(708,563,7,date("h:i a")); // Agregar la Hora
		$io_pdf->addText(700,555,7,"Sistema Sigesp"); // TITULO
		
		$io_pdf->addText(108,563,7,"INSTITUTO TECNOLOGICO DE TECNOLOGIA"); // TITULO
		$io_pdf->addText(128,553,7,"'' JACINTO NAVARRO VALLENILLA ''"); // TITULO
		$io_pdf->addText(138,543,7,"CARUPANO EDO.SUCRE"); // TITULO
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
		$la_data=array(array('codigop'=>'<b>C�DIGO</b>','cedula'=>'<b>C�DULA</b>','nombre'=>'<b>APELLIDOS Y NOMBRE</b>','cargo'=>'<b>CARGO</b>',
							 'unidad'=>'<b>UNIDAD DE ADSCRIPCI�N</b>','firma'=>'<b>FIRMA</b>'));
		$la_columnas=array('codigop'=>'',
						   'cedula'=>'',
						   'nombre'=>'',
						   'cargo'=>'',
						   'unidad'=>'',
						   'firma'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 10,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>400, // Orientaci�n de la tabla
						 'cols'=>array('codigop'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>200), // Justificaci�n y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>120),// Justificaci�n y ancho de la columna
									   'unidad'=>array('justification'=>'center','width'=>160), // Justificaci�n y ancho de la columna
						 			   'firma'=>array('justification'=>'center','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
	}// end function uf_print_encabezadopagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($num,$as_codper,$as_cedper,$as_apenomper,$as_descar,$as_desuniadm,$ad_fecingper,$as_codcueban,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // c�dula del personal
		//	    		   as_apenomper // apellidos y nombre del personal
		//	    		   as_descar // descripci�n del cargo
		//	    		   as_desuniadm // descripci�n de la unidad administrativa
		//	    		   ad_fecingper // fecha de ingreso
		//	    		   as_codcueban // c�digo de lla cuenta bancaria
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($num===1)
		{
			$io_pdf->addText(20,495,7,"--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"); // TITULO
			$io_pdf->addText(20,483,7,"--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"); // TITULO
		}
		$la_data=array(array('codigop'=>$as_codper,'cedula'=>$as_cedper,'nombre'=>$as_apenomper,'cargo'=>$as_descar,'unidad'=>$as_desuniadm,'firma'=>''));
		$la_columnas=array('codigop'=>'',
						   'cedula'=>'',
						   'nombre'=>'',
						   'cargo'=>'',
						   'unidad'=>'',
						   'firma'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 10,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>403, // Orientaci�n de la tabla
						 'cols'=>array('codigop'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>200), // Justificaci�n y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>120),// Justificaci�n y ancho de la columna
									   'unidad'=>array('justification'=>'center','width'=>160), // Justificaci�n y ancho de la columna
						 			   'firma'=>array('justification'=>'center','width'=>120))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
		$la_data2=array(array('fecingper'=>'<b>FECHA INGRESO      </b>'.$ad_fecingper."      ",'cuebanper'=>'<b>CUENTA BANCARIA      </b>'.$as_codcueban));
		$la_columnas=array('fecingper'=>'',
						   'cuebanper'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 10,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>212, // Orientaci�n de la tabla
						 'cols'=>array('fecingper'=>array('justification'=>'left','width'=>160), // Justificaci�n y ancho de la columna
						 			   'cuebanper'=>array('justification'=>'left','width'=>170))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data2,$la_columnas,'',$la_config);
		
		$io_pdf->ezSetDy(-8);
		$la_data3=array(array('line'=>'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
		$la_columnas=array('line'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 10,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>422, // Orientaci�n de la tabla
						 'rowGap' =>-2 ,
						 'cols'=>array('line'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data3,$la_columnas,'',$la_config);
			
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(11);
		$la_columnas=array('codigo'=>'<b>C�DIGO</b>',
						   'nombre'=>'<b>               CONCEPTO</b>',
						   'asignacion'=>'<b>ASIGNACI�N        </b>',						   
						   'deduccion'=>'<b>DEDUCCI�N        </b>',
						   'neto'=>'<b>            NETO A COBRAR</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>350, // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>199), // Justificaci�n y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>90), // Justificaci�n y ancho de la columna
									   'deduccion'=>array('justification'=>'right','width'=>90), // Justificaci�n y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
		$la_data3=array(array('line'=>'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
		$la_columnas=array('line'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 10,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>600, // Orientaci�n de la tabla
						 'rowGap' =>-2 ,
						 'cols'=>array('line'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data3,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($as_apenomper,$ai_totalasignacion,$ai_totaldeduccion,$ai_totalaporte,$ai_total_neto,$as_obsrecper,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_totalasignacion // Total Asignaci�n
		//	   			   ai_totaldeduccion // Total Deduccci�n
		//	   			   ai_totalaporte // Total aporte
		//	   			   ai_total_neto // Total Neto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 26/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('totales'=>'<b>Totales...   '.$as_apenomper.'</b>','asignacion'=>$ai_totalasignacion,'deduccion'=>$ai_totaldeduccion,
							 'neto'=>$ai_total_neto,'firma'=>'---------------------------------------------------------'));
		$la_columna=array('totales'=>'','asignacion'=>'','deduccion'=>'','neto'=>'','firma'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>430, // Orientaci�n de la tabla
						 'cols'=>array('totales'=>array('justification'=>'center','width'=>259), // Justificaci�n y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>90), // Justificaci�n y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>90), // Justificaci�n y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>90), // Justificaci�n y ancho de la columna
									   'firma'=>array('justification'=>'right','width'=>160))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data3=array(array('line'=>'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
		$la_columnas=array('line'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 10,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>600, // Orientaci�n de la tabla
						 'rowGap' =>-2 ,
						 'cols'=>array('line'=>array('justification'=>'left','width'=>660))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data3,$la_columnas,'',$la_config);
	}// end function uf_print_piecabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piepagina($ai_totasi,$ai_totded,$ai_totapo,$ai_totgeneral,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piepagina
		//		   Access: private 
		//	    Arguments: ai_totasi // Total de Asignaciones
		//	   			   ai_totded // Total de Deducciones
		//	   			   ai_totapo // Total de Aportes
		//	   			   ai_totgeneral // Total de Neto a Pagar
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 25/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>500); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('titulo'=>'<b>Total N�mina '.$ls_bolivares.': </b>','asignacion'=>$ai_totasi,
							 'deduccion'=>$ai_totded,'neto'=>$ai_totgeneral));
		$la_columna=array('titulo'=>'','asignacion'=>'','deduccion'=>'','neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 6, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'shadeCol'=>array((224/255),(224/255),(224/255)), // Color de la sombra
						 'shadeCol2'=>array((224/255),(224/255),(224/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M�ximo de la tabla
						 'xPos'=>370, // Orientaci�n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>220), // Justificaci�n y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>90), // Justificaci�n y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	if($_SESSION["la_nomina"]["tiponomina"]=="NORMAL")
	{
		require_once("sigesp_sno_class_report.php");
		$io_report=new sigesp_sno_class_report();
		$li_tipo=1;
	}
	if($_SESSION["la_nomina"]["tiponomina"]=="HISTORICA")
	{
		require_once("sigesp_sno_class_report_historico.php");
		$io_report=new sigesp_sno_class_report_historico();
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
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Reporte General de Pago</b>";
	$ls_periodo="<b>Per�odo Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_codubifis=$io_fun_nomina->uf_obtenervalor_get("codubifis","");
	$ls_codpai=$io_fun_nomina->uf_obtenervalor_get("codpai","");
	$ls_codest=$io_fun_nomina->uf_obtenervalor_get("codest","");
	$ls_codmun=$io_fun_nomina->uf_obtenervalor_get("codmun","");
	$ls_codpar=$io_fun_nomina->uf_obtenervalor_get("codpar","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	$ls_pagobanco=$io_fun_nomina->uf_obtenervalor_get("pagobanco","");
	$ls_pagocheque=$io_fun_nomina->uf_obtenervalor_get("pagocheque","");						
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_pagonomina_personal($ls_codperdes,$ls_codperhas,$ls_conceptocero,$ls_conceptoreporte,$ls_conceptop2,
													  $ls_codubifis,$ls_codpai,$ls_codest,$ls_codmun,$ls_codpar,$ls_subnomdes,$ls_subnomhas,$ls_orden,
													  $ls_pagobanco,$ls_pagocheque); // Cargar el DS con los datos de la cabecera del reporte
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
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(4,2.5,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(730,50,10,'','',1); // Insertar el n�mero de p�gina
		$io_pdf->FitWindow=true;
		$li_totrow=$io_report->rs_data->RecordCount();
		$li_totasi=0;
		$li_totded=0;
		$li_totapo=0;
		$li_totgeneral=0;
		$num=1;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
			$li_totalasignacion=0;
			$li_totaldeduccion=0;
			$li_totalaporte=0;
			$li_total_neto=0;
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_cedper=$io_report->rs_data->fields["cedper"];
			$ls_apenomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			$ls_descar=$io_report->rs_data->fields["descar"];
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecingper"]);
			$ls_codcueban=$io_report->rs_data->fields["codcueban"];
			$ls_obsrecper=$io_report->rs_data->fields["obsrecper"];
			uf_print_cabecera($num,$ls_codper,$ls_cedper,$ls_apenomper,$ls_descar,$ls_desuniadm,$ld_fecingper,$ls_codcueban,$io_pdf); // Imprimimos la cabecera del registro
			$num++;
			$lb_valido=$io_report->uf_pagonomina_conceptopersonal($ls_codper,$ls_conceptocero,$ls_tituloconcepto,$ls_conceptoreporte,$ls_conceptop2); // Obtenemos el detalle del reporte
			if($lb_valido)
			{				
				$li_totrow_res=$io_report->rs_data_detalle->RecordCount();
				$li_s=1;
				while (!$io_report->rs_data_detalle->EOF)
				{
					$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
					$ls_frevarcon=$io_report->rs_data_detalle->fields["frevarcon"];
					$ls_frecuencia="";
					if($ls_frevarcon==1)
					{
						$ls_frecuencia="  (*)";
					}					
					
					$ls_repconsunicon=$io_report->rs_data_detalle->fields["repconsunicon"];
					$ls_consunicon=$io_report->rs_data_detalle->fields["consunicon"];
					
					$ls_cuota="";
					if (($ls_repconsunicon=='1')&&($ls_consunicon!=""))
					{
						$io_report->uf_buscar_cuotas($ls_consunicon,$ls_codper,$ls_cuota);
					}					
					
					$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"].$ls_frecuencia;
					$ls_tipsal=rtrim($io_report->rs_data_detalle->fields["tipsal"]);
					$li_valsal=abs($io_report->rs_data_detalle->fields["valsal"]);
					
					switch($ls_tipsal)
					{
						case "A":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "V1":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "W1":
							$li_totalasignacion=$li_totalasignacion + $li_valsal;
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte=""; 
							break;
							
						case "D":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;
							
						case "V2":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;
							
						case "W2":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "P1":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "V3":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "W3":
							$li_totaldeduccion=$li_totaldeduccion + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=number_format($li_valsal,2,",",".");
							$li_aporte=""; 
							break;

						case "P2":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=number_format($li_valsal,2,",",".");
							break;

						case "V4":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=number_format($li_valsal,2,",",".");
							break;

						case "W4":
							$li_totalaporte=$li_totalaporte + $li_valsal;
							$li_asignacion=""; 
							$li_deduccion=""; 
							$li_aporte=number_format($li_valsal,2,",",".");
							break;

						case "R":
							$li_asignacion=number_format($li_valsal,2,",",".");
							$li_deduccion=""; 
							$li_aporte="";
							break;
					}
					$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'cuota'=>$ls_cuota,
					                      'asignacion'=>$li_asignacion,
										  'deduccion'=>$li_deduccion,'aporte'=>$li_aporte,'neto'=>'');
					$li_s++;
					$io_report->rs_data_detalle->MoveNext();					  
				}
  			    uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle  
				$li_total_neto=$li_totalasignacion-$li_totaldeduccion;
				$li_totasi=$li_totasi+$li_totalasignacion;
				$li_totded=$li_totded+$li_totaldeduccion;
				$li_totapo=$li_totapo+$li_totalaporte;
				$li_totgeneral=$li_totgeneral+$li_total_neto;
				$li_totalasignacion=number_format($li_totalasignacion,2,",",".");
				$li_totaldeduccion=number_format($li_totaldeduccion,2,",",".");
				$li_totalaporte=number_format($li_totalaporte,2,",",".");
				$li_total_neto=number_format($li_total_neto,2,",",".");
				uf_print_piecabecera($ls_apenomper,$li_totalasignacion,$li_totaldeduccion,$li_totalaporte,$li_total_neto,$ls_obsrecper,$io_pdf); // Imprimimos el pie de la cabecera*/
			}
			unset($la_data);
			$io_report->rs_data->MoveNext();
		}
		$li_totasi=number_format($li_totasi,2,",",".");
		$li_totded=number_format($li_totded,2,",",".");
		$li_totapo=number_format($li_totapo,2,",",".");
		$li_totgeneral=number_format($li_totgeneral,2,",",".");
		uf_print_piepagina($li_totasi,$li_totded,$li_totapo,$li_totgeneral,$io_pdf);
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