<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte de Cuentas por Pagar
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
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_solicitudes.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_fechadesde,$as_fechahasta,$as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_fechadesde // Intervalo de Busqueda
		//	    		   as_fechahasta // Intervalo de Busqueda
		//	    		   as_titulo     // T�tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Yesenia Moreno Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_denominacion="<b> en Bs.F.</b>";
		}
		else
		{
			$ls_denominacion="<b> en Bs.</b>";
		}
		$ls_periodo="<b>Del: </b>".$as_fechadesde."   "."<b>Al: </b>".$as_fechahasta;	
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,520,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo.$ls_denominacion);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,535,11,$as_titulo.$ls_denominacion); // Agregar el t�tulo
		$li_tm=$io_pdf->getTextWidth(11,$ls_periodo);
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,525,11,$ls_periodo); // Agregar el t�tulo
		$io_pdf->addText(740,570,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(746,564,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_codigo,$as_nombre,$ad_saldo_anterior,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codigo         // Codigo del Proveedor/Beneficiario
		//	    		   as_nombre         // Nombre del Proveedor/Beneficiario
		//	    		   ad_saldo_anterior // Saldo hasta la fecha de inicio del Intervalo
		//	    		   io_pdf            // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data    = array(array('codigo'=>'<b>C�digo:</b>','codproben'=>$as_codigo));
		$la_columna = array('codigo'=>'','codproben'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
							'fontSize' => 10,  // Tama�o de Letras
							'showLines'=>0,    // Mostrar L�neas
							'shaded'=>0,       // Sombra entre l�neas
							'xOrientation'=>'center', // Orientaci�n de la tabla
							'colGap'=>1,
							'width'=>530,
							'cols'=>array('codigo'=>array('justification'=>'left','width'=>60),
										  'codproben'=>array('justification'=>'left','width'=>670))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data    = array(array('nombre'=>'<b>Nombre:</b>','nomproben'=>$as_nombre,'saldo'=>'<b>Saldo Anterior:</b> '.$ad_saldo_anterior));
		$la_columna = array('nombre'=>'','nomproben'=>'','saldo'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
							'fontSize' => 10,  // Tama�o de Letras
							'showLines'=>0,    // Mostrar L�neas
							'shaded'=>0,       // Sombra entre l�neas
							'xOrientation'=>'center', // Orientaci�n de la tabla
							'colGap'=>1,
							'width'=>530,
							'cols'=>array('nombre'=>array('justification'=>'left','width'=>60),
										  'nomproben'=>array('justification'=>'left','width'=>470),
										  'saldo'=>array('justification'=>'left','width'=>200))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_subtitulo(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codigo         // Codigo del Proveedor/Beneficiario
		//	    		   as_nombre         // Nombre del Proveedor/Beneficiario
		//	    		   ad_saldo_anterior // Saldo hasta la fecha de inicio del Intervalo
		//	    		   io_pdf            // Instancia de objeto pdf
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data    = array(array('codigo'=>'<b>PAGOS CON SOLICITUDES PREVIAS AL INTERVALO INDICADO</b>'));
		$la_columna = array('codigo'=>'');
		$la_config  = array('showHeadings'=>0, // Mostrar encabezados
							'fontSize' => 12,  // Tama�o de Letras
							'showLines'=>0,    // Mostrar L�neas
							'shaded'=>2,       // Sombra entre l�neas
							'xOrientation'=>'center', // Orientaci�n de la tabla
							'colGap'=>1,
							'width'=>530,
							'cols'=>array('codigo'=>array('justification'=>'left','width'=>730))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_solicitudes_actuales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_solicitudes_actuales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna = array('numsol'=>'<b>Documento</b>','consol'=>'<b>Concepto</b>','procedencia'=>'<b>Procedencia</b>','fecha'=>'<b>F. Emisi�n</b>','debe'=>'<b>Debe</b>','haber'=>'<b>Haber</b>','saldo'=>'<b>Saldo</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
									   'consol'=>array('justification'=>'left','width'=>280),
									   'procedencia'=>array('justification'=>'center','width'=>60),
									   'fecha'=>array('justification'=>'center','width'=>60),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end uf_print_detalle_solicitudes_actuales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_ndnc_actuales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_ndnc_actuales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datatit= array(array('subtitulo'=>'<b>									Notas Debito/Credito</b>'));
		$la_columna= array('subtitulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('subtitulo'=>array('justification'=>'left','width'=>730))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$la_columna = array('numsol'=>'<b>Documento</b>','consol'=>'<b>Concepto</b>','procedencia'=>'Procedencia','fecha'=>'<b>F. Emisi�n</b>','debe'=>'<b>Debe</b>','haber'=>'<b>Haber</b>','saldo'=>'<b>Saldo</b>');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
									   'consol'=>array('justification'=>'left','width'=>280),
									   'procedencia'=>array('justification'=>'center','width'=>60),
									   'fecha'=>array('justification'=>'center','width'=>60),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle_ndnc_actuales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle_pagos_actuales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_pagos_actuales
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_datatit= array(array('subtitulo'=>'<b>									Pagos</b>'));
		$la_columna= array('subtitulo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('subtitulo'=>array('justification'=>'left','width'=>730))); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_datatit,$la_columna,'',$la_config);
		unset($la_datatit);
		unset($la_columnas);
		unset($la_config);
		$la_columna = array('numsol'=>'<b>Documento</b>','consol'=>'<b>Concepto</b>','procedencia'=>'<b>Procedencia</b>','fecha'=>'<b>F. Emisi�n</b>','debe'=>'<b>Debe</b>','haber'=>'<b>Haber</b>','saldo'=>'<b>Saldo</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('numsol'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
									   'consol'=>array('justification'=>'left','width'=>280),
									   'procedencia'=>array('justification'=>'center','width'=>60),
									   'fecha'=>array('justification'=>'center','width'=>60),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle_pagos_actuales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_separador(&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_separador
		//		   Access: private 
		//	    Arguments: 
		//    Description: funci�n que imprime los totales por proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $la_data[0]=array('name'=>'');
	    $la_data[1]=array('name'=>'_________________________________________________________________________________________________________________________________________________________________');
	    $la_data[2]=array('name'=>'');
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>730, // Ancho de la tabla
						 'maxWidth'=>730, // Ancho M�ximo de la tabla
						 'xPos'=>400); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	 }// end function uf_print_separador
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales($ai_totaldebe,$ai_totalhaber,$ai_totalsaldo,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_totaldebe  // Total de la Columna Debe
		//	   			   ai_totalhaber // Total de la Columna Haber
		//	   			   ai_totalsaldo // Total de la Columna Saldo
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los totales por proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    $la_data=array(array('name'=>'__________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xPos'=>715); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]= array('total'=>'<b>TOTALES:</b>','debe'=>$ai_totaldebe,'haber'=>$ai_totalhaber,'saldo'=>$ai_totalsaldo);
		$la_columna = array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>480),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	 }// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales_generales($ai_totgendeb,$ai_totgenhab,$ai_totgensal,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_totaldebe  // Total de la Columna Debe
		//	   			   ai_totalhaber // Total de la Columna Haber
		//	   			   ai_totalsaldo // Total de la Columna Saldo
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los totales por proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totgendeb= number_format($ai_totgendeb,2,',','.');
		$ai_totgenhab= number_format($ai_totgenhab,2,',','.');
		if(doubleval($ai_totgensal)>0)
		{
			$ai_totgensal= "(".number_format($ai_totgensal,2,',','.').")";
		}
		else
		{
			$ai_totgensal= abs($ai_totgensal);
			$ai_totgensal= number_format($ai_totgensal,2,',','.');
		}
	   
	   
	    $la_data=array(array('name'=>'__________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xPos'=>715); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data[1]= array('total'=>'<b>TOTAL GENERAL:</b>','debe'=>$ai_totgendeb,'haber'=>$ai_totgenhab,'saldo'=>$ai_totgensal);
		$la_columna = array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>480),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	 }// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	 function uf_print_totales_totales($ai_totgendeb,$ai_totgenhab,$ai_totgensal,&$io_pdf)
	 {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: ai_totaldebe  // Total de la Columna Debe
		//	   			   ai_totalhaber // Total de la Columna Haber
		//	   			   ai_totalsaldo // Total de la Columna Saldo
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los totales por proveedor/beneficiario
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creaci�n: 21/06/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$ai_totgendeb= number_format($ai_totgendeb,2,',','.');
		$ai_totgenhab= number_format($ai_totgenhab,2,',','.');
		if(doubleval($ai_totgensal)>0)
		{
			$ai_totgensal= "(".number_format($ai_totgensal,2,',','.').")";
		}
		else
		{
			$ai_totgensal= abs($ai_totgensal);
			$ai_totgensal= number_format($ai_totgensal,2,',','.');
		}
	   
	   
		$la_data[1]= array('total'=>'<b>TOTAL REPORTE:</b>','debe'=>$ai_totgendeb,'haber'=>$ai_totgenhab,'saldo'=>$ai_totgensal);
		$la_columna = array('total'=>'','debe'=>'','haber'=>'','saldo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>540, // Ancho de la tabla
						 'maxWidth'=>540, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('total'=>array('justification'=>'right','width'=>480),
									   'debe'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80),
									   'saldo'=>array('justification'=>'right','width'=>85))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	 }// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_dsctasxpagar= new class_datastore();
	$io_dsctasxpagar = new class_datastore();
	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
	$ls_titulo= "<b>Reporte de Cuentas Por Pagar</b>";
	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_proben=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_codprobendes=$io_fun_cxp->uf_obtenervalor_get("codprobendes","");
	$ls_codprobenhas=$io_fun_cxp->uf_obtenervalor_get("codprobenhas","");
	$ld_fecemides=$io_fun_cxp->uf_obtenervalor_get("fecemides","");
	$ld_fecemihas=$io_fun_cxp->uf_obtenervalor_get("fecemihas","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$ls_estretiva=$_SESSION["la_empresa"]["estretiva"];
	$lb_valido= $io_report->uf_select_solicitudes($ls_proben,$ls_codprobendes,$ls_codprobenhas,$ld_fecemides,$ld_fecemihas);
	error_reporting(E_ALL);
	$io_pdf=new Cezpdf('LETTER','landscape');                       // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,3,3,3);                            // Configuraci�n de los margenes en cent�metros
	uf_print_encabezado_pagina($ld_fecemides,$ld_fecemihas,$ls_titulo,$io_pdf); // Imprimimos el encabezado de la p�gina
	$io_pdf->ezStartPageNumbers(750,50,8,'','',1); // Insertar el n�mero de p�gina
	$li_tottotdeb=0;
	$li_tottothab=0;
	$li_tottotsal=0;
	$li_tolalsaldodebe=0;
	$li_tolalsaldohaber=0;
	if (!$lb_valido) // Existe alg�n error � no hay registros.
	{
//		print("<script language=JavaScript>");
//		print(" alert('No hay nada que Reportar ');"); 
//		//print(" close();");
//		print("</ script>");
	}
	else // Imprimimos el reporte
	{
		$li_totrow= $io_report->rs_data->RecordCount();
		$li_totgendeb=0;
		$li_totgenhab=0;
		$li_totgensal=0;
		while(!$io_report->rs_data->EOF)
		{			
			$li_salsol=0;
			$li_totaldebe=0;
			$li_totalhaber=0;
			$li_totalsaldo=0;
			$ls_tipproben= $io_report->rs_data->fields['tipproben'];
			$ls_cedbene= $io_report->rs_data->fields['ced_bene'];
			$ls_codpro= $io_report->rs_data->fields['cod_pro'];
			$ls_nombre= $io_report->rs_data->fields['nombre'];
			if($ls_tipproben=="B")
			{
				$ls_codigo=$ls_cedbene;
			}
			else
			{
				$ls_codigo=$ls_codpro;
			}
			if($lb_valido)
			{
				$li_monsolpre=0;
				//////////////////////////////////        SALDO PREVIO        //////////////////////////////////
				$lb_valido= $io_report->uf_select_solicitudes_previas($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas);
				if($lb_valido)
				{
					$li_solcont=0;
					$li_solanul=0;
					while(!$io_report->rs_solprevias->EOF)
					{
						$li_numsolprevias=$io_report->rs_solprevias->fields['numsol'];
						$ls_estatus= $io_report->rs_solprevias->fields['estatus'];
						$li_monsol= $io_report->rs_solprevias->fields['monsol'];
						$ls_numsolp= $io_report->rs_solprevias->fields['numsol'];
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsolp);
							$li_monsol=$li_monsol+$li_monretiva;
						}
						switch ($ls_estatus)
						{
							case "C":
								$li_solcont=($li_solcont+$li_monsol);
							break;
							case "A":
								$li_solanul=($li_solanul+$li_monsol);
							break;
						}
						$io_report->rs_solprevias->MoveNext();
					}
					$li_monsolpre=($li_solcont-$li_solanul);
				}
				$io_report->io_sql->free_result($io_report->rs_solprevias);
				$li_monpagpre=0;
				$lb_valido= $io_report->uf_select_pagosprevios($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas,
															   $li_monpagpre,$li_monretpre);
				if($ls_estretiva=="B")
				{ 
					$li_monpagpre=$li_monpagpre+$li_monretpre;
				}
				//////////////////////////////////    NOTAS DEBITO/CREDITO  PREVIO  //////////////////////////////////
				$li_monndncdebe=0;
				$li_monndnchaber=0;
				$li_monndncant=0;
				if($ls_tipproben=="P")
				{
					$ls_codigo=$ls_codpro;
				}
				else
				{
					$ls_codigo=$ls_cedbene;
				}
				$lb_valido=$io_report->uf_select_informacionndnc($ls_tipproben,$ls_codigo,"",$ld_fecemides,"");
				if($lb_valido)
				{
					$li_j=0;
					while(!$io_report->rs_ndnc->EOF)
					{
						$ls_codope= $io_report->rs_ndnc->fields['codope'];
						$li_monto=  $io_report->rs_ndnc->fields['monto']; //Monto de la Solicitudes de Pago actuales.
						if($ls_codope=="ND")
						{
							$li_monndnchaber= $li_monndnchaber+$li_monto;
						}
						else
						{
							$li_monndncdebe= $li_monndncdebe-$li_monto;
						}
						$io_report->rs_ndnc->MoveNext();
					}
					$li_monndncant=$li_monndnchaber-$li_monndncdebe;
				}
				$io_report->io_sql->free_result($io_report->rs_ndnc);
				//////////////////////////////////    NOTAS DEBITO/CREDITO  PREVIO  //////////////////////////////////

				$li_monsalant=($li_monsolpre-$li_monpagpre-$li_monndncant);
				$li_tolalsaldohaber=$li_tolalsaldohaber+$li_monsalant;
				if($li_monsalant>0)
				{
					$ls_saldoanterior= number_format($li_monsalant,2,',','.');
					$ls_saldoanterior="(".$ls_saldoanterior.")";
				}
				else
				{
					$ls_saldoanterior= abs($li_monsalant);
					$ls_saldoanterior= number_format($ls_saldoanterior,2,',','.');
				}
				uf_print_cabecera($ls_codigo,$ls_nombre,$ls_saldoanterior,$io_pdf);
				//////////////////////////////////        SALDO PREVIO        //////////////////////////////////
				
				//////////////////////////////////    SOLICITUDES ACTUALES    //////////////////////////////////
				$lb_valido= $io_report->uf_select_solicitudesactualescxp($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,$ld_fecemihas);
				if($lb_valido)
				{
					$li_salsol=$li_monsalant;
					$li_j=0;
					while(!$io_report->rs_solicitudes->EOF)
					{
						$ls_numsol= $io_report->rs_solicitudes->fields['numsol'];
						$ls_estprodoc= $io_report->rs_solicitudes->fields['estprodoc'];
						$ls_consol= $io_report->rs_solicitudes->fields['consol'];
						$li_monsol= $io_report->rs_solicitudes->fields['monsol']; //Monto de la Solicitudes de Pago actuales.
						$ld_fecsol= $io_report->rs_solicitudes->fields['fecha'];
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
							$li_monsol=$li_monsol+$li_monretiva;
						}
						$li_salsol= $li_salsol+$li_monsol;
						$li_totalhaber=$li_totalhaber+$li_monsol;
						$li_tolalsaldohaber=$li_tolalsaldohaber+$li_monsol;
						$ld_fecsol=$io_funciones->uf_convertirfecmostrar($ld_fecsol);
						$ls_monto= number_format($li_monsol,2,',','.');
						$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
						$la_datasol[$li_j]= array('numsol'=>$ls_numsol,'consol'=>$ls_consol,'procedencia'=>"Solicitud",'fecha'=>$ld_fecsol,'debe'=>"0,00",'haber'=>$ls_monto,'saldo'=>$ls_salsol);
						uf_print_detalle_solicitudes_actuales($la_datasol,&$io_pdf);
						unset($la_datasol);
						
						//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
						$lb_valido=$io_report->uf_select_informacionndnc($ls_tipproben,$ls_codigo,$ld_fecemides,$ld_fecemihas,$ls_numsol);
						if($lb_valido)
						{
							$li_j=0;
							while(!$io_report->rs_ndnc->EOF)
							{
								$ls_numdc= $io_report->rs_ndnc->fields['numdc'];
								$ls_codope= $io_report->rs_ndnc->fields['codope'];
								$ls_desope= $io_report->rs_ndnc->fields['desope'];
								$li_monto=  $io_report->rs_ndnc->fields['monto']; //Monto de la Solicitudes de Pago actuales.
								$ld_fecope= $io_report->rs_ndnc->fields['fecope'];
								if($ls_codope=="ND")
								{
									$li_salsol= $li_salsol+$li_monto;
									$li_debe=0;
									$li_haber=$li_monto;
									$ls_procedencia="Debito";
									$li_totalhaber=$li_totalhaber+$li_monto;
									$li_tolalsaldohaber=$li_tolalsaldohaber+$li_monto;
								}
								else
								{
									$li_salsol= $li_salsol-$li_monto;
									$li_debe=$li_monto;
									$li_haber=0;
									$ls_procedencia="Credito";
									$li_totaldebe=$li_totaldebe+$li_monto;
									$li_tolalsaldodebe=$li_tolalsaldodebe+$li_monto;
								}
								$ld_fecope=$io_funciones->uf_convertirfecmostrar($ld_fecope);
								$li_debe= number_format($li_debe,2,',','.');
								$li_haber= number_format($li_haber,2,',','.');
								$li_salsol=round($li_salsol,2);
								if(doubleval($li_salsol)>0)
								{
									$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
								}
								else
								{
									$ls_salsol= abs($li_salsol);
									$ls_salsol= number_format($ls_salsol,2,',','.');
								}
								$la_datandnc[$li_j]= array('numsol'=>$ls_numdc,'consol'=>$ls_desope,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecope,'debe'=>$li_debe,'haber'=>$li_haber,'saldo'=>$ls_salsol);
								$li_j++;
								$io_report->rs_ndnc->MoveNext();
							}
							if($li_j>0)
							{
								uf_print_detalle_ndnc_actuales($la_datandnc,&$io_pdf);
								unset($la_datandnc);
							}
						}
						$io_report->io_sql->free_result($io_report->rs_ndnc);
						//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////

						//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
						$lb_valido=$io_report->uf_select_informacionpagoscxp($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,$ld_fecemihas,$ls_numsol);
						if($lb_valido)
						{
							$li_j=0;
							while(!$io_report->rs_pagactuales->EOF)
							{
								$ls_salsol="";
								$ls_numsol= $io_report->rs_pagactuales->fields['numsol'];
								$ls_numdoc= $io_report->rs_pagactuales->fields['numdoc'];
								$ls_codope= $io_report->rs_pagactuales->fields['codope'];
								$ls_conmov= $io_report->rs_pagactuales->fields['conmov'];
								$li_monto= $io_report->rs_pagactuales->fields['monto']; //Monto de la Solicitudes de Pago actuales.
								$ld_fecmov= $io_report->rs_pagactuales->fields['fecmov'];
								$ls_estmov= $io_report->rs_pagactuales->fields['estmov'];
								if($ls_estretiva=="B")
								{
									$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
									$li_monto=$li_monto+$li_monretiva;
								}
								if ($ls_estmov=='O' || $ls_estmov=='C')
								{
									$li_salsol= $li_salsol-$li_monto;
									$li_totaldebe=$li_totaldebe+$li_monto;
									$li_tolalsaldodebe=$li_tolalsaldodebe+$li_monto;
									$ld_debe=number_format($li_monto,2,',','.');
									$ld_haber="0,00";
									$ls_anulado ="";
								}
								else 
								{
									$li_salsol= $li_salsol+$li_monto;
									$li_totalhaber=$li_totalhaber+$li_monto;
									$li_tolalsaldohaber=$li_tolalsaldohaber+$li_monto;
									$ld_debe="0,00";
									$ld_haber=number_format($li_monto,2,',','.');
									$ls_anulado =" Anulado";
								}
								$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ld_fecmov);
								$li_salsol=round($li_salsol,2);
								if(doubleval($li_salsol)>0)
								{
									$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
								}
								else
								{
									$ls_salsol= abs($li_salsol);
									$ls_salsol= number_format($ls_salsol,2,',','.');
								}
								$ls_procedencia="";
								switch($ls_codope)
								{
									case "CH":
										$ls_procedencia="Cheque".$ls_anulado;
									break;
									case "ND":
										$ls_procedencia="Nota de Debito";
									break;
									case "NC":
										$ls_procedencia="Nota de Credito";
									break;
								}
								$ls_monto= number_format($li_monto,2,',','.');
								$la_datapag[$li_j]= array('numsol'=>$ls_numdoc,'consol'=>$ls_conmov,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecmov,'debe'=>$ld_debe,'haber'=>$ld_haber,'saldo'=>$ls_salsol);
								$li_j++;
								$io_report->rs_pagactuales->MoveNext();
							}
							if($li_j>0)
							{
								uf_print_detalle_pagos_actuales($la_datapag,&$io_pdf);
								unset($la_datapag);
							}
						}
						$io_report->io_sql->free_result($io_report->rs_pagactuales);
						//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////

						uf_print_separador(&$io_pdf);
						$io_report->rs_solicitudes->MoveNext();
					}
					$io_report->io_sql->free_result($io_report->rs_solicitudes);
					//////////////////////////////////    SOLICITUDES ACTUALES    //////////////////////////////////
				}
			}
			else
			{
				break;
			}
			$li_totalsaldo=$li_salsol;
			$li_totgendeb=$li_totgendeb+$li_totaldebe;
			$li_totgenhab=$li_totgenhab+$li_totalhaber;
			$li_totgensal=$li_totgensal+$li_totalsaldo;
			if(doubleval($li_totalsaldo)>0)
			{
				$li_totalsaldo= "(".number_format($li_totalsaldo,2,',','.').")";
			}
			else
			{
				$li_totalsaldo= abs($li_totalsaldo);
				$li_totalsaldo= number_format($li_totalsaldo,2,',','.');
			}
			$li_totalhaber= number_format($li_totalhaber,2,',','.');
			$li_totaldebe= number_format($li_totaldebe,2,',','.');
			uf_print_totales($li_totaldebe,$li_totalhaber,$li_totalsaldo,&$io_pdf);
			if(!$lb_valido)
			{break;}
			//////////////////////////////////       PAGOS ACTUALES II      //////////////////////////////////
			$lb_valido=$io_report->uf_select_informacionpagosactuales($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,$ld_fecemihas,"");
			if($lb_valido)
			{
				$li_j=0;
				$li_salsol=$li_monsalant;
				$li_totaldebe=0;
				$li_totalhaber=0;
				while(!$io_report->rs_pagactuales->EOF)
				{
					$ls_numsol= $io_report->rs_pagactuales->fields['numsol'];
					$ls_numdoc= $io_report->rs_pagactuales->fields['numdoc'];
					$ls_codope= $io_report->rs_pagactuales->fields['codope'];
					$ls_conmov= $io_report->rs_pagactuales->fields['conmov'];
					$li_monto= $io_report->rs_pagactuales->fields['monto']; //Monto de la Solicitudes de Pago actuales.
					$ld_fecmov= $io_report->rs_pagactuales->fields['fecmov'];
					$ls_estmov= $io_report->rs_pagactuales->fields['estmov'];
					if($ls_estretiva=="B")
					{
						$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
						$li_monto=$li_monto+$li_monretiva;
					}
					if ($ls_estmov=='O' || $ls_estmov=='C')
					{
						$li_salsol= $li_salsol-$li_monto;
						$li_totaldebe=$li_totaldebe+$li_monto;
						$li_tolalsaldodebe=$li_tolalsaldodebe+$li_monto;
						$ld_debe=number_format($li_monto,2,',','.');
						$ld_haber="0,00";
						$ls_anulado ="";
					}
					else 
					{
						$li_salsol= $li_salsol+$li_monto;
						$li_totalhaber=$li_totalhaber+$li_monto;
						$li_tolalsaldohaber=$li_tolalsaldohaber+$li_monto;
						$ld_debe="0,00";
						$ld_haber=number_format($li_monto,2,',','.');
						$ls_anulado =" Anulado";
					}
					$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ld_fecmov);
					$li_salsol=round($li_salsol,2);
					if(doubleval($li_salsol)>0)
					{
						$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
					}
					else
					{
						$ls_salsol= abs($li_salsol);
						$ls_salsol= number_format($ls_salsol,2,',','.');
					}
					$ls_procedencia="";
					if($ls_codope=="CH")
					{
						$ls_procedencia="Cheque".$ls_anulado;
					}
					$ls_monto= number_format($li_monto,2,',','.');
					$la_datapag[$li_j]= array('numsol'=>$ls_numdoc,'consol'=>$ls_conmov,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecmov,'debe'=>$ld_debe,'haber'=>$ld_haber,'saldo'=>$ls_salsol);
					$li_j++;
					$io_report->rs_pagactuales->MoveNext();
				}
				if($li_j>0)
				{
					uf_print_detalle_pagos_actuales($la_datapag,&$io_pdf);
					unset($la_datapag);
				}
			}
			$io_report->io_sql->free_result($io_report->rs_pagactuales);
			$li_totalsaldo=$li_salsol;
			$li_totgendeb=$li_totgendeb+$li_totaldebe;
			$li_totgenhab=$li_totgenhab+$li_totalhaber;
			$li_totgensal=$li_totgensal+$li_totalsaldo;
			if(doubleval($li_totalsaldo)>0)
			{
				$li_totalsaldo= "(".number_format($li_totalsaldo,2,',','.').")";
			}
			else
			{
				$li_totalsaldo= abs($li_totalsaldo);
				$li_totalsaldo= number_format($li_totalsaldo,2,',','.');
			}
			$li_totalhaber= number_format($li_totalhaber,2,',','.');
			$li_totaldebe= number_format($li_totaldebe,2,',','.');
			if($li_j>0)
			{
				uf_print_totales($li_totaldebe,$li_totalhaber,$li_totalsaldo,&$io_pdf);
			}
			//////////////////////////////////       PAGOS ACTUALES  II     //////////////////////////////////
			$io_report->rs_data->MoveNext();
		}// fin for uf_select_solicitudes
		$li_totgensal=($li_tolalsaldohaber-$li_tolalsaldodebe);
		$io_report->io_sql->free_result($io_report->rs_data);
		$li_tottotdeb=$li_tottotdeb+$li_totgendeb;
		$li_tottothab=$li_tottothab+$li_totgenhab;
		$li_tottotsal=$li_tottotsal+$li_totgensal;
		uf_print_totales_generales($li_totgendeb,$li_totgenhab,$li_totgensal,&$io_pdf);
		
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////       PROVEEDORES SOLO CON SALDO ANTERIOR        ///////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$lb_existe= $io_report->uf_select_saldosprevios($ls_proben,$ls_codprobendes,$ls_codprobenhas,$ld_fecemides,$ld_fecemihas);
	uf_print_subtitulo($io_pdf);
	$li_totgendeb=0;
	$li_totgenhab=0;
	$li_totgensal=0;
	while(!$io_report->rs_provanteriores->EOF)
	{
		$ls_codpro= $io_report->rs_provanteriores->fields['cod_pro'];
		$ls_cedbene= $io_report->rs_provanteriores->fields['ced_bene'];
		$ls_tipproben= $io_report->rs_provanteriores->fields['tipproben'];
		$ls_nombre= $io_report->rs_provanteriores->fields['nombre'];
		$lb_valido= $io_report->uf_select_solicitudesanteriores($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas);
		if($lb_valido)
		{
			$li_solcont=0;
			$li_solanul=0;
			$li_monsolpre=0;
			while(!$io_report->rs_solanteriores->EOF)
			{
				$li_monsol= $io_report->rs_solanteriores->fields['monsol'];
				$ls_numsolp= $io_report->rs_solanteriores->fields['numsol'];
				if($ls_tipproben=="B")
				{
					$ls_codigo=$ls_cedbene;
				}
				else
				{
					$ls_codigo=$ls_codpro;
				}
				if($ls_estretiva=="B")
				{
					$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsolp);
					$li_monsol=$li_monsol+$li_monretiva;
				}
				$li_monsolpre=$li_monsolpre+$li_monsol;
				$io_report->rs_solanteriores->MoveNext();
			}
//			print $ls_codigo."  PREVIO->  ".$li_monsolpre."<br>";
			$io_report->io_sql->free_result($io_report->rs_solanteriores);
			$li_monpagpre=0;
			$lb_valido= $io_report->uf_select_pagosanteriores($ls_tipproben,$ls_codpro,$ls_cedbene,$ld_fecemides,$ld_fecemihas,
														   $li_monpagpre);
			
			//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////
			$li_monndncdebe=0;
			$li_monndnchaber=0;
			$li_monndncant=0;
			if($ls_tipproben=="P")
			{
				$ls_codigo=$ls_codpro;
			}
			else
			{
				$ls_codigo=$ls_cedbene;
			}
			$lb_valido=$io_report->uf_select_informacionndnc($ls_tipproben,$ls_codigo,"",$ld_fecemides,"");
			if($lb_valido)
			{
				$li_j=0;
				while(!$io_report->rs_ndnc->EOF)
				{
					$ls_codope= $io_report->rs_ndnc->fields['codope'];
					$li_monto=  $io_report->rs_ndnc->fields['monto']; //Monto de la Solicitudes de Pago actuales.
					if($ls_codope=="ND")
					{
						$li_monndnchaber= $li_monndnchaber+$li_monto;
					}
					else
					{
						$li_monndncdebe= $li_monndncdebe-$li_monto;
					}
					$io_report->rs_ndnc->MoveNext();
				}
				$li_monndncant=$li_monndnchaber-$li_monndncdebe;
			}
			$io_report->io_sql->free_result($io_report->rs_ndnc);
			//////////////////////////////////    NOTAS DEBITO/CREDITO    //////////////////////////////////



//			print $ls_codigo."  PAGO->  ".$li_monpagpre."<br>";
			$li_monsalant=($li_monsolpre-$li_monpagpre-$li_monndncant);
//			print $ls_codigo."  SALDO->  ".$li_monsalant."<br>";
			if(($li_monsalant>=0))
			{
				if($li_monsalant>0)
				{
					$ls_saldoanterior= number_format($li_monsalant,2,',','.');
					$ls_saldoanterior="(".$ls_saldoanterior.")";
				}
				else
				{
					$ls_saldoanterior= abs($li_monsalant);
					$ls_saldoanterior= number_format($ls_saldoanterior,2,',','.');
				}
				uf_print_cabecera($ls_codigo,$ls_nombre,$ls_saldoanterior,$io_pdf);
				//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
				$lb_valido=$io_report->uf_select_informacionpagosactuales($ls_tipproben,$ls_cedbene,$ls_codpro,$ld_fecemides,$ld_fecemihas,"");
				if($lb_valido)
				{
					$li_j=0;
					$li_salsol=$li_monsalant;
					$li_totaldebe=0;
					$li_totalhaber=0;
					while(!$io_report->rs_pagactuales->EOF)
					{
						$ls_numsol= $io_report->rs_pagactuales->fields['numsol'];
						$ls_numdoc= $io_report->rs_pagactuales->fields['numdoc'];
						$ls_codope= $io_report->rs_pagactuales->fields['codope'];
						$ls_conmov= $io_report->rs_pagactuales->fields['conmov'];
						$li_monto= $io_report->rs_pagactuales->fields['monto']; //Monto de la Solicitudes de Pago actuales.
						$ld_fecmov= $io_report->rs_pagactuales->fields['fecmov'];
						$ls_estmov= $io_report->rs_pagactuales->fields['estmov'];
						if($ls_estretiva=="B")
						{
							$li_monretiva=$io_report->uf_select_det_deducciones_solpag($ls_numsol);
							$li_monto=$li_monto+$li_monretiva;
						}
						if ($ls_estmov=='O' || $ls_estmov=='C')
						{
							$li_salsol= $li_salsol-$li_monto;
							$li_totaldebe=$li_totaldebe+$li_monto;
							$ld_debe=number_format($li_monto,2,',','.');
							$ld_haber="0,00";
							$ls_anulado ="";
						}
						else 
						{
							$li_salsol= $li_salsol+$li_monto;
							$li_totalhaber=$li_totalhaber+$li_monto;
							$ld_debe="0,00";
							$ld_haber=number_format($li_monto,2,',','.');
							$ls_anulado =" Anulado";
						}
						$ld_fecmov=$io_funciones->uf_convertirfecmostrar($ld_fecmov);
						$li_salsol=round($li_salsol,2);
						if(doubleval($li_salsol)>0)
						{
							$ls_salsol= "(".number_format($li_salsol,2,',','.').")";
						}
						else
						{
							$ls_salsol= abs($li_salsol);
							$ls_salsol= number_format($ls_salsol,2,',','.');
						}
						$ls_procedencia="";
						if($ls_codope=="CH")
						{
							$ls_procedencia="Cheque".$ls_anulado;
						}
						$ls_monto= number_format($li_monto,2,',','.');
						$la_datapag[$li_j]= array('numsol'=>$ls_numdoc,'consol'=>$ls_conmov,'procedencia'=>$ls_procedencia,'fecha'=>$ld_fecmov,'debe'=>$ld_debe,'haber'=>$ld_haber,'saldo'=>$ls_salsol);
						$li_j++;
						$io_report->rs_pagactuales->MoveNext();
					}
					if($li_j>0)
					{
						uf_print_detalle_pagos_actuales($la_datapag,&$io_pdf);
						unset($la_datapag);
					}
				}
				$io_report->io_sql->free_result($io_report->rs_pagactuales);
				$li_totalsaldo=$li_salsol;
				$li_totgendeb=$li_totgendeb+$li_totaldebe;
				$li_totgenhab=$li_totgenhab+$li_totalhaber;
				$li_totgensal=$li_totgensal+$li_totalsaldo;
				if(doubleval($li_totalsaldo)>0)
				{
					$li_totalsaldo= "(".number_format($li_totalsaldo,2,',','.').")";
				}
				else
				{
					$li_totalsaldo= abs($li_totalsaldo);
					$li_totalsaldo= number_format($li_totalsaldo,2,',','.');
				}
				$li_totalhaber= number_format($li_totalhaber,2,',','.');
				$li_totaldebe= number_format($li_totaldebe,2,',','.');
				uf_print_totales($li_totaldebe,$li_totalhaber,$li_totalsaldo,&$io_pdf);
				//////////////////////////////////       PAGOS ACTUALES       //////////////////////////////////
			
			}
		}
		$io_report->rs_provanteriores->MoveNext();
	}
		$li_tottotdeb=$li_tottotdeb+$li_totgendeb;
		$li_tottothab=$li_tottothab+$li_totgenhab;
		$li_tottotsal=$li_tottotsal+$li_totgensal;
		uf_print_totales_generales($li_totgendeb,$li_totgenhab,$li_totgensal,&$io_pdf);
		uf_print_separador(&$io_pdf);
		uf_print_totales_totales($li_tottotdeb,$li_tottothab,$li_tottotsal,&$io_pdf);
		if($lb_valido)
		{
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
		//	unset($io_pdf);
		//	unset($io_report);
		}
		else
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generarse el Reporte');"); 
			print(" close();");
			print("</script>");
		}
	
?>