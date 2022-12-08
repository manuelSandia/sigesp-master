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
		// Fecha Creaci�n: 03/05/2010 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_personal_rac_rec.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_codorgvipladin,$as_grupovipladin,$as_vigenciavipladin,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 03/05/2010 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,755,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,530,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,11,$as_titulo); // Agregar el t�tulo
		$io_pdf->addText(50,510,11,"Fecha de Vigencia:"); // Agregar el t�tulo
		$io_pdf->addText(150,510,11,$as_vigenciavipladin); // Agregar el t�tulo
		$io_pdf->addText(50,495,11,"Organismo:"); // Agregar el t�tulo
		$io_pdf->addText(150,495,11,$as_codorgvipladin); // Agregar el t�tulo
		$io_pdf->addText(50,480,11,"Grupo:"); // Agregar el t�tulo
		$io_pdf->addText(150,480,11,$as_grupovipladin); // Agregar el t�tulo
		$io_pdf->addText(712,560,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(718,553,7,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_tiponomina,&$io_cabecera,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 03/05/2010 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->saveState();

		$io_pdf->addText(600,480,11,"Tipo de Nombramiento:"); // Agregar el t�tulo
		$io_pdf->addText(720,480,11,$as_tiponomina); // Agregar el t�tulo
		$io_pdf->ezSety(475);
        $io_pdf->setColor(0.9,0.9,0.9);
        $io_pdf->filledRectangle(50,461,700,$io_pdf->getFontHeight(10));
        $io_pdf->setColor(0,0,0);
		$la_data[1]=array('nomina'=>'<b>N�mina</b>',
						  'cedula'=>'<b>C�dula</b>',
						  'nombre'=>'<b>Apellidos y Nombres</b>',
						  'cargo'=>'<b>Denominaci�n del Cargo</b>',
						  'clase'=>'<b>Clase</b>',
						  'grado'=>'<b>GR</b>',
						  'tipo'=>'<b>TC</b>',
						  'basico'=>'<b>B�sico</b>',
						  'compensacion'=>'<b>Compensaci�n</b>',
						  'total'=>'<b>Total</b>');
		$la_columna=array('nomina'=>'',
						  'cedula'=>'',
						  'nombre'=>'',
						  'cargo'=>'',
						  'clase'=>'',
						  'grado'=>'',
						  'tipo'=>'',
						  'basico'=>'',
						  'compensacion'=>'',
						  'total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'xPos' => 405,
						 'cols'=>array('nomina'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>145), // Justificaci�n y ancho de la columna
								   'cargo'=>array('justification'=>'center','width'=>140), // Justificaci�n y ancho de la columna
						 			   'clase'=>array('justification'=>'center','width'=>45), // Justificaci�n y ancho de la columna
						 			   'grado'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'tipo'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'basico'=>array('justification'=>'center','width'=>60), // Justificaci�n y ancho de la columna
						 			   'compensacion'=>array('justification'=>'center','width'=>70), // Justificaci�n y ancho de la columna
						 			   'total'=>array('justification'=>'center','width'=>60))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,$as_minorguniadm,$as_ofiuniadm,$as_uniuniadm,$as_depuniadm,$as_prouniadm,$as_desuniadm,
							  $as_codubivipladin,$as_distritovipladin,$as_municipiovipladin,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 03/05/2010 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('nomina'=>'',
						  'cedula'=>'',
						  'nombre'=>'',
						  'cargo'=>'',
						  'clase'=>'',
						  'grado'=>'',
						  'tipo'=>'',
						  'basico'=>'',
						  'compensacion'=>'',
						  'total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.1,
						 'innerLineThickness' =>0.1,
						 'xPos' => 405,
						 'cols'=>array('nomina'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'cedula'=>array('justification'=>'center','width'=>50), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>145), // Justificaci�n y ancho de la columna
						 			   'cargo'=>array('justification'=>'left','width'=>140), // Justificaci�n y ancho de la columna
						 			   'clase'=>array('justification'=>'center','width'=>45), // Justificaci�n y ancho de la columna
						 			   'grado'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'tipo'=>array('justification'=>'center','width'=>40), // Justificaci�n y ancho de la columna
						 			   'basico'=>array('justification'=>'right','width'=>60), // Justificaci�n y ancho de la columna
						 			   'compensacion'=>array('justification'=>'right','width'=>70), // Justificaci�n y ancho de la columna
						 			   'total'=>array('justification'=>'right','width'=>60))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		$io_pdf->ezSetDy(5);
		$la_data[1]=array('texto1'=>'<b>Ubicaci�n Administrativa</b>',
						  'texto2'=>$as_minorguniadm.$as_ofiuniadm.$as_uniuniadm.$as_depuniadm.$as_prouniadm,
						  'texto3'=>$as_desuniadm);
		$la_data[2]=array('texto1'=>'<b>Ubicaci�n Geogr�fica</b>',
						  'texto2'=>$as_codubivipladin,
						  'texto3'=>$as_distritovipladin.'  '.$as_municipiovipladin);
		$la_columna=array('texto1'=>'',
						  'texto2'=>'',
						  'texto3'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.1,
						 'innerLineThickness' =>0.1,
						 'xPos' => 405,
						 'cols'=>array('texto1'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'texto2'=>array('justification'=>'left','width'=>80), // Justificaci�n y ancho de la columna
						 			   'texto3'=>array('justification'=>'left','width'=>500))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		$io_pdf->ezSetDy(7);
		$la_data[1]=array('texto1'=>'<b>___________________________________________________________________________________________________________________________________________________________</b>');
		$la_columna=array('texto1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>700, // Ancho de la tabla
						 'maxWidth'=>700, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.1,
						 'innerLineThickness' =>0.1,
						 'xPos' => 405,
						 'cols'=>array('texto1'=>array('justification'=>'left','width'=>700))); // Justificaci�n y ancho de la columna
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
	$ls_titulo="<b>REGISTRO DE ASIGNACI�N DE CARGOS ORDENADO POR C�DIGO DE N�MINA</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codnomdes=$io_fun_nomina->uf_obtenervalor_get("codnomdes","");
	$ls_codnomhas=$io_fun_nomina->uf_obtenervalor_get("codnomhas","");
	$ls_codperdes=$io_fun_nomina->uf_obtenervalor_get("codperdes","");
	$ls_codperhas=$io_fun_nomina->uf_obtenervalor_get("codperhas","");
	$ls_compensacion=$io_fun_nomina->uf_obtenervalor_get("compensacion","");
	$ls_anio=$io_fun_nomina->uf_obtenervalor_get("anio","");	
	$ls_mes=$io_fun_nomina->uf_obtenervalor_get("mes","");	
	$ls_codorgvipladin=trim($io_report->uf_select_config("SNO","CONFIG","COD_ORGANISMO_VIPLADIN","","C"));
	$ls_grupovipladin=trim($io_report->uf_select_config("SNO","CONFIG","GRUPO_VIPLADIN","","C"));
	$ls_codubivipladin=trim($io_report->uf_select_config("SNO","CONFIG","COD_UBICACION_VIPLADIN","","C"));
	$ls_distritovipladin=trim($io_report->uf_select_config("SNO","CONFIG","DISTRITO_VIPLADIN","","C"));
	$ls_municipiovipladin=trim($io_report->uf_select_config("SNO","CONFIG","MUNICIPIO_VIPLADIN","","C"));
	$ls_vigenciavipladin=trim($io_report->uf_select_config("SNO","CONFIG","VIGENCIA_VIPLADIN","","C"));
	$ls_compensacion="'".$ls_compensacion."'";
	$ls_compensacion=str_replace("-","','",$ls_compensacion);
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadopersonal_rac($ls_codnomdes,$ls_codnomhas,$ls_codperdes,$ls_codperhas,$ls_anio,$ls_mes,'CODIGONOMINA'); // Obtenemos el detalle del reporte
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
		$io_pdf->ezSetCmMargins(5.3,1.5,3,3); // Configuraci�n de los margenes en cent�metros
		uf_print_encabezado_pagina($ls_titulo,$ls_codorgvipladin,$ls_grupovipladin,$ls_vigenciavipladin,$io_pdf); // Imprimimos el encabezado de la p�gina
		$io_pdf->ezStartPageNumbers(750,45,10,'','',1); // Insertar el n�mero de p�gina
		$ls_codded_ant="";
		$li_i=0;
		while((!$io_report->rs_data->EOF)&&($lb_valido))
		{
			$ls_codnom=$io_report->rs_data->fields["codnom"];
			$ls_codper=$io_report->rs_data->fields["codper"];
			$ls_codded=$io_report->rs_data->fields["codded"];
			$ls_minorguniadm=$io_report->rs_data->fields["minorguniadm"];
			$ls_ofiuniadm=$io_report->rs_data->fields["ofiuniadm"];
			$ls_uniuniadm=$io_report->rs_data->fields["uniuniadm"];
			$ls_depuniadm=$io_report->rs_data->fields["depuniadm"];
			$ls_prouniadm=$io_report->rs_data->fields["prouniadm"];
			$ls_desuniadm=$io_report->rs_data->fields["desuniadm"];
			$ls_codunirac=$io_report->rs_data->fields["codunirac"];
			$ls_cedula=$io_report->rs_data->fields["cedula"];
			$ls_nombre=$io_report->rs_data->fields["nombre"];
			$ls_denasicar=$io_report->rs_data->fields["denasicar"];
			$ls_codgra=$io_report->rs_data->fields["codgra"];
			$ls_claasicar=$io_report->rs_data->fields["claasicar"];
			$ls_codtipper=$io_report->rs_data->fields["codtipper"];
			$li_monsalgra=$io_report->rs_data->fields["monsalgra"];
			$li_moncomgra=$io_report->rs_data->fields["moncomgra"];
			if($li_moncomgra<=0)
			{
				$lb_valido=$io_report->uf_buscar_valor_concepto_personal($ls_codnom,$ls_codper,$ls_anio,$ls_mes,$ls_compensacion,&$li_moncomgra);
			}
			$li_total=$li_monsalgra+$li_moncomgra;
			$li_monsalgra=number_format($li_monsalgra,2,",",".");
			$li_moncomgra=number_format($li_moncomgra,2,",",".");
			$li_total=number_format($li_total,2,",",".");
			switch(substr($ls_codtipper,2,2))
			{
				case "02":
					$ls_codtipper='PR';
				break;
				case "03":
					$ls_codtipper='AD';
				break;
			}
			$ls_tiponomina='';
			switch($ls_codded)
			{
				case "100":
					$ls_tiponomina='Fijo';
				break;
				case "200":
					$ls_tiponomina='Fijo';
				break;
				case "300":
					$ls_tiponomina='Contratado';
				break;
			}

			if($ls_codded_ant!=$ls_codded)
			{
				$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
				uf_print_cabecera($ls_tiponomina,$io_cabecera,$io_pdf); // Imprimimos la cabecera del registro
				$ls_codded_ant=$ls_codded;
			}
			$li_i++;
			$la_data[$li_i]=array('nomina'=>$ls_codunirac,'cedula'=>$ls_cedula,'nombre'=>$ls_nombre,'cargo'=>$ls_denasicar,'grado'=>$ls_codgra,
						  		  'clase'=>$ls_claasicar,'tipo'=>$ls_codtipper,'basico'=>$li_monsalgra,'compensacion'=>$li_moncomgra,'total'=>$li_total);
			uf_print_detalle($la_data,$ls_minorguniadm,$ls_ofiuniadm,$ls_uniuniadm,$ls_depuniadm,$ls_prouniadm,$ls_desuniadm,
							 $ls_codubivipladin,$ls_distritovipladin,$ls_municipiovipladin,$io_pdf); // Imprimimos el detalle 
			unset($la_data);
			$li_i=0;
			$io_report->rs_data->MoveNext();
			$ls_codded=$io_report->rs_data->fields["codded"];
			if($ls_codded_ant!=$ls_codded)
			{
				$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
				if($ls_codded!="")
				{
					$io_pdf->ezNewPage(); // Insertar una nueva p�gina
				}
				unset($io_cabecera);
			}			
		}
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