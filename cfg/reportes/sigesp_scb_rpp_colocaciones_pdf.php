<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();";		
		print "close();";
		print "</script>";		
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T?tulo del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci?n que imprime los encabezados por p?gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci?n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,515,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$as_titulo = $as_titulo;
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=470-($li_tm/2);
		$io_pdf->addText($tm,570,15,"<b>".$as_titulo."</b>"); // Agregar el t?tulo
		$io_pdf->addText(800,580,10,date("d/m/Y")); // Agregar la Fecha
		$tm=406-($li_tm/2);
		/*if(($ls_nomban!="")&&($ls_ctaban!=""))		
		{
			$ls_den="<b>BANCO :</b>       ".$ls_nomban."                                  <b>CUENTA: </b>".$ls_ctaban;
			$io_pdf->addText(260,525,9,$ls_den); // Agregar el t?tulo	
		}*/
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: as_numdoc // N?mero del documento
		//	    		   as_conmov // concepto del documento
		//	    		   as_nomproben // nombre del proveedor beneficiario
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci?n que imprime la cabecera de cada p?gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci?n: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('banco'=>'<b>Proyecto</b>','capital'=>'<b>Capital</b>','emision'=>'<b>Fecha Emisi?n</b>','vencimiento'=>'<b>Fecha Vencimiento</b>','dias'=>'<b>Dias</b>',
		 				   'tasa'=>'<b>Tasa</b>','rendimiento'=>'<b>Rendimiento</b>','conjunto'=>'<b>Conjunto</b>','nroexp'=>'<b>N? Expediente</b>','ctacedente'=>'<b>Cuenta Cedente</b>',
						   'concepto'=>'<b>Concepto Colocaci?n</b>','tipocol'=>'<b>Entidad</b>');
		 $la_config=array('showHeadings'=>1, // Mostrar encabezados
			 'fontSize' => 8, // Tama?o de Letras
			 'showLines'=>1, // Mostrar L?neas
			 'shaded'=>2, // Sombra entre l?neas
			 'shadeCol2'=>array(0.95,0.95,0.95), // Color de la sombra
			 'shadeCol'=>array(1.5,1.5,1.5), // Color de la sombra
			 'width'=>580, // Ancho de la tabla
			 'maxWidth'=>580, // Ancho M?ximo de la tabla
			 'xOrientation'=>'center', // Justificaci?n y ancho de la columna
			 'cols'=>array('banco'=>array('justification'=>'center','width'=>90), // Justificaci?n y ancho de la columna
			   			   'capital'=>array('justification'=>'center','width'=>80), // Justificaci?n y ancho de la columna
			 			   'emision'=>array('justification'=>'center','width'=>60), // Justificaci?n y ancho de la columna
			 			   'vencimiento'=>array('justification'=>'center','width'=>60), // Justificaci?n y ancho de la columna
			 			   'dias'=>array('justification'=>'center','width'=>35), // Justificaci?n y ancho de la columna
						   'tasa'=>array('justification'=>'center','width'=>65), // Justificaci?n y ancho de la columna
						   'rendimiento'=>array('justification'=>'center','width'=>70), // Justificaci?n y ancho de la columna
						   'conjunto'=>array('justification'=>'center','width'=>55), // Justificaci?n y ancho de la columna
						   'conjunto'=>array('justification'=>'center','width'=>70), // Justificaci?n y ancho de la columna
						   'ctacedente'=>array('justification'=>'center','width'=>130), // Justificaci?n y ancho de la columna
						   'concepto'=>array('justification'=>'center','width'=>150), // Justificaci?n y ancho de la columna
						   'tipocol'=>array('justification'=>'center','width'=>80))); // Justificaci?n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function restaFechas($dFecIni, $dFecFin)
	{
		$dFecIni = str_replace("-","",$dFecIni);
		$dFecIni = str_replace("/","",$dFecIni);
		$dFecFin = str_replace("-","",$dFecFin);
		$dFecFin = str_replace("/","",$dFecFin);
		ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
		ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);
		$date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
		$date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);
		return round(($date2 - $date1) / (60 * 60 * 24));	}
	// end function restaFechas
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/sigesp_include.php");
    $sig_inc   = new sigesp_include();
	$con       = $sig_inc->uf_conectar();
	require_once("../../scb/reportes/sigesp_scb_report.php");
	$class_report=new sigesp_scb_report($con);
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_datastore.php");
	$ds_colocacion=new class_datastore();	
	$io_sql    = new class_sql($con);
	$ls_titulo = "Inventario de Colocaciones Bancarias ";
	//--------------------------------------------------  Par?metros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_titemp=$_SESSION["la_empresa"]["titulo"];
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_numcol=$_GET["colocacion"];
	
	$data=$class_report->uf_generar_estado_cuenta_colocacion_cfg($ls_codemp,$ls_numcol,$ls_codban,$ls_ctaban);
	$ds_colocacion->data=$data;
	error_reporting(E_ALL);

	$li_totrow=$ds_colocacion->getRowCount("numcol");
	
	if($li_totrow>0)
	{
		$ls_check=0;
		error_reporting(E_ALL);
		$io_pdf=new Cezpdf('LEGAL','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuraci?n de los margenes en cent?metros
		$io_pdf->ezStartPageNumbers(735,50,10,'','',1); // Insertar el n?mero de p?gina
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
				$ls_check=1;
				$ls_banco		= $ds_colocacion->getValue("nomban",$li_i);
				$ls_colocacion	= $ds_colocacion->getValue("numcol",$li_i);
				$ls_fecinicol   = $ds_colocacion->getValue("feccol",$li_i);
			    $ls_fecinicol   = $io_funciones->uf_convertirfecmostrar($ls_fecinicol);
			    $ls_fecfincol   = $ds_colocacion->getValue("fecvencol",$li_i);
			    $ls_fecfincol   = $io_funciones->uf_convertirfecmostrar($ls_fecfincol);
				$ls_diacol      = $ds_colocacion->getValue("diacol",$li_i);
				$ls_tascol   	= $ds_colocacion->getValue("tascol",$li_i);
				$ls_rendimiento = $ds_colocacion->getValue("monint",$li_i);
				$ls_monto       = $ds_colocacion->getValue("monto",$li_i);
				$ls_conjunto    = $ls_rendimiento+$ls_monto;
				$ls_ctabancedente = $ds_colocacion->getValue("ctaban",$li_i);
				$ls_concepto	= $ds_colocacion->getValue("dencta",$li_i);
				$ls_tipocol  	= $ds_colocacion->getValue("denominacion",$li_i);
				
			    $la_data[$li_i]=array('banco'=>$ls_banco,'capital'=>number_format($ls_monto,2,",","."),'emision'=>$ls_fecinicol,'vencimiento'=>$ls_fecfincol,'dias'=>$ls_diacol,'tasa'=>number_format($ls_tascol,2,",",".")." %",
									  'rendimiento'=>number_format($ls_rendimiento,2,",","."),'conjunto'=>number_format($ls_conjunto,2,",","."),'ctacedente'=>$ls_ctabancedente,
							          'nroexp'=>$ls_colocacion,'concepto'=>$ls_concepto,'tipocol'=>$ls_tipocol);
		      }
		uf_print_encabezado_pagina($ls_titulo,$io_pdf); // Imprimimos el encabezado de la p?gina
		uf_print_detalle($la_data,&$io_pdf);
		//$ldec_total=number_format($ldec_total,2,",",".");
		//uf_print_totales($ldec_total,&$io_pdf);
		if(($li_totrow>0)&&($ls_check=1)) // Si no ocurrio ning?n error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresi?n de los n?meros de p?gina
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo alg?n error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
		unset($io_funciones);
	}
	else
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
?> 