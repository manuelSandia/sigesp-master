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
		$io_pdf->addText(540,690,9,date("d/m/Y")); // Agregar la Fecha
/*        $io_pdf->Rectangle(15,60,180,80);
		$io_pdf->line(15,120,195,120);
		$io_pdf->line(105,60,105,140);
		$io_pdf->addText(25,125,8,"ELABORADO POR"); // Agregar el título
		$io_pdf->addText(115,125,8,"VERIFICADO POR"); // Agregar el título
*/		$io_pdf->restoreState();
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
		//	    Arguments: la_data // arreglo de información
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el detalle por concepto
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 27/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columnas=array('ubicacion'=>'<b>Ubicacion Geografica</b>',
						   'desobr'=>'<b>Obra</b>',
						   'monobr'=>'<b>Monto Obras</b>',
						   'contratos'=>'<b>Contratos</b>',
						   'anticipos'=>'<b>Total Anticipos</b>',
						   'valuaciones'=>'<b>Total Valuaciones</b>',
						   'porcobrar'=>'<b>Total por Cobrar</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'titleFontSize' => 10,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>560, // Ancho de la tabla
						 'maxWidth'=>560, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('ubicacion'=>array('justification'=>'left','width'=>120), // Justificación y ancho de la columna
						 			   'desobr'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'monobr'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'contratos'=>array('justification'=>'left','width'=>80), // Justificación y ancho de la columna
						 			   'anticipos'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'valuaciones'=>array('justification'=>'right','width'=>60), // Justificación y ancho de la columna
						 			   'porcobrar'=>array('justification'=>'right','width'=>60))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle_cuentas
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
	 $ls_coddes=$io_fun_sob->uf_obtenervalor_get("coddes","");
	 $ls_codhas=$io_fun_sob->uf_obtenervalor_get("codhas","");
	 $ls_fecregdes=$io_fun_sob->uf_obtenervalor_get("fecregdes","");
	 $ls_fecreghas=$io_fun_sob->uf_obtenervalor_get("fecreghas","");
	 $ls_codorgeje=$io_fun_sob->uf_obtenervalor_get("codorgeje","");
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
	 $ls_titulo='<b>LISTADO DE OBRAS</b>';
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{

		$lb_valido=$io_report->uf_select_listadoobras($ls_coddes,$ls_codhas,$ls_fecregdes,$ls_fecreghas,$ls_codorgeje); // Cargar el DS con los datos del reporte
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
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			$li_s=0;
			while(!$io_report->rs_data->EOF)
			{			
				$li_s++;
				$ls_codobr=$io_report->rs_data->fields["codobr"];
				$ls_desobr=$io_report->rs_data->fields["desobr"];
				$li_monto=$io_report->rs_data->fields["monto"];
				$ls_contratos=$io_report->uf_select_contratosobras($ls_codobr);
				$ls_despai=$io_report->rs_data->fields["despai"];
				$ls_desest=$io_report->rs_data->fields["desest"];
				$ls_desmun=$io_report->rs_data->fields["desmun"];
				$ls_despar=$io_report->rs_data->fields["despar"];
				$ls_descom=$io_report->rs_data->fields["descom"];
				$li_anticipos=$io_report->rs_data->fields["anticipo"];
				$li_valuaciones=$io_report->rs_data->fields["valuacion"];
				$li_porcobrar=$li_monto-$li_valuaciones;  //REVISAR FORMULA
				$li_monto=number_format($li_monto,2,",",".");
				$li_anticipos=number_format($li_anticipos,2,",",".");
				$li_valuaciones=number_format($li_valuaciones,2,",",".");
				$li_porcobrar=number_format($li_porcobrar,2,",",".");
				$ls_ubicacion=$ls_despai." - ".$ls_desest." - ".$ls_desmun." - ".$ls_despar." - ".$ls_descom;
				$la_data[$li_s]=array('ubicacion'=>$ls_ubicacion,'desobr'=>$ls_desobr,'monobr'=>$li_monto,'contratos'=>$ls_contratos,'anticipos'=>$li_anticipos,'valuaciones'=>$li_valuaciones,'porcobrar'=>$li_porcobrar);
				$io_report->rs_data->MoveNext();
			}
			if($li_s>0)
			{
				uf_print_detalle($la_data,&$io_pdf);
				unset($la_data);
			}
			else
			{
				$lb_valido=false;
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
			print(" alert('No hay nada que Reportar');"); 
			print(" close();");
			print("</script>");		
		}
		
	}

?>
