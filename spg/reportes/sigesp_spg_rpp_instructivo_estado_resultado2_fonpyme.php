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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_nombre_empresa,$as_bs,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 05/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(10,30,1000,30);
		//print $as_titulo."---".$as_nombre_empresa."---".$as_bs;
		//$io_pdf->rectangle(10,480,988,110);
		//$io_pdf->addText(15,580,11,"<b>OFICINA NACIONAL DE PRESUPUESTO (ONAPRE)</b>"); // Agregar la Fecha
		//$io_pdf->addText(15,565,11,"<b>OFICINA DE PLANIFICACIÓN DEL SECTOR UNIVERSITARIO (OPSU)</b>"); // Agregar la Fecha
		
		$li_tm=$io_pdf->getTextWidth(12,$as_nombre_empresa);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,530,12,$as_nombre_empresa); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(12,$as_titulo);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,515,12,$as_titulo); // Agregar el título
		
		$li_tm=$io_pdf->getTextWidth(12,$as_bs);
		$tm=505-($li_tm/2);
		$io_pdf->addText($tm,500,10,$as_bs); // Agregar el título
		
		//$io_pdf->addText(900,550,10,date("d/m/Y")); // Agregar la Fecha
		//$io_pdf->addText(900,540,10,date("h:i a")); // Agregar la hora
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo_reporte($ai_mesdes,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		    Acess: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_periodo_comp // Descripción del periodo del comprobante
		//	    		   as_fecha_comp // Descripción del período de la fecha del comprobante 
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 26/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(590);
		$ls_codemp    = $_SESSION["la_empresa"]["codemp"];
		$ls_nombre    = $_SESSION["la_empresa"]["nombre"];
		$ls_nomorgads = $_SESSION["la_empresa"]["nomorgads"];
		$ls_codasiona = $_SESSION['la_empresa']['codasiona'];
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones = new class_funciones();
		$ls_periodo = "";
		if($ai_mesdes==1)
		{
		 $ls_periodo = "ENERO - MARZO";
		}
		if($ai_mesdes==4)
		{
		  $ls_periodo = "ABRIL - JUNIO";
		}
		if($ai_mesdes==7)
		{
		  $ls_periodo = "JULIO - SEPTIEMBRE";
		}
		if($ai_mesdes==10)
		{
		  $ls_periodo = "OCTUBRE - DICIEMBRE";
		}
		
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);	

		$la_data=array(array('name'=>'<b>CODIGO PRESUPUESTARIO DEL ENTE:     </b>'.'<b>'.$ls_codasiona.'</b>'),
		               array('name'=>'<b>DENOMINACION:    </b>'.'<b>'.$ls_nombre.'</b>'),
					   array('name'=>'<b>ORGANO DE ADSCRIPCION:    </b>'.'<b>'.$ls_nomorgads.'</b>'),
		               array('name'=>'<b>PERIODO PRESUPUESTARIO:    </b>'.'<b>'.$ls_periodo." ".$li_ano.'</b>'));
		$la_columna=array('name'=>'','name'=>'','name'=>'','name'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 8,       // Tamaño de Letras
						 'titleFontSize' => 8, // Tamaño de Letras de los títulos
						 'showLines'=>0,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>465,//65
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900);
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo($ai_mesdes,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_titulo
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 05/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->ezSetY(475);
		if($ai_mesdes==1)
		{
		  $ls_etiqueta="I";
		}
		if($ai_mesdes==4)
		{
		  $ls_etiqueta="II";
		}
		if($ai_mesdes==7)
		{
		  $ls_etiqueta="III";
		}
		if($ai_mesdes==10)
		{
		  $ls_etiqueta="IV";
		}
		$la_data=array(array('name1'=>'','name2'=>'<b>TRIMESTRE N°: '.strtoupper($ls_etiqueta).'</b>',
		                     'name3'=>'<b>VARIACIÓN EJECUTADO - PROGRAMADO TRIMESTRE N°: '.strtoupper($ls_etiqueta).'</b>',
							 'name4'=>'<b>TOTAL ACUMULADO AL TRIMESTRE N°: '.strtoupper($ls_etiqueta).'</b>'));
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'');
		$la_config =array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 9,       // Tamaño de Letras
						 'titleFontSize' => 9, // Tamaño de Letras de los títulos
						 'showLines'=>1,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'xPos'=>509,
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>450),// Justificación y ancho de la columna
						               'name2'=>array('justification'=>'center','width'=>200),// Justificación y ancho de la columna
									   'name3'=>array('justification'=>'center','width'=>140),// Justificación y ancho de la columna
									   'name4'=>array('justification'=>'center','width'=>200))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_titulo
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 05/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$la_data=array(array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominación</b>','presupuesto'=>'<b>Presupuesto Aprobado  </b>',
		                     'presupuesto_modificado'=>'<b>Presupuesto Modificado</b>','programado'=>'<b>Programado</b>',
		                     'ejecutado'=>'<b>Ejecutado</b>','absoluta'=>'<b>Absoluta</b>','porcentaje'=>'<b>Porcentaje (%)</b>',
							 'programado_acumulado'=>'<b>Programado</b>','ejecutado_acumulado'=>'<b>Ejecutado</b>'));
		$la_columna=array('cuenta'=>'','denominacion'=>'','presupuesto'=>'','presupuesto_modificado'=>'',
		                  'programado'=>'','ejecutado'=>'','absoluta'=>'','porcentaje'=>'','programado_acumulado'=>'',
						  'ejecutado_acumulado'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 9,       // Tamaño de Letras
						 'titleFontSize' => 9, // Tamaño de Letras de los títulos
						 'showLines'=>2,        // Mostrar Líneas
						 'shaded'=>0,           // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'center','width'=>160), // Justificación y ancho de la columna
									   'presupuesto'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
									   'presupuesto_modificado'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
									   'programado'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
						 			   'ejecutado'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
									   'absoluta'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'porcentaje'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
									   'programado_acumulado'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la columna
									   'ejecutado_acumulado'=>array('justification'=>'center','width'=>100))); // Justificación y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 05/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>90), // Justificación y ancho de la columna
						 			   'denominacion'=>array('justification'=>'left','width'=>160), // Justificación y ancho de la columna
									   'presupuesto'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'presupuesto_modificado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'programado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'ejecutado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'absoluta'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'porcentaje'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'programado_acumulado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'ejecutado_acumulado'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		
		$la_columnas=array('cuenta'=>'',
						   'denominacion'=>'',
						   'presupuesto'=>'',
						   'presupuesto_modificado'=>'',
						   'programado'=>'',
						   'ejecutado'=>'',
						   'absoluta'=>'',
						   'porcentaje'=>'',
						   'programado_acumulado'=>'',
						   'ejecutado_acumulado'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_detalle*/
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_resultado($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_resultado
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragán
		// Fecha Creación: 05/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 9,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>0, // separacion entre tablas
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('total'=>array('justification'=>'center','width'=>250), // Justificación y ancho de la columna
									   'presupuesto'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'presupuesto_modificado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'programado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
						 			   'ejecutado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'absoluta'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'porcentaje'=>array('justification'=>'right','width'=>70), // Justificación y ancho de la columna
									   'programado_acumulado'=>array('justification'=>'right','width'=>100), // Justificación y ancho de la columna
									   'ejecutado_acumulado'=>array('justification'=>'right','width'=>100))); // Justificación y ancho de la columna
		
		$la_columnas=array('total'=>'',
						   'presupuesto'=>'',
						   'presupuesto_modificado'=>'',
						   'programado'=>'',
						   'ejecutado'=>'',
						   'absoluta'=>'',
						   'porcentaje'=>'',
						   'programado_acumulado'=>'',
						   'ejecutado_acumulado'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_resultado
	//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_formato_salida($as_cuenta,$ai_nivel,$as_formato,$as_separador)
	{
	    ///////////////////////////////////////////////////////////////////////////////////////////////////////
        //       Function: uf_formato_salida
        //         Access: public
        //        Returns: vacio     
        //    Description: Este método da formato según lo que estable los instrucivos de la ONAPRE para la cuentas
        //////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_arreglo = explode('-',$as_formato);
        $ls_cuenta = "";
		$j=0;
		$ls_nvoformato = "";
		do
		{
		 if($j<$ai_nivel-1)
		 {
		  $ls_nvoformato .= $la_arreglo[$j].'-';
		 }
		 else
		 {
		  $ls_nvoformato .= $la_arreglo[$j];
		 }
		 $j++;
		}while($j<$ai_nivel);
		
		$la_arreglo_nvo = explode('-',trim($ls_nvoformato));
		$li_total = count($la_arreglo_nvo);
		$ini = 0;
		foreach($la_arreglo_nvo as $key => $valor)
		{
		  if($key <> 0)
		  {
		   $ini += strlen(trim($la_arreglo_nvo[$key-1]));
		  }
		  $len = strlen(trim($valor));
		  if($key<$li_total-1)
		  {
		   $ls_cuenta .= substr(trim($as_cuenta),$ini,$len).$as_separador;
		  }
		  else
		  {
		   $ls_cuenta .= substr(trim($as_cuenta),$ini,$len);
		  }
		}
		
		return $ls_cuenta;
	
	}
	
	//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		require_once("../../shared/ezpdf/class.ezpdf.php");
		require_once("../../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();	
		require_once("sigesp_spg_funciones_reportes.php");
		$io_function_report=new sigesp_spg_funciones_reportes();	
		require_once("../../shared/class_folder/class_fecha.php");
		$io_fecha = new class_fecha();
		require_once("sigesp_spg_class_reportes_instructivos.php");
		$io_report = new sigesp_spg_class_reportes_instructivos();
	//-----------------------------------------------------------------------------------------------------------------------------
		
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
		$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
		$li_ano=substr($ldt_periodo,0,4);
		$li_estmodest=$_SESSION["la_empresa"]["estmodest"];
		$ls_nombre=$_SESSION["la_empresa"]["nombre"];
		
		$ls_trimestre=$_GET["trimestre"];
		$li_mesdes=substr($ls_trimestre,0,2);
		$ldt_fecdes=$li_ano."-".$li_mesdes."-01";
		$li_meshas=substr($ls_trimestre,2,2);
		$ldt_ult_dia=$io_fecha->uf_last_day($li_meshas,$li_ano);
		$fechas=$ldt_ult_dia;
		$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
		$ls_mesdes=$io_fecha->uf_load_nombre_mes($li_mesdes);
		$ls_meshas=$io_fecha->uf_load_nombre_mes($li_meshas);
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
		$ls_nombre_empresa="<b>".$ls_nombre."</b>";
		$ls_titulo=" <b>ESTADO DE RESULTADO</b>";    
		$ls_bs="<b>(En Bolivares)</b>"  ; 
	//--------------------------------------------------------------------------------------------------------------------------------
    // Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )	
     //$lb_valido=$io_report->uf_spg_reportes_estado_de_resultado2($ldt_fecdes,$ldt_fechas,"",$ls_mesdes,$ls_meshas);
	 $lb_valido=$io_report->uf_spg_reportes_estado_de_resultado_inst08($ldt_fecdes,$ldt_fechas,$ls_mesdes,$ls_meshas);
	 if($lb_valido==false) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else // Imprimimos el reporte
	 {
	    error_reporting(E_ALL);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(7.014,3,3,3); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($ls_titulo,$ls_nombre_empresa,$ls_bs,$io_pdf); // Imprimimos el encabezado de la página
 	    $io_pdf->ezStartPageNumbers(980,40,10,'','',1); // Insertar el número de página
		$li_total=$io_report->dts_reporte->getRowCount("cuenta");
		$li_nivel = count(explode('-',$_SESSION["la_empresa"]["formpre"]));
		$ls_mascara = str_replace("-","",$_SESSION["la_empresa"]["formpre"]);
		$li_len_mas = strlen($ls_mascara);
		$ls_ceros = str_pad("",$li_len_mas - 9,"0"); 
		
        $la_cuentas_saldos[]  = '303000000'.$ls_ceros;
        $la_cuentas_saldos[]  = '408070000'.$ls_ceros;
        $la_cuentas_saldos[]  = '304000000'.$ls_ceros;
        $la_cuentas_saldos[]  = '305000000'.$ls_ceros;
        $la_cuentas_saldos[]  = '407000000'.$ls_ceros;
        $la_cuentas_saldos[]  = '408000000'.$ls_ceros;
		
		$ld_asignado_rs=0;
		$ld_asignado_modificado_rs=0;
		$ld_programado_rs=0;
		$ld_ejecutado_rs=0;
		$ld_variacion_absoluta_rs=0;
		$ld_variacion_porcentual_rs=0;
		$ld_programado_acumulado_rs=0;
		$ld_ejecutado_acumulado_rs=0;
		
		$ld_asignado_rs_i=0;
		$ld_asignado_modificado_rs_i=0;
		$ld_programado_rs_i=0;
		$ld_ejecutado_rs_i=0;
		$ld_variacion_absoluta_rs_i=0;
		$ld_variacion_porcentual_rs_i=0;
		$ld_programado_acumulado_rs_i=0;
		$ld_ejecutado_acumulado_rs_i=0;
        
		
		for($z=1;$z<=$li_total;$z++)
		{
		    $thisPageNum=$io_pdf->ezPageCount;
			$ls_cuenta=trim($io_report->dts_reporte->data["cuenta"][$z]);
			$ls_denominacion=trim($io_report->dts_reporte->data["denominacion"][$z]);
			$ld_asignado=$io_report->dts_reporte->data["asignado"][$z];
			$ld_asignado_modificado=$io_report->dts_reporte->data["asignado_modificado"][$z];
			$ld_programado=$io_report->dts_reporte->data["programado"][$z];
			$ld_ejecutado=$io_report->dts_reporte->data["ejecutado"][$z];
			$ld_variacion_absoluta=$io_report->dts_reporte->data["variacion_absoluta"][$z];
			$ld_variacion_porcentual=$io_report->dts_reporte->data["variacion_porcentual"][$z];
			$ld_programado_acumulado=$io_report->dts_reporte->data["programado_acumulado"][$z];
			$ld_ejecutado_acumulado=$io_report->dts_reporte->data["ejecutado_acumulado"][$z];
			$ls_tipo=$io_report->dts_reporte->data["tipo"][$z];
			$ls_cuenta_nva = "";
			//echo $ld_asignado_modificado;
			if(!empty($ls_cuenta))
			{
			 $ls_cuenta_nva = uf_formato_salida($ls_cuenta,$li_nivel,$_SESSION["la_empresa"]["formpre"],".");
			}
			
			if(is_int(array_search($ls_cuenta,$la_cuentas_saldos)))
			{
				switch(substr($ls_cuenta,0,1))
				{
				 case '3':
						 $ld_asignado_rs += $ld_asignado;
						 $ld_asignado_modificado_rs += $ld_asignado_modificado;
						 $ld_programado_rs += $ld_programado;
						 $ld_ejecutado_rs += $ld_ejecutado;
						 $ld_programado_acumulado_rs += $ld_programado_acumulado;
						 $ld_ejecutado_acumulado_rs +=$ld_ejecutado_acumulado;
				 break;
				 
				 case '4':
				         $ld_asignado_rs -= $ld_asignado;
						 $ld_asignado_modificado_rs -= $ld_asignado_modificado;
						 $ld_programado_rs -= $ld_programado;
						 $ld_ejecutado_rs -= $ld_ejecutado;
						 $ld_programado_acumulado_rs -= $ld_programado_acumulado;
						 $ld_ejecutado_acumulado_rs -= $ld_ejecutado_acumulado;
				 break;
				}
			}
			
		    if(($ls_tipo != 'IN')&&($ls_tipo != 'EG'))
			{
		 	 $ld_asignado=number_format($ld_asignado,2,",",".");
			 $ld_asignado_modificado=number_format($ld_asignado_modificado,2,",",".");
			 $ld_programado=number_format($ld_programado,2,",",".");
			 $ld_ejecutado=number_format($ld_ejecutado,2,",",".");
			 $ld_variacion_absoluta=number_format($ld_variacion_absoluta,2,",",".");
			 $ld_variacion_porcentual=number_format($ld_variacion_porcentual,2,",",".");
			 $ld_programado_acumulado=number_format($ld_programado_acumulado,2,",",".");
			 $ld_ejecutado_acumulado=number_format($ld_ejecutado_acumulado,2,",",".");
			}
			
			
		    
		    $la_data[]=array('cuenta'=>$ls_cuenta_nva,
			                 'denominacion'=>$ls_denominacion,
			                 'presupuesto'=>$ld_asignado,
							 'presupuesto_modificado'=>$ld_asignado_modificado,
			                 'programado'=>$ld_programado,
							 'ejecutado'=>$ld_ejecutado,
							 'absoluta'=>$ld_variacion_absoluta,
							 'porcentaje'=>$ld_variacion_porcentual,
							 'programado_acumulado'=>$ld_programado_acumulado,
							 'ejecutado_acumulado'=>$ld_ejecutado_acumulado);
			if($z == $li_total - 1)
			{
			  if(($ld_programado_rs >= 0)&&($ld_ejecutado_rs > 0))
			  {
			   $ld_variacion_absoluta_rs = abs($ld_programado_rs - $ld_ejecutado_rs);
			  }		
			  if($ld_programado_rs > 0)
			  {
			   	$ld_variacion_porcentual_rs = ($ld_ejecutado_rs/$ld_programado_rs)*100;
			  }
			  else if($ld_programado_rs==0){
			  	$ld_variacion_porcentual_rs = 100;
			  }
			  
			  $ld_asignado_rs_i=$ld_asignado_rs;
			  $ld_asignado_modificado_rs_i=$ld_asignado_modificado_rs;
			  $ld_programado_rs_i=$ld_programado_rs;
			  $ld_ejecutado_rs_i=$ld_ejecutado_rs;
			  $ld_programado_acumulado_rs_i=$ld_programado_acumulado_rs;
			  $ld_ejecutado_acumulado_rs_i=$ld_ejecutado_acumulado_rs;
			  
			  $ld_asignado_rs=number_format($ld_asignado_rs,2,",",".");
			  $ld_asignado_modificado_rs=number_format($ld_asignado_modificado_rs,2,",",".");
			  $ld_programado_rs=number_format($ld_programado_rs,2,",",".");
			  $ld_ejecutado_rs=number_format($ld_ejecutado_rs,2,",",".");
			  $ld_variacion_absoluta_rs=number_format($ld_variacion_absoluta_rs,2,",",".");
			  $ld_variacion_porcentual_rs=number_format($ld_variacion_porcentual_rs,2,",",".");
			  $ld_programado_acumulado_rs=number_format($ld_programado_acumulado_rs,2,",",".");
			  $ld_ejecutado_acumulado_rs=number_format($ld_ejecutado_acumulado_rs,2,",",".");
			  
			  $la_data[]=array('cuenta'=>'',
			                   'denominacion'=>'<b>Resultado antes del Impuesto Sobre la Renta</b>',
			                   'presupuesto'=>$ld_asignado_rs,
							   'presupuesto_modificado'=>$ld_asignado_modificado_rs,
			                   'programado'=>$ld_programado_rs,
							   'ejecutado'=>$ld_ejecutado_rs,
							   'absoluta'=>$ld_variacion_absoluta_rs,
							   'porcentaje'=>$ld_variacion_porcentual_rs,
							   'programado_acumulado'=>$ld_programado_acumulado_rs,
							   'ejecutado_acumulado'=>$ld_ejecutado_acumulado_rs);
			
			}
			if(trim($ls_cuenta) == '408060700'.$ls_ceros)
			{
			  $ld_asignado_rs_i -= $ld_asignado;
			  $ld_asignado_modificado_rs_i -= $ld_asignado_modificado;
			  $ld_programado_rs_i -= $ld_programado;
			  $ld_ejecutado_rs_i -= $ld_ejecutado;
			  $ld_programado_acumulado_rs_i -= $ld_programado_acumulado;
			  $ld_ejecutado_acumulado_rs_i -= $ld_ejecutado_acumulado;
			}
		}
		if(($ld_programado_rs_i >= 0)&&($ld_ejecutado_rs_i > 0))
	    {
	     $ld_variacion_absoluta_rs_i = abs($ld_programado_rs_i - $ld_ejecutado_rs_i);
	    }		
	    if($ld_programado_rs_i > 0)
	    {
	     $ld_variacion_porcentual_rs_i = ($ld_ejecutado_rs_i/$ld_programado_rs_i)*100;
	    }
	    elseif ($ld_programado_rs_i == 0){
	    	$ld_variacion_porcentual_rs_i = 100;
	    }
		$ld_asignado_rs_i=number_format($ld_asignado_rs_i,2,",",".");
	    $ld_asignado_modificado_rs_i=number_format($ld_asignado_modificado_rs_i,2,",",".");
	    $ld_programado_rs_i=number_format($ld_programado_rs_i,2,",",".");
	    $ld_ejecutado_rs_i=number_format($ld_ejecutado_rs_i,2,",",".");
	    $ld_variacion_absoluta_rs_i=number_format($ld_variacion_absoluta_rs_i,2,",",".");
	    $ld_variacion_porcentual_rs_i=number_format($ld_variacion_porcentual_rs_i,2,",",".");
	    $ld_programado_acumulado_rs_i=number_format($ld_programado_acumulado_rs_i,2,",",".");
	    $ld_ejecutado_acumulado_rs_i=number_format($ld_ejecutado_acumulado_rs_i,2,",",".");
		
		$la_data[]=array('cuenta'=>'',
					     'denominacion'=>'<b>Resultado del Ejercicio</b>',
					     'presupuesto'=>$ld_asignado_rs_i,
					     'presupuesto_modificado'=>$ld_asignado_modificado_rs_i,
					     'programado'=>$ld_programado_rs_i,
					     'ejecutado'=>$ld_ejecutado_rs_i,
					     'absoluta'=>$ld_variacion_absoluta_rs_i,
					     'porcentaje'=>$ld_variacion_porcentual_rs_i,
					     'programado_acumulado'=>$ld_programado_acumulado_rs_i,
					     'ejecutado_acumulado'=>$ld_ejecutado_acumulado_rs_i);
		
		uf_print_titulo_reporte($li_mesdes,$io_pdf);
		uf_print_titulo($li_mesdes,$io_pdf);
		uf_print_cabecera($io_pdf);
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		unset($la_data_resultado);
		if($z<$li_total)
		{
		 $io_pdf->ezNewPage(); // Insertar una nueva página
		}
		$io_pdf->ezStopPageNumbers(1,1);
		
		if (isset($d) && $d)
		{
			$ls_pdfcode = $io_pdf->ezOutput(1);
			$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
			echo '<html><body>';
			echo trim($ls_pdfcode);
			echo '</body></html>';
		}
		else
		{
			$io_pdf->ezStream();
		}
		unset($io_pdf);
	}//else
	unset($io_report);
	unset($io_funciones);
?> 