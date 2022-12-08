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
	function uf_insert_seguridad($as_titulo,$as_desnom,$as_periodo,$ai_tipo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T铆tulo del Reporte
		//	    		   as_desnom // Descripci贸n de la n贸mina
		//	    		   as_periodo // Descripci贸n del per铆odo
		//    Description: funci贸n que guarda la seguridad de quien gener贸 el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci贸n: 27/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$ls_descripcion="Gener贸 el Reporte ".$as_titulo.". Para ".$as_desnom.". ".$as_periodo;
		if($ai_tipo==1)
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_prenomina.php",$ls_descripcion,$ls_codnom);
		}
		else
		{
			$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte_nomina("SNO","sigesp_sno_r_hprenomina.php",$ls_descripcion,$ls_codnom);
		}
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_desnom,$as_periodo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T铆tulo del Reporte
		//	    		   as_desnom // Descripci贸n de la n贸mina
		//	    		   as_periodo // Descripci贸n del per铆odo
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci贸n que imprime los encabezados por p谩gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci贸n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el t铆tulo
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,720,11,$as_periodo); // Agregar el t铆tulo
		$li_tm=$io_pdf->getTextWidth(10,$as_desnom);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,710,10,$as_desnom); // Agregar el t铆tulo
		$io_pdf->addText(512,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(518,743,7,date("h:i a")); // Agregar la Hora
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codper,$as_nomper,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // c贸digo del personal
		//	    		   as_nomper // Nombres y apellidos del personal
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci贸n que imprime la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci贸n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>'<b>Personal</b>  '.$as_codper.' - '.$as_nomper.''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama帽o de Letras
						 'showLines'=>0, // Mostrar L铆neas
						 'shaded'=>2, // Sombra entre l铆neas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci贸n de la tabla
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500); // Ancho M谩ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci贸n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci贸n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci贸n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columna=array('codigo'=>'<b>C骴igo</b>',
						  'concepto'=>'<b>                              Concepto</b>',
						  'signo'=>'<b>Signo</b>',
						  'prenomina'=>'<b>Pren髆ina        </b>',
						  'anterior'=>'<b>Anterior         </b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama帽o de Letras
						 'titleFontSize' =>9,  // Tama帽o de Letras de los t铆tulos
						 'showLines'=>1, // Mostrar L铆neas
						 'shaded'=>0, // Sombra entre l铆neas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M谩ximo de la tabla
						 'xOrientation'=>'center', // Orientaci贸n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codigo'=>array('justification'=>'center','width'=>70), // Justificaci贸n y ancho de la columna
						 			   'concepto'=>array('justification'=>'left','width'=>180), // Justificaci贸n y ancho de la columna
						 			   'signo'=>array('justification'=>'center','width'=>90), // Justificaci贸n y ancho de la columna
						 			   'prenomina'=>array('justification'=>'right','width'=>80), // Justificaci贸n y ancho de la columna
						 			   'anterior'=>array('justification'=>'right','width'=>80))); // Justificaci贸n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_totprenom,$ai_totant,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private 
		//	    Arguments: ai_totprenom // Total Pren贸mina
		//	   			   ai_totant // Total Anterior
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci贸n que imprime el fin de la cabecera por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci贸n: 26/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;
		
		$io_pdf->ezSetDy(1);
		$la_data=array(array('total'=>'<b>Total '.$ls_bolivares.'</b>','prenomina'=>$ai_totprenom,'anterior'=>$ai_totant));
		$la_columna=array('total'=>'','prenomina'=>'','anterior'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama帽o de Letras
						 'showLines'=>1, // Mostrar L铆neas
						 'shaded'=>2, // Sombra entre l铆neas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>500, // Ancho M谩ximo de la tabla
						 'xOrientation'=>'center', // Orientaci贸n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>340), // Justificaci贸n y ancho de la columna
						 			   'prenomina'=>array('justification'=>'right','width'=>80), // Justificaci贸n y ancho de la columna
						 			   'anterior'=>array('justification'=>'right','width'=>80))); // Justificaci贸n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L铆neas
						 'shaded'=>0, // Sombra entre l铆neas
						 'width'=>500, // Ancho M谩ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci贸n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
        $io_pdf->setColor(0,0,0);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_piepagina($ai_totpre,$ai_tothis,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_piepagina
		//		   Access: private 
		//	    Arguments: ai_totpre // Total de Pren贸mina
		//	   			   ai_tothis // Total de Hist贸rico
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci贸n que imprime el fin de la cabecera
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci贸n: 25/05/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_bolivares;

		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama帽o de Letras
						 'showLines'=>0, // Mostrar L铆neas
						 'shaded'=>0, // Sombra entre l铆neas
						 'xOrientation'=>'center', // Orientaci贸n de la tabla
						 'width'=>500); // Ancho M谩ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$la_data=array(array('titulo'=>'<b>Total Pren髆ina '.$ls_bolivares.': </b>','prenomina'=>$ai_totpre,'historico'=>$ai_tothis));
		$la_columna=array('titulo'=>'','prenomina'=>'','historico'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama帽o de Letras
						 'titleFontSize' => 9,  // Tama帽o de Letras de los t铆tulos
						 'showLines'=>0, // Mostrar L铆neas
						 'shaded'=>2, // Sombra entre l铆neas
						 'shadeCol'=>array((224/255),(224/255),(224/255)), // Color de la sombra
						 'shadeCol2'=>array((224/255),(224/255),(224/255)), // Color de la sombra
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M谩ximo de la tabla
						 'xOrientation'=>'center', // Orientaci贸n de la tabla
						 'cols'=>array('titulo'=>array('justification'=>'right','width'=>340), // Justificaci贸n y ancho de la columna
						 			   'prenomina'=>array('justification'=>'right','width'=>80), // Justificaci贸n y ancho de la columna
						 			   'historico'=>array('justification'=>'right','width'=>80))); // Justificaci贸n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);

		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama帽o de Letras
						 'showLines'=>0, // Mostrar L铆neas
						 'shaded'=>0, // Sombra entre l铆neas
						 'xOrientation'=>'center', // Orientaci贸n de la tabla
						 'width'=>500); // Ancho M谩ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		
		
		
		
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->Rectangle(15,60,570,50); 
		$io_pdf->line(15,100,585,100);	//HORIZONTAL	
		$io_pdf->addText(60,102,7,'<b>ELABORADO POR:</b>'); // Agregar el t锟絫ulo
		$io_pdf->addText(30,63,7,"ANALISTA DE RECURSOS HUMANOS"); // Agregar el t锟絫ulo
		$io_pdf->line(190,60,190,110);	//VERTICAL	
		$io_pdf->line(15,70,585,70);	//HORIZONTAL
		$io_pdf->addText(260,102,7,'<b>REVISADO POR:</b>'); // Agregar el t锟絫ulo
		$io_pdf->addText(260,63,7,"JEFE OFICINA"); // Agregar el t锟絫ulo
		$io_pdf->line(380,60,380,110);	//VERTICAL	
		$io_pdf->addText(420,102,7,'<b>APROBADO POR: xxxx</b>  '); // Agregar el t锟絫ulo
		$io_pdf->addText(420,63,7,"GERENCIA DE RECURSOS HUMANOS"); // Agregar el t锟絫ulo
		
		
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	$ls_tiporeporte="0";
	$ls_bolivares ="Bs.";
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
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Par谩metros del encabezado  -----------------------------------------------
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ld_fecdesper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
	$ld_fechasper=$io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
	$ls_titulo="<b>Reporte de Pren髆ina</b>";
	$ls_periodo="<b>Per韔do Nro ".$ls_peractnom.", ".$ld_fecdesper." - ".$ld_fechasper."</b>";
	//--------------------------------------------------  Par谩metros para Filtar el Reporte  -----------------------------------------
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_conceptocero=$io_fun_nomina->uf_obtenervalor_get("conceptocero","");
	$ls_conceptop2=$io_fun_nomina->uf_obtenervalor_get("conceptop2","");
	$ls_subnomdes=$io_fun_nomina->uf_obtenervalor_get("subnomdes","");
	$ls_subnomhas=$io_fun_nomina->uf_obtenervalor_get("subnomhas","");
	$ls_orden=$io_fun_nomina->uf_obtenervalor_get("orden","1");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo,$ls_desnom,$ls_periodo,$li_tipo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_prenomina_personal($ls_codperdes,$ls_codperhas,$ls_subnomdes,$ls_subnomhas,$ls_orden); // Cargar el DS con los datos de la cabecera del reporte
	}
	$li_totrow=$io_report->rs_data->RecordCount();
	if(($lb_valido==false) || ($li_totrow == 0) )// Existe alg煤n error 贸 no hay registros
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
		$io_pdf->ezSetCmMargins(3,2.5,3,3); // Configuraci贸n de los margenes en cent铆metros
		uf_print_encabezado_pagina($ls_titulo,$ls_desnom,$ls_periodo,$io_pdf); // Imprimimos el encabezado de la p谩gina
		
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el n煤mero de p谩gina
		$li_total_pre=0;
		$li_total_his=0;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
	        $io_pdf->transaction('start'); // Iniciamos la transacci贸n
			$li_numpag=$io_pdf->ezPageCount; // N煤mero de p谩gina
			$li_totprenom=0;
			$li_totant=0;			
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_nomper=$io_report->rs_data->fields["apeper"].", ".$io_report->rs_data->fields["nomper"];
			uf_print_cabecera($ls_codper,$ls_nomper,$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_prenomina_conceptopersonal($ls_codper,$ls_conceptocero,$ls_conceptop2); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->rs_data_detalle->RecordCount();				
				$li_s=0;
				while (!$io_report->rs_data_detalle->EOF)
				{					
					$ls_codconc=$io_report->rs_data_detalle->fields["codconc"];
					$ls_nomcon=$io_report->rs_data_detalle->fields["nomcon"];
					$ls_tipprenom=rtrim($io_report->rs_data_detalle->fields["tipprenom"]);
					switch($ls_tipprenom)
					{
						case "A": // asignaci贸n
							$ls_tipprenom="ASIGNACI覰";							
							$li_totprenom=$li_totprenom+$io_report->rs_data_detalle->fields["valprenom"];
							$li_totant=$li_totant+$io_report->rs_data_detalle->fields["valhis"];
							break;

						case "V1": // asignaci贸n
							$ls_tipprenom="ASIGNACI覰";							
							$li_totprenom=$li_totprenom+$io_report->rs_data_detalle->fields["valprenom"];
							$li_totant=$li_totant+$io_report->rs_data_detalle->fields["valhis"];
							break;

						case "W1": // asignaci贸n
							$ls_tipprenom="ASIGNACI覰";							
							$li_totprenom=$li_totprenom+$io_report->rs_data_detalle->fields["valprenom"];
							$li_totant=$li_totant+$io_report->rs_data_detalle->fields["valhis"];
							break;

						case "D": // deducci贸n
							$ls_tipprenom="DEDUCCI覰";							
							$li_totprenom=$li_totprenom+$io_report->rs_data_detalle->fields["valprenom"];
							$li_totant=$li_totant+$io_report->rs_data_detalle->fields["valhis"];
							break;

						case "V2": // deducci贸n
							$ls_tipprenom="DEDUCCI覰";							
							$li_totprenom=$li_totprenom+$io_report->rs_data_detalle->fields["valprenom"];
							$li_totant=$li_totant+$io_report->rs_data_detalle->fields["valhis"];
							break;

						case "W2": // deducci贸n
							$ls_tipprenom="DEDUCCI覰";							
							$li_totprenom=$li_totprenom+$io_report->rs_data_detalle->fields["valprenom"];
							$li_totant=$li_totant+$io_report->rs_data_detalle->fields["valhis"];
							break;

						case "P1": // aporte
							$ls_tipprenom="APORTE PATRONAL";							
							$li_totprenom=$li_totprenom+$io_report->rs_data_detalle->fields["valprenom"];
							$li_totant=$li_totant+$io_report->rs_data_detalle->fields["valhis"];
							break;

						case "V3": // aporte
							$ls_tipprenom="APORTE PATRONAL";							
							$li_totprenom=$li_totprenom+$io_report->rs_data_detalle->fields["valprenom"];
							$li_totant=$li_totant+$io_report->rs_data_detalle->fields["valhis"];
							break;

						case "W3": // aporte
							$ls_tipprenom="APORTE PATRONAL";							
							$li_totprenom=$li_totprenom+$io_report->rs_data_detalle->fields["valprenom"];
							$li_totant=$li_totant+$io_report->rs_data_detalle->fields["valhis"];
							break;

						case "P2": // aporte
							$ls_tipprenom="APORTE PATRONAL";
							break;

						case "R": // Reporte
							$ls_tipprenom="REPORTE";
							break;					

						case "B": // Reintegro de Deducci贸n
							$ls_tipprenom="REINTEGRO DE DEDUCCI覰";
							break;					

						case "E": // Reintegro de Asignaci贸n
							$ls_tipprenom="REINTEGRO DE ASIGNACI覰";
							break;
					}					
					$li_valprenom=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valprenom"]));
					$li_valhis=$io_fun_nomina->uf_formatonumerico(abs($io_report->rs_data_detalle->fields["valhis"]));
					$li_s++;
					$la_data[$li_s]=array('codigo'=>$ls_codconc,'concepto'=>$ls_nomcon,'signo'=>$ls_tipprenom,'prenomina'=>$li_valprenom,'anterior'=>$li_valhis);
					$io_report->rs_data_detalle->MoveNext();
				}
				$li_total_pre=$li_total_pre+$li_totprenom;
				$li_total_his=$li_total_his+$li_totant;
				$li_totprenom=$io_fun_nomina->uf_formatonumerico(abs($li_totprenom));
				$li_totant=$io_fun_nomina->uf_formatonumerico(abs($li_totant));
				if ($li_s>0)
				{
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera($li_totprenom,$li_totant,$io_pdf); // Imprimimos pie de la cabecera
				}
				if ($io_pdf->ezPageCount==$li_numpag)
				{// Hacemos el commit de los registros que se desean imprimir
					$io_pdf->transaction('commit');
				}
				else
				{// Hacemos un rollback de los registros, agregamos una nueva p谩gina y volvemos a imprimir
					$io_pdf->transaction('rewind');
					$io_pdf->ezNewPage(); // Insertar una nueva p谩gina
					uf_print_cabecera($ls_codper,$ls_nomper,$io_pdf); // Imprimimos la cabecera del registro
					uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
					uf_print_pie_cabecera($li_totprenom,$li_totant,$io_pdf); // Imprimimos pie de la cabecera
				}
			}
			unset($la_data);
			$io_report->rs_data->MoveNext();			
		}
		//$io_report->DS->resetds("codper");
		$li_total_pre=$io_fun_nomina->uf_formatonumerico(abs($li_total_pre));
		$li_total_his=$io_fun_nomina->uf_formatonumerico(abs($li_total_his));
		uf_print_piepagina($li_total_pre,$li_total_his,$io_pdf);
		if($lb_valido) // Si no ocurrio ning煤n error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi贸n de los n煤meros de p谩gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo alg煤n error
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
