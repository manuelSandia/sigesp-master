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
		$li_tm=$io_pdf->getTextWidth(16,$as_titulo);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,710,16,$as_titulo); // Agregar el título

		$io_pdf->setStrokeColor(0,0,0);
        $io_pdf->Rectangle(15,60,580,140);
		$io_pdf->line(15,180,595,180);
		$io_pdf->line(15,160,595,160);
		$io_pdf->line(205,60,205,200);
		$io_pdf->line(400,60,400,180);
		$io_pdf->addText(65,185,9,"Por la INSTITUCION"); // Agregar el título
		$io_pdf->addText(335,185,9,"Por la EMPRESA CONTRATISTA"); // Agregar el título
		$io_pdf->addText(68,165,9,"Ingeniero Inspector"); // Agregar el título
		$io_pdf->addText(260,165,9,"Representante Legal"); // Agregar el título
		$io_pdf->addText(470,165,9,"Ing. Residente"); // Agregar el título
		$io_pdf->addText(70,105,8,"Nombre y Apellido"); // Agregar el título
		$io_pdf->addText(270,105,8,"Nombre y Apellido"); // Agregar el título
		$io_pdf->addText(460,105,8,"Nombre y Apellido"); // Agregar el título
		$io_pdf->addText(60,85,8,"C.I."); // Agregar el título
		$io_pdf->addText(260,85,8,"C.I."); // Agregar el título
		$io_pdf->addText(450,85,8,"C.I."); // Agregar el título
		$io_pdf->addText(60,65,8,"CIV"); // Agregar el título
		$io_pdf->addText(450,65,8,"CIV"); // Agregar el título

		//LINEAS DE FIRMAS
		$io_pdf->line(45,115,170,115);
		$io_pdf->line(245,115,370,115);
		$io_pdf->line(435,115,550,115);
		$io_pdf->line(75,85,175,85);
		$io_pdf->line(275,85,375,85);
		$io_pdf->line(465,85,565,85);
		$io_pdf->line(80,65,170,65);
		$io_pdf->line(470,65,560,65);


		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codcon,$as_fecinicon,$as_fecfincon,$as_fecact,$as_cedinsact,$as_civinsact,$as_inspector,$as_cedresact,$as_nomresact,$as_civresact,
							   $as_contratista,$as_representante,$as_cedrepresentante,$as_desobr,$as_ejecucion,$as_diaact,$as_mesact,$as_anioact,$as_diafinact,
							   $as_mesfinact,$as_aniofinact,$as_feccon,&$io_pdf)

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
		$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
		$la_empresa=$_SESSION["la_empresa"];
		
		$la_data[1]=array('titulo'=>'<b> Obra: </b>'.$as_desobr);
		$la_data[2]=array('titulo'=>'<b> Contrato No.: </b>'.$as_codcon.'             Fecha: '.$as_feccon);
		$la_data[3]=array('titulo'=>'<b> Empresa: </b>'.$as_contratista);
		
		$la_columnas=array('titulo'=>'');
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
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>500))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$io_pdf->EzSetDy(-15);
		$la_data[1]=array('1'=>'     En el día de hoy, a los '.$as_diaact.' días del mes de '.$as_mesact.' de '.$as_anioact.'; reunidos '. 
		'por una parte el Ciudadano, '.$as_inspector.' titular de la C.I. '.$as_cedinsact.', CIV '.$as_civinsact.', en su carácter de Ingeniero Inspector de la obra, '.
		'en representación de '.$ls_nomemp.' y por la otra '.$as_representante.',titular de la C.I. '.$as_cedrepresentante.' y el ciudadano '.$as_nomresact.', '. 
		'Ingeniero Residente, titular de la C.I. '.$as_cedresact.', CIV '.$as_civresact.', en representación de la empresa contratista '.$as_contratista.',  '.
		'reunidos con la finalidad de dejar constancia de  que  cesaron los motivos que originaron la  paralización en los referidos trabajos, quedando conforme.'.
		'Este trámite cumple con los requisitos exigidos en el Articulo 38 de la Ley de Contraloría General de la República y del Sistema Nacional de Control Fiscal.');
		$la_data[2]=array('1'=>' ');
		$la_data[3]=array('1'=>'     En prueba de conformidad se firman (2) ejemplares de  la presente Acta.');
		$la_columnas=array('1'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 14,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('1'=>array('justification'=>'justify','width'=>480))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();				
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
	 $ls_codact=$io_fun_sob->uf_obtenervalor_get("codact","S");
	 $ls_codcon=$io_fun_sob->uf_obtenervalor_get("codcon","");
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
	 $ls_titulo='<b>ACTA DE REINICIO</b>';
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_actacontrato($ls_codact,$ls_codcon,"6"); // Cargar el DS con los datos del reporte
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
			$io_pdf->ezSetCmMargins(6,6,4,4); // Configuración de los margenes en centímetros
//			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			if(!$io_report->rs_data->EOF)
			{			
				$ls_desobr=$io_report->rs_data->fields["desobr"];
				$ls_codcon=$io_report->rs_data->fields["codcon"];
				$ls_feccon=$io_report->rs_data->fields["feccon"];
				$ls_fecinicon=$io_report->rs_data->fields["fecinicon"];
				$ls_fecfincon=$io_report->rs_data->fields["fecfincon"];
				$ls_fecact=$io_report->rs_data->fields["fecact"];
				$ls_fecfinact=$io_report->rs_data->fields["fecfinact"];
				$ls_cedinsact=$io_report->rs_data->fields["cedinsact"];
				$ls_civinsact=$io_report->rs_data->fields["civinsact"];
				$ls_civresact=$io_report->rs_data->fields["civresact"];
				$ls_cedresact=$io_report->rs_data->fields["cedresact"];
				$ls_nomresact=$io_report->rs_data->fields["nomresact"];
				$ls_inspector=$io_report->rs_data->fields["inspector"];
				$ls_contratista=$io_report->rs_data->fields["contratista"];
				$ls_representante=$io_report->rs_data->fields["representante"];
				$ls_cedrepresentante=$io_report->rs_data->fields["cedrepresentante"];
				$ls_diaact=substr($ls_fecact,8,2);
				$ls_mesact=substr($ls_fecact,5,2);
				$ls_anioact=substr($ls_fecact,0,4);
				$ls_mesact=$io_fecha->uf_load_nombre_mes($ls_mesact);
				
				$ls_fecinicon=$io_funciones->uf_convertirfecmostrar($ls_fecinicon);
				$ls_fecfincon=$io_funciones->uf_convertirfecmostrar($ls_fecfincon);
				$ls_feccon=$io_funciones->uf_convertirfecmostrar($ls_feccon);
				$ls_diafinact=substr($ls_fecfinact,8,2);
				$ls_mesfinact=substr($ls_fecfinact,5,2);
				$ls_aniofinact=substr($ls_fecfinact,0,4);
				$ls_mesfinact=$io_fecha->uf_load_nombre_mes($ls_mesfinact);
				uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
				uf_print_cabecera($ls_codcon,$ls_fecinicon,$ls_fecfincon,$ls_fecact,$ls_cedinsact,$ls_civinsact,$ls_inspector,$ls_cedresact,$ls_nomresact,$ls_civresact,
								  $ls_contratista,$ls_representante,$ls_cedrepresentante,$ls_desobr,"",$ls_diaact,$ls_mesact,$ls_anioact,$ls_diafinact,$ls_mesfinact,$ls_aniofinact,$ls_feccon,&$io_pdf);
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
			//print(" close();");
			print("</script>");		
		}
		
	}

?>
