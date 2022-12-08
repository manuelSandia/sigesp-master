<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Documentos
//  ORGANISMO: Ninguno en particular
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		//	    Arguments: as_titulo // T�tulo del reporte
		//    Description: funci�n que guarda la seguridad de quien gener� el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creaci�n: 11/03/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Gener� el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_solicitudesf1.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
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
		$io_pdf->line(15,40,585,40);
        $io_pdf->Rectangle(15,700,570,60);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el t�tulo
		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
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
		//	    Arguments: la_data      // arreglo de informaci�n
		//				   ai_i         // total de registros
		//				   li_totmonsol // total de solicitudes (Montos)
		//	    		   io_pdf       // Instancia de objeto pdf
		//    Description: Funci�n que imprime el detalle del reporte
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 16/06/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-2);
		$la_datatit[1]=array('numsol'=>'<b>Solicitud</b>','numrecdoc'=>'<b>Factura</b>','nombre'=>'<b>Proveedor/Beneficiario</b>',
							 'fecemidoc'=>'<b>Fecha Factura</b>','dended'=>'<b>Retencion</b>');
		$la_columnas=array('numsol'=>'',
						   'numrecdoc'=>'',
						   'nombre'=>'',
						   'fecemidoc'=>'',
						   'dended'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>90), // Justificaci�n y ancho de la columna
						 			   'numrecdoc'=>array('justification'=>'center','width'=>100), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'center','width'=>210), // Justificaci�n y ancho de la columna
						 			   'fecemidoc'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
						 			   'dended'=>array('justification'=>'center','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_datatit,$la_columnas,'',$la_config);

		$la_columnas=array('numsol'=>'',
						   'numrecdoc'=>'',
						   'nombre'=>'',
						   'fecemidoc'=>'',
						   'dended'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 12,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>90), // Justificaci�n y ancho de la columna
						 			   'numrecdoc'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'nombre'=>array('justification'=>'left','width'=>210), // Justificaci�n y ancho de la columna
						 			   'fecemidoc'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
						 			   'dended'=>array('justification'=>'left','width'=>90))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle
	//-----------------------------------------------------------------------------------------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_estmodest=$_SESSION["la_empresa"]["estmodest"];
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo="<b>SOLICITUDES POR GENERAR RETENCION</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_tipproben=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_cxp->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_cxp->uf_obtenervalor_get("codprobenhas","");
	$ls_tipret=$io_fun_cxp->uf_obtenervalor_get("tipret","1");
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$rs_data=$io_report->uf_select_solicitudessinretencion($ls_tipproben,$ls_codprobendes,$ls_codprobenhas,$ls_tipret,&$lb_valido); // Cargar el DS con los datos del reporte
		if($lb_valido==false) // Existe alg�n error � no hay registros
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
			$io_pdf->ezSetCmMargins(3.6,4.8,3,3); // Configuraci�n de los margenes en cent�metros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el n�mero de p�gina
			$li_totrow=$io_report->io_sql->num_rows($rs_data);
			if($li_totrow>0)
			{			
				$li_i=0;
				while((!$rs_data->EOF))
				{
					$ls_numsol=$rs_data->fields["numsol"];
					$ls_nombre=$rs_data->fields["nombre"];
					$ls_numrecdoc=$rs_data->fields["numrecdoc"];
					$ld_fecemisol=$rs_data->fields["fecemidoc"];
					$ls_dended=$rs_data->fields["dended"];
					$ld_fecemisol=$io_funciones->uf_convertirfecmostrar($ld_fecemisol);
					$li_i++;
					$la_data[$li_i]=array('numsol'=>$ls_numsol,'numrecdoc'=>$ls_numrecdoc,'nombre'=>$ls_nombre,'fecemidoc'=>$ld_fecemisol,
										  'dended'=>$ls_dended);
					$rs_data->MoveNext();
				}
				uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
				uf_print_detalle($la_data,&$io_pdf);
			}
			else
			{
				$lb_valido=false;
			}
		}
		if($lb_valido) // Si no ocurrio ning�n error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi�n de los n�meros de p�gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");
		}
		
	}

?>
