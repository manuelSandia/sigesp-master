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
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,713,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=296-($li_tm/2);
		$io_pdf->addText($tm,670,11,$as_titulo); // Agregar el título
		$io_pdf->addText(50,700,11,$ls_nomemp); // Agregar el título
		$io_pdf->addText(50,690,11,$ls_sigemp); // Agregar el título
		$io_pdf->addText(540,690,9,date("d/m/Y")); // Agregar la Fecha
        $io_pdf->Rectangle(15,60,180,80);
		$io_pdf->line(15,120,195,120);
		$io_pdf->line(105,60,105,140);
		$io_pdf->addText(25,125,8,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(115,125,8,"VERIFICADO POR"); // Agregar el título
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codasi,$as_codcon,$as_feccon,$as_fecinicon,$as_fecfincon,$as_obscon,$as_fecasi,$ai_monto,$ai_montotasi,$as_codant,
							   $as_fecant,$ai_montotant,$as_desobr,$ls_codpro,$ls_codproins,$ls_empcon,$ls_empins,&$io_pdf)

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
		$la_data[1]=array('1'=>'<b>Codigo:</b> '.$as_codcon,'2'=>'<b>Fecha Contrato:</b> '.$as_feccon,'3'=>'<b>Monto Ofertado:</b>','4'=>$ai_monto);
		$la_data[2]=array('1'=>'<b>Fecha Inicio:</b> ','2'=>$as_fecinicon,'3'=>'<b>Fecha Fin:</b>','4'=>$as_fecfincon);
		$la_data[3]=array('1'=>'<b>Asignacion:</b> '.$as_codasi,'2'=>'<b>Fecha Asignacion:</b> '.$as_fecasi,'3'=>'<b>Monto Asignacion:</b>','4'=>$ai_montotasi);
		$la_data[4]=array('1'=>'<b>Monto Anticipo:</b> ','2'=>$ai_montotant.' Bs.','3'=>'<b>Anticipo:</b> '.$as_codant,'4'=>'<b>Fecha Anticipo:</b> '.$as_fecant);
		$la_columnas=array('1'=>'','2'=>'','3'=>'','4'=>'');
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
						 'cols'=>array('1'=>array('justification'=>'left','width'=>135),
						 			   '2'=>array('justification'=>'left','width'=>145),
									   '3'=>array('justification'=>'left','width'=>135),
									   '4'=>array('justification'=>'left','width'=>135))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b> Obra: </b>'.$as_desobr);
		$la_data[2]=array('titulo'=>'');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
		$la_data[1]=array('titulo'=>'<b> Empresa Inspectora: </b>'.$ls_codproins." ".$ls_empins);
		$la_data[2]=array('titulo'=>'<b> Empresa Contratista: </b>'.$ls_codpro." ".$ls_empcon);
		$la_data[3]=array('titulo'=>'');
		$la_columnas=array('titulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'shadeCol'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'shadeCol2'=>array((249/255),(249/255),(249/255)), // Color de la sombra
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('titulo'=>array('justification'=>'left','width'=>550))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);
	}// end function uf_print_cabecera
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_cuentas($la_data,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_cuentas
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columnas=array('estructura'=>'<b>Estructura</b>',
						   'estcla'=>'<b>Estatus</b>',
						   'cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominacion</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('estructura'=>array('justification'=>'center','width'=>140), // Justificación y ancho de la columna
						 			   'estcla'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'cuenta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>270))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle_cuentas
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_valuacion($la_data,$li_montotval,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_valuacion
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-15);
		$la_columnas=array('codval'=>'<b>Valuacion</b>',
						   'fecinival'=>'<b>Fecha Inicio</b>',
						   'fecfinval'=>'<b>Fecha Fin</b>',
						   'obsval'=>'<b>Observacion</b>',
						   'montotval'=>'<b>Monto</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codval'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'fecinival'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'fecfinval'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'obsval'=>array('justification'=>'left','width'=>210), // Justificación y ancho de la columna
						 			   'montotval'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'VALUACIONES ASOCIADAS',$la_config);
		unset($la_data);
		unset($la_columnas);
		unset($la_config);

		$la_data[1]=array('codval'=>'TOTAL',
						   'montotval'=>$li_montotval);
		$la_columnas=array('codval'=>'<b></b>',
						   'montotval'=>'<b></b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codval'=>array('justification'=>'right','width'=>450), // Justificación y ancho de la columna
						 			   'montotval'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle_valuacion
	//-----------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
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
	 $ls_codasi=$io_fun_sob->uf_obtenervalor_get("codasi","");
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
	 $ls_titulo='<b>CONTRATO</b>';
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_contrato($ls_codasi,$ls_codcon); // Cargar el DS con los datos del reporte
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
			$io_pdf->ezSetCmMargins(5,6,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página
			if(!$io_report->rs_data->EOF)
			{			
				$ls_codasi=$io_report->rs_data->fields["codasi"];
				$ls_codcon=$io_report->rs_data->fields["codcon"];
				$ls_feccon=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["feccon"]);
				$ls_fecinicon=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecinicon"]);
				$ls_fecfincon=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecfincon"]);
				$ls_fecant=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecant"]);
				$ls_obscon=$io_report->rs_data->fields["obscon"];
				$ls_codant=$io_report->rs_data->fields["codant"];
				$ls_desobr=$io_report->rs_data->fields["desobr"];
				$ls_codpro=$io_report->rs_data->fields["cod_pro"];
				$ls_codproins=$io_report->rs_data->fields["cod_pro_ins"];
				$ls_empcon=$io_report->rs_data->fields["empcon"];
				$ls_empins=$io_report->rs_data->fields["empins"];
				$ls_fecasi=$io_funciones->uf_convertirfecmostrar($io_report->rs_data->fields["fecasi"]);
				$li_monto=number_format($io_report->rs_data->fields["monto"],2,",",".");
				$li_montotasi=number_format($io_report->rs_data->fields["montotasi"],2,",",".");
				$li_montotant=number_format($io_report->rs_data->fields["montotant"],2,",",".");
				uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
				uf_print_cabecera($ls_codasi,$ls_codcon,$ls_feccon,$ls_fecinicon,$ls_fecfincon,$ls_obscon,$ls_fecasi,$li_monto,$li_montotasi,$ls_codant,$ls_fecant,
							      $li_montotant,$ls_desobr,$ls_codpro,$ls_codproins,$ls_empcon,$ls_empins,&$io_pdf);
				$lb_valido=$io_report->uf_select_cuentas_asignacion($ls_codasi); 
				$li_s=0;
				while(!$io_report->rs_datacuentas->EOF)
				{
					$li_s++;
					$ls_codestpro1=$io_report->rs_datacuentas->fields["codestpro1"];
					$ls_codestpro2=$io_report->rs_datacuentas->fields["codestpro2"];
					$ls_codestpro3=$io_report->rs_datacuentas->fields["codestpro3"];
					$ls_codestpro4=$io_report->rs_datacuentas->fields["codestpro4"];
					$ls_codestpro5=$io_report->rs_datacuentas->fields["codestpro5"];
					$ls_estcla=$io_report->rs_datacuentas->fields["estcla"];
					$ls_spgcuenta=$io_report->rs_datacuentas->fields["spg_cuenta"];
					$ls_denominacion=$io_report->rs_datacuentas->fields["denominacion"];
					$ls_codpro=$ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
					$io_fun_sob->uf_formatoprogramatica($ls_codpro,&$ls_programatica);
					if($ls_estcla=="P")
					{
						$ls_estcla="PROYECTO";
					}
					else
					{
						$ls_estcla="ACCION";
					}
					$la_data[$li_s]=array('estructura'=>$ls_programatica,'estcla'=>$ls_estcla,'cuenta'=>$ls_spgcuenta,'denominacion'=>$ls_denominacion);
					
					$io_report->rs_datacuentas->MoveNext();
				}
				if($li_s>0)
				{
					uf_print_detalle_cuentas($la_data,&$io_pdf);
					unset($la_data);
				}
				$lb_valido=$io_report->uf_select_valuaciones($ls_codcon); 
				$li_x=0;
				$li_totmontotval=0;
				while(!$io_report->rs_datavaluaciones->EOF)
				{
					$li_x++;
					$ls_codcon=$io_report->rs_datavaluaciones->fields["codcon"];
					$ls_codval=$io_report->rs_datavaluaciones->fields["codval"];
					$ls_fecinival=$io_funciones->uf_convertirfecmostrar($io_report->rs_datavaluaciones->fields["fecinival"]);
					$ls_fecfinval=$io_funciones->uf_convertirfecmostrar($io_report->rs_datavaluaciones->fields["fecfinval"]);
					$ls_obsval=$io_report->rs_datavaluaciones->fields["obsval"];
					$li_montotval=$io_report->rs_datavaluaciones->fields["montotval"];
					$li_totmontotval=$li_totmontotval+$li_montotval;
					$li_montotval=number_format($li_montotval,2,",",".");
					$la_data[$li_x]=array('codval'=>$ls_codval,'fecinival'=>$ls_fecinival,'fecfinval'=>$ls_fecfinval,'obsval'=>$ls_obsval,'montotval'=>$li_montotval);
					
					$io_report->rs_datavaluaciones->MoveNext();
				}
				if($li_x>0)
				{
					uf_print_detalle_valuacion($la_data,$li_totmontotval,&$io_pdf);
					unset($la_data);
				}
		
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
			print(" close();");
			print("</script>");		
		}
		
	}

?>
