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

	/*/-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_sep;
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_sep->uf_load_seguridad_reporte("SEP","sigesp_sep_r_solicitudes.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------*/

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,555,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el t�tulo
		$io_pdf->addText(500,750,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(506,743,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 13/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_columnas=array('numdoc'=>'<b>Documento</b>',
						   'monto'=>'<b>Monto</b>',
						   'fecha'=>'<b>Fecha Contabilizaci�n</b>',
						   'codusu'=>'<b>Contabilizado por</b>',
						   'modulo'=>'<b>Modulo</b>',
						   'tipoope'=>'<b>Tipo Operaci�n</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>1, // Sombra entre l�neas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numdoc'=>array('justification'=>'center','width'=>90), // Justificaci�n y ancho de la columna
						 			   'monto'=>array('justification'=>'center','width'=>90), // Justificaci�n y ancho de la columna
						 			   'fecha'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
						 			   'codusu'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
									   'modulo'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
									   'tipoope'=>array('justification'=>'center','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo="<b>DOCUMENTOS CONTABILIZADOS </b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codusu   = $_GET["codusu"];
	$ls_fecdes   = $_GET["fecdes"];
	$ls_fechas   = $_GET["fechas"];
	$ls_modulo   = $_GET["modulo"];
	$ls_orden    = $_GET["orden"];
	$ls_concepto = $_GET["concepto"];
	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("sigesp_mis_class_report.php");
	$io_report=new sigesp_mis_class_report();
	//$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	/*if($lb_valido)
	{*/
		$rs_data=$io_report->uf_select_documentos_contabilizados($ls_codusu,$ls_fecdes,$ls_fechas,$ls_modulo,$ls_concepto,$ls_orden);
		//var_dump($rs_data);
		if($rs_data->EOF) // Existe alg�n error � no hay registros
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
			$io_pdf->ezSetCmMargins(3.6,2.5,3,3); // Configuraci�n de los margenes en cent�metros
			uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la p�gina
			$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el n�mero de p�gina
			$li_s=0;
			
			while(!$rs_data->EOF){
				$ls_numdoc  = $rs_data->fields['numdoc']; 
				$ld_monto   = $rs_data->fields['monto'];
				$ls_fecha   = $rs_data->fields['fecha'];
				$ls_procede = $rs_data->fields['procede'];
				$ls_tipoope = $rs_data->fields['desproc'];
				$ls_codusur = $rs_data->fields['codusu'];
				$ls_modulo  = substr($ls_procede,0,3);
				$ls_fecha   = $io_funciones->uf_convertirfecmostrar($ls_fecha);
				$ld_monto   = number_format($ld_monto,2,",",".");
				
				
				$la_data[$li_s]= array('numdoc'=>$ls_numdoc,'monto'=>$ld_monto,'fecha'=>$ls_fecha,'codusu'=>$ls_codusur,'modulo'=>$ls_modulo,'tipoope'=>$ls_tipoope);
				$rs_data->MoveNext();
				$li_s++;
			}		
					
			uf_print_detalle($la_data,&$io_pdf);
			unset($la_data);
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
//}		
?>
