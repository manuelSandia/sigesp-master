<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "</script>";		
	}	
			
  // para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo =  tempnam("/tmp", "EJECUCI�N_FINANCIERA_DE_LAS_ACCIONES_ESPEC�FICAS_DEL_ORGANO.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();

	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_moneda,$as_trimestre,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//                 $as_moneda // Moneda
		//	    		   as_trimestre // Nro. del Trimestre
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Arnaldo Su�rez
		// Fecha Creaci�n: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(69,380,69,460);
		$io_pdf->line(199,380,199,460);
		$io_pdf->line(279,380,279,430); // Linea entre Mensual y Acumulado
		$io_pdf->line(349,380,349,460);
		$io_pdf->line(419,380,419,405);
		$io_pdf->line(489,380,489,430);
		$io_pdf->line(559,380,559,405);
		$io_pdf->line(350,405,629,405);
		$io_pdf->line(629,380,629,460);
		$io_pdf->line(699,380,699,430);
		$io_pdf->line(769,380,769,460);
		$io_pdf->line(900,380,900,460);
		$io_pdf->line(200,430,769,430);
		$io_pdf->addText(25,400,7,"CODIGO");
		$io_pdf->addText(105,400,7,"DENOMINACION");
		$io_pdf->addText(250,435,7,"PROGRAMADO");
		$io_pdf->addText(215,405,7,"MENSUAL");
		$io_pdf->addText(290,405,7,"ACUMULADO");
		$io_pdf->addText(405,410,7,"MENSUAL");
		$io_pdf->addText(540,410,7,"ACUMULADO");
		$io_pdf->addText(358,390,7,"COMPROMISO");
		$io_pdf->addText(470,435,7,"EJECUTADO ");
		$io_pdf->addText(435,390,7,"CAUSADO");
		$io_pdf->addText(500,390,7,"COMPROMISO");
		$io_pdf->addText(575,390,7,"CAUSADO");
		$io_pdf->addText(670,435,7,"VARIACION MES");
		$io_pdf->addText(635,405,7,"% COMPROMISO");
		$io_pdf->addText(710,405,7,"% CAUSADO");
		$io_pdf->addText(800,415,7,"RESPONSABLE DE");
		$io_pdf->addText(805,405,7,"LA EJECUCION");
		$io_pdf->addText(935,415,7,"PREVISION");
		$io_pdf->addText(930,405,7,"ACTUALIZADA");
		$io_pdf->addText(930,395,7,"PROXIMO MES");
		$io_pdf->rectangle(10,460,990,120);
		$io_pdf->rectangle(10,382,990,78);
		
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,500,16,$as_titulo); // Agregar el t�tulo
		
		// Fecha
		$io_pdf->line(900,500,900,520);
		$io_pdf->line(900,500,970,500);
		$io_pdf->line(920,500,920,520);
		$io_pdf->line(940,500,940,520);
		$io_pdf->line(970,500,970,520);
		$io_pdf->addText(915,525,10,"FECHA");
		$io_pdf->addText(905,505,10,date("d"));
		$io_pdf->addText(925,505,10,date("m"));
		$io_pdf->addText(945,505,10,date("Y"));
		
		//$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($io_cabecera,$as_programatica,$ai_ano,$as_mes,$as_denestpro,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_periodo_comp // Descripci�n del periodo del comprobante
		//	    		   as_fecha_comp // Descripci�n del per�odo de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Arnaldo Su�rez
		// Fecha Creaci�n: 14/10/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetY(570);
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$ls_nomorgads=$_SESSION["la_empresa"]["nomorgads"];
		$ls_codasiona   = $_SESSION['la_empresa']['codasiona'];
		
		$la_data=array(array('name'=>'<b>CODIGO DEL ORGANO:     </b>'.'<b>'.$ls_codasiona.'</b>'),
		               array('name'=>'<b>DENOMINACION:    </b>'.'<b>'.$ls_nombre.'</b>'),
					   array('name'=>'<b>PROYECTO/ACCION:    </b>'.'<b>'.$as_denestpro.'</b>'),
					   array('name'=>'<b>MES:    </b>'.'<b>'.$as_mes." ".$ai_ano.'</b>'));
		$la_columna=array('name'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,       // Tama�o de Letras
						 'titleFontSize' => 8, // Tama�o de Letras de los t�tulos
						 'showLines'=>0,        // Mostrar L�neas
						 'shaded'=>0,           // Sombra entre l�neas
						 'xPos'=>465,//65
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900);
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
		
	}// end function uf_print_titulo_reporte
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo($io_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_titulo
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yozelin Barrag�n
		// Fecha Creaci�n: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();
		$io_pdf->ezSetDy(-100); // para  el rectangulo 
		$la_data=array(array('codigo'=>'',
							 'denominacion'=>'',
							 'programado_mensual'=>'',
							 'programado_acumulado'=>'',
							 'ejecutado_mens_comp'=>'',
							 'ejecutado_mens_caus'=>'',
							 'ejecutado_acum_comp'=>'',
							 'ejecutado_acum_caus'=>'',
							 'variacion_comp'=>'',
							 'variacion_caus'=>'',
							 'responsable_ejec'=>'',
							 'prevision_prox_mes'=>''));
							 
		$la_columna=array(   'codigo'=>'',
							 'denominacion'=>'',
							 'programado_mensual'=>'',
							 'programado_acumulado'=>'',
							 'ejecutado_mens_comp'=>'',
							 'ejecutado_mens_caus'=>'',
							 'ejecutado_acum_comp'=>'',
							 'ejecutado_acum_caus'=>'',
							 'variacion_comp'=>'',
							 'variacion_caus'=>'',
							 'responsable_ejec'=>'',
							 'prevision_prox_mes'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 7,       // Tama�o de Letras
						 'titleFontSize' => 7, // Tama�o de Letras de los t�tulos
						 'showLines'=>1,        // Mostrar L�neas
						 'shaded'=>0,           // Sombra entre l�neas
						 'xPos'=>504,
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 'cols'=>array('codigo'=>         array('justification'=>'center','width'=>60),
							           'denominacion'=>    array('justification'=>'center','width'=>130),
							           'programado_mensual'=>        array('justification'=>'center','width'=>80),
							           'programado_acumulado'=>      array('justification'=>'center','width'=>70),
							           'ejecutado_mens_comp'=>      array('justification'=>'center','width'=>70),
							           'ejecutado_mens_caus'=>      array('justification'=>'center','width'=>70),
							           'ejecutado_acum_comp'=>         array('justification'=>'center','width'=>70),
							           'ejecutado_acum_caus'=>          array('justification'=>'center','width'=>70),
							           'variacion_comp'=> array('justification'=>'center','width'=>70),
							           'variacion_caus'=> array('justification'=>'center','width'=>70),
							           'responsable_ejec'=>    array('justification'=>'center','width'=>70),
							           'prevision_prox_mes'=>     array('justification'=>'center','width'=>80))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_titulo,'all');

	}// end function uf_print_titulo
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barrag�n
		// Fecha Creaci�n: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
						 'showLines'=>2, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('codigo'=>         array('justification'=>'center','width'=>60),
							           'denominacion'=>    array('justification'=>'left','width'=>130),
							           'programado_mensual'=>        array('justification'=>'right','width'=>80),
							           'programado_acumulado'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_mens_comp'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_mens_caus'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_acum_comp'=>         array('justification'=>'right','width'=>70),
							           'ejecutado_acum_caus'=>          array('justification'=>'right','width'=>70),
							           'variacion_comp'=> array('justification'=>'right','width'=>70),
							           'variacion_caus'=> array('justification'=>'right','width'=>70),
							           'responsable_ejec'=>    array('justification'=>'center','width'=>131),
							           'prevision_prox_mes'=>     array('justification'=>'right','width'=>99))); // Justificaci�n y ancho de la columna
		$la_columnas=array(  'codigo'=>'',
							 'denominacion'=>'',
							 'programado_mensual'=>'',
							 'programado_acumulado'=>'',
							 'ejecutado_mens_comp'=>'',
							 'ejecutado_mens_caus'=>'',
							 'ejecutado_acum_comp'=>'',
							 'ejecutado_acum_caus'=>'',
							 'variacion_comp'=>'',
							 'variacion_caus'=>'',
							 'responsable_ejec'=>'',
							 'prevision_prox_mes'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($la_data_tot,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function : uf_print_pie_cabecera
		//		    Acess : private 
		//	    Arguments : ad_total // Total General
		//    Description : funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por: Ing. Arnaldo US�rez
		// Fecha Creaci�n: 10/06/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 7,  // Tama�o de Letras de los t�tulos
						 'showLines'=>2, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('totales'=>array('justification'=>'center','width'=>190), // Justificaci�n y ancho de la columna
						 			   'programado_mensual'=>        array('justification'=>'right','width'=>80),
							           'programado_acumulado'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_mens_comp'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_mens_caus'=>      array('justification'=>'right','width'=>70),
							           'ejecutado_acum_comp'=>         array('justification'=>'right','width'=>70),
							           'ejecutado_acum_caus'=>          array('justification'=>'right','width'=>70),
							           'variacion_comp'=> array('justification'=>'right','width'=>70),
							           'variacion_caus'=> array('justification'=>'right','width'=>70),
							           'responsable_ejec'=>    array('justification'=>'center','width'=>131),
							           'prevision_prox_mes'=>     array('justification'=>'right','width'=>99))); // Justificaci�n y ancho de la columna
		$la_columnas=array('totales'=>'',
						   'programado_mensual'=>'',
						   'programado_acumulado'=>'',
						   'ejecutado_mens_comp'=>'',
						   'ejecutado_mens_caus'=>'',
						   'ejecutado_acum_comp'=>'',
						   'ejecutado_acum_caus'=>'',
						   'variacion_comp'=>'',
						   'variacion_caus'=>'',
						   'responsable_ejec'=>'',
						   'prevision_prox_mes'=>'');
		$io_pdf->ezTable($la_data_tot,$la_columnas,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report=new sigesp_spg_funciones_reportes();	
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
//-----------------------------------------------------------------------------------------------------------------------------
		global $la_data_tot;
		require_once("sigesp_spg_class_reportes_instructivo_06.php");
		$io_report = new sigesp_spg_class_reportes_instructivo_06();
		
		/* $li_candeccon=$_SESSION["la_empresa"]["candeccon"];
		 $li_tipconmon=$_SESSION["la_empresa"]["tipconmon"];
		 $li_redconmon=$_SESSION["la_empresa"]["redconmon"]; */
		 
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];

		$ls_cmbmes=$_GET["mes"];
		switch($ls_cmbmes)
		{
		 case '01': $ls_mes = "ENERO";
		 break;
		 case '02': $ls_mes = "FEBRERO";
		 break;
		 
		 case '03': $ls_mes = "MARZO";
		 break;
		 case '04': $ls_mes = "ABRIL";
		 break;
		 case '05': $ls_mes = "MAYO";
		 break;
		 
		 case '06': $ls_mes = "JUNIO";
		 break;
		 
		 case '07': $ls_mes = "JULIO";
		 break;
		 
		 case '08': $ls_mes = "AGOSTO";
		 break;
		 
		 case '09': $ls_mes = "SEPTIEMBRE";
		 break;
		 
		 case '10': $ls_mes = "OCTUBRE";
		 break;
		 
		 case '11': $ls_mes = "NOVIEMBRE";
		 break;
		 
		 case '12': $ls_mes = "DICIEMBRE";
		 break;
		
		}
		$li_mesdes=substr($ls_cmbmes,0,2);
		$ldt_fecdes=$li_ano."-".$ls_cmbmes."-01";
		$li_meshas=substr($ls_cmbmes,2,2);
		$ldt_ult_dia=$io_fecha->uf_last_day($ls_cmbmes,$li_ano);
		$fechas=$ldt_ult_dia;
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);	
		
