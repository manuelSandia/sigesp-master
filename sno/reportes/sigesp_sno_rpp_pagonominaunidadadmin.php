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
		//	    Arguments: as_titulo // Tï¿½tulo del Reporte
		//	    		   as_desnom // Descripciï¿½n de la nï¿½mina
		//	    		   as_periodo // Descripciï¿½n del perï¿½odo
		//    Description: funciï¿½n que guarda la seguridad de quien generï¿½ el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 07/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Generï¿½ el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_pagonominaunidadadmin.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hpagonominaunidadadmin.php",$ls_descripcion,$ls_codnom);
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
		//	    Arguments: as_titulo // Tï¿½tulo del Reporte
		//	    		   as_desnom // Descripciï¿½n de la nï¿½mina
		//	    		   as_periodo // Descripciï¿½n del perï¿½odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime los encabezados por pï¿½gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 07/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el tï¿½tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$as_periodo); // Agregar el tï¿½tulo
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,10,$as_desnom); // Agregar el tï¿½tulo
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_minorguniadm,$as_ofiuniadm,$as_uniuniadm,$as_depuniadm,$as_prouniadm,$as_desuniadm,
							   &$io_cabecera,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_minorguniadm // Cï¿½digo de la unidad
		//	   			   as_ofiuniadm // Cï¿½digo de la unidad
		//	   			   as_uniuniadm // Cï¿½digo de la unidad
		//	   			   as_depuniadm // Cï¿½digo de la unidad
		//	   			   as_prouniadm // Cï¿½digo de la unidad
		//	   			   as_desuniadm // Descripciï¿½n de la unidad
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime la cabecera por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 07/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,690,500,$io_pdf->getFontHeight(14));
        $io_pdf->setColor(0,0,0);
		$io_pdf->addText(55,695,9,'<b>Unidad Administrativa</b> '.$as_minorguniadm.'-'.$as_ofiuniadm.'-'.$as_uniuniadm.'-'.$as_depuniadm.'-'.$as_prouniadm.'  '.$as_desuniadm.''); // Agregar el tï¿½tulo
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_personal($as_cedper,$as_apenomper,$as_descar,$as_desuniadm,$ad_fecingper,$as_codcueban,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_cedper // cï¿½dula del personal
		//	    		   as_apenomper // apellidos y nombre del personal
		//	    		   as_descar // descripciï¿½n del cargo
		//	    		   as_desuniadm // descripciï¿½n de la unidad administrativa
		//	    		   ad_fecingper // fecha de ingreso
		//	    		   as_codcueban // cï¿½digo de lla cuenta bancaria
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 07/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('cedula'=>'<b>Cédula</b>','nombre'=>'<b>Nombre</b>','cargo'=>'<b>Cargo</b>','fecha'=>'<b>Fecha Ingreso</b>'));
		$la_columnas=array('cedula'=>'',
						   'nombre'=>'',
						   'cargo'=>'',
						   'fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaï¿½o de Letras
						 'titleFontSize' => 10,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>2, // Sombra entre lï¿½neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>60), // Justificaciï¿½n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>180), // Justificaciï¿½n y ancho de la columna
						 			   'cargo'=>array('justification'=>'center','width'=>180),// Justificaciï¿½n y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>80))); // Justificaciï¿½n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	

		$la_data=array(array('cedula'=>$as_cedper,'nombre'=>$as_apenomper,'cargo'=>$as_descar,'fecha'=>$ad_fecingper));
		$la_columnas=array('cedula'=>'',
						   'nombre'=>'',
						   'cargo'=>'',
						   'fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaï¿½o de Letras
						 'titleFontSize' => 10,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>2, // Sombra entre lï¿½neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'cols'=>array('cedula'=>array('justification'=>'center','width'=>60), // Justificaciï¿½n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>180), // Justificaciï¿½n y ancho de la columna
						 			   'cargo'=>array('justification'=>'left','width'=>180),// Justificaciï¿½n y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>80))); // Justificaciï¿½n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);	
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
		//    Description: funciï¿½n que imprime el detalle del personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 07/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('codigo'=>'<b>Código</b>',
						   'nombre'=>'<b>               Concepto</b>',
						   'asignacion'=>'<b>Asignación        </b>',
						   'deduccion'=>'<b>Deducción        </b>',
						   'aporte'=>'<b>Aporte Patronal  </b>',
						   'neto'=>'<b>Neto            </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaï¿½o de Letras
						 'titleFontSize' => 12,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>1, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificaciï¿½n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>110), // Justificaciï¿½n y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>80))); // Justificaciï¿½n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piecabecera($ai_totalasignacion,$ai_totaldeduccion,$ai_totalaporte,$ai_total_neto,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piecabecera
		//		   Access: private 
		//	    Arguments: ai_totalasignacion // Total Asignaciï¿½n
		//	   			   ai_totaldeduccion // Total Deduccciï¿½n
		//	   			   ai_totalaporte // Total aporte
		//	   			   ai_total_neto // Total Neto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 07/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('totales'=>'<b>Totales '.$ls_bolivares.'</b>','asignacion'=>$ai_totalasignacion,'deduccion'=>$ai_totaldeduccion,
							 'aporte'=>$ai_totalaporte,'neto'=>$ai_total_neto));
		$la_columna=array('totales'=>'','asignacion'=>'','deduccion'=>'','aporte'=>'','neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaï¿½o de Letras
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
						 'cols'=>array('totales'=>array('justification'=>'right','width'=>180), // Justificaciï¿½n y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>80))); // Justificaciï¿½n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'width'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center'); // Orientaciï¿½n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_piecabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piepagina($ai_totasi,$ai_totded,$ai_totapo,$ai_totgeneral,$as_desuniadm,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piepagina
		//		   Access: private 
		//	    Arguments: ai_totasi // Total de Asignaciones
		//	   			   ai_totded // Total de Deducciones
		//	   			   ai_totapo // Total de Aportes
		//	   			   ai_totgeneral // Total de Neto a Pagar
		//	   			   as_desuniadm // Descripciï¿½n Unidad Administrativa
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funciï¿½n que imprime el fin de la cabecera
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaciï¿½n: 07/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaï¿½o de Letras
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>0, // Sombra entre lï¿½neas
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'width'=>500); // Ancho Mï¿½ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('titulo'=>'<b>Total '.$ls_bolivares.' '.$as_desuniadm.': </b>','asignacion'=>$ai_totasi,
							 'deduccion'=>$ai_totded,'aporte'=>$ai_totapo,'neto'=>$ai_totgeneral));
		$la_columna=array('titulo'=>'','asignacion'=>'','deduccion'=>'','aporte'=>'','neto'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaï¿½o de Letras
						 'titleFontSize' => 12,  // Tamaï¿½o de Letras de los tï¿½tulos
						 'showLines'=>0, // Mostrar Lï¿½neas
						 'shaded'=>2, // Sombra entre lï¿½neas
						 'shadeCol'=>array((224/255),(224/255),(224/255)), // Color de la sombra
						 'shadeCol2'=>array((224/255),(224/255),(224/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Mï¿½ximo de la tabla
						 'xOrientation'=>'center', // Orientaciï¿½n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>180), // Justificaciï¿½n y ancho de la columna
						 			   'asignacion'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'deduccion'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'aporte'=>array('justification'=>'right','width'=>80), // Justificaciï¿½n y ancho de la columna
						 			   'neto'=>array('justification'=>'right','width'=>80))); // Justificaciï¿½n y ancho de la columna
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->Rectangle(15,60,570,50); 
		$io_pdf->line(15,100,585,100);	//HORIZONTAL	
		$io_pdf->addText(60,102,7,'<b>ELABORADO POR:</b>'); // Agregar el tï¿½tulo
		$io_pdf->addText(30,63,7,"ANALISTA DE RECURSOS HUMANOS"); // Agregar el tï¿½tulo
		$io_pdf->line(190,60,190,110);	//VERTICAL	
		$io_pdf->line(15,70,585,70);	//HORIZONTAL
		$io_pdf->addText(260,102,7,'<b>REVISADO POR:</b>'); // Agregar el tï¿½tulo
		$io_pdf->addText(260,63,7,"JEFE OFICINA"); // Agregar el tï¿½tulo
		$io_pdf->line(380,60,380,110);	//VERTICAL	
		$io_pdf->addText(420,102,7,'<b>APROBADO POR: xxxxx</b>  '); // Agregar el tï¿½tulo
		$io_pdf->addText(420,63,7,"GERENCIA DE RECURSOS HUMANOS"); // Agregar el tï¿½tulo
		
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
	//----------------------------------------------------  Parï¿½metros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Reporte General de Pago</b>";
	$ls_periodo="<b>Período Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Parï¿½metros para Filtar el Reporte  -----------------------------------------
	$ls_coduniadmdes=$io_fun_nomina->uf_obtenervalor_get("coduniadmdes","");
	$ls_coduniadmhas=$io_fun_nomina->uf_obtenervalor_get("coduniadmhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_tituloconcepto=$io_fun_nomina->uf_obtenervalor_get("tituloconcepto","");
	$ls_conceptoreporte=$io_fun_nomina->uf_obtenervalor_get("conceptoreporte","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_pagonominaunidad_unidad($ls_codperdes,$ls_codperhas,$ls_conceptocero,$ls_conceptoreporte,
														  $ls_conceptop2,$ls_coduniadmdes,$ls_coduniadmhas,$ls_subnomdes,$ls_subnomhas); // Cargar el DS con los datos de la cabecera del reporte
	}
	if(($lb_valido==false) || ($io_report->rs_data->RecordCount()==0)) // Existe algï¿½n error ï¿½ no hay registros
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
		$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuraciï¿½n de los margenes en centï¿½metros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la pï¿½gina
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el nï¿½mero de pï¿½gina
		$io_pdf->FitWindow=true;
		
		$li_totrowuni=$io_report->rs_data->RecordCount();
		$li_k=1;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
			$li_totasi=0;
			$li_totded=0;
			$li_totapo=0;
			$li_totgeneral=0;
			
			$ls_minorguniadm=$io_report->rs_data->fields["minorguniadm"];
			$ls_ofiuniadm=$io_report->rs_data->fields["ofiuniadm"];
			$ls_uniuniadm=$io_report->rs_data->fields["uniuniadm"];
			$ls_depuniadm=$io_report->rs_data->fields["depuniadm"];
			$ls_prouniadm=$io_report->rs_data->fields["prouniadm"];
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			uf_print_cabecera($ls_minorguniadm,$ls_ofiuniadm,$ls_uniuniadm,$ls_depuniadm,$ls_prouniadm,$ls_desuniadm,
							  $io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_pagonominaunidad_personal($ls_codperdes,$ls_codperhas,$ls_conceptocero,$ls_conceptoreporte,
															    $ls_conceptop2,$ls_minorguniadm,$ls_ofiuniadm,$ls_uniuniadm,
																$ls_depuniadm,$ls_prouniadm,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
			$li_totrow=$io_report->rs_data_detalle->RecordCount();			
			while((!$io_report->rs_data_detalle->EOF)&&($lb_valido))
			{
				$io_pdf->transaction('start'); // Iniciamos la transacciï¿½n
				$li_numpag=$io_pdf->ezPageCount; // Nï¿½mero de pï¿½gina
				$li_totalasignacion=0;
				$li_totaldeduccion=0;
				$li_totalaporte=0;
				$li_total_neto=0;
				
				$ls_codper=$io_report->rs_data_detalle->fields["codper"];
				$ls_cedper=$io_report->rs_data_detalle->fields["cedper"];
				$ls_apenomper=$io_report->rs_data_detalle->fields["apeper"].", ".$io_report->rs_data_detalle->fields["nomper"];
				$ls_descar=$io_report->rs_data_detalle->fields["descar"];
				$ls_desuniadm=$io_report->rs_data_detalle->fields["desuniadm"];
				$ld_fecingper=$io_funciones->uf_convertirfecmostrar($io_report->rs_data_detalle->fields["fecingper"]);
				$ls_codcueban=$io_report->rs_data_detalle->fields["codcueban"];
				
				uf_print_cabecera_personal($ls_cedper,$ls_apenomper,$ls_descar,$ls_desuniadm,$ld_fecingper,$ls_codcueban,$io_pdf); // Imprimimos la cabecera del registro
				$lb_valido=$io_report->uf_pagonominaunidad_conceptopersonal($ls_codper,$ls_conceptocero,$ls_tituloconcepto,$ls_conceptoreporte,$ls_conceptop2); // Obtenemos el detalle del reporte
				if($lb_valido)
				{
					$li_totrow_res=$io_report->rs_data_detalle2->RecordCount();
					$li_s=1;
					while((!$io_report->rs_data_detalle2->EOF)&&($lb_valido))
					{						
						$ls_codconc=$io_report->rs_data_detalle2->fields["codconc"];
						$ls_nomcon=$io_report->rs_data_detalle2->fields["nomcon"];
						$ls_tipsal=rtrim($io_report->rs_data_detalle2->fields["tipsal"]);
						$li_valsal=abs($io_report->rs_data_detalle2->fields["valsal"]);
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
						$la_data[$li_s]=array('codigo'=>$ls_codconc,'nombre'=>$ls_nomcon,'asignacion'=>$li_asignacion,
											  'deduccion'=>$li_deduccion,'aporte'=>$li_aporte,'neto'=>'');
						$li_s++;
						$io_report->rs_data_detalle2->MoveNext();					  
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
					uf_print_piecabecera($li_totalasignacion,$li_totaldeduccion,$li_totalaporte,$li_total_neto,$io_pdf); // Imprimimos el pie de la cabecera
					if ($io_pdf->ezPageCount==$li_numpag)
					{// Hacemos el commit de los registros que se desean imprimir
						$io_pdf->transaction('commit');
					}
					else
					{// Hacemos un rollback de los registros, agregamos una nueva pï¿½gina y volvemos a imprimir
						$io_pdf->transaction('rewind');
						$io_pdf->ezNewPage(); // Insertar una nueva pï¿½gina
						uf_print_cabecera_personal($ls_cedper,$ls_apenomper,$ls_descar,$ls_desuniadm,$ld_fecingper,$ls_codcueban,$io_pdf); // Imprimimos la cabecera del registro
						uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
						uf_print_piecabecera($li_totalasignacion,$li_totaldeduccion,$li_totalaporte,$li_total_neto,$io_pdf); // Imprimimos el pie de la cabecera
					}
				}
				unset($la_data);
				$io_report->rs_data_detalle->MoveNext();	
			}
			$li_totasi=number_format($li_totasi,2,",",".");
			$li_totded=number_format($li_totded,2,",",".");
			$li_totapo=number_format($li_totapo,2,",",".");
			$li_totgeneral=number_format($li_totgeneral,2,",",".");
			uf_print_piepagina($li_totasi,$li_totded,$li_totapo,$li_totgeneral,$ls_desuniadm,$io_pdf);
			$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
			if($li_k<$li_totrowuni)
			{
				$io_pdf->ezNewPage(); // Insertar una nueva pï¿½gina
			}
			unset($io_cabecera);	
			$li_k++;
			$io_report->rs_data->MoveNext();		
		}	
		if($lb_valido) // Si no ocurrio ningï¿½n error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresiï¿½n de los nï¿½meros de pï¿½gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algï¿½n error
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