//----------------------------------------------------  Par�metros del encabezado  ---------------------------------------------
		$ls_titulo="EJECUCI�N FINANCIERA DE LAS ACCIONES ESPEC�FICAS DEL ORGANO";       
//--------------------------------------------------------------------------------------------------------------------------------
   
     $lb_valido=$io_report->uf_spg_reporte_ejecucion_financiera_acc_esp($ldt_fecdes,$ldt_fechas);

	 if($lb_valido==false) // Existe alg�n error � no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	 	
	 	/*
	    error_reporting(E_ALL);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		uf_print_encabezado_pagina($ls_titulo,"",$ls_mes,$io_pdf); // Imprimimos el encabezado de la p�gina
 	    $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el n�mero de p�gina
		$io_pdf->transaction('start'); // Iniciamos la transacci�n
		$thisPageNum=$io_pdf->ezPageCount;
		
	    $ld_total_programado_mensual   = 0;
		$ld_total_programado_acumulado = 0;
		$ld_total_ejecutado_mens_comp  = 0;
		$ld_total_ejecutado_mens_caus  = 0;
		$ld_total_ejecutado_acum_comp  = 0;
		$ld_total_ejecutado_acum_caus  = 0;
		$ld_total_prevision_prox_mes   = 0;
		$ls_mesdes = "";	
		*/
		
		
	 	
	 	
	 	
	 	$li_tot=$io_report->dts_reporte->getRowCount("codigo");	 	
	 	$lo_encabezado= &$lo_libro->addformat();
		$lo_encabezado->set_bold();
		$lo_encabezado->set_font("Verdana");
		$lo_encabezado->set_align('center');
		$lo_encabezado->set_size('11');
		$lo_titulo= &$lo_libro->addformat();
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
		$lo_dataright2= &$lo_libro->addformat();
		$lo_dataright2->set_font("Verdana");
		$lo_dataright2->set_bold();
		$lo_dataright2->set_align('right');
		$lo_dataright2->set_size('9');
		$lo_hoja->set_column(0,0,15);
		$lo_hoja->set_column(1,1,20);
		$lo_hoja->set_column(2,2,30);
		$lo_hoja->set_column(3,3,20);
		$lo_hoja->set_column(4,4,13);
		$lo_hoja->set_column(5,7,30);
	    $ls_spg_cuenta_ant="";
		$ld_total_asignado=0;
		$ld_total_aumento=0;
		$ld_total_disminucion=0;
		$ld_total_monto_actualizado=0;
		$ld_total_compromiso=0;
		$ld_total_precompromiso=0;
		$ld_total_compromiso=0;
		$ld_total_saldo_comprometer=0;
		$ld_total_causado=0;
		$ld_total_pagado=0;
		$ld_total_por_paga=0;
		$li_row=2;
		$z=0;
		$contlineas=0;
		
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		$ls_nomorgads=$_SESSION["la_empresa"]["nomorgads"];
		$ls_codasiona  = $_SESSION['la_empresa']['codasiona'];	
	
		for($z=1;$z<=$li_tot;$z++)
		{
				
			$li_tmp=($z+1);
			$thisPageNum=$io_pdf->ezPageCount;
			$ls_codigo="";
			$ls_denominacion="";
			$ls_denestpro2="";
			$ls_denpryacc = "";
			$ls_responsable_ejec = "";
			$ld_programado_mensual   = 0;
		    $ld_programado_acumulado = 0;
		    $ld_ejecutado_mens_comp  = 0;
		    $ld_ejecutado_mens_caus  = 0;
			$ld_ejecutado_acum_comp  = 0;
		    $ld_ejecutado_acum_caus  = 0;
			$ld_variacion_comp       = 0;
			$ld_variacion_caus       = 0;
			$ld_prevision_prox_mes   = 0;
			

				  $ls_pry_acc                = trim($io_report->dts_reporte->data["codigo"][$z]);
				  $ls_codigo                 = trim($io_report->dts_reporte->data["codestpro2"][$z]);
				  $ls_denpryacc				 = trim($io_report->dts_reporte->data["denominacion"][$z]);
				  $ls_denominacion           = trim($io_report->dts_reporte->data["denestpro2"][$z]);
				  $ld_programado_mensual     = $io_report->dts_reporte->data["programado_mensual"][$z];
				  $ld_programado_acumulado   = $io_report->dts_reporte->data["programado_acumulado"][$z];
				  $ld_ejecutado_mens_comp    = $io_report->dts_reporte->data["ejecutado_mens_comp"][$z];
				  $ld_ejecutado_mens_caus    = $io_report->dts_reporte->data["ejecutado_mens_caus"][$z];
				  $ld_ejecutado_acum_comp    = $io_report->dts_reporte->data["ejecutado_acum_comp"][$z];
				  $ld_ejecutado_acum_caus    = $io_report->dts_reporte->data["ejecutado_acum_caus"][$z];
				  $ld_prevision_prox_mes     = $io_report->dts_reporte->data["prevision_prox_mes"][$z];
				  if ($z<$li_tot)
				  {
					$ls_pry_acc_next=$io_report->dts_reporte->data["codigo"][$li_tmp]; 
				  }
				  elseif($z=$li_tot)
				  {
					$ls_pry_acc_next='no_next';
				  }
				  if(!empty($ls_pry_acc))
			      {
			       $ls_pry_acc_ant = $ls_pry_acc;
				   $ls_denominacion_ant = $ls_denominacion;
				   $ld_total_programado_mensual   = 0;
				   $ld_total_programado_acumulado = 0;
				   $ld_total_ejecutado_mens_comp  = 0;
				   $ld_total_ejecutado_mens_caus  = 0;
				   $ld_total_ejecutado_acum_comp  = 0;
				   $ld_total_ejecutado_acum_caus  = 0;
				   $ld_total_prevision_prox_mes   = 0;
				   if($ld_programado_mensual > 0)
				   {
					 $ld_variacion_comp         = ($ld_ejecutado_mens_comp*100)/$ld_programado_mensual;
				   }
				   else
				   {
					 $ld_variacion_comp = 0;
				   }
				   if ($ld_ejecutado_mens_comp >0)
				   {
				    $ld_variacion_caus         = ($ld_ejecutado_mens_caus*100)/$ld_ejecutado_mens_comp;
				   }
				   else
				   {
				    $ld_variacion_caus = 0;
				   }  
				  $ld_total_programado_mensual   = $ld_total_programado_mensual + $ld_programado_mensual;
				  $ld_total_programado_acumulado = $ld_total_programado_acumulado + $ld_programado_acumulado;
				  $ld_total_ejecutado_mens_comp  = $ld_total_ejecutado_mens_comp + $ld_ejecutado_mens_comp;
				  $ld_total_ejecutado_mens_caus  = $ld_total_ejecutado_mens_caus + $ld_ejecutado_mens_caus;
				  $ld_total_ejecutado_acum_comp  = $ld_total_ejecutado_acum_comp + $ld_ejecutado_acum_comp;
				  $ld_total_ejecutado_acum_caus  = $ld_total_ejecutado_acum_caus + $ld_ejecutado_acum_caus;
				  $ld_total_prevision_prox_mes   = $ld_total_prevision_prox_mes + $ld_prevision_prox_mes;
				  
				  $ld_programado_mensual   = number_format($ld_programado_mensual,2,",",".");
				  $ld_programado_acumulado = number_format($ld_programado_acumulado,2,",",".");
				  $ld_ejecutado_mens_comp  = number_format($ld_ejecutado_mens_comp,2,",",".");
				  $ld_ejecutado_mens_caus  = number_format($ld_ejecutado_mens_caus,2,",",".");
				  $ld_ejecutado_acum_comp  = number_format($ld_ejecutado_acum_comp,2,",",".");
				  $ld_ejecutado_acum_caus  = number_format($ld_ejecutado_acum_caus,2,",",".");
				  $ld_variacion_comp       = number_format($ld_variacion_comp,2,",",".");
				  $ld_variacion_caus       = number_format($ld_variacion_caus,2,",",".");
				  $ld_prevision_prox_mes   = number_format($ld_prevision_prox_mes,2,",","."); 
		
			      }
				 
				 			 
					  							 						   
			  $ld_total_programado_mensual   = number_format($ld_total_programado_mensual,2,",",".");
			  $ld_total_programado_acumulado = number_format($ld_total_programado_acumulado,2,",",".");
			  $ld_total_ejecutado_mens_comp  = number_format($ld_total_ejecutado_mens_comp,2,",",".");
			  $ld_total_ejecutado_mens_caus  = number_format($ld_total_ejecutado_mens_caus,2,",",".");
			  $ld_total_ejecutado_acum_comp  = number_format($ld_total_ejecutado_acum_comp,2,",",".");
			  $ld_total_ejecutado_acum_caus  = number_format($ld_total_ejecutado_acum_caus,2,",",".");
			  $ld_total_prevision_prox_mes   = number_format($ld_total_prevision_prox_mes,2,",",".");
			
			$la_data_tot[1]=array('totales'=>"TOTALES",
								  'programado_mensual'=>$ld_total_programado_mensual,
								  'programado_acumulado'=>$ld_total_programado_acumulado,
								  'ejecutado_mens_comp'=>$ld_total_ejecutado_mens_comp,
								  'ejecutado_mens_caus'=>$ld_total_ejecutado_mens_caus,
								  'ejecutado_acum_comp'=>$ld_total_ejecutado_acum_comp,
								  'ejecutado_acum_caus'=>$ld_total_ejecutado_acum_caus,
								  'variacion_comp'=>'',
								  'variacion_caus'=>'',
								  'responsable_ejec'=>'',
								  'prevision_prox_mes'=>$ld_total_prevision_prox_mes);
 
			
			
			 unset($la_data);
			 unset($la_data_tot);
			 
			 if ((!empty($ls_pry_acc))&&($z<$li_tot))
			 {
			//	 $io_pdf->ezNewPage(); // Insertar una nueva p�gina
			 }
		
			 
		$lo_hoja->write($contlineas, 0,"C�DIGO DEL �RGANO",$lo_titulo);
		$lo_hoja->write($contlineas, 1,$ls_codasiona,$lo_titulo);
		$contlineas++;
		$lo_hoja->write($contlineas, 0,"DENOMINACI�N",$lo_titulo);
		$lo_hoja->write($contlineas, 1,$ls_nombre,$lo_titulo);
		$contlineas++;
		$lo_hoja->write($contlineas, 0,"PROYECTO/ACCION",$lo_titulo);
		$lo_hoja->write($contlineas, 1,$ls_pry_acc." - ".$ls_denpryacc,$lo_titulo);
		$contlineas++;
		$lo_hoja->write($contlineas, 0,"MES",$lo_titulo);
		$lo_hoja->write($contlineas, 1,"{$ls_mes} {$li_ano}",$lo_titulo);
	 	$contlineas++;
		$lo_hoja->write($contlineas, 5,$ls_titulo,$lo_titulo);
	 	$lo_hoja->write($contlineas, 9,"".date('d')."/".date('m')."/".date('Y'),$lo_titulo);
		$contlineas++;
		$contlineas++;
	 	$lo_hoja->write($contlineas,0,"C�digo",$lo_titulo);
		$lo_hoja->write($contlineas,1,"Denominaci�n",$lo_titulo);
		$lo_hoja->write($contlineas,2,"Programado",$lo_dataright2);
		$lo_hoja->write($contlineas,3," ",$lo_titulo);
		$lo_hoja->write($contlineas,4," ",$lo_titulo);
		$lo_hoja->write($contlineas,5,"Ejecutado",$lo_dataright2);
		$lo_hoja->write($contlineas,6," .",$lo_titulo);
		$lo_hoja->write($contlineas,7," .",$lo_titulo);	
		$lo_hoja->write($contlineas,8,"Variaci�n Mes",$lo_titulo);
		$lo_hoja->write($contlineas,9," .",$lo_titulo);
		$lo_hoja->write($contlineas,10,"Responsable de la Ejecuci�n",$lo_titulo);
		$lo_hoja->write($contlineas,11,"Previsi�n Actualizada Pr�ximo Mes",$lo_titulo);
		$contlineas++;
		$lo_hoja->write($contlineas,0," .",$lo_titulo);
		$lo_hoja->write($contlineas,1," .",$lo_titulo);
		$lo_hoja->write($contlineas,2,"Mensual",$lo_titulo);
		$lo_hoja->write($contlineas,3,"Acumulado",$lo_titulo);
		$lo_hoja->write($contlineas,4,"Mensual",$lo_dataright2);
		$lo_hoja->write($contlineas,5,".",$lo_titulo);
		$lo_hoja->write($contlineas,6,"Acumulado",$lo_dataright2);
		$lo_hoja->write($contlineas,7,".",$lo_titulo);	
		$lo_hoja->write($contlineas,8," .",$lo_titulo);
		$lo_hoja->write($contlineas,9," ",$lo_titulo);
		$lo_hoja->write($contlineas,10," .",$lo_titulo);
		$lo_hoja->write($contlineas,11," .",$lo_titulo);
		$contlineas++;
		$lo_hoja->write($contlineas,0," .",$lo_titulo);
		$lo_hoja->write($contlineas,1," .",$lo_titulo);
		$lo_hoja->write($contlineas,2," ",$lo_titulo);
		$lo_hoja->write($contlineas,3," ",$lo_titulo);
		$lo_hoja->write($contlineas,4,"Compromiso",$lo_titulo);
		$lo_hoja->write($contlineas,5,"Causado",$lo_titulo);
		$lo_hoja->write($contlineas,6,"Compromiso",$lo_titulo);
		$lo_hoja->write($contlineas,7,"Causado",$lo_titulo);	
		$lo_hoja->write($contlineas,8," .",$lo_titulo);
		$lo_hoja->write($contlineas,9," ",$lo_titulo);
		$lo_hoja->write($contlineas,10," .",$lo_titulo);
		$lo_hoja->write($contlineas,11," .",$lo_titulo);
	 	
		
		
		$lo_hoja->write($contlineas,0," ".$ls_codigo." ",$lo_dataleft);
		$lo_hoja->write($contlineas,1," $ls_denominacion",$lo_dataleft);
		$lo_hoja->write($contlineas,2,$ld_programado_mensual,$lo_dataright);
		$lo_hoja->write($contlineas,3,$ld_programado_acumulado,$lo_dataright);
		$lo_hoja->write($contlineas,4,$ld_ejecutado_mens_comp,$lo_dataright);
		$lo_hoja->write($contlineas,5,$ld_ejecutado_mens_caus,$lo_dataright);
		$lo_hoja->write($contlineas,6,$ld_ejecutado_acum_comp,$lo_dataright);
		$lo_hoja->write($contlineas,7,$ld_ejecutado_acum_caus,$lo_dataright);	
		$lo_hoja->write($contlineas,8,$ld_variacion_comp,$lo_dataright);
		$lo_hoja->write($contlineas,9,$ld_variacion_caus,$lo_dataright);
		$lo_hoja->write($contlineas,10," ",$lo_titulo);
		$lo_hoja->write($contlineas,11,$ld_prevision_prox_mes,$lo_dataright);
		

		$contlineas++;
        $lo_hoja->write($contlineas,0," ",$lo_titulo);
		$lo_hoja->write($contlineas,1,"Totales",$lo_titulo);
		$lo_hoja->write($contlineas,2,$ld_total_programado_mensual,$lo_dataright);
		$lo_hoja->write($contlineas,3,$ld_total_programado_acumulado,$lo_dataright);
		$lo_hoja->write($contlineas,4,$ld_total_ejecutado_mens_comp,$lo_dataright);
		$lo_hoja->write($contlineas,5,$ld_total_ejecutado_mens_caus,$lo_dataright);
		$lo_hoja->write($contlineas,6,$ld_total_ejecutado_acum_comp,$lo_dataright);
		$lo_hoja->write($contlineas,7,$ld_total_ejecutado_acum_caus,$lo_dataright);	
		$lo_hoja->write($contlineas,8,0,$lo_dataright);
		$lo_hoja->write($contlineas,9,0,$lo_dataright);
		$lo_hoja->write($contlineas,10," ",$lo_titulo);
		$lo_hoja->write($contlineas,11,$ld_total_prevision_prox_mes,$lo_dataright);			
		$contlineas=$contlineas+10;
		
		}//for			
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"EJECUCI�N_FINANCIERA_DE_LOS_PROYECTOS/ACCIONES_CENTRALIZADAS_DEL_ORGANO.xls\"");
		header("Content-Disposition: inline; filename=\"EJECUCI�N_FINANCIERA_DE_LOS_PROYECTOS/ACCIONES_CENTRALIZADAS_DEL_ORGANO.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		print(" close();");
		print("</script>");
		unset($class_report);
		unset($io_funciones);
		unset($la_data);
		unset($la_data_tot);		
	}//else
	unset($io_report);
	unset($io_funciones);
?> 